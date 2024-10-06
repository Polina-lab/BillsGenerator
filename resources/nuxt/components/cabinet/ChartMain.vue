<template>
    <div>
      <LineChartComponent/>
    </div>
</template>
<script>
import LineChartComponent from '../elements/charts/LineChartComponent.vue';
export default {
  name: "ChartMain",
  middleware:[ 'auth' ],
  components: { LineChartComponent },
  data() {
      return {

      }
  },
  computed: {
    OrdersList() {
      return JSON.parse(JSON.stringify(this.$store.state.orders.orders.data));
    },
    AllOrdersList() {
      return JSON.parse(JSON.stringify(this.$store.state.orders.orders));
    },
    companies() {
      return this.$store.state.bills.firmsList;
    },
    mainCompany() {
      return this.$store.state.bills.firmsList.find(c => c.main_firm == 1);
    }
  },
}
</script>
<style lang="scss" scoped>
@import '~assets/scss/_var.scss';

</style>