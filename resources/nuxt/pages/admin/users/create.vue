<template>
  <div>
    <h1>Create user</h1>
        <UserGeneral ref="userGeneral"/>
        <button class="btn btn-success mt-3 mb-3" @click="createUser">Create</button>
  </div>
</template>

<script>
  import UserGeneral from "@/components/user/UserGeneral";
  import {User} from "@/assets/js/models/Users";

  export default {
    name: "userDetail",
    layout: "admin",
    components: { UserGeneral},
    async asyncData({store, params}) {
        if(params.id) {
            await store.dispatch('users/fetchUserDetail', params.id)
        }
        /*
      if (!store.state.users.userRoles.length || !store.state.users.userPermissions.length) {
        await store.dispatch('users/fetchUserRoles')
        await store.dispatch('users/fetchUserPermissions')
      }
         */
    },
    created() {
      this.$store.commit('SET_OBJ',
        {
          name: 'breadcrumbs',
          value: [
            {name: 'Users List', url: '/user/'},
            {name: 'User Detail', url: ''},
          ]
        })
    },
    destroyed() {
      this.$store.commit('users/SET_OBJ', {name: 'userDetail', value: new User()})
    },
    methods: {
      createUser() {
        if (!this.$refs.userGeneral.isFieldsFilled()) {
          this.$refs.userGeneral.$el.scrollIntoView()
          this.$toast.error('Fill required fields').goAway(3500)
          return
        }
        this.$store.dispatch('users/createUser');
      }
    }
  }
</script>

<style scoped>


</style>
