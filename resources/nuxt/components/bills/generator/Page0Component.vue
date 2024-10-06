<template>
  <div class="row p20">
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="form-row">
        <div class="col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
          <div class="row">
            <div class="col-10">
              <label for="select_firm">{{ $t("bills.firm")}}<sup class="red">*</sup></label>
              <select name="select_firm"
                id="select_firm"
                class="form-control"
                :class="{ 'is-invalid': !eventBill.firm_id }"
                v-model="eventBill.firm_id"
                @change="dateChange = false; addItem()"
              >
                <option v-for="firm in firmList"
                        :key="firm.id"
                        :value="firm.id">
                  {{ firm.name }}
                </option>
              </select>
            </div>
            <div class="col-2 pt-2">
              <button
                class="btn-action mt-4 add__firm"
                :title="$t('bills.add_new_firm')"
                @click="$emit('addNewFirm')"
                :class="{
                  'btn-action': isElementInteractible('add-new-firm'),
                  'zeroOpacity': !isElementInteractible('add-new-firm')
                  }"
                :disabled="!isElementInteractible('add-new-firm')">
                <i class="fas fa-plus p-2"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
          <label for="prepaid">{{ $t('bills.type') }}<sup class="red">*</sup></label>
          <select v-model="eventBill.prepaid"
                  class="form-control"
                  @change="updateBill($event)"
                  id="prepaid">
            <option v-for="i in invoice_type"
                    :value="i.id"
                    :key="i.id">
              {{ $t('bills.' +i.name) }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="form-row">
        <div class="col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
          <label for="startdate">{{ $t("bills.start_date")}}<sup class="red">*</sup></label>
          <input type="date"
                 id="startdate"
                 class="form-control"
                 :class="{ 'is-invalid': !eventBill.date }"
                 v-model="eventBill.date"
                 @change="dateChange = true; updateBill($event); addItem()"
                 required/>
        </div>
      </div>
      <div class="form-row">
        <div class="col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
          <label for="deadline">{{ $t("bills.deadline") }}</label>
          <input type="date"
                 class="form-control"
                 id="deadline"
                 v-model="eventBill.deadline"
                 @change="updateBill($event)"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import isElementInteractible from '../../../mixins/isElementInteractible';
export default {
  name: "Page0Component",
  props: [ "eventBill" ],
  mixins: [ isElementInteractible ],
  data() {
    return {
      firm: {},
      filterBills: {},
      dateChange: false
    }
  },

  mounted() {
    if (this.firmList.length === 1) {
      this.$store.commit('bills/SET_OBJ', { name: 'eventBill', key: 'firm_id', value: this.firmList[0].id })
    }
  },

  computed: {
    firmList() {
      return JSON.parse(JSON.stringify(this.$store.state.bills.firmsList));
    },
    invoice_type(){
      return JSON.parse(JSON.stringify(this.$store.state.bills.invoice_type));
    },
  },

  methods: {
    addItem(){
      if (!this.eventBill.date) {
        this.eventBill.date = new Date().toISOString().slice(0, 10);
      }
      if (this.$store.state.bills.filterBills.month === null) {
          this.filterBills.month = new Date().getMonth() + 1;
      } else { this.filterBills.month = this.$store.state.bills.filterBills.month }
      if (this.$store.state.bills.filterBills.year === null) {
        this.filterBills.year = new Date().getFullYear();
      } else { this.filterBills.year = this.$store.state.bills.filterBills.year }
      this.$store.dispatch('bills/getUniqNumber', {
        month: this.filterBills.month,
        year: this.filterBills.year,
        firm: this.eventBill.firm_id
        }).then(
        resp => {
            if (resp['number']) {
              this.eventBill.MaxNumberBill = Number(resp['number']);
            } else {
              this.eventBill.MaxNumberBill = 1;
            }
          this.eventBill.number = this.eventBill.MaxNumberBill;
          this.commitValuesToStore();
        })
    },

    /**
     * Updates bill data in store given data object
     * @param {Object} data - data object to store
     * @param {Number} data.target.id - id of object to store
     * @param {Object} data.target.value - value of object to store
     */
    updateBill(data){
      this.eventBill[data.target.id]= data.target.value;
      this.commitValuesToStore();
    },


    /**
     * Stores bill values in store
     */
    commitValuesToStore() {
      this.$store.commit('bills/SET_OBJ', {name: 'eventBill', value: this.eventBill})
    },
  }

}
</script>

<style scoped>

</style>
