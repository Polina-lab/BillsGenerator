<template>
  <div class="container" :class="{ 'text-mode-width': previewData.is_pdf_pic_mode }">
    <div class="row" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
      <div class="col-6 p-0 m-0">
        <img v-if="previewData.firms" class="firm__img"
            :src="previewData.firms.view_in_invoice && previewData.firms.logo_blob &&
            (previewData.firms.logo_blob.length === 0 ?
            'https://test3.gloreal.ee' + previewData.firms.logo : previewData.firms.logo_blob)"
            :alt="previewData.firms.company_name"
            style="width:100%; max-width:250px; height: 140px;">
      </div>
      <div class="col-6 text-right p-0 m-0">
        <h4>
          <strong v-if="previewData.prepaid != 0">{{ $t("bills.prepaid", previewData.locale ) }}</strong>
          <strong v-else>{{ $t("bills.invoice", previewData.locale ) }}</strong>
        </h4>
        <p><strong>{{ $t("bills.invoice_number", previewData.locale )}} # </strong>{{ previewData.invoice}}<br>
        <strong>{{ $t("bills.invoice_date", previewData.locale )}}:</strong>  {{dateFormat(previewData.date)}} <br>
        <span v-if="previewData.deadline && previewData.deadline != '0000-00-00'"><strong>{{$t("bills.deadline",  previewData.locale )}}: </strong>{{dateFormat(previewData.deadline)}}</span></p>
        <p v-if="previewData.payment_method"><strong>{{ $t(`bills.${previewData.payment_method_name}`, previewData.locale) }}</strong></p>
      </div>
    </div> <!-- /row -->

    <div class="row " :class="{ 'box-width': previewData.is_pdf_pic_mode }">
      <div class="panel panel-info col-6 border-0 p-0 m-0">
        <div class="panel-heading">

            <h4>{{ $t('bills.beneficiary', previewData.locale )}}: <strong>{{ previewData['firms'].name  }}</strong></h4>

          <div class="panel-body">
            <p>
              <strong>{{ $t("bills.reg_num", previewData.locale )}}: </strong> {{ previewData['firms'].reg_num}}
            </p>
          </div>
        </div>
      </div>

        <div class="panel panel-info col-6 text-right p-0 m-0">
          <div class="panel-heading">

            <h4 v-if="previewData.companies !== null && previewData.companies.name ">{{ $t('bills.refered_to', previewData.locale )}} : {{ previewData.companies.name }}</h4>
            <h4 v-else-if="previewData.clients">{{ $t('bills.refered_to', previewData.locale )}} : {{ previewData.clients.firstname  }} {{ previewData.clients.lastname }}</h4>

          </div>
          <div class="panel-body">
            <p  v-if="previewData.companies !== null && previewData.companies.name "><strong v-if="previewData.companies.reg_num">{{ $t("bills.reg_num", previewData.locale )}}: </strong> {{ previewData.companies.reg_num }} </p>
            <p  v-else-if="previewData.clients"><strong v-if="previewData.clients.reg_num">{{ $t("bills.reg_num", previewData.locale )}}: </strong> {{ previewData.clients.reg_num }} </p>


            <p v-if="previewData.companies !== null && previewData.companies.name "><strong v-if="previewData.companies.address">{{ $t("bills.address", previewData.locale )}}: </strong> {{ previewData.companies.address  }} </p>
            <p v-else-if="previewData.clients"><strong v-if="previewData.clients.address">{{ $t("bills.address", previewData.locale )}}: </strong> {{ previewData.clients.address  }}</p>

            <p v-if="previewData.companies !== null && previewData.companies.name  "><strong v-if="previewData.companies.phone">{{ $t("bills.phone", previewData.locale )}}: </strong> {{ previewData.companies.phone }}</p>
            <p v-else-if="previewData.clients"><strong v-if="previewData.clients.phone">{{ $t("bills.phone", previewData.locale )}}: </strong> {{ previewData.clients.phone }} </p>

            <p v-if="previewData.companies !== null && previewData.companies.name  && previewData.clients !== null && previewData.clients.firstname !== null && previewData.clients.lastname !== null "><strong>{{ $t("bills.represented_by", previewData.locale )}}:</strong> {{ previewData.clients.firstname  }} {{ previewData.clients.lastname }} </p>

          </div>
        </div>

    </div> <!-- / end client details section -->
    <div class="row" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
    <table class="table p-0 m-0">
      <thead class="p-0 m-0">
      <tr >
        <th colspan="3">{{ $t('bills.explanation', previewData.locale )}}</th>
        <th>{{ $t('bills.unit', previewData.locale )}}</th>
        <th class="text-right">{{ $t('bills.amount', previewData.locale )}}</th>
        <th class="text-right">{{ $t('bills.price_per_unit', previewData.locale )}}</th>
        <th class="text-right">{{ $t('bills.total', previewData.locale ) }}</th>
      </tr>
      </thead>
      <tbody class="p-0 m-0">
      <tr v-for="(d, idx) in previewData.orders " :key="idx">
        <td colspan="3">{{ d.name }}</td>
        <td v-if="d.unit == 'Another'">{{ d.unit_custom }}</td>
        <td v-else-if="d.unit!='Another'">{{ d.unit }}</td>
        <td class="text-right">{{ d.amount }}</td>
        <td class="text-right"><span v-if="d.unit_price">{{ Number(d.unit_price).toFixed(2) }}</span>
          <span v-else>0.00</span></td>
        <td class="text-right"  @load="currency = d.currency; " >
          <span v-if="d.price">{{ d.price  + " "+ previewData.currency }}</span>
        </td>
      </tr>
      <tr v-if="previewData.comment">
        <td colspan="7"> <span v-html="previewData.comment ? previewData.comment : 'Comment:'"  ></span></td>
      </tr>

      </tbody>
    </table>
   </div>

    <div class="row " :class="{ 'box-width': previewData.is_pdf_pic_mode }">
      <div class="col-7 p-0 m-0">
        <p class="small">{{ $t("bills.pro_text", previewData.locale, {penalty: previewData.penalty ? previewData.penalty : 0.5} )}}</p>
      </div>

      <div class="col-5 text-right p-0 m-0">
        <p>

            {{ $t("bills.total_withoutKM", previewData.locale )}}: <strong>{{ previewData.price_no_km || previewData.price  }} {{previewData.currency}}</strong><br>
            {{ $t("bills.KM_percent", previewData.locale, {'km_percent': previewData.firms.km}) }}:
          <strong v-html="previewData.price_km !=0 && previewData.price_km ? previewData.price_km + previewData.currency : 'N/A'"></strong><br>
            {{ $t("bills.total", previewData.locale )}}: <strong>{{ previewData.price_with_km || previewData.price  }} {{previewData.currency}}</strong><br>

        </p>
      </div>

    </div>

    <div class="row" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
      <div class="col-5 p-0 m-0">
        <div class="panel panel-info p-0 m-0">
          <div class="panel-heading">
            <h4>{{ $t("bills.bank", previewData.locale )}}</h4>
          </div>
          <div class="panel-body">
            <p>
              <strong>{{ $t("bills.bank_name",previewData.locale )}}: </strong>{{ previewData['firms'].bank_name}}<br>
              <strong>{{ $t("bills.bank_num", previewData.locale )}}: </strong>{{ previewData['firms'].bank_num}}<br>
              <strong>{{ $t("bills.swift", previewData.locale )}}: </strong>{{ previewData['firms'].bank_swift}}<br>
              <strong>{{ $t("bills.bank_address", previewData.locale )}}: </strong>{{ previewData['firms'].bank_address}}<br>
              <strong>{{ $t("bills.km_reg_nr", previewData.locale )}}: </strong>
              <div v-if="previewData.firms.km_reg_num">
                {{ previewData['firms'].km_reg_num}}
              </div>
              <div v-else>
                {{ $t('User.missing_vat')}}
              </div>
            </p>
          </div>
        </div>

      </div>
      <div class="col-7 p-0 m-0">
        <div class="span7">
          <div class="panel panel-info">
            <div class="panel-heading text-right">
              <h4>{{ $t("bills.contact_details", previewData.locale )}}</h4>
            </div>
            <div class="panel-body text-right">
              <p>
                <strong>{{ $t("bills.email", previewData.locale )}}: </strong> info@gloreal.ee <br>
                <strong>{{ $t("bills.phone", previewData.locale )}}: </strong>{{ previewData['firms'].phone}}<br>
                <strong>{{ $t("bills.site", previewData.locale )}}: </strong> <a href="https://www.gloreal.ee">https://www.gloreal.ee</a><br>
                <small>{{ $t("bills.notify", previewData.locale )}}
                {{ $t("bills.notify_text", previewData.locale )}}</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
      <div class="col-6 p-0 m-0">
        <p><strong>{{ $t("bills.Send_by", previewData.locale )}}:</strong></p>
        <p> &nbsp; <span v-if="previewData['sender_user_id']">{{ previewData['sender_user'].username }}</span> </p> <br>
        <hr>
      </div>
      <div class="col-6  text-right p-0 m-0">
        <p><strong>{{ $t("bills.received_by", previewData.locale )}}:</strong></p>
        <p> &nbsp; <span v-if="previewData.clients">{{ previewData.clients.firstname ? previewData.clients.firstname : " "}} {{  previewData.clients.lastname ? previewData.clients.lastname : " "  }}</span> </p> <br>
        <hr>
      </div>
    </div>
  <!-- #region Footer -->
       <footer v-if="previewData.firms.banks.length > 1" :class="{ 'box-width': previewData.is_pdf_pic_mode }">
        <!-- <tr> -->
          <!-- <td colspan="7" class="text-center" style="float: bottom">  -->
            <tr v-for="(bank, idx) in previewData.firms.banks" :key="bank.id">
              <td v-if="idx != 0"> {{
                `${bank.bank_num} ${bank.bank_name} ${$t("bills.swift", previewData.locale)}: ${bank.bank_swift}`
                }}
              </td>             
            </tr>
          <!-- </td> -->
        <!-- </tr> -->
      </footer>
    <!-- #endregion Footer -->
  </div> <!-- /container -->
</template>

<script>
import dateFormat from "../../mixins/dateFormat";
export default {
  name: "previewComponent3",
  props: ["previewData"],
  mixins: [dateFormat],
  data() {
    return {
      sum: 0,
      km_def: 0,
      penalty_num: 0
    }
  },
 methods:{
   longText(previewData){
     if(previewData.comment > 5){
       return 'font-size: 12px';
     }else{
       return 'font-size: 12px';
     }
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
  .container{
    font-family: Astra, Helvetica , "sans-serif";
  }

  .table {
    margin: 0;
    padding: 0;
    table-layout: fixed;
    width: 100%;
}

@media only screen and (max-width:480px) {

  .h4, h5, h6 {
    font-size: 1rem;
  }

  .text-responsive {
    height: fit-content;
    width: max-content;
    font-size: calc(1vw + 1vh);
    align-content: stretch;
    margin: 0;
    padding: 0;
  }
}
.container.row{
  width: 100%;
  max-width: 1200px;
}
.container.text-mode-width{
  width: 400px;
  max-width: 400px;
  margin: auto;
  padding: 0;
}
 .box-width {
    width: 430px;
    max-width: 430px;
    font-size: 11px;
}
</style>
