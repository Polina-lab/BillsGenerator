<template>
  <div>
    <h1>{{ $t('clients.add_new')}}</h1>
    <ClientInfo :clientBlockToggle="true"></ClientInfo>
    <div class="row p-2">
      <div class="col-12 ">
         <button class="btn btn-outline-secondary float-right m20"
                @click="addCompanyAct">{{ $t("clients.add_new_company") }}</button>

      </div>
      <div class="col-12">
        <ClientAddition v-if="showAdditionInfo"></ClientAddition>
        <CompanyCreate v-if="showAddCompany"></CompanyCreate>
      </div>
      <div class="col-12">
        <button class="btn btn-outline-secondary m20"  @click="saveClient">{{ $t("common.save") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
  import ClientInfo  from "@/components/clients/ClientInfo";
  import ClientAddition from "@/components/clients/clientAddition";
  import CompanyCreate from "@/components/bills/CompanyCreate";
  import {Client} from "assets/js/models/Client";
  import {Company} from "assets/js/models/Company";

  export default {
    name: "ClientsList",
    layout : 'admin',
    middleware:[ 'auth' ],
    components: {ClientInfo, ClientAddition ,CompanyCreate},
    data() {
      return {
        showAdditionInfo:false,
        showAddressBlock: false,
        showAddCompany: false,
      }
    },

    computed: {
      client() {
        return this.$store.getters['clients/getClientDetail']
      },
    },
    created() {
      this.$store.commit('SET_OBJ', {name: 'clients/ClientDetail', value: new Client()})
      this.$store.commit('SET_OBJ', {name: 'clients/CompanyDetail', value: new Company()})
      this.$store.commit('SET_OBJ', {name: 'breadcrumbs', value: [{name: 'Create', url: ''},]})
    },
    destroyed() {

      this.$store.commit('SET_OBJ', {name: 'breadcrumbs', value: []})
      // this.$store.commit('location/SET_OBJ', {name: 'fullAddress', value: new Address()})
      this.$store.commit("clients/SET_OBJ", {name: "ClientDetail", value: new Client()})
      this.$store.commit("clients/SET_OBJ", {name: "CompanyDetail", value: new Company()})
      this.$store.commit('location/SET_OBJ', {name: 'towns', value: []})
      this.$store.commit('location/SET_OBJ', {name: 'districts', value: []})
      this.$store.commit('location/SET_OBJ', {name: 'streets', value: []})
    },
    methods: {
      async saveClient() {
        if(this.$store.state.clients.CompanyDetail.name !== ''){
          this.$store.commit("clients/PUSH_COMPANY", this.$store.state.clients.CompanyDetail );
        }
         await  this.$store.dispatch('clients/saveClient' ,  { "client" : this.client });
        // this.$router.push("/admin/clients");
      },
      addCompanyAct(){
        this.showAddCompany = !this.showAddCompany;
        this.showAdditionInfo = false;
        this.showAddressBlock = false;
        this.$store.commit('clients/SET_OBJ', {name: 'CompanyDetail', value: new Company()})
      }
    },
  }
</script>

<style scoped>

</style>
