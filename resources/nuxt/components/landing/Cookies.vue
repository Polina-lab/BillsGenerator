<i18n lang="i18n">
{
  "ee": { "header": "Me hoolime teie privaatsusest",
          "cookies": "Kasutame „küpsiseid” et tagada Teile, meie veebisaidi sirvimisel parima kogemuse ja veelgi paremini täiustada oma teenust. Kui klõpsate nupul „Nõustun kõigega”, nõustute sellega, et I. GEN võib salvestada teie seadmesse küpsiseid ja avaldada teavet vastavalt meie küpsisepoliitikale.",
          "check": "Lisateabe saamiseks vaadake ",
          "information": "lehelt." },
  "en": { "header": "We Care About Your Privacy",
          "cookies": "We use „cookies” to provide you with the best experience while browsing our website and to further improve our service. By clicking „Accept all”, you agree I.GEN can store cookies on your devise and disclose information in accordance with our Cookie Policy",
          "check": "Check out ",
          "information": " for more information." },
  "ru": { "header": "Мы заботимся о вашей конфиденциальности",
          "cookies": "Мы используем «cookies», чтобы обеспечить вам лучший опыт просмотра нашего веб-сайта и улучшить наш сервис. Нажимая «Принять все», вы соглашаетесь, что I.GEN может хранить файлы cookie на вашем устройстве и раскрывать информацию в соответствии с нашей Политикой в ​​отношении файлов cookie.",
          "check": "Ознакомьтесь с ",
          "information": " для получения дополнительной информации." }
}
</i18n>
<template>
    <div>
      <div class="fixed-bottom cookies">
        <div class="d-flex">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="cookies__header">{{ $t('header') }}</div>
              <div class="cookies__text">{{ $t('cookies') }} <p>{{ $t('check') }}<a href="/" target="_blank">Cookie Policy</a> {{ $t('information') }}</p></div>
            </div>
            <div class="col-12 col-sm-4 col-md-2 cookies__item pl-lg-5">
              <img class="cookies__img" src="@/assets/img/landing/cookies.svg" alt="Cookies">
            </div>
            <div class="line__break"></div>
            <div class="col-4 col-md-2 center__item">
              <button class="customise" @click="showCookieSettings = true">{{ $t('cookies.customise') }}</button>
            </div>
            <div class="col-4 col-md-2 center__item">
              <button class="accept__all"  @click="acceptCookies({all : true , tg : true , st: true , pf: true})">{{ $t('cookies.accept_all') }}</button>
            </div>
          </div>
        </div>
      </div>
      <CookiesModal v-if="showCookieSettings" :active="showCookieSettings" @acceptCookies="acceptCookies" @closeModal="showCookieSettings = false"></CookiesModal>
    </div>
</template>
<script>

import CookiesModal from "@/components/landing/CookiesModal";

export default {
   name: "Cookies",
   components: { CookiesModal },
   data: () => {
     return {
       showCookieSettings : false
     }
  },
  methods:{
     acceptCookies(data){
       this.$store.commit('SET_OBJ' , { name:'CookiesAccepted', value: !this.$store.state.CookiesAccepted })
         if(data.all) this.$cookies.set('acceptCookies', 'true', {path: '/', maxAge: 60 * 60 * 24 * 7, })
         if(data.st)  this.$cookies.set('_st', 'true', {path: '/', maxAge: 60 * 60 * 24 * 7});
         if(data.pf)  this.$cookies.set('_pf', 'true', {path: '/', maxAge: 60 * 60 * 24 * 7});
         if(data.tg)  this.$cookies.set('_tg', 'true', {path: '/', maxAge: 60 * 60 * 24 * 7});
       }
    }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/_var.scss";
.cookies{
  background-color: #474747;
  font-family: 'Montserrat', sans-serif;
  min-height: 170px;
  z-index: 9998;
  @media screen and (max-width: 600px){

  }
}
.d-flex{
 margin: 30px 80px 20px 80px;
   @media screen and (max-width: 600px){
      margin: 30px 30px 20px 30px;
  }
}
.cookies__header{
  color: #ffffff;
  font-weight: bold;
  font-size: 20px;
  @media screen and (max-width: 600px){
      margin-left: 60px;
  }
}
.cookies__text{
  margin-top: 10px;
  color: #ffffff;
  font-size: 13px;
  line-height: 1.2;
}
.cookies__text p a{
    color: $logo-green;
    text-decoration: underline;
    &:hover{
      color: $logo-orange;
    }
}
.customise{
  background-color: #ffffff;
  color: $logo-green;
  width: 170px;
  height: 50px;
  border-radius: 25px;
  border: 1px solid #ffffff;
  font-weight: bold;
  font-size: 18px;
}
.accept__all{
  background-color: $logo-green;
  color:#ffffff;
  width: 170px;
  height: 50px;
  border-radius: 25px;
  border: 1px solid $logo-green;
  font-weight: bold;
  font-size: 18px;
  line-height: 0.9;
}
.cookies__img{
  width: 80px;
  height: auto;
    @media screen and (max-width: 600px) {
        position: static;
        max-width: 40px;
    }
}
.center__item{
  display: flex;
  justify-content: center;
  align-items: center;
    @media screen and (max-width: 768px) {
        justify-content: left;
        margin-left: -20px !important;
    }
    @media screen and (max-width: 600px) {
        margin-left: 0px !important;
    }
}
.line__break{
    @media screen and (max-width: 600px) {
        min-width: 100%;
    }
}
.cookies__item{
    display: flex;
    justify-content: center;
    align-items: center;
    @media screen and (max-width: 768px) {
        justify-content: left;
    }
    @media screen and (max-width: 600px) {
        position: absolute;
        min-width: 100%;
        justify-content: left;
    }
}
</style>
