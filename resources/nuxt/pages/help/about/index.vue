<i18n lang="i18n">
{
  "ee": { 
          "we_are": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
          "we_do": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat." },
  "en": { 
          "we_are": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
          "we_do": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
  "ru": { 
          "we_are": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt  ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat." },
          "we_do": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat." }
}
</i18n>
<template>
    <div>
        <div id="about_bg1">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <h1 id="about-mobile-title">Who we are? What we do?</h1>
              </div>
              <div class="col-12 col-md-6">
                <h1 class="about__title">Who we are?</h1>
                <p class="about__text">{{ $t('we_are') }}</p>
              </div>
              <div class="col-12 col-md-6">
                <h1 class="about__title text-right">What we do?</h1>
                <p class="about__text right">{{ $t('we_do') }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="offset-0 offset-sm-4 offset-md-6 col-12 col-sm-8 col-md-6">
            <div class="text-center btn__box">
              <n-link to="#" class="btn btn-outline-white">{{ $t('menu.contact_us') }}</n-link>
            </div>
          </div>
        </div>

        <div>
          <OurCompany></OurCompany>
        </div>
    </div>
</template>
<script>
import OurCompany from "@/components/about/OurCompany";

export default{
    name: "about",
    layout:"help",
    components:{ OurCompany },

      data() {
          return{
        activeItem: 'home'
    }

  },

  methods: {
  	isActive (menuItem) {
      return this.activeItem == menuItem
    },
    setActive (menuItem) {
      this.activeItem = menuItem
    }
   }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/var";
.container{
  margin-top: 80px;
  max-width: 84%;
  font-family: 'Montserrat', sans-serif;
  @media screen and (min-width:2300px){
     margin-top: 0px;
  }
  @media screen and (max-width:800px){
     margin-top: 30px;
  }
}
#about-mobile-title{
  display: none;
  color: #ffffff;
  font-size: 5vw;
  font-weight: 600;
  @media screen and (max-width:768px){
    display: flex; 
  }
  @media screen and (max-width:650px){
    margin-top: -20px; 
  }
}
.about__title{
  color: #ffffff;
  font-size: 32px;
  font-weight: 600;
  @media screen and (max-width:768px){
    display: none;
  }
}
.about__text{
  color: #ffffff;
  max-width: 75%;
  text-align: justify;
  font-size: 16px;
  @media screen and (max-width:1100px){
     max-width: 100%;
  }
  @media screen and (max-width:1000px){
     font-size: 16px;
  }
  @media screen and (max-width:900px){
     font-size: 14px;
  }
  @media screen and (max-width:790px){
     font-size: 18px;
  }
  @media screen and (max-width:749px){
    line-height: 1.5;
    font-size: 2.8vw;
    margin-top: 20px;
  }
  @media screen and (max-width:380px){
    margin-top: 0px;
    font-size: 0.7rem;
    line-height: 1;
  }
}
.right{
  text-align: justify;
  float: right;
  @media screen and (max-width: 768px) {
    display: none;
  }
}
#about_bg1{
    position: relative;
    z-index: 99;
    max-width: 1300px;
    margin-left: auto;
    margin-right: auto;
    margin-top: -500px;
    max-height:  850px;
    height: 320px;
    @media screen and (min-width: 1700px){
      min-height:  400px;
      height: 400px;
    }
    @media screen and (max-width: 800px) {
      height: 420px;
    }
    @media(max-width: 768px){
      margin-top: -80vw;
    }
}
.btn__box{
  display: flex;
  justify-content: center;
  margin-bottom: 80px;
}
.btn.btn-outline-white {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 40px !important;
  background-color: rgb(60, 167, 71);
  color: #ffffff;
  width: 280px;
  height: 55px;
  line-height: 0.8;
  text-align: center;
  font-size: 22px;
  z-index: 20;
  opacity: 1;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: bold;
  &:hover {
    background-color: rgb(17, 120, 28);
  }
    &:hover::after {
    box-shadow: none;
  }
  &:after {
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
    transform: translate(-50%, 0);
    transition: transform 1s;
  }
  @media (max-width: 1700px) {
    margin-top: 30px;
  }
  @media (max-width: 1300px) {
    margin-top: 0px;
  }
  @media (max-width: 786px) {
    height: 70px;
    font-size: 32px;
    font-weight: 600;
    text-transform: uppercase;
    width: 80%;
  }
}
</style>