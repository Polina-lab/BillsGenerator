<template>
  <section>
    <div class="card p-1 m-2 border-left-0 border-right-0 border-top-0" v-for="(b, idx) in eventBill.orders" :key="idx">
      <div class="card-body">
            <label for="name">{{ $t("bills.bill_desc") }}</label>
            <input class="form-control col-12"
                      :class="{ 'is-invalid': !b.name }"
                      id="name"
                      v-model="b.name"
                      @change="updateOrder($event, idx)">
          <div class="form-row">
              <div class="col-12 col-sm-9 col-md-9 col-lg-10">
              <label for="desc">{{ $t("bills.addition_info") }}</label>
              <vue-editor :style="{  'height':  height +'px' }"
                          v-model="b.desc" class="desc"
                          @input="updateOrder($event , idx)"
                          :editor-toolbar="customToolbar"
                          id="desc">
              </vue-editor>

                <button v-if="eventBill.orders.length>1"
                        class="btn btn-danger pointer d-none d-sm-block" style="margin-top: 60px;cursor:pointer;"
                        @click="deleteOrder(idx)">
                  {{ $t("bills.delete_gen") }}
                </button>

              </div>
              <div class="col-12 col-sm-3 col-md-3 col-lg-2 "  >
                <label for="unit_price">{{ $t("bills.piece_price") }}<sup style="color: red;">*</sup></label>
                <input type="text"
                       maxlength="8"
                       class="form-control"
                       inputmode="decimal"
                       :class="{ 'is-invalid': ((!b.unit_price) || (b.unit_price >= 100000000)) }"
                       id="unit_price"
                       v-model="b.unit_price"
                       @input="updateOrder($event, idx);"/>

                  <label for="amount">{{ $t("bills.amount")}}<sup style="color: red;">*</sup></label>
                  <input type="text"
                         class="form-control"
                         :class="{'is-invalid': !b.amount}"
                         id="amount"
                         v-model="b.amount"
                         @input="updateOrder($event, idx);"
                  />


                  <label for="unit">{{ $t("bills.unit")}}<sup style="color: red;">*</sup></label>
                  <select id="unit"
                          v-model="b.unit"
                          class="form-control"
                          @input="updateOrder($event, idx)"
                          required
                  >
                    <option v-for="u in unit" :key="u.id" :value="u.name">{{ u.name }}</option>
                  </select>
                  <div class="mt-2" v-if="b.unit == 'Another' || (b.unit && !(unit.map(x => x.name).includes(b.unit)))">
                    <input type="text" class="form-control" id="unit_name" @change="updateBill($event)" v-model="b.unit"/>
                  </div>
              </div>
          </div>
            </div>
          </div>
    <div class="row p-1 m-2 ">
      <div class="col-sm-6 col-md-6 col-lg-6 mt-3 pb-5">
        <button @click="addOrder()" class="btn btn-outline-secondary  " >
          <i  class="fa fa-plus "></i>
          {{ $t("bills.lisa_uus_arve")}}
        </button>
        <div style="position: absolute; bottom: 0px;">
        <span @click="showMore = !showMore">More info <i class="fa" :class="{'fa-chevron-up' : showMore  , 'fa-chevron-down': !showMore}"></i></span>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="row">
          <div class="offset-6 col-6" v-if="currentVatData && currentVatData.vat > 0" >
            <label for="total_withoutKM"><span >{{ $t("bills.total_withoutKM") }}</span></label>
            <input type="number" id="total_withoutKM" class="form-control " v-model="billPrice" disabled/>
          </div>
        </div>

        <div v-if="currentFirm && currentFirm.vat" class="row">
          <div class="col-6">
             <label for="vat_id">{{ $t("bills.KM")}}</label>
             <select type="number" id="vat_id" class="form-control" v-model="eventBill.vat_id" @change="changeVat($event) " >
               <option v-for="val , index in currentFirm.vat" :key="index"  :value="val.id" >
                   {{ val.vat + " %" }}
               </option>
            </select>
          </div><!--col-6-->
          <div class="col-6">
              <label for="total">{{ $t("bills.total")}}</label>
              <input type="number" id="total" class="form-control" v-model="totalPrice" disabled/>
          </div>
          </div>
      </div>
    </div><!--row-->
    <div class="row p-1 m-2" v-if="showMore">
        <div class="col-3">
          <label for="currency">{{ $t('bills.currency')}}<sup style="color: red;">*</sup></label>
          <select id="currency"
                  v-model="eventBill.currency"
                  class="form-control "
                  @change="updateBill($event)">
            <option v-for="c in currency"
                    :key="c.id"
                    :value="c.name">
              {{ c.name  }}
              <span v-if="c.icon !== null">
              ( {{ c.icon }} )
            </span>
            </option>
          </select>

        </div>
          <div class="col-3 " v-if="eventBill.currency == 'Another' || eventBill.currency &&
           !(currency.map(x => x.name).includes(eventBill.currency))">
            <label>Another</label>
            <input type="text"
               class="form-control"
               v-model="eventBill.currency"
               @change="updateBill($event)"
            />
          </div>
      <div v-else class="col-3"></div>

        <div class="col-3">
          <label for="payment_method">{{ $t("bills.payment_method")}}</label>
          <select id="payment_method"
                  v-model="eventBill.payment_method"
                  class="form-control"
                  @change="updateBill($event)">
            <option v-for="method in eventBill.paymentMethods"
                    :key="method.id"
                    :value="method.id">
              {{ $t(`bills.${method.name}`)}}
            </option>
          </select>
        </div>

        <div class="col-3">
          <label for="penalty">{{ $t("bills.penalty")}}<sup style="color: red;">*</sup></label>
          <input type="number"
                 class="form-control "
                 :class="{
                   'is-invalid': !(!!(eventBill.penalty>=0)&&!!(eventBill.penalty<=100))
                   }"
                 id="penalty"
                 placeholder="0.5"
                 v-model="eventBill.penalty"
                 @change="updateBill($event)"
          />
        </div>
      </div>
  </section>
</template>

<script>
import formatNumberInput from "../../../mixins/formatNumberInput";
import {VAT} from "../../../assets/js/models/Bill";

let VueEditor;
if (process.client) {
  VueEditor = require('vue2-editor').VueEditor;
}

export default {
  name: "Page2Component",
  components: { VueEditor },
  mixins: [ formatNumberInput ],
  props: [ "eventBill" ],
  data() {
    return {
      showMore:false,
      currentVatData: {},
      customToolbar: [
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
      ],
    }
  },
  beforeMount() {
    if (this.currentFirm && this.currentFirm.vat && this.eventBill.vat_id) {
      this.currentVatData = this.currentFirm.vat.find(v => v.id === this.eventBill.vat_id);
    } else {
      this.currentVatData = this.currentFirm.vat.find(v => v.default === true);
    }
  },
  computed: {
    firmList() {
      return JSON.parse(JSON.stringify(this.$store.state.bills.firmsList));
    },
    unit(){
      return JSON.parse(JSON.stringify(this.$store.state.bills.unit));
    },
    currency(){
      return JSON.parse(JSON.stringify(this.$store.state.bills.currency));
    },
    billPrice(){
      var sum = 0;
      this.eventBill.orders.map(function (order) {
        sum += Number(order.unit_price) * Number(order.amount);
      });
      return sum.toFixed(2);
    },
    currentFirm(){
      if(this.eventBill.firm_id) {
        return this.firmList.find(x => x.id === this.eventBill.firm_id)
      }
    },
    kmAmount(){
       let kmPercent = this.currentVatData ? this.currentVatData.vat : 0;
      return +this.billPrice * kmPercent / 100;
    },

    totalPrice(){
      return (+this.billPrice + this.kmAmount).toFixed(2);
    },
    height(){
        var res = 130;
        if(this.eventBill.orders.length>1){
          res = 90;
        }
      return res;

    }

  },

  created(){
    if(this.currentFirm){
        this.eventBill['vat_id'] = this.currentFirm.vat_default.id;
        this.commitValuesToStore();
    }
  },

  methods: {
    /**
     * Adds a new order to list and initializes it with default data
     */
    addOrder(){
      if (this.isOrderFilled()) {
        let newOrder = {
          unit_price: 0,
          amount: 1,
          name: "",
          unit: "Object",
          desc: '',
        };
        this.$store.commit('bills/ADD_ORDERS', newOrder)
      }
    },

    /**
     * Updates order data in store given data object
     * @param {Object} data - data object to store
     * @param {Number} data.target.id - id of object to store
     * @param {Object} data.target.value - value of object to store
     * @param {Number} idx - incremental id of order
     */
    updateOrder(data, idx){
      let arrayOfFieldsToCheck = ['unit_price', 'amount'];

      if (!data.target) {
        let temp = data;
        data = { target: {} }
        data.target.id = 'desk';
        data.target.value = temp;
      }

      if (arrayOfFieldsToCheck.includes(data.target.id)) {
        data.target.value = this.formatNumberInput(data.target.value);
      }
      this.eventBill.orders[idx][data.target.id] = data.target.value;
      this.commitValuesToStore();
    },

    /**
     * Deletes order from list with given id
     * @param {Number} id - id of order to delete
     */
    deleteOrder(id){
      if (this.eventBill.orders[id]) {
        this.$delete(this.eventBill.orders, id);
        this.commitValuesToStore();
      }
    },

    /**
     * Returns true if all required order fields are filled
     *
     * @returns {Boolean}
     */
    isOrderFilled(){
      return !!(this.eventBill.orders[0].name && this.eventBill.orders[0].amount && this.eventBill.orders[0].unit_price);
    },

    /**
     * Stores bill values in store
     */
    commitValuesToStore() {
      this.$store.commit('bills/SET_OBJ', {name: 'eventBill', value: this.eventBill})
    },

    /**
     * Save checkbox value to store
     * @param {Object} data $event object to pass
     */
    saveChechboxValue(data){
      this.eventBill[data.target.id] = data.target.checked ? 1 : 0;
      this.commitValuesToStore();
    },


    changeVat(data){
      this.currentVatData = (this.currentFirm.vat.find(v => v.id == data.target.value));
      this.updateBill(data);
    },


    /**
     * Updates bill data in store given data object
     * @param {Object} data - data object to store
     * @param {Number} data.target.id - id of object to store
     * @param {Object} data.target.value - value of object to stosre
     */
    updateBill(data){
      let arrayOfFieldsToCheck = ['penalty'];
      if (arrayOfFieldsToCheck.includes(data.target.id)) {
        data.target.value = this.formatNumberInput(data.target.value);
      }
      this.eventBill[data.target.id] = data.target.value;
      this.commitValuesToStore();
    }
  }
}
</script>

<style scoped>

.desc >>> .ql-editor {
  min-height: 100px;
  font-size: 16px;
}

</style>
