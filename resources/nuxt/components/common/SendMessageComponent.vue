<template>
  <div>
       <Modal :title="$t('User.send_msg')"
           :active="showSendMessageModal"
           @closeModal="closeSendMessageModal()"
           v-if="showSendMessageModal">
      <div  class="p10" > 
        <span v-for="(s, idx) in selected" :key="idx">
          {{ showUsername({s, users}) }}<span v-if="idx+1 < selected.length" > ,</span>
        </span>
      </div>

      <div class="modal-body">
        <label for="message">{{ $t("User.message")}}</label>
        <textarea class="form-control" id="message" v-model="message" rows="3"></textarea>
      </div>
      <template v-slot:footer>
        <button type="button" class="btn btn-secondary"
                @click="closeSendMessageModal()">{{ $t("common.cancel") }}
        </button>
        <button type="button"
                class="btn btn-success"
                @click="sendMessage()">{{ $t("User.send") }}
        </button>
      </template>
    </Modal>
  </div>
</template>

<script>
import Modal from "./Modal";
  export default {
    name: "SendMessageComponent",
    props: ['selected', 'showSendMessageModal', 'replyUser'],
    data: function() {
        return {
            message:'',
        }
    },
    computed: {
      users() {
        return this.$store.state.users.users
      },
    },
    components: {Modal},
    methods: {
     async sendMessage(){
        await this.$store.dispatch('users/sendMessage' , {"recipients" : this.selected , "message" : this.message});
        this.closeSendMessageModal();
      },
      closeSendMessageModal(){
        this.message='';
        this.$emit('closeSendMessageModal', false);
      },
      showUsername(data){
        if (data.users.length===0) {
          return this.replyUser;
        } else if (data.users.length!==0) {          
          return data.users.find(i => i.id === data.s ).username
        }
      }
    },
  }
</script>

