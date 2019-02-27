<template>
    <div>
       <img :src="avatar" alt="" width="100" height="100">
       <h2>{{ profileUser.name }}</h2>
        <p v-text="'Account created at '+profileUser.created_at"></p>

        <form v-if="canBeUpdated" method="POST" :action="'/api/users/'+profileUser.id+'/avatar'" enctype="multipart/form-data">
            <input type="file" name="avatar" accept="image/*" @change="onChange">
        </form>
    </div>

</template>

<script>
    export default {
        props:['profile-user'],
        data(){
            return{
                avatar: this.profileUser.avatar_path,
            }
        },
        computed: {
            canBeUpdated() {
                return this.authorize(user => user.id === this.profileUser.id)
            }
        },
        methods:{
            onChange(e){
                if(!e.target.files.length) return;
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = e => {
                    this.avatar = e.target.result;
                };

                this.persist(file);

            },
            persist(file){
                let data = new FormData();
                data.append('avatar', file);
                axios.post(`/api/users/${this.profileUser.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded'));
            }
        }
    }
</script>

<style scoped>

</style>