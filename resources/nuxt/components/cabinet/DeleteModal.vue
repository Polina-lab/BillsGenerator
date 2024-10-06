<i18n lang="i18n">
{
    "ee": {"creater": "Looja: ",
            "are_you": "Kas olete kindel, et soovite selle ettevõtte kustutada?"},
    "en": {"creater": "Creater: ",
            "are_you": "Are you shure you want to delete this company?"},
    "ru": {"creater": "Создатель: ",
            "are_you": "Вы уверены, что хотите удалить эту компанию?"}
}
</i18n>
<template>
  <Modal :title="`${$t('User.delete_secondary')}: ${dataItem.company_name}`" 
         size="modal-lg" @click="$emit('closeModal', false)">
      <div class="alert alert-light">
        <p>{{ $t('User.company') }}: {{ mainCompany.company_name }}</p>
        <p>{{ $t('creater') }}{{ dataItem.company_name }}</p>
        <p class="red__text">{{ $t('are_you') }}</p>
      </div>
      <template v-slot:footer>
        <button type="button" class="btn btn-secondary cancel" @click="$emit('closeModal', false)">
          {{ $t('common.cancel')}}
        </button>
        <button type="button" class="set" @click="$emit('closeModal', false)">
          {{ $t('common.delete')}}
        </button>
      </template>
  </Modal>
</template>
<script>
import Modal from '../common/Modal.vue'

export default {
  name: 'SetMainFirmModal',
  components: { Modal },
  props: ['dataItem'],
  data: () => ({}),
  computed: {
    mainCompany() {
      return this.$store.state.bills.firmsList.find(c => c.main_firm == 1);
    },
  },
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
  background-color: red;
  color: #ffffff;
  border-radius: 5px;
  border: 1px solid red;
  height: 50px;
  text-align: center;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  &:hover{
    background-color: #ffffff;
    color: red;
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
.red__text{
    color: red;
}

</style>