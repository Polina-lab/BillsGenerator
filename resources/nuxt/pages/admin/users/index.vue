<template>
  <div>
    <div class="container-fluid container__button m-1">
      <button class="btn btn-outline-success" @click="inviteModal = true">{{ $t("User.invite_User")}}</button>
    </div>


    <div class="table-responsive tableFixHead">
      <no-ssr>
        <table class="table table-hover">
          <thead>
            <tr>
              <!--<th class="checkbox">
                <input type="checkbox" class="form-check-input" :checked="selectedAll" @change="selectAllUsers($event)">
              </th>-->
              <th class="text-white">{{ $t("User.login") }}</th>
              <th class="text-white">{{ $t("User.username") }}</th>
              <th class="text-white">{{ $t("User.phone")}}</th>
              <th class="text-white">{{ $t("User.Role") }}</th>
              <th class="text-white">{{ $t("User.lang") }}</th>
              <th class="text-white text-center">{{ $t("common.status")}}</th>
              <th class="text-white text-center">{{ $t("common.actions")}}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, idx) in usersList" :key="idx">
              <!--<td class="checkbox">
                <input type="checkbox" class="form-check-input" v-model="selected" :value="user.id">
              </td>-->
              <td :data-label="$t('User.login')"><n-link :to="'/admin/users/'+user.id+'/'">
                {{ user.email }}</n-link></td>
              <td :data-label="$t('User.username')">{{ user.firstname }} {{ user.lastname }}</td>
              <td :data-label="$t('User.phone')">{{ user.phone }}</td>
              <td :data-label="$t('User.Role')">{{ userRoles.find( r => r.id === user.roles_id ).name }}</td>
              <td :data-label="$t('User.lang')">{{ locales.find( r => user.langs_id === r.id ).name }}</td>
              <td :data-label="$t('common.active')"><i :class="is_userActive(user.status)"></i>
                <span v-if="!user.is_enabled">{{ $t('User.' + status[user.status]) }}</span>
              </td>
              <td :data-label="$t('common.actions')" class="text-center">
                  <div class="icons__box">
                      <img
                      src="@/assets/img/users/mes.svg"
                      alt="messenger"
                      class="button__messenger"
                      @click="showModal = true; selected.push(user.id);">

                      <img
                      alt="delete"
                      src="/icons/delete_red.svg"
                      class="button__delete"
                      :class="{ 'button__notification': usersList.length != 1 }"
                      :title="$t('User.not_possible_user')"
                      @click="usersList.length > 1 ?
                      (isConfirmationModalVisible = true)&&(idRowToDelete = user.id)
                      : notification()">
                  </div>
                <div v-if="usersList.length <= 1" class="text__hover">{{ $t("User.not_possible_user") }}</div>
                <i class="fas fa-reply fa-2x icon__notification"></i>
              </td>
            </tr>
          </tbody>
        </table>
      </no-ssr>
    </div>
    <!--Push Modal-->
    <SendMessageComponent :selected="selected" @closeSendMessageModal="closeSendMessageModal" :showSendMessageModal="showModal"/>
    <InviteUserComponent :is-visible="inviteModal" :title="$t('User.invite_User')" @closeInviteModal="closeInviteModal"/>
    <Modal :title="$t('common.delete')"
           @closeModal="isConfirmationModalVisible = $event;"
           v-if="isConfirmationModalVisible">
      <div class="alert alert-light">
        <p>{{ `${$t('User.login')}: ${itemFromUserList(idRowToDelete).email}` }} </p>
        <p>{{ `${$t('User.username')}:
          ${itemFromUserList(idRowToDelete).firstname} ${itemFromUserList(idRowToDelete).lastname}` }}
        </p>
        <p>{{ `${$t('User.Role')}:
          ${userRoles.find(role => role.id === itemFromUserList(idRowToDelete).roles_id).name}` }}
        </p>
        <p class="text-danger">{{ $t('bills.deleteConfirmation') }}</p>
      </div>
      <template v-slot:footer>
        <button type="button"
                class="btn btn-secondary"
                @click.once="isConfirmationModalVisible = false">
                {{ $t("common.cancel")}}
        </button>
        <button type="button" class="btn btn-danger"
          @click="deleteUser(idRowToDelete); isConfirmationModalVisible = false;">{{ $t("common.delete")}}
        </button>
      </template>
    </Modal>
  </div>
</template>

<script>
import  SendMessageComponent  from "@/components/common/SendMessageComponent";
import InviteUserComponent from "@/components/user/InviteUserComponent";
import Modal from '@/components/common/Modal';
export default {
  name: "usersList",
  layout:'admin',
  middleware: ['auth'],
  components: { SendMessageComponent, InviteUserComponent, Modal},
  async asyncData({store}) {
    await store.dispatch('users/fetchUsersList')
    await store.dispatch('permissions/fetchRoles')
  },
  data(){
    return {
      selected:[],
      selectedAll: false,
      inviteModal: false,
      showModal: false,
      idRowToDelete: null,
      isConfirmationModalVisible: false,
      checkUser: false,
      status :[ /*0*/'mail_was_sent', /*1*/'active', 'banned'],
    }
  },
  computed: {
    locales(){
      return this.$i18n.locales;
    },
    usersList() {
      return this.$store.state.users.users;
    },
    userRoles() {
      return this.$store.state.permissions.roles;
    }
  },

  methods: {
   /* fetchUserDetail() {
      this.$store.commit('users/SET_OBJ', { name: 'userDetail', value: this.userDetail })
    },*/
    notification(){
      //TODO: create new key and translation
        this.$toast.error(this.$t("User.not_possible_user")).goAway(5000);
    },


   /* updateUsers(data){

        this.fetchUserDetail.date = data;
        this.fetchUserDetail();
        this.$store.dispatch('users/fetchUsersList');
      },  */
    closeSendMessageModal(){
      this.selected = [];
      this.selectedAll = false;
      this.showModal = false;
    },
    closeInviteModal(){
      this.$store.dispatch('users/fetchUsersList');
      this.inviteModal = false;
    },
    is_userActive(val) {
      if (val) {
        return 'fas fa-check text-success'
      } else {
        return 'fa fa-times text-danger'
      }
    },
    deleteUser(id){
       this.$store.dispatch('users/deleteUser', { 'id': id });
    },
    selectAllUsers(e) {
      if (e.target.checked) {
        this.selected = this.usersList.map(i => i.id)
      } else {
        this.selected = []
      }
    },
    /**
     * Returns user object given user table row ID in table view
     * @param {number} itemID - id to search for
     *
     * @returns {object} - user to return
     */
    itemFromUserList(itemID) {
      return this.usersList.find(x => x.id === itemID);
    },
  },
  destroyed() {
    this.$store.commit('SET_OBJ', { name: 'breadcrumbs', value: [] });
  },
}
</script>

<style lang="scss" scoped>
@import '~assets/scss/_var.scss';
table {
  font-family: 'Montserrat', sans-serif;
  font-size: 15px;
  padding: 0px;
}
.checkbox {
  vertical-align: middle;
}
.checkbox input {
  margin: 0;
  position: relative;
}
.text__hover{
  display: none;
  background-color: #ffffff;
  text-align: center;
  z-index: 3000;
  border: 1px solid red;
  border-radius: 5px;
  margin-top: -44px;
  margin-left: -260px;
  color: black;
  position: absolute;
  width: 220px;
  height: 50px;
}
.icon__notification{
  display: none;
  color: $logo-green;
  position: absolute;
  margin-top: -30px;
  margin-left: -40px;
  z-index: 9000;
}
.button__notification:hover + .text__hover + .icon__notification{
  display: block;
}
.button__notification:hover + .text__hover{
  display: block;
}
.button__delete{
  width:25px;
}
.button__messenger{
  width:40px;
  margin: 0px 10px;
  filter: invert(42%) sepia(73%) saturate(2139%) hue-rotate(168deg) brightness(97%) contrast(101%);
  //filter: invert(63%) sepia(56%) saturate(0%) hue-rotate(147deg) brightness(85%) contrast(85%); //to use, if hover
}
tbody tr td{
  vertical-align: middle;
}
table > thead > tr > th{
  vertical-align: middle;
  line-height: 1.2;
  //white-space: nowrap;
}
.checkbox{
  min-height: 80px;
}
.icons__box{
  display: flex;
}
@media only screen and (max-width: 768px) {
  .table thead {
    display: none;
  }
  .table tr {
    display: block;
  }
  .table td {
    display: flex;
    justify-content: space-between;
  }
  table > tbody > tr {
    border-bottom: 2px solid cornflowerblue;
  }
  .table td::before {
    content: attr(data-label);
    font-weight: bold;
    margin-right: 20px;
    text-align: left;
  }
.container__button{
    display: flex;
    justify-content: right;
  }
}
</style>
