<?php

namespace App\Http\Controllers;

use App\Events\MeetingAlert;
use App\Http\Requests\Meeting\CreateMeetingRequest;
use App\Http\Requests\Meeting\EndMeetingRequest;
use App\Http\Requests\Meeting\JoinMeetingRequest;
use App\Http\Requests\Meeting\StartMeetingRequest;
use App\Http\Requests\Meeting\StatusMeetingRequest;
use App\Http\Services\BigBlueButtonService;
use App\Http\Services\MeetingService;
use App\Models\BbbDefaultCreateSetting;
use App\Models\BbbMeeting;
use App\Models\BbbMeetingParticipant;
use App\Models\ClientProject;
use App\Models\User;
use App\Models\Settings;
use App\Models\Earnings;
use App\Models\Transactions;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    protected $bbb;
    public function createDefaultSetting()
    {
        BbbDefaultCreateSetting::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'duration' => 3000,
                'record' => true,
                'autoStartRecording' => true,
                'allowStartStopRecording' => false,
                'maxParticipants' => 2,
            ]);
    }

    public function create(CreateMeetingRequest $request, MeetingService $dbService)
    {
        $data = $request->validated();

        $project = ClientProject::getByUuid($data['meeting_id']);

        if (!$project) {
            return response()->json([
                'status' => '404',
                'data' => [
                    'meeting_id' => $data['meeting_id'],
                ],
            ]);
        }
        $settings = Settings::first();

        $user = User::findOrFail($project->user_id);
        $client_name = $user->name;

        $data['project_id'] = $project->id;
        $data['client_id'] = $project->user_id;
        $data['name'] = $project->name;
        if($settings)  $data['pricePerMinute'] = $settings->pricePerCallMinute;
        $data['attendee_pass'] = "ap_" . strval(rand());
        $data['moderator_pass'] = "mp_" . strval(rand());
        $meeting = $dbService->storeMeeting($data);
        broadcast(new MeetingAlert($data['developer_id'], $client_name, $data['meeting_id'], 0))->toOthers();

        $transaction = Transactions::find( $data['transaction_id']);
        $transaction->bbb_meeting_id = $meeting['bbb_meeting_id'];
        $transaction->save();

        return response()->json([
            'status' => '200',
            'data' => [
                'meeting' => $meeting,
            ],
        ]);
    }

    public function start(StartMeetingRequest $request, BigBlueButtonService $bbbService, MeetingService $dbService)
    {
        $bbb = new BigBlueButton();
        $request_data = $request->validated();

        $meeting_data = BbbMeeting::whereUuid($request_data['meeting_id'], 'bbb_meeting_id')->first();

        if (!$meeting_data) {

            return response()->json(['status' => '422', 'message' => trans('frontend.meetingNotExist')]);
        }

        $defaults = $dbService->getCreateMeetingDefaults();

        $bbbResponse = $bbbService->createMeeting($meeting_data, $defaults);

        if ($bbbResponse['success'] == false) {
            return response()->json(['status' => '422', 'message' => $bbbResponse['message']]);
        }

        if (!isset($bbbResponse['messageKey']) || (isset($bbbResponse['messageKey']) && $bbbResponse['messageKey'] != 'duplicateWarning')) {
            $meeting_data->bbb_internal_meeting_id = $bbbResponse['data']['internalMeetingID'];
            $meeting_data->save();
        }

        return response()->json([
            'status' => '200',
            'data' => $bbbResponse['data']['meetingID'],
        ]);
    }

    public function join(JoinMeetingRequest $request, BigBlueButtonService $bbbService, MeetingService $dbService)
    {
        $data = $request->validated();
        $meetingData = BbbMeeting::whereUuid($data['meeting_id'], 'bbb_meeting_id')->first();

        if (!$meetingData) {
            return response()->json(['status' => '422', 'message' => trans('frontend.meetingNotExist')]);
        }

        $meetingStarted = $bbbService->isMeetingStarted($data['meeting_id']);

        $isClient = $data['client'];

        // if($isClient && $meetingStarted == false) {
        //     return response()->json([ 'status'=>'422','message' => trans('frontend.meetingNotStartedYet') ]);
        // }

        $user = User::findOrFail($data['user_id']);
        $data['user_id'] = $user->uuid;
        $data['full_name'] = $user->name;
        $data['password'] = $isClient ? $meetingData->attendee_pass : $meetingData->moderator_pass;

        $defaults = $dbService->getJoinMeetingDefaults();

        $bbbResponse = $bbbService->joinMeeting($data, $defaults);

        return response()->json([
            'status' => '200',
            'data' => [
                'meeting_url' => $bbbResponse,
            ],
        ]);
    }

    public function end(EndMeetingRequest $request, BigBlueButtonService $bbbService, MeetingService $dbService)
    {
        $data = $request->validated();

        $meetingData = BbbMeeting::whereUuid($data['meeting_id'], 'bbb_meeting_id')->first();

        if (!$meetingData) {
            return response()->json(['status' => '422', 'message' => trans('frontend.meetingNotExist')]);
        }
        $endTime = date("Y-m-d H:i:s");
        $meeting = BbbMeeting::find($meetingData['id']);
        $meeting->end_time = $endTime;
        $meeting->save();
        $diff = abs(strtotime($endTime) - strtotime($meetingData['start_time']));
        $time = ceil(($diff)/ (60));
        $transaction = Transactions::whereUuid( $meetingData['bbb_meeting_id'], 'bbb_meeting_id')->first();
        
        $earning = new Earnings();
        $earning->transaction_id = $transaction->id??'0';
        $earning->type = '1';
        $earning->amount = $time * $meetingData['pricePerMinute'];
        $earning->dev_id =  $meetingData['developer_id'];
        $earning->status = '1';
        $earning->save();

        $meetingStarted = $bbbService->isMeetingStarted($data['meeting_id']);

        $isClient = $data['client'];

        if (!$isClient && $meetingStarted == false) {
            return response()->json(['status' => '422', 'message' => trans('frontend.meetingNotStartedYet')]);
        }
        $user = User::findOrFail($data['user_id']);
        $project = ClientProject::whereUuid($data['meeting_id'], 'uuid')->where('user_id', $user->id)->get();
        if (!$project) {
            return response()->json(['status' => '422', 'message' => trans('frontend.projectNotExist'), 'data' => $project]);
        }

        $participants = array();
        $participants["meeting_id"] = $meetingData->id;
        $participants["user_id"] = $data['user_id'];
        BbbMeetingParticipant::create($participants);

        $bbb = new BigBlueButton();
        $endMeetingParams = new EndMeetingParameters($data['meeting_id'], $meetingData->moderator_pass);
        $response = $bbb->endMeeting($endMeetingParams);

        // if($bbbResponse == false) {
        //     return response()->json([ 'status'=>'422','message' => $bbbResponse ]);
        // }

        return response()->json([
            'status' => '200',
            'data' => trans('frontend.meetingEnded'),
        ]);
    }

    public function extend(Request $request, BigBlueButtonService $bbbService, MeetingService $dbService)
    {
        $data = $request->all();

        $project = ClientProject::getByUuid($data['meeting_id']);

        if (!$project) {
            return response()->json([
                'status' => '404',
                'data' => [
                    'meeting_id' => $data['meeting_id'],
                ],
            ]);
        }
        $user = User::findOrFail($project->user_id);
        $client_name = $user->name;

        broadcast(new MeetingAlert($data['developer_id'], $client_name, $data['meeting_id'], $data['extendDuration']))->toOthers();

        return response()->json([
            'status' => '200',
        ]);
    }

    public function status(StatusMeetingRequest $request, MeetingService $dbService)
    {
        $data = $request->validated();
        $meetingData = BbbMeeting::whereUuid($data['meeting_id'], 'bbb_meeting_id')->first();

        if (!$meetingData) {
            return response()->json(['status' => '422', 'message' => trans('frontend.meetingNotExist')]);
        }
        $bbb = new BigBlueButton();

        $bbbResponse = (boolean) $bbb->isMeetingRunning(new IsMeetingRunningParameters($data['meeting_id']));

        if ($meetingData->pending == 1) {
            $meetingData->start_time = now();
        }
        $meetingData->pending = false;
        $meetingData->save();

        return response()->json([
            'status' => '200',
            'data' => $bbbResponse,
        ]);
    }

    public function alertMsg(Request $request)
    {
        $meetingData = BbbMeeting::where('id', $request->post('meeting_id'))->first();
        
        if ($meetingData) {
            if ($request->post('client')) {
                $user = User::findOrFail($meetingData['developer_id']);
                $client_name = $user->name;
    
                $project = ClientProject::where('id', $meetingData['project_id'])->first();
                if ($project) {
                    $meeting_id = $project->uuid;
                    broadcast(new MeetingAlert($meetingData['client_id'], $client_name, $meeting_id, 0))->toOthers();
                }
            } else {
                $user = User::findOrFail($meetingData['client_id']);
                $client_name = $user->name;
    
                $project = ClientProject::where('id', $meetingData['project_id'])->first();
                if ($project) {
                    $meeting_id = $project->uuid;
                    broadcast(new MeetingAlert($meetingData['developer_id'], $client_name, $meeting_id, 0))->toOthers();
                }
            }
            
        }

        return response()->json([
            'status' => '200',
            'data' => 'success',
        ]);
    }

    public function recordings($bbb_meeting_id, BigBlueButtonService $bbbService)
    {
        $recordings = $bbbService->getRecordings($bbb_meeting_id);

        return response()->json([
            'status' => '200',
            'data' => $recordings,
        ]);
    }

    public function deleteRecording($record_id, BigBlueButtonService $bbbService)
    {
        $bbbResponse = $bbbService->deleteRecording($record_id);

        if ($bbbResponse['success'] == false) {

            return response()->json(['status' => '422', 'message' => $bbbResponse['message']]);
        }

        return response()->json([
            'status' => '200',
            'data' => trans('frontend.recording_deleted'),
        ]);
    }

    /**
     * Bbb Callback
     * meta_bbb-recording-ready-url
     */
    public function recordingReadyCallback(Request $request, BigBlueButtonService $bbbService, MeetingService $dbService)
    {
        if (!empty($request->signed_parameters)) {

            $decoded = JWT::decode($request->signed_parameters, new Key(env('BBB_SECRET'), 'HS256'));

            if (!empty($decoded)) {
                $decoded = (array) $decoded;

                if (!empty($decoded['meeting_id']) && !empty($decoded['record_id'])) {

                    $recording = $bbbService->getRecordings($decoded['meeting_id'], $decoded['record_id']);

                    if (!empty($recording)) {
                        $records = $recording->getRecords();

                        foreach ($records as $key => $record) {
                            $meetingData = BbbMeeting::whereUuid($decoded['meeting_id'], 'bbb_meeting_id')->first();
                            $result = array();
                            $result['project_id'] = $meetingData ? $meetingData->project_id : 1;
                            $result['bbb_meeting_id'] = $record->getMeetingId();
                            $result['bbb_record_id'] = $record->getRecordId();
                            $result['url'] = $record->getPlaybackUrl();
                            $result['start_time'] = date('Y-m-d', $record->getStartTime() / 1000);
                            

                            $dbService->storeRecording($result);
                        }

                    }
                }
            }
        }

        return response()->json([
            'status' => '200',
            'data' => trans('frontend.recording_stored'),
        ]);
    }

    public function index()
    {
        // Bigbluebutton::create([
        //     'meetingID' => '123456789',
        //     'meetingName' => 'test meeting',
        //     'attendeePW' => 'attendee',
        //     'moderatorPW' => 'moderator',
        //     'bbb-recording-ready-url'  => 'https://example.com/api/v1/recording_status',
        // ]);

        // return redirect()->to(
        //     Bigbluebutton::join([
        //        'meetingID' => '123456789',
        //        'userName' => 'disa',
        //        'password' => 'attendee' //which user role want to join set password here
        //     ])
        //    );

        return redirect()->to();

        $response = Bigbluebutton::create([
            'meetingID' => 'tamku',
            'meetingName' => 'test meeting',
            'attendeePW' => 'attendee',
            'moderatorPW' => 'moderator',
        ]);

        print_r($response);

        echo 'helper: ';
        dd(bigbluebutton()->isConnect());
        dd(BigBlueButton::isConnect());

        die;

        $apiUrl = config('bigbluebutton.BBB_SERVER_BASE_URL');
        $salt = config('bigbluebutton.BBB_SECURITY_SALT');
        $meetingId = "test_01";
        $goFurther = false;
        $isMeetingRunning = false;

        $bbb = new BigBlueButton($apiUrl, $salt);

        echo $version = $bbb->getApiVersion()->getVersion();die;

        $meetingRunningParams = new IsMeetingRunningParameters($meetingId);

        try {
            $response = $bbb->isMeetingRunning($meetingRunningParams);

            if ($response->success()) {
                $isMeetingRunning = $response->isRunning();
                $goFurther = true;
            } else {
                echo $response->getMessage() . "\n";
            }

        } catch (\Exception$e) {
            echo $e->getMessage() . "\n";
        }

        if (!$goFurther) {
            return;
        }
    }

    public function endCallback(Request $request)
    {
        $meetingID = $request->meetingID;
        $meetingData = BbbMeeting::whereUuid($meetingID, 'bbb_meeting_id')->first();
        if (!$meetingData) {
            return response()->json(['status' => '422', 'message' => trans('frontend.meetingNotExist')]);
        }

        $meetingData->end_time = now();
        $meetingData->save();

        return response()->json([
            'status' => '200',
            'data' => trans('frontend.meetingEnded'),
        ]);
    }

}
