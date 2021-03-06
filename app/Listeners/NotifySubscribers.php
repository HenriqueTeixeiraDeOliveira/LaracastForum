<?php

namespace App\Listeners;

use App\Events\ThreadReceiveNewReply;

class NotifySubscribers
{


    /**
     * Handle the event.
     *
     * @param  ThreadReceiveNewReply  $event
     * @return void
     */
    public function handle(ThreadReceiveNewReply $event)
    {
        $thread = $event->reply->thread;

        $thread->subscriptions
            ->where('user_id', '!=' ,$event->reply->user_id)
            ->each
            ->notify($event->reply);
    }
}
