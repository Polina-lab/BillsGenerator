export default {
  methods: {
    mutationUser() {
      this.$store.commit('users/SET_OBJ', {name: 'userDetail', value: this.user})
    }
  }
}
