<template>
  <div class="container-fluid" >
    <div class="invoice-box" :class="{ 'text-mode-width': previewData.is_pdf_pic_mode }">
      <table class="main-table" cellpadding="0" cellspacing="0">
        <tr class="top">
          <td colspan="2">
            <div class="table__top">
              <table>
                <tr>
                  <td class="title">
                    <div v-if="previewData.firms.view_in_invoice && previewData.firms.logo_blob &&
                    (previewData.firms.logo_blob.length === 0 ?
                    'https://test3.gloreal.ee' + previewData.firms.logo : previewData.firms.logo_blob)">
                      <img v-if="previewData.firms" :data-attr="checkType('firm')" class="firm__img"    @click="updateTest($event)"
                      :src="previewData.firms.view_in_invoice && previewData.firms.logo_blob &&
                      (previewData.firms.logo_blob.length === 0 ?
                      'https://test3.gloreal.ee' + previewData.firms.logo : previewData.firms.logo_blob)"
                    :alt="previewData.firms.company_name">
                    </div>
                     <div v-else class="alt__error" @click="updateTest($event)">
                      <h2 v-if="previewData.firms.company_name" :data-attr="checkType('firm')">{{ previewData.firms.company_name }}</h2>
                      <h2 v-else :data-attr="checkType('firm')">{{$t("bills.company_name", previewData.locale)}}</h2>
                    </div>
                  </td>
                  <td>
                    <img class="right__img" :src="setImage" alt="Valentines day!" />
                  </td>
                </tr>
              </table> 
            </div>
          </td>
        </tr>

        <tr class="information">

          <td>
            <div class="table__beneficiary">
              <table v-if="previewData['firms']">
                <thead>
                  <tr>
                    <th class="text-left" colspan="2"><span>{{ $t('bills.beneficiary', previewData.locale)}}:</span>
                      <span v-if="previewData['firms'].name" :data-attr="checkType('firm')" class="firm bold" @click="updateTest($event)">&nbsp;{{ previewData['firms'].company_name }}</span>
                    </th>
                  </tr>
                </thead>
                <tbody>

                <tr v-if="previewData['firms'].reg_num">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.reg_num", previewData.locale)}}:</strong> <span :data-attr="checkType('firm')" class="firm" id="reg_num" @click="updateTest($event)" > &nbsp;{{ previewData['firms'].reg_num}}</span>
                  </td>
                </tr>
                <tr v-if="previewData['firms'].phone">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.phone", previewData.locale)}}:</strong><span :data-attr="checkType('firm')" id="phone" class="firm"  @click="updateTest($event)">&nbsp;{{ previewData['firms'].phone}}</span>
                  </td>
                </tr>
                <tr v-if="previewData['firms'].address">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.address", previewData.locale)}}: </strong>
                    <span :data-attr="checkType('firm')">{{ previewData['firms'].address }}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('bank_name')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.bank", previewData.locale)}}:</strong><span  :data-attr="checkType('bank')"  id="bank_name" class="bank" @click="updateTest($event)">&nbsp;{{ getBankData('bank_name')}}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('bank_num')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.bank_num", previewData.locale)}}:</strong><span  :data-attr="checkType('bank')" id="bank_num" class="bank" @click="updateTest($event)">&nbsp;{{ getBankData('bank_num')}}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('bank_account')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.acc_num", previewData.locale)}}:</strong><span  :data-attr="checkType('bank')"  id="bank_account" class="bank" @click="updateTest($event)">&nbsp;{{ getBankData('bank_account')}}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('bank_address')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.swift", previewData.locale )}}:</strong><span  :data-attr="checkType('firm')"  id="bank_swift" class="bank" @click="updateTest($event)">&nbsp;{{ getBankData('bank_swift')}}</span>
                  </td>
                </tr>

                <tr v-if="getBankData('bank_address')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.bank_address", previewData.locale )}}:</strong><span  :data-attr="checkType('bank')" id="bank_address" class="bank" @input="updateTest($event)">&nbsp;{{ getBankData('bank_address')}}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('km_reg_num')">
                  <td class="text-left">
                    <strong>{{ $t("bills.km_reg_nr", previewData.locale )}}:</strong><span  :data-attr="checkType('bank')" id="km_reg_num" class="bank" @input="updateTest($event)">&nbsp;{{ getBankData('km_reg_num')}}</span>
                  </td>
                </tr>
                <tr v-else>
                  <td class="text-left">
                    <strong>{{ $t("bills.km_reg_nr", previewData.locale )}}:</strong><span  :data-attr="checkType('bank')" id="km_reg_num" class="bank" @input="updateTest($event)">&nbsp;{{ $t('User.missing_vat')}}</span>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </td>
          
          <td>
            <div class="table__right">
              <table>
                <thead>
                  <tr>
                    <td colspan="2" class="text-right">
                      <span class="bold text-right">{{ $t("bills.prepaid" )}}</span>
                    </td>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="previewData.invoice">
                    <td class="text-right"><strong>{{ $t("bills.invoice_number", previewData.locale )}}:</strong>
                    </td>
                    <td class="text-right" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
                      <span :data-attr="checkType('invoice')" @click="updateTest($event)">{{ previewData.invoice }}</span>
                    </td>
                  </tr>

                  <tr v-if="previewData.deadline">
                    <td class="text-right"><strong>{{$t("bills.deadline",  previewData.locale )}}:</strong></td>
                    <td class="text-right" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
                        <span :data-attr="checkType('invoice')" @click="updateTest($event)">{{ previewData.deadline }}</span>
                      </td>
                  </tr>

                  <tr>
                    <td class="text-right"><strong>{{ $t("bills.invoice_date", previewData.locale )}}:</strong></td>
                    <td class="text-right" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
                      <span :data-attr="checkType('invoice')" @click="updateTest($event)">{{ previewData.date }}</span>
                    </td>
                  </tr>

                  <tr v-if="previewData.payment_method">
                    <td class="text-right" colspan="2"><strong>{{ $t(`bills.${previewData.payment_method_name}`, previewData.locale) }}</strong></td>
                  </tr>

                </tbody>
              </table>
            </div>
          </td>

        </tr>

        <tr>
          <td colspan="2">
            <div class="table__reffered">
              <table>
                <thead>
                  <tr>
                    <th class="text-left">
                      {{ $t('bills.refered_to', previewData.locale )}}: 
                          <span class="bold" v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null" :data-attr="checkType('companies')" @click="updateTest($event)" >
                            &nbsp;{{ previewData.companies.name }}
                          </span>
                          <span class="bold" v-else-if="previewData.clients" :data-attr="checkType('clients')"  @click="updateTest($event)">
                            &nbsp;{{ previewData.clients.firstname }} {{ previewData.clients.lastname }}
                          </span>
                    </th>
                  </tr>
                </thead>
                <tbody>

                <tr v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null">
                  <td class="text-left">
                      <strong v-if="previewData.companies.reg_num">
                        {{ $t("bills.reg_num", previewData.locale )}}:
                      </strong>
                      <span @click="updateTest($event)">&nbsp;{{ previewData.companies.reg_num }}</span>
                    </td>
                </tr>

                <tr v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null">
                  <td class="text-left">
                      <strong v-if="previewData.clients.reg_num">
                        {{ $t("bills.reg_num", previewData.locale )}}:
                      </strong><span :data-attr="checkType('clients')" @click="updateTest($event)">&nbsp;{{ previewData.clients.reg_num }}</span>
                  </td>
                </tr>

                <tr v-if="(!Array.isArray(previewData.companies)&&previewData.companies!==null) || (!previewData.clients)">
                  <td  class="text-left">
                    <span v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null">
                      <strong v-if="previewData.companies.address">
                        {{ $t("bills.address" , previewData.locale )}}:
                      </strong>
                      <span @click="updateTest" :data-attr="checkType('companies')">{{ previewData.companies.address  }}</span>
                      </span>
                    <span v-else-if="previewData.clients">
                      <strong v-if="previewData.clients.address">
                        {{ $t("bills.address" , previewData.locale )}}:
                      </strong>
                      <span @click="updateTest" :data-attr="checkType('clients')">{{ previewData.clients.address  }}</span>
                    </span>
                  </td>
                </tr>

                <tr v-if="previewData.clients && previewData.clients.firstname">
                  <td class="text-left">
                    <strong>
                      {{$t("bills.represented_by", previewData.locale )}}: 
                    </strong>
                    <span  @click="updateTest" :data-attr="checkType('clients')">&nbsp;{{ previewData.clients.firstname }} {{  previewData.clients.lastname }}</span>
                  </td>
                </tr>

                  <tr v-if="previewData.companies.length === 0 && previewData.clients.length === 0">
                    <td>
                      <span  @click="updateTest" :data-attr="checkType('clients')">&nbsp;+ {{ $t('bills.add_client_or_company')}}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </td>       
        </tr>
        
        <tr class="details">
          <td colspan="2">
            <table class="table__red">
              <thead>
                <tr>
                  <th class="text-left">{{ $t('bills.explanation', previewData.locale )}}</th>
                  <th class="text-center">{{ $t('bills.unit', previewData.locale )}}</th>
                  <th class="text-center">{{ $t('bills.amount', previewData.locale )}}</th>
                  <th class="text-right">{{ $t('bills.price_per_unit', previewData.locale )}}</th>
                  <th class="text-right">{{ $t('bills.total', previewData.locale ) }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(d, idx) in previewData.orders" :key="idx">
                  <td class="text-left" >
                    <span :data-attr="checkType('details')" @click="updateTest($event)" v-html="d.desc" ></span>
                  </td>
                  <td v-if="d.unit == 'Another'" class="text-center"><span :data-attr="checkType('details')" @click="updateTest($event)">{{ d.unit_custom }}</span></td>
                  <td v-else-if="d.unit != 'Another'" class="text-center"><span :data-attr="checkType('details')" @click="updateTest($event)">{{ d.unit }}</span></td>
                  <td class="text-center"><span :data-attr="checkType('details')" @click="updateTest($event)">{{ d.amount }}</span></td>
                  <td class="text-right" style="white-space:nowrap" >
                    <span v-if="d.unit_price" :data-attr="checkType('details')" @click="updateTest($event)">{{ Number(d.unit_price).toFixed(2, ',') }}</span>
                    <span v-else :data-attr="checkType('details')" @click="updateTest($event)">0.00</span>
                  </td>
                  <td class="text-right" style="white-space:nowrap" @load="currency = d.currency;">
                    <span v-if="d.price" :data-attr="checkType('details')" @click="updateTest($event)">{{ d.price  + " " + previewData.currency }}</span>
                  </td>
                </tr>

                <tr>
                  <td colspan="1"></td>
                  <td colspan="4">
                  <img :src="setImageHeart" class="heart__img">

                  <!-- block heart-image, if it is better -->
                    <!--<div class="heart__container">
                      <div class="heart__container1">
                        <div class="main__heart"></div>
                      </div>
                      <div class="heart__container2">
                        <div class="additional__heart"></div>
                      </div>
                    </div>-->
                  <!-- end of block heart-image --> 
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="data">
                    <span>{{ $t("bills.invoice_date", previewData.locale )}}:</span>
                    <div :class="{ 'box-width': previewData.is_pdf_pic_mode }">
                      <span :data-attr="checkType('invoice')" @click="updateTest($event)">&nbsp;{{ previewData.date }}</span>
                    </div>
                  </td>
                  <td colspan="2"></td>
                </tr> 

                <tr>
                  <td colspan="3"></td>
                  <td class="text-right red">
                    <div>{{ $t("bills.total_withoutKM", previewData.locale )}}:</div>
                    <br>
                    <div><b>{{ $t("bills.total" , previewData.locale )}}:</b></div>
                  </td>
                  <td class="text-right red">
                    <div v-if="previewData.price_no_km">
                      <span :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.price_no_km  + " " + previewData.currency }}</span>
                    </div>
                    <br>
                    <div v-if="previewData.price_with_km">
                      <span :data-attr="checkType('details')" @click="updateTest($event)" style="white-space:nowrap"><b>{{ previewData.price_with_km + " " + previewData.currency}}</b></span>
                    </div>
                    <div v-else-if="previewData.price">
                      <span :data-attr="checkType('details')" @click="updateTest($event)" style="white-space:nowrap"><b>{{ previewData.price + " " + previewData.currency }}</b></span>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td colspan="1" class="text-left red__text">
                    <small v-if="previewData.penalty" class="red__small" :data-attr="checkType('details')" @click="updateTest($event)">
                      {{ $t("bills.pro_text", previewData.locale, {"penalty": previewData.penalty} )}}
                    </small>
                    <small v-else :data-attr="checkType('details')" class="red__small" @click="updateTest($event)">
                      {{ $t("bills.pro_text", previewData.locale, {"penalty": 0.5} )}}
                    </small>
                  </td>
                  <td colspan="4" rowspan="2"></td>
                </tr>

                <tr>
                  <td colspan="1" class="notify__text">
                    <span>{{ $t("bills.notify", previewData.locale )}}</span><br>
                    <span>{{ $t("bills.notify_text", previewData.locale )}}</span>
                  </td>
                </tr>
              </tfoot>
            </table>
          </td>
        </tr>

        <tfoot>
        <tr>
          <td>
            <div>
              <strong class="red__header">{{ $t("bills.Send_by", previewData.locale )}}</strong>
            </div>
          </td>

          <td>
            <div class="red__p">
              <strong class="red__header">{{ $t("bills.received_by", previewData.locale )}}</strong>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <p v-if="previewData.sender_user" class="line__height" :data-attr="checkType('sender')" @click="updateTest($event)">{{ previewData.sender_user.username }}</p>
            <p v-else-if="previewData.users" class="line__height" :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.users.username }}</p>
          </td>
          <td>
            <p class="text-right red__p line__height">
              <span v-if="previewData.clients" :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.clients.firstname ? previewData.clients.firstname : " "}} {{  previewData.clients.lastname ? previewData.clients.lastname : " "  }}</span>
            </p>
          </td>
        </tr>
        </tfoot>
      </table>

    </div>
    
  </div>
</template>

<script>
import isIncludedInPackage from "../../mixins/isIncludedInPackage";
import { Valentines }  from "../../assets/js/base64/Valentines";
import { Heart }  from "../../assets/js/base64/Heart";

export default {
  name: "previewComponent4",
  props: ["previewData"],
  mixins: [isIncludedInPackage],
  data(){
    return {
      has_dirty: false,
    }
  },
  computed:{
    setImage(){
      return Valentines;
    },
    setImageHeart(){
      return Heart;
    }
  },
  methods:{
    updateTest(data){
      switch (data.target.getAttribute("data-attr")){
        case "bank" :
            this.$store.commit('bills/SET_OBJ', {name: 'chooseBankModal', value: true})
            break;
        case "clients" || 'companies':
          this.$store.commit('clients/SET_OBJ' , {name: 'canChange' , value: true});
          this.$emit('changeClient');
          break;
        case 'firm':
          this.$emit('changeFirm', this.previewData.firms );
          break;
        case 'invoice':
          this.$emit('changeDetails' , 0);
          break;
        case 'details':
          this.$emit('changeDetails', 2);
          break;
        case 'sender':
          this.$emit('changeDetails', 3);
          break;
        case 'footer':
          this.$emit('changeFirm' , this.previewData.firms );
          break;
        default:
           this.has_dirty = true;
           break;
      }
    },

    checkType(data){
      if(!this.previewData.type === true) data = '';
      return data;
    },

    getBankData(name) {
        if (this.previewData.bank_id !== null && Array.isArray(this.previewData['firms'].banks)) {
          const res = this.previewData['firms'].banks.find(e => e.id === this.previewData.bank_id)
          if (typeof res === 'object' && res.hasOwnProperty(name)) return res[name];
        } else if (Array.isArray(this.previewData['firms'].banks) && this.previewData['firms'].banks[0])
          return this.previewData['firms'].banks[0][name]
        else return "";
      }
  }
}

</script>

<style lang="scss" scoped>
@import "./assets/scss/_var.scss";

/*@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 500;
  src: local('Montserrat'), local('PT Montserrat');
  font-display: swap;
}*/
@font-face {
  font-family: 'Astra';
  src: local('Astra Sans'), local('PT Astra Sans');
  font-style: normal;
  font-weight: 500;
  font-display: swap;
}
html, body {
  height: 100%;
}
.invoice-box {
  margin-top: 20px;
  padding: 0px 15px auto 15px;
  width: 100%;
  max-width: 1200px;
  height: 100%;
  font-size: 15px;
  line-height: 1em;
  font-family: Astra, Helvetica , "sans-serif";
  //font-family: 'Montserrat', sans-serif;
  color: rgb(0, 0, 0);
  }
.invoice-box.text-mode-width {
    width: 660px;
  }
.firm__img{
  max-height: 140px;
  height: 140px;
  width: 140px;
  width: auto;
  float: left;
}
.firm__img[alt]{
  display: flex;
  color: #000000;
  white-space: normal;
  font-size: 34px;
  line-height: 55px;
}
.alt__error{
  position: absolute;
  vertical-align: middle;
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: normal;
  font-size: 28px;
  color: #000000;
  height: 60px;
  width: auto;
}
.alt__error h2{
  position: relative;
  padding: 15px 20px;
  line-height: 30px;
  background-color: $secondary-light;
}
.title{
  width: 50%;
  vertical-align: top;
}
table{
  width: 100%;
}
.right__img{
  position: relative;
  min-height: 160px;
  height: 160px;
  width: auto;
  float: right;
}
.heart__img{
  position: absolute;
  margin-top: -30px;
  overflow: hidden;
  height: 26rem;
  width: auto;
  @media screen and(max-width: 1200px){
    height: 24%;
  }
}
.table__beneficiary table{
  margin: 0px 0px 15px 0px;
  width:260px;
  max-width:260px;
}
.table__beneficiary table thead{
  color: #d4426e;
  font-size: 23px;
  height: 40px;
  vertical-align: top;
  white-space: nowrap;
}
.table__beneficiary table tr td{
   height: 15px;
}
.table__reffered table{
  margin: 0px 0px 20px 0px;
  max-width: 1200px;
}
.table__reffered table thead{
  color: #d4426e;
  font-size: 23px;
  height: 50px;
}
.table__reffered table tr td{
  height: 25px;
}
.table__right{
  width:260px;
  max-width:260px;
  float: right;
  text-align: right;
}
.table__right table thead{
  color: #d4426e;
  font-size: 23px;
  height: 40px;
  white-space: nowrap;
}
.table__right table tr td{
  height: 25px;
}
.table__red{
  width: 100%;
  max-width: 1200px;
}
.table__red thead tr th:last-of-type{
  background-color: #d4426e;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
  padding-right: 10px;
}
.table__red thead tr th:first-of-type{
  padding-left: 10px; 
}
.text-right{
   position: relative;
   z-index: 20;
}
.table__red thead tr th{
  background-color: #d4426e;
  color: #ffffff;
  height: 30px; 
  white-space: nowrap;
}
.table__red tbody tr td{
  height: 70px;
}
.table__red tbody tr td:first-of-type{
  max-width: 260px;
  padding-left: 10px;
}
.table__red tbody tr td:last-of-type{
  padding-right: 10px;
}
.data{
  padding-left: 10px;
  display: flex;
  flex-direction: row;
}
.red__header{
  color: #d4426e;
  font-size: 23px;
  margin-top:40px;
  width: 260px;
  vertical-align: top;
}
.red__p{
  text-align: right;
}
.line__height{
  line-height: 2;
}
.red__text{
  padding-top: 20px;
  color: #d4426e;
  font-size: 18px;
  max-width: 260px;
  text-align: justify;
  margin-left: 0px;
  padding-left: 0px;
}
.red__small{
  text-align: justify;
}
.red{
  position: relative;
  z-index: 10;
  color: #d4426e;
  white-space: nowrap;
}
.table__red tfoot tr td:first-of-type,
.notify__text{
  max-width: 260px;
  word-wrap: break-word;
}
.notify__text{
  padding-top: 20px;
  font-size: 14px;
  text-align: justify;
  margin-left: 0px;
  padding-left: 0px;
}
.main-table{
  width: 100%;
  max-width: 1200px;
}
.main-table tfoot tr{
  height: 60px;
  margin-bottom: 20px;
  margin-top: auto;
  vertical-align: bottom;
}
.table__top{
  width: 100%;
  max-width: 1200px;
}
.information{
  width: 100%;
  max-width: 1200px;
}
.box-width {
    width: 135px;
}
 .title:hover [data-attr="firm"]{
    outline: 2px solid #f3bbc4;
    cursor: pointer;
  }
.bold{
  font-weight: 600;
}
/* heart image */
.heart__container{
  position: absolute;
  z-index: 0;
  margin-left: -40px;
}
.heart__container1{
  position: relative;
  z-index: 0;
  margin-left: 60px;
}
.heart__container2{
  position: relative;
  z-index: 0;
  margin-top: 30px;
}
.main__heart,
.additional__heart{
    position: relative;
    z-index: 0;
    margin-top: -80px;
    width: 7rem;
    height: 11rem;
    -webkit-border-radius: 320px 220px 220px 0px;
    -moz-border-radius: 320px 220px 220px 0px;
    border-radius: 320px 220px 220px 0px;
     background-color: rgba(252, 236, 239, 0.9);
    -webkit-transform: rotate(325deg);
    -moz-transform: rotate(325deg);
    -ms-transform: rotate(325deg);
    -o-transform: rotate(325deg);
    transform: rotate(325deg);
}
.additional__heart{
  -webkit-transform: rotate(320deg);
  -moz-transform: rotate(320deg);
  -ms-transform: rotate(320deg);
  -o-transform: rotate(320deg);
  transform: rotate(320deg);
  background-color: rgba(254, 238, 234, 0.9);
}
.main__heart:before,
.additional__heart:before{
    position: absolute;
    content: "";
    z-index: 0;
    width: 11rem;
    height: 7rem;
    left: 0;
    bottom: 0;
    -webkit-border-radius: 220px 220px 320px 0px;
    -moz-border-radius: 220px 220px 320px 0px;
    border-radius: 220px 220px 320px 0px;
    background-color: rgba(252, 236, 239, 0.8);
}
.additional__heart:before{
  background-color: rgba(254, 238, 234, 0.9);
}
/* end heart image */

.information [data-attr="bank"]:hover,
.information [data-attr="firm"]:hover,
.information [data-attr='companies']:hover,
.information [data-attr='clients']:hover,
.table__reffered [data-attr='clients']:hover,
.title [data-attr="firm"]:hover,
  footer [data-attr='footer']:hover,
.table__red [data-attr='invoice']:hover,
.table__right [data-attr='invoice']:hover,
.details [data-attr='details']:hover,
tfoot [data-attr='details']:hover,
tfoot [data-attr='sender']:hover{
  background-color: rgba(248, 211, 218, 0.9);
  cursor: pointer;
}
.information:active [data-attr="bank"],
.information:active [data-attr="firm"],
.information:active [data-attr='companies'],
.information:active [data-attr='clients'],
.table__reffered:active [data-attr='clients'],
.title:active [data-attr="firm"],
  footer:active [data-attr='footer'],
.table__red:active [data-attr='invoice'],
.table__right:active [data-attr='invoice'],
.details:active [data-attr='details'],
tfoot:active [data-attr='details'],
tfoot:active [data-attr='sender']{
  text-decoration: underline;
}

</style>
