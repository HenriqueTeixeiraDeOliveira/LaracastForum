@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">

                            <span class="flex">
                                <a href="{{ route('profile', $thread->creator) }}">
                                    {{ $thread->creator->name }}
                                </a> posted: {{$thread->title }}
                            </span>

                            @can ('update', $thread)
                                <form action="{{ $thread->path() }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endcan

                        </div>
                    </div>
                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>

                <replies @added="repliesCount++" @removed="repliesCount--"></replies>

            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            This thread was publish {{$thread->created_at->diffForHumans()}}
                            by <a href="#"> {{$thread->creator->name}}</a>, and currently
                            has <span v-text="repliesCount"></span> replies.
                        </p>
                        <p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo)}}"></subscribe-button>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </thread-view>
@endsection