<template>
    <ais-instant-search :search-client="searchClient" index-name="threads">
        <ais-search-box />
        <ais-hits>
            <div slot="item" slot-scope="{ item }">
                <h2>{{ item.title }}</h2>
            </div>
        </ais-hits>
    </ais-instant-search>
</template>

<script>
    import algoliasearch from 'algoliasearch/lite';
    import 'instantsearch.css/themes/algolia-min.css';

    export default {
        data() {
            return {
                searchClient: algoliasearch(
                    'U0JENLY1JI',
                    '161c047b903d72bd7aa7142a02344157'
                ),
            };
        },
    };
</script>

<style>
    body {
        font-family: sans-serif;
        padding: 1em;
    }
</style>
