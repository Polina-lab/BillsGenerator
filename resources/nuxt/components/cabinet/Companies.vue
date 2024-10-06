<template>
  <div v-if="mainCompany" class="green__block"><!-- delete 'v-if-"mainCompany"', when code "if one firm - it is main firm" is exist-->
    <div class="container-fluid row container-header">
      <div class="col-12 col-md-9 col-lg-10 col-xl-10">
        <div class="row box__caret">
          <h1 id="company__name">{{ mainCompany.company_name }}</h1>
          <h2 id="company__description">{{ $t('User.main_company') }}</h2>
        </div>
      </div>

      <div class="col-12 col-md-3 col-lg-2 col-xl-2 pl-md-5 pl-lg-0 pl-xl-4 text-right mobile-container-0">
          <div class="logo__box">
            <div v-if="mainCompany.logo != null" class="row justify-content-center">
              <img id="company__logo" :src="serverUrl + mainCompany.logo" alt="Your company logo.">
            </div>
            <div v-else class="row justify-content-end">
              <img id="standart__logo" src="@/assets/img/cabinet/company_main_grey.svg" alt="Someone's photo.">
            </div>
          </div>
          <div class="button__box">
            <div v-if="(user.permissions.indexOf( 'edit-firm' ) != -1) || (user.permissions.indexOf( 'edit-company' ) != -1)"> <!-- check -->
              <button   @click="setLogo()"  class="cabinet__logo">{{ $t('User.change_logo') }}
              </button>
            </div>
            <div v-if="(user.permissions.indexOf( 'edit-firm' ) != -1) || (user.permissions.indexOf( 'edit-company' ) != -1)"> <!-- check -->
              <button
              class="cabinet__profile">
                  {{ $t('bills.edit_f') }}
              </button>
            </div>
          </div>
      </div>
    </div>

    <div class="container-fluid row mobile-container-1">
      <div class="col-12 col-xl-5" v-if="user.local_data.roles">
        <table>
          <caption class="caption">{{ $t('User.general_info') }}</caption>
          <tr>
            <td>{{ $t('bills.company_name') }}</td>
            <td class="black__text">{{  mainCompany.name ? mainCompany.name : "-"}}</td>
          </tr>
          <tr>
            <td>{{ $t('bills.reg_num') }}</td>
            <td class="black__text">{{ mainCompany.reg_num ? mainCompany.reg_num : "-" }}</td>
          </tr>
          <tr>
            <td>{{ $t('bills.vat_amount') }}</td>
            <td class="black__text">{{ mainCompany.km ? mainCompany.km : "-" }}</td><!-- clarify the correctness of the data -->
          </tr>
          <tr>
            <td>{{ $t('bills.representative_name') }}</td>
            <td class="black__text">{{ mainCompany.representative_name ? mainCompany.representative_name : "-" }}</td>
          </tr>
          <tr>
            <td>{{ $t('User.contact_address') }}</td>
            <td class="black__text">{{ mainCompany.address ? mainCompany.address : "-" }}</td>
          </tr>
        </table>
      </div>

      <div class="col-12 col-xl-5 mt-5">
        <table>
          <tr>
            <td>{{ $t('bills.title') }}</td>
            <td class="black__text">{{  mainCompany.name ? mainCompany.name : "-"}}</td>
          </tr>
          <tr>
            <td>{{ $t('User.email') }}</td>
            <td class="black__text">{{ mainCompany.email ? mainCompany.email : "-" }}</td>
          </tr>
          <tr>
            <td>{{ $t('bills.km_reg_nr') }}</td>
            <td class="black__text">{{ mainCompany.km_reg_num ? mainCompany.km_reg_num : "-" }}</td>
          </tr>
          <tr>
            <td>{{ $t('bills.phone') }}</td>
            <td class="black__text">{{ mainCompany.phone ? mainCompany.phone : "-" }}</td>
          </tr>
        </table>
      </div>

    </div>

     <div class="container-fluid row">
        <div v-for="(item, i) in mainCompany.banks" :key="i+1" class="col-12 col-xl-5 mobile-container-2">
          <table>
            <caption class="caption">{{ $t('User.bank_data') }}</caption>
            <tr>
              <td>{{ $t('bills.bank_name') }}</td>
              <td class="black__text">{{ mainCompany.banks[i].bank_name ? mainCompany.banks[i].bank_name : "-" }}</td>
            </tr>
            <tr>
              <td>{{ $t('bills.bank_num') }}</td>
              <td class="black__text">{{ mainCompany.banks[i].bank_num ? mainCompany.banks[i].bank_num : "-" }}</td>
            </tr>
            <tr>
              <td>{{ $t('bills.bank_address') }}</td>
              <td class="black__text">{{ mainCompany.banks[i].bank_address ? mainCompany.banks[i].bank_address : "-" }}</td>
            </tr>
            <tr>
              <td>{{ $t('bills.swift') }}</td>
              <td class="black__text">{{ mainCompany.banks[i].bank_swift ? mainCompany.banks[i].bank_swift : "-" }}</td>
            </tr>
          </table>
        </div>
      </div>


        <div v-if="mainCompany.footer" class="row container-fluid">
          <div class="col-12 col-xl-9 mobile-container">
            <h3 id="general__info" class="mt-5">{{ $t('bills.footer_data_tab') }}</h3>
            <p>{{ mainCompany.footer }}</p>
          </div>
        </div>

    <SetLogoModal v-if="isSetLogoModalVisible" :isSetLogoModalVisible="isSetLogoModalVisible" @closeModal="$store.commit('SET_OBJ' , { name : 'isSetLogoModalVisible' , value: false})"/>

  </div>
</template>
<script>
import SetLogoModal from "@/components/cabinet/SetLogoModal";

export default {
    name: "Companies",
    middleware:[ 'auth' ],
    components: {SetLogoModal},

  async asyncData({ store, params }) {
      await store.dispatch('bills/fetchFirmsList');
  },
    data: () => ({
    serverUrl: process.env.serverUrl,
    selectLang: "en",
    isSetLogoModalVisible: false,
  }),
  computed: {
    user() {
      return this.$store.getters['users/getUserDetail'];
    },
    companies() {
      return this.$store.state.bills.firmsList;
    },
    mainCompany() {
      return this.$store.state.bills.firmsList.find(c => c.main_firm == 1);
      //it is code - to do visible green-block, in the future will be changed to the following code:
      //return this.$store.state.bills.firmsList[this.$store.state.bills.firmsList.map((o) => o.main_firm).indexOf(1)];
    },
  },
  methods: {
        setNewFirm(){
          this.isFirmModalVisible = true;
          this.FirmTitle = 'Create Company';
          this.firm = new Firm({ vat: [ new VAT({ default: true }) ] });
          },
  setLogo(){
       this.isSetLogoModalVisible = true;
      }
  }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/var";

.row{
  @media (max-width: 600px){
    margin-right: 0 !important;
    margin-left: 0 !important;
  }
}
.caption{
  caption-side: top;
  color: #26a647;
  font-weight: 600;
  line-height: 2;
  @media (max-width: 849px){
     padding-left: 10px;
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

/**box */
.container-header{
    margin-left: 3px;
    @media (max-width: 768px){
     margin-left: 15px;
  }
}
.box__caret{
  display: flex;
  flex-direction: column;
  margin-top: -12px;
  @media (max-width: 650px){
     flex-wrap: wrap;
  }
}
#company__name{
  font-size: 24px;
  font-weight: 700;
  letter-spacing: 1.8px;
  color: #646464;
  line-height: 1;
  @media (max-width: 849px){
     font-size: 34px;
  }
  @media (max-width: 650px){
     white-space:normal;
     display: flex;
     justify-content: center;
     width: 100%;
  }
}
#company__description{
  font-weight: 600;
  font-size: 18px;
  color: $logo-green;
  line-height: 1;
  @media (max-width: 849px){
     font-size: 28px;
  }
  @media (max-width: 650px){
     display: flex;
     justify-content: center;
     align-items: center;
     margin-left: auto;
     margin-right: auto;
  }
}
#general__info{
    color: $logo-green;
    font-size: 16px;
    line-height: 4;
}

.green__block{
  font-size: 14px;
  margin-top: 30px;
  background-color: rgb(226, 240, 234);
  border: 2px solid rgb(220, 242, 248);
  padding: 30px 30px;
  @media (max-width: 1216px){
    margin-top: 40px;
    margin-left: auto;
    margin-right: auto;
    padding: 40px 5px !important;
  }
  @media (max-width: 849px){
    font-size: 18px;
  }
}
.logo__box{
  display: flex;
  justify-content: center;
  overflow: hidden;
  align-items: center;
  background-color: #ffffff;
  margin-top: 40px;
  border-radius: 50% 0% 50% 80%;
  width: 200px;
  height: 180px;
  @media screen and (max-width: 900px) {
      margin-left: -20px;
   }
   @media screen and (max-width: 768px) {
       margin-top: -190px;
       float:right;
   }
    @media screen and (max-width: 650px) {
      border-radius: 50% 50% 50% 50%;
      margin-top: -100px;
      float: none;
      display: flex;
      margin-left: auto;
      margin-right: auto;
   }
}
.button__box{
  position: relative;
  min-height: 40px;
    z-index: 999;
  @media (max-width: 1200px){
       margin-left: 0% !important;
    }
  @media (max-width: 1100px){
      margin-left: -30% !important;
    }
  @media (max-width: 900px){
      margin-left: -60% !important;
    }
   @media screen and (max-width: 768px) {
    position: relative;
    display: flex;
    width: 100%;
    flex-direction: row-reverse;
    justify-content: space-between;
    margin-left: -5% !important;
   }
}
.mobile-container-0{
  position: relative;
  margin-top: -90px;
  @media screen and (max-width: 768px) {
    min-width: 100% !important;
    margin-top: 100px;
    margin-left: 4%;
  }
}
#company__logo{
  position: relative;
  z-index: 2;
  width: 60%;
  height: auto;
  @media screen and (max-width: 900px) {
      width: 50%;
   }
    @media (max-width: 768px){
     width: 60%;
    }
}
#standart__logo{
    width: 120px;
    height: 120px;
}
.mobile-container-1{
  margin-top: -200px;
 @media (max-width: 768px){
     margin-top: 20px;
  }
}
.mobile-container-2{
  margin-top: 20px;
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

  }
}
table tr td:nth-child(1){
  width: 210px;
  color: $logo-green;
  @media (max-width: 849px){
     padding-left: 15px;
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
     padding-left: 60px;
  }
  @media (max-width: 600px){
    position: relative;
    display: flex;
    max-height: 40px;
    min-width: 100%;
    justify-content: center;
    align-items: center;
    padding-left: 0 !important;
  }
}

%propertis{
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
  margin-bottom: 15px;
  height: 40px;
  width: 165px;
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
  border: 1px solid $secondary-color;
  color: #ffffff;
  background-color: $secondary-color;
  @media screen and (max-width: 849px) {
    height: 50px;
    width: 185px;
    color: $secondary-color;
    border: 2px solid $secondary-color;
    background-color: #e2f0ea;
  }
  @media screen and (max-width: 530px) {
      white-space:normal;
      width: 170px;
      min-height: 40px;
   }
  @media screen and (max-width: 440px) {
    white-space: normal;
    min-height: 54px;
    width: 120px;
  }
  &:hover{
    color: $secondary-color;
    border: 1px solid #868484;
    background-color: #ffffff;
  }
 }
 .cabinet__logo:hover{
    color: $secondary-color;
    border: 1px solid #868484;
    background-color: #ffffff;
  }
.cabinet__profile{
  @extend %propertis;
  border: 1px solid  $logo-green;
  background-color: #e2f0ea;
  color: $logo-green;
  @media screen and (max-width: 849px) {
    height: 50px;
    width: 185px;
    border: 2px solid $logo-green;
  }
  @media screen and (max-width: 530px) {
    white-space:normal;
     min-height: 40px;
     width: 170px;
   }
  @media screen and (max-width: 440px) {
    white-space:normal;
    min-height: 54px;
    width: 120px;
  }
}
.cabinet__profile:hover{
    background-color: $logo-green !important;
    color: #ffffff !important;
  }

</style>
