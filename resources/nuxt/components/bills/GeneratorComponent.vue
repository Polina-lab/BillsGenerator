<template>
  <div class="container" style="overflow-y: auto; max-height: 70%">
    <section v-if="has_display !== 4">
      <LazyArrowsComponent :steps_amount="4" :style="arrow_styles" :page="has_display+1" @arrow_clicked="changeStage(eventBill.firm_id, $event)"></LazyArrowsComponent>
    </section>
    <section>
      <div class="alert alert-danger p20" role="alert" v-if="error.show">
        {{ error.msg }}
      </div>
    </section>
    <section v-if="has_display === 0">
      <LazyPage0Component :eventBill="eventBill" @addNewFirm="$emit('addNewFirm')"
      ></LazyPage0Component>
    </section>

    <section v-if="has_display === 1">
      <LazyPage1Component></LazyPage1Component>
    </section>

    <section v-if="has_display === 2">
      <LazyPage2Component :eventBill="eventBill"></LazyPage2Component>
    </section>

    <section v-if="has_display === 3">
      <LazyPage3Component :eventBill="eventBill"></LazyPage3Component>
    </section>

    <section v-if="has_display === 4">
      <Page4Component :eventBill="eventBill"
                      @changeDisplay="changeDisplay($event)"
                      @changeFirm="(data) => { $emit('changeFirm', data) }"
                      @changeClient="has_display = 1">
      </Page4Component>
    </section>

    <div class="d-flex justify-content-between p20" v-if="has_display !== 4">
      <div>
        <button class="btn btn-outline-info"
                v-if="has_display === 0 ? false : true"
                @click="changeStage(has_display-1)">
          <i class="fa fa-arrow-left"></i>
        </button>
      </div>
      <div v-if="has_display !=4 && (eventBill.firm_id  && eventBill.date && client)">
        <button class="btn btn-secondary" v-if="eventBill.id" @click="cancelViewGen">
          {{ $t("common.cancel") }}
        </button>
        <button class="btn btn-success" v-if="eventBill.id" @click="updateGen">
          {{ $t("common.update") }}
        </button>
        <button class="btn btn-success" v-else-if="eventBill.clients !== null && eventBill.orders[0].name.length > 1" @click="saveGen">
          {{ $t("common.create") }}
        </button>

      </div>
      <div>

      <div v-if="eventBill.firm_id">
      <button class="btn btn-outline-info"
               @click="correct(eventBill.firm_id, has_display)">
          <i class="fa fa-arrow-right"></i>
      </button>
      </div>

      </div>
    </div>

    <Modal :title="$t('clients.error_modal')"
           @closeModal="showModal.status = $event;"
           v-if="showModal.status">
      <div class="alert alert-danger">
        {{ showModal.msg }}
      </div>
      <template v-slot:footer>
        <button type="button"
                class="btn btn-primary"
                @click=" showModal.status = false;">
          {{ $t('common.ok')}}
        </button>
      </template>
    </Modal>
  </div>
</template>

<script>
import Page4Component from "@/components/bills/generator/Page4Component";
// import {User} from "assets/js/models/Users";
// let VueEditor;
// if (process.client) {
//   VueEditor = require('vue2-editor').VueEditor;
// }

export default {
  name: "GeneratorComponent",
  components: {
    Modal: () => import("@/components/common/Modal"),
    LazyArrowsComponent: () => import('@/components/elements/Arrows'),
    LazyPage0Component: () => import('@/components/bills/generator/Page0Component'),
    LazyPage1Component: () => import('@/components/bills/generator/Page1Component'),
    LazyPage2Component: () => import('@/components/bills/generator/Page2Component'),
    LazyPage3Component: () => import('@/components/bills/generator/Page3Component'),
    // LazyPage4Component: () => import('@/components/bills/generator/Page4Component'),
    Page4Component,
    // VueEditor
    },
  props: [ 'display', 'data' ],
  data() {
    return {
      has_display: this.display ?? 0,
      maxFinishedStage: 0,
      error: { show: false },
      showModal: { status: false, msg: "" },
      showAddCompany: false,
      paymentMethods:[
          { id: 1, name: 'cash' },
          { id: 2, name: 'card' },
          { id: 3, name: 'transfer' }
        ],

      arrow_styles: {
          '--width': '100%',
          '--height': '40px',
          '--padding-inline-start': '0px'
      },
    }
  },
  async asyncData({store}) {
    await store.dispatch('users/fetchUsersList');
    await store.dispatch('bills/fetchBillsList');
    await store.dispatch('bills/fetchFirmsList');
  },
  destroyed() {
    this.$store.commit("clients/SET_OBJ", { name: "ClientDetail", value: {} });
    this.$store.commit("clients/SET_OBJ", { name: "CompanyDetail", value: {} });
    this.$store.commit('clients/SET_OBJ', {name: 'canChange', value: true})
  },

  computed: {
    firmList(){
      return JSON.parse(JSON.stringify(this.$store.state.bills.firmsList));
    },
    client(){
      return  JSON.parse(JSON.stringify(this.$store.state.clients.ClientDetail));
    },
    eventBill(){
      let bill = JSON.parse(JSON.stringify(this.$store.state.bills.eventBill));
      bill.paymentMethods = this.paymentMethods;
      if (!isNaN(bill.payment_method)) {
        bill.payment_method_name = this.paymentMethods.find(x => x.id == bill.payment_method).name; // replace id with name
      }
      bill.is_pdf_pic_mode = null;
      return bill;
    },
  },

  methods: {
    correct(data, has_display){      
      let firm = this.firmList.filter(company => company.id == data);
      if((firm[0].reg_num == null) || (firm[0].address == null) || (firm[0].email == null) || (firm[0].banks[0].bank_swift == null) || (firm[0].banks[0].bank_name == null) || (firm[0].banks[0].bank_address == null))    
      this.$emit('correctFirm', firm[0]);
      else
      this.changeStage(has_display + 1);
    },
    /**
     * Cancel edit and show bill
     */
    cancelViewGen(){
      this.$store.dispatch("bills/getDataById", this.eventBill.id);
      this.has_display = 4;
    },
    /**
     * Updates price in store
     */
    updatePrice(){
      this.eventBill.price = this.price;
      this.$store.commit('bills/SET_OBJ', {name: 'eventBill', value: this.eventBill})
    },

    /**
     * Updates client in store
     */
    updateClient(){
        this.eventBill.companies = this.$store.state.clients.CompanyDetail
        this.eventBill.clients = this.$store.state.clients.ClientDetail
    },

    /**
     * Changes display to given stage number
     * @param {Number} data - stage to change to
     */
    changeDisplay(data){
      this.has_display = data;
    },

    /**
     * Saves data entered into invoice generator
     */
    saveGen(){
      this.updatePrice();
      this.updateClient();
      this.$store.dispatch("bills/saveGen", this.eventBill).then((value) =>{
        this.$store.commit("bills/SET_OBJ", { name: "eventBill", value: value });
        this.has_display = 4;
        this.updateGen();
      }).catch((err) => { this.$toast.error("Oops:" +err.toString()).goAway(4000); });
    },

    /**
     * Updates data stored on backend with the one entered into invoice generator
     */
   async updateGen(){
      if(this.$store.state.clients.canChange === true) {
        this.updateClient();
      }
      await this.$store.dispatch("bills/updateGen", { "data": this.eventBill, "id": this.eventBill.id })
        .then((value) =>{
          this.$store.commit("bills/SET_OBJ", { name: "eventBill", value: value });
          this.has_display = 4;
        }).catch((err) => { this.$toast.error("Oops:" +err.toString()).goAway(4000);});
    },

    /**
     * Restricts movement further is bill stages if requirements are not met
     * @param {Number} stageToChangeTo
     */
    async changeStage(stageToChangeTo){
      if (stageToChangeTo<=this.maxFinishedStage) {
        this.has_display=stageToChangeTo;
      } else {
        stageToChangeTo=this.maxFinishedStage+1;
        switch (stageToChangeTo) {
          case 1: // 0th stage is not covered as it is allways available
            let firm = this.firmList.filter(company => company.id == this.eventBill.firm_id);
            if((firm[0].reg_num == null) || (firm[0].address == null) || (firm[0].email == null) || (firm[0].banks[0].bank_swift == null) || (firm[0].banks[0].bank_name == null) || (firm[0].banks[0].bank_address == null)){
            this.$emit('correctFirm', firm[0]);
            }
            else  if (this.eventBill.firm_id && this.eventBill.date){
              this.has_display = stageToChangeTo;
              this.maxFinishedStage = 1;
              if (this.eventBill.company_id) {
                this.showAddCompany = true;
              }
            }
            break;
          case 2:
          if (  this.client.lastname.length < 2
              &&  this.client.phone.length < 5
              &&  this.client.email.length < 5){
            this.showModal.status = true;
            this.showModal.msg = this.$t('clients.client_data_not_filled');
            break;
          } else {
            this.updateClient();
          }
            this.has_display = stageToChangeTo;
            this.maxFinishedStage = 2;
            break;
          case 3:
            if (this.isOrderFilled(this.eventBill.orders.length-1)){
              this.has_display = stageToChangeTo;
              this.maxFinishedStage = 3;
            }
            break;
          default:
            break;
        }
      }
    },

    /**
     * Returns true if all required order fields are filled
     *
     * @returns {Boolean}
     */
    isOrderFilled(){
      return !!(this.eventBill.orders[0].name && this.eventBill.orders[0].amount && this.eventBill.orders[0].unit_price)
    },
  },
}

</script>

<style lang="scss" scoped>

.p20{
  padding: 20px;
}
</style>
