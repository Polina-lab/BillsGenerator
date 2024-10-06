<template>
  <div>
    <div class="container mw950 add_border_bottom1" >
      <div class="p-0" >
        <label for="customer" class="mlm20 customer__label">{{ $t('payments.customer') }}</label>
        <br/>
        <div class="w400 d-flex align-items-center">
          <div class="mr-auto" id="customer">
            {{buyer.firstname + ' ' + buyer.lastname}}
          </div>
          <div class="ml-auto">
            <button v-if="!$route.path.includes('edit')" class="btn btn-outline-secondary mb-2 float-right grey_btn mtm25"
                    @click="$emit('next', 3)">
              {{ $t('common.edit') }}
            </button>
          </div>
        </div>
      </div>
    </div><!--.mw950-->
    <div class="container mw950 add_border_bottom2">
      <div class="p-1">
        <label for="pricing" class="mlm20 label__pricing">{{ $t('payments.payment_plan') }}</label>
        <br/>
        <div class="w400 d-flex align-items-center pricing__box">
          <div class="mr-auto" id="pricing">
            {{ payment_plan.string[0].toUpperCase() + payment_plan.string.slice(1) }}
            {{ isFree ? $t('pricing.free') : sum.total + ' ' + payment_plan.currency }}
<!--            <span>+</span> <input id="month" v-model="month" type="number" size="3"> =-->
          </div>
          <div class="ml-auto">
            <button class="btn btn-outline-secondary mb-2 float-right grey_btn"
                    @click="$route.path.includes('edit') ?
                      $emit('returnToSettings') : $emit('next', 4)">
              {{ $t('common.edit') }}
            </button>
          </div>
        </div>
      </div>
    </div><!--.mw950-->
    <div v-if="!isFree" class="d-flex justify-content-center text-center pt-5">
      <div class="border__radius">
        <div class="double__border">
          <div class="m-2 pb-2 pl-4 mw600" style="width: 100%; z-index: 1;" >
            <div class="p-3 text-center">
              <h5 class="mt-2 ">{{ $t('coupons.code') }}</h5>
              <div class="mt-3" >
                <label for="couponCode">{{ $t('register.enter_coupon_code')}}</label>
                <input id="couponCode" class="form-control input_props offset-2 col-8 "
                       v-model="couponCode"
                       @input="isCouponValid ? '' : isCouponValid = null"
                       @keyup.enter="checkCoupon()"/>
              </div>
              <button class="btn btn-outline-primary pt-2 col-8 mt-3" style=" border-radius: 10px;" @click="checkCoupon()">{{ $t('coupons.check')}}</button>
              <div v-if="(isCouponValid != null && !isCouponValid)" style="color: red">{{ $t('coupons.invalid') }}</div>
              <div v-else style="color: #00b3ff">{{ $t('coupons.text') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div><!--coupons-->

    <div v-if="!isFree" class="d-flex justify-content-center pt-4">
      <h5 class="p-2 text-center green"  >{{ $t('payments.select_payment_method') }}</h5>
    </div>
    <div v-if="!isFree" class="container-fluid mw950">
        <div class="row text-center" style="vertical-align:bottom;">
          <div v-for="(method) in payment_methods" :key="method.id" class="col-4 p-3 bordered pointer" :class="{ 'selected': selected_payment === method.name }"
               @click="['bank_link', 'card'].includes(method.name) ? showError() : selected_payment = method.name"
          >
            <img class="m-2" :class="method.class" :src="method.image" :id="'payment_'+method.id" :alt="method.name">
            <label :for="'payment_'+method.id" style="text-align: left; color:#2fca69;">{{ $t(`payments.${method.name}_payment`) }}</label>
          </div>
        </div>
    </div><!--.container-->
    <div v-if="!isFree" class="pt-3 container-fluid" :class="{'mw500' : selected_payment === 'bank_link' }" >
      <div v-if="selected_payment === 'card'" class="text-center">
        <h5 class="m-3 green" >{{ $t(`payments.${selected_payment}_payment`) }}</h5>
        <PayingCardComponent :cardHolderProp="`${buyer.firstname} ${buyer.lastname}`"/>
      </div>
      <div v-if="selected_payment === 'bank_link'" class="text-center">
          <h5 class="m-3 green">{{ $t(`payments.${selected_payment}_payment`) }}</h5>
          <div class="row  mh95 pt-4">
            <div class="col-4 p-2">
              <a href="https://www.swedbank.ee" target="_blank">
              <img :src="require('assets/img/payment/icons/swedbank.svg')" class="bordered-5px  w-150 m-1" style="border-color: #e0a800"
                    alt="swed">
              </a>
            </div><!--col-4-->
            <div class="col-4 p-2">
              <a href="https://www.seb.ee/" target="_blank">
              <img :src="require('assets/img/payment/icons/seb.svg')" class="bordered-5px w-150 m-1"  style="border-color: #27f769"
                     alt="seb">
              </a>
            </div><!--col-4-->
            <div class="col-4 p-2" >
              <a href="https://www.lhv.ee/ru" target="_blank" >
              <img :src="require('assets/img/payment/icons/lhv.svg')" class="bordered-5px w-150 m-1" style="border-color: #797979"
                    alt="lhv">
              </a>
            </div><!--col-4-->
          </div>
          <div class="row mh95">
          <div class="col-4 p-2">
            <a href="https://www.coop.ee/" target="_blank">
            <img :src="require('assets/img/payment/icons/coop.svg')" class="bordered-5px w-150 m-1" style="border-color: #00a6ea"
                  alt="coop">
            </a>
          </div>
          <div class="col-4 p-2" target="_blank">
            <a href="https://luminor.ee/">
            <img :src="require('assets/img/payment/icons/luminor.svg')" class="bordered-5px w-150 m-1" style="border-color: #a46887"
                  alt="luminor">
            </a>
          </div>
          <div class="col-4 p-2">
            <a href="https://paypal.com/" target="_blank">
            <img :src="require('assets/img/payment/icons/paypal.svg')" class="bordered-5px w-150 m-1" style="border-color: #7c7eb6"
                  alt="paypal">
            </a>
          </div>
        </div>
      </div>
      <div class="pt-3 container-fluid mw950">
        <div v-if="selected_payment === 'invoice'" class="">
        <h5 class="m-3 text-center green">{{ $t(`payments.${selected_payment}_payment`) }}</h5>
        <div class="container p-3">
          <div class="col-6 text-center">
            <div class="btn btn-outline" :class="{ 'selected-3': selected_data_source === 'person' }" @click="selected_data_source = 'person'"><i class="fa fa-user fa-1.5x m-2" ></i>{{ $t('payments.for_person') }}</div>
          </div>
          <div class="col-6 text-center">
            <div class="btn btn-outline" :class="{ 'selected-3': selected_data_source === 'company' }" @click="selected_data_source = 'company'"><i class="fa fa-building fa-1.5x m-2"></i>{{ $t('payments.for_company') }}</div>
          </div>
        </div><!--container-->
          <div v-if="selected_data_source === invoice_type[0].name">
            <div class="row ">
              <div class="col-6">
                <label for="firstname">{{ $t('User.firstname') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="firstname"
                       :value="buyer.firstname"
                       :class="{ 'is-invalid': !buyer.firstname }"
                       @change="updateBuyerData($event)"
                />
              </div>
              <div class="col-6">
                <label for="lastname">{{ $t('User.lastname') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="lastname"
                       :value="buyer.lastname"
                       :class="{ 'is-invalid': !buyer.lastname }"
                       @change="updateBuyerData($event)"
                />
              </div>
            </div>
            <div class="row ">
              <div class="col-12">
                <label for="address">{{ $t('bills.address') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="address"
                       :value="buyer.address"
                       @input="handleAddress($event)"
                       :class="{ 'is-invalid': !buyer.address }"
                       @change="updateBuyerData($event)"
                       list="userAddressList"
                />
                <datalist id="userAddressList">
                  <option v-for="address in address_search_results"
                          :value="address"
                          :key="address">
                    {{ address }}
                  </option>
                </datalist>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
              </div>
              <div class="col-6">
                <label for="email">{{ $t('User.email') }}<sup class="red">*</sup></label>
                <input type="email" class="form-control" id="email" :value="this.$auth.user.email" >
              </div>
            </div>
          </div>
          <div v-if="selected_data_source === invoice_type[1].name" >
            <div class="row">
              <div class="col-6">
                <label for="name">{{ $t('company.name') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="name"
                       :value="buyer.firm.name"
                       :class="{ 'is-invalid': !buyer.firm.name }"
                       @change="updateFirmData($event)"
                />
              </div>
              <div class="col-6">
                <label for="reg_num">{{ $t('bills.reg_num') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="reg_num"
                       :value="buyer.firm.reg_num"
                       :class="{ 'is-invalid': !buyer.firm.reg_num }"
                       @change="updateFirmData($event)"
                />
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <label for="firm_address">{{ $t('bills.address') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="firm_address"
                       :value="buyer.firm.address"
                       @change="updateFirmData($event)"
                      :class="{ 'is-invalid': !buyer.firm.address }"
                       list="firmAddressList"
                />

                <datalist id="firmAddressList">
                  <option v-for="address in address_search_results"
                          :value="address"
                          :key="address">
                    {{ address }}
                  </option>
                </datalist>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label for="phone">{{ $t('bills.phone') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="phone"
                       :value="buyer.firm.phone"
                       @input="updateFirmData($event)"
                />
              </div>
              <div class="col-6">
                <label for="email">{{ $t('User.email') }}<sup class="red">*</sup></label>
                <input type="email" class="form-control" id="email" :value="buyer.firm.email" >
              </div>
            </div>
            <div  class="row">
              <div class="col-6">
                <label for="vat_reg_num">{{ $t('bills.km_reg_nr') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="vat_reg_num"
                       :value="buyer.firm.vat_reg_num"
                       @input="updateFirmData($event)"
                />
              </div>
              <div class="col-6">
                <addVatComponent :vat-data="buyer.firm.vat" :add-new="true" :show-add="false"  from="register"
                                 @update="updateVat"
                ></addVatComponent>
              </div>
            </div>

            <div class="row">
              <div class="col-6">
                <label for="firstname">{{ $t('User.firstname') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="firstname"
                       :value="buyer.firstname"
                       :class="{ 'is-invalid': !buyer.firstname }"
                       @change="updateBuyerData($event)"
                />
              </div>
              <div class="col-6">
                <label for="lastname">{{ $t('User.lastname') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="lastname"
                       :value="buyer.lastname"
                       :class="{ 'is-invalid': !buyer.lastname }"
                       @change="updateBuyerData($event)"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container pt-5 mb-3 mw950" style="margin-top: 70px;">
      <div class="notification">
        <label for="checkbox1">
          <div class="text-view">
            <input  type="checkbox" id="checkbox1" v-model="checked1">
            <span>{{ $t('register.confirm_paying') }}</span>
          </div>
        </label>
        <label for="checkbox2">
          <div class="text-view">
            <input  type="checkbox" id="checkbox2" v-model="checked2">
            <span>{{ $t('register.confirm_tos_part1') }}</span> <!-- NB! Your agree to -->
            <n-link target="_blank" :to="`/help/terms`">{{ $t('register.tos') }}</n-link> <!-- terms of service - tos -->
            <span>{{ $t('register.confirm_tos_part2') }}</span> <!-- authorize to be charged until you cancel. You understand  -->
            <n-link target="_blank" :to="`/help/subscriptions`">{{ $t('register.subscriptions_help') }}</n-link> <!-- how your subscriptions works -->
            <span>{{ $t('register.confirm_tos_part3') }}</span> <!-- and -->
            <n-link target="_blank" :to="`/help/cancel`">{{ $t('register.cancel_help') }}</n-link> <!-- how to cancel it. -->
          </div>
        </label>
      </div>
    </div>
    <div class="d-flex justify-content-lg-end mw1100 m-5">

      <div class="btn btn-outline-success float-right" style="width: 220px; border-radius: 10px;" @click="pay">
        {{ $t('bills.pay') }}
      </div>
    </div>
    <Modal title=" NB!" :active="showModal"
            v-if="showModal" @closeModal="cancelEvent">
      <div class="modal-body">
        {{ $t('register.agree_to_tos') }}
      </div>
      <template v-slot:footer>
        <button type="button" class="btn btn-success"
                @click="cancelEvent">{{ $t('common.ok') }}
        </button>
      </template>
    </Modal>
  </div>
</template>

<script>
import PayingCardComponent from "@/components/bills/PayingCardComponent";
import Modal  from "@/components/common/Modal";
import PreviewComponent from './previewComponent.vue';
import addVatComponent from "@/components/bills/common/addVatComponent";
import {SET_OBJ} from "assets/js/mutations";
  export default {
    name: 'PaymentComponent',
    components: { PayingCardComponent, Modal, PreviewComponent , addVatComponent },//, Coupons},
    middleware: 'auth',
    props : ['buyer'],
    data: () => ({
      showModal : false,
      payment_methods: [
        { id: 0, class: 'mh30', name: 'card', image: require('@/assets/img/payment/credit-card_green.svg')},
        { id: 1, class: 'mh45', name: 'bank_link', image: require("@/assets/img/payment/bank-link_green.svg")},
        { id: 2, class: 'mh45', name: 'invoice', image: require("@/assets/img/payment/invoice_green.svg") }
      ],
      invoice_type: [
        { id: 0, name: 'person' },
        { id: 1, name: 'company' }
      ],
      selected_payment: 'invoice',
      selected_data_source: 'company',
      month: 1,
      checked1: false,
      checked2: false,
      country_vat: 20,
      couponCode: null,
      isCouponValid: null,
      amount_percent: 0,
      amount_eur: 0,
      was_changed : false,
      address_search_results: []
    }),
    created(){
      this.$store.commit('users/SET_OBJ', { name: `buyer.firm.email`, value: this.$auth.user.email });
    },


    computed: {
      payment_plan() {
        return this.$store.getters['payment/getSelectedPlan'];
      },
      sum() {
        let total_sum = (
          Math.floor(
            ((this.payment_plan.price * this.month * (1 - this.amount_percent/100)) - this.amount_eur)*100
          )/100
        ).toFixed(2);
        let vat = (Math.floor(total_sum * this.country_vat) / 100).toFixed(2);
        return { total: total_sum, vat: vat, total_without_vat: (total_sum - vat).toFixed(2) };
      },
      username() {
        return this.$auth.user.username;
      },
      isFree() {
        return this.sum.total == 0.00;
      }
    },

    methods: {
      goBack() {
        this.$emit("next", 4);
      },
      cancelEvent(){
        this.showModal = false;
      },
      confirmEvent(){
        this.$emit("next", 6);
      },

      updateVat(data) {
        this.$store.commit('users/SET_OBJ', { name: `buyer.firm.vat[${data.idx}].vat`, value: data.value });
      },



      async pay() {
        if((!this.checked1 === true) || (!this.checked2 === true)) {
          this.showModal = true;
          return;
        }
        if (this.selected_payment === 'invoice') {
          if (this.was_changed) {
            await this.$store.dispatch("users/createBuyer").then(
              res => { res.type === 'success' ? this.was_changed = false : '' },
              err => { this.$toast.error(err.msg); });
          }
          this.$store.dispatch("payment/setPaymentPlans", {
            'send_to': this.selected_data_source,
            'send_pdf': true,
            'payment_plan_id': this.payment_plan.id
          });
        } else this.$store.dispatch("payment/setPaymentPlans", {'payment_plan_id': this.payment_plan.id});

        this.$emit("next", 6);
        /**?**/
       this.$emit('returnToSettings', this.payment_plan.id);
      },
      updateBuyerData(data) {
        this.$store.commit('users/SET_OBJ', { name: 'buyer', key: data.target.id, value: data.target.value });
        this.was_changed = true;
      },

      updateFirmData(data){
        if(data.target.id === 'firm_address'){
          data.target.id = 'address'
        }

        this.$store.commit('users/SET_OBJ', { name: 'buyer', subkey : data.target.id ,  key: 'firm' , value: data.target.value })
        this.was_changed = true;
      },

      async checkCoupon() {
        if (!this.couponCode) {
          return;
        }
        await this.$store.dispatch('coupons/fetchCoupon', this.couponCode).then((coupon) => {
          if(coupon.code == 200) {
            if(coupon.data) {
              this.amount_percent = coupon.data.amount_percent ?? 0;
              this.amount_eur = coupon.data.amount_eur ?? 0;
            }
            this.isCouponValid = true;
          }else{
            this.isCouponValid = false;
          }
         }).catch(e => {
          this.isCouponValid = false;
        });
      },

      /**
       * Wraps address setting to store
       * @param {string} address address to handle
       */
      async handleAddress(data) {
        let search_querry = data.target.value;
        if (search_querry.length < 3) {
          return;
        }
        let addresses = await this.$store.dispatch('users/fetchAddresses', search_querry);
        if (addresses === undefined) {
          this.address_search_results = [];
        } else {
          this.address_search_results = addresses.map(address => address.ipikkaadress);
        }
        search_querry += ',';
        const regex = /^.+[\w|\s]+(\s\d+\w*),/g;
        if (this.address_search_results.length > 0 // check if addresses were found
          && data.inputType !== 'deleteContentBackward' // check if backspace or similar on touch was used
          && this.address_search_results[0].match(regex) // check if is matched
          && search_querry.match(regex) // check if is matched
          && this.address_search_results[0].match(regex)[0] !== search_querry.match(regex)[0] // check if they differ
          ) {
          data.target.value = this.address_search_results[0].match(regex)[0].replace(/,/g, '');
        }

        console.log(data.target.id);
        if(data.target.id === 'firm_address'){
          data.target.id  = 'address'
          this.updateFirmData(data)
        }else {
          this.updateBuyerData(data);
        }
      },

      showError() {
        this.$toast.error(this.$t('payments.not_available')).goAway(5000);
      }
    }
  }
</script>

<style scoped lang="scss">
@import "@/assets/scss/var";

.mlm20{
  margin-left: -20px;
}

.mtm25{
  margin-top: -25px;
}

.bordered{
  border: 1px solid #dee2e6;
}

.bordered-5px{
  border: 1px solid #dee2e6;
  border-radius: 5px;
  &:hover{
    border: none;
    filter: brightness(30%);
  }
}

.mh95{
  max-height: 95px;
}

.green{
  color: #2fca69
}
.selected{
  border-bottom: 10px solid #ffc300;
}

.selected-3{
  border-bottom: 3px solid #ffc300;
}

.wrap-icon {
  cursor: pointer;
  color: #000;
  width: 200px;
  height: 200px;
  border-radius: 50%;
  img{
    filter: invert(43%) sepia(74%) saturate(2289%) hue-rotate(113deg) brightness(98%) contrast(106%);
    &:hover {
      filter: invert(29%) sepia(66%) saturate(1502%) hue-rotate(97deg) brightness(90%) contrast(87%);
     // filter: invert(32%) sepia(81%) saturate(515%) hue-rotate(76deg) brightness(93%) contrast(98%);
    }
  }

  &.active {
    color: #fff;
    background-color: #00ba3c;

    img {
      -webkit-filter: brightness(0) invert(1);
      filter: brightness(0) invert(1);
    }
  }
}

.border__radius{
  flex: 1 1 auto;
  display: flex;
  position: relative;
  justify-content: center;
  align-items: center;
  text-align: center;
  min-height: 260px;
  max-width: 450px;
  margin-right: 20px;
}

.double__border{
  position: relative;
  flex: 1 1 auto;
  display: flex;
  min-width: 100%;
  height: 100%;
}
.double__border::before{
  content: "";
  position: absolute;
  display: flex;
  width: 100%;
  height: 100%;
  border: 2px solid #e9ebeb;
  border-radius: 15px;
  z-index: -1;
}
.double__border::after{
  content: "";
  position: absolute;
  display: flex;
  top: 20px;
  left: 20px;
  width: 100%;
  height: 100%;
  border: 2px solid #e9ebeb;
  border-radius: 15px;
  z-index: -1;
}

.mh30{
  max-height: 30px;
}

.mh45{
  max-height: 45px;
}


.mh60{
  max-height: 60px;
}
.modal{
  position: fixed;
  padding-top: 200px;
}
.add_border_bottom1{
  border-bottom: solid 2px #b9b9b9
}
.add_border_bottom2{
  border-bottom: solid 3px #e8e8e8;
}

.grey_btn{
  padding-left: 3.5rem;
  padding-right: 3.5rem;
  background-color: #e8e8e8;
  color: #000000;
  border-color: #e8e8e8;
  border-radius: 7px;
  margin-top: -20px;
}

.input_props{
  padding: 1.5rem 1rem;
  border-radius: 15px;
}

.modal-body {
    padding: 20px;
    background-color: #fff;
    color: #000;
}


.h170{
  height: 170px;
}
.swedbank {
  border: solid 2px #ff9800;
}

.seb {
  border: solid 2px #54bb2c;
}
.lhv {
  border: solid 2px #000000;
}
.container__bank{
  margin-top: 3px;
}
.coop {
  border: solid 2px royalblue;
}

.luminor {
  border: solid 2px #481335;
}
.paypal {
  border: solid 2px #09297c;
}
.container{
 display:flex;
}
.company-contacts {
    width: 100%;
    border: 1px solid #888585;
    padding: 10px 5px;
  }
.element{
    min-width: 15vw;
  }

.text-view{
    white-space:normal;
    width: 100%;
  }
#customer{
    color: black;
  }
#pricing{
  color: black;
  font-size: 14px;
}
.customer__label{
  margin-top: 30px;
  color: $secondary-color;
}
.pricing__box{
  margin-top: -5px;
}
.label__pricing{
  margin-top: 25px;
  color: $secondary-color;
}
#scaled_preview {
  transform: scale(0.75);
  margin-top: -105px;
}

@media(max-width: 460px){
.container{
 display:table;
}
  .element{
    display: flex;
    width: 100%;
    flex-direction: row-reverse;
    float: left;
  }
  .element label{
    padding-left: 5px;
  }
 .element input{
   margin-left: 0px;
 }
}
</style>
