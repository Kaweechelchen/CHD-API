@extends('layout')

@section('content')
    @foreach ($petitions as $petition)

        <div class="row well">
            <div class="col-xs-2">
                <h1>{{ $petition->number }}</h1>
                {{ $status->find($petition->statuses()->orderBy('created_at', 'desc')->first()->status_id)->status }}
            </div>
            <div class="col-xs-8">
                <div class="row">
                    <h3>
                        {{ $petition->description }} &middot;
                        <small>
                            @foreach($petition->authors as $author)
                                {{ $author->name }}
                            @endforeach
                        </small>
                    </h3>
                </div>
            </div>
            <div class="col-xs-2">
                {{ count($petition->signatures) }} / 4500 ({{ round((count($petition->signatures) / 4500 * 100), 1) }}%)
            </div>
        </div>

        {{--<div class="row well">
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
                {{ count($petition->signatures) }}
            </div>
        </div>--}}
    @endforeach
@stop
