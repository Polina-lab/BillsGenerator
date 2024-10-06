<template>
  <div>
    <div class="container-fluid">
      <button class="btn btn-success" @click="showModalRole = true">Create new role</button>
<!--      <button class="btn btn-success" @click="showModalPermission = true">Create new permission</button>-->
    </div>
    <section>
      <div class="col-12 tableFixHead" id="to_print">
        <h3>{{ $t('User.permissions')}}</h3>
        <table class="table">
          <thead>
            <tr>
              <th>{{ $t('User.role')}}</th>
              <th>{{ $t('User.permission')}}</th>
              <th>{{ $t('User.users')}}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(role , i) in permissionsTable" :key="i">
              <td>
                {{ role.name }}<br>
                <span class="delete_btn"  @click="showModalDeleteRole = true; role_id = role.id">Delete role</span>
              </td>
              <td v-if="role.permissions" class="permissions_form">
                 <multiselect
                  :id="role.id"
                  :value="role.permissions"
                  @input="updatePermission({key : i , id: role.id } , $event)"
                  :options="permissions"
                  :multiple="true"
                  :close-on-select="false"
                  label="sys_name" track-by="id" >
                   <template slot="selection" slot-scope="{ values,  isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} selected</span></template>
                 </multiselect>
              </td>


              <td v-if="role.users" >
                <span v-for="(user, i) in role.users" :key="i">
                  <n-link :to="{ path :'/user/' + user.id}">
                    {{ user.username }}
                  </n-link>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <Modal :title="$t('role.create_new_role')"
           :active="showModalRole"
           @closeModal="closeModal('role')"
           v-if="showModalRole">
      <div class="modal-body">
        <div class="row">
          <div class="col-9">
            <label for="name">{{ $t("role.name")}}</label>
            <input type="text" class="form-control col-8" id="name" v-model="role.name" required>
          </div>
          <div class="col-3">
            <label for="is_enabled">{{ $t("role.is_enabled")}}</label>
            <input type="checkbox" class=" col-2" id="is_enabled" v-model="role.is_enabled" required>
          </div>
        </div>

        <label for="desc">{{ $t("role.sysname")}}</label>
        <input type="text" id="desc" class="form-control" v-model="role.sys_name" required>

      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary float-left" @click="closeModal('role')">{{ $t("system.cancel") }}</button>
        <button class="btn btn-success float-right" @click="createNewRole">{{ $t("system.success") }}</button>
      </div>
    </Modal>


    <Modal :title="$t('role.confirm_delete')"
           :active="showModalDeleteRole"
           @closeModal="closeModal('delete-role')"
           v-if="showModalDeleteRole">
      <div class="modal-body">
        <div class="p-2">!  Удаляя роль - избавьте пользователей от данной роли. </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary float-left" @click="closeModal('delete-role')">{{ $t("system.cancel") }}</button>
        <button class="btn btn-success float-right" @click="deleteRole()">{{ $t("system.success") }}</button>
      </div>
    </Modal>


<!--    <Modal :title="$t('role.create_new_permission')"
           :active="showModalPermission"
           @closeModal="closeModal('permission')"
           v-if="showModalPermission">
      <div class="modal-body">
        <label for="sysname">{{ $t("permissions.sysname") }}</label>
        <input type="text" id="sysname" class="form-control" v-model="permission.sysname" >

        <label for="description">{{ $t("permissions.description") }}</label>
        <input type="text" id="description" class="form-control" v-model="permission.description" >

        <label for="role" >{{ $t("permissions.select_role") }}</label>
        <select name="role" id="role" v-model="permission.role" class="form-control">
          <option v-for="rol in permissionsTable" :value="rol.id" :key="rol.id">{{ rol.name }}</option>
        </select>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary float-left" @click="closeModal('permission')">{{ $t("system.cancel") }}</button>
        <button class="btn btn-success float-right" @click="permissionCreate">{{ $t("system.success") }}</button>
      </div>
    </Modal>-->


  </div>
</template>

<script>
import Modal from "@/components/common/Modal";
import Multiselect from "vue-multiselect";

export default {
  name: "roles",
  layout: 'admin',
  middleware: [ "auth" ],
  components: { Modal, Multiselect },
  data: () => ({
    showModalRole: false,
    showModalPermission: false,
    showModalDeleteRole: false,
    test: [],
    role_id: null,
    role: {
      name: null,
      sys_name: null,
      is_enabled: true
    },
    permission: {
      name: null,
      is_enabled: null,
      desc: null,
    },
  }),

  async asyncData({store}){
    await store.dispatch('permissions/fetchRolesWithPermissions');
    await store.dispatch('permissions/fetchPermissions');
  },

  computed:{
    // roles() {
    //   return JSON.parse(JSON.stringify(this.$store.state.permissions.roles))
    // },
    permissions(){
      return this.$store.state.permissions.permissions;
    },
    permissionsTable(){
      return this.$store.state.permissions.permissionsTable;
    }
  },

  methods:{

    async roleToggleStatus(item){
      item.is_enabled = !item.is_enabled;
      await this.$store.dispatch("permissions/updateRole", item)
        .then(res => {
          this.$toast.success(res.msg).goAway(2500);
          this.$store.dispatch('permissions/fetchRoles');
        })
        .catch(err => {
          this.$toast.error(err.msg).goAway(2500);
        })
    },

    async deleteRole() {
      await this.$store.dispatch("permissions/deleteRole", this.role_id)
        .then(res => {
          this.$toast.success(res.msg).goAway(2500);
          this.$store.commit('permissions/DELETE_ITEM_ARRAY', { name: 'permissionsTable', value: this.role_id });
          this.closeModal('delete-role');
          this.role_id = null;
          // this.$store.dispatch('permissions/fetchRoles');
        }).catch(err => { this.$toast.error(err.msg).goAway(2500) });
    },

    async permissionDelete(id) {
      await this.$store.dispatch("permissions/deletePermission", id);
    },

    async updatePermission(param, data) {
       this.$store.commit('permissions/SET_OBJ', { name : 'permissionsTable' ,  key: param.key , subkey : "permissions",  value: data});
       await this.$store.dispatch('permissions/updatePermissions', param );
    },

    closeModal(data) {
      switch (data) {
        case "role":
          this.showModalRole = false;
          this.role.name = "";
          this.role.sysname  = "";
          break;
        case "permission":
          this.showModalPermission = false;
          this.permission.name = "";
          this.permission.desc ="";
          break;
        case "delete-role":
          this.showModalDeleteRole = false;
      }
    },

    async permissionCreate() {
      await this.$store.dispatch("permissions/CreateNewPermission", this.permission).then(
        res => {
          this.showModalPermission = false;
          this.$toast.success(res["data"].msg).goAway(2500);
          this.$store.dispatch('permissions/fetchPermissions');
          this.permission = {
            sysname: null,
            description: true
          };
        }).catch(err => {
          this.$toast.success(err["data"].msg ).goAway(2500);
        }
      )
    },

    async createNewRole() {
      await this.$store.dispatch("permissions/createNewRole", this.role).then(
        res => {
          this.showModalRole = false;
          this.$toast.success(res.msg ).goAway(2500);
          this.$store.dispatch('permissions/fetchRolesWithPermissions');
          this.$store.dispatch('permissions/fetchRoles');
          //  даже 2 запроса
          this.role = {
            name: null,
            sys_name: null,
            is_enabled: true
          };
        }
      ).catch(err => {
        this.$toast.success(err.msg).goAway(2500);
      })
    }
  }
}
</script>

<style scoped>
.delete_btn{
  cursor: pointer;
  color: red;
  font-size: 14px;
}



</style>
