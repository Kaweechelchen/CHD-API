@extends('layout')

@section('content')

@include('statsSidebar')

<div class="col-md-9">

    @include('pagination')
    <ul class="petitionsList">
        @foreach ($petitions as $key => $petition)

            <li class="border-bottom petition" itemprop="owns" itemscope="" itemtype="http://schema.org/Code">

                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-xs-12 name">
                                <h3>
                                    <a href="/{{ $petition->number }}" itemprop="name codeRepository">{{ $petition->name }}</a>
                                </h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 details">
                                <div class="col-md-9 grayOut noPadding description">
                                    {{ $petition->description }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 grayOut meta">
                                <span class="status">
                                    <i class="fa fa-certificate" aria-hidden="true"></i>
                                    {{ $petition->status }}
                                    &middot;
                                    <relative-time datetime="{{ $petition->status_updated_at }}" title="{{ date('j.m.Y H:i', strtotime($petition->status_updated_at)) }}">{{ date('j. M', strtotime($petition->status_updated_at)) }}</relative-time>
                                </span>
                                <span class="authors" title="Auteur(s) de la petition: {{ $petition->authors }}">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                        {{ $petition->authors }}
                                </span>
                                <span class="signatures" title="Nombre de signatures: {{ $petition->signature_count }}">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    {{ $petition->signature_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 graphContainer noPadding">
                        <canvas class="smallGraph" data-data="[10,200,700,2800,3000,3030,3050]"></canvas>
                    </div>
                </div>
            </li>

        @endforeach
    </ul>

    @include('pagination')

</div>

@stop
