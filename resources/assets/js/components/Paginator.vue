<template>

    <ul class="pagination" v-if="shouldPaginate">
        <li class="page-item" v-show="prevUrl"><a class="page-link" href="#" rel="prev" @click.prevent="page--">Previous</a></li>
        <li :class="['page-item', isActivePage(i) ? activeClass : '']" v-for="i in dataSet.last_page" aria-current="page">
            <a v-if="isActivePage(i)" class="page-link">[ {{ i }} ]
                    <span class="sr-only">(current)</span>
            </a>
            <a v-else class="page-link" @click.prevent="page = i">{{ i }}</a>
        </li>
        <li class="page-item" v-show="nextUrl"><a class="page-link" href="#" rel="next" @click.prevent="page++">Next</a></li>
    </ul>

</template>

<script>
    export default {
        props: ['dataSet'],
        data(){
            return{
                page: 1,
                prevUrl: false,
                nextUrl: false,
                activeClass: 'active',
            }
        },
        watch:{
            dataSet(){
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },
            page(){
                this.broadcast().updateUrl();
            }
        },
        computed:{
            shouldPaginate(){
                return !!this.prevUrl || !!this.nextUrl;
            }

        },
        methods:{
            broadcast(){
                return this.$emit('changed', this.page);
            },
            updateUrl(){
                history.pushState(null, null, '?page='+this.page);
            },
            isActivePage(page){
                if(this.page === page){
                    return true;
                }
                else return false;
            }
        }
    }
</script>

<style scoped>

</style>