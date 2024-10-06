<template>
    <div>
      <div class="container">
        <div class="row justify-content-between">
          <div class="arrow__container">
            <nuxt-link to="/admin/orders"  prefetch class="pointer delete__styles"><i class="fas fa-arrow-left"></i><h6>{{ $t('products.product_list') }} / <strong>{{ $t('products.preview_product') }}</strong></h6></nuxt-link>
          </div>

        </div>
        <CardProduct :order="order" :firm="firm" />

        <div class="row justify-content-around justify-content-sm-between">
          <div class="arrow__box pointer" @click="changePage('prev')"><i class="fas fa-chevron-left fa-1x p-2"></i><h6>{{ $t('products.previous') }}</h6>
          </div>
          <div class="arrow__box pointer" @click="changePage('next')"><h6>{{ $t('products.next') }}</h6><i class="fas fa-chevron-right fa-1x p-2"></i></div>
        </div>
        <ChartMain></ChartMain>
      </div>
    </div>
</template>
<script>
import CardProduct from "@/components/cabinet/CardProduct";
import ChartMain from "@/components/cabinet/ChartMain";

export default {
  name: "preview",
  layout: "admin",
  middleware: ['auth'],
  components: { CardProduct, ChartMain },
  data() {
    return {
      search_name:null,
    }
  },
  async asyncData({store , route}) {
    const order  = await store.dispatch('orders/fetchOrder' , +route.params.id )
    await store.dispatch('bills/fetchFirmsList');
    return {'order' : order , 'firm' : order.firm && {} };
  },
  computed: {
   productsList() {
      return JSON.parse(JSON.stringify(this.$store.state.orders.orders.data));
   },
  },
  methods: {
    searchByName(data){
      console.log(data);
    },

    changePage(direction) {
      let current_product_index = this.productsList.map(p => p.id).indexOf(+this.$route.params.id)
      let index = 0;
      if (direction === 'next') {
        index = current_product_index + 1 === this.productsList.length ? 0 : current_product_index + 1;
      } else if (direction === 'prev') {
        index = current_product_index - 1 < 0 ? this.productsList.length - 1 : current_product_index - 1;
      }
      this.$router.push(`/admin/orders/preview/${this.productsList[index].id}`);
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~assets/scss/_var.scss';
.arrow__box{
  display: flex;
  justify-content: center;
  align-items: center;
  color: $secondary-color;
  h6{
    position: relative;
    margin-top: auto;
    margin-bottom: auto;
    text-align: center;
  }
}
.fa-arrow-left{
  font-size: 25px;
  letter-spacing: 10px;
  @media screen and (max-width: 768px){
    font-size: 36px;
  }
}
.delete__styles{
  display: flex;
  justify-content: center;
  align-items: center;
  color: $secondary-color;
  text-decoration: none;
   margin: 20px 0px 30px 10px;
}
.delete__styles h6{
    position: relative;
    margin-top: auto;
    margin-bottom: auto;
    text-align: center;
}
.delete__styles h6 strong{
  color: #000000;
}
.fa-search{
  display: flex;
  transform: scale(1.3);
  color: $secondary-color;
}
.i__group{
  display: flex;
  flex-direction: row;
  @media screen and (max-width: 768px){
    position: relative;
    margin-top: -10px !important;
    padding-bottom: 20px;
    margin-left: auto;
    margin-right: 1rem;
  }
}
.first__div{
  display: flex;
}
.arrow__box:hover{
  color:#474747;
}
</style>
