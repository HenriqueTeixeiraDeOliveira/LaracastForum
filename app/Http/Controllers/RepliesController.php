<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Inspections\Spam;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{

    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        try {
            $this->validateReply();

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422 );
        }

        return $reply->load('owner');

    }

    /**
     * @param Reply $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {

        $this->authorize('update', $reply);


        try {
            $this->validateReply();

            $reply->update(request(['body']));

        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }

    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update',$reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    public function validateReply() {
        $this->validate(request(), ['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }

}
