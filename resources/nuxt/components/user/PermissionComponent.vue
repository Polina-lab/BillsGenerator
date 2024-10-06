<template>
  <div>
    <div class="card border-primary mt-5">
      <div class="card-title">
        <h2 class="m-2">{{ $t('User.permission') }}</h2>
      </div>
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-2 text-center">
            <label for="role">{{ $t('User.Role') }}</label>
            <select name="role" id="role">
              <option v-for="role in roles" :key='role.id' :value="role.id">{{ role.name }}</option>
            </select>
          </div>
          <div class="col-10">
            <span v-for="(permission, i) in user.permissions" :key='i'>
             <span class="p2">{{ permission }}<span v-if="i < user.permissions.length-1">, </span></span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PermissionComponent.vue",
  computed:{
    roles() {
      return this.$store.state.permissions.roles;
    },
    user() {
      return this.$store.state.users.userDetail;
    }
  },
  methods:{
    updateRole() {

    }
  }
}
</script>

<style scoped>

</style>
