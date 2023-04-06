<?php

namespace App\Http\Controllers;

use Mail;
use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Skills;
use App\Models\Project;
use App\Models\Question;
use App\Models\Settings;
use App\Models\BbbMeeting;
use Illuminate\Support\Str;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\ClientProject;
use App\Models\PdfPermission;
use App\Models\Questionnaire;
use App\Models\UserQuestionnaire;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CollaborativeProjects;
use App\Models\QuestionnaireResponse;
use App\Models\UserVerificationToken;
use App\Models\AnonymousClientProject;
use App\Models\Earnings;
use App\Models\ProjectQuestion;
use App\Models\ProjectTypes;
use App\Models\Skill;
use App\Models\ParentProjectType;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class ClientController extends Controller
{
    // Show Login Form
    public function showLogin()
    {

        #echo $random= hex2bin(substr(str_shuffle("0123456789abcdef"), 0, 16));
        /*$users = DB::table('users')->whereNull('uuid')->get();
        foreach($users as $user){
            # $string = hex2bin($user->password);
            $uuid = (string) Str::uuid();
            $string = hex2bin(str_replace('-', '', $uuid));
            DB::table('users')->where('id', $user->id)->update(['uuid' => $string]);
        }
        die("done");*/

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.clients');
            } elseif ($user->hasRole('client')) {
                return redirect()->route('client.projects');
            } elseif ($user->hasRole('freelancer')) {
                return redirect()->route('developer.projects');
            }
        }
        return view('auth.client.login');
    }

    // Show Register Form
    public function showRegister(Request $request)
    {
        if ($request->has('ref')) {
            // session(['referrer' => $request->query('ref')]);
            $request->session()->put('referral_token', $request->query('ref'));
        } else {
            $request->session()->forget('referral_token');
        }

        if ($request->has('anonymous_project')) {
            $request->session()->put('anonymous_project', $request->query('anonymous_project'));
        } else {
            $request->session()->forget('anonymous_project');
        }

        if ($request->has('project')) {
            $request->session()->put('project', $request->query('project'));
        } else {
            $request->session()->forget('project');
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.clients');
            } elseif ($user->hasRole('client')) {
                return redirect()->route('client.projects');
            } elseif ($user->hasRole('freelancer')) {
                return redirect()->route('developer.projects');
            }
        }
        return view('auth.client.register');
    }

    // Show Forgot Password Form
    public function showForgot()
    {
        return view('auth.client.forgot');
    }

    // Show Reset Password Form
    public function showReset($token)
    {
        return view('auth.client.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect()->route('client.login.form')->with('success', 'Your password has been changed!');
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $get_email = $request->post('email');
        $user_data = User::where('email', $get_email)->first();
        $uid = $user_data->id;
        $current_role = '3';

        $mhr = DB::table('model_has_roles')->where('model_id', $uid)->get();
        $has_roles = array();
        foreach ($mhr as $role) $has_roles[] = $role->role_id;

        if (in_array($current_role, $has_roles)) {
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send('emails.forgotPasswordClient', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            return back()->with('success', 'We have e-mailed your password reset link!');
        } else {
            return back()->with('error', 'Email Not Found!');
        }
    }

    // Register a new Client
    public function register(Request $request)
    {
        $referrer = User::where('referral_token', session()->get('referral_token'))->first();
        $project_name = $request->session()->get('project');
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'referrer_id'       => $referrer ? $referrer->id : null
        ]);

        if ($referrer) {
            $project = Project::where('user_id', $referrer->id)->where('user_created', 0)->where('client_id', null)->where('name', $project_name)->latest()->first();
            if ($project) {
                $project->client_id = $user->id;
                $project->user_created = 1;
                $project->update();
            } else {
                $project = new Project();
                $project->name = 'Untitled';
                $project->client_id = $user->id;
                $project->user_created = 1;
                $project->user_id = $referrer->id;
                $project->save();
            }
        }

        $role = Role::where('name', 'client')->first();
        if ($role) {
            $user->assignRole($role);
        } else {
            $role1 = Role::create(['name' => 'client']);
            $user->assignRole($role1);
        }

        $anonymous_project_ref = session()->get('anonymous_project');

        if (!empty($anonymous_project_ref)) {

            (new AnonymousClientProject())->registerClientToAnonymousProject($user, $anonymous_project_ref);
        }

        $token = Str::random(64);

        UserVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        // Send Email to Verify Email
        $details = [
            'token' => $token,
            'role'  => 'client',
            'email' => $user->email
        ];

        dispatch(new \App\Jobs\VerifyEmailJob($details));

        return redirect()->route('client.login.form')->with('success', 'A fresh verification link has been sent to your email address.');
    }

    // Register anonymous client
    public function anonymousRegisterApi(Request $request)
    {
        // return response()->json([session()->get('referral_token')]);
        $referrer = User::where('referral_token', $request->referral_token)->first();
        $project_name = $request->project;
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'referrer_id'       => $referrer ? $referrer->id : null
        ]);

        if ($referrer) {
            $project = Project::where('user_id', $referrer->id)->where('user_created', 0)->where('client_id', null)->where('name', $project_name)->latest()->first();
            if ($project) {
                $project->client_id = $user->id;
                $project->user_created = 1;
                $project->update();
            } else {
                $project = new Project();
                $project->name = 'Untitled';
                $project->client_id = $user->id;
                $project->user_created = 1;
                $project->user_id = $referrer->id;
                $project->save();
            }
        }

        $role = Role::where('name', 'client')->first();
        if ($role) {
            $user->assignRole($role);
        } else {
            $role1 = Role::create(['name' => 'client']);
            $user->assignRole($role1);
        }

        // $anonymous_project_ref = session()->get('anonymous_project');

        if ($request->has('anonymous_client')) {
            $anonymous_project = ClientProject::where('anonymous_user_id', $request->anonymous_client)->first();
            if($anonymous_project) {
                $anonymous_project->update(['user_id' => $user->id]);
            }
        }

        $token = Str::random(64);

        UserVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        // Send Email to Verify Email
        $details = [
            'token' => $token,
            'role'  => 'client',
            'email' => $user->email
        ];

        dispatch(new \App\Jobs\VerifyEmailJob($details));

        return response()->json(
            [
                'success'=>true,
                'error'=>'null',
                'status'=>'200',
                'message'=>'A fresh verification link has been sent to your email address.'
        ]);
    }
    // Register a new Client
    public function registerApi(Request $request)
    {
        return response()->json([session()->get('referral_token')]);
        $referrer = User::where('referral_token', session()->get('referral_token'))->first();
        $project_name = $request->session()->get('project');
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'referrer_id'       => $referrer ? $referrer->id : null
        ]);

        if ($referrer) {
            $project = Project::where('user_id', $referrer->id)->where('user_created', 0)->where('client_id', null)->where('name', $project_name)->latest()->first();
            if ($project) {
                $project->client_id = $user->id;
                $project->user_created = 1;
                $project->update();
            } else {
                $project = new Project();
                $project->name = 'Untitled';
                $project->client_id = $user->id;
                $project->user_created = 1;
                $project->user_id = $referrer->id;
                $project->save();
            }
        }

        $role = Role::where('name', 'client')->first();
        if ($role) {
            $user->assignRole($role);
        } else {
            $role1 = Role::create(['name' => 'client']);
            $user->assignRole($role1);
        }

        $anonymous_project_ref = session()->get('anonymous_project');

        if (!empty($anonymous_project_ref)) {

            (new AnonymousClientProject())->registerClientToAnonymousProject($user, $anonymous_project_ref);
        }

        $token = Str::random(64);

        UserVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        // Send Email to Verify Email
        $details = [
            'token' => $token,
            'role'  => 'client',
            'email' => $user->email
        ];

        dispatch(new \App\Jobs\VerifyEmailJob($details));

        return response()->json(
            [
                'success'=>true,
                'error'=>'null',
                'status'=>'200',
                'message'=>'A fresh verification link has been sent to your email address.'
        ]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        // Check if user is admin
        $user = User::where('email', $request->email)->first();
        $credentials = $request->only('email', 'password');
        if ($user) {
            if ($user->hasRole('admin')) {
                return back()->with('error', 'You are not authorized to access this page!');
            }

            if ($user->hasRole('freelancer')) {
                return redirect()->route('developer.login');
            }

            if ($user->hasRole('client')) {
                if (!$user->is_email_verified) {
                    $token = UserVerificationToken::where('user_id', $user->id)->first();

                    // Send Email to Verify Email
                    $details = [
                        'token' => $token->token,
                        'role'  => 'client',
                        'email' => $user->email
                    ];

                    dispatch(new \App\Jobs\VerifyEmailJob($details));
                    return back()->with('error', 'Your Email Address is not verified. Please check your Email for a fresh verification link.');
                }

                if (Auth::attempt($credentials)) {
                    if ($request->session()->has('url')) {
                        return redirect(session('url'));
                    }

                    return redirect()->route('client.projects');
                } else {
                    return back()->with('error', 'Credentials do not match!');
                }
            }
        }

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Credentials do not match!');
        }

        return redirect()->route('developer.login.form');
    }

    public function loginApi(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        // Check if user is admin
        $user = User::where('email', $request->email)->first();
        $credentials = $request->only('email', 'password');
        if ($user) {
            if ($user->hasRole('admin') || $user->hasRole('freelancer')) {
                return response()->json(
                    [
                        'success'=>false,
                        'status'=>'200',
                        'message'=>'You are not authorized to access this page!'
                ]);
            }
            if ($user->hasRole('client')) {
                if (!$user->is_email_verified) {
                    $token = UserVerificationToken::where('user_id', $user->id)->first();

                    // Send Email to Verify Email
                    $details = [
                        'token' => $token->token,
                        'role'  => 'client',
                        'email' => $user->email
                    ];

                    dispatch(new \App\Jobs\VerifyEmailJob($details));
                    return response()->json(
                        [
                            'success'=>false,
                            'status'=>'200',
                            'message'=>'Your Email Address is not verified. Please check your Email for a fresh verification link.'
                    ]);
                }

                if (Auth::attempt($credentials)) {
                    //return redirect()->route('client.projects');
                } else {
                    return response()->json(
                        [
                            'success'=>false,
                            'status'=>'200',
                            'message'=>'Credentials do not match!'
                    ]);
                }
            }
        }

        if (!Auth::attempt($credentials)) {
            return response()->json(
                [
                    'success'=>false,
                    'status'=>'200',
                    'message'=>'Credentials do not match!'
            ]);
        }

        if($request->has('anonymous_client')) {
            $anonymous_project = ClientProject::where('anonymous_user_id', $request->anonymous_client)->first();
            if($anonymous_project) {
                $anonymous_project->update(['user_id' => $user->id]);
            }
        }

        if($request->has('referral_token')) {
            $referrer = User::where('referral_token', $request->referral_token)->first();
            if(!$user->referrer_id) {
                $user->update(['referrer_id'       =>   $referrer ? $referrer->id : null]);
            }
        }

        return response()->json(
            [
                'success'=>true,
                'status'=>'200',
                'message'=>'Success'
        ]);
    }

    public function myProfile()
    {
        if (Auth::check()) {
            return view('client.profile.index');
        } else {
            return route('client.login.form');
        }
    }

    public function showSecuritySettings()
    {
        if (Auth::check()) {
            return view('client.profile.security-settings');
        } else {
            return route('client.login.form');
        }
    }

    public function showPaymentSettings()
    {
        if (Auth::check()) {
            return view('client.profile.payment-settings');
        } else {
            return route('client.login.form');
        }
    }

    // Update Profile
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'company' => 'required',
        ]);

        // Get current user
        $user = User::findOrFail(auth()->user()->id);
        $user->name = $request->name;
        $user->country_id = $request->country_id;
        $user->timezone = $request->timezone;
        $user->company = $request->company;
        $user->language = $request->language;
        // Persist user record to database

        $user->update();
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $user->clearMediaCollection('avatar');
            $user->addMedia($request->file('image'))->toMediaCollection('avatar');;
            ///$user->addMediaFromRequest('image')->toMediaCollection('avatar');
        }

        // Return user back and show a flash message
        return redirect()->back()->with(['success' => 'Profile updated successfully.']);
    }

    // Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required'],
            'new_password' => ['required'],
            'confirm_new_password' => ['same:new_password'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Current password does not match!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password successfully changed!');
    }

    // Update Payment Methods
    public function updatePaymentMethods(Request $request)
    {
        $request->validate([
            'paypal_email' => 'required|email',
            'payoneer_email' => 'nullable',
        ]);

        $user = Auth::user();

        $user->paypal_email = $request->paypal_email;
        $user->payoneer_email = !empty($request->payoneer_email) ? $request->payoneer_email : '';
        $user->save();

        return back()->with('success', 'Payment Information successfully changed!');
    }

    public function listQuestionnaires()
    {
        return view('client.questionnaires.index');
    }

    public function questionnaires()
    {
        $questionnaires = Questionnaire::with('skills')->with('project_types')->where(["questionnaires.status" => true])->get()->toArray();

        return response()->json(["questionnaires" => $questionnaires], 200);
    }

    public function showQuestionnaire(int $id)
    {
        $questionnaire = Questionnaire::with(['skills', 'project_types', 'questions'])->find($id);
        if (!$questionnaire) {
            return redirect()->route('client.questionnaires.list')->with(['errors' => __('frontend.questionnaire_not_found')]);
        }
        $questionnaire = $questionnaire->toArray();

        $is_connected = Auth::check();

        if ($is_connected == true) {
            $userQuestionnaire = UserQuestionnaire::where(['questionnaire_id' => $id, 'user_id' => Auth::user()->id, 'status' => 'pending'])->first();
            if($userQuestionnaire){
                $questions = QuestionnaireResponse::where('user_questionnaire_id', $userQuestionnaire->id)
                ->orderBy('question_id', 'ASC')->select('response AS fields', 'questionnaire_responses.*')->get()->groupBy('step')->toArray();
            } else {
                $questions = Question::where('questionnaire_id', $id)->orderBy('position', 'ASC')->get()->groupBy('step')->toArray();
            }
        }else {
            $questions = Question::where('questionnaire_id', $id)->orderBy('position', 'ASC')->get()->groupBy('step')->toArray();
        }

        $questions = json_encode($questions);
        $questionnaire = json_encode($questionnaire);

        return view('client.questionnaires.show', compact(['questionnaire', 'questions', 'id', 'is_connected']));
    }

    public function detailsQuestionnaire(int $id)
    {
        $questionnaire = Questionnaire::with(['skills', 'project_types', 'questions'])->find($id);
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }
        $questionnaire = $questionnaire->toArray();

        $questions = [];

        for ($i=1; $i <= Questionnaire::find($id)->step; $i++) {
            $questions[$i] = Question::where(['questionnaire_id' => $id, 'step' => $i])->orderBy('position', 'ASC')->get()->toArray();
        }

        return response()->json([
            "questionnaire" => $questionnaire,
            "questions" => $questions
        ], 200);
    }

    public function detailsProjectQuestions($id)
    {
        $client_project = ClientProject::select('client_projects.*', 'transactions.status')
            ->leftJoin('transactions', 'transactions.project_id',"=", 'client_projects.uuid')
            ->whereUuid($id, 'client_projects.uuid')
            ->first();
        $questionnaire = Questionnaire::with(['skills', 'project_types', 'questions'])->where('type_id', $client_project->type_id)->first();
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }

        $questions = [];

        for ($i=1; $i <= $questionnaire->step; $i++) {
            $questions[$i] = ProjectQuestion::where(['project_id' => $client_project->id, 'step' => $i])->orderBy('position', 'ASC')->get()->toArray();
        }

        $questionnaire = $questionnaire->toArray();
        return response()->json([
            "questionnaire" => $questionnaire,
            "questions" => $questions,
            "project" => $client_project->toArray()
        ], 200);
    }

    public function storeProjectQuestions(Request $request, $id)
    {
        $project = ClientProject::whereUuid($id, 'uuid')->first();
        // $questionnaire = Questionnaire::where('type_id', $project->type_id)->first();
        foreach($request->question_array_by_step as $step_questions) {
            foreach($step_questions as $question) {
                if($question['value']) {
                    $value = $question['value'];
                    if($question['fields']['type'] == 'checkbox-group') {
                        $value = json_encode($value);
                    }
                    ProjectQuestion::find($question['id'])->update([
                        'value' => $question['value']
                    ]);
                }
            }
        }

        $project->update(['left_step' => $request->left_step]);

        return response()->json([
            "success" => "sucess",
        ], 200);
    }

    public function projectPayment(Request $request, int $project_id) {
        $project = ClientProject::find($project_id);
        $user = auth()->user();
        $amount = $request->amount;

        $amountToCharge = intval($request->amount) * 100;
        $stripeCharge = auth()->user()->charge(
            $amountToCharge, $request->paymentMethod['id']
        );

        $now = new \DateTime();

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/stripe/'.$now->format('Y-m-d') .'.log'),
        ])->info($stripeCharge);

        $transaction = new Transactions();
        $transaction->user_id = $user->id;
        $transaction->type = $request->type;
        $transaction->project_id = $project->uuid;
        $transaction->bbb_meeting_id = null;
        $transaction->amount = $amount;
        $transaction->stripe_id = $request->paymentMethod['id'];
        $transaction->status = 2; // success
        $transaction->save();

        if($user->referrer_id) {
            $referral_amount = 0;
            $percentage = 5;
            $settings = Settings::first();
            if($settings) {
                $percentage = $settings->devProjectReferralCommission;
            }

            $referral_amount = ($amount * $percentage) / 100;

            $earning = new Earnings();
            $earning->transaction_id = $transaction->id ?? '0';
            $earning->type = '0';
            $earning->amount = $referral_amount;
            $earning->dev_id =  $user->referrer_id;
            $earning->status = '1';
            $earning->save();
        }

        return response()->json([
            "success" => "sucess",
        ], 200);
    }

    public function storeQuestionnaireResponse(Request $request, int $id)
    {
        if (!Auth::check()) {
            $request->session()->put('response', json_decode($request->response));
            $request->session()->put('is_connected', Auth::check());
            $request->session()->put('url', url()->current());
            $request->session()->save();

            return redirect()->route('client.login.form');

        } else {

            if ($request->session()->has('is_connected')) {

                $responses = session('response');

                // $validator = Validator::make($response, [
                //     'response.*.' => 'required|string'
                // ]);

                // if ($validator->fails()) {
                //     return redirect()->route('client.questionnaires.show', ['id' => $id])->with(['errors' => $validator->errors()]);
                // }

                $questionnaire = Questionnaire::find($id);
                if (!$questionnaire) {
                    return redirect()->route('client.questionnaires.show', ['id' => $id])->with(['errors' => __('frontend.questionnaire_not_found')]);
                }

                $user = User::find(Auth::user()->id);
                $userQuestionnaire = UserQuestionnaire::where(["user_id" => $user->id, "questionnaire_id" => $questionnaire->id, 'status' => 'pending'])->latest('created_at')->first();
                if (!$userQuestionnaire) {
                    UserQuestionnaire::create(["user_id" => $user->id, "questionnaire_id" => $questionnaire->id, 'status' => 'success']);
                    $userQuestionnaire = UserQuestionnaire::where(["user_id" => $user->id, "questionnaire_id" => $questionnaire->id, 'status' => 'success'])->latest('created_at')->first();
                } else {
                    return redirect()->route('client.questionnaires.show', ['id' => $id])->with(['errors' => __('frontend.user_questionnaire_already_open'), 'user_questionnaire' => $userQuestionnaire->toArray()]);
                }

                foreach ($responses as $step) {

                    foreach ($step as $response) {
                        $question_response = new QuestionnaireResponse();
                        $question_response->response = $response->fields;
                        $question_response->step = $response->step;
                        $question_response->user_questionnaire()->associate($userQuestionnaire);
                        $question_response->question()->associate(Question::find($response->id));
                        $question_response->save();
                    }
                }

                $questionnaire_response_count = QuestionnaireResponse::where(["user_questionnaire_id" => $userQuestionnaire->id])->count();
                $default_price_question = Settings::first() ? Settings::first()->defaultQuestionPrice : 0;
                $total = $questionnaire_response_count * $default_price_question;

                $userQuestionnaire->update(['total' => $total]);

                $request->session()->forget('response');
                $request->session()->forget('is_connected');
                $request->session()->forget('url');

            } else {

                $questionnaire = Questionnaire::find($id);
                if (!$questionnaire) {
                    return back()->with(['errors' => __('frontend.questionnaire_not_found')]);
                }

                $user = User::find(Auth::user()->id);

                $userQuestionnaire = UserQuestionnaire::where(["user_id" => $user->id, "questionnaire_id" => $questionnaire->id, 'status' => 'pending'])->latest('created_at')->first();
                if (!$userQuestionnaire) {
                    return back()->with(['errors' => __('frontend.user_questionnaire_not_found')]);
                }
                $userQuestionnaire->status = 'success';

                $questionnaire_response_count = QuestionnaireResponse::where(["user_questionnaire_id" => $userQuestionnaire->id])->count();
                $default_price_question = Settings::first() ? Settings::first()->defaultQuestionPrice : 0;
                $total = $questionnaire_response_count * $default_price_question;

                $userQuestionnaire->total = $total;
                $userQuestionnaire->save();
            }

            return redirect()->route('client.questionnaire.payment', ['id' => $questionnaire->id, 'user_questionnaire_id' => $userQuestionnaire->id]);
        }
    }

    public function storeQuestionResponse(Request $request, int $questionnaire_id, int $question_id)
    {
        $validator = Validator::make($request->all(), [
            'responses' => 'required|json'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $questionnaire = Questionnaire::find($questionnaire_id);
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }

        $question = Question::find($question_id);
        if (!$question) {
            return response()->json(['errors' => __('frontend.question_not_found')], 404);
        }

        $user = User::find(Auth::user()->id);

        $userQuestionnaire = UserQuestionnaire::where(["user_id" => $user->id, "questionnaire_id" => $questionnaire->id, 'status' => 'pending'])->latest('created_at')->first();
        if (!$userQuestionnaire) {
            $userQuestionnaire = new UserQuestionnaire();
            $userQuestionnaire->user_id = $user->id;
            $userQuestionnaire->questionnaire_id = $questionnaire->id;
            $userQuestionnaire->status = 'pending';
            $userQuestionnaire->save();

            $userQuestionnaire = UserQuestionnaire::where(["user_id" => $userQuestionnaire->user_id, "questionnaire_id" => $userQuestionnaire->questionnaire_id, 'status' => 'pending'])->first();

            foreach ($questionnaire->questions as $quest) {
                $question_response = new QuestionnaireResponse();
                $question_response->response = $quest->fields;
                $question_response->step = $quest->step;
                $question_response->user_questionnaire_id = $userQuestionnaire->id;
                $question_response->question()->associate($quest);
                $question_response->save();
            }
        }

        $response = json_decode($request->responses);
        $question_response = QuestionnaireResponse::where(['question_id' => $question->id, 'user_questionnaire_id' => $userQuestionnaire->id])->latest('created_at')->first();
        if ($question_response != null) {
            $question_response->response = $response->fields;
            $question_response->step = $response->step;
            $question_response->save();
        } else {
            $question_response = new QuestionnaireResponse();
            $question_response->response = $response->fields;
            $question_response->step = $response->step;
            $question_response->user_questionnaire_id = $userQuestionnaire->id;
            $question_response->question()->associate($question);
            $question_response->save();
        }

        return response()->json(["message" => __('frontend.response_save')], 200);
    }

    public function viewPayment(Request $request, int $id, int $user_questionnaire_id)
    {
        $questionnaire = Questionnaire::find($id);
        if (!$questionnaire) {
            return back()->with(['errors' => __('frontend.questionnaire_not_found')]);
        }

        $userQuestionnaire = UserQuestionnaire::find($user_questionnaire_id);
        if (!$userQuestionnaire) {
            return back()->with(['errors' => __('frontend.user_questionnaire_not_found')]);
        }

        $user = User::find(Auth::user()->id);

        $intent = auth()->user()->createSetupIntent();

        return view('client.questionnaires.payment', compact('questionnaire','userQuestionnaire', 'user', 'intent'));
    }

    public function pay(Request $request, Questionnaire $id)
    {
        $questionnaire = Questionnaire::findOrFail($request->get('questionnaire'));
        $user_questionnaire = UserQuestionnaire::findOrFail($request->get('user_questionnaire'));

        // $request->user()
        //     ->newSubscription('main', $plan->stripe_plan)
        //     ->create($request->stripeToken);

        $user          = $request->user();
        $paymentMethod = $request->input('payment_method');

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $stripeCharge = $user->charge($user_questionnaire->total * 100, $paymentMethod);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $now = new \DateTime();

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/stripe/' . $now->format('Y-m-d') . '.log'),
        ])->info($stripeCharge);

        $transaction = new Transactions();
        $transaction->user_id = Auth::id();
        $transaction->type = 2;
        $transaction->project_id = $user_questionnaire->uuid;
        $transaction->amount = $user_questionnaire->total;
        $transaction->stripe_id = $paymentMethod;
        $transaction->status = 2;
        $transaction->save();

        return back()->with('success', __('frontend.questionnaire_response_pay'));
    }

    // My Projects
    public function myProjects()
    {

        if (Auth::check()) {
            $user = Auth::user();
            $assigneid = CollaborativeProjects::where('assigned_user_email', $user->email)->get()->pluck('project_id');
            if ($assigneid) {
                $projects = ClientProject::select('client_projects.*', DB::raw('HEX(client_projects.uuid) As BLOBText'))->where('user_id', Auth::id())->orWhereIn('id', $assigneid)->get();
            } else {
                $projects = ClientProject::select('client_projects.*', DB::raw('HEX(client_projects.uuid) As BLOBText'))->where('user_id', Auth::id())->get();
            }

            foreach($projects as $project) {
                $project->full_access = 1;
            }

            $project_ids = clone $projects;
            $project_ids = $project_ids->pluck('id')->toArray();
            $pdf_projects = PdfPermission::where('user_id', $user->id)->pluck('project_id')->toArray();
            $newProjects = collect();

            foreach($projects as $project) {
                $newProjects->push($project);
            }

            foreach($pdf_projects as $pdf_project) {
                if(!in_array($pdf_project, $project_ids)) {
                    $project = ClientProject::select('client_projects.*', DB::raw('HEX(client_projects.uuid) As BLOBText'))->where('id', $pdf_project)->first();
                    $project->can_view_pdf = 1;
                    if($project) {
                        $newProjects->push($project);
                    }
                }
            }
            $projects = $newProjects;
            /* Remove locked projects */
            $affected = DB::table('client_projects')->where('user_id', Auth::id())->update(['editing_user_id' => NULL]);
            /* Get Client Users */
            $userlist   = DB::table('users')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->where('model_has_roles.role_id', 3)
                ->where('users.email', '!=', Auth::id())
                ->get();
            $user = User::where('id', '!=', Auth::id())->orderBy('email')->get();
            $skills = Skill::get();
            $parentProjectTypes = ParentProjectType::with('projectTypes')->get();
            $questionnaires = Questionnaire::get();
            $ids = [];
            foreach($questionnaires as $item)
            {
                array_push($ids, $item->type_id);
            }
            $subProjectTypes = ProjectTypes::whereIn('id', $ids)->get();
            return view('client.projects.index', compact(['projects', 'userlist', 'user', 'subProjectTypes','parentProjectTypes', 'skills']));
        } else {
            return route('client.login.form');
        }
    }

    public function createProjectByNewUser(Request $request){
        if(auth()->user()) {
            return redirect()->route('client.projects');
        }
        $ref_id = $request->get('ref');
        if(!isset($ref_id)) return redirect()->route('developer.login.form');
        $request->session()->forget('referral_id');
        $request->session()->put('referral_id', $ref_id);

        $dev = User::select(
            'users.*',
            DB::raw('COALESCE((SELECT COUNT(bbb_meetings.id) FROM bbb_meetings WHERE bbb_meetings.pending != 1 AND bbb_meetings.developer_id = users.id),-1) as total_calls'),
            DB::raw('COALESCE((SELECT ROUND(SUM(dev_call_ratings.stars)/COUNT(dev_call_ratings.stars),2) FROM dev_call_ratings WHERE dev_call_ratings.dev_id = users.id),0) as rating')
        )->role('freelancer')->where('referral_token',$ref_id)->first();

        if(!isset($dev)) return redirect()->route('developer.login.form');
        $dev_skills = '';
        if(is_array(json_decode($dev->skills))){
            foreach(json_decode($dev->skills) AS $one){
                $skill = Skills::where('id',$one)->first();
                if($dev_skills!='') $dev_skills .= ', ';
                if($skill) $dev_skills .= $skill->name;
            }

        }
        $metaTitle = '';
        $metaDescription = '';
        $metaImage = '';
        if(Settings::first() != NULL) {
            $metaTitle = Settings::first()->referralMetaTitle;
            // Variables: %NAME%, %EMAIL%, %SKILLS%, %RATING%, %TOTALCALLS%
            $metaTitle = str_replace("%NAME%",$dev->name,$metaTitle);
            $metaTitle = str_replace("%EMAIL%",$dev->email,$metaTitle);
            $metaTitle = str_replace("%SKILLS%",$dev_skills,$metaTitle);
            $metaTitle = str_replace("%RATING%",$dev->rating!=0?$dev->rating:"New",$metaTitle);
            $metaTitle = str_replace("%TOTALCALLS%",$dev->total_calls,$metaTitle);
            $metaDescription = Settings::first()->referralMetaDesc;
            $metaDescription = str_replace("%NAME%",$dev->name,$metaDescription);
            $metaDescription = str_replace("%EMAIL%",$dev->email,$metaDescription);
            $metaDescription = str_replace("%SKILLS%",$dev_skills,$metaDescription);
            $metaDescription = str_replace("%RATING%",$dev->rating!=0?$dev->rating:"New",$metaDescription);
            $metaDescription = str_replace("%TOTALCALLS%",$dev->total_calls,$metaDescription);
            $referralMetaImage = Settings::first()->referralMetaImage;
            if($referralMetaImage) {
                $metaImage = !empty($dev->getFirstMediaUrl('avatar', 'thumb')) ? $dev->getFirstMediaUrl('avatar', 'thumb') : asset('ui_assets/img/content-images/user-avatar.jpg') ;
            }else{
                $metaImage = Settings::first()->metaImage;
                $metaImage = asset('upload/'.$metaImage);
            }

        }
        $id = 136;
        return view('client.projects.new.createbynewuser', compact('metaTitle', 'metaDescription', 'metaImage', 'id', 'dev'));
    }

    // Show Create Project Form
    public function createProject(Request $request)
    {
        $request->session()->forget('formdata');
        $request->session()->forget('formloopdata');
        $request->session()->forget('current_project_id');
        $numberOfDevsAvailable=50;
        $callEnable = false;
        $pricePerCallMinute = 1;
        if(Settings::first() != NULL) {
            $numberOfDevsAvailable = Settings::first()->numberOfDevsAvailable??'50';
            //$callEnable = Settings::first()->calls;
            $pricePerCallMinute = Settings::first()->pricePerCallMinute;
        }

        $remainTime = BbbMeeting::select(
            DB::raw('SUM(IF(transactions.id IS NOT NULL,bbb_meetings.duration,0) - TIMESTAMPDIFF(MINUTE, bbb_meetings.start_time, IF(bbb_meetings.end_time IS NULL, bbb_meetings.start_time, bbb_meetings.end_time))-1)  AS remainTime')
        )
        ->join('transactions', 'transactions.bbb_meeting_id', '=', 'bbb_meetings.bbb_meeting_id', 'left')
        ->where('bbb_meetings.client_id', '=', Auth::id())
        ->orderBy('bbb_meetings.id', 'DESC')->first()->remainTime;


        $mode_type = 'create';
        return view('client.projects.create', compact('mode_type', 'numberOfDevsAvailable', 'callEnable','remainTime', 'pricePerCallMinute'));

    }

    // Show Create Project Form
    public function createProject_existing(Request $request)
    {
        $request->session()->forget('formdata');
        $request->session()->forget('formloopdata');
        $request->session()->forget('current_project_id');

        if (Auth::check()) {
            $mode_type = 'create';
            return view('client.projects_existing.create', compact('mode_type'));
        } else {
            return route('client.login.form');
        }
    }

    // Invite Client
    public function inviteClient(Request $request)
    {
        if ($request->id) {
            PdfPermission::where('project_id', $request->id)->delete();
            ClientProject::where('id', $request->id)->update(['is_shared' => 0]);
            return response()->json(true);
        }
        if ($request->mockup == 1) {
            PdfPermission::where('project_id', $request->project_id)->delete();
            ClientProject::where('id', $request->project_id)->update(['is_shared' => 0]);
        }
        if ($request->mockup == 0) {
            $user_email = $request->user_email;
            foreach ($user_email as $user) {
                $permission = new PdfPermission();
                $permission->project_id   = $request->project_id;
                $permission->user_id  = $user;
                $permission->shared_by  = Auth::id();
                $permission->save();
            }
            ClientProject::where('id', $request->project_id)->update(['is_shared' => 1]);
            if ($request->user_email) {
                // Send Email to invite Client
                foreach ($user_email as $mail) {
                    $user = User::where('id', $mail)->first();
                    $project_name = ClientProject::where('id', $request->project_id)->first();
                    $details = [
                        'project' => $project_name->name,
                        'user'    => Auth::user()->name,
                        'link'    => $request->pdf_link,
                    ];
                    \Mail::to($user->email)->send(new \App\Mail\ProjectPdfShare($details));
                }
            }
        }
        return back()->with('success', 'Project PDF shared Successfully!');
    }

    // Logout
    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('client.login.form');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function verifyAccount($token)
    {
        $verifyUser = UserVerificationToken::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
            return redirect()->route('client.login.form')->with('success', $message);
        }
        return redirect()->route('client.login.form')->with('error', $message);
    }

    public function callRoom($project_uuid)
    {
        if (Auth::check() && isset($project_uuid)) {
            $pricePerCallMinute = 1;
            $extendDuration = true;
            if(Settings::first() != NULL) {
                $pricePerCallMinute = Settings::first()->pricePerCallMinute;
                $extendDuration = Settings::first()->extendDuration;
            }

            $projects = ClientProject::where('user_id', Auth::id())->whereUuid($project_uuid, 'uuid')->get();
            if (count($projects) == 0) {
                return redirect()->route('client.projects');
            }
            $project = $projects[0];
            $meetingRooms = BBBMeeting::where('project_id', $project->id)->latest()->get();
            if (count($meetingRooms) == 0) {
                return redirect()->route('client.projects');
            }
            $meetingRoom = $meetingRooms[0];
            // Create BBB Meeting
            return view('client.projects.call-room', compact(['project', 'meetingRoom', 'pricePerCallMinute', 'extendDuration']));
        } else {
            return route('client.login.form');
        }
    }

    public function callDone($project_uuid)
    {
        return view('client.projects.call-done', compact(['project_uuid']));
    }

    public function calls(Request $request)
    {
        $perpage= 10;

        $calls = BbbMeeting::select(
            'client_projects.name as project_name',
            'bbb_meetings.duration',
            DB::raw('TIMEDIFF(bbb_meetings.end_time,bbb_meetings.start_time) AS diff_time'),
            'bbb_meetings.start_time',
            'bbb_meetings.end_time',
            'transactions.amount',
            'transactions.stripe_id',
            'bbb_meetings.pricePerMinute',
            'bbb_meetings.created_at'
        )
        ->join('client_projects', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
        ->join('transactions', 'transactions.bbb_meeting_id', '=', 'bbb_meetings.bbb_meeting_id', 'left')
        ->where('bbb_meetings.client_id', '=', Auth::id())
            ->orderBy('bbb_meetings.id', 'DESC')->paginate($perpage, ['*'], 'Pagination');

        $remainTime = BbbMeeting::select(
            DB::raw('SUM(IF(transactions.id IS NOT NULL,bbb_meetings.duration,0) - TIMESTAMPDIFF(MINUTE, bbb_meetings.start_time, IF(bbb_meetings.end_time IS NULL, bbb_meetings.start_time, bbb_meetings.end_time))-1)  AS remainTime')
        )
        ->join('transactions', 'transactions.bbb_meeting_id', '=', 'bbb_meetings.bbb_meeting_id', 'left')
        ->where('bbb_meetings.client_id', '=', Auth::id())
        ->orderBy('bbb_meetings.id', 'DESC')->first()->remainTime;
        $status = false;
        return view('client.calls', compact('calls','perpage','remainTime','status'));
    }

    public function skillsList()
    {
        $skills = Skill::get()->toArray();

        return response()->json(["skills" => $skills], 200);
    }

    public function projectTypesList()
    {
        $projectTypes = ParentProjectType::with('projectTypes')->get()->toArray();
        $questionnaires = Questionnaire::get();
        $ids = [];
        foreach($questionnaires as $item)
        {
            array_push($ids, $item->type_id);
        }
        $subProjectTypes = ProjectTypes::whereIn('id', $ids)->get()->toArray();
        return response()->json(["project_types" => $projectTypes, "sub_project_types"=>  $subProjectTypes], 200);
    }

    public function getProject($user_id)
    {
        $project = ClientProject::where('anonymous_user_id', $user_id)->first();
        return response()->json(["project" => $project], 200);
    }

    public function getSettings() {
        $settings = Settings::first();
        return response()->json(["success" => 1, "settings" => $settings], 200);
    }

    public function getAuthUser() {
        $user = auth()->user();
        return response()->json(["success" => 1, "user" => $user], 200);
    }

    public function getStripeKeys() {
        $key = env('STRIPE_KEY');
        $secret = env('STRIPE_SECRET');
        return response()->json(["success" => 1, "key" => $key, "secret" => $secret], 200);
    }
}