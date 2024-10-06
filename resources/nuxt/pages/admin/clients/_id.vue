<template>
  <div>
    <ClientInfo :clientBlockToggle="true"></ClientInfo>
    <div class="row">
      <div class="col-12 p-2" v-if="!showAddCompany">
        <button class="btn btn-outline-secondary float-right m20" @click="addCompanyAct"> {{ $t("clients.add_new_company")}}</button>
<!--        <button class="btn btn-outline-primary float-right m20" @click="showAdditionalInfo = !showAdditionalInfo;  showAddressBlock = false; showAddCompany = false;">{{ $t("clients.add_additional_info") }}</button>
        <button class="btn btn-outline-success float-right m20" @click="showAddressBlock = !showAddressBlock; showAddCompany = false; showAdditionalInfo= false;">{{ $t("clients.add_address") }}</button>-->
      </div>
      <div class="col-12 p-2">
<!--        <ClientAddition v-if="showAdditionalInfo"></ClientAddition>-->
        <CompanyCreate v-if="showAddCompany"></CompanyCreate>
<!--        <ClientAddresses v-if="showAddressBlock"></ClientAddresses>-->
      </div>
      <div class="col-12 p-2">
        <button class="btn btn-outline-secondary m20"  @click="updateClient"> {{ $t("common.update")}}</button>
      </div>
    </div>
  </div>
</template>

<script>
import {Company} from "assets/js/models/Company";
import {Client} from "assets/js/models/Client";
import ClientInfo from "@/components/clients/ClientInfo";
export default {
  name: "ClientEdit",
  layout: "admin",
  middleware:[ 'auth' ],
  components: {
    ClientInfo,
    CompanyCreate: () => import("@/components/bills/CompanyCreate")},
  async asyncData({store, params}) {
        await store.dispatch('clients/fetchClient', params.id)
  },
  data() {
    return {
      showAdditionalInfo:false,
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
    this.showAddCompany = (this.$store.state.clients.CompanyDetail.name === null);
  },

  destroyed() {
    this.$store.commit("clients/SET_OBJ", {name: "ClientDetail", value: new Client()})
    this.$store.commit("clients/SET_OBJ", {name: "CompanyDetail", value: new Company()})
  },

  methods:{
    updateClient() {
      if (this.$store.state.clients.CompanyDetail.name !== '') {
        this.$store.commit("clients/PUSH_COMPANY", this.$store.state.clients.CompanyDetail);
      }
      this.$store.dispatch('clients/updateClient', this.client)
    },
    addCompanyAct(){
      this.showAddCompany = ! this.showAddCompany;
      this.showAdditionalInfo = false;
      this.showAddressBlock = false;
      this.$store.commit('clients/SET_OBJ', {name: 'CompanyDetail', value: new Company({'user_id' : this.$auth.user.local_data.id})})
    }
  }

}
</script>

<style scoped>

</style>
