<template>
<div>
  <Modal :title="title" size="modal-lg"
         @closeModal="closeCategoryModal()"
         v-if="isCategoryModalVisible">
    <div class="modal-body p-2 m-0">
      <label for="company">Name:</label>
      <input type="text"  class="form-control" v-model="category.name" id="company"  >

      <label for="desc">Description:</label>
      <textarea  id="desc" class="form-control" v-model="category.desc" cols="10" rows="2">
      </textarea>
    </div>
    <template v-slot:footer>

      <div class="btn btn-primary" @click="closeCategoryModal">
        Cancel
      </div>

      <div v-if="category.id" class="btn btn-danger float-right" @click="deleteCategory" >
        Delete
      </div>

      <div v-if="category.id" class="btn btn-success float-right" @click="update">
        Update
      </div>
      <div v-else class="btn btn-success float-right" @click="create">
        Create
      </div>

    </template>
  </Modal>


</div>
</template>

<script>
import Modal from "@/components/common/Modal";
export default {
  name: "CategoryModalComponent",
  props: ["category" , "isCategoryModalVisible", "title"],
  components: { Modal },
  methods:{
    async create(){
      console.log(this.category);
      console.log("here");
      await this.$store.dispatch('orders/createCategory' , this.category)
    },
    async update(){
      console.log(this.category);
      console.log("here");
      await this.$store.dispatch('orders/updateCategory' , this.category)
    },
    async deleteCategory(){
      console.log(this.category);
      console.log("here");
      await this.$store.dispatch('orders/deleteCategory' , this.category.id)
    },
    closeCategoryModal(){
      this.$emit('closeCategoryModal', false);
    },
  }

}
</script>

<style scoped>

</style>
