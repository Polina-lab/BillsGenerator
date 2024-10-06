

<i18n lang="i18n">
{
  "ee": { "best_app": "Tõenäoliselt mugavaim arvete koostamise lahendus maailmas!",
          "about_app": "Meie rakenduse abil saate luua igapäevaseid arveid, hallata oma kliente, loetleda oma tooteid/teenuseid - hoida oma äri finantsosa kontrolli all!",
          "under": "Ja seda kõike saate teha isegi telefonist või tahvelarvutist vaid mõne lihtsa sammu ja mitme lihtsa klõpsuga" },
  "en": { "best_app": "Probably the most convenient invoice generating solution in the world!",
          "about_app": "With our application you will be able to create daily invoices, manage your clients, list your products/services – keep your financial part of your business under control!",
          "under": "And all this you can do even from a telephone or a tablet with only few easy steps and several simple clicks" },
  "ru": { "best_app": "Пожалуй, самое удобное решение для создания счетов в мире!",
          "about_app": "С помощью нашего приложения вы сможете создавать ежедневные счета, управлять своими клиентами, перечислять свои продукты/услуги - держите свою финансовую часть своего бизнеса под контролем!",
          "under": "И все это вы можете сделать даже с телефона или планшета, выполнив всего несколько простых шагов и несколько простых щелчков мышью." }
}
</i18n>

<template>
  <div class="container-fluid mw1140" style="z-index: 99;" >
    <div class="row">
      <div class="col-7 col-md-6 col-xl-6 pt180">
        <h1 id="landing-heading">{{ $t('best_app') }}</h1>
        <p id="p-heading" class="mb-5  pl-sm-0" :class="language(i18n)" >{{ $t('about_app') }}<br>{{ $t('under') }}</p>
        <div class="login__box">
          <n-link to="/login" class="login">{{ $t('menu.login')}}</n-link>
        </div>
      </div>
      <div class="col-4 col-md-6 col-xl-6 preview__box">
        <div v-once class="row preview">

          <div class="col-4 col-xl-4">
            <div class="tablet">
              <img class="tablet-picture" src="@/assets/img/landing_Section1/tablet_200_310px.svg" alt="Tablet">
              <img class="tablet-screen" src="@/assets/img/landing_Section1/screen_tablet4.png" alt="Tablet">
              
            </div>
          </div>

          <div class="col-4 col-xl-6">
            <div class="computer">
              <img class="video" src="@/assets/img/bandicam.gif" alt="Video." />
              <img  class="comp" src="@/assets/img/landing_Section1/computer_2_430_250px.svg" alt="Monitor.">
            </div>
          </div>

          <div class="col-6 col-md-4 col-xl-2">
            <div class="phone">
              <img class="phone_picture" src="@/assets/img/landing/phone_output.png" alt="Phone screen picture." />
              <img class="phone_cover" src="@/assets/img/landing_Section1/iphone_125_250px.svg" alt="Phone.">
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="text-center">
      <n-link to="/register" class="btn btn-outline-white">{{ $t('menu.get_started') }}</n-link>
    </div>

  </div>
</template>

<script>
export default {
  name: "Section1",
  computed:{
    i18n(){
      return this.$i18n.locale;
    }
  },
  methods:{
    language(data){
      let cla = '';
      if (data  === 'ru')
      cla = 'ru-size';
       else if(data  === 'en')
      cla = 'en-size';
      else if (data  === 'ee')
      cla = 'ee-size';
      return cla;
    }
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/_var.scss";
.preview__box{
  max-height: 300px;
}
.ru-size{
  font-size: 1.2vw;
}
.ee-size{
  font-size: 1.4vw;
}
.en-size{
  font-size: 1.4vw;
}
.pt180{
  padding-top: 9vw;
  padding-left: 9%;
  text-align: justify;
  @media  (max-width: 1000px) {
    padding-top: 6vw;
  }
  @media  (max-width: 768px) {
    padding-top:8vw;
    text-align: left;
    padding-left: 6%;
  }
}
#landing-heading{
  color: #fff;
  font-size: 2.3vw;
  line-height: 1.2;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  max-width: 38vw;
  @media  (min-width: 1300px){
    font-size: 29px;
    max-width: 38vw;
  }
  @media  (max-width: 768px) {
    font-size: 4.5vw;
    max-width: 42vw;
  }
}
#p-heading{
  font-variant: small-caps;
  max-width: 38vw;
  color: #ffffff;
  @media screen and (min-width: 1300px) {
     font-size: 18px !important;
     max-width: 38vw;
   }
   @media screen and (max-width: 768px) {
     display: none;
   }
}
.preview{
  padding-top:8vw;
  max-height: 75vw;
  height: 75vw;
  max-width: 600px;
  position: relative;
  margin-left: -30px;
  z-index: 10;
  @media screen and (min-width: 1500px) {
    max-height: 1000px;
    height: 1000px;
  }
  @media  (max-width: 1000px) {
    padding-top: 5vw;
  }
  @media screen and (max-width: 767px) {
    padding-top: 0vw;
  }
  .computer{
    position: relative;
    width: 480px;
    left: -100px;
    transition: 1s;
    overflow: hidden;
    padding-bottom: 40px;
    -webkit-filter: grayscale(.70);
    filter: grayscale(.70);
    @media screen and (max-width: 1200px) {
      width: 380px;
      left: -110px;
    }
    @media screen and (max-width: 1000px) {
      width: 380px;
      left: -100px;
    }
    @media screen and (max-width: 950px) {
      width: 350px;
      left: -110px;
    }
    @media screen and (max-width: 767px) {
      display: none;
    }
    &:hover {
      -webkit-filter: none;
      filter: none;
    }
  }
  .computer::after{
    content: "";
    display: block;
    position: relative;
    width: 96%;
    margin-left: 2%;
    text-align: center;
    height: 20px;
    margin-top: -20px;
    z-index: 2;
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 50%;
    -webkit-box-shadow: 1px 22px 23px -1px rgba(39, 27, 27,1);
    -moz-box-shadow: 1px 22px 23px -1px rgba(39, 27, 27,1);
    box-shadow: 1px 22px 23px -1px rgba(39, 27, 27,1);
  }
  .comp{
    position: relative;
    width: 100%;
    z-index: 7;
  }
  .video {
    position: absolute;
    margin-top: 0.5vw;
    margin-left: 9%;
    z-index: 5;
    width: 82%;
    height: 82%;
    overflow: hidden;
    transition: 1s;
    @media screen and (max-width: 767px) {
      display:none;
    }
  }

  .tablet {
    z-index: 11;
    margin-top: 30px;
    position: relative;
    overflow: hidden;
    width: 200px;
    -webkit-filter: grayscale(.90);
    filter: grayscale(.90);
    transition: 1s;
    &:hover {
      transform: scale(1.1) translate(-12px, 7px);
      -webkit-transform: scale(1.1) translate(-12px, 7px);
      -moz-transform:scale(1.1) translate(-12px, 7px);
      -ms-transform:scale(1.1) translate(-12px, 7px);
      -o-transform: scale(1.1) translate(-12px, 7px);
      -webkit-filter: none;
      filter: none;
    }
  }

  .tablet-picture {
    position: absolute;
    max-width: 200px;
    width: 200px;
    z-index: 6;
    overflow: hidden;
   @media screen and (max-width: 1200px) {
      width: 160px;
    }
    @media screen and (max-width: 767px) {
      display: none;
    }

  }

  .tablet-screen{
    position: relative;
    z-index: 5;
    width: 100%;
    margin: auto;
    padding: 19px 11px;
    @media screen and (max-width: 1200px) {
      padding: 15px 11px;
      width: 160px;
    }
    @media screen and (max-width: 767px) {
      display: none;
    }
  }

  .phone_cover{
    position: relative;
    width: 100%;
    z-index: 6;
  }
  .phone_picture{
    position: absolute;
    margin-top: 0.2vw;
    margin-left: 6%;
    z-index: 5;
    width: 87%;
    height: 84%;
    overflow: hidden;
    @media screen and (max-width: 1200px){
      height: 82%;
    }
    @media screen and (max-width: 768px){
      margin-top: 1.2vw;
      width: 88%;
    }
  }
  .phone {
    position: relative;
    overflow: hidden;
    width: 100px;
    z-index: 11;
    -webkit-filter: grayscale(.95);
    filter: grayscale(.95);
    transition: 1s;
    padding-top: 30px;
		-webkit-transition: all 1s linear 0s;
	  -moz-transition: all 1s linear 0s;
	  -o-transition: all 1s linear 0s;
	  transition: all 1s linear 0s;
    @media screen and (max-width: 1200px) {
      width: 80px;
    }
    @media screen and (max-width: 767px) {
      left: 6vw;
      width: 30vw;
      -webkit-filter: none;
      filter: none;
    }
    &:hover {
      transform: scale(1.1) translate(-40px, 20px);
      -webkit-transform: scale(1.1) translate(-40px, 20px);
      -moz-transform:scale(1.1) translate(-40px, 20px);
      -ms-transform:scale(1.1) translate(-40px, 20px);
      -o-transform: scale(1.1) translate(-40px, 20px);
      -webkit-filter: none;
      filter: none;
    }
  }
}
.text-center{
  display: flex;
}
.btn.btn-outline-white {
  @extend %all-buttons;
  position: relative;
  border-radius: 40px !important;
  background-color: rgb(60, 167, 71);
  color: #ffffff;
  max-width: 280px;
  width: auto;
  padding: 0 50px;
  height: 60px;
  margin-left: 60%;
  margin-top: 0vw;
  text-align: center;
  font-size: 22px;
  z-index: 5;
  opacity: 1;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: bold;
  white-space: nowrap;
  &:hover {
    background-color: rgb(17, 120, 28);
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
    transform: translate(-50%, 0);
    transition: transform 1s;
  }
  &:hover::after {
    box-shadow: none;
  }
  @media (min-width: 1200px) {
     margin-top: 4.5vw;
   }
  @media (max-width: 768px) {
    position: relative;
    margin-top: 26vw;
    height: 10vw;
    height: 60px;
    margin-left: auto;
    margin-right: auto;
    font-size: 4.5vw;
  }
   @media (max-width: 600px) {
     font-size: 6vw;
   }
}
.login__box{
  display: flex;
}
.login{
  position: relative;
  z-index: 10;
  @extend %all-buttons;
  border-radius: 25px;
  background-color: $logo-orange;
  color: #ffffff;
  border-radius: 25px;
  width: auto;
  height: 45px;
  font-size: 18px;
  padding: 0px 50px;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  display: none;
   @media screen and (max-width: 1050px) {
     display: flex;
   }
  @media screen and (max-width: 768px) {
    position: relative;
    height: 45px;
    padding: 0vw 6vw;
    margin-top: 10vw;
    margin-left: auto;
    margin-right: auto;
    box-shadow: 0px 20px 15px 5px rgba(0, 0, 0, 0.4);
  }
  &:hover{
  background-color: #be891c;
  cursor: pointer;
  text-decoration: none;
  }
}

</style>
