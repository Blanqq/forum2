

<script>
    import algoliasearch from 'algoliasearch/lite';
    import 'instantsearch.css/themes/algolia-min.css';

    export default {
        props: ['dataAppId', 'dataAlgoliaKey'],
        data() {
            return {
                searchClient: algoliasearch(
                    this.dataAppId,
                    this.dataAlgoliaKey
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
