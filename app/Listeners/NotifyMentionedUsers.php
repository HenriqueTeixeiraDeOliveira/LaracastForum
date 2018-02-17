<?php

namespace App\Listeners;

use App\Events\ThreadReceiveNewReply;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceiveNewReply  $event
     * @return void
     */
    public function handle(ThreadReceiveNewReply $event)
    {
        $mentionedUsers = $event->reply->mentionedUsers();

        collect($event->reply->mentionedUsers())
            ->map(function($name){
                return User::where('name', $name)->first();
            })
            ->filter()                                  //filter without arguments get rid of all null values
            ->each(function ($user) use ($event){
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
}
