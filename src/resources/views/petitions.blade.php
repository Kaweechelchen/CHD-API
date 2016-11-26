@extends('layout')

@section('content')
    @foreach ($petitions as $petition)
        <div class="row well">
            <h4>{{ $petition->name }} | {{ $status->find($petition->statuses()->orderBy('created_at', 'desc')->first()->status_id)->status }} &middot;
            <small>
                @foreach($petition->authors as $author)
                    {{ $author->name }}
                @endforeach
            </small>
            </h4>
            <div class="col-sm-6">
                @foreach($petition->events as $event)
                    <dl class="dl-horizontal">
                        <dt>{{ $event->datetime }}</dt>
                        <dd>{{ substr($event->event, 0, 100) }}</dd>
                    </dl>
                @endforeach
            </div>
            <div class="col-sm-6">
                @foreach($petition->signatures as $signature)
                    {{ $signature }}
                @endforeach
            </div>
        </div>
    @endforeach
@stop
