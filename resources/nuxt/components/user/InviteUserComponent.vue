<template>
  <div>
    <Modal :title="title"
           :active="isVisible"
           @closeModal="closeModal()"
           v-if="isVisible">
      <div class="modal-body">
        <div class="row">
          <div class="col-4">
            <label for="role">{{ $t('User.Role') }}</label>
            <select name="role" class="form-control"  v-model="role" id="role" required>
              <option v-for="role in roles" :value="role.id" :key="role.id">{{ role.name }}</option>
            </select>
          </div>
          <div class="col-8">
            <label>{{ $t('User.email')}}</label>
            <input type="email" class="form-control" v-model="email" placeholder="email" required>
          </div>
        </div><!--row-->
      </div>
      <template v-slot:footer>
        <button class="btn btn-secondary" @click="closeModal">{{ $t('common.cancel')}}</button>
        <button class="btn btn-success" @click="sendInvite">{{ $t('User.invite')}}</button>
      </template>
    </Modal>
  </div>
</template>

<script>
  import Modal from "@/components/common/Modal";
    export default {
      name: "InviteUserComponent",
      props: [ "isVisible", "title"],
      components: { Modal },
      data() {
        return {
          role: 3,
          email: null
        }
      },
      computed: {
        roles() {
          return this.$store.state.permissions.roles;
        }
      },
      methods: {
        closeModal() {
          this.$emit('closeInviteModal', false);
        },
        async sendInvite(){
          await this.$store.dispatch("users/sendInvite", { roles_id: this.role, email: this.email }).then(
            res => {
              this.$toast[res.data.type](res.data.msg).goAway(3000);
              this.$emit('closeInviteModal', false);
            }).catch(err => {
              console.log(err);
              this.$toast.error("Oops!").goAway(3000)
          });
        }
      }
    }
</script>

<style scoped>

</style>
