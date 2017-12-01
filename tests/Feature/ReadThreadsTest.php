<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Carbon\Carbon;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    function a_user_can_view_all_threads()
    {
        //dd($this->thread);
        $this->get('/threads')
             ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_a_single_thread()
    {

        $this->get($this->thread->path())
             ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // Given we have a thread (Done: Since we define a setUp function, we already have it.)
        // And that thread includes replies (Done: We just generated one reply that is associated our thread)
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())      // When we visit a thread page
             ->assertSee($reply->body);                      //  Then we should see the replies.

    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        //dd ($channel, $threadInChannel, $threadNotInChannel);

        $this->get('/threads/' . $channel->slug)
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);

        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
             ->assertSee($threadByJohn->title)
             ->assertDontSee($threadNotByJohn->title);

    }

    /** @test */
    function a_user_can_filter_threads_by_popularity()
    {
        //Given we have three threads

        $threadWithTwoReplies = create('App\Thread', ['created_at' => new Carbon('-2 minute')]);
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Thread', ['created_at' => new Carbon('-1 minute')]);
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;



        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3,2,0], array_column($response,'replies_count'));
    }


}
