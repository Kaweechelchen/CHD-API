@extends('layout')

@section('content')

    <ul class="petitionsList">
    @foreach ($petitions as $key => $petition)

        <li class="col-md-9 offset-md-3 border-bottom petition" itemprop="owns" itemscope="" itemtype="http://schema.org/Code">

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
                        Updated <relative-time datetime="2016-11-28T18:51:43Z" title="28 Nov 2016, 19:51 CET">{{ $petition->status_updated_at }}</relative-time>
                    </span>
                </div>
            </div>
        </li>

    @endforeach
    </ul>

@stop
