@extends('layout')

@section('title')
| About
@stop

@section('bodyClass')
@stop

@section('content')
    <div class="col-md-10 offset-md-1 about">
        <div class="col-sm-4 hidden-xs-down">
            <img class="rounded img-fluid" title="Tezza 😘" src="/build/img/photo.jpg" />
            <i class="fa fa-hand-o-up" aria-hidden="true"></i> Current team<br />
            <i class="fa fa-twitter" aria-hidden="true"></i> <a href="https://twitter.com/FAQ" target="_blank">@FAQ</a><br />
        </div>
        <div class="col-sm-8">
            <h2>Hello world,</h2>

            <p>
                The goal of this project is to make things easier for everybody.
            </p>
            <p>
                By <strong>things</strong> I mean the accessibility to the public petition data of the grand duchy of Luxembourg. There's a lot of data out there that (I think) isn't being processed in an understandable way. A lot of information can be read from the amount of signatures a petition gets as well as when those signatures appear online. The graphs next to the petitions are just the beginning of what can be done with the data.
            </p>
            <p>
                By <strong>everybody</strong> I mean every creature that the data from those petitions could be of use to. The first goal is to make the life of journalists easier by providing easier access to status changes or graphs. Next on the list are developers. I can't wait to see what else can be done with this data which isn't that easy to scrape from chd.lu... Setting up the API shouldn't be too much of a hassle, but I had to prioritise things to get this thing out on December 5th 2016 (28 years after I was born *hint*).
            </p>
            <h3>Credits:</h3>
            <p>
                Most data is from <a href="http://chd.lu" target="_blank">chd.lu</a>. The statistics are generated after every successful scrape (every hour, takes around 35-40 minutes).
            </p>
            <p>
                Special thanks to:
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
            </p>
        </div>
    </div>
@stop
