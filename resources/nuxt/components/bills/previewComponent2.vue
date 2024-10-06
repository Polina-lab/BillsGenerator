<template>
  <div class="invoice" :class="{ 'text-mode-width': previewData.is_pdf_pic_mode }">
    <div class="wrapper" >
    <img :src="previewData.firms.view_in_invoice && 'https://test.gloreal.ee'+previewData.firms.logo"
        :alt="previewData.firms.company_name"
        style="width:100%; max-width:250px;">
      <div class="container">
        <table class="table1">
          <tbody>
            <tr >
              <td>{{ $t('bills.beneficiary', previewData.locale)}}</td>
              <td>{{ previewData.firms.company_name }}</td>
              <td></td>
            </tr>
            <span v-if="previewData.firms.banks">
              <tr v-for="(bank, idx) in previewData.firms.banks" :key="bank.id">
                <td v-if="idx == 0">{{ $t('bills.requisites', previewData.locale)}}</td>
                <td v-else></td>
                <td> {{
                  `${bank.bank_num} ${bank.bank_name} ${$t("bills.swift", previewData.locale)}: ${bank.bank_swift}`
                  }}
                </td>
                <td></td><!-- clarify -->
              </tr>
            </span>
            <tr>
              <td v-if="previewData.firms.representative_custom">
                {{ $t(`bills.${previewData.firms.representative_custom}`, previewData.locale) }}
              </td>
              <td v-else>{{ $t(`representative.${previewData.firms.representatives.name}`, previewData.locale) }}</td>
              <td>{{ previewData.firms.representative_name }}</td>
              <td></td>
            </tr>
            <tr v-if="previewData.firms.km_reg_num">
              <td>{{ $t("bills.km_reg_nr", previewData.locale) }}</td>
              <td>{{ previewData.firms.km_reg_num }}</td>
              <td></td>
            </tr>
            <tr v-else>
              <td>{{ $t("bills.km_reg_nr", previewData.locale) }}</td>
              <td>{{ $t('User.missing_vat')}}</td>
              <td></td>
            </tr>
            <tr v-if="previewData.firms.bank_address">
              <td>{{ $t("bills.address", previewData.locale) }}</td>
              <td>{{ previewData.firms.bank_address }}</td>
              <td></td>
            </tr>
          </tbody>
        </table>

        <table class="table2">
          <tbody>
            <tr v-if="previewData.invoice">
              <td>{{ $t("bills.invoice_number", previewData.locale )}}</td>
              <td class="text-right">{{ previewData.invoice}}</td>
            </tr>
            <tr>
              <td>{{ $t("bills.invoice_date", previewData.locale )}}</td>
              <td class="text-right">{{dateFormat(previewData.date)}}</td>
            </tr>
            <tr>
              <td>{{ $t("bills.deadline", previewData.locale )}}</td>
              <td class="text-right" v-if="previewData.deadline">{{dateFormat(previewData.deadline)}}</td>
            </tr>
            <tr v-if="previewData.payment_method">
              <td>{{ $t("bills.payment_method", previewData.locale )}}</td>
              <td class="text-right">{{ $t(`bills.${previewData.payment_method_name}`, previewData.locale) }}</td>
            </tr>
            <tr>
              <td colspan="2" id="colspan_text">
                <p v-if="previewData.penalty">
                {{ $t("bills.new_pro_text", previewData.locale, {"penalty" : previewData.penalty}) }}
                </p>
                <p v-else>
                  {{ $t("bills.new_pro_text", previewData.locale, {"penalty" : 0.5}) }}
                </p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

        <table class="table3">
          <tbody>
            <tr>
              <td>{{ $t('bills.refered_to', previewData.locale )}}</td>
              <td v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null">{{ previewData.companies.name }}</td>
            </tr>
            <tr>
              <td>{{ $t("bills.address", previewData.locale )}}</td>
              <td v-if="!Array.isArray(previewData.companies)&&previewData.companies!==null">{{ previewData.companies.address }}</td>
            </tr>
          </tbody>
        </table>

        <table class="table4">
          <thead>
              <tr>
                <th colspan="2" class="text-left">{{ $t("bills.explanation", previewData.locale )}}</th>
                <th class="text-center">{{ $t("bills.unit", previewData.locale )}}</th>
                <th class="text-center">{{ $t("bills.amount", previewData.locale )}}</th>
                <th class="text-center" style="width: 100px;">{{ $t("bills.price", previewData.locale )}}</th>
                <th class="text-center">{{ $t("bills.KM", previewData.locale )+'%' }}</th>
                <th class="text-right"  style="width: 100px;">{{ $t("bills.total", previewData.locale )}}</th>
              </tr>
          </thead>
          <tbody>
            <tr v-for="(d , idx) in previewData.orders " :key="idx">
              <td class="text-right"></td><!-- clarify -->
              <td id="long_text">{{ d.name }}</td>
              <td v-if="d.unit == 'Another'" class="text-center">{{ d.unit_custom }}</td>
              <td v-else-if="d.unit != 'Another'" class="text-center">{{ d.unit }}</td>
              <td class="text-center">{{ d.amount }}</td>
              <td class="text-right">
                <span v-if="d.unit_price">{{ Number(d.unit_price).toFixed(2,',') }}</span>
                <span v-else>0.00</span></td>
              <td class="text-right">{{previewData.firms.km}}</td>
              <td class="text-right" @load="currency = d.currency;" v-if="d.price">
                <span v-if="d.price">{{ d.price }}</span>
              </td>
            </tr>
          </tbody>
        </table>

        <table class="table5">
          <tbody>
            <tr>
              <!-- <td style="width: 150px;">{{ $t("bills.sum_in_words", previewData.locale )}}</td> -->
              <!-- <td rowspan="2" class="bold" style="width: 200px;">kakssada kaheksa eurot ja üheksakümmend neli senti</td>                            -->
              <td></td>
              <td></td>
              <td class="text-right" style="white-space: nowrap;">{{ $t("bills.total_withoutKM", previewData.locale )}}</td>
              <td class="text-right" v-if="previewData.price_no_km">{{ previewData.price_no_km }}</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <!-- <td>({{ previewData.currency }})</td> -->
              <!-- <td class="for_nowrap text-right">{{ $t("bills.KM", previewData.locale) }}</td> -->
              <td class="for_nowrap text-right">
                {{ $t("bills.KM_percent", previewData.locale, {'km_percent': previewData.firms.km}) }} ({{ $t("bills.from_the_total_withoutKM", previewData.locale) }} {{ previewData.price_no_km }})</td>
              <td class="for_nowrap text-right" v-if="previewData.price_km">{{ previewData.price_km }}</td>
            </tr>
            <tr>
              <td class="blank_row" colspan="4"></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td class="text-right bold">{{ $t("bills.total", previewData.locale )}}({{ previewData.currency }})</td>
              <td class="text-right bold" v-if="previewData.price_with_km">{{ previewData.price_with_km }}</td>
            </tr>
            <tr>
              <td class="blank_row" colspan="4"></td>
            </tr>
            <tr>
              <td>{{ $t("bills.Send_by", previewData.locale )}}</td>
              <td colspan="2" v-if="previewData.sender_user">{{ previewData.sender_user.username }}</td>
              <td colspan="2" v-else-if="previewData.users">{{ previewData.users.username }}</td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td v-if="previewData.sender_user"><span>telefon </span><span>{{ previewData.firms.phone}}</span></td><!-- clarify -->
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td v-if="previewData.sender_user"><span>e-post </span><span>{{ previewData.sender_user.email }}</span></td><!-- clarify -->
              <td v-else-if="previewData.users"><span>e-post </span><span>{{ previewData.users.email }}</span></td><!-- clarify -->
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
       </div>
      <!-- #region Footer -->
        <div class="white"></div>
         <footer>
          <!-- <tr> -->
            <!-- <td colspan="7" class="text-center" style="float: bottom">  -->
              <span v-html="previewData.firms.footer">
              </span>
            <!-- </td> -->
          <!-- </tr> -->
        </footer>
      <!-- #endregion Footer -->
  </div>

</template>
<script>
import dateFormat from "../../mixins/dateFormat";

export default {
  name: "previewComponent2",
  props: ["previewData"],
  mixins: [dateFormat]
}

</script>

<style scoped>

  img{
    width:100%;
    max-width:250px;
    padding-bottom: 5%;
  }
  @font-face {
    font-family: 'DejaVu';
    font-style: normal;
    font-weight: 500;
    src: local('DejaVu Sans'), local('PT DejaVu Sans');
    font-display: swap;
  }
  .invoice {
    flex: 1 0 auto;
    display: fixed;
    margin: 3% 5% auto 5%;
    font-size: 14px;
    font-family: DejaVu Sans , Helvetica , "sans-serif";
    color: rgb(54, 53, 53);
    flex-wrap: nowrap;
    min-width: 550px;
    max-width: 1200px;
  }
  .wrapper{
    position: relative;
    min-height: 20.7cm;
  }

  .container{
    display: flex;
    justify-content: spase-around;
    max-width: 1200px !important;
    width: 100%;
  }

  /*//////////////////table1/////////////////*/

  .invoice table.table1 {
    display: flex;
    width: 100%;
    /*max-width: 100%;*/
    max-width: 680px;
    height: 100%;
    table-layout: inherit;
    vertical-align: top;
    text-align: left;
    float:left;
  }

  .invoice table.table1 tbody tr td:nth-child(2n){
    white-space: nowrap;
    width: 250px;
    vertical-align: top;
  }

  @media (max-width:1200px) {
    .invoice table.table1 tbody tr td:nth-child(2n){
      white-space: normal;
    }
  }

  .invoice table.table1 tbody tr td:nth-child(2n + 1){
    font-weight: bold;
    white-space: nowrap;
    width: 110px;
    vertical-align: top;
  }

/*//////////////////table2/////////////////*/

  .invoice table.table2 {
    display: flex;
    width: 40%;
    /*max-width: 40%;*/
    max-width: 260px;
    height: 100%;
    table-layout: inherit;
    line-height: 150%;
  }

  .invoice table.table2 tbody tr td:nth-child(2n + 1) {
    font-weight: bold;
    min-width: 130px;
    white-space: nowrap;
  }


  .invoice table.table2 tbody tr td:nth-child(2n) {
    white-space: wrap;
    width: 150px;
  }

  #colspan_text{
    font-weight: normal!important;
    white-space: normal!important;
  }

  /*//////////////////table3/////////////////*/

  .invoice table.table3 {
    width: 100%;
    /*max-width: 100%;*/
    max-width: 1200px;
    table-layout: fixed;
    line-height: 180%;
    text-align: left;
    border-top: 2px solid rgb(99, 97, 97);
  }

  .invoice table.table3 tbody tr td:nth-child(2n + 1){
    width: 110px;
  }

   .invoice table.table3 tbody tr td:nth-child(2n){
    font-weight: bold;
  }

  /*//////////////////table4/////////////////*/

  .invoice table.table4 {
    width: 100%;
    max-width: 1200px;
    table-layout: inherit;
    text-align: left;
    border-bottom: 2px solid rgb(99, 97, 97);
  }

  .invoice table.table4 thead{
    border-collapse: collapse;
    border: 2px solid  rgb(99, 97, 97);
    line-height: 190%;
  }

  .invoice table.table4 thead tr th{
    border-collapse: collapse;
    border: 1.8px solid  rgb(99, 97, 97);
    text-align: justify;
    font-weight: normal!important;
  }

  .invoice table.table4 td {
    padding: 5px;
    vertical-align: top;
  }

#long_text{
  width: 40%;
}

/*//////////////////table5/////////////////*/

.invoice table.table5 {
    width: 100%;
    /*max-width: 100%;*/
    max-width: 1200px;
    table-layout: inherit;
    text-align: left;
  }

  .invoice table.table5 tbody tr td:nth-child{
    vertical-align: top;
  }

  @media (max-width:1200px) {
    .for_nowrap{
      white-space: normal;
    }
  }

.bold{
  font-weight:bolder;
}

.white{
  min-height: 60px;
  height: 100%;
}

.blank_row
{
    height: 30px !important;
    background-color: #FFFFFF;
}
.invoice.text-mode-width {
  width: 430px;
  max-width: 430px;
}
.invoice.text-mode-width table.table1 tbody tr td:nth-child(2n){
  white-space:initial;
  width: 430px;
  max-width: 430px;
}
</style>
