<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    function guests_cannot_see_the_create_thread_page()
    {
        $this->withExceptionHandling()
             ->get('/threads/create')
             ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have a signed User
        $this->signIn();
        // When we hit the endpoint to create a new thread
        $thread = make('App\Thread');                       // Testing Helpers
        $this->post('/threads', $thread->toArray());
        // Then, when we visit the thread page
        $this->get($thread->path())
             ->assertSee($thread->title)
             ->assertSee($thread->body);
        // we should see the new thread
    }
}
