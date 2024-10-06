<template>
    <Modal :title="$t('User.change_logo') + ': ' + mainCompany.name"
        size="modal-lg"
        @closeModal="$emit('closeModal', false); $router.push('/admin/users');">
        <div class="alert alert-light">
            <div class="col-12 pt-3">
                <div id="dragndrop_area"
                     @dragover="dragover($event)"
                     @dragleave="dragleave($event)"
                     @drop="drop($event)">
                <div style="border: dashed 1px dimgrey; background-color: #f3f3f3 ">
                <input ref="uploadForm"
                     type="file"
                     hidden
                     accept="image/*"
                     @change="uploadLogo">
                  <div v-if="user.image">
                    <img :src="mainCompany.logo.length < 100 ? serverUrl + mainCompany.logo : mainCompany.logo" alt="mainCompany.logo" width="100%">
                    </div>
                  <div v-else class="text-center p-5 fa__box">
                    <i class="fa fa-image" style="font-size:4em; color:#c1c1c1;"></i>
                    </div>
                </div>
                <div class="row upload__box">
                    <div class="col-12 cloud__box">
                        <div class="d-flex pt-3">
                          <button class="col-12 btn btn-success" @click.prevent="$refs.uploadForm. click()">
                            <i class="fa fa-cloud-upload-alt"></i>
                            {{ $t("User.upload_photo")}}
                          </button>
                        </div>
                    </div>
                    <div class="col-12 trash__box">
                        <div class="d-flex pt-3">
                          <button class="col-12 btn btn-danger" @click="deleteLogo">
                            <i class="fa fa-trash-alt"></i>
                            {{ $t("User.delete_photo")}}
                          </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <template v-slot:footer>
            <button type="button"
             class="btn btn-secondary cancel"
              @click="$emit('closeModal', false);">
              {{ $t("bills.cancel")}}
        </button>
        <button type="button"
              class="set__logo"
              @click="$emit('closeModal', false);">
              {{ $t("bills.change_simple")}}
        </button>
        </template>
    </Modal>
</template>
<script>
import Modal from '../common/Modal.vue'
import dragNdropArea from '../../mixins/dragNdropArea';

export default {
  name: 'SetLogoModal',
  components: { Modal },
  mixins: [ dragNdropArea ],
  props: ['isSetLogoModalVisible'],
  data: () => ({}),
    computed: {
    user() {
      return this.$store.getters['users/getUserDetail'];
    },
    companies(){
      return this.$store.state.bills.firmsList;
    },
    addCompanies(){
      const index = this.$store.state.bills.firmsList.map((o) => o.main_firm).indexOf(1);
        if (index > -1) {
            return this.$store.state.bills.firmsList.splice(index, 1);
        }else{
          return this.$store.state.bills.firmsList;
        }
    },
    mainCompany(){
      return this.$store.state.bills.firmsList[0];
      //return this.$store.state.bills.firmsList[this.$store.state.bills.firmsList.map((o) => o.main_firm).indexOf(1)];
    },
  },
  methods:{
    uploadLogo(event){
      const images = event.target.files[0];
      this.user.image = null;
      let reader = new FileReader();
      reader.onload = (e) => {
        this.user.image = reader.result;
      };
      reader.readAsDataURL(images);
    },

    /**
     * Wraps local file upload functionality
     * @param {Object[]} files list of files to interact handle
     */
    fileUploadHandler(files){
      this.uploadLogo({ target: { files: files } });
    },

    /**
     * Removes locally stored logo data
     */
    deleteLogo(){
      this.user.image = null;
    },
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
  margin: 10px 70px 20px 70px;
}
.set__logo{
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: 10px;
  border-radius: 5px;
  color: #ffffff;
  border: 1px solid $secondary-color;
  background-color: $secondary-color;
  height: 40px;
  width: 120px;
  text-align: center;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  &:hover{
    background-color: #e6e0e0;
    color: $secondary-color;
   }
}
.cancel{
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: 10px;
  color: $secondary-color;
  border: 1px solid $secondary-color;
  background-color: #ffffff;
  border-radius: 5px;
  height: 40px;
  width: 120px;
  text-align: center;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  &:hover{
    background-color: #e6e0e0;
    color: $secondary-color;
   }
}
.blue__text{
    color: $logo-blue;
}
.fa__box{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px;
}
.upload__box{
    display: flex;
    flex-direction: column;
}
</style>
