<template>
  <div>
    <h1 class="text-center">{{ $t('menu.templates') }}</h1>

    <div id="swiperWrapper">
      <swiper @slideChange="realIndexChangeHandler()"
              class="swiper-container" ref="templateSwiper" :options="swiperOptions">
        <swiper-slide v-for="template, idx in templates" :key="template.url">
          <img :src="require('@/assets/img/carousel/' + template.url)" v-img
               :alt="template.alt"
               class="img1 scale"
               :class="{ 'center scale__center': idx === realIndex,
                         'img2 scale': (idx === ((realIndex - 1) < 0 ?
                                        realIndex - 1 + templates.length : (realIndex - 1))) ||
                        (idx === (templates.length-1 < realIndex + 1 ?
                                        realIndex + 1 - templates.length : realIndex + 1))}"/>
        </swiper-slide>
        <div slot="button-prev"
             class="p-0 justify-content-center
                    align-self-center pointer swiper-btn swiper-button-prev">
          <i class="fas fa-chevron-left fa-3x makeBrighter p-2"></i>
        </div>
        <div slot="button-next"
             class="p-0 justify-content-center
                    align-self-center pointer swiper-btn swiper-button-next">
          <i class="fas fa-chevron-right fa-3x makeBrighter p-2"></i>
        </div>
      </swiper>
    </div>

    <div id="mobileSwiperWrapper">
      <swiper @slideChange="realIndexChangeHandler($refs.templateSwiper.$swiper)"
              class="swiper-container" ref="templateSwiperMobile" :options="swiperOptionsMobile">
        <swiper-slide v-for="template in templates" :key="template.url">
          <img :src="require('@/assets/img/carousel/' + template.url)" v-img
               :alt="template.alt"/>
        </swiper-slide>
        <div slot="button-prev"
             class="p-0 justify-content-center
                    align-self-center pointer swiper-btn swiper-button-prev">
          <i class="fas fa-chevron-left fa-4x makeBrighter p-2"></i>
        </div>
        <div slot="button-next"
             class="p-0 justify-content-center
                    align-self-center pointer swiper-btn swiper-button-next">
          <i class="fas fa-chevron-right fa-4x makeBrighter p-2"></i>
        </div>
      </swiper>
    </div>
  </div>
</template>
<script>


export default {
  name: "Carousel",
  data() {
    return {
      swiperOptions: {
        slidesPerView: 5,
        spaceBetween: 50,
        centeredSlides: true,
        loop: true,
        grabCursor: true,
        speed: 1000,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        navigation: {
          nextEl: '.fa-chevron-left',
          prevEl: '.fa-chevron-right'
        }
      },
      swiperOptionsMobile: {
        slidesPerView: 1,
        spaceBetween: 30,
        centeredSlides: true,
        loop: true,
        grabCursor: true,
        speed: 1000,
        /*autoplay: {
          delay: 4000,
          disableOnInteraction: false
        },*/
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        navigation: {
          nextEl: '.fa-chevron-right',
          prevEl: '.fa-chevron-left'
        }
      },
      templates: [
        { url: "8marts.png", alt: "8 marts" },
        { url: "MerryChristmas.png", alt: "Merry Chrismas" },
        { url: "valentines_day.png", alt: "Valentines day" },
        { url: "template2.png", alt: "template 2" },
        { url: "template1.png", alt: "template 1" },
        { url: "it_template.png", alt: "it_template 2" },
        { url: "halloween.png", alt: "Halloween" },
      ],
      slider: [ {url: "MerryChristmas2.png", alt: "Merry Chrismas"} ],
      isMounted: false,
      realIndex: 0
    }
  },

  mounted() {
    this.isMounted = true;
  },

  computed:{

  },

  methods: {
    realIndexChangeHandler() {
      this.realIndex = this.$refs.templateSwiper.$swiper.realIndex;
    }
  }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/_var.scss";
img{
   display: inline-block !important;
   margin-left: 10px;
}

h1{
  color: #ffffff;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 500;
  font-variant: small-caps;
  padding-top: 20px;
}
.scale {
  transition: 1s;
}
.scale:hover {
  transform: scale(1.08);
  -webkit-filter: none;
  filter: none;
  z-index: 9999;
}
.scale__center {
  transition: 1s;
}

.scale__center:hover {
  transform: scale(1.08);
  -webkit-filter: none;
  filter: none;
  z-index: 9999;
  @media screen and (max-width: 749px) {
    transform: none;
  }
}
.hidden{
  width: 0px;
  height: auto;
}
.img1{
  display: block;
  width: 9vw;
	height: 12.5vw;
  -webkit-filter: brightness(90%);
  filter: brightness(90%);
	background-color: antiquewhite;
	z-index: 999;
  position: relative;
  margin-top: 20px;
  @media(min-width:1500px){
    width: 140px;
	  height: 180px;
  }
}
.swiper-slide{
  display: flex;
  justify-content: center;
  align-items: center;
}
.img2{
  display: block;
  width: 14vw;
	height: 18.5vw;
  -webkit-filter: brightness(95%);
  filter: brightness(95%);
	background-color:antiquewhite;
	z-index: 999;
  @media(min-width:1500px){
    width: 200px;
	  height: 280px;
  }
}
.center{
  display: block;
  width: 32vw;
	height: 35vw;
	background-color: #ffffff;
	z-index: 999;
  -webkit-filter: none;
  @media(min-width:1500px){
    width: 400px;
	  height: 440px;
  }
}
  #swiperWrapper {
    display: flex;
  @media(min-width:1400px){
    margin-bottom: 0px;
    }
  @media(max-width:749px){
    display: none;
    }
  }
  .swiper-wrapper{
    transition-duration: 0ms;
  }
   #swiperWrapper:nth-child(2) img{
      margin-top: 20px;
      margin-bottom: 40px;
    @media(min-width:1400px){
      margin-bottom: 10px;
    }
   }
   #mobileSwiperWrapper {
      display: none;
      @media(max-width:749px){
        display: block;
      }
    }
   #mobileSwiperWrapper img{
      width: 0%;
   @media(max-width:749px){
      width: 80%;
      margin-top: 20px;
      margin-bottom: 40px;
      }
    }
.swiper-btn{
  color: #ffffff;
}

.swiper-btn::after {
  content: 'none';
}

// Source to button outside of swiper https://stackoverflow.com/a/63050848

.swiper-container {

  padding-left: 30px;
  padding-right: 30px;
  //transform: translate3d(-195.6px, 0px, 0px);
}
.swiper-slide-prev{
  transform: translate3d(-45px, 0px, 0px);
  @media(max-width:900px){
    transform: translate3d(-35px, 0px, 0px);
  }
}
.swiper-slide-next{
  transform: translate3d(45px, 0px, 0px);
  @media(max-width:900px){
    transform: translate3d(35px, 0px, 0px);
  }
}
</style>
