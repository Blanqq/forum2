@extends('layouts.app')

@section('content')
    <div class="container">
        <search>        >
        </search>
        {{--:data-app-id="{{ config('scout.algolia.id') }}"
        :data-algolia-key="{{ config('scout.algolia.key') }}"--}}
        {{--<ais-instant-search app-id="latency" api-key="3d9875e51fbd20c7754e65422f7ce5e1" index-name="bestbuy">

            <ais-search-box></ais-search-box>


            <ais-hits>
                <template slot-scope="{ result }">
                    <p>
                        <ais-highlight :result="result" attribute-name="name"></ais-highlight>
                    </p>
                </template>
            </ais-hits>
        </ais-instant-search>--}}
    </div>

@endsection