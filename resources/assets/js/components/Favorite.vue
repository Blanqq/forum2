<template>

    <button type="submit" :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],
        data() {
            return {
                count: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },
        computed:{
            classes(){
                return ['btn', this.isFavorited ? 'btn-danger' : 'btn-primary'];
            }
        },
        methods:{
            toggle(){
                if(this.isFavorited){
                    axios.delete('/replies/'+this.reply.id+'/favorites');
                    this.isFavorited = false;
                    this.count--;
                }else{
                    axios.post('/replies/'+this.reply.id+'/favorites');
                    this.isFavorited = true;
                    this.count++;
                }
            },
        }
    }
</script>