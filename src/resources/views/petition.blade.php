@extends('layout')

@section('content')
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

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Event</th>
            </tr>
        </thead>
        <tbody>
            @foreach($petition->events()->get() as $event)
                <tr>
                    <td><nobr>{!! substr($event->datetime,0,10) !!}</nobr></td>
                    <td>{!! $event->event !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
