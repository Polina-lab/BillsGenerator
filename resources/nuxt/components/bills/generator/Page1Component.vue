<template>
  <section>
    <label>{{ $t("clients.search") }}</label>
    <div class="form-row">
      <div class="col-sm-8 col-md-3 col-lg-10">
        <InputSearch id="new_full_search"
                     dispatchModule="clients"
                     :placeholder="$t('clients.enter_anything')"
                     dispatchName="fetchClientsOrCompanies"
                     @SelectedClient="AddSelectedClient">
        </InputSearch>
      </div>
      <div class="col-sm-4 col-md-3 col-lg-2 text-center">
         <button class="btn btn-outline-secondary "
                  :class="{'active': showAddClient}"
                  @click="checkClient" >
            {{ $t('clients.add_new') }}
          </button>
      </div>
    </div>
      <ClientInfo v-if="showAddClient || clients.firstname != null" :show-sup="showAddCompany"
                  @showConfirm="showConfirm"
                  @setCompanyVisible="setCompanyVisible" ref='clientInfo'></ClientInfo>
      <CompanyCreate v-if="showAddCompany || companies.name != null" @showConfirm="showConfirm"></CompanyCreate>
      <div class="col-3 pt-4" v-if="showAddCompany">
        <button class="btn btn-outline-secondary m-2"
                :class="{'inactive': showAddCompany}"
                @click="deleteCompany">
          {{ $t("company.delete_company")}}
        </button>
      </div>
    <div class="row">
      <div class="offset-9 col-3" v-if="showAddClient || clients.firstname != null">
        <input type="checkbox"
               v-model="hasRemember" :disabled="hasClientId()"
               @click="hasRemember ? '' : $toast.success($t('clients.saving_client')).goAway(9000)"/>
        {{ $t('clients.store_in_db')}}
      </div>
      <div v-if="showAddClient === false & showAddCompany === false" class="col-12 pt-5 pb-5 text-center">
        <p class="text-center">{{ $t('clients.instructions')}}</p>
      </div>
    </div>
    <!--Modal to confirm changes-->
    <Modal :title="$t('common.change')"
           @closeModal="isConfirmVisible.status = $event;"
           v-if="isConfirmVisible.status">
      <div class="alert alert-warning">
        <p>{{$t('clients.saving_client_question')}}</p>
        <p>{{$t('clients.saving_client_description_add_new')}}</p>
        <p>{{$t('clients.saving_client_description_change_current')}}</p>
        <p>{{$t('clients.saving_client_description_change_all')}}</p>
      </div>
      <template v-slot:footer>
        <button type="button"
                class="btn btn-primary m-2"
                @click="addNew(); isConfirmVisible.status = false;">
          {{$t('clients.add_new')}}
        </button>
        <button type="button"
                class="btn btn-secondary m-2"
                @click="createCopy(); isConfirmVisible.status = false;">
          {{$t('clients.change_current')}}
        </button>
        <button type="button"
                class="btn btn-secondary m-2"
                @click="changeData(); isConfirmVisible.status = false;">
          {{$t('clients.change_all')}}
        </button>
        <button type="button" class="btn btn-danger m-2"
                @click="isConfirmVisible.status = false;">
          {{$t('common.cancel')}}
        </button>
      </template>
    </Modal>
  </section>
</template>

<script>
import InputSearch from "@/components/elements/InputSearch";
export default {
  name: "Page1Component",
  components:{
    InputSearch,
    Modal: () => import("@/components/common/Modal"),
    ClientInfo: ()=> import("@/components/clients/ClientInfo"),
    CompanyCreate: () => import("@/components/bills/CompanyCreate")},
  data() {
    return {
      showAddCompany:false,
      showAddClient: false,
      isConfirmVisible: {
        data : "",
        status : false
      },
    }
  },
  computed: {
    hasRemember:{
      get(){
          if(this.showAddCompany){
            var res = this.$store.state.clients.CompanyDetail.status;
          }else{
            var res = this.$store.state.clients.ClientDetail.status;
          }
          return res;
        },
      set (value) {
        if(this.showAddCompany){
          this.$store.commit("clients/SET_OBJ" , {name: 'CompanyDetail', key: 'status' , value: value });
        }else{
          this.$store.commit("clients/SET_OBJ" , { name: 'ClientDetail' , key: 'status' , value : value })
        }
      }
    },
    clients(){
      return JSON.parse(JSON.stringify(this.$store.state.clients.ClientDetail));
    },
    companies(){
      return JSON.parse(JSON.stringify(this.$store.state.clients.CompanyDetail));
    },
    canChange(){
      return this.$store.state.clients.canChange ;
    },

  },

  methods: {

    /**
     * Retrieves selected client data from server and writes it to store then touches
     */
    async AddSelectedClient(data){
     // await this.$store.dispatch('clients/createCompany');
      this.showAddCompany = false;
      await this.$store.dispatch('clients/fetchClient', data.client_id);
      this.$store.commit('clients/SET_OBJ' , {name : "canChange" , value : false});
      this.$store.commit('bills/SET_OBJ', { name: 'eventBill', key: 'client_id', value: data.client_id });

      this.showAddClient = true;
      if(data.company_id) {
        await this.$store.dispatch('clients/fetchCompany', data.company_id);
        this.$store.commit('bills/SET_OBJ', { name: 'eventBill', key: 'company_id', value: data.company_id })
        this.showAddCompany = true;
      }
  /*    if (this.$refs.clientInfo) {
        this.$refs.clientInfo.checkDataValidity();
      }*/
    },



    /**
     * Toggles client data inputs display, hides company data input and sets client data to defaults
     */
    checkClient(){
      this.showAddClient = !this.showAddClient;
      this.showAddCompany = false;
      this.hasRemember = true;
      this.$store.dispatch('clients/createClient')
      this.$store.dispatch('clients/createCompany')
    },

    addNew(){
      this.$store.commit('clients/SET_OBJ' , {name: 'canChange' , value: true});
      if(this.isConfirmVisible.name  === 'ClientDetail') {
        this.$store.commit("clients/SET_OBJ", {name: this.isConfirmVisible.name, key: 'id', value: null})
        this.$store.commit("clients/SET_OBJ", {name: 'ClientDetail', key: 'companies', value: {}})
        this.$store.dispatch('clients/createCompany')
        this.showAddCompany = false;
        this.hasRemember = true;
      }else if ( this.isConfirmVisible.name  === 'CompanyDetail' ){
        this.$store.commit("clients/SET_OBJ", {name: this.isConfirmVisible.name, key: 'id', value: null})
        this.hasRemember = true;
      }else{
        console.log("Oops : !!");
        console.log(this.isConfirmVisible);
      }

    },

    async createCopy(){
      if(this.isConfirmVisible.name  === 'ClientDetail') {
        this.$store.commit("clients/SET_OBJ", {name: 'ClientDetail', key: 'status', value: 0})
        await this.$store.dispatch('clients/updateClient',  { data : this.clients  })
        this.$store.commit("clients/SET_OBJ", {name: 'ClientDetail', key: 'status', value: 1})
        this.$store.commit("clients/SET_OBJ", {name: this.isConfirmVisible.name, key: 'id', value: null})
        this.$store.commit("clients/SET_OBJ", {name: 'ClientDetail', key: 'companies', value: {}})
        this.$store.dispatch('clients/createCompany')
        this.hasRemember = true;
        this.showAddCompany = false;
      }else if (this.isConfirmVisible.name  === 'CompanyDetails'){
        this.$store.commit("clients/SET_OBJ", {name: 'CompanyDetails', key: 'status', value: 0})
        await this.$store.dispatch('clients/updateCompany',  { data : this.companies  })
        this.$store.commit("clients/SET_OBJ", {name: this.isConfirmVisible.name, key: 'id', value: null})
        this.hasRemember = true;
      }else {
        console.log(this.isConfirmVisible.name);
      }
    },


    changeData(){
      this.hasRemember = true;
      this.$store.commit("clients/SET_OBJ", {name: this.isConfirmVisible.name, key: 'status', value: 1})
      this.$store.commit("clients/SET_OBJ" , { name : 'canChange' , value : true})
    },

    showConfirm(data){
      console.log(data);
      this.isConfirmVisible = data;
    },

    setCompanyVisible(){
      this.showAddCompany = true;
      this.$store.commit("clients/SET_OBJ",  { name : 'canChange' , value : true} );
      this.$store.commit("clients/SET_OBJ" , {name: 'CompanyDetail', key: 'status' , value: false });
    },

    hasClientId(){
      var res =  this.clients.id !== null
      if (this.canChange === true) res = false;
      return res
    },

    /**
     * Toggles company data inputs display and sets data to defaults
     */
    checkCompany(){
      this.$store.dispatch('clients/createCompany')
    },

    /**
     * Toggles company data inputs display
     */
    deleteCompany() {
      this.showAddCompany = !this.showAddCompany;
    },
  }
}
</script>

<style scoped>

.mt31{
  margin-top: 31px;
}
</style>
