<i18n lang="i18n">
{
  "ee": {
          "some_cookies": "Mõned küpsised on meie teenuste kasutamiseks hädavajalikud. Kui külastate meie veebisaiti, võib see teie brauserisse salvestada või hankida teavet (enamasti küpsiste kujul). See teave võib puudutada teid, teie eelistusi või seadet ning seda kasutatakse enamasti selleks, et sait toimiks ootuspäraselt. Kuna austame teie õigust privaatsusele, saate teatud tüüpi küpsiseid mitte lubada. Lisateabe saamiseks klõpsake erinevate kategooriate pealkirjadel. Pange tähele, et teatud tüüpi küpsiste blokeerimine võib mõjutada teie kogemusi saidil ja meie pakutavaid teenuseid.",
          "to_continue": "I.GEN -i jätkamiseks vaadake enne alltoodud nõustumist üle saadaolevad küpsiste juhtelemendid ja tehke soovitud valikud.",
          "scrictly": "Rangelt vajalik",
          "these_cookies": "Need küpsised on vajalikud meie veebisaidi nõuetekohaseks toimimiseks ja neid ei saa meie süsteemides välja lülitada. Need määratakse tavaliselt ainult vastuseks teie tehtud toimingutele, mis vastavad teenuste taotlusele, näiteks teie privaatsuseelistuste määramine, sisselogimine või vormide täitmine või kui need on teile soovitud teenuse pakkumiseks hädavajalikud. te ei saa nendest küpsistest loobuda. Saate seadistada oma brauseri neid küpsiseid blokeerima või hoiatama, kuid kui te seda teete, siis mõned saidi osad ei tööta. Need küpsised ei salvesta isikut tuvastavat teavet." },
  "en": {
          "some_cookies": "Some cookies are essential to use our services. When you visit our website, it may store or retrieve information on your browser(mostly in the form of cookies). This informatsioon might be about you, your preferences or your device and is mostly used to make the site work as you expect it to. Because we respect your right the privacy, you can choose not to allow some types of cookies. Click on the different category headings to find out more. Please note, blocking some types of cookies may impact your experience of the site and the services we are able to offer.",
          "to_continue": "To continue to I.GEN, review the available cookie controls and make any optional selections you'd like before accepting below.",
          "scrictly": "Scrictly Necessary",
          "these_cookies": "These cookies are necessary for our website to function properly and cannot be swithed off in our systems. They are usually only set in response to actions made by your which amount to a request for services, such as setting your privacy preferences, logging in or filling in forms or where they're essential to provide you with a serviceyou have requested. you cannot opt-out of these cookies. You can set your browser to block or alert you about these cookies, but if you do, some parts of the site will not then work. These cookies do not store any personally identifiable informatsioon." },
  "ru": {
           "some_cookies": "Некоторые файлы cookie необходимы для использования наших услуг. Когда вы посещаете наш веб-сайт, он может сохранять или извлекать информацию в вашем браузере (в основном в виде файлов cookie). Эта информация может касаться вас, ваших предпочтений или вашего устройства и в основном используется для того, чтобы сайт работал так, как вы ожидаете. Поскольку мы уважаем ваше право на конфиденциальность, вы можете запретить использование некоторых типов файлов cookie. Щелкните заголовки различных категорий, чтобы узнать больше. Обратите внимание, что блокировка некоторых типов файлов cookie может повлиять на ваше восприятие сайта и услуг, которые мы можем предложить.",
          "to_continue": "Чтобы перейти к I.GEN, просмотрите доступные элементы управления файлами cookie и сделайте любой желаемый выбор, прежде чем принять его ниже.",
          "scrictly": "Строго необходимо",
          "these_cookies": "Эти файлы cookie необходимы для правильной работы нашего веб-сайта и не могут быть отключены в наших системах. Обычно они устанавливаются только в ответ на ваши действия, которые равносильны запросу на услуги, например, установка ваших предпочтений конфиденциальности, вход в систему или заполнение форм, или когда они необходимы для предоставления вам запрошенной вами услуги. вы не можете отказаться от этих файлов cookie. Вы можете настроить свой браузер так, чтобы он блокировал или предупреждал вас об этих файлах cookie, но если вы это сделаете, некоторые части сайта не будут работать. Эти файлы cookie не хранят никакой информации, позволяющей установить личность." }
}
</i18n>
<template>
<Modal :title="$t('cookies.cookie_settings')" :active="active" @closeModal="$emit('closeModal')"
  size="modal-lg" theme="dark">
    <div class="modal-body">
      <div class="cookies__item">
        <h5 id="img__header">{{ $t('cookies.cookie_settings') }}</h5>
        <img class="cookies__img" src="@/assets/img/landing/cookies.svg" alt="Cookies">
      </div>
      <p>{{ $t('some_cookies') }}<br>{{ $t('to_continue') }}</p>

      <div class="box__checkbox">
        <input type="checkbox" id="all" v-model="cookie.all" class="visible" disabled>
        <label for="all"></label>
      </div>
      <input type="checkbox" id="hd-1" class="hide">
      <label for="hd-1">{{ $t('scrictly') }}<i class="fas fa-question-circle"></i></label>
      <p>{{ $t('these_cookies') }}</p>
      <br>
      <div class="box__checkbox">
        <input type="checkbox" id="st" v-model="cookie.st" class="visible">
        <label for="st"></label>
      </div>
      <input type="checkbox" id="hd-2"  class="hide">
      <label for="hd-2">{{ $t('coupons.statistics') }}<i class="fas fa-question-circle"></i></label>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      <br>
      <div class="box__checkbox">
        <input type="checkbox" id="pf" v-model="cookie.pf" class="visible">
        <label for="pf"></label>
      </div>
      <input type="checkbox" id="hd-3"  class="hide">
      <label for="hd-3">{{ $t('cookies.preferences') }}<i class="fas fa-question-circle"></i></label>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      <br>
      <div class="box__checkbox">
        <input type="checkbox" id="vis-4" v-model="cookie.tg" class="visible">
        <label for="vis-4"></label>
      </div>
      <input type="checkbox" id="hd-4" class="hide">
      <label for="hd-4">{{ $t('cookies.targeting_cookies') }}<i class="fas fa-question-circle"></i></label>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      <br>

    </div>
    <template v-slot:footer>
      <div class="container-fluid justify-content-between">
      <div class="row general__row">
        <div class="buttton__box">
          <button type="button"
              class="decline"
              @click="declineAll(); confirmChoice()">
              {{ $t('cookies.decline_all') }}
          </button>
        </div>
          <div class="buttton__box">
          <button type="button"
              class="accept__selected"
                  @click="confirmChoice()">
              {{ $t('cookies.accept_selected') }}
          </button>
        </div>
          <div class="buttton__box">
          <button type="button"
              class="accept__all"
              @click="acceptAll(); confirmChoice()">
              {{ $t('cookies.accept_all') }}
          </button>
        </div>
      </div>
    </div>
  </template>
</Modal>

</template>

<script>
import Modal from '../common/Modal.vue'
export default {
  name: 'CookiesModal',
  components: { Modal },
  props:['active'],
  data : () => { return {
    cookie: {
      all: true,
      st: true,
      pf: true,
      tg : true
    }
  }},
  methods:{
    acceptAll(){
      this.cookie.st = true;
      this.cookie.pf = true;
      this.cookie.tg = true;
    },

    declineAll(){
      this.cookie.st = false;
      this.cookie.pf = false;
      this.cookie.tg = false;
    },

    confirmChoice() {
      this.$emit('acceptCookies', this.cookie); this.$emit('closeModal');
    }
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
.modal-body{
  background-color: #494949;
  color:#ffffff;
  font-size: 14px;
  font-weight: 500;
  line-height: 1.3;
  font-family: 'Montserrat', sans-serif;
  margin: -20px 0px -10px 0px;
  padding-left: 30px;
  padding-right: 30px;
  @media screen and (max-width: 443px) {
      text-align: justify;
    }
}
.cookies__item{
  position: absolute;
  margin-top: -65px;
  display: flex;
  flex-direction: row;
}
#img__header{
  font-family: 'Montserrat', sans-serif;
  font-size: 36px;
  font-weight: 600;
  white-space: nowrap;
  padding-left: 10px;
  opacity: 0;
  @media screen and (max-width: 650px) {
    font-size: 30px;
  }
  @media screen and (max-width: 550px) {
    font-size: 22px;
  }
}
.cookies__img{
  display: block;
  margin-left: 20px;
  max-width: 36px;
  @media screen and (max-width: 443px) {
    display: none;
  }
}
.general__row{
  display: flex;
  justify-content: space-between;
  margin-right: 0px;
  @media screen and (max-width: 991px) {
     margin-left: 20px;
   }
  @media screen and (max-width: 768px) {
     margin-left: 0px;
   }
}
.buttton__box{
    @media screen and (max-width: 443px) {
      min-width: 100%;
      width: 100%;
   }
}
%propertis{
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 25px;
  height: 50px;
  text-align: center;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  line-height: 1;
}
.decline{
  @extend %propertis;
  border: 1px solid #ffffff;
  background-color: #494949;
  color: #ffffff;
  width: 160px;
  &:hover{
    background-color: #ffffff;
    color: #494949;
    border: 1px solid #ffffff;
   }
  @media screen and (max-width: 991px) {
    margin-top: 60px;
  }
   @media screen and (max-width: 768px) {
     margin-top: 0px;
   }
   @media screen and (max-width: 443px) {
      margin-left: auto;
      margin-right: auto;
   }
}
.accept__selected{
  @extend %propertis;
  background-color: #ffffff;
  color: $logo-green;
  border: 1px solid #ffffff;
  margin-left: 180px;
  width: 200px;
  &:hover{
    background-color: $logo-green;
    color: #ffffff;
    border: 1px solid $logo-green;
   }
   @media screen and (max-width: 991px) {
     margin-right: 0px;
   }
   @media screen and (max-width: 768px) {
     margin-left: 0px;
   }
    @media screen and (max-width: 593px) {
     margin-right: 0px;
     margin-top: 30px;
   }
    @media screen and (max-width: 443px) {
      margin-left: auto;
      margin-right: auto;
   }
}
.accept__all{
  @extend %propertis;
  background-color: $logo-green;
  color: #ffffff;
  border: 1px solid $logo-green;
  width: 160px;
  &:hover{
    background-color: #ffffff;
    color: $logo-green;
    border: 1px solid #ffffff;
   }
  @media screen and (max-width: 443px) {
    margin-top: 30px;
    margin-left: auto;
    margin-right: auto;
   }
}
.hide {
    display: none;
}
.hide + label ~ p{
    display: none;
}

.hide + label {
    padding: 10px 0px;
    cursor: pointer;
    display: inline-block;
}

.hide:checked + label + p {
    display: block;
    padding-top: 0px;
    @media screen and (max-width: 443px) {
      text-align: justify;
    }
}
label{
  min-width: 100%;
}
.visible{
  float: right;
  width: 30px;
  height: 30px;
  margin-right: 0px;
  opacity: 0;
}
.box__checkbox{
  position: absolute;
  left: auto;
  right: 0px;
  width: 30px;
  height: 30px;
}
.visible + label{
  cursor: pointer;
  background-color:#afabab !important;
  color: $logo-green !important;
  border-radius: 3px;
  float: right;
  margin-right: 50px;
  margin-top: -30px;
  height: 30px;
}
.visible:checked + label{
  background-color: $logo-green !important;
}
.visible:checked + label:after{
  content: "\2713";
  font-size: 30px;
  margin-left: 5px;
  color: #ffffff;
}

#all + label{
  background-color: #afabab !important;
}
</style>
