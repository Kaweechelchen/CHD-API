@extends('layout')

@section('content')
    @foreach ($petitions as $petition)
{{--
        <div class="row well">
            <div class="col-xs-2">
                <h1><a href="/petitions/{{ $petition->number }}">{{ $petition->number }}</a></h1>
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
        </div>--}}

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

    <div class="card-columns">

    @foreach ($petitions as $key => $petition)

        @if ($key % 3 == 0)


            <div class="card">
                <div class="card-img-top graph-bg">
                    <canvas class="smallGraph" data-data="[10,200,700,2800,3000,3030,3050]"></canvas>
                </div>
                <div class="card-img-overlay card-inverse">
                    <h4 class="card-title">{{$petition['number']}}</h4>
                </div>
                <div class="card-block">
                    <p class="card-text">{{$petition['description']}}</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>

        @endif

    @endforeach

    @foreach ($petitions as $key => $petition)

        @if ($key % 3 == 1)

            <div class="card card-block card-inverse card-primary text-xs-center">
                <blockquote class="card-blockquote">
                    <h4 class="card-title">{{$key}}</h4>
                    {{$petition['number']}}
                </blockquote>
            </div>

        @endif

    @endforeach

    @foreach ($petitions as $key => $petition)

        @if ($key % 3 == 2)

            <div class="card card-block card-inverse card-primary text-xs-center">
                <blockquote class="card-blockquote">
                    <h4 class="card-title">{{$key}}</h4>
                    {{$petition['number']}}
                </blockquote>
            </div>

        @endif

    @endforeach

    </div>

    <div style="width: 200px">
        <canvas class="smallGraph" data-data="[10,20,30]"></canvas>
        <canvas class="smallGraph" data-data="[10,200,300]"></canvas>
        <canvas class="smallGraph" data-data="[10,2000,3000]"></canvas>
    </div>

    {{--

    <div class="card-columns">
        <div class="card card-block card-inverse card-primary text-xs-center">
            <blockquote class="card-blockquote">
                <h4 class="card-title">1</h4>
                <footer>
                    <small>
                        Someone famous in <cite title="Source Title">Source Title</cite>
                    </small>
                </footer>
            </blockquote>
        </div>
        <div class="card card-block text-xs-center">
            <h4 class="card-title">2</h4>
            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
        </div>
        <div class="card card-block text-xs-right">
            <blockquote class="card-blockquote">
                <h4 class="card-title">3</h4>
                <footer>
                    <small class="text-muted">
              Someone famous in <cite title="Source Title">Source Title</cite>
            </small>
                </footer>
            </blockquote>
        </div>
        <div class="card card-block card-inverse card-primary text-xs-center">
            <blockquote class="card-blockquote">
                <h4 class="card-title">4</h4>
                <footer>
                    <small>
                        Someone famous in <cite title="Source Title">Source Title</cite>
                    </small>
                </footer>
            </blockquote>
        </div>
        <div class="card card-block text-xs-center">
            <h4 class="card-title">5</h4>
            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
        </div>
        <div class="card card-block text-xs-right">
            <blockquote class="card-blockquote">
                <h4 class="card-title">6</h4>
                <footer>
                    <small class="text-muted">
              Someone famous in <cite title="Source Title">Source Title</cite>
            </small>
                </footer>
            </blockquote>
        </div>
        <div class="card card-block card-inverse card-primary text-xs-center">
            <blockquote class="card-blockquote">
                <h4 class="card-title">7</h4>
                <footer>
                    <small>
                        Someone famous in <cite title="Source Title">Source Title</cite>
                    </small>
                </footer>
            </blockquote>
        </div>
        <div class="card card-block text-xs-center">
            <h4 class="card-title">8</h4>
            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
        </div>
        <div class="card card-block text-xs-right">
            <blockquote class="card-blockquote">
                <h4 class="card-title">9</h4>
                <footer>
                    <small class="text-muted">
              Someone famous in <cite title="Source Title">Source Title</cite>
            </small>
                </footer>
            </blockquote>
        </div>
        <div class="card card-block card-inverse card-primary text-xs-center">
            <blockquote class="card-blockquote">
                <h4 class="card-title">10</h4>
                <footer>
                    <small>
                        Someone famous in <cite title="Source Title">Source Title</cite>
                    </small>
                </footer>
            </blockquote>
        </div>
        <div class="card card-block text-xs-center">
            <h4 class="card-title">11</h4>
            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
        </div>
        <div class="card card-block text-xs-right">
            <blockquote class="card-blockquote">
                <h4 class="card-title">12</h4>
                <footer>
                    <small class="text-muted">
              Someone famous in <cite title="Source Title">Source Title</cite>
            </small>
                </footer>
            </blockquote>
        </div>
    </div>--}}
@stop
