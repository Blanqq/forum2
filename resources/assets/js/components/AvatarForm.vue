<template>
    <div>
        <div class="level">
            <img :src="avatar" alt="" width="100" height="100">
            <h1>{{ profileUser.name }}</h1>
        </div>
        <p v-text="'Account created at '+profileUser.created_at"></p>

        <form v-if="canBeUpdated" method="POST" :action="'/api/users/'+profileUser.id+'/avatar'" enctype="multipart/form-data">
            <avatar-upload name="avatar" @loaded="onLoad"></avatar-upload>
        </form>
    </div>

</template>

<script>
    import AvatarUpload from './AvatarUpload.vue';
    export default {
        props: ['profile-user'],
        components: { AvatarUpload },
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
            onLoad(avatar){
                this.avatar = avatar.src;
                this.persist(avatar.file);
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