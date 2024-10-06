<template>
  <div class="container-fluid" >
    <div class="invoice-box" :class="{ 'text-mode-width': previewData.is_pdf_pic_mode }">
      <table class="main-table" cellpadding="0" cellspacing="0">
        <tr class="top" >
          <td colspan="2">
            <table>
              <tr>
                <td class="title">
                  <img v-if="previewData.firms" :data-attr="checkType('firm')" class="firm" @click="updateTest($event)"
                       :src="previewData.firms.view_in_invoice && previewData.firms.logo_blob &&
                        (previewData.firms.logo_blob.length === 0 ?
                          'https://test3.gloreal.ee' + previewData.firms.logo : previewData.firms.logo_blob)"
                       :alt="previewData.firms.company_name"
                       style="width:100%; max-width:250px;">
                </td>
                <td></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr class="information">
          <td>
            <div id="t1">
              <table v-if="previewData['firms']">
                <thead>
                  <tr>
                    <th class="text-left" colspan="2">{{ $t('bills.beneficiary', previewData.locale)}}</th>
                  </tr>
                </thead>
                <tbody>
                <tr v-if="previewData['firms'].name">
                  <td colspan="2" class="text-left">
                      <strong :data-attr="checkType('firm')" class="firm under_border" @click="updateTest($event)">
                          {{ previewData['firms'].company_name }}
                      </strong>
                  </td>
                </tr>
                <tr v-if="previewData['firms'].reg_num">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.reg_num", previewData.locale)}}: </strong> <span :data-attr="checkType('firm')" class="firm" id="reg_num" @click="updateTest($event)" > {{ previewData['firms'].reg_num}}</span>
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
                    <strong>{{ $t("bills.phone", previewData.locale)}}: </strong><span :data-attr="checkType('firm')" id="phone" class="firm"  @click="updateTest($event)">{{ previewData['firms'].phone}}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('bank_name')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.bank", previewData.locale)}}:</strong><span  :data-attr="checkType('bank')"  id="bank_name" class="bank" @click="updateTest($event)">{{ getBankData('bank_name')}}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('bank_num')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.bank_num", previewData.locale)}}:</strong><span  :data-attr="checkType('bank')" id="bank_num" class="bank" @click="updateTest($event)">{{ getBankData('bank_num')}}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('bank_account')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.acc_num", previewData.locale)}}:</strong><span  :data-attr="checkType('bank')"  id="bank_account" class="bank" @click="updateTest($event)">{{ getBankData('bank_account')}}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('bank_address')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.swift", previewData.locale )}}:</strong><span  :data-attr="checkType('firm')"  id="bank_swift" class="bank" @click="updateTest($event)">{{ getBankData('bank_swift')}}</span>
                  </td>
                </tr>

                <tr v-if="getBankData('bank_address')">
                  <td colspan="2" class="text-left">
                    <strong>{{ $t("bills.bank_address", previewData.locale )}}: </strong><span  :data-attr="checkType('bank')" id="bank_address" class="bank" @input="updateTest($event)">{{ getBankData('bank_address')}}</span>
                  </td>
                </tr>
                <tr v-if="getBankData('km_reg_num')">
                  <td class="text-left">
                    <strong>{{ $t("bills.km_reg_nr", previewData.locale )}}: </strong><span  :data-attr="checkType('bank')" id="km_reg_num" class="bank" @input="updateTest($event)">{{ getBankData('km_reg_num')}}</span>
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
            <div id="t2">
              <table>
                <thead>
                  <tr>
                    <th colspan="2" class="text-left">
                      {{ $t('bills.refered_to', previewData.locale )}}
                    </th>
                  </tr>
                </thead>
                <tbody>
                <tr>
                  <td colspan="2" class="text-left">
                    <strong class="under_border">
                      <span  v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null" :data-attr="checkType('companies')" @click="updateTest($event)" >
                        {{ previewData.companies.name }}
                      </span>
                      <span v-else-if="previewData.clients" :data-attr="checkType('clients')" @click="updateTest($event)">
                        {{ previewData.clients.firstname }} {{ previewData.clients.lastname }}
                      </span>
                    </strong>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" class="text-left">
                    <span v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null">
                      <strong v-if="previewData.companies.reg_num">
                        {{ $t("bills.reg_num", previewData.locale )}}:
                      </strong>
                      <span @click="updateTest($event)" >{{ previewData.companies.reg_num }}</span>
                    </span>
                    <span v-else-if="previewData.clients">
                      <strong v-if="previewData.clients.reg_num">
                        {{ $t("bills.reg_num", previewData.locale )}}:
                      </strong><span :data-attr="checkType('clients')" @click="updateTest($event)">{{ previewData.clients.reg_num }}</span>
                    </span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" class="text-left" >
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
                <tr>
                  <td colspan="2" class="text-left">
                    <span v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null">
                      <strong v-if="previewData.companies.phone">
                        {{ $t("bills.phone", previewData.locale )}}:
                      </strong><span @click="updateTest" :data-attr="checkType('companies')">{{ previewData.companies.phone }}</span>
                    </span>
                    <span v-else-if="previewData.clients">
                      <strong v-if="previewData.clients.phone">
                         {{ $t("bills.phone", previewData.locale )}}:
                      </strong>
                      <span @click="updateTest" :data-attr="checkType('clients')">{{ previewData.clients.phone }}</span>
                    </span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" class="text-left" v-if="previewData.clients && previewData.clients.firstname " >
                    <strong>
                      {{$t("bills.represented_by", previewData.locale )}}:
                    </strong>
                    <span  @click="updateTest" :data-attr="checkType('clients')">{{ previewData.clients.firstname }} {{  previewData.clients.lastname }}</span>
                  </td>
                </tr>
                  <tr v-if="previewData.companies.length === 0 && previewData.clients.length ===0">
                    <td>
                      <span  @click="updateTest" :data-attr="checkType('clients')">+ {{ $t('bills.add_client_or_company')}}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </td>
        </tr>
        <tr class="inv">
          <td></td>
          <td>
            <table>
              <tr>
                <td class="text-right" colspan="2">
                  <strong v-if="previewData.prepaid == 0">{{ $t("bills.invoice", previewData.locale ) }}</strong>
                  <strong v-else-if="previewData.prepaid == 1">{{ $t("bills.prepaid", previewData.locale ) }}</strong>
                </td>
              </tr>
              <tr v-if="previewData.invoice">
                <td class="text-right"><strong>{{ $t("bills.invoice_number", previewData.locale )}}:</strong></td>
                <td class="text-right" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
                  <span :data-attr="checkType('invoice')" @click="updateTest($event)">{{ previewData.invoice }}</span>
                </td>
              </tr>
              <tr>
                <td class="text-right"><strong>{{ $t("bills.invoice_date", previewData.locale )}}:</strong></td>
                <td class="text-right" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
                  <span :data-attr="checkType('invoice')" @click="updateTest($event)">{{ previewData.date }}</span>
                </td>
              </tr>
              <tr v-if="previewData.deadline">
                <td class="text-right"><strong>{{$t("bills.deadline",  previewData.locale )}}:</strong></td>
                <td class="text-right" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
                    <span :data-attr="checkType('invoice')" @click="updateTest($event)">{{ previewData.deadline }}</span>
                  </td>
              </tr>
              <tr v-if="previewData.payment_method">
                <td class="text-right" colspan="2"><strong>{{ $t(`bills.${previewData.payment_method_name}`, previewData.locale) }}</strong></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr class="details">
          <td colspan="2">
            <table id="details_table">
              <thead>
                <tr>
                  <th colspan="3" class="text-left">{{ $t('bills.explanation', previewData.locale )}}</th>
                  <th class="text-center">{{ $t('bills.unit', previewData.locale )}}</th>
                  <th class="text-center">{{ $t('bills.amount', previewData.locale )}}</th>
                  <th class="text-center">{{ $t('bills.price_per_unit', previewData.locale )}}</th>
                  <th class="text-center">{{ $t('bills.total', previewData.locale ) }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(d, idx) in previewData.orders" :key="idx">
                  <td colspan="3" class="text-left mw450" ><span :data-attr="checkType('details')" @click="updateTest($event)">{{ d.name }}</span>
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
                  <td colspan="7">
                    <span :data-attr="checkType('details')" @click="updateTest($event)" v-html="previewData.comment ? previewData.comment+'<br/>': '<br/><br/><br/>'"></span>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="w_b" v-if="previewData.price_no_km">
                  <th colspan="6" class="text-right ">{{ $t("bills.total_withoutKM", previewData.locale )}}:</th>
                  <td><span :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.price_no_km  + " " + previewData.currency }}</span></td>
                </tr>
                <tr v-if="previewData.price_km">
                  <th colspan="6" class="text-right">{{ $t("bills.KM_percent", previewData.locale, {'km_percent': previewData.firms.vat.find(v => v.id === previewData.vat_id ).vat }) }}:</th>
                  <td style="white-space:nowrap">
                    <span :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.price_km + " " + previewData.currency }}</span></td>
                </tr>
                <tr v-if="previewData.price_with_km">
                  <th colspan="6" class="text-right ">{{ $t("bills.total" , previewData.locale )}}:</th>
                  <td style="white-space:nowrap"><span :data-attr="checkType('details')" @click="updateTest($event)" >{{ previewData.price_with_km + " " + previewData.currency}}</span></td>
                </tr>
                <tr v-else-if="previewData.price">
                  <th colspan="6" class="text-right ">{{ $t("bills.total" , previewData.locale )}}:</th>
                  <td style="white-space:nowrap"><span :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.price + " " + previewData.currency }}</span></td>
                </tr>
              </tfoot>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="text-center">
            <small v-if="previewData.penalty" :data-attr="checkType('details')" @click="updateTest($event)">
              {{ $t("bills.pro_text", previewData.locale, {"penalty": previewData.penalty} )}}
            </small>
            <small v-else :data-attr="checkType('details')" @click="updateTest($event)">
              {{ $t("bills.pro_text", previewData.locale, {"penalty": 0.5} )}}
            </small>
          </td>
        </tr>
        <tr class="notify">
          <td>
            <div  style="margin-top: 30px">
              <strong>{{ $t("bills.notify", previewData.locale )}}</strong>
            </div>
          </td>
          <td></td>
        </tr>
        <tr class="notify-text">
          <td colspan="2" >
            <strong>{{ $t("bills.notify_text", previewData.locale )}}</strong>
          </td>
          <td></td>
        </tr>
        <tr>
          <td>
            <div style="width: 260px; margin-top: 40px">
              <strong>{{ $t("bills.Send_by", previewData.locale )}}</strong>
              <br>
                <p v-if="previewData.sender_user" :data-attr="checkType('sender')" @click="updateTest($event)">{{ previewData.sender_user.username }}</p>
                <p v-else-if="previewData.users" :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.users.username }}</p>
            </div>
          </td>

          <td >
            <div style="width: 260px; float: right; margin-top:40px ">
              <strong style="float: left;">{{ $t("bills.received_by", previewData.locale )}}</strong>
              <br>
              <p style="float: left;">
                <span v-if="previewData.clients" :data-attr="checkType('details')" @click="updateTest($event)">{{ previewData.clients.firstname ? previewData.clients.firstname : " "}} {{  previewData.clients.lastname ? previewData.clients.lastname : " "  }}</span>
              </p>
            </div>
          </td>
        </tr>
      </table>
      <!-- #region Footer -->
       <footer v-if="previewData.firms && previewData.firms.banks.length > 1">
        <!-- <tr> -->
          <!-- <td colspan="7" class="text-center" style="float: bottom">  -->
            <tr v-for="(bank, idx) in previewData.firms.banks" :key="bank.id">
              <td v-if="idx != 0">
                <p :data-attr="checkType('footer')" @click="updateTest($event)" >
                  <strong>{{ $t("bills.bank_name", previewData.locale) }}: </strong>{{ bank.bank_name }}
                </p>
                <p :data-attr="checkType('footer')" @click="updateTest($event)" >
                  <strong>{{ $t("bills.bank_num", previewData.locale) }}: </strong>{{ bank.bank_num }}
                </p>
                <p :data-attr="checkType('footer')" @click="updateTest($event)">
                  <strong>{{ $t("bills.swift", previewData.locale) }}: </strong>{{ bank.bank_swift }}
                </p>
                <p :data-attr="checkType('footer')" @click="updateTest($event)">
                  <strong>{{ $t("bills.bank_address", previewData.locale) }}: </strong>{{ bank.bank_address }}
                </p>
              </td>
            </tr>
          <!-- </td> -->
        <!-- </tr> -->
      </footer>
    <!-- #endregion Footer -->
    </div>
  </div>
</template>

<script>
import isIncludedInPackage from "../../mixins/isIncludedInPackage";

export default {
  name: "previewComponent",
  props: ["previewData"],
  mixins: [isIncludedInPackage],
  data(){
    return {
      has_dirty: false,
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

<style scoped>


  @font-face {
    font-family: 'Astra';
    font-style: normal;
    font-weight: 500;
    src: local('Astra Sans'), local('PT Astra Sans');
    font-display: swap;
  }

  .under_border{
    border-bottom: 1px solid #000000;
  }

  .invoice-box {
    margin: auto;
    /* box-shadow: 0 0 10px rgba(0, 0, 0, .15); */
    font-size: 15px;
    line-height: 0.9em;
    font-family: Astra, Helvetica , "sans-serif";
    color: rgb(0, 0, 0);
  }

  .invoice-box table{
    width: 100%;
    max-width: 1200px;
    line-height: inherit;
    text-align: left;
  }

  .invoice-box.text-mode-width table {
    width: 400px;
  }

  /* .main-table{
    width: 100%;
    max-width: 1200px;
    line-height: inherit;
    text-align: left;
   } */

  #details_table {
    width: 100%;
    max-width: 1200px;
    line-height: inherit;
    text-align: left;
  }

  .mw450{
    max-width: 500px;
  }

  .invoice-box table td {
    padding: 5px;
    vertical-align: top;
  }

  .invoice-box table tr td:nth-child(2) {
    text-align: right;
  }

  .invoice-box table tr.top table td {
    padding-bottom: 20px;
  }

  .invoice-box table tr.top table td.title {
    font-size: 45px;
    line-height: 45px;
    color: #333;
  }

  .invoice-box table tr.information table td {
    padding-bottom: 2px;
  }

  .invoice-box table tr.heading td {
    background: #eee;
    border-bottom: 1px solid #ddd;
  }

  thead{
    background-color: #00b3ff;
  }

  .invoice-box table tr.details table {
    border: 1px solid lightgray;
    border-collapse: collapse;
  }


  .invoice-box table tr.details table td{
    border: 1px solid lightgray;
    border-collapse: collapse;
  }

  #t1{
    height: 260px;
    width:250px;
    /*border: #0c1021 solid 1px;*/
    border: 2px solid lightgray;
  }

  #t1 table {
    max-width: 250px;
  }

  #t2{
    height: 260px;
    width:250px;
    /*border: #0c1021 solid 1px;*/
    border: 2px solid lightgray;
    float: right;
  }

  #t2 table {
    max-width: 250px;
  }

  .box-width {
    width: 135px;
  }

  .information:hover  [data-attr="bank"]{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }
  .information:hover [data-attr="firm"]{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }
  .information:hover [data-attr='companies']{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }
  .information:hover [data-attr='clients']{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }

  .title:hover [data-attr="firm"]{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }
   footer:hover [data-attr='footer']{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }

  .inv:hover [data-attr='invoice']{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }

  .details:hover [data-attr='details']{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }

  .notify:hover [data-attr='details']{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }

  .notify:hover [data-attr='sender']{
    background-color: rgb(181, 220, 245);
    cursor: pointer;
  }

  .invoice-box table tr.item td{
    border-bottom: 1px solid #eee;
  }

  .invoice-box table tr.item.last td {
    border-bottom: none;
  }
  .text-right{
    text-align: right;
  }
  .text-center{
    text-align: center;
    /* font-size: 1.2vw; */
  }

  .text-left{
    text-align: left;
  }

  .invoice-box table tr.total td:nth-child(2) {
    border-top: 2px solid #eee;
  }

  .notify{
    color:rgb(128, 126, 126);
    letter-spacing: 0px;
    font-size: 15px;
  }

  .notify-text{
    color:rgb(128, 126, 126);
    line-height: 20px;
    letter-spacing: 0px;
  }

  p + p{
    margin-bottom: 0.2rem !important;
  }
/*.container-fluid{
  @media (max-width: 992px) {
      transform: scale(0.75);
      margin-top: -30%;
      margin-left: -15%;
      border: none;
  }
  @media (max-width: 767px) {
      transform: scale(0.9);
      margin-top: -10%;
      margin-left: -10%;
      border: none;
  }

  @media (max-width: 749px) {
      transform: scale(1);
      margin-top: 0%;
      margin-left: -10%;
      border: none;
  }
  @media (max-width: 575px) {
      transform: scale(0.8);
      margin-top: -15%;
      margin-left: -10%;
      border: none;
  }
  @media (max-width: 499px) {
      transform: scale(0.6);
      margin-top: -50%;
      margin-left: -20%;
      border: none;
  }
  @media (max-width: 399px) {
      transform: scale(0.5);
      margin-top: -60%;
      margin-left: -30%;
      border: none;
    }
  }*/
</style>
