
export default {
    data() {
        return {
            items: []
        }
    },
    methods:{
        remove(index){
            this.items.splice(index, 1);
            this.$emit('removed');
            flash('Reply was deleted');
        },
        add(reply){
            this.items.push(reply);
            this.$emit('added');
            //flash('Reply was added');
        }
    }
}