@extends('layout')

@section('content')

@include('pagination')

<div class="col-md-3">

    <div class="graphContainer noPadding">
        <canvas class="graph" data-data="[
                @foreach ($weeklyStats as $key => $weeklyStat)
                    {{ $weeklyStat->count }} @if (!$loop->last) , @endif
                @endforeach
            ]" data-labels='[
                @foreach ($weeklyStats as $key => $weeklyStat)
                    "{{ date(' D, d.M H\h ', $weeklyStat->label) }}" @if (!$loop->last) , @endif
                @endforeach
            ]'></canvas>
    </div>

</div>

<div class="col-md-9">
    <ul class="petitionsList">
        @foreach ($petitions as $key => $petition)

            <li class="border-bottom petition" itemprop="owns" itemscope="" itemtype="http://schema.org/Code">

                <div class="row">
                    <div class="name">
                        <h3>
                            <a href="/petitions/{{ $petition->number }}" itemprop="name codeRepository">{{ $petition->name }}</a>
                        </h3>
                    </div>
                </div>

                <div class="row">
                    <div class="details">
                        <div class="col-md-9 grayOut noPadding description">
                            {{ $petition->description }}
                        </div>

                        <div class="col-md-3 graphContainer noPadding">
                            <canvas class="smallGraph" data-data="[10,200,700,2800,3000,3030,3050]"></canvas>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="grayOut meta">
                        <span class="authors">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                {{ $petition->authors }}
                            </span>
                        <span class="status">
                                <i class="fa fa-certificate" aria-hidden="true"></i>
                                {{ $petition->status }}
                                &middot;
                                <relative-time datetime="{{ $petition->status_updated_at }}" title="{{ date('j.m.Y H:i', strtotime($petition->status_updated_at)) }}">{{ date('j. M', strtotime($petition->status_updated_at)) }}</relative-time>
                            </span>
                    </div>
                </div>
            </li>

        @endforeach
    </ul>
</div>

@include('pagination')

@stop
