<template>
      <div class="d-flex justify-content-center text-center">
        <div class="row text-center justify-content-center bills__statistic">
          <div class="media__1000"></div>
            <div class="col-3 order-lg-first first__col">
              <div class="main__statistic">
                <div class="first__block" :class="current_team.current_balance >= 0 ? 'green' : 'red'">
                    <div class="statistic__number1">{{ current_team.current_balance }} &#x20ac;</div>
                    <div class="statistic__text1">{{ $t('bills.account_balance') }}</div>
                    <div class="statistic__href"><a href="/bills?incoming=true">{{ $t('profile.my_bills') }}</a></div>
                </div>
              </div>
            </div>
            <div class="col-3 order-sm-first col-md-5">
              <div class="middle__statistic">
                <div class="middle__block" :class="paymentPackage.string+'-pkg'" v-if="paymentPackage">
                    <div class="statistic__header" :class="paymentPackage.string+'-pkg'">
                      {{ $t('payments.payment_plan') }}
                    </div>
                    <div class="statistic__number2" :class="paymentPackage.string+'-pkg'">
                      <div v-if="paymentPackage.price > 0 ">{{ paymentPackage.price }} &#x20ac;</div>
                      <div v-else>{{ $t('pricing.free') }}</div>
                    </div>
                    <div class="statistic__text2" :class="paymentPackage.string+'-pkg'">
                      {{ paymentPackage.string[0].toUpperCase() + paymentPackage.string.slice(1) }}
                    </div>
                    <div class="statistic__button">
                      <a class="button__text"
                              :class="[paymentPackage.string+'-pkg-background']"
                              href="/bills">
                        {{ $t('profile.view_all') }}
                      </a>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-3 last__col">
              <div class="main__statistic">
                <div class="last__block">
                    <div class="statistic__number3">{{ billsCount }}</div>
                    <div class="statistic__text3">{{ $t('bills.name') }}</div>
                    <div class="statistic__href"><a href="">{{ $t('profile.view_all') }}</a></div>
                </div>
              </div>
            </div>
          <div></div>
        </div>
      </div>
</template>
<script>


export default{
    name: "BillsStatistic",
    computed: {
      current_team() {
        return this.$store.state.current_team;
      },
      paymentPackage() {
        return this.$store.state.payment.paymentPlans.find(
          e => e.id  == this.current_team.payment_plan_id);
      },
      billsCount() {
        return this.$store.getters['bills/countBillsList']
      },
    },

  beforeMount(){
      if(!this.$store.state.payment.paymentPlans)
          this.$store.dispatch('payment/fetchPaymentsPlans')
  }

}
</script>
<style scoped lang="scss">
@import "./assets/scss/var";
.col-3{
  @media screen and (max-width: 1000px){
    max-width: 35% !important;
  }
  @media screen and (max-width: 983px){
    max-width: 100% !important;
  }
}
.first__col{
    @media screen and (max-width: 749px) {
    padding-left: 0px;
    padding-right: 60px;
  }
  @media screen and (max-width: 630px) {
    padding-left: 15px;
    padding-right: 15px;
  }
}
.last__col{
    @media screen and (max-width: 749px) {
    padding-left: 60px;
    padding-right: 0px;
  }
    @media screen and (max-width: 630px) {
    padding-left: 15px;
    padding-right: 15px;
  }
}
.bills__statistic{
  margin-right: 0px;
  margin-left: 0px;
  display: flex;
  padding-top: 5px;
  justify-content: center;
  align-items: center;
  min-width: 100%;
    @media screen and (max-width: 1000px){
    margin-right: auto;
     margin-left: auto;
   }
    @media screen and (max-width: 649px){
     margin-right: auto;
     margin-left: auto;
   }
}
.media__1000{
    width: 0px;
    @media screen and (max-width: 991px){
        width: 100%;
  }
}
.main__statistic{
  position: relative;
  display: flex;
  max-width: 260px;
  width: 260px;
  height: 210px;
  z-index: 1;
  @media screen and (max-width: 991px) {
    margin-bottom: 40px;
  }
  @media screen and (max-width: 749px) {
    margin-bottom: 60px;
    max-width: 235px;
    width: 235px;
    height: 230px;
  }
}
.middle__statistic{
  position: relative;
  display: flex;
  max-width: 400px;
  width: 400px;
  height: 210px;
  z-index: 1;
  @media screen and (max-width: 991px) {
    margin-bottom: 40px;
  }
  @media screen and (max-width: 749px) {
    margin-bottom: 60px;
    max-width: 590px;
    width: 590px;
    height: 300px;
  }
}
%propertis{
  flex: 1 1 auto;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
}
%before__propertis{
  content: "";
  position: absolute;
  display: flex;
  min-width: 100%;
  height: 100%;
  bottom: 10px;
  right: 10px;
  border: 2px solid #e9ebeb;
  border-radius: 15px;
  z-index: -1;
}
%after__propertis{
  content: "";
  position: absolute;
  display: flex;
  top: 10px;
  left: 10px;
  width: 100%;
  height: 100%;
  border: 2px solid #e9ebeb;
  border-radius: 15px;
  z-index: -1;
}
.first__block::before{
  @extend %before__propertis;
  border: 2px solid;
}
.first__block::after{
  @extend %after__propertis;
  border: 2px solid;
}
.middle__block::before{
  @extend %before__propertis;
  border: 2px solid;
}
.middle__block::after{
  @extend %after__propertis;
  border: 2px solid;
}
.last__block::before{
  @extend %before__propertis;
  border: 2px solid #e9ebeb;
}
.last__block::after{
  @extend %after__propertis;
  border: 2px solid #e9ebeb;
}
.first__block,
.middle__block,
.last__block{
  @extend %propertis;
}
.statistic__number1,
.statistic__number2,
.statistic__number3,
.statistic__number4{
  font-size: 47px;
  font-weight: 600;
  line-height: 0.8;
  @media screen and (max-width: 749px) {
    font-size: 60px;
  }
}
.statistic__text1,
.statistic__text2,
.statistic__text3{
  font-size: 23px;
  @media screen and (max-width: 749px) {
    margin-bottom: 10px;
  }
}
.statistic__text2{
    @media screen and (max-width: 749px) {
    margin-top: 10px;
    margin-bottom: 30px;
  }
}
.statistic__href a{
  font-size: 21px;
  line-height: 2;
  color:black;
  @media screen and (max-width: 749px) {
    font-size: 23px;
    text-decoration: underline !important;
    text-decoration-line: underline !important;
  }
}
.statistic__header{
  @media screen and (max-width: 749px) {
    font-size: 23px;
    margin-bottom: 20px;
  }
}
.statistic__button{
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%; 
}
.button__text{
  display: flex;
  justify-content: center;
  align-items: center;
  width: 160px;
  height: 40px;
  border: 1px solid;
  border-radius: 10px;
  color: #ffffff;
  line-height: 0.8;
  @media screen and (max-width: 749px) {
    width: 280px;
    height: 50px;
    font-size: 25px;
  }
  @media screen and (max-width: 450px) {
    width: 160px;
  }
}
.statistic__number3,
.statistic__text3{
  color: $secondary-color;
}
.col-5, .col-3{
    display: flex;
    justify-content: center;
}

.gold-pkg-background{
  @include but-background ($gold);
  &:hover{
    @include but-background (#be891c);
  }
}
.standard-pkg-background{
  @include but-background ($standard);
    &:hover{
    @include but-background (#c9c8c8);
  }
}
.diamond-pkg-background{
  @include but-background ($diamond);
    &:hover{
    @include but-background (#429689);
  }
}
.universe-pkg-background{
    @include but-background ($universe);
    &:hover{
    @include but-background (#a874dd);
  }
}

.red {
  color: red;
}
.green {
  color: $logo-green;
}
</style>
