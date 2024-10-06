<template>
  <div>
      <UserEditComponent class="mw1600" :settings_group="page" @changeSettingsPage="page = $event"></UserEditComponent>
      <!--PermissionComponent></PermissionComponent-->
  </div>
</template>

<script>
import isElementInteractible from "@/mixins/isElementInteractible";
import UserEditComponent from "@/components/user/UserEditComponent";
//import PermissionComponent from "@/components/user/PermissionComponent";
export default {
  name: "edit_user",
  mixins: [ isElementInteractible ],
  layout: "admin",
  middleware:[ 'auth' ],
  components: { UserEditComponent },
  async asyncData({ store, params }) {
    await store.dispatch('permissions/fetchRoles');
    await store.dispatch('users/fetchUserDetail' , params.id );
  },

  created() {
    if(this.$nuxt._route.query.page){
       this.page  = this.$nuxt._route.query.page;
    }
  },


  data() {
    return {
      selected:[],
      page: 'account',
      selectedAll: false,
      inviteModal: false,
      showModal: false,
    }
  },

  computed: {
    locales(){
      return this.$i18n.locales;
    },
    users() {
      return this.$store.state.users.users;
    },
    userRoles() {
      return this.$store.state.permissions.roles;
    }
  },

}
</script>

<style scoped>

</style>
