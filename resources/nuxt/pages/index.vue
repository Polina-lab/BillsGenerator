<template>
    <div>
        <div class="wrapper">
          <Advantages/>
        </div>
        <div id="bg2">
          <div class="wrapper">
            <Statistic :statistics="statistics"/>
          </div>
        </div><!--bg2-->
        <div id="pricing"><!--pricing-->
          <div class="wrapper">
            <PricingComponent/>
          </div>
        </div><!--pricing-->
        <div id="bg3"><!--carousel-->
         <div class="wrapper">
           <no-ssr>
            <Carousel/>
           </no-ssr>
          </div>
        </div><!--carousel-->
        <div id="partner"><!--partners-->
          <div class="wrapper">
            <Partner/>
          </div>
        </div><!--partners-->
    </div>
</template>
<script>
    import Advantages from "@/components/landing/Advantages";
    import Statistic from "@/components/landing/Statistic";
    import PricingComponent from "@/components/bills/PricingComponent";

export default {
    name: "default",
    layout:"default",
    data: () => {
      return {
        statistics : null
      }
    },
    components:{
      Advantages,
      Statistic,
      PricingComponent,
      Carousel : () => import("@/components/landing/Carousel"),
      Partner: () => import("@/components/landing/Partner")
    },
  async asyncData({store}){
    const res = await store.dispatch('api/fetchStatistics');
    return {'statistics' : res.data};
  }
}
</script>
<style scoped lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,400;0,500;1,300&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Cormorant+SC:wght@400;500;600;700;800&display=swap");

@import "./assets/scss/var";
  #bg2{
    width:100%;
    height: 50%;
    max-height: 1400px;
    background-image:  url('@/assets/img/fon-2.svg'), linear-gradient(to bottom, #26a647, #00ab7f, #00adaf, #00abcf, #1fa6dd);
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    @media(min-width: 1400px){
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }
    @media(max-width: 749px){
      background-image: linear-gradient(to bottom, #26a647, #00ab7f, #00adaf, #00abcf, #1fa6dd);
    }
  }
  #bg3{
    width:100%;
    min-height: 100%;
    background-image:  url('@/assets/img/fon-2.svg'), linear-gradient(to bottom, #1fa6dd, #00abcf, #00adaf, #00ab7f, #26a647);
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    background-repeat: no-repeat;
    @media(min-width: 1300px){
      padding-bottom: 30px;
    }
    @media(max-width: 749px){
      background-image:  linear-gradient(to bottom, #1fa6dd, #00abcf, #00adaf, #00ab7f, #26a647);
    }
  }
  #partner{
    margin-bottom: 15px;
    position: relative;
    background-color: #ffffff;
    z-index: 99;
  }

</style>
