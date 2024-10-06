export default {
  methods: {
    mutationCategory() {
      this.$store.commit('category/SET_OBJ', {name: 'categoryDetail', value: this.category})
    }
  }
}
