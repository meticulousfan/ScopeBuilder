<?php

namespace App\Http\Services;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;

class BigBlueButtonService
{
    protected $success = true;
    protected $message = '';
    protected $response_data = [];
    protected $bbb = null;

    public function createMeeting($meeting_data, $defaults = [])
    {
        $bbb = new BigBlueButton();
        $createMeetingParams = new CreateMeetingParameters($meeting_data['bbb_meeting_id'], $meeting_data['name']);
        $createMeetingParams->setAttendeePassword($meeting_data['attendee_pass']);
        $createMeetingParams->setModeratorPassword($meeting_data['moderator_pass']);

        $createMeetingParams->setRecord($defaults["record"]);
        $createMeetingParams->setAllowStartStopRecording($defaults["allowStartStopRecording"]);
        $createMeetingParams->setAutoStartRecording($defaults["autoStartRecording"]);
        $createMeetingParams->setMaxParticipants($defaults["maxParticipants"]);
        $createMeetingParams->setModeratorOnlyMessage($defaults["moderatorOnlyMessage"]);

      //  $createMeetingParams->setCustomParameter("bannerText", $defaults["bannerText"]);
        // $createMeetingParams->setCustomParameter("bannerColor", $defaults["bannerColor"]);
        // $createMeetingParams->setCustomParameter("learningDashboardEnabled", $defaults["learningDashboardEnabled"]);
        // $createMeetingParams->setCustomParameter("virtualBackgroundsDisabled", $defaults["virtualBackgroundsDisabled"]);
        // $createMeetingParams->setCustomParameter("endWhenNoModerator", $defaults["endWhenNoModerator"]);
        // $createMeetingParams->setCustomParameter("endWhenNoModeratorDelayInMinutes", $defaults["endWhenNoModeratorDelayInMinutes"]);
        // $createMeetingParams->setCustomParameter("disabledFeatures", $defaults["disabledFeatures"]);

        $createMeetingParams->setLogoutUrl(env("APP_URL") . "/client/call-done/" . $meeting_data['bbb_meeting_id']); // logout url
        // for staging server
        // $createMeetingParams->setEndCallbackUrl(urldecode(env("APP_URL") . "/endCallback"));
        // $createMeetingParams->setRecordingReadyCallbackUrl(urldecode(env("APP_URL") . "/api/meeting/recordingReady"));

        // for local test
        $createMeetingParams->setEndCallbackUrl(urldecode("https://roman.requestcatcher.com/endCallback")); // endcallback 
        $createMeetingParams->setRecordingReadyCallbackUrl(urldecode("https://roman.requestcatcher.com/api/meeting/recordingReady")); // recording ready callbak

        $response = $bbb->createMeeting($createMeetingParams);

        if ($response->getReturnCode() == 'FAILED') {
            $this->success = false;
            $this->message = $response->getMessage();
        } else {
            $this->success = true;
            $this->message = $response->getMessage();
        }

        if ($this->success) {
            $this->response_data["internalMeetingID"] = $response->getInternalMeetingId();
            $this->response_data["meetingID"] = $response->getMeetingId();
        }

        return $this->response();
    }

    public function getMeetingInfo($meeting_id, $password)
    {
        $meeting = $bbb->getMeetingInfo([
            'meetingID' => $meeting_id,
            'moderatorPW' => $password,
        ]);

        if ($meeting) {

            echo '<pre>';
            print_r($meeting->toArray());
            die;
        }

        die;
    }

    public function isMeetingStarted($meeting_id)
    {
        $bbb = new BigBlueButton();
        return (boolean) $bbb->isMeetingRunning(new IsMeetingRunningParameters($meeting_id));
    }

    public function joinMeeting($data, $defaults = array())
    {
        $bbb = new BigBlueButton();

        $joinMeetingParams = new JoinMeetingParameters($data['meeting_id'], $data['full_name'], $data['password']);
        $joinMeetingParams->setRedirect(true);
        $joinMeetingParams->setUserId($data['user_id']);
        // $joinMeetingParams->setCustomParameter("userdata-bbb_auto_join_audio", $defaults["userdata_bbb_auto_join_audio"] ? "true" : "false");
        // $joinMeetingParams->setCustomParameter("userdata-bbb_listen_only_mode", $defaults["userdata_bbb_listen_only_mode"] ? "true" : "false");
        // $joinMeetingParams->setCustomParameter("userdata-bbb_skip_check_audio", $defaults["userdata_bbb_skip_check_audio"] ? "true" : "false");
        // $joinMeetingParams->setCustomParameter("userdata-bbb_auto_share_webcam", $defaults["userdata_bbb_auto_share_webcam"] ? "true" : "false");
        // $joinMeetingParams->setCustomParameter("userdata-bbb_custom_style_url", $defaults["userdata_bbb_custom_style_url"]);
        // $joinMeetingParams->setCustomParameter("userdata-bbb_record_video", $defaults["userdata_bbb_record_video"]);
        // $joinMeetingParams->setCustomParameter("userdata-bbb_hide_nav_bar", $defaults["userdata_bbb_hide_nav_bar"]);
        // $joinMeetingParams->setCustomParameter("userdata-bbb_hide_presentation", $defaults["userdata_bbb_hide_presentation"]);

        return $bbb->getJoinMeetingURL($joinMeetingParams);
    }

    public function getRecordings($bbb_meeting_id, $record_id = false)
    {
        $bbb = new BigBlueButton();

        $recordingParams = new GetRecordingsParameters();
        $recordingParams->setMeetingId($bbb_meeting_id);

        if ($record_id) {
            $recordingParams->setRecordId($record_id);
        }

        $recordings = $bbb->getRecordings($recordingParams);

        return $recordings;
    }

    public function deleteRecording($record_id)
    {
        $response = $bbb->deleteRecordings([
            'recordID' => $record_id,
        ]);

        $response = $response->toArray();

        $this->success = false;

        if (isset($response['returncode']) && $response['returncode'] === 'SUCCESS') {

            $this->success = true;
        }

        return $this->response();
    }

    protected function xmlToArray($xmlString)
    {
        return $xmlString;

        $xml = simplexml_load_string($xmlString->getBody(), 'SimpleXMLElement', LIBXML_NOCDATA);

        return json_decode(json_encode($xml), true);
    }

    protected function response()
    {
        return [
            'success' => (boolean) $this->success,
            'message' => (string) $this->message,
            'data' => (array) $this->response_data,
        ];
    }

    protected function is_JSON($string)
    {

        return (is_null(json_decode($string))) ? false : true;
    }

}
