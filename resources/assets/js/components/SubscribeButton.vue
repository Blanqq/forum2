<template>
    <button v-if="isSubscribed" class=" btn btn-danger" @click="unsubscribe">Unsubscribe</button>
    <button v-else="isSubscribed" class=" btn btn-default" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        data(){
            return{
                isSubscribed: this.isSubscribed,
            }
        },
        methods:{
            subscribe(){
                axios.post(location.pathname+'/subscriptions');
                this.isSubscribed = true;
                flash('You Have subscribed this thread');


            },
            unsubscribe(){
                axios.delete(location.pathname+'/subscriptions');
                this.isSubscribed = false;
                flash('You Have deleted subscription for this thread');

            }
        }
    }
</script>

<style scoped>

</style>