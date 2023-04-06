<?php

namespace App\Http\Services;

use App\Models\BbbDefaultCreateSetting;
use App\Models\BbbDefaultJoinSetting;
use App\Models\BbbMeeting;
use App\Models\BbbRecording;

class MeetingService
{
	public function storeMeeting($data)
	{
		$data = array_merge($data, [
			'pending' => true,
			'start_time' => null,
			'end_time' => null,
			'create_params' => $this->getCreateMeetingDefaults(),
		]);

		return BbbMeeting::create($data);
	}

	public function getCreateMeetingDefaults()
	{
		$defaults = BbbDefaultCreateSetting::first();

		if($defaults) {

			$defaults = $defaults->toArray();

			unset($defaults['id'], $defaults['id'], $defaults['created_at'], $defaults['updated_at']);

			return $defaults;
		}

		return [];
	}

	public function getJoinMeetingDefaults()
	{
		$defaults = BbbDefaultJoinSetting::first();

		if($defaults) {

			$defaults = $defaults->toArray();

			unset($defaults['id'], $defaults['id'], $defaults['created_at'], $defaults['updated_at']);

			if(!empty($defaults)) {

				foreach($defaults as $key => $value) {

					if(strpos($key, 'userdata__') !== false) {

						$key_copy = $key;

						$key_copy = str_replace('userdata__', 'userdata-', $key_copy);

						$defaults[$key_copy] = $value;

						unset($defaults[$key]);
					}
				}
			}

			return $defaults;
		}

		return [];
	}

	public function storeRecording($data)
	{
		BbbRecording::create($data);
	}

	public function getProjectIdByBbbMeetingId($bbb_meeting_id)
	{
		$meeting = BbbMeeting::whereUuid($bbb_meeting_id, 'bbb_meeting_id')->first();

		return ($meeting) ? $meeting->project_id : null;
	}

}
