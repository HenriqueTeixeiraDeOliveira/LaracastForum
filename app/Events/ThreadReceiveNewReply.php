<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ThreadReceiveNewReply
{
    use Dispatchable, SerializesModels;

    public $reply;

    /**
     * ThreadReceiveNewReply constructor.
     * @param $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

}
