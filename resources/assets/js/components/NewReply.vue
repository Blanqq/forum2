<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group" >
                    <textarea name="body"
                              id="body"
                              class="form-control"
                              placeholder="Type your answer here"
                              rows="5"
                              required
                              v-model="body"></textarea>
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
    export default {
        props: ['endpoint'],
        data(){
            return  {
                body: '',
            }
        },
        computed:{
            signedIn() {
                return window.App.signedIn;
            }
        },
        methods:{
            addReply(){
                axios.post(this.endpoint, { body: this.body})
                    .then(({data}) => {
                        this.body = '';
                        flash('Your reply has been posted');
                        this.$emit('created', data);
                    });
            }
        }
    }
</script>