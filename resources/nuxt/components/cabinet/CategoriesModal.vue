<template>
  <div>
    <Modal :title="$t(`common.${category.id ? 'edit' : 'create'}`)"
           @closeModal="$emit('closeModal')"
           size="modal-lg"
           v-if="isModalVisible">
      <div v-if="category" class="modal-body p-2 m-0">
        <label for="company">{{ $t("User.name") }}:</label>
        <input id="company" type="text" class="form-control" v-model="category.name">
      </div>
      <template v-slot:footer>
        <button type="button"
                class="btn btn-secondary"
                @click="$emit('closeModal')">
                {{ $t("common.cancel") }}
        </button>
        <button v-if="category.id" type="button" class="btn btn-outline-danger"
                @click="isConfirmationModalVisible = true;">{{ $t('common.delete') }}
        </button>
        <button type="button" class="btn btn-success"
                @click="createCategory(); $emit('closeModal')">
          {{ $t(`common.${category.id ? 'save' : 'create'}`) }}
        </button>
      </template>
    </Modal>
    <Modal :title="$t('common.delete')"
           @closeModal="isConfirmationModalVisible = $event;"
           v-if="isConfirmationModalVisible">
      <div class="alert alert-light">
        <p class="text-danger">{{ $t('bills.deleteConfirmation') }}</p>
      </div>
      <template v-slot:footer>
        <button type="button"
                class="btn btn-secondary"
                @click="isConfirmationModalVisible = false">
                {{ $t("common.cancel")}}
        </button>
        <button type="button" class="btn btn-danger"
          @click="deleteCategory(); isConfirmationModalVisible = false; $emit('closeModal')">{{ $t("common.delete") }}
        </button>
      </template>
    </Modal>
  </div>
</template>

<script>
import Modal from "../common/Modal.vue";

export default {
  name: 'CategoriesModal',
  props: [ 'isModalVisible', 'category'],
  components: { Modal },
  data: () => ({
    isConfirmationModalVisible: false,
  }),
  computed: {},
  methods: {
    async createCategory() {
      //TODO: need validation for name
      if(this.category.name.length > 3) {
        await this.$store.dispatch('orders/createCategory', this.category)
        await this.reloadCategoriesList();
      }else
        this.$toast.error("Oops  ");
    },

    async deleteCategory() {
       await this.$store.dispatch('orders/deleteCategory', this.category.id);
       this.reloadCategoriesList();
    },
    reloadCategoriesList() {
      this.$emit('reloadCategoriesList' , this.category.name);
    }
  }
}
</script>

<style>

</style>
