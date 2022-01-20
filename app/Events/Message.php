<?php

namespace App\Events;

class Message extends Notification
{
    public $sender_id;
    public $msg_body;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($receiver_id, $username, $avatar, $sender_id, $msg_body)
    {
        parent::__construct($receiver_id, $username, $avatar);
        $this->sender_id = $sender_id;
        $this->msg_body = $msg_body;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message';
    }
}
