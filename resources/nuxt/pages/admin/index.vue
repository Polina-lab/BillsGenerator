<template>
  <div>
    <RegistrationArrows v-if="page < 7" :page="page" :show_text="true"/>
    <div class="container-fluid" >
      <UserComponent :buyer="buyer" v-if="page === 3" @update="updateBuyer"  @next="goToPage" ></UserComponent>
      <PricingComponent @next="goToPage" v-if="page === 4"></PricingComponent>
      <PaymentComponent @next="goToPage" :buyer="buyer" v-if="page === 5"></PaymentComponent>
      <CompleteComponent @next="goToPage" v-if="page === 6" ></CompleteComponent>
    </div>
    <div v-if="page === 7">
      <div class="loader">
        <i class="fas fa-spinner fa-3x fa-pulse"></i>
       </div>
    </div>
  </div>
</template>
<script>
import UserComponent from "../../components/user/UserComponent";
import RegistrationArrows from "../../components/common/RegistrationArrows";
import PricingComponent from "../../components/bills/PricingComponent"
import PaymentComponent from "../../components/bills/PaymentComponent";
import CompleteComponent from "../../components/bills/CompleteComponent";
import {Buyer} from "assets/js/models/Users";
import {VAT , Firm} from "assets/js/models/Bill";
export default {
  name:"AdminIndex",
  layout: "admin",
  middleware:[ 'auth' ],
  components: { RegistrationArrows, PricingComponent, PaymentComponent, CompleteComponent  , UserComponent},
  data() {
    return {
      page: 4,
    }
  },
  mounted() {
    this.$nextTick().then(() => {
      this.$store.commit('users/SET_OBJ', { name: 'buyer', key: 'email', value: this.$auth.user.email });
      this.$store.commit('users/SET_OBJ', { name: 'buyer.firm.vat[0].default', value: true });
    });
  },
  async created() {
    if (this.$auth.user.username === '') {
      this.page = 3
      await this.$store.commit("users/SET_OBJ", { name: 'buyer', value:  new Buyer()});
      this.show_circles = true;
    } else if (this.$store.state.current_team.payment_plan_id === 0 ||
               this.$auth.user.available_teams[0].payment_plan_id == 0) {
       await this.$store.dispatch('firms/fetchFirmsList');
       const buyer =  this.$auth.user.local_data
       if(this.$store.state.firms.firmsList[0]){
          buyer.firm = this.$store.state.firms.firmsList[0];
       }else {
          buyer.firm = new Firm();
       }
      this.$store.commit("users/SET_OBJ", { name: 'buyer', value:  buyer });
      this.page = 4;
    } else {
      this.page = 7;
      this.$router.push('/bills');
    }
  },
  computed:{
    buyer() {
      return this.$store.state.users.buyer;
    },
  },
  methods: {
    goToPage(val) {
      if(!Boolean(this.$auth.user.has_buyer) && val === 4){
        val = 6;
      }
      this.page = val;
    },

    updateBuyer(data){
      if(data.to === 'vat'){
        this.$store.commit('users/ADD_ITEM_ARRAY_BOTTOM', { name: 'buyer.firm.vat', value: new VAT()});
        this.$store.commit('users/SET_OBJ', { name: 'buyer.firm.vat['+ this.buyer.firm.vat.length -1 +'].default', value: true });
      }

      if(data.to === 'firm'){
        this.$store.commit('users/SET_OBJ', { name: 'buyer', key: 'firm', subkey: data.data.target.id, value: data.data.target.value })
      }

      if(data.target) this.$store.commit("users/SET_OBJ", { name: 'buyer', key: data.target.id, value: data.target.value })
    },

    createBuyer(){
      this.$store.dispatch("users/createBuyer").then(
        res => {
          if(res.type === 'success')
            this.$emit("next", 4);
        },
        err =>{
          this.$toast.error(err.msg);
        }
      );
    }
  }
}
</script>

<style scoped>

.loader {
  color: forestgreen;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 9000;
}

</style>
