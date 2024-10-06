
<script>
  export default {

    async asyncData({ store }) {
      store.dispatch('bills/fetchIncomingBills');
    },
    fetch ({  redirect }) {
      redirect(301, `/bills?incoming=true`)
    },
    destroyed(){
      this.$store.commit('bills/SET_OBJ', {name: 'filterBills', key: 'incoming', value: false})
    }


}
</script>

<style scoped>

</style>
