<i18n lang="i18n">
{
    "ee": { "creator": "Looja: ",
            "current": "Praegune peamine ettevõte: ",
            "set": "Määratakse peamiseks ettevõtteks: ",
            "are_you_sure_main": "Kas olete kindel, et soovite seda ettevõtet peamiseks ettevõtteks seada?",
            "are_you_sure_delete": "Kas olete kindel, et soovite seda ettevõtet ära kustutada?"},
    "en": { "creator": "Creator: ",
            "current": "Current main company: ",
            "set": "Set the main company: ",
            "are_you_sure_main": "Are you sure you want to set as the main this company?",
            "are_you_sure_delete": "Are you sure you want to delete this company?"},
    "ru": { "creator": "Создатель: ",
            "current": "Текущая основная компания: ",
            "set": "Задайте основную компанию: ",
            "are_you_sure_main": "Вы уверены, что хотите сделать эту компанию основной?",
            "are_you_sure_delete": "Вы уверены, что хотите удалить эту компанию?"}
}
</i18n>
<template>
  <Modal :title="`${$t('bills.set_the_main')}: ${dataItem.company_name}`"
         size="modal-lg" @click="$emit('closeModal', false)">
      <div class="alert alert-light">
        <div v-if="confirmType === 'main'">
          <p>{{ $t('User.company') }}: {{ dataItem.company_name }}</p>
          <p>{{ $t('current') }}{{ dataItem.company_name }}</p>
          <p>{{ $t('set') }}{{ dataItem.company_name }}</p>
          <p class="blue__text">{{ $t('are_you_sure_main') }}</p>
        </div>
        <div v-else-if="confirmType === 'delete'">
          <p>{{ $t('creater') }}{{ dataItem.company_name }}</p>
          <p class="blue__text">{{ $t('are_you_sure_delete') }}</p>
        </div>
      </div>
      <template v-slot:footer>
        <button type="button" class="btn btn-secondary cancel" @click="$emit('closeModal', false)">
          {{ $t('common.cancel')}}
        </button>
        <button type="button" class="set" 
                @click="confirmType === 'delete' ? deleteFirm() : setNewMainFirm()">
          {{ $t(confirmType === 'delete' ? 'common.delete' : 'bills.set_the_main')}}
        </button>
      </template>
  </Modal>
</template>
<script>
import Modal from '../common/Modal.vue'

export default {
  name: 'SetMainFirmModal',
  components: { Modal },
  props: ['dataItem', 'confirmType'],
  data: () => ({}),
  computed: {
    mainCompany() {
      return this.$store.state.bills.firmsList.find(c => c.main_firm == 1);
    },
  },
  methods: {
    deleteFirm() { // TODO: test method for correct behaviour 
      this.$store.dispatch('firms/deleteFirm', this.dataItem.id).then(resp => {
        this.$emit('closeModal', false);
        this.$toast.success(resp.msg).goAway(3000);
        this.$store.dispatch('bills/fetchFirmsList');
      },
      error => {
        this.$toast.error(error.msg).goAway(3000)
      });
    },
    setNewMainFirm() {
      let firm_index = this.$store.state.bills.firmsList.map(firm => firm.id).indexOf(this.dataItem.id);
      this.$store.commit("bills/SET_OBJ", { name: `firmsList[${firm_index}].main_firm`, value: 1 })
      this.$store.dispatch('firms/updateFirm', { id: this.dataItem.id, data: this.dataItem }).then(resp => {
        this.$emit('closeModal', false);
        this.$toast.success(resp.msg).goAway(3000);
        this.$store.dispatch('bills/fetchFirmsList');
      },
      error => {
        this.$toast.error(error.msg).goAway(3000)
      });
    }
  }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/var";
.alert-light{
  font-size: 15px;
  line-height: 2;
  font-weight: 500;
  min-height: 400px;
  margin: 0px 50px 0px 50px;
}
.set{
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: 10px;
  background-color: $logo-blue;
  color: #ffffff;
  border-radius: 5px;
  border: 1px solid $logo-blue;
  height: 50px;
  text-align: center;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  &:hover{
    background-color: #ffffff;
    color: $logo-blue;
   }
}
.cancel{
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: 10px;
  color: #ffffff;
  border: 1px solid $secondary-color;
  border-radius: 5px;
  height: 50px;
  text-align: center;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  &:hover{
    background-color: #ffffff;
    color: $secondary-color;
   }
}
.blue__text{
  color: $logo-blue;
}
.red__text{
  color: red;
}

</style>
