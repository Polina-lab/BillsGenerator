<template>
  <div :key="bank_data_key">
    <div class="p-1 m-2" v-for="(requisite, idx) in localBanks" :key="idx">
      <div class="row" id="bank__row">
        <div class="col-12 col-sm-6">
          <label for="bank_name">{{ $t("bills.bank_name") }}<sup class="red">*</sup></label>
          <input  :class="{ 'is-invalid': (isCorrect && !requisite.bank_name) }" type="text" id="bank_name" class="form-control" minlength="3" @change="$emit('checkData')" v-model="requisite.bank_name"/>
          <label for="bank_address">{{ $t("bills.bank_address") }}</label>
          <input type="text" :class="{ 'is-invalid': (isCorrect  && !requisite.bank_address)}" id="bank_address" class="form-control" v-model="requisite.bank_address"/>
        </div>
        <div class="col-12 col-sm-6">
          <label for="bank_num" >{{ $t("bills.bank_num") }}<sup class="red">*</sup></label>
          <input type="text" id="bank_num" class="form-control" minlength="3" v-model="requisite.bank_num"/>
          <label for="bank_swift">{{ $t("bills.bank_swift") }}</label>
          <input type="text" :class="{ 'is-invalid': (isCorrect && !requisite.bank_swift) }" id="bank_swift" class="form-control" v-model="requisite.bank_swift"/>
        </div>
        <div class="col-12">
          <button v-if="localBanks.length > 1"
                  class="btn btn-outline-danger mt-4 float-right"
                  @click="deleteBankRequisites(requisite.id, idx)">
                {{ $t("bills.delete_requisites") }}
          </button>
        </div>
      </div>
    </div>
    <label for="add_bank_requisites">{{ $t('bills.add_bank_requisites') }}</label>
    <i id="add_bank_requisites" class="fa fa-plus btn-action" @click="addBankRequisites()">
    </i>
  </div>
</template>

<script>
import { Bank } from '../../../assets/js/models/Bill';
export default {
  name: "printBankRequisite",
  props: ['firm_id', 'banks', "isCorrect"],
  data: () => ({
    bank_data_key: 0
  }),
  computed: {
    firmIndex() {
      return this.$store.state.bills.firmsList.map(firm => firm.id).indexOf(this.firm_id);
    },
    localBanks() { // wraps two sources of banks to handle banks that are not saved in store
      if (this.firm_id === undefined) {
        return this.banks;
      } else {
        return this.$store.state.bills.firmsList[this.firmIndex].banks;
      }
    },
  },
  methods:{
    addBankRequisites() {
      if (this.firm_id === undefined) {
        if (this.localBanks.length === 0) {
          this.$set(this.localBanks, 0, {});
        } else if (this.isRequisitesFilled()) {
          this.$set(this.localBanks, this.localBanks.length, {});
        }
      } else {
        if (this.localBanks.length === 0 || this.isRequisitesFilled()) {
          this.$store.commit('bills/ADD_ITEM_ARRAY_BOTTOM',
            { name: `firmsList[${this.firmIndex}].banks`, value: new Bank() });
        }
      }
      this.bank_data_key += 1;
    },

    /**
     * Deletes firm requisits given requisites id
     * @param {Number} id id of requisite to delete
     */
    async deleteBankRequisites(id, idx) {
      if (this.firm_id === undefined) {
        if (typeof(id) == 'undefined') {
          this.localBanks.pop() // deletes local data only that was not sent tot server
          this.bank_data_key += 1;
          return;
        }
      } else {
        if (typeof(id) == 'undefined') {
          this.$store.commit('bills/DELETE_ITEM_ARRAY',
            { name: `firmsList[${this.firmIndex}].banks`, value: idx,
              elementReference: 'idx' }); // deletes local data only that was not sent to server
          this.bank_data_key += 1;
          return;
        }
      }
      await this.$store.dispatch("bills/deleteRequisites", id).then((response) => {
        if(response.code === 200){
          this.$store.dispatch('bills/fetchFirmsList');
          this.bank_data_key += 1;
        }
      }).catch((error) => {
        this.$toast.error(error.msg).goAway(3000);
      });
    },

    /**
     * Evaluates if all required fields of the last requisite are filled
     * @returns {Boolean}
     */
    isRequisitesFilled() {
      let last_index = this.localBanks.length - 1;
      return !!(this.localBanks[last_index].bank_name &&
        this.localBanks[last_index].bank_address &&
        this.localBanks[last_index].bank_swift &&
        this.localBanks[last_index].bank_num);
    },
  }

}
</script>

<style scoped>
.is-invalid{
    border: 1px solid red;
  }
</style>
