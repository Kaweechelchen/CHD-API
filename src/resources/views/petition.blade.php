@extends('layout')

@section('title')
| {{ $petition->name }}
@stop
@section('description')
{{ $petition->description }}
@stop

@section('content')

<div class="col-md-3 graphContainer">

    <h1 class="petitionNumber border-bottom">N° {{ $petition->number }}</h1>

    <div class="hidden-sm-down">
        <h4 class="stats-header center">Signatures de cette pétition</h4>
        <div class="graphContainer">
            <canvas class="graph" data-data="[{{ $stats['daily'] }}]"></canvas>
            <div class="right">
                {{ count($petition->signatures) }} / 4500 ({{ round((count($petition->signatures) / 4500 * 100), 1) }}%)
            </div>
        </div>

        <div class="stats grayOut">
            <div class="col-xs-6">
                <div class="weekly">
                    {{ $stats['day'] }}
                </div>
                <p class="label right grayOut"><nobr>/24 heures</nobr></p>
            </div>
            <div class="col-xs-6 right">
                <div class="weekly">
                    {{ $stats['week'] }}
                </div>
                <p class="label grayOut">/semaine</p>
            </div>
        </div>
        <a href="http://chd.lu/wps/portal/public/PetitionDetail?action=doPetitionDetail&id={{ $petition->id }}" target="_blank" class="btn btn-outline-info btn-sm" role="button" aria-disabled="true">Voir pétition sur chd.lu</a>
    </div>

</div>

<div class="col-md-9">

    <div class="row">
        <div class="col-xs-12 description">
            <h3>{{ $petition->description }}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 grayOut meta">
            {{--<span class="status" data-toggle="tooltip" data-placement="bottom" title="{{ date('j.m.Y H:i', strtotime($petition->status_updated_at)) }}">
                <i class="fa fa-certificate" aria-hidden="true"></i>
                {{ $petition->status }}
                &middot;
                <relative-time datetime="{{ $petition->status_updated_at }}" title="{{ date('j.m.Y H:i', strtotime($petition->status_updated_at)) }}">{{ date('j. M', strtotime($petition->status_updated_at)) }}</relative-time>
            </span>--}}
            <span class="authors" data-toggle="tooltip" data-placement="bottom" title="Auteur(s) de la petition: {{ $petition->authors }}">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    {{ $petition->authors }}
            </span>
            @if (count($petition->signatures) > 1)
                <span class="signatures" data-toggle="tooltip" data-placement="bottom" title="Nombre de signatures: {{ count($petition->signatures) }}">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    {{ count($petition->signatures) }}
                </span>
            @endif
        </div>
    </div>

    <br /><h3>Events:</h3>

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
                    <td>{!! nl2br($event->event) !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

@stop
