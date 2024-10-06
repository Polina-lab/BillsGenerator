<template>
  <div>
    <div>
      <h2 class="m-2 card-title">{{ $t('register.package') }}</h2>
    </div>
    <div v-if="!isPricingComponentVisible && !isPaymentComponentVisible" class="card-body pt-0 width__settings">
      <div class="card__container">
        <h4 class="card-subtitle">
          {{ $t('profile.current_package') }}
        </h4>
        <div v-if="pkg" class="pkg__content">
          <div class="number__text" :class="pkg.string+'-pkg'">
            <div v-if="pkg.price > 0">
              {{ `${pkg.price}`}} {{`${pkg.currency}` }}
            </div>
            <div v-else>
              {{ $t('pricing.free') }}
            </div>
          </div>
          <div :class="pkg.string+'-pkg'">
            {{ pkg.string[0].toUpperCase() + pkg.string.slice(1) }}
          </div>
          <div>
            <div v-if="daysRemainingActive < 1" class="red">
              {{ $t('profile.expired') }}
            </div>
            <div v-else-if="daysRemainingActive >= 1 && 3 >= daysRemainingActive" class="red">
              {{ $t('profile.expires_in', {
                days_remaining_active: daysRemainingActive, active_until_date: dateFormat(active_until_date)
                }) }}
            </div>
            <div v-else-if="daysRemainingActive > 3" class="green">
              {{ $t('profile.billed_on', {
                days_remaining_active: daysRemainingActive, active_until_date: dateFormat(active_until_date)
                }) }}
            </div>
          </div>
        </div>
      </div>
      <div v-if="pkg" class="d-flex justify-content-around justify-content-md-end mt-5">
        <div class="row ">
        <div class="col-12 order-last order-md-first col-md-4">
        <button class="btn btn-outline-secondary p-2 first__tab" :class="pkg.string+'-pkg'"
                @click="isFeaturesVisible.pkg = !isFeaturesVisible.pkg">
          {{ isFeaturesVisible.pkg ? $t('profile.hide_features') : $t('profile.features') }}
        </button>
        </div>
        <div class="col-6 order-first col-md-4">
        <button class="btn btn-outline-secondary p-2"
                :class="pkg.string+'-pkg-background'"
                @click="goToPayment(pkg)">
          {{ $t('common.update') }}
        </button>
        </div>
        <div class="col-6 col-md-4">
        <button class="btn btn-outline-secondary p-2 last__tab"
                @click="isPricingComponentVisible = true">
          {{ $t('profile.change') }}
        </button>
        </div>
        </div>
      </div>
      <hr>
      <ul v-if="isFeaturesVisible.pkg && pkg" class="list-unstyled text-left mark-symbol media__columns">
        <div v-for="feature in pkg.extra.split(',')" :key="feature">
          <li>{{ getPricingTKey(feature) }}</li>
          <br>
        </div>
      </ul>
    </div>
    <div v-if="!isPricingComponentVisible && !isPaymentComponentVisible" class="card-body pt-0">
      <div class="d-flex bd-highlight mr-n2 flex-break">
        <div class="p-2 bd-highlight">
          <h6 id="cansel-subscription">
            {{ $t('profile.cancel_subscription') }}
          </h6>
          <div id="cancellation_notice">
            {{ $t('profile.cancellation_notice') }}
          </div>
        </div>

        <div class="ml-auto p-2 bd-highlight red__box">
          <button class="btn btn-outline-danger red-pkg-border">
            {{ $t('common.cancel') }}
          </button>
        </div>
      </div>
    </div>
    <div v-if="isPricingComponentVisible" class="card-body pt-0">
      <div class="pointer__box">
        <div @click="isPricingComponentVisible = false" class="pointer">
          <i class="fas fa-arrow-left"></i>
          {{ $t('profile.return_to_settings') }}
        </div>
      </div>
      <div>
        <div class="row justify-content-center mt-3 mt-md-0 ring__box">
          <option v-for="(plan, i) in packageList" class="ring" :class="plan.string+'-pkg'" :key="i" 
          @click="(selected = packageList[i].string)">
            <p class="ring__text">{{ i + 1 }}</p>
          </option>
        </div>
        <div class="row align-items-center align-items-md-baseline">
          <div class="col-1">
              <i class="fas fa-chevron-left fa-3x arrow__left" @click="goToBack()"></i>
          </div>
          <div class="col-9 col-sm-10 col-md-12 pl-5">
            <div class="row align-items-stretch justify-content-between grid__container" id="mw1150" v-touch:swipe.left="swipeHandlerLeft" v-touch:swipe.right="swipeHandlerRight">
              <div v-for="(plan, idx) in packageList"
                   class="col-12 text-center" 
                   :class="[plan.string+'-pkg', 
                          ( selected != null ) ? ((plan.string === selected  ? 'open' : 'close') || 
                          ( plan.string === goToNext() ? 'visible' : 'hidden')) : ( idx === 0 ? 'open' : 'close') ]"
                   :key="idx">
            <div class="row text-center justify-content-center statistic__row">
              <div class="main__statistic">
                <div class="statistic__block pricing h-100 text-center" :class="plan.string+'-pkg'">
                  <div class="price__block">
                    <strong class="price" :class="plan.string+'-pkg'">
                      <div v-if="plan.string === 'diamond' || plan.string === 'universe'">
                        {{ $t('pricing.coming_soon') }}
                      </div>
                      <div v-else-if="plan.price > 0">
                        {{ `€${plan.price}/ ${$t('pricing.month')}` }}
                      </div>
                      <div v-else>
                        {{ $t('pricing.free') }}
                      </div>
                    </strong>
                  </div>
                  <h3>{{ plan.string }}</h3>
                  <button class="btn btn-features"
                          :class="[plan.string+'-pkg', { checked : !isFeaturesVisible[plan.string] }]"
                          @click="isFeaturesVisible[plan.string] = isFeaturesVisible[plan.string] ? false : true">
                    {{ isFeaturesVisible[plan.string] ? $t('profile.hide_features') : $t('profile.features') }}
                  </button>
                  <div v-if="plan.id !== $store.state.current_team.payment_plan_id" class="pricing h-100 text-center">
                    <ul v-if="isFeaturesVisible[plan.string]" class="list-unstyled text-left mark-symbol">
                      <div v-if="idx === 3">
                        <li>
                          {{ packageList[idx-1].string + ' +' }}
                        </li>
                        <br>
                      </div>
                      <div v-if="(plan.extra !== null)">
                        <div v-for="feature in plan.extra.split(',')" :key="feature">
                          <li>{{ getPricingTKey(feature, idx) }}</li>
                          <br>
                        </div>
                      </div>
                    </ul>
                    <div class="price-cta">
                      <p v-if="!(plan.string == 'diamond' || plan.string == 'universe')">
                        <button @click="goToPayment(plan)"
                                class="btn btn-select mt-5"
                                :class="plan.string+'-pkg-background'">
                          {{ $t('pricing.select_plan') }}
                        </button>
                      </p>
                      <p v-else>
                        <button @click="is_coming_soon_modal_visible = true"
                                class="btn btn-select mt-5"
                                :class="plan.string+'-pkg-background'">
                          {{ $t('pricing.select_plan') }}
                        </button>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              </div>
            </div>
          </div>
          <div class="col-1">
            <i class="fas fa-chevron-right fa-3x arrow__right" @click="goToNext()"></i>
          </div>
        </div>
      </div>
    </div>
    <div v-if="isPaymentComponentVisible">
      <div class="pointer__box">
        <div @click="isPaymentComponentVisible = false; isPricingComponentVisible = true;" class="pointer">
          <i class="fas fa-arrow-left"></i>
          {{ $t('profile.return_to_settings') }}
        </div>
      </div>
      <div>
        <PaymentComponent @returnToSettings="
          isPaymentComponentVisible = false;
          $event ? changePackage($event) : isPricingComponentVisible = true" :buyer="user"/>
      </div>
    </div>
    <Modal :title="$t('pricing.coming_soon')"
         @closeModal="is_coming_soon_modal_visible = $event;"
         v-if="is_coming_soon_modal_visible">
      <div class="alert alert-danger">
        {{ $t('pricing.in_development') }}
      </div>
      <template v-slot:footer>
        <button type="button"
                class="btn btn-primary"
                @click="is_coming_soon_modal_visible = false;">
          {{ $t('common.ok')}}
        </button>
      </template>
    </Modal>
  </div>
</template>

<script>
import getPricingTKey from '../../../mixins/getPricingTKey';
import PaymentComponent from '../../bills/PaymentComponent.vue';
import Modal from '../../common/Modal.vue';
import dateFormat from "../../../mixins/dateFormat";

export default {
  name: 'UsSetPackageComponent',
  components: { PaymentComponent, Modal },
  mixins: [ getPricingTKey, dateFormat ],
  data: () => ({
    selected: null,
    current_date: new Date(),
    active_until_date: null,
    is_coming_soon_modal_visible: false,
    isPricingComponentVisible: false,
    isPaymentComponentVisible: false,
    isFeaturesVisible: {
      pkg: false,
      standard: true,
      gold: true,
      diamond: true,
      universe: true
    },
  }),

  created(){
    this.$nextTick(() => {
      this.active_until_date = new Date(this.$store.state.current_team.active_until);
    });
  },

  computed: {
    packageList() {
      let packageList = this.$store.getters['payment/getPaymentsPlans'];
      if (packageList.length > 0 && !this.isPricingComponentVisible) {
        let diamond_features = packageList.find(p => p.string === 'diamond').extra;
        let universe_features = packageList.find(p => p.string === 'universe').extra;
        let d_f = diamond_features.split(',');
        d_f.pop();
        packageList.find(p => p.string === 'universe').extra_orignal = universe_features;
        packageList.find(p => p.string === 'universe').extra = d_f.join(',') + ',' + universe_features;
      } else if (packageList.length > 0 && this.isPricingComponentVisible) {
        packageList.find(p => p.string === 'universe').extra = packageList.find(p => p.string === 'universe').extra_orignal;
        packageList = packageList.filter(p => p.id !== this.$store.state.current_team.payment_plan_id);
      }
      return packageList;
    },

    package_id() {
      return this.$store.state.auth.user.available_teams[this.team_index].payment_plan_id;
    },
    pkg() {
      return this.packageList.find(single_package => single_package.id == this.$store.state.current_team.payment_plan_id);
    },
    daysRemainingActive() {
      var diffTime = (this.active_until_date - this.current_date);
      return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    },
    team_index() {
      let key = this.$store.state.key;
      let team_index = this.$auth.user.available_teams.indexOf(
      this.$auth.user.available_teams.find(team => team.key === key));
      return team_index;
    },
    mainCompany() {
      return this.$store.state.bills.firmsList.find(firm => firm.main_firm == 1);
    },
    user() {
      let buyer = this.$store.state.users.userDetail.local_data;
      buyer.firm = {};
      let firm_fields = ['name', 'reg_num', 'address', 'phone', 'vat_reg_num', 'vat'];
      for (let i = 0; i < firm_fields.length; i++) {
        const element = firm_fields[i];
        buyer.firm[firm_fields[i]] = this.mainCompany[firm_fields[i]];
      }
      return buyer;
    },
  },
  methods: {
    goToNext(){
      this.packageList.push(this.packageList.shift());
      return this.selected = this.packageList[0].string;
    },
    goToBack(){
        this.packageList.unshift(this.packageList.pop());
        return this.selected = this.packageList[0].string;
    },
    swipeHandlerLeft (direction) {
        this.packageList.push(this.packageList.shift());
        return this.selected = this.packageList[0].string;
        },
    swipeHandlerRight(direction) {
        this.packageList.unshift(this.packageList.pop());
        return this.selected = this.packageList[0].string;
        },
    goToPayment(plan) {
      this.$store.commit('payment/SET_OBJ', { name: 'selectedPlan', value: plan });
      this.isPricingComponentVisible = false;
      this.isPaymentComponentVisible = true;
    },

    changePackage(package_id) {
      this.$store.commit('SET_OBJ', {
        name: 'current_team.payment_plan_id',
        value: package_id
      }, { root: true });
    },
  }
}
</script>

<style lang="scss" scoped>
@import "@/assets/scss/var";
.flex-break{
  @media screen and (max-width:768px){
    display: flex;
    flex-direction: column;
    align-items: center;
  }
}
#cansel-subscription{
  @media screen and (max-width: 768px){
    text-align: center;
    font-size: 22px;
    font-weight: 600;
  }
}
.red__box{
  margin-top: -25px;
  @media screen and (max-width:768px){
    margin-top: 0px;
  }
  @media screen and (max-width:768px){
    width: 100%;
    display: flex;
    margin-top: 30px;
  }
}
.card-title{
  position: relative;
  font-family: 'Montserrat', sans-serif;
  font-size: 19px;
  font-weight: 500 !important;
  left: -30px;
  @media screen and (max-width: 768px){
    text-align: center;
    font-size: 25px;
  }
}
.card-subtitle{
  font-family: 'Montserrat', sans-serif;
  font-size: 15px;
  color: $secondary-color;
  margin-top: 25px;
  @media screen and (max-width: 749px){
    margin-top: 36px;
  }
}
.pkg__content{
  margin-top: -25px;
  text-align: right;
  @media screen and (max-width: 600px){
    margin-top: 0px;
  }
  @media screen and (max-width: 500px){
    text-align: left;
  }
}
.number__text{
  font-size: 2em;
}
%propertis{
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
  margin-bottom: 15px;
  height: 40px;
  white-space: nowrap;
  font-size: 13px;
  border-radius: 6px;
  width: 145px;
    @media (max-width: 1220px){
    margin-right: 20px;
    height: 30px;
    width: 120px;
    font-size: 10px;
  }
  @media (max-width: 1150px){
    width: 120px !important;
  }
  @media (max-width: 768px){
    height: 60px;
    width: 210px !important;
    font-size: 17px;
  }
  @media (max-width: 600px){
    height: 40px;
    width: 140px !important;
    font-size: 12px;
  }
}
.red {
  color: red;
}
.green {
  color: $logo-green;
}
.mark-symbol li {
  color: $secondary-color;
}
.mark-symbol li::before {
  content: "✓ ";
}
#cancellation_notice {
  color: $secondary-color;
}
label {
  cursor: pointer;
}
.section{
  margin-top: 35px;
  margin-bottom: 65px;
}
.pricing {
  padding: 15px 0px;
  position: relative;
  border-radius: 4px;
}
.price-cta{
  position: absolute;
  bottom: 10px;
  display: flex;
  min-width: 100%;
  width: 100%;
  justify-content: center;
  align-items: center;
  z-index: 2;
}
.btn-select {
  position: relative;
  margin-right: auto;
  margin-left: auto;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  font-size: 14px;
  @extend %propertis;
}

.btn-features {
  position: relative;
  margin-right: auto;
  margin-left: auto;
  background-color: #ffffff;
  border: 1px solid;
  z-index: 3;
  @extend %propertis;
}
  .checked{
    margin-right: 30px;
    margin-left: 40px;
  @media screen and (max-width: 991px){
    margin-right: 30px;
    margin-left: 20px;
    }
  @media screen and (max-width: 768px){
    margin-right: auto;
    margin-left: auto;
    }
  }
.pricing h3 {
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  padding: 8px 0px 0px 0px;
  line-height: 1;
  font-size: 18px;
  margin-top: 10px;
  @media screen and (max-width: 749px){
    margin-top: 0px;
  }
}

.pricing .price__block .price {
  position: relative;
  margin-left: auto;
  margin-right: auto;
  max-width: 80%;
  display: block;
  margin-top: 10px;
  font-size: 1.5rem;
  font-weight: 300;
  height: 100%;
}
.list-unstyled {
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  line-height: 1.1;
  font-size: 16px;
  padding-left: 30px;
  padding-right: 30px;
  padding-bottom: 100px;
  margin: 0 auto 0;
  word-wrap: normal;
  @media (max-width: 1200px){
    font-size: 14px;
  }
  @media (max-width: 991px){
    font-size: 12px;
    padding-left: 20px;
  }
  @media screen and(max-width: 768px) {
    font-size: 20px;
    font-weight: 500;
    line-height: 0.9;
    padding-left: 60px;
    padding-right: 10px;
  }
  @media (max-width: 600px){
    font-size: 13px;
    padding-left: 20px;
    padding-right: 20px;
  }
}
.media__columns{
  columns: 2;
  @media screen and (max-width: 768px){
    columns: 1;
    font-size: 20px;      
   } 
}
.grid__container{
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  grid-gap: 5vw;
  @media screen and (max-width: 768px){
    display: flex;      
   } 
}
.ring{
  display: flex;
  margin: 35px 25px -40px 25px;
  width: 80px;
  height: 80px;
  border-radius: 50px;
  border: 2px solid;
  display: none;
  @media screen and (max-width: 768px){
    display: flex;
    margin: 35px auto -40px auto;
  }
}
.ring__box{
  position: relative;
  margin-right: auto;
  margin-left: auto;
  max-width: 400px;
}
.ring__text{
  font-size: 33px;
  font-weight: 700;
  position: relative;
  margin: auto;
  display: flex;
  justify-content: center;
  align-items: center;
}
.ring__box .universe-pkg:active{
  @include but-background($universe);
}
.ring__box .diamond-pkg:active{
  @include but-background($diamond);
}
.ring__box .standard-pkg:active{
  @include but-background($standard);
}
.ring__box .gold-pkg:active{
  @include but-background($gold);
}
.ring__box a:hover,
.ring__box a:active{
  text-decoration: none;
}
.statistic__row{
  display: flex;
  padding-top: 80px;
  justify-content: center;
  align-items: center;
  max-width: 250px;
   @media screen and (max-width: 1200px){
     max-width: 190px;
   }
    @media screen and (max-width: 1000px){
     max-width: 150px;
   }
    @media screen and (max-width: 768px){
     width: 100%;
     max-width: 450px;
     margin-right: auto;
     margin-left: auto;
   }
}
.main__statistic{
  display: flex;
  max-width: inherit;
  width: inherit;
}
.arrow__left,
.arrow__right{
  color: $secondary-color;
  display: none;
  @media screen and (max-width: 768px){
    display: block;
  }
}

.statistic__block{
  flex: 1 1 auto;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
  max-width: 100%;
  width: 100%;
  height: 100%;
}
.statistic__block::before{
  content: "";
  position: absolute;
  display: flex;
  min-width: 100%;
  height: 100%;
  bottom: 10px;
  right: 10px;
  border: 1px solid;
  border-radius: 15px;
}
.statistic__block::after{
  content: "";
  position: absolute;
  display: flex;
  top: 10px;
  left: 10px;
  width: 100%;
  height: 100%;
  border: 1px solid;
  border-radius: 15px;
  z-index: 1;
}
%button-settings{
  margin: 30px 0px 30px 20px;
  min-width: 150px;
  height: 40px;
  line-height: 0.8;
  border: 1px solid;
  @media screen and (max-width: 1050px){
    margin: 30px 0px 30px 0px;
  }
  @media screen and (max-width: 768px){
    margin: -10px 0px 30px 0px;
    height: 60px;
    min-width: 210px;
  }
  @media screen and (max-width: 600px){
    margin: -10px 0px 30px 0px;
    height: 50px;
    min-width: 100%;
  }
}
.last__tab{
  @extend %button-settings;
  color: $secondary-color;
  border: 1px solid $secondary-color;
  &:hover{
    @include but-background (#817f7f);
  }
  @media screen and (max-width: 768px){
    float: right;
  }
}
.first__tab{
  @media screen and (max-width: 768px){
    width: 100%;
  }
  @media screen and (max-width: 600px){
    //width: 150px;
  }
}
.btn-outline-secondary{
  @extend %button-settings;
    &:hover{
    @include but-background (#c9c8c8);
  }
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
.red-pkg-border{
  @extend %button-settings;
  color: #dc3545;
  &:hover{
    @include but-background (#dc3545);
  }
  @media screen and (max-width: 768px){
    position: relative;
    margin-left: auto;
    margin-right: auto;
    min-width: 300px;
  }
  @media screen and (max-width: 600px){
    position: static;
    float: left;
    max-width: 150px;
    min-width: 150px;
  }
}
.pointer__box{
  display: flex;
  position: relative;
  margin: 25px auto auto -15px;
  @media screen and (max-width: 768px){
    margin: 40px auto auto 0px;
  }
}
.pointer{
  display: flex;
  align-items: center;
  font-size: 16px;
  @media screen and (max-width: 768px){
    font-size: 20px;
  }
}
.fa-arrow-left{
  font-size: 25px;
  letter-spacing: 10px;
  @media screen and (max-width: 768px){
    font-size: 36px;
  }
}
.center{
  @media screen and (max-width: 768px) {
    display: flex;
    min-width: 100% !important;
    justify-content: center !important;
  }
}
.row__center{
  min-width: 100% !important;
}
.hidden,
.close{
  @media screen and (max-width: 768px){
    display: none;
  }
}
.open,
.visible{
  @media screen and (max-width: 749px){
    display: flex;
  }
}
.width__settings{
  @media screen and (max-width: 749px){
    position: relative;
    margin-left: auto;
    margin-right: auto;
    max-width: 560px;
  }
}

</style>
