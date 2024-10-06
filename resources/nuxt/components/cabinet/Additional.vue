<template>
<div>
    <div v-for="(item) in additionalCompanies" :key="item.id" id="top-leht" class="gray__block">
      <div class="container-fluid row container-header">
        <div class="col-12 col-lg-4 col-xl-8">
            <div class="row box__caret">
                <h1 id="company__name">{{ item.company_name }}</h1>
                <button @click="visible(item.id)" class="button__caret ml-2">
                    <i v-if="showCompany !== item.id" class="fas fa-caret-down fa-3x"></i>
                    <i v-if="showCompany === item.id" class="fas fa-caret-up fa-3x"></i>
                </button>
            </div>
        </div>
        <div class="col-12 col-lg-8 col-xl-4">
            <div v-if="showCompany !== item.id" class="row justify-content-end row__buttons">
                    <i class="fas fa-edit fa-3x text-success pointer"></i>
                    <i class="fa fa-trash fa-stack-3x fa-3x" style="color:red;"></i>
                <span v-if="(user.permissions.indexOf( 'edit-firm' ) != -1) || (user.permissions.indexOf( 'edit-company' ) != -1)"> <!-- check -->
                    <button class="profile__top">{{ $t('bills.edit_firm') }}</button>
                <span v-if="(user.permissions.indexOf( 'edit-firm' ) != -1) || (user.permissions.indexOf( 'edit-company' ) != -1)"> <!-- check -->
                    <button @click="setDelete()" class="delete__company ml-2">{{ $t('bills.delete_firm') }}</button>
                </span>
          </span>
            </div>
        </div>
        <h2 id="company__description">{{ $t('User.secondary_company') }}</h2>
      </div>


    <transition name="fade">
    <div v-if="showCompany == item.id">

    <div class="row justify-content-md-center">
        <div class="col-12 col-md-3 col-lg-2  col-xl-2 offset-md-7 offset-lg-8 offset-xl-10 text-right mobile-container-0">
          <div class="logo__box">
            <div v-if="item.logo != null" class="row justify-content-center">
              <img id="company__logo" :src="serverUrl + item.logo" :alt="'your-company-logo'">
            </div>
            <div v-else class="row">
                <img id="standart__logo" src="@/assets/img/cabinet/company_secondary.svg" alt="Someone's photo.">
            </div>
          </div>

          <div class="button__box text-right">
            <div v-if="(user.permissions.indexOf( 'edit-firm' ) != -1) || (user.permissions.indexOf( 'edit-company' ) != -1)"> <!-- check -->
                <button class="cabinet__logo">{{ $t('User.change_logo') }}</button>
            </div>
            <div v-if="(user.permissions.indexOf( 'edit-firm' ) != -1) || (user.permissions.indexOf( 'edit-company' ) != -1)"> <!-- check -->
                <button class="cabinet__profile">{{ $t('bills.edit_f') }}</button>
            </div>
          </div>

           <div class="set__box" v-if="(user.permissions.indexOf( 'edit-firm' ) != -1) || (user.permissions.indexOf( 'edit-company' ) != -1)"> <!-- check -->
            <button @click="setMain();" class="set__main">{{ $t('bills.set_the_main') }}</button>
          </div>

        </div>
    </div>

    <div class="container-fluid row mobile-container-1">
      <div class="col-12 col-xl-5" v-if="user.local_data.roles">
        <table>
          <caption class="caption">{{ $t('User.general_info') }}</caption>
          <tr>
            <td>{{ $t('bills.company_name') }}</td>
            <td class="black__text">{{  item.name ? item.name : "-"}}</td>
          </tr>
          <tr>
            <td>{{ $t('bills.reg_num') }}</td>
            <td class="black__text">{{ item.reg_num ? item.reg_num : "-" }}</td>
          </tr>
          <tr>
            <td>{{ $t('bills.vat_amount') }}</td>
            <td class="black__text">{{ item.km ? item.km : "-" }}</td><!-- clarify the correctness of the data -->
          </tr>
          <tr>
            <td>{{ $t('bills.representative_name') }}</td>
            <td class="black__text">{{ item.representative_name ? item.representative_name : "-" }}</td>
          </tr>
          <tr>
            <td>{{ $t('User.contact_address') }}</td>
            <td class="black__text">{{ item.address ? item.address : "-" }}</td>
          </tr>
        </table>
      </div>

      <div class="col-12 col-xl-5 mt-5">
        <table class="second__path">
          <tr>
            <td>{{ $t('bills.title') }}</td>
            <td class="black__text">{{  item.name ? item.name : "-"}}</td>
          </tr>
          <tr>
            <td>{{ $t('User.email') }}</td>
            <td class="black__text">{{ item.email ? item.email : "-" }}</td>
          </tr>
          <tr>
            <td>{{ $t('bills.km_reg_nr') }}</td>
            <td class="black__text">{{ item.km_reg_num ? item.km_reg_num : "-" }}</td>
          </tr>
          <tr>
            <td>{{ $t('bills.phone') }}</td>
            <td class="black__text">{{ item.phone ? item.phone : "-" }}</td>
          </tr>
        </table>
      </div>

    </div>

     <div v-if="item.banks" class="container-fluid row block__height">
        <div v-for="(bank, i) in item.banks" :key="i" class="col-12 col-xl-5 mobile-container-2">
          <table>
            <caption class="caption">{{ $t('User.bank_data') }}</caption>
            <tr>
              <td>{{ $t('bills.bank_name') }}</td>
              <td class="black__text">{{ bank.bank_name ? bank.bank_name : "-" }}</td>
            </tr>
            <tr>
              <td>{{ $t('bills.bank_num') }}</td>
              <td class="black__text">{{ bank.bank_num ? bank.bank_num : "-" }}</td>
            </tr>
            <tr>
              <td>{{ $t('bills.bank_address') }}</td>
              <td class="black__text">{{ bank.bank_address ? bank.bank_address : "-" }}</td>
            </tr>
            <tr>
              <td>{{ $t('bills.swift') }}</td>
              <td class="black__text">{{ bank.bank_swift ? bank.bank_swift : "-" }}</td>
            </tr>
          </table>
        </div>
      </div>
      <div v-else class="block__height">

      </div>


        <div class="row container-fluid">
            <div class="col-12">
                <h3 v-if="item.footer" id="general__info" class="mt-5">{{ $t('bills.footer_data_tab') }}</h3>
            </div>
            <div class="col-12">
            <div class="row justify-content-end">
                <div class="col-12 col-md-8 col-xl-10">
                    <p id="general__p" v-if="item.footer">{{ item.footer }}</p>
                </div>
                <div class="col-12 col-md-4 col-xl-2 bottom__box">
                    <div v-if="(user.permissions.indexOf( 'edit-firm' ) != -1) || (user.permissions.indexOf( 'edit-company' ) != -1)"> <!-- check -->
                        <button @click="setDelete()" class="delete__bottom">{{ $t('bills.delete_firm') }}</button>
                    </div>
                </div>
            </div>
            </div>
        </div>


      </div>
      </transition>

      <SetMainFirmModal :data-item="item" v-if="isMainFirmModalVisible" :isMainFirmModalVisible="isMainFirmModalVisible" @closeModal="$store.commit('SET_OBJ' , { name : 'isMainFirmModalVisible' , value: false})"/>
      <DeleteModal :data-item="item" v-if="isDeleteModalVisible" :isDeleteModalVisible="isDeleteModalVisible" @closeModal="$store.commit('SET_OBJ' , { name : 'isDeleteModalVisible' , value: false})"/>
      </div>
    </div>
</template>
<script>
import SetMainFirmModal from "@/components/cabinet/SetMainFirmModal";
import DeleteModal from "@/components/cabinet/DeleteModal";

export default {
    name: "Additional",
    middleware:[ 'auth' ],
    components: {SetMainFirmModal, DeleteModal},

  async asyncData({ store, params }) {
      await store.dispatch('bills/fetchFirmsList');
  },
    data: () => ({
    serverUrl: process.env.serverUrl,
    showCompany: null,
    selectLang: "en",
    isMainFirmModalVisible : false,
    isDeleteModalVisible : false
  }),
  computed: {
    user() {
      return this.$store.getters['users/getUserDetail'];
    },
    additionalCompanies() {
      return this.$store.state.bills.firmsList.filter(company => company.main_firm == 0);
    }
  },
     methods:{
      visible(company_id){
          if(this.showCompany === company_id )
            this.showCompany = null
          else
          this.showCompany = company_id;
      },
      setMain(){
        this.isMainFirmModalVisible = true;
      },
      setDelete(){
       this.isDeleteModalVisible = true;
      }
  }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/var";
.block__height{
    min-height: 100px;
    @media (max-width: 468px){
      min-height: 50px;
    }
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

.row{
  @media (max-width: 600px){
    margin-right: 0 !important;
    margin-left: 0 !important;
  }
}

/**box */
.container-header{
    margin-left: 3px;
    @media (max-width: 768px){
     margin-left: 15px;
  }
}
.box__caret{
  margin-top: -12px;
  align-items: center;
  flex-wrap: nowrap;
  @media (max-width: 650px){
     flex-wrap: wrap;
  }
}
#company__name{
  display: flex;
  font-size: 24px;
  font-weight: 600;
  letter-spacing: 1.8px;
  color: #646464;
  line-height: 1;
  width: auto;
  min-width: 280px;
  @media (max-width: 768px){
     font-size: 32px;
     line-height: 0.2;
  }
    @media (max-width: 650px){
     white-space:normal;
     display: flex;
     justify-content: center;
     width: 100%;
  }
}
.button__caret{
    position: relative;
    z-index: 4;
    border: none;
    background-color: #ffffff;
  @media (max-width: 650px){
     display: flex;
     justify-content: center;
     margin-top: 10px;
     width: 100%;
  }
  &:active{
      outline: none;
  }
    &:focus{
      outline: none;
  }
}
.fa-caret-down,
.fa-caret-up{
    color: #646464;
}
#company__description{
  position: relative;
  z-index: 5;
  font-weight: 500;
  font-size: 18px;
  color: $logo-green;
  line-height: 1;
  @media (max-width: 768px){
     font-size: 25px;
     line-height: 0.1;
  }
  @media (max-width: 650px){
     white-space: nowrap;
     display: flex;
     justify-content: center;
     width: 100%;
  }
}
#general__info{
    color: $logo-green;
    font-size: 16px;
    line-height: 4;
  @media (max-width: 650px){
     width: 100%;
     display: flex;
     justify-content: center !important;
  }
}
#general__p{
    @media (max-width: 650px){
     width: 100%;
     display: flex;
     justify-content: center !important;
  }
}
.gray__block{
  font-size: 14px;
  margin-top: 30px;
  border: 2px solid #e9ebeb;
  padding: 30px 30px;
  @media (max-width: 1216px){
    margin-top: 40px;
    margin-left: auto;
    margin-right: auto;
    padding: 40px 5px !important;
  }
  @media (max-width: 768px){
    font-size: 18px;
  }
}
.logo__box{
  display: flex;
  justify-content: center;
  overflow: hidden;
  align-items: center;
  background-color: #ffffff;
  margin-top: 10px;
  width: 160px;
  height: 160px;
   @media screen and (max-width: 768px) {
       margin-top: -190px;
       float:right;
   }
      @media screen and (max-width: 650px) {
       margin-top: -100px;
       float: none;
       display: flex;
        margin-left: auto;
        margin-right: auto;
   }
}
.button__box{
  position: relative;
  min-height: 220px;
   @media screen and (max-width: 768px) {
    position: relative;
    min-height: 40px !important;
    display: flex;
    width: 90%;
    flex-direction: row-reverse;
    justify-content: space-between;
    margin: -60px 20px 0px 25px;
   }
   @media screen and (max-width: 650px) {
        margin: -10px 20px 0px 25px;
   }
}
.mobile-container-0{
  position: relative;
  margin-top: -90px;

  @media screen and (max-width: 768px) {
    min-width: 100% !important;
    margin-top: 100px;
  }
}
#company__logo{
  position: relative;
  z-index: 2;
  width: 70%;
  height: auto;
    @media (max-width: 768px){
     width: 60%;
    }
}
#standart__logo{
  width: 200px;
  height: 200px;
  @media (max-width: 768px){
    width: 120px;
    height: 120px;
  }
}
.mobile-container-1{
  margin-top: -300px;
 @media (max-width: 768px){
     margin-top: 20px;
  }
}
.mobile-container-2{
  margin-top: 20px;
  @media (max-width: 849px){
     margin-top: 60px;
    }
}

table tr td{
  white-space: nowrap;
  word-wrap: unset;
  line-height: 2;
  @media (max-width: 600px){
    display: flex;
    min-width: 100%;
    flex-direction: column;
    justify-content: center;
  }
}
table{
  @media (max-width: 600px){
    position: relative;
    min-width: 100%;
    padding-left: auto;
    padding-right: auto;
  }
}
.second__path{
    @media (max-width: 849px){
    position: relative;
    margin-top: -60px;
  }
}
table tr td:nth-child(1){
  width: 210px;
  color: $logo-green;
  @media (max-width: 849px){
     padding-left: 10px;
  }
    @media (max-width: 600px){
    position: relative;
    display: flex;
    min-width: 100%;
    justify-content: center;
    align-items: center;
    padding-left: 0px !important;
  }
}
.black__text{
  padding-left: 20px;
   @media (max-width: 1200px){
     padding-left: 220px;
  }
  @media (max-width: 1000px){
     padding-left: 70px;
  }
  @media (max-width: 900px){
     padding-left: 5px;
  }
  @media (max-width: 849px){
     padding-left: 20px;
  }
  @media (max-width: 600px){
    position: relative;
    display: flex;
    max-height: 40px;
    min-width: 100%;
    justify-content: center;
    align-items: center;
    padding-left: 0px !important;
  }
}

%propertis{
  margin-top: 20px;
  margin-bottom: 15px;
  height: 40px;
  white-space: nowrap;
  font-size: 13px;
  border-radius: 6px;
  @media screen and (max-width: 749px) {
        font-size: 20px;
  }
  @media screen and (max-width: 530px) {
      font-size: 16px;
   }
}
.cabinet__logo{
 @extend %propertis;
 position: relative;
 z-index: 30;
 width: 165px;
 border: 1px solid $secondary-color;
 color: #ffffff;
 background-color: $secondary-color;
 display: flex;
 justify-content: center;
 align-items: center;
 @media screen and (max-width: 768px) {
    height: 50px;
    width: 200px;
    color: $secondary-color;
    border: 2px solid $secondary-color;
    background-color: #ffffff;
  }
  @media screen and (max-width: 600px) {
      white-space:normal;
      width: 160px;
      min-height: 54px;
   }
  @media screen and (max-width: 500px) {
    min-height: 30px;
    width: 90px;
  }
 }
 .cabinet__logo:hover{
    color: $secondary-color;
    border: 1px solid #868484;
    background-color: #ffffff;
  }
.cabinet__profile{
  @extend %propertis;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 165px;
  border: 1px solid  $logo-green;
  background-color: #ffffff;
  color: $logo-green;
  @media screen and (max-width: 768px) {
    height: 50px;
    width: 200px;
    border: 2px solid $logo-green;
  }
  @media screen and (max-width: 600px) {
     white-space:normal;
     min-height: 54px;
     width: 160px;
   }
  @media screen and (max-width: 500px) {
    min-height: 30px;
    width: 90px;
  }
}
.cabinet__profile:hover{
    background-color: $logo-green !important;
    color: #ffffff !important;
  }
.set__box{
    display: flex;
    margin: -100px auto 0px auto;
    @media screen and (max-width: 768px) {
        margin: -15px auto -20px 25px;
    }
  }
.set__main{
  @extend %propertis;
  position: relative;
  width: 185px;
  border: 1px solid  $secondary-color;
  background-color: #ffffff;
  color: $secondary-color;
  z-index: 30;
  @media screen and (max-width: 768px) {
    height: 50px;
    min-width: 280px;
    border: 2px solid $secondary-color;
  }
  @media screen and (max-width: 650px) {
    white-space:normal;
     height: auto;
     min-height: 40px;
     min-width: 185px;
     margin: 20px auto 0px auto;
   }
   &:hover{
      border: 1px solid  #ffffff;
      background-color: $secondary-color;
      color: #ffffff;
   }
}
.delete__company{
  @extend %propertis;
  width: 165px;
  color: red;
  border: 1px solid red;
  background-color: #ffffff;
  @media screen and (max-width: 950px) {
        display: none;
    }
}
.profile__top{
  @extend %propertis;
  width: 165px;
  border: 1px solid  $logo-green;
  background-color: #ffffff;
  color: $logo-green;
    @media screen and (max-width: 950px) {
        display: none;
    }
}
.fa-edit,
.fa-trash{
    line-height: 1.8;
    display: none;
    @media screen and (max-width: 950px) {
        display: block;
    }
}
.row__buttons{
    white-space: nowrap;
    margin-top: -30px;
    @media screen and (max-width: 950px) {
         margin-top: -60px;
    }
    @media (max-width: 650px){
     width: 100%;
     display: flex;
     justify-content: center !important;
     margin-top: 5px;
  }
}
.bottom__box{
    @media (max-width: 650px){
        display: flex;
        justify-content: center;
    }
}
.delete__bottom{
  @extend %propertis;
  width: 165px;
  color: #ffffff;
  border: 1px solid red;
  background-color: red;
  @media (max-width: 768px){
    height: 50px;
    min-width: 200px;
    float: right;
    color: red;
    border: 2px solid red;
    background-color: #ffffff;
    font-size: 20px;
    font-weight: 500;
  }
}
.delete__bottom:hover{
    color: red;
    border: 1px solid red;
    background-color: #ffffff;
  @media (max-width: 768px){
    color: #ffffff;
    border: 1px solid red;
    background-color: red;
  }
}
.caption{
  caption-side: top;
  color: #26a647;
  font-weight: 600;
  line-height: 1;
  @media (max-width: 768px){
     padding-left: 10px;
     max-height: 30px;
  }
  @media (max-width: 600px){
    position: relative;
    display: flex;
    max-height: 40px;
    min-width: 100%;
    justify-content: center;
    align-items: center;
  }
}
</style>
