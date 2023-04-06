<?php

use DeepCopy\DeepCopy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ClientProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Broadcast::routes([
    'middleware' => ['web'],
]);

Route::get('/', function () {
    return redirect()->route('developer.login.form');
});

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Auth::routes();


Route::get('api/verify-dev', [DeveloperController::class, 'verifyDev'])->name('api.verify-dev');
// Developer Routes
Route::prefix('freelancer')->name('developer.')->group(function () {

    // Authentication
    Route::get('login', [DeveloperController::class, 'showLogin'])->name('login.form');
    Route::post('login', [DeveloperController::class, 'login'])->name('login');

    Route::get('register', [DeveloperController::class, 'showRegister'])->name('register.form');
    Route::post('register', [DeveloperController::class, 'register'])->name('register');

    Route::get('forgot', [DeveloperController::class, 'showForgot'])->name('forgot.form');
    Route::post('forgot', [DeveloperController::class, 'forgot'])->name('forgot');

    Route::get('reset/{token}', [DeveloperController::class, 'showReset'])->name('reset.form');
    Route::post('reset', [DeveloperController::class, 'reset'])->name('reset');

    Route::get('account/verify/{token}', [DeveloperController::class, 'verifyAccount'])->name('user.verify');

    // Developer Routes
    Route::middleware(['authrole:freelancer'])->group(function () {

        Route::get('dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');

        Route::get('project_list', [DeveloperController::class, 'project_list'])->name('project_list');

        Route::get('projects', [DeveloperController::class, 'myProjects'])->name('projects');
        Route::post('invite-client', [DeveloperController::class, 'inviteClient'])->name('invite');

        Route::get('projects/edit/{project_uuid}/{type}', [ClientProjectController::class, 'edit'])->name('projects.edit');
        Route::get('projects_existing/edit/{project_uuid}/{type}', [ClientProjectController::class, 'edit_existing'])->name('projects_existing.edit');

        Route::get('payouts', [DeveloperController::class, 'mypayouts'])->name('payouts');

        // Request Payout
        Route::post('request-payout', [DeveloperController::class, 'requestPayouts'])->name('request.payout');
        Route::post('skill/suggest', [AdminController::class, 'suggestSkill'])->name('skill.suggest');

        Route::prefix('profile')->group(function () {

            // Profile Routes
            Route::get('/', [DeveloperController::class, 'myProfile'])->name('profile');
            Route::post('update', [DeveloperController::class, 'profileUpdate'])->name('profile.update');

            // Password Update
            Route::get('security-settings', [DeveloperController::class, 'showSecuritySettings'])->name('security.settings');
            Route::post('update-password', [DeveloperController::class, 'updatePassword'])->name('profile.update.password');

            // Payment Details
            Route::get('payment-settings', [DeveloperController::class, 'showPaymentSettings'])->name('payment.settings');
            Route::post('update-payment-method', [DeveloperController::class, 'updatePaymentMethods'])->name('profile.update.payment');
        });

        // BBB Meeting
        Route::get('call/{project_uuid}', [DeveloperController::class, 'callRoom'])->name('call');

        // Logout
        Route::post('logout', [DeveloperController::class, 'logout'])->name('logout');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication
    Route::get('login', [AdminController::class, 'showLogin'])->name('login.form');
    Route::post('login', [AdminController::class, 'login'])->name('login');

    Route::get('forgot', [AdminController::class, 'showForgot'])->name('forgot.form');
    Route::post('forgot', [AdminController::class, 'forgot'])->name('forgot');

    Route::get('reset/{token}', [AdminController::class, 'showReset'])->name('reset.form');
    Route::post('reset', [AdminController::class, 'reset'])->name('reset');


    // Admin Routes
    Route::middleware(['authrole:admin'])->group(function () {
        Route::get('clients', [AdminController::class, 'clients'])->name('clients');
        Route::get('client/{uuid}', [AdminController::class, 'client_view'])->name('client');
        Route::post('users/delete', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('user/changeStatus', [AdminController::class, 'changeStatus'])->name('user.changeStatus');

        Route::get('freelancers', [AdminController::class, 'developers'])->name('developers');
        Route::get('freelancer/{uuid}', [AdminController::class, 'developer_view'])->name('developer');
        Route::get('user/changeCallStatus', [AdminController::class, 'changeCallStatus'])->name('user.changeCallStatus');
        Route::get('recording/{uuid}', [AdminController::class, 'recording'])->name('recording');

        Route::get('projects', [AdminController::class, 'projects'])->name('projects');
        Route::get('project/{uuid}', [AdminController::class, 'project_view'])->name('project');
        Route::post('projects/delete', [AdminController::class, 'deleteProject'])->name('projects.delete');

        Route::get('projects/assign/{project_uuid}', [AdminController::class, 'projects_assign'])->name('projects.assign');
        Route::post('projects/assign/{project_uuid}', [AdminController::class, 'projects_assign_submit'])->name('projects.assign.submit');

        Route::get('venttedprojects', [AdminController::class, 'venttedprojects'])->name('venttedprojects');
        Route::get('recordings', [AdminController::class, 'recordings'])->name('recordings');

        Route::get('types', [AdminController::class, 'projectTypes'])->name('types');
        Route::post('type/changeStatus', [AdminController::class, 'changeStatusProjectType'])->name('type.changeStatus');
        Route::post('type/parent/changeStatus', [AdminController::class, 'changeParentStatusProjectType'])->name('type.parent.changeStatus');
        Route::post('type/delete', [AdminController::class, 'deleteProjectType'])->name('type.delete');
        Route::post('type/parent/delete', [AdminController::class, 'deleteParentProjectType'])->name('type.parent.delete');
        Route::post('type/store', [AdminController::class, 'storeProjectType'])->name('type.store');
        Route::patch('type/skills/store', [AdminController::class, 'storeSkills'])->name('type.skills.store');
        Route::post('type/parent/store', [AdminController::class, 'storeParentProjectType'])->name('type.parent.store');

        Route::get('skills', [AdminController::class, 'skills'])->name('skills');
        Route::post('skill/changeStatus', [AdminController::class, 'changeStatusSkill'])->name('skill.changeStatus');
        Route::post('skill/delete', [AdminController::class, 'deleteSkill'])->name('skill.delete');
        Route::post('skill/store', [AdminController::class, 'storeSkill'])->name('skill.store');

        // Vue Route
        Route::get('skills/list', [AdminController::class, 'skillsList'])->name('skills.list');
        // Route::get('project-types/list', [AdminController::class, 'projectTypesList'])->name('project-types.list');
        Route::get('project-types/list', [AdminController::class, 'adminProjectTypesList'])->name('project-types.list');

        Route::get('questionnaires/list', [AdminController::class, 'listQuestionnaires'])->name('list.questionnaires');
        Route::post('questionnaires/create', [AdminController::class, 'createQuestionnaireForm'])->name('create.questionnaire.form');
        Route::get('questionnaires/{id}/show', [AdminController::class, 'showQuestionnaireForm'])->name('show.questionnaire.form');
        Route::put('questionnaires/{id}/edit', [AdminController::class, 'editQuestionnaireForm'])->name('edit.questionnaire.form');
        Route::put('questionnaires/{id}/edit/status', [AdminController::class, 'editQuestionnaireFormStatus'])->name('edit.questionnaire.form.status');
        Route::delete('questionnaires/{id}/delete', [AdminController::class, 'deleteQuestionnaireForm'])->name('delete.questionnaire.form');

        Route::post('questionnaires/{id}/add-step', [AdminController::class, 'addStepToQuestionnaireForm'])->name('add.step.questionnaire.form');
        Route::post('questionnaires/{id}/remove-step', [AdminController::class, 'removeStepToQuestionnaireForm'])->name('remove.step.questionnaire.form');

        Route::get('questionnaires/{questionnaire_id}/questions/{step}', [AdminController::class, 'questionsQuestionnaireForm'])->name('questions.questionnaire.form');
        Route::post('question/create', [AdminController::class, 'createQuestionField'])->name('create.question.field');
        Route::get('question/{id}/show', [AdminController::class, 'showQuestionField'])->name('show.question.field');
        Route::put('question/{id}/edit', [AdminController::class, 'editQuestionField'])->name('edit.question.field');
        Route::delete('question/{id}/delete', [AdminController::class, 'deleteQuestionField'])->name('delete.question.field');

        Route::get('questionnaires', [AdminController::class, 'questionnaires'])->name('questionnaires');
        Route::post('questionnaire/changeStatus', [AdminController::class, 'changeStatusQuestionnaire'])->name('questionnaire.changeStatus');
        Route::post('questionnaire/delete', [AdminController::class, 'deleteQuestionnaire'])->name('questionnaire.delete');
        Route::get('questions', [AdminController::class, 'createQuestion'])->name('questions.create');

        // questionnaires views
        Route::get('questionnaire/{id}/questions/manage', [AdminController::class, 'manageQuestions'])->name('questions.manage');
        Route::get('question/{id}/update', [AdminController::class, 'editQuestion'])->name('question.edit');
        Route::get('questionnaires/display', [AdminController::class, 'displayQuestionnaire'])->name('questionnaire.display');
        //

        Route::get('settings/index', [AdminController::class, 'settings_index'])->name('settings.index');
        Route::post('settings/index', [AdminController::class, 'settings_index_submit'])->name('settings.index.submit');
        Route::get('settings/calls-settings', [AdminController::class, 'showCallsSettings'])->name('settings.calls');
        Route::get('settings/seo', [AdminController::class, 'seoSettings'])->name('settings.seo');

        Route::get('payouts', [AdminController::class, 'payouts'])->name('payouts');
        Route::get('payouts.accepted', [AdminController::class, 'acceptedPayouts'])->name('payouts.accepted');
        Route::get('payouts.rejected', [AdminController::class, 'rejectedPayouts'])->name('payouts.rejected');
        Route::get('payout_methods', [AdminController::class, 'payout_methods'])->name('payout_methods');
        Route::get('payouts/changeStatus', [AdminController::class, 'payout_methodsChangeStatus'])->name('payout_methods.changeStatus');
        Route::post('payout/action', [AdminController::class, 'payoutAction'])->name('payout.action');

        // Profile Routes
        Route::prefix('profile')->group(function () {

            // Profile Routes
            Route::get('/', [AdminController::class, 'myProfile'])->name('profile');
            Route::post('update', [AdminController::class, 'profileUpdate'])->name('profile.update');

            // Password Update
            Route::get('security-settings', [AdminController::class, 'showSecuritySettings'])->name('security.settings');
            Route::post('update-password', [AdminController::class, 'updatePassword'])->name('profile.update.password');

            // Payment Details
            Route::get('payment-settings', [AdminController::class, 'showPaymentSettings'])->name('payment.settings');
            Route::post('update-payment-method', [AdminController::class, 'updatePaymentMethods'])->name('profile.update.payment');
        });

        // Logout
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
    });
});

Route::get('', [ClientController::class, 'createProjectByNewUser'])->name('dev_ref');
Route::get('auth/user', [ClientController::class, 'getAuthUser'])->name('auth.user');

Route::get('stripe/keys', [ClientController::class, 'getStripeKeys'])->name('stripe.keys');
// Client Routes
Route::get('/settings', [ClientController::class, 'getSettings'])->name('settings.get');
Route::prefix('client')->name('client.')->group(function () {
    // Authentication
    Route::get('login', [ClientController::class, 'showLogin'])->name('login.form');
    Route::post('login', [ClientController::class, 'login'])->name('login');
    Route::post('login_api', [ClientController::class, 'loginApi'])->name('login_api');

    Route::get('register', [ClientController::class, 'showRegister'])->name('register.form');
    Route::post('register', [ClientController::class, 'register'])->name('register');
    // Route::post('anonymous/register', [ClientController::class, 'anonymousRegister'])->name('anonymous.register');
    Route::post('register_api', [ClientController::class, 'registerApi'])->name('register_api');
    Route::post('anonymous/register_api', [ClientController::class, 'anonymousRegisterApi'])->name('anonymous.register_api');

    Route::get('forgot', [ClientController::class, 'showForgot'])->name('forgot.form');
    Route::post('forgot', [ClientController::class, 'forgot'])->name('forgot');

    Route::get('reset/{token}', [ClientController::class, 'showReset'])->name('reset.form');
    Route::post('reset', [ClientController::class, 'reset'])->name('reset');

    Route::get('account/verify/{token}', [ClientController::class, 'verifyAccount'])->name('user.verify');
    Route::get('projects/generate-pdf/{project_uuid}', [ClientProjectController::class, 'generatePdf'])->name('projects.download.pdf');
    Route::get('projects/generate-pdf/public/{project_uuid}', [ClientProjectController::class, 'generatePublicPdf'])->name('projects.download.public.pdf');
    Route::get('projects_existing/generate-pdf/{project_uuid}', [ClientProjectController::class, 'generatePdf_existing'])->name('projects_existing.download.pdf');

    Route::get('projects/create', [ClientController::class, 'createProject'])->name('projects.create');
    Route::post('projects/store', [ClientProjectController::class, 'store'])->name('projects.store');
    Route::post('projects/store/new', [ClientProjectController::class, 'storeNew'])->name('projects.store.new');
    Route::get('projects/{id}/questions', [ClientProjectController::class, 'projectQuestions'])->name('projects.questions');

    // Vue Route
    Route::get('skills/list', [ClientController::class, 'skillsList'])->name('skills.list');
    Route::get('project-types/list', [ClientController::class, 'projectTypesList'])->name('project-types.list');
    Route::post('projects/anonymous/store', [ClientProjectController::class, 'storeAnonymous'])->name('projects.anonymous.store');
    Route::get('project/{user_id}', [ClientController::class, 'getProject'])->name('project.get');

    Route::get('questionnaires/list', [ClientController::class, 'listQuestionnaires'])->name('questionnaires.list');
    Route::get('questionnaires', [ClientController::class, 'questionnaires'])->name('questionnaires');

    Route::get('questionnaires/{id}/show', [ClientController::class, 'showQuestionnaire'])->name('questionnaires.show');
    Route::get('questionnaires/{id}', [ClientController::class, 'detailsQuestionnaire'])->name('questionnaires.details');
    Route::get('project/{id}/questions/details', [ClientController::class, 'detailsProjectQuestions'])->name('project.questions.details');
    Route::post('project/{id}/questions/store', [ClientController::class, 'storeProjectQuestions'])->name('project.questions.store');
    Route::post('project/{project_id}/pay', [ClientController::class, 'projectPayment'])->name('project.pay');

    Route::match(['get', 'post'], 'questionnaires/{id}/response', [ClientController::class, 'storeQuestionnaireResponse'])->name('questionnaires.response');
    // Route::post('questionnaires/{id}/response', [ClientController::class, 'storeQuestionnaireResponse'])->name('questionnaires.response');

    // Client Routes
    Route::middleware(['authrole:client'])->group(function () {

        Route::post('questionnaires/{questionnaire_id}/response/{question_id}', [ClientController::class, 'storeQuestionResponse'])->name('question.response');

        Route::get('questionnaires/{id}/payment/{user_questionnaire_id}/user-questionnaire', [ClientController::class, 'viewPayment'])->name('questionnaire.payment');

        Route::post('questionnaires/{id}/pay', [ClientController::class, 'pay'])->name('questionnaire.pay');

        Route::post('freelancers', [ClientProjectController::class, 'getDevelopers'])->name('developers');

        // Projects
        Route::get('projects', [ClientController::class, 'myProjects'])->name('projects');
        Route::post('invite-client', [ClientController::class, 'inviteClient'])->name('invite');

        Route::post('projects/adduser', [ClientProjectController::class, 'adduser'])->name('projects.adduser');
        Route::post('projects/adduserbyid', [ClientProjectController::class, 'adduserbyid'])->name('projects.adduserbyid');

        Route::get('projects_existing/create', [ClientController::class, 'createProject_existing'])->name('projects_existing.create');

        Route::get('projects/edit/{project_uuid}/{type}', [ClientProjectController::class, 'edit'])->name('projects.edit');
        Route::get('projects_existing/edit/{project_uuid}/{type}', [ClientProjectController::class, 'edit_existing'])->name('projects_existing.edit');

        Route::post('projects_existing/store', [ClientProjectController::class, 'store_existing'])->name('projects_existing.store');

        Route::post('projects/update/{project_uuid}', [ClientProjectController::class, 'update'])->name('projects.update');
        Route::post('projects_existing/update/{project_uuid}', [ClientProjectController::class, 'update_existing'])->name('projects_existing.update');

        Route::post('projects/stores', [ClientProjectController::class, 'stores'])->name('projects.stores');
        Route::post('projects/step2', [ClientProjectController::class, 'stepTwo'])->name('projects.step2');
        Route::post('projects/destroy', [ClientProjectController::class, 'destroy'])->name('projects.destroy');


        Route::get('projects/get/{project}', [ClientProjectController::class, 'getData']);

        // Generate PDF
        #Route::get('projects/generate-pdf/{project}', [ClientProjectController::class, 'generatePdf'])->name('projects.download.pdf');
        Route::get('projects/{project}', [ClientProjectController::class, 'show'])->name('projects.show');

        Route::prefix('profile')->group(function () {

            // Profile Routes
            Route::get('/', [ClientController::class, 'myProfile'])->name('profile');
            Route::post('update', [ClientController::class, 'profileUpdate'])->name('profile.update');

            // Password Update
            Route::get('security-settings', [ClientController::class, 'showSecuritySettings'])->name('security.settings');
            Route::post('update-password', [ClientController::class, 'updatePassword'])->name('profile.update.password');

            // Payment Details
            Route::get('payment-settings', [ClientController::class, 'showPaymentSettings'])->name('payment.settings');
            Route::post('update-payment-method', [ClientController::class, 'updatePaymentMethods'])->name('profile.update.payment');
        });

        Route::get('calls', [ClientController::class, 'calls'])->name('calls');

        // BBB Meeting
        Route::get('call/{project_uuid}', [ClientController::class, 'callRoom'])->name('call');

        //payment
        Route::post('finalize-payment', [PaymentController::class, 'singleChargePayment'])->name('payment.finalizePayment');
        Route::post('save-transaction', [PaymentController::class, 'saveTransaction'])->name('payment.saveTransaction');

        // Logout
        Route::post('logout', [ClientController::class, 'logout'])->name('logout');
    });

    Route::get('call-done/{project_uuid}', [ClientController::class, 'callDone'])->name('call.done');

});