<template>
        <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
            <div class="panel-heading">
                <div class="level">
                    <h5 class="flex">
                        Written:
                        {{ postedOn(dataReply) }}... by
                        <a :href="'/profiles/'+dataReply.owner.name" v-text="dataReply.owner.name"></a>
                    </h5>
                    <div v-if="signedIn">
                        <favorite :reply="dataReply"></favorite>
                    </div>
                </div>


            </div>
            <div class="panel-body">
                <div v-if="editing">
                    <form @submit.prevent="update"> <!--prevent because don't work in firefox-->
                        <div class="form-group">
                            <wysiwyg v-model="body"></wysiwyg>
                            <!--<textarea class="form-control" v-model="body" required></textarea>-->
                        </div>
                        <button class="btn btn-xs btn-primary">Update</button>
                        <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
                    </form>



                </div>
                <div v-else v-html="body">

                </div>
            </div>
            <!--<@can('update', $reply)-->
            <div class="panel-footer level" v-if="authorize('owns', dataReply) || authorize('owns', dataReply.thread)">
                <div v-if="authorize('owns', dataReply)">
                    <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                    <button class="btn btn-danger btn-xs" @click="destroy">Delete Reply</button>
                </div>

                <button class="btn btn-primary btn-xs ml-a"
                        @click="markBest"
                        v-if="authorize('owns', dataReply.thread) && !isBest">
                    Mark This Reply As Best
                </button>
            </div>
            <!--@endcan-->


        </div>
</template>

<script>
    import moment from 'moment';
    import Favorite from './Favorite.vue';
    export default {
        props: ['dataReply'],
        components: { Favorite },
        data() {
            return {
                editing: false,
                id: this.dataReply.id,
                body: this.dataReply.body,
                isBest: this.dataReply.isBest,
            };
        },
        computed:{
/*            signedIn(){
                return window.App.signedIn;
            },*/
/*            canUpdate(){
                return this.authorize(user => this.data.user_id == user.id);
                //return this.data.user_id == window.App.user.id;
            }*/
        },
        created(){
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id)
            });
        },
        methods:{
            update() {
                axios.patch('/replies/'+ this.dataReply.id, {
                    body: this.body
                })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
                this.editing = false;
                flash('Updated');
            },
            destroy(){
                axios.delete('/replies/'+this.dataReply.id);
                this.$emit('deleted', this.dataReply.id);
            },
            postedOn(data){
                return moment(data.created_at).fromNow();
            },
            markBest(){
                this.isBest = true;
                axios.post('/best-replies/'+this.dataReply.id);
                flash('Reply was marked as best');
                window.events.$emit('best-reply-selected', this.dataReply.id);
            }
        }
    }
</script>