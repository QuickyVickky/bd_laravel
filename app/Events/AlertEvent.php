<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AlertEvent implements ShouldBroadcast 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $title;
    public $icon;
    public $image;
    public $linkurl;

    public function __construct($title,$message, $icon='', $image= '', $linkurl='')
    {
      $this->message = $message;
      $this->title = $title;
      $this->icon = $icon;
      $this->image = $image;
      $this->linkurl = $linkurl;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */


    public function broadcastOn()
    {
        return new Channel(env("PUSHER_APP_CHANNELNAME"));
        //return ['mychanneltestc'];
    }

    public function broadcastAs()
    {
      return env("PUSHER_APP_EVENTNAME");
    }

}
