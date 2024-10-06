<template>
  <div>
  <Modal :title="$t('bills.email_form')"
         @closeModal="closeModal" size="modal-lg"
         v-if="isActive">
        <div class="col-12 p-2">
          <h3 class="text-center">{{ $t('bills.letter') }}</h3>
          <label for="email">{{ $t('bills.email')}}</label>
          <input type="email" id="email" class="form-control" v-model="data.sender_mail" @change="closeModal"/>

          <label for="letter">{{ $t('bills.write_letter')}}</label>
          <vue-editor style="width:100%;"
                      v-model="letter"
                      :editor-toolbar="customToolbar"
                      id="letter">
          </vue-editor>

          <input ref="uploadForm"
                 type="file"
                 multiple
                 hidden
                 accept=".jpg, .jpeg, .png, .doc, .docx, .xls, .xlsx, .pdf"
                 @change="uploadFiles">
          <div class="d-flex p-2 justify-content-end">
            <div>
              <i class="fa fa-plus p-2 f2 fa-2x pointer" @click.prevent="$refs.uploadForm.click()" :title="$t('bills.add_new')"></i>
            </div>
              <div v-for="(file, idx) in filesList" :key="idx"  >
                <span class="fa-stack p-2">
                  <i class="fa fa-file fa-2x fa-stack-2x" :title="file.name"></i>
                  <i class="fa fa-trash fa-stack-1x red_delete_icon" @click="deleteFileFromList(idx)" :title="$t('common.delete')"></i>
                </span>
              </div>
            <div>
              <i class="fa fa-file-pdf p-2 fa-2x pointer" :title="$t('file.download')" @click="generatePDF"></i>
            </div>
          </div>


        </div>
        <template v-slot:footer>
        <button type="button"
                class="btn btn-outline-primary"
                @click="closeModal">
          {{ $t('common.cancel')}}
        </button>

        <button type="button"
                class="btn btn-primary"
                @click="sendEmail()">
          {{ $t('bills.send')}}
        </button>
      </template>

  </Modal>
  </div>
</template>

<script>
import Modal from "@/components/common/Modal";

let VueEditor;
if (process.client) {
  VueEditor = require('vue2-editor').VueEditor;
}


export default {
  name: "SendEmailComponent",
  components: { Modal, VueEditor },
  props: ['data', 'isActive'],

  data(){
    return {
      letter: '',
      file : null,
      filesList: [],
      customToolbar: [
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
      ],
    }
  },
 methods:{

    closeModal(){
      this.$emit('setInActive', false);
      this.filesList = [];
      this.letter = '';
      this.file = null;
    },


    uploadFiles(event) {
       event.target.files.forEach(image => {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.filesList.push({ name: image.name, type: image.type, data: reader.result });
        };
        reader.readAsDataURL(image);
      });
    },
    deleteFileFromList(idx) {
      this.filesList.splice(idx, 1);
    },
    sendEmail(){
      this.$emit('sendMail', { firm_id: this.data.firms.id, invoice: this.data.invoice, 
                               mail_attachment: { attachment: this.filesList, text: this.letter } });
      this.closeModal();
    },

    generatePDF(){
      this.$emit('generatePdf', { firm_id: this.data.firms.id, invoice: this.data.invoice, has_filename: true });
    }

  }


}
</script>

<style scoped>
.f2{
  font-size: 2rem;
}
.red_delete_icon{
  color: red;
  margin-left: 10px;
  margin-top: 10px;
  cursor: pointer;
}





</style>
