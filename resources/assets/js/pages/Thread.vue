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
                slug: this.dataThread.slug
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
            }
        }
    }
</script>