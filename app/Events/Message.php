<?php

namespace App\Events;

class Message extends Notification
{
    public $msg_body;
    public $sender_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($receiver_id, $username, $avatar, $msg_body, $sender_id)
    {
        parent::__construct($receiver_id, $username, $avatar);
        $this->msg_body = $msg_body;
        $this->sender_id = $sender_id;
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
