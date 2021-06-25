<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AlertEventUserWeb implements ShouldBroadcast 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $title;
    public $icon;
    public $image;
    public $linkurl;
    public $userweb_id;

    public function __construct($title,$message, $icon='', $image= '', $linkurl='', $userweb_id)
    {
      $this->message = $message;
      $this->title = $title;
      $this->icon = $icon;
      $this->image = $image;
      $this->linkurl = $linkurl;
      $this->userweb_id = $userweb_id;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */


    public function broadcastOn()
    {
        return new Channel(env("PUSHER_APP_CHANNELNAME_USER_WEB").$this->userweb_id);
    }

    public function broadcastAs()
    {
      return env("PUSHER_APP_EVENTNAME_USER_WEB").$this->userweb_id;
    }

}
