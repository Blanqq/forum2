<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';
    export default {
        components: {Replies, SubscribeButton},

        props: ['dataThread'],
        data(){
            return{
                repliesCount: this.dataThread.replies_count,
                locked: this.dataThread.locked,
                channel: this.dataThread.channel,
                slug: this.dataThread.slug,
                title: this.dataThread.title,
                body: this.dataThread.body,
                editing: false
            }
        },
        methods: {
            lock(){
                axios.post('/locked-threads/'+this.slug);   //  axios[this.locked ? 'delete' : 'post']('/locked....
                this.locked = true;
            },
            unlock(){
                axios.delete('/locked-threads/'+this.slug);
                this.locked = false;
            },
            update(){
                axios.patch('/threads/'+this.channel.slug+'/'+this.slug, {
                    title: this.title,
                    body: this.body
                })
                    .catch(error => {
                    flash(error.response.data, 'danger');
                });
                this.editing = false;
                flash('Updated');
            },
            cancel(){
                this.editing = false;
                this.title = this.dataThread.title;
                this.body = this.dataThread.body;
            }
        }
    }
</script>