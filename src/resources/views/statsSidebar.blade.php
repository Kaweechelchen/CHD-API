<div class="col-md-3 hidden-sm-down">

    <h4 class="stats-header center">Signatures sur toutes les pétitions</h4>

    <div class="graphContainer">
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

    <div class="stats grayOut">
        <div class="col-xs-12 dailyContainer">
            <div class="daily">
                {{ $stats['day'] }}
            </div>
            <p class="label right grayOut">/24 heures</p>
        </div>
        <div class="col-xs-6">
            <div class="weekly">
                {{ $stats['week'] }}
            </div>
            <p class="label grayOut">/semaine</p>
        </div>
        <div class="col-xs-6 right">
            <div class="monthly">
                {{ $stats['month'] }}
            </div>
            <p class="label grayOut">/mois</p>
        </div>
        {{--<div class="col-xs-6 offset-xs-6 right">
            <div class="total">
                {{ $stats['total'] }}
            </div>
            <p class="label grayOut">Total</p>
        </div>--}}
    </div>

</div>
