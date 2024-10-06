<template>
<div>
  <div class="row justify-content-start">
    <div class="arrow__container">
      <nuxt-link to="/admin/users" prefetch class="pointer delete__styles"><i class="fas fa-arrow-left"></i><h6>{{ $t('User.user_list') }} / <strong>{{ $t('User.user_profile') }}</strong></h6></nuxt-link>
    </div>
  </div>
<div class="d-flex justify-content-center">
  <div class="container-fluid" id="mw1400">
    <div v-if="$auth.user.username === user.username">
      <Notification :unpaid="unpaid" @closeNotification="unpaid = false"/>
    </div>
    <div class="box" v-if="user.local_data" >
      <div class="row justify-content-between">
        <div class="col-12 col-sm-6 col-md-6 col-lg-9 mobile-container">
          <div class="name">
          <h1 id="cabinet__name">{{ user.local_data.firstname ? user.local_data.firstname : " "}} {{  user.local_data.lastname ? user.local_data.lastname : " "}}</h1>
          </div>
          <div class="name">
          <h2 id="cabinet__role">{{ user.local_data.roles.name ? user.local_data.roles.name : " " }}</h2>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3 float-right img__row">
          <div class="float-right img__box">
              <img id="user__img" :src="getAvatar(user.local_data.avatar)" :alt="user.username">
          </div>
        </div>
      </div>
      <div class="move__line"></div>
      <div class="col-12">  
        <div class="row justify-content-between float-right mobile-container-01">
            <div class="button__box">
              <button class="float-right cabinet__profile"
                      :class="{ 'zeroOpacity' : !isElementInteractible('edit-user', user.id) }"
                      @click="$router.push(`/admin/users/edit/${user.local_data.id}`)"
                      :disabled="!isElementInteractible('edit-user', user.id)">
                {{ $t('User.edit_profile') }}
              </button>
            </div>
        </div>
        </div>
        
      
      <div class="row mobile-row-1">
        <div class="col-12 col-xl-5 mobile-container-1">
          <table>
            <tr>
              <td>{{ $t('User.name') }}</td>
              <td class="user__name"><span>{{ user.local_data.firstname ? user.local_data.firstname : "--" }}</span></td>
            </tr>
            <tr>
              <td>{{ $t('User.lastname') }}</td>
              <td class="user__lastname"><span>{{ user.local_data.lastname ? user.local_data.lastname : "--" }}</span></td>
            </tr>
            <tr>
              <td>{{ $t('User.email') }}:</td>
              <td class="user__mail"><span>{{ user.email ? user.email : "--" }}</span></td>
            </tr>
            <tr>
              <td>{{ $t('User.contact_address') }}</td>
              <td>{{ user.contact_address ? user.contact_address : "--" }}</td>
            </tr>
          </table>
        </div>

        <div class="col-12 col-xl-5 mobile-container-2">
          <table>
            <tr>
              <td>{{ $t('User.phone') }}</td>
              <td>{{ user.local_data.phone ? user.local_data.phone : "--" }}</td>
            </tr>
            <tr>
              <td>{{ $t('User.lang_spoken')}}</td>
              <td >
                <span class="user__lang" v-for="(item, idx) in this.$i18n.locales" :key="idx">
                  <span v-if="user.local_data.langs_id === item.id">
                    {{ item.name + '&nbsp;' }}
                  </span>
                </span>
              </td>
            </tr>
            <tr>
              <td>{{ $t('User.date_logged') }}:</td>
              <td><span >{{ user.last_login ? user.last_login  : "--" }}</span></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row mobile-row-2">
        <div class="col-12 col-sm-12 col-xl-5 mobile-container-3">
          <table>
            <tr v-if="$auth.user.username === user.username &&
                      $store.state.payment.paymentPlans &&
                      $store.state.current_team.payment_plan_id">
              <td>{{ $t('User.current_package') }}</td>
              <td>{{ this.$store.state.payment.paymentPlans.find(p => p.id === $store.state.current_team.payment_plan_id).string.toUpperCase()  }}</td>
            </tr>
            <tr v-if="$auth.user.username === user.username">
              <td>{{ $t('User.account_balance') }}</td>
              <td>{{ $store.state.current_team.current_balance ? $store.state.current_team.current_balance  + "€" : '--'}}</td>
            </tr>
            <tr v-if="$auth.user.username === user.username">
              <td class="pre__company">{{ $t('User.companies') }}</td>
              <td class="company__string">
                <span>{{ companies.length === 0 ? '- - -' : companies.map(x => x.company_name).toString().replace(/,/g, ', ').slice(0,-2)}}</span>
                <!-- ссылки на компании мы дать не можем (-->
              </td>
            </tr>
          <!--
            <tr class="variant" v-if="$auth.user.username === user.username">
              <td class="pre__permissions">{{ $t('User.permissions') }}</td>
              <td class="permissions__string">
                {{ user.permissions.length === 0 ? '- - -' : user.permissions.toString().replace(/,/g, ', ')}}
              </td>
            </tr>
            -->
            <tr v-if="user.local_data.roles">
              <td>{{ $t('User.Roles') }}</td>
              <td class="user__roles"><span>{{ user.local_data.roles.name ? user.local_data.roles.name : "-" }}</span></td>
            </tr>
          </table>
        </div>
        <div class="move__line"></div>
        <div class="col-12 col-sm-12 col-xl-4 mobile-container-4">
          <table>
            <tr>
              <td><nuxt-link :to="'/admin/users/edit/'+ $auth.user.local_data.id +'?page=package'"  >{{ $t('profile.package_settings') }}</nuxt-link></td>
            </tr>
            <tr>
              <td><nuxt-link to="/bills?user=1&my_bills=true">{{ $t('profile.my_bills') }}</nuxt-link></td>
            </tr>
          </table>
        </div>
      </div>

      <div class="row mobile-row-4">
        <!--<div class="offset-0 offset-md-auto offset-lg-9 offset-xl-10 col-12 col-md-2 col-lg-auto mobile-container-5">-->
          <div class="col-12 justify-content-end float-right row__password">
          <n-link class="float-right cabinet__password" :to="'/admin/users/edit/'+ $auth.user.local_data.id +'?page=password'">{{ $t('User.change_password') }}</n-link>
          <!--</div>-->
        </div>
      </div>
      
    </div>
      <div class="statistic" v-if="$auth.user.extra_info">
        <!--statistic-->
        <StatisticCabinet :data="$auth.user.extra_info"/>
    </div>
    <div class="companies" v-if="$auth.user.username == user.username">
        <!--companies-->
        <Companies/>
    </div>
    <div class="d-flex justify-content-start button__block">
      <div class="row">
        <h1 id="button__text">{{ $t('bills.firm') }}</h1>
        <button 
            @click="setNewFirm" 
            class="add__new"
            :class="{
            '': isElementInteractible('add-new-firm'),
            'zeroOpacity': !isElementInteractible('add-new-firm')
              }"
            :disabled="!isElementInteractible('add-new-firm')">
                {{ $t('bills.add_new_firm') }}
         </button>
      </div>
    </div>
    <div class="additional" v-if="$auth.user.username == user.username">
        <!--additional companies-->
        <Additional/>
    </div>
  </div>

  <LazyFirmModalComponent :firm="firm"
                            :isFirmModalVisible="isFirmModalVisible"
                            :title="FirmTitle"
                            @closeFirmModal="isFirmModalVisible = $event; $store.dispatch('bills/fetchFirmsList');">
  </LazyFirmModalComponent>

</div>
</div>
</template>

<script>
//import GeneratorComponent from '@/components/bills/GeneratorComponent';
import isElementInteractible from "../../../mixins/isElementInteractible";
import StatisticCabinet from "@/components/cabinet/StatisticCabinet";
import Notification from "@/components/common/Notification";
import Companies from "@/components/cabinet/Companies";
import Additional from "@/components/cabinet/Additional";
import { Firm, VAT } from '../../../assets/js/models/Bill';
import Modal from "../../../components/common/Modal";

export default {
  name: "user_edit",
  mixins: [ isElementInteractible ],
  components: { Modal, StatisticCabinet, Notification, Companies, Additional,LazyFirmModalComponent: () => import('@/components/bills/FirmModalComponent')},
  layout: "admin",
  middleware:[ 'auth' ],

  async asyncData({ store, params }) {
      await store.dispatch('users/fetchUserDetail', params.id)
      /*if (!store.state.users.userRoles.length || !store.state.users.userPermissions.length) {
      await store.dispatch('users/fetchUserRoles')
    }*/
    await store.dispatch('bills/fetchFirmsList');
  },

  data: () => ({
    selectLang: "en",
    unpaid: true,
    isFirmModalVisible: false,
    itemModal: false,
    FirmTitle : "Create Company",
    firm: new Firm({ vat: [ new VAT({ default: true }) ] }),
    isSetPhotoModalVisible: false
  }),

  destroyed() {
    this.isSetPhotoModalVisible = false
  },

  computed: {
    user() {
      return this.$store.getters['users/getUserDetail'];
    },
    companies(){
      return this.$store.state.bills.firmsList;
    },
  },
  methods:{  
    setNewFirm(){
      this.itemModal = true,
      this.isFirmModalVisible = true;
      this.FirmTitle = 'Create Company';
      this.firm = new Firm({ vat: [ new VAT({ default: true }) ] });
    },
    async getUser(){
      await this.$store.dispatch('users/fetchUserDetail')
    },
    getAvatar(data){
      if(data === null) {
       return require('/assets/img/cabinet/profile_UserBlue.svg');
      }else{
        return process.env.serverUrl + data ;
      }
    }
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
.d-flex{
  margin-left: 40px;
  margin-right: 40px;
  @media (max-width: 768px){
      margin-left: 0px !important;
      margin-right: 0px !important;
   }
}
.mobile-container{
  padding-left: 35px;
}
.mobile-container-01{
    @media (max-width: 768px){
      float: none !important;
      margin-top: -70px;
      padding-left: 20px;
      justify-content: flex-start;
   }
    @media (max-width: 600px){
      margin-top: 0px;
      padding-left: 0px;
   }
}
.button__box{
  position: relative;
  @media (max-width: 1230px){
       margin-left: 0 !important;
    }
  @media (max-width: 1200px){
      display: flex;
      justify-content: space-between;
    }
  @media screen and (max-width: 768px) {
      margin-top: -20px;
  }
  @media screen and (max-width: 749px) {
     width:100%;
  }
  @media screen and (max-width: 600px) {
    margin-left: auto !important;
    margin-right: auto !important;
    min-width: 100%;
    width: 100%;
  }
}
.container-fluid{
  font-family: 'Montserrat', sans-serif;
}
/**box */
.head__name{
  display: flex;
  justify-content: left;
  padding-left: 1.5rem;
   @media screen and (max-width:600px){
    min-width: 100%;
    justify-content: center;
    text-align: center;
    padding-left: 0rem;
   }
}
#cabinet__name{
  font-size: 24px;
  font-weight: 700;
  letter-spacing: 1.8px;
  color: #646464;
  line-height: 1.5;
  padding-left: 1.5rem;
  @media screen and (max-width:800px){
    text-overflow: ellipsis;
    white-space: nowrap; 
    overflow: hidden;
  }
  @media screen and (max-width:600px){
    padding-left: 0rem;
  }
}
#cabinet__role{
  font-weight: 600;
  font-size: 18px;
  color: $logo-blue;
  line-height: 1;
  padding-left: 1.5rem;
  @media screen and (max-width:600px){
    display: flex;
    min-width: 100%;
    justify-content: center;
    padding-left: 0rem;
  }
}
.box{
  margin-top: 40px;
  border: 2px solid rgb(220, 242, 248);
  padding: 30px 40px 40px 30px;
  font-size: 18px;
  @media (max-width: 1200px){
    max-height: 750px;
    margin-top: 40px;
    margin-left: auto;
    margin-right: auto;
  }
    @media (max-width: 768px){
       max-height: 1150px;
  }
      @media (max-width: 600px){
       max-height: 1600px;
  }
}
.img__row{
  height: 150px;

}
.img__box{
  height: 140px;
  background-size: cover;
  overflow: hidden;
  max-width: 130px;
  width: 130px;
  margin-right: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  @media screen and (max-width:600px){
    float: none !important;
    margin-right: auto;
    margin-left: auto;
  }
}

#user__img{  
  position: relative;
  width:auto;
  height: 100%;
}
.user__name,
.user__lastname,
.user__mail,
.user__roles{
  text-overflow: ellipsis; 
  white-space: nowrap; 
  overflow: hidden;
  max-width: 250px;
  width: 250px;
  @media screen and (max-width: 1200px) {
    max-width: 100%;
    width: 100%;
  }
}
.user__roles{
  z-index: 99;
}
.mobile-container-3 table{
  margin-top: 40px;
}
.move__line{
  @media screen and (max-width: 700px) {
  min-width: 100%;
  }
}
.mobile-container-4 table{
  @media screen and (max-width: 1200px) {
    margin-left: 20px;
  }
  @media screen and (max-width: 768px) {
    margin-top: 40px !important;
  }
  @media screen and (max-width: 749px) {
    flex-direction: column;
    align-items: left;
    padding-left: -20px;
  }
    @media screen and (max-width: 700px) {
    display: flex;
    width: 100%;
    flex-direction: column;
    margin-left: -5px;
    align-items: left;
    padding-left: 20px;
  }
    @media screen and (max-width: 600px) {
    margin-top: 30px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: auto;
    margin-right: auto;
  }
}
.mobile-row-4{
  position: relative;
  max-width: 100%;
  min-width: 100%;
  width: 100%;
  @media screen and (max-width: 1200px) {
    margin-left: auto !important;
    margin-right: 0 !important;
  }
  @media screen and (max-width: 600px) {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: auto;
  }
}
.row__photo,
.row__profile,
.row__password{
 position: absolute;
 margin-top: -200px;
  @media screen and (max-width: 1250px){
    position: relative;
    padding-left: 0;
     margin-top: 0px;
 }
   @media screen and (max-width: 991px) {
     width: 100%;
      justify-content: center !important;
   }

}
.row__password{
  @media screen and (max-width: 991px) {

   }
}
table tr td,
span{
  white-space: nowrap;
  line-height: 1.8;
}
table tr td:nth-child(1){
  color: $logo-blue;
}
table tr td:nth-child(2){
  padding-left: 60px;
}
table tr td:nth-child(3){
  padding-left: 10px;
}

  .mobile-container-1 table tr td:nth-child(1),
  .mobile-container-2 table tr td:nth-child(1),
  .mobile-container-3 table tr td:nth-child(1){
    padding-left: 20px;
    min-width: 160px;
    max-width: 160px;
    width: 160px;
  @media (max-width: 600px){
    line-height: 10px;
    width: 100%;
    text-align: center;
    margin-left: auto !important;
    margin-right: auto !important;
    margin-top: 45px;
    display: flex;
    justify-content: center;
    font-weight: 600;
     padding-left: 0px;
  }
  }
  .mobile-container-1 table tr td:nth-child(2),
  .mobile-container-2 table tr td:nth-child(2),
  .mobile-container-3 table tr td:nth-child(2),
  .company__string{
  @media (max-width: 600px){
    position: absolute;
    display: flex;
    justify-content: center;
    width: 100%;
    min-width: 100%;
    text-align: center;
    line-height: 30px;
    padding-left: 0px !important;
    margin-left: 0px !important;
    }
  }
  .mobile-row-1{
    @media screen and (max-width: 1200px){
      margin-top: 90px;
      margin-left: 0px !important;
    }
    @media (max-width: 768px){
      margin-top: 210px;
    }
    @media (max-width: 600px){
      margin-top: 100px;
    }
  }
  .mobile-row-2{
    margin-top: 20px;
  }
  .mobile-container-1{
    @media (max-width: 1200px){
    margin-top:-100px;
    }
    @media (max-width: 768px){
    margin-top:-150px;
    }
  }
  .mobile-container-2{
    margin-left: 50px;
    @media screen and(max-width: 1200px) {
       margin-top: 0px;
    }
    @media (max-width: 768px){
      margin-top: 50px;
    }
  }
  .mobile-container-3{
    margin-top:-50px;
    min-height: 160px;
    @media (max-width: 768px){
      margin-top: 0px;
    }
  }
  table{
    @media (max-width: 1200px){
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    }
  }
  .mobile-container-1,
  .mobile-container-2{
    @media (max-width: 1200px){
    display:flex;
    align-items: center;
    justify-content:space-around;
    margin-left: auto;
    margin-right: auto;
  }
}
.company__string span,
.user__name span,
.user__lastname span,
.user__mail span,
.user__roles span{
    @media screen and (max-width: 1200px){
    white-space: normal;
  }
    @media (max-width: 600px){
      display: flex;
      white-space: nowrap;
      min-width: 100%;
      height: 100px;
      overflow: hidden;
      text-overflow: ellipsis;
      justify-content: center;
    }
  }
  #cabinet__name:hover{
      position:sticky;
      height: 100%;
      overflow: visible;
      border: 1px solid $logo-blue;
      border-radius: 10px;
      white-space: normal;
      word-break: break-all;
      white-space: wrap;
      flex-wrap: wrap;
      width: 100%;
      background-color: #ffffff;
      z-index: 999;
  }
   .company__string span:hover,
   .user__name span:hover,
   .user__lastname span:hover,
   .user__mail span:hover,
   .user__roles span:hover{
     @media screen and (max-width: 600px){
      position:sticky;
      height: 100%;
      overflow: visible;
      border: 1px solid $logo-blue;
      border-radius: 10px;
      white-space: normal;
      word-break: break-all;
      white-space: wrap;
      flex-wrap: wrap;
      width: 100%;
      background-color: #ffffff;
      z-index: 999;
     }
   }
.user__lang{
  display: inline-block;
}
%propertis{
  margin-top: 20px;
  margin-bottom: 15px;
  height: 40px;
  white-space: nowrap;
  font-size: 13px;
  border-radius: 6px;
    @media (max-width: 1239px){
    margin-right: 20px;
    font-size: 14px;
  }
  @media (max-width: 768px){
    height: 40px;
    font-size: 20px;
  }
  @media screen and (max-width: 500px) {
    font-size: 12px;
  }
}
.cabinet__profile{
  position: relative;
  z-index: 30;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 160px;
  color: $logo-blue;
  border: 1px solid $logo-blue;
  background-color: #ffffff;
  @extend %propertis;
  @media screen and (max-width: 768px) {
     white-space:normal;
     min-height: 54px;
     width: 180px;
   }
    @media screen and (max-width:600px){
    margin-right: auto;
    margin-left: auto;
  }
  &:hover{
    color: #ffffff;
    border: 1px solid #ffffff;
    background-color: $logo-blue;
  }
}
.cabinet__password{
  position: relative;
  z-index: 30;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 190px;
  color: $secondary-color;
  border: 1px solid $secondary-color;
  background-color: #ffffff;
  padding-right: 0px !important;
  @extend %propertis;
  @media screen and (max-width: 1250px){
    width: 190px;
 }
   @media screen and (max-width: 1200px){
    width: 180px;
 }
   @media screen and (max-width: 768px) {
     white-space:normal;
     min-height: 54px;
     width: 200px;
   }
  @media screen and (max-width:600px){
    float: none !important;
    margin-right: auto;
    margin-left: auto;
  }
  @media screen and (max-width: 500px) {
    min-height: 30px;
    width: 160px;
  }
  &:hover{
    color: #ffffff;
    border: 1px solid #ffffff;
    background-color: $secondary-color;
  }
}
.pre__permissions,
.pre__company{
  vertical-align: top;
}
/*.permissions__string{
  display: flex;
  max-width: 80%;
  max-height: 100px;
  overflow-y: scroll;
  scroll-behavior: right;
  white-space: normal;
  word-break: break;
}
.permissions__string:hover{

}
.variant{
  @media screen and (max-width: 600px){
    display: none;
  }
}*/
.button__block{
  max-width: 1400px;
  margin: 40px 15px;
}
#button__text{
  display: flex;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 700;
  letter-spacing: 1.8px;
  color: #646464;
  line-height: 1;
}
.add__new{
  width: 160px;
  margin-left: 60px;
  background-color: #ffffff;
  border: 2px solid $secondary-color;
  color: $secondary-color;
  border-radius: 5px;
  height: 40px;
  &:hover{
    border: 2px solid $secondary-light;
    color: $secondary-light;
  }
}
.col-sm-6,.col-md-6,
.col-lg-2, .col-12{
  padding-right: 0px !important;
}
.col-sm-6,
.col-12{
  padding-left: 0px !important;
}
.row{
  margin-right: 0px !important;
  margin-left: 0px !important;
}
.fa-arrow-left{
  font-size: 25px;
  letter-spacing: 10px;
  @media screen and (max-width: 768px){
    font-size: 36px;
  }
}
.delete__styles{
  display: flex;
  justify-content: center;
  align-items: center;
  color: $secondary-color;
  text-decoration: none;
   margin: 20px 0px 30px 10px;
}
.delete__styles h6{
    position: relative;
    margin-top: auto;
    margin-bottom: auto;
    text-align: center;
}
.delete__styles h6 strong{
  color: #000000;
}
</style>
