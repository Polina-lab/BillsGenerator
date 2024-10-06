<template>
  <div>
    <div class="container-fluid m-1">
      <n-link class="btn btn-outline-success" to="/admin/clients/create/">{{ $t("clients.add_new") }}</n-link>
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-outline-primary" :class="{ 'active' : !active }">
          <input type="radio" v-model="active" :value="0" @click="search" id="option1" autocomplete="off" checked>{{ $t('common.active') }}
        </label>
        <label class="btn btn-outline-primary" :class="{ 'active' : active }">
          <input type="radio" v-model="active" :value="1" @click="search" id="option2" autocomplete="off">{{ $t('common.inactive') }}
        </label>
      </div>
    </div>
    <div class="table-responsive mt-1 tableFixHead">
      <table class="table table-hover" id="to_print"> <!--ДЛЯ ПРИНТА-->
        <thead>
          <tr>
            <!--<th scope="col" class="p-1">
              <input type="checkbox"
                     v-model="selectAll"
                     @change="selectAllClients">
            </th>-->
            <th scope="col" class="p-1 text-white"><div style="padding-left: 30px">{{ $t('clients.client_name') }}</div></th>
            <th scope="col" class="p-1 text-white">{{ $t('bills.company_name') }}</th>
            <th scope="col" class="p-1">
              <select id="input__brocker" name="user.id" class="form-control" v-model="search_broker">
                <option :value="null" >{{ $t('bills.select_user')}}</option>
                <option v-for="(user, idx) in  $store.state.users.users" :key="idx" :value="user.id"><div v-if="user.username !== null">{{ user.username }}</div>
                </option>
              </select>
              </th>
            <th scope="col" class="p-1">
              <div class="input-group">
                <input class="form-control" id="input__mail" type="text" :placeholder="$t('User.email')" v-model="search_email">
              </div>
            </th>
            <th scope="col" class="p-1 text-white">{{ $t('User.phone') }}</th>
            <th scope="col" class="p-1 text-white">{{ $t('common.status') }}</th>
            <th scope="col" class="p-1 text-white text-center">{{ $t('common.created_at') }}</th>
            <th scope="col" class="p-1 text-white">{{ $t('bills.actions') }}</th>
          </tr>
        </thead>
        <tbody>
        <tr v-for="(client, idx) in ClientsList" :key="idx">
          <!--<td>
            <input type="checkbox" v-model="selected" :value="client.id">
          </td>-->
          <td :data-label=" $t('User.username')"
            v-if="client.username"
            :class="{ 'pointer makeBrighter': isElementInteractible('edit-client') }"
            :title="$t('clients.edit_client')"
            @click="isElementInteractible('edit-client')&&editItem(client)">{{ client.username }}</td>
          <td v-else>--</td>
          <td :data-label=" $t('bills.company_name')" >
            <span
              v-if="client.companies"
              :class="{ 'pointer makeBrighter':isElementInteractible('edit-client') }"
              v-for="(comp, idx) in client.companies" :key="idx" :title="$t('clients.edit_company')"
              @click="isElementInteractible('edit-company') && editItem(comp)">
              {{ comp.name }}<span v-if="idx+1 < client.companies.length">, </span> </span>
            <span v-else>--</span>
          </td>
          <td :data-label=" $t('clients.client_name')" >
            {{ $store.state.users.users.find( u => u.id === client.user_id )
              ? $store.state.users.users.find( u => u.id === client.user_id ).username : "--" }}
          </td>
          <td :data-label="$t('User.email')" >{{ client.email }}</td>
          <td :data-label="$t('User.phone')" >{{ client.phone }}</td>
          <td :data-label="$t('common.status')" class="text-center">{{ client.status }}</td>
          <td :data-label="$t('common.created_at')" class="text-center">{{ client.created_at}}</td>
          <td :data-label="$t('common.actions')">
            <span class="float-right">
              <div class="icons__box">
              <img
                src="/icons/edit_color.svg"
                alt="Edit."
                :class="{
                  'zeroOpacity':!isElementInteractible('edit-client'),
                  'pointer':isElementInteractible('edit-client')
                  }"
                @click="isElementInteractible('edit-client')&&editItem(client)"
                class="button__edit">
              <img
                src="/icons/delete_red.svg"
                alt="Delete."
                :class="{
                  'zeroOpacity':!isElementInteractible('edit-company'),
                  'pointer':isElementInteractible('edit-company')
                  }"
                @click="isConfirm = true; action_client = client"
                class="button__delete">
                </div>
            </span>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
<!--
    <div class="row" >
      <div class="col-offset-10 col-2">
        <select class="form-control action-selected mt-3 mb-5"
                :disabled="!selected.length"
                v-model="currentAction"
                @change="actionStart">
          <option :value="null" disabled v-if="!selected.length">{{$t("clients.select_action")}}</option>
          <option :value="null" disabled v-else>{{$t("clients.actions")}}</option>
          <option value="delete" @click="isElementInteractible('delete-client')&&actionStart('delete', client.id)">{{$t("clients.delete")}}</option>
        </select>
      </div>
    </div>-->

    <Modal :title="$t('common.delete')"
           @closeModal="isConfirm = $event;"
           v-if="isConfirm">
      <div class="alert alert-warning">
          {{ $t('clients.deletion_warning') }}
      </div>
      <template v-slot:footer>
        <button type="button"
                class="btn btn-primary"
                @click=" actionStart('delete', action_client.id); isConfirm = false;">
          {{ $t('common.delete') }}
        </button>

        <button type="button"
                class="btn btn-secondary"
                @click="actionStart('copy', action_client); isConfirm = false;">
          Create copy
        </button>
        <button type="button" class="btn btn-danger"
                @click="isConfirm = false;">{{ $t('common.cancel') }}
        </button>
      </template>
    </Modal>



  </div>
</template>

<script>

  import isElementInteractible from "@/mixins/isElementInteractible";
  import Modal from "@/components/common/Modal";
  export default {
    name: "index",
    layout: "admin",
    middleware:[ 'auth' ],
    components: {Modal},
    mixins:[isElementInteractible],
    data(){
      return {
        isConfirm : false,
        active : 0,
        currentAction:null,
        action_client : {},
        selected:[],
        selectAll: false,
        search_address: "",
        search_broker: "",
        search_email: "",
        search_status: "",
      }
    },
    async asyncData({store}) {
      await store.dispatch('clients/fetchClientsList');
      await store.dispatch('users/fetchUsersList');
    },

    computed:{
      ClientsList() {
        //return JSON.parse(JSON.stringify(this.$store.state.clients.ClientsList))
        return this.$store.getters["clients/getClientsList"];
      },
    },

    methods:{
      selectAllClients(e) {
        if (e.target.checked) {
          this.selected = this.ClientsList.map(i => i.id)
        } else {
          this.selected = []
        }
      },

      async actionStart(action , item ){
        if (typeof action === 'string') {
          this.currentAction = action
        }
        if(this.currentAction === "copy") {

          if(this.action_client) {
            this.action_client.status = 0;
            await this.$store.dispatch('clients/updateClient', this.action_client)
            var new_client  = this.action_client;
            new_client.id =  null;
            new_client.status =  1;
            new_client.companies.map(el => el.id = null);
            await this.$store.dispatch('clients/saveClient', { client: new_client});
            await store.dispatch('clients/fetchClientsList');
          }
        }
        if(this.currentAction === "delete") {
            await this.$store.dispatch('clients/clientsDelete',item ? [item] :  this.selected )
        }
            this.currentAction = null
            this.selected = []
            this.selectAllCkeckbox = false
        },

      search(){
        let request = "?address=" + this.search_address;

        request += '&active=' + this.active;

        if(this.search_broker != null){
          request += "&broker=" + this.search_broker;
        }
        if(this.search_email != null){
          request += "&email=" + this.search_email;
        }
        if(this.search_status != null){
          request += "&status=" + this.search_status;
        }
        this.$store.dispatch("clients/fetchClientsList", request );
      },
      deleteItem(data){
        this.$store.dispatch("clients/deleteClient" , data);
      },
      editItem(data){
        if(data.firstname){
          this.$router.push("/admin/clients/"+ data.id)
        }else {
          this.$router.push("/admin/clients/company/" +data.id)
        }
      },


    }
  }
</script>

<style lang="scss" scoped>
table {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  padding: 0.3rem;
  width: 100%;
}
.pointer{
  cursor: pointer;
}
.makeBrighter:hover {
  filter: brightness(1.2);
}
.zeroOpacity{
  opacity: 0%;
}
table > tbody > tr {
  border-bottom: 1px solid cornflowerblue;
}
table > tbody > tr > td{
  vertical-align: middle;
}
table > thead > tr > th{
  vertical-align: middle;
  line-height: 1.2;
  white-space: nowrap;
}
#input__mail,
#input__brocker{
  max-width: 190px;
  min-width: 100px;
}
  @mixin properties{
   /*.table tbody tr > *:nth-child(1){
       display: none;
  }*/
   .table thead{
    display: none;
  }
   .table tbody tr{
      display: block;
      text-align: right;
    td{
      display: flex;
      justify-content: space-between;
    }
    td::before {
      content: attr(data-label);
      font-weight: bold;
    }
  }
   .span{
    display: flex;
    flex-direction: column;
  }
  .form-control{
    display: none;
  }
}
  .button__delete{
    width:25px;
  }
  .icons__box{
    display: flex;
  }
  .button__edit{
    padding-right: 10px;
    width: 40px;
  }

@media screen and (max-width: 768px){
  @include properties;
}

</style>
