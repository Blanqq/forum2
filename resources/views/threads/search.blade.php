@extends('layouts.app')

@section('header')
@endsection

@section('content')
<search-view :data-app-id="'{{ config('scout.algolia.id') }}'"
             :data-algolia-key="'{{ config('scout.algolia.key') }}'" inline-template>
    <div class="container">
        <div class="row">
                <ais-instant-search :search-client="searchClient" index-name="threads">
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Search:
                            </div>
                            <div class="panel-body">
                                <ais-search-box></ais-search-box>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Channel:
                            </div>
                            <div class="panel-body">
                                <ais-refinement-list attribute="channel.name"></ais-refinement-list>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Threads that match Your search criteria
                            </div>
                            <div class="panel-body">
                                <ais-hits>
                                    <div slot-scope="{ items }">
                                        <h4 v-for="item in items">
                                            <a :href="item.path" v-text="item.title"></a>
                                        </h4>

                                    </div>
                                </ais-hits>
                            </div>
                        </div>
                    </div>
                </ais-instant-search>
        </div>
    </div>
</search-view>
@endsection