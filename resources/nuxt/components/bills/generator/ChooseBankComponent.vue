<template>
  <div>
    <Modal :title="$t('bills.choose_bank')" :active="active"  @closeModal="closeModal" >
      <div class="container pb-4">
        <div class="form-row">
            <label for="bank_id">{{ $t('bills.choose_bank')}}</label>
            <select name="bank_id" id="bank_id" class="form-control col-10" v-model="selected_bank"  >
              <option v-for="(bank ,  i) in firms.banks" :key="i" :value="bank.id">{{ bank.bank_name }}</option>
            </select>
          <div class="col-2 justify-content-end">
             <div class="btn btn-action " @click="editRequisites = !editRequisites;">
                  <i class="fa fa-edit" :title="$t('bills.edit_requisites')"></i>
              </div>
          </div>
        </div>
        <printBankRequisite v-if="editRequisites" class="p-3" :banks="firms.banks" @addBankRequisites="addBankRequisites" @deleteBankRequisites="deleteBankRequisites" ></printBankRequisite>

      </div><!--container-->
      <template v-slot:footer>
        <button type="button" class="btn btn-primary" @click="closeModal">{{ $t('common.cancel')}}</button>
        <button type="button" class="btn btn-success" @click="update">{{ $t('common.ok')}}</button>
      </template>
    </Modal>
  </div>
</template>

<script>
import Modal from "@/components/common/Modal";
import printBankRequisite from "@/components/bills/common/printBankRequisite";
export default {
  name: "ChooseBankComponent",
  components:{Modal , printBankRequisite},
  props:['active'],
  data(){
    return{
      editRequisites: false,
    }
  },
  computed:{
    firms(){
      return JSON.parse(JSON.stringify(this.$store.state.bills.eventBill.firms));
    },
    selected_bank:{
      get() {
        return this.$store.state.bills.eventBill.bank_id;
      },
      set(value){
        this.$store.commit("bills/SET_OBJ" , { name : 'eventBill' , key: 'bank_id' , value:  value })
      }
    }
  },
  methods:{
    closeModal(){
      this.$store.commit("bills/SET_OBJ",{ name:'chooseBankModal', value: false } )
    },

    changeRequisites(data){
      this.$store.commit("bills/SET_OBJ" , { name : 'eventBill' , key: 'bank_id' , value:  data.target.value })
    },


    addBankRequisites(){
      if (this.firms.banks.length === 0) {
        this.$set(this.firms.banks, 0, {});
      } else{
        this.$set(this.firms.banks, this.firms.banks.length, {});
      }
    },

    /**
     * Deletes firm requisits given requisites id
     * @param {Number} id id of requisite to delete
     */
    async deleteBankRequisites(id, idx){
      if (typeof(id) == 'undefined') {
        this.firms.banks.pop()
        return;
      }
      await this.$store.dispatch("bills/deleteRequisites", id).then((response) => {
        this.$delete(this.firm.banks, idx);
      }).catch((error) => {
        this.$toast.error(error.msg).goAway(3000);
      });
    },

    async update(){
      await this.$store.dispatch('bills/updateGen' , { data :this.$store.state.bills.eventBill , id : this.$store.state.bills.eventBill.id }).then(
        (resp) => {
           this.$toast.success(resp.msg).goAway(3000);
           this.closeModal();
        }).catch((err) => {
        this.$toast.error(err.msg).goAway(3000);
      });
    },

  }
}
</script>

<style scoped>

</style>
