<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeetingAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client_name;
    public $developer_id;
    public $meeting_uid;
    public $extend_duration;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($developer_id, $client_name, $meeting_uid, $extend_duration)
    {
        $this->developer_id = $developer_id;
        $this->client_name = $client_name;
        $this->meeting_uid = $meeting_uid;
        $this->extend_duration = $extend_duration;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('developer_room.' . $this->developer_id);
    }
}
