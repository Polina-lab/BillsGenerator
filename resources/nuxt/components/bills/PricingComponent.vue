<template>
<div class="section">
  <no-ssr>
  <div class="row justify-content-center text-center desktop-version">
    <div v-if="$route.path !== null && $route.path === '/'"  class="col-md-7 col-12" >
      <h2 class="section-heading">{{ $t('pricing.choose_plan') }}</h2>
    </div>
    <div class="row align-items-stretch justify-content-center" id="mw1150">
      <div v-for="(plan, idx) in payment_plan"
           :class="changeColor(plan)"
           class="col-lg-3 col-md-3 col-sm-8 col-xs-8 mb-3 mb-lg-0"
           :key="idx">
        <div class="pricing text-center " :class="{ open: isActive}">
          <h3>{{ plan.string }}</h3>
          <div class="price__block unic">
            <strong class="price">
              <div v-if="idx == 0">
                {{ $t('pricing.free') }}
              </div>
              <div v-else-if="idx == 1">
                {{ `€${plan.price}/${$t('pricing.month')}` }}
              </div>
              <div v-else>
                {{ $t('pricing.coming_soon') }}
              </div>
            </strong>
          </div>
          <ul class="list-unstyled text-left mark-symbol" :class="{ open: (isActive || isAdmin())}">
            <div v-if="idx === 3">
              <li class="blue">
                {{ payment_plan[idx-1].string + ' +' }}
              </li>
              <br>
            </div>
            <div v-if="(plan.extra !== null)">
              <div v-for="feature in plan.extra.split(',')" :key="feature">
                <!--<li :class="getFeatureColor(feature, plan.string, idx)">-->
                <li>
                  {{ getPricingTKey(feature, idx) }}
                </li>
                <br>
              </div>
            </div>
          </ul>
          <div v-if="$auth.loggedIn" class="price-cta" :class="{ open: isActive }">
            <p><button v-if="!($nuxt.$route.path == '/admin')" class="btn__text text-center" @click="addClass(idx)" :class="{ open: isActive}">{{ $t('pricing.show_all') }}</button></p>
            <button v-if="!($nuxt.$route.path == '/admin')"
            class="btn__no text-center" @click="isActive = !isActive" :class="{ open: isActive}">{{ $t('pricing.hide_all') }}</button>
            <p v-if="!(plan.string == 'diamond' || plan.string == 'universe')">
              <button @click="setPlan(plan)" class="btn btn-white">
                {{ $t('pricing.select_plan') }}
              </button>
            </p>
            <p v-else>
              <button @click="is_coming_soon_modal_visible = true" class="btn btn-white">
                {{ $t('pricing.select_plan') }}
              </button>
            </p>
          </div>
          <div v-else class="price-cta" :class="{ open: isActive}">
            <p><button class="btn__text text-center" @click="addClass(idx)" :class="{ open: isActive}">{{ $t('pricing.show_all') }}</button></p>
            <button class="btn__no text-center" @click="isActive = !isActive" :class="{ open: isActive}">{{ $t('pricing.hide_all') }}</button>
            <p><a href="/login" class="btn btn-white">{{ $t('pricing.select_plan') }}</a></p>
          </div>
        </div>
      </div>
    </div>
    <!--<div class="col-10">
      <button v-if="!$auth.loggedIn" class="started__blue float-center mt-5">
        <n-link :to="'/register'">{{ $t('menu.get_started') }}</n-link>
      </button>
    </div>-->
  </div>
  <div class="mobile-version">
    <div id="swiperWrapper">
      <swiper class="swiper-container" ref="packetSwiper" :options="swiperOptions">
        <div slot="button-prev"
             class="p-0 justify-content-center
                    align-self-center pointer swiper-btn swiper-button-prev">
          <i class="fas fa-chevron-left fa-2x makeBrighter p-2"></i>
        </div>
        <swiper-slide v-for="(plan, idx) in payment_plan"
                      :class="changeColor(plan)"
                      :key="idx">
                      <!--  :style="{'max-height': `calc(100% - ${filterSectionHeight}px)`}"-->
          <div class="pricing text-center" :class="{ open: isActive}">
            <h3>{{ plan.string }}</h3>
            <div class="price__block unic">
              <strong class="price">
                <div v-if="idx == 0">
                  {{ $t('pricing.free') }}
                </div>
                <div v-else-if="idx == 1">
                  {{ `€${plan.price}/${$t('pricing.month')}` }}
                </div>
                <div v-else>
                  {{ $t('pricing.coming_soon') }}
                </div>
              </strong>
            </div>
            <ul class="list-unstyled text-left mark-symbol" :class="{ open: (isActive || isAdmin()) }">
              <div v-if="idx === 3">
                <li class="blue">
                  {{ payment_plan[idx-1].string + ' +' }}
                </li>
                <br>
              </div>
              <div v-if="(plan.extra !== null)">
                <div v-for="feature in plan.extra.split(',')" :key="feature">
                  <!--<li :class="getFeatureColor(feature, plan.string, idx)">-->
                  <li>
                    {{ getPricingTKey(feature, idx) }}
                  </li>
                  <br>
                </div>
              </div>
            </ul>
            <div v-if="$auth.loggedIn" class="price-cta" :class="{ open: isActive}">
              <p><button class="btn__text text-center" @click="addClass(idx)" :class="{ open: isActive}">{{ $t('pricing.show_all') }}</button></p>
              <p><button class="btn__no text-center" @click="isActive = !isActive" :class="{ open: isActive}">{{ $t('pricing.hide_all') }}</button></p>
              <p v-if="!(plan.string == 'diamond' || plan.string == 'universe')">
                <button @click="setPlan(plan)" class="btn btn-white mt-5">
                  {{ $t('pricing.select_plan') }}
                </button>
              </p>
              <p v-else>
                <button @click="is_coming_soon_modal_visible = true" class="btn btn-white mt-5">
                  {{ $t('pricing.select_plan') }}
                </button>
              </p>
            </div>
            <div v-else class="price-cta" :class="{ open: isActive}">
              <p><button class="btn__text text-center" @click="addClass(idx)" :class="{ open: isActive}">{{ $t('pricing.show_all') }}</button></p>
              <p><button class="btn__no text-center" @click="isActive = !isActive" :class="{ open: isActive}">{{ $t('pricing.hide_all') }}</button></p>
              <p><a href="/login" class="btn btn-white mt-5">{{ $t('pricing.select_plan') }}</a></p>
            </div>
          </div>
        </swiper-slide>
        <div slot="button-next"
             class="p-0 justify-content-center
                    align-self-center pointer swiper-btn swiper-button-next">
          <i class="fas fa-chevron-right fa-2x makeBrighter p-2"></i>
        </div>
      </swiper>
    </div>
  </div>

    <div class="container-flex row">
      <div class="col-12 text-center">
        <button v-if="!$auth.loggedIn" class="started__blue float-center mt-5">
          <n-link :to="'/register'" class="">{{ $t('menu.get_started') }}</n-link>
        </button>
      </div>
    </div>
  </no-ssr>
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
import getPricingTKey from '../../mixins/getPricingTKey';
import Modal from '../common/Modal.vue';
export default {
  name: "PricingComponent",
  components: { Modal },
  mixins: [getPricingTKey],
  data: () => ({
    isActive: false,
    is_coming_soon_modal_visible: false,
    swiperOptions: {
      slidesPerView: 1,
      spaceBetween: 50,
      centeredSlides: true,
      loop: false,
      speed: 1000,
      autoHeight: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: true
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true
      },
      navigation: {
        nextEl: '.fa-chevron-right',
        prevEl: '.fa-chevron-left'
      }
    },
  }),
  computed:{
    payment_plan() {
      return this.$store.getters['payment/getPaymentsPlans']
    },
    //isAdmin(){
      //return this.$route.path === '/admin' ? true : false
   // }
    /*filterSectionHeight() {
      const filterSectionDOM = document.getElementById("filterSection");
      //const filterSectionDOM = this.$refs(filterSection);
      return filterSectionDOM ? filterSectionDOM.offsetHeight : 0;
    },*/
    /*isMobile(){
      if (process.client) {

        //if (window.innerWidth < 749) {}
      }*/
  },
  methods: {
    isAdmin(){
      return this.$route.path === '/admin' ? true : false
    },
    addClass(){
        this.isActive = true;
    },
    changeColor(plan) {
      let cl = '';
      if (plan.string === "standard") {
        cl = 'standard';
      } else
      if (plan.string === "gold") {
        cl = 'gold';
      } else
      if (plan.string === "diamond") {
        cl = 'diamond';
      } else
      if (plan.string === "universe") {
        cl = 'universal';
      }
      return cl;
    },
    async setPlan(data) {
      this.$store.commit("payment/SET_OBJ" , { name: "selectedPlan", value: data } );
      this.$emit("next", 5);
    },

    goBack() {
      this.$emit("next", 3);
    },

    /*getFeatureColor(feature, pkg_name, idx) {
      if (pkg_name === 'diamond') {
        return 'blue';
      }
      let packageList = this.$store.getters['payment/getPaymentsPlans'];
      let features = packageList[( idx == 0 ? 0 : idx - 1)].extra.split(',');
      if (pkg_name == 'gold' &&
        (feature == 'multiple_user_accounts' || feature == 'invoice_styles'))
        {
          return pkg_name + '-pkg';
      }
      return features.includes(feature) ? 'blue' : pkg_name + '-pkg'
    },*/
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/_var.scss";
.row{
  margin-right: 0px !important;
  margin-left: 0px !important;
}
label{
  cursor: pointer;
}
input[type='radio']{
  display: none;
}
.slider{
  position: relative;
}
.slide{
  position: absolute;
  top: 0; left: 0;
  width: 200px; height: 200px;
  background: no-repeat 50% 50% / cover;
}
input:checked + .slide{
  left: 210px;
}
.standard{
  color: $secondary-color;
  &:hover{
    color: #616060;
  }
}
.gold{
  color: $logo-blue;
    &:hover{
      color: $gold;
  }
}
.diamond{
  color: $logo-blue;
    &:hover{
      color: #62ccbc;
  }
}
.universal{
  color: $logo-blue;
    &:hover{
      color: $universe;
  }
}
.section{
  margin-top: 35px;
  margin-bottom: 65px;
}
.mark-symbol li.blue{
  color: $logo-blue;
}
.mark-symbol li::before {
  content: "✓ ";
}
.pricing{
  padding: 40px 0px;
  position: relative;
  display: block;
  z-index: 10;
  border-radius: 4px;
  margin: -2px;
  min-height: 300px;
  &:after{
    content: "";
    position: absolute;
    display: block;
    width: 100%;
    height: 100%;
    border: 1px solid;
    border-radius: 20px;
    top: 0px;
    left: 10px;
    z-index: 5;
  }
  &:before{
    content: "";
    position: absolute;
    display: block;
    width: 100%;
    height: 100%;
    border: 1px solid;
    border-radius: 20px;
    margin-top: -60px;
    padding-top: 10%;
    right: 10px;
    z-index: 5;
  }
}
.pricing.open{
  min-height: calc(100% + 15px);
  height: calc(100% + 15px);
}
.swiper-slide .standard .swiper-slide-duplicate .swiper-slide-visible .swiper-slide-active:active{
  outline: 0 !important;
}
.price-cta{
  position: absolute;
  bottom: 10px;
  display: flex;
  flex-direction: column;
  min-width: 100%;
  width: 100%;
  justify-content: center;
  align-items: center;
  padding-bottom: 20px;
}
.price-cta.open{
  padding-bottom: 20px;
  justify-content: center;
}
.btn__text{
  position: relative;
  color: inherit;
  background-color: transparent;
  border: none;
  font-weight: 600;
  font-size: 20px;
  z-index: 15;
  &:hover{
    font-weight: 700;
  }
  &:active{
    outline: 0 !important;
  }
}

.makeBrighter:hover {
  &:active{
    outline: 0 !important;
  }
}
.btn__text.open{
  display: none;
}
.btn__no{
  position: relative;
  color: inherit;
  background-color: transparent;
  border: none;
  font-weight: 600;
  font-size: 20px;
  display: none;
  &:hover{
    font-weight: 700;
  }
}
.btn__no.open{
    position: relative;
    display: block;
    max-height: 50px;
    padding-bottom: 15px;
    outline: 0 !important;
    z-index: 15;
}
.btn-white {
  position: relative;
  background-color: $logo-blue;
  color:#ffffff;
  border: 2px solid #f1f1f1;
  border-radius: 4px;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 400;
  font-size: 18px;
  line-height: 2;
  font-variant: small-caps;
  max-width: 170px;
  width: 170px;
  z-index: 15;
  @media screen and (max-width: 900px){
    width: 140px;
  }
  @media screen and (max-width: 768px){
    width: 170px;
  }
}
 .btn-white:hover {
  background-color: rgb(24, 94, 120);
  color: #ffffff;
}
.last-plan{
  height: 65vw;
  min-height: 65vw;
}
.auto-height{
  height: auto;
}
.section-heading{
  color: $logo-blue;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  font-size: 36px;
  padding-bottom: 30px;
  letter-spacing: 0.025em;
}
.pricing h3 {
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  padding-bottom: 25px;
  line-height: 1.2;
  text-transform: uppercase;
  font-size: 28px;
  @media screen and (max-width:900px) {
    font-size: 24px;
  }
}
.pricing .price__block .price{
  display: block;
  margin-bottom: 20px;
  padding-bottom:10px; ;
  font-size: 1.5rem;
  font-weight: 300;
  line-height: 60px;
}
.unic{
  z-index: 9;
  position: relative;
  width:100%
}
/*.height-change{
  height: 80px;
}*/
.started__blue{
  color: #ffffff;
  border-radius: 40px !important;
  background-color: $logo-blue;
  border: none;
  height: 60px;
  width: 265px;
  text-align: center;
  margin: 90px 30px 0px 30px;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: bold;
  z-index: 5;
  position: relative;
&:hover {
  background-color: rgb(24, 94, 120);
}
&::after {
    z-index: 1;
    content: "";
    display: block;
    position: absolute;
    bottom: 0px;
    left: 50%;
    height: 25px;
    width: 90%;
    box-shadow: 0px 20px 15px 8px rgba(114, 114, 114, 0.4);
    border-radius: 50%;
    background-color: none;
    transform: translate(-50%, 0);
    transition: transform 1s;
}
  &:hover::after{
    box-shadow: none;
  }
  & a{
  display: flex;
  justify-content: center;
  color: #ffffff;
  font-size: 22px;
  text-decoration: none;
  }
}
.list-unstyled{
  font-family: 'Montserrat', sans-serif !important;
  font-variant: small-caps;
  font-weight: 400;
  line-height: 1;
  font-size: 18px;
  padding-top: 5px;
  padding-left: 30px;
  padding-right: 30px;
  margin: 0 auto 0;
  overflow: hidden;
  max-height: 130px;
  margin-bottom: 100px;
  @media screen and(max-width: 1080px) {
      font-size: 1.5vw;
    }
  @media screen and(max-width: 774px) {
    text-align: center !important;
    font-size: 18px !important;
    max-height: 150px !important;
    margin-bottom: 180px !important;
    }
  &:after{
    content: '';
    position: absolute;
    left: 0;
    bottom: -0;
    width: 100%;
    height: 80px;
    margin-bottom: 140px;
    background: -moz-linear-gradient(bottom, rgba(255,255,255, 0.3), #ffffff 100%);
    background: -webkit-linear-gradient(bottom, rgba(255,255,255, 0.3), #ffffff 100%);
    background: -o-linear-gradient(bottom, rgba(255,255,255, 0.3), #ffffff 100%);
    background: -ms-linear-gradient(bottom, rgba(255,255,255, 0.3), #ffffff 100%);
    background: linear-gradient(to bottom, rgba(255,255,255, 0.3), #ffffff 100%);
    @media screen and(max-width: 749px) {
      height: 150px;
      margin-bottom: 160px;
    }
  }
}
@media (max-width: 773px){
  .desktop-version{
    display: none;
  }
}
@media (min-width: 774px){
  .mobile-version{
    display: none;
  }
}
.swiper-btn::after {
  content: 'none';
}
// Source to button outside of swiper https://stackoverflow.com/a/63050848
.swiper-container {
  padding-left: 50px;
  padding-right: 50px;
}
.swiper-slide{
  margin-bottom: 40px;
  margin-top: 40px;
  padding-bottom: 80px;
}
.list-unstyled.open{
  min-height: 100%;
  max-height: 100%;
  overflow: visible;
  &:after{
    display: none;
  }
  @media screen and(max-width: 749px) {
    min-height: 100% !important;
    max-height: 100% !important;
    overflow: visible !important;
  }
}
</style>

