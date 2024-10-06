<template>
  <div class="container-fluid" >
    <div class="invoice-box" :class="{ 'text-mode-width': previewData.is_pdf_pic_mode }">
      <table class="main-table" cellpadding="0" cellspacing="0">
        <tr class="top z__index">
          <td colspan="2">
            <div class="table__top">
              <table>
                <tr><img :src="setImageSpiderweb" class="wallpaper"></tr>
                <tr>
                  <td colspan="2" class="title">
                    <div v-if="previewData.firms.view_in_invoice && previewData.firms.logo_blob &&
                    (previewData.firms.logo_blob.length === 0 ?
                    'https://test3.gloreal.ee' + previewData.firms.logo : previewData.firms.logo_blob)">
                    <img v-if="previewData.firms" :data-attr="checkType('firm')"  class="right__img"    @click="updateTest($event)"
                    :src="previewData.firms.view_in_invoice && previewData.firms.logo_blob &&
                    (previewData.firms.logo_blob.length === 0 ?
                    'https://test3.gloreal.ee' + previewData.firms.logo : previewData.firms.logo_blob)"
                    :alt="previewData.firms.company_name">
                    </div>
                    <div v-else class="alt__error"  @click="updateTest($event)">
                      <h2 v-if="previewData.firms.company_name" :data-attr="checkType('firm')">{{ previewData.firms.company_name }}</h2>
                      <h2 v-else :data-attr="checkType('firm')">{{$t("bills.company_name", previewData.locale)}}</h2>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>

        <tr class="information z__index">

          <td>
            <div class="table__beneficiary">
              <table v-if="previewData['firms']">
                <thead>
                  <tr>
                    <th class="text-left" colspan="2"><span>{{ $t('bills.beneficiary', previewData.locale)}}</span><br>
                    </th>
                  </tr>
                </thead>
                <tbody>
                <tr>
                  <td>
                    <span v-if="previewData['firms'].name" :data-attr="checkType('firm')" class="firm bold" @click="updateTest($event)">{{ previewData['firms'].company_name }}</span>
                  </td>
                </tr>
                <tr v-if="previewData['firms'].reg_num">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.reg_num", previewData.locale)}}:</strong> <span :data-attr="checkType('firm')" class="firm" id="reg_num" @click="updateTest($event)" > &nbsp;{{ previewData['firms'].reg_num}}</span>
                  </td>
                </tr>
                <tr v-if="previewData['firms'].address">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.address", previewData.locale)}}: </strong>
                    <span :data-attr="checkType('firm')">{{ previewData['firms'].address }}</span>
                  </td>
                </tr>
                <tr v-if="previewData['firms'].phone">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.phone", previewData.locale)}}:</strong><span :data-attr="checkType('firm')" id="phone" class="firm"  @click="updateTest($event)">&nbsp;{{ previewData['firms'].phone}}</span>
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

          <td class="right__box">
            <div class="table__right">
              <table>
                <thead>
                  <tr>
                    <th class="text-right">
                      {{ $t('bills.refered_to', previewData.locale )}}
                    </th>
                  </tr>
                </thead>
                <tbody>
                <tr>
                  <td>
                    <span class="bold" v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null" :data-attr="checkType('companies')" @click="updateTest($event)" >
                    {{ previewData.companies.name }}
                    </span>
                    <span class="bold" v-else-if="previewData.clients" :data-attr="checkType('clients')"  @click="updateTest($event)">
                    {{ previewData.clients.firstname }} {{ previewData.clients.lastname }}
                    </span>
                  </td>
                </tr>
                <tr v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null">
                  <td class="text-right">
                      <strong v-if="previewData.companies.reg_num">
                        {{ $t("bills.reg_num", previewData.locale )}}:
                      </strong>
                      <span @click="updateTest($event)">&nbsp;{{ previewData.companies.reg_num }}</span>
                    </td>
                </tr>

                <tr v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null">
                  <td class="text-right">
                      <strong v-if="previewData.clients.reg_num">
                        {{ $t("bills.reg_num", previewData.locale )}}:
                      </strong><span :data-attr="checkType('clients')" @click="updateTest($event)">&nbsp;{{ previewData.clients.reg_num }}</span>
                  </td>
                </tr>

                <tr v-if="(!Array.isArray(previewData.companies)&&previewData.companies!==null) || (!previewData.clients)">
                  <td  class="text-right">
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
                  <td class="text-right">
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

        <tr class="z__index">
          <td colspan="2">
            <div class="table__reffered">
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
                    <td class="text-right" :class="{ 'box-width': previewData.is_pdf_pic_mode }"><span :data-attr="checkType('invoice')" @click="updateTest($event)">{{ previewData.deadline }}</span>
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

        <tr class="details z__index">
          <td colspan="2">
            <img :src="setImagePumpkin" alt="Pumpkin." class="pumpkin">
            <table class="table__gray">
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
                <tr class="black__border">
                  <td colspan="3"></td>
                  <td class="text-right gray">
                    <div><b>{{ $t("bills.total_withoutKM", previewData.locale )}}:</b></div>
                    <br>
                    <div><b>{{ $t("bills.total" , previewData.locale )}}:</b></div>
                  </td>
                  <td class="text-right gray additional__gray">
                    <div v-if="previewData.price_no_km">
                      <span :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.price_no_km  + " " + previewData.currency }}</span>
                    </div>
                    <br>
                    <div v-if="previewData.price_with_km">
                      <span :data-attr="checkType('details')" @click="updateTest($event)" style="white-space:nowrap">{{ previewData.price_with_km + " " + previewData.currency}}</span>
                    </div>
                    <div v-else-if="previewData.price">
                      <span :data-attr="checkType('details')" @click="updateTest($event)" style="white-space:nowrap">{{ previewData.price + " " + previewData.currency }}</span>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td colspan="3" class="text-left gray__text">
                    <small v-if="previewData.penalty" class="gray__small" :data-attr="checkType('details')" @click="updateTest($event)">
                      {{ $t("bills.pro_text", previewData.locale, {"penalty": previewData.penalty} )}}
                    </small>
                    <small v-else :data-attr="checkType('details')" class="gray__small" @click="updateTest($event)">
                      {{ $t("bills.pro_text", previewData.locale, {"penalty": 0.5} )}}
                    </small>
                  </td>
                  <td colspan="2" rowspan="2" class="spider__box"><img :src="setImageSpider" class="spider"></td>
                </tr>

                <tr>
                  <td colspan="3" class="notify__text">
                    <span>{{ $t("bills.notify", previewData.locale )}}</span><br>
                    <span>{{ $t("bills.notify_text", previewData.locale )}}</span>
                  </td>
                </tr>
              </tfoot>
            </table>
          </td>
        </tr>

      <tfoot class="z__index">
        <tr>
          <td>
            <div>
              <strong class="gray__header">{{ $t("bills.Send_by", previewData.locale )}}</strong>
            </div>
          </td>

          <td>
            <div class="gray__p">
              <strong class="gray__header">{{ $t("bills.received_by", previewData.locale )}}</strong>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <p v-if="previewData.sender_user" class="line__height" :data-attr="checkType('sender')" @click="updateTest($event)">{{ previewData.sender_user.username }}</p>
            <p v-else-if="previewData.users" class="line__height" :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.users.username }}</p>
          </td>
          <td>
            <p class="text-right gray__p line__height">
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
import { Pumpkin }  from "@/assets/js/base64/Pumpkin";
import { Spider }  from "@/assets/js/base64/Spider";
import { Spiderweb }  from "@/assets/js/base64/Spiderweb";

export default {
  name: "previewComponent8",
  props: ["previewData"],
  mixins: [isIncludedInPackage],
  data(){
    return {
      has_dirty: false,
    }
  },
  computed:{
    setImagePumpkin(){
      return Pumpkin;
    },
    setImageSpider(){
      return Spider;
    },
    setImageSpiderweb(){
      return Spiderweb;
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
// import font

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
   margin: 40px 0px;
  //margin: 5px 10px 0px 10px;
  padding: 0px 15px 0px 15px;
  width: 100%;
  max-width: 1200px;
  height: 100%;
  font-size: 15px;
  //line-height: 1em;
  font-family: Astra, Helvetica , "sans-serif";
  //font-family: 'Montserrat', sans-serif;
  color: rgb(0, 0, 0);
  }
.invoice-box.text-mode-width {
    width: 660px;
  }
.alt__error{
  position: relative;
  z-index: 20;
  float:right;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  white-space: normal;
  font-size: 28px;
  color: #000000;
  //height: 60px;
  width: auto;
}
.alt__error h2{
  background-color: $secondary-light;
  padding: 15px 20px;
  line-height: 30px;
}
.error__img{
  max-height: 140px;
  width: auto;
  float: right;
}
.title{
  //width: 40%;
  vertical-align: top;
}
table{
  width: 100%;
}
.right__img{
  position: relative;
  max-height: 140px;
  height: 140px;
  width: auto;
  float: right;
}
.right__img[alt]{
  display: flex;
  font-size: 34px;
  white-space: normal;
  line-height: 55px;
}
.table__beneficiary table{
  margin: 0px 0px 15px 0px;
  width:250px;
  max-width:250px;
}
.table__beneficiary table thead{
  color: #c34b00;
  font-size: 23px;
  height: 40px;
  vertical-align: top;
  white-space: nowrap;
}
.table__beneficiary table tr td{
   height: 15px;
}
.table__beneficiary tbody tr:first-of-type{
  text-decoration: underline;
}
.table__reffered table{
  margin: 0px 0px 20px 0px;
  max-width: 1200px;
}
.table__reffered table thead{
  color: #c34b00;
  font-size: 23px;
  height: 50px;
}
.table__reffered table tbody tr td:first-of-type{
  width: 75%;
}
.right__box{
  vertical-align: top;
}
.table__right{
  width:250px;
  max-width:250px;
  float: right;
  text-align: right;
  height: 100%;
}
.table__right table{
  height: auto;
}
.table__right table thead{
  color: #c34b00;
  font-size: 23px;
  height: 40px;
  white-space: nowrap;
}
.table__right table tr td{
  height: 25px;
}
.table__right tbody tr:first-of-type{
  text-decoration: underline;
}
.pumpkin{
  position: relative;
  width: auto;
  height: 8rem;
  margin-top: -150px;
  margin-bottom: -85px;
  margin-left: -10px;
}
.table__gray{
  width: 100%;
  max-width: 1200px;
}
.table__gray thead{
  background-color: #c34b00;
}
.table__gray thead tr th:last-of-type{
  padding-right: 10px;
}
.table__gray thead tr th:first-of-type{
  padding-left: 110px;
}
.text-right{
   position: relative;
   z-index: 20;
}
.table__gray tbody tr{
  border-bottom: 4px solid #c34b00;
}
.table__gray thead tr th{
  color: #ffffff;
  height: 30px;
  white-space: nowrap;
}
.table__gray tbody tr td{
  height: 70px;
}
.table__gray tbody tr td:first-of-type{
  max-width: 250px;
  padding-left: 10px;
}
.table__gray tbody tr td:last-of-type{
  padding-right: 10px;
}
.data{
  padding-left: 10px;
  display: flex;
  flex-direction: row;
  margin-top: 60px;
}
.gray__header{
  color: #000000;
  font-size: 23px;
  margin-top:40px;
  width: 250px;
  vertical-align: top;
}
.gray__p{
  text-align: right;
}
.line__height{
  line-height: 2;
}
.gray__text{
  padding-top: 20px;
  color: $secondary-color;
  font-size: 18px;
  max-width: 250px;
  text-align: center;
  margin-left: 0px;
  padding-left: 0px;
}
.gray__small{
  position: relative;
  text-align: justify;
  z-index: 20;
}
.gray{
  position: relative;
  z-index: 10;
  color: #000000;
  white-space: nowrap;
  vertical-align: middle;
}
.black__border{
  border-bottom: 10px solid #c34b00;
  border-top: 4px solid #c34b00;
}
.additional__gray{
  padding-right: 10px;
  vertical-align: middle;
  font-size: 16px;
}
.spider__box{
  vertical-align: top;
}
.spider{
  position: relative;
  /*width: 200px;
  height: auto;*/
  width: auto;
  height: 9rem;
  float: right;
  margin-right: 20px;
  margin-top: -4px;
}
.table__gray tfoot tr td:first-of-type,
.notify__text{
  max-width: 250px;
  word-wrap: break-word;
}
.notify__text{
  padding-top: 20px;
  font-size: 14px;
  text-align: justify;
  margin-left: 0px;
  padding-left: 0px;
}
.z__index{
  position: relative;
  z-index: 10;
}
.main-table{
  width: 100%;
  max-width: 1200px;
}
.main-table tfoot tr{
  vertical-align: bottom;
}
.table__top{
  width: 100%;
  max-width: 1200px;
}
.wallpaper{
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 0;
  width: 100%;
  height: auto;
  left: 0;
  overflow: hidden;
  padding-left: 20px;
  padding-right: 20px;
  object-fit: contain;
}
.information{
  width: 100%;
  max-width: 1200px;
}
.box-width {
    width: 135px;
}
.title [data-attr="firm"]:hover{
    outline: 2px solid $secondary-light;
    cursor: pointer;
  }
.bold{
  font-weight: 600;
}
.information [data-attr="bank"]:hover,
.information [data-attr="firm"]:hover,
.information [data-attr='companies']:hover,
.information [data-attr='clients']:hover,
.table__right [data-attr='clients']:hover,
.title [data-attr="firm"]:hover,
footer [data-attr='footer']:hover,
.table__gray [data-attr='invoice']:hover,
.table__reffered [data-attr='invoice']:hover,
.details [data-attr='details']:hover,
tfoot [data-attr='details']:hover,
tfoot [data-attr='sender']:hover{
  background-color: #fab68c;
  cursor: pointer;
}
.information:active [data-attr="bank"],
.information:active [data-attr="firm"],
.information:active [data-attr='companies'],
.information:active [data-attr='clients'],
.table__right:active [data-attr='clients'],
.title:active [data-attr="firm"],
  footer:active [data-attr='footer'],
.table__gray:active [data-attr='invoice'],
.table__reffered:active [data-attr='invoice'],
.details:active [data-attr='details'],
tfoot:active [data-attr='details'],
tfoot:active [data-attr='sender']{
  text-decoration: underline;
}

</style>
