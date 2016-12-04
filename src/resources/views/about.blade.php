@extends('layout')

@section('title')
| About
@stop

@section('bodyClass')
@stop

@section('content')
    <div class="col-md-10 offset-md-1 about">
        <div class="col-md-4 hidden-sm-down">
            <img class="rounded img-fluid" title="Tezza 😘" src="/build/img/photo.jpg" />
            <i class="fa fa-hand-o-up" aria-hidden="true"></i> Current team<br />
            <i class="fa fa-twitter" aria-hidden="true"></i> <a href="https://twitter.com/FAQ" target="_blank">@FAQ</a><br />
        </div>
        <div class="col-md-8">
            Hello world =D<br />

            Special thanks go to:
            <ul>
                @foreach($peopleToThank as $person)
                    <li>
                        @if (isset($person['link']))
                            <a href="{{ $person['link'] }}" target="_blank">
                                {!! $person['name'] !!}
                            </a>
                        @else
                            {!! $person['name'] !!}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@stop
