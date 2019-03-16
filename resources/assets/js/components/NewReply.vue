<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group" >
                <wysiwyg name="body" v-model="body" placeholder="Want to leave a reply?" :should-clear="completed"></wysiwyg>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default pull-right" @click="addReply">Post</button>
            </div>
        </div>

        <div v-else>
            <p class="text-center"><a href="/login">Please Sign In To Leave a Comment</a></p>
        </div>

    </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';
    export default {
        props: ['endpoint'],
        data(){
            return  {
                body: '',
                completed: false,
            }
        },
        computed:{
/*            signedIn() {
                return window.App.signedIn;
            }*/
        },
        mounted(){
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks:{
                    remoteFilter: function(query, callback){
                        $.getJSON("/api/users", {name: query}, function(username){
                            callback(username)
                        });
                    }
                }
            });
        },
        methods:{
            addReply(){
                axios.post(this.endpoint, { body: this.body})
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
                    .then(({data}) => {
                        this.body = '';
                        this.completed = true;
                        flash('Your reply has been posted');
                        this.$emit('created', data);
                    });
            }
        }
    }
</script>