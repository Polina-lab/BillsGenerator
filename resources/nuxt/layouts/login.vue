<template>
  <div>
    <DemoTosModal v-if="isDemoModalVisible" :isDemoModalVisible="isDemoModalVisible" @closeModal="$store.commit('SET_OBJ' , { name : 'isDemoModalVisible' , value: false})"/>
    <div class="site">
      <TopBar id="topbar_background"/>
      <div>
        <Nuxt/>
      </div>
    </div>
    <div class="footer">
      <Footer/>
    </div>
    <Cookies v-if="!hasCookiesAccepted"></Cookies>
  </div>
</template>

<script>
  import TopBar from "../components/common/TopBar";
  import Footer from "../components/common/FooterLogin";
  import DemoTosModal from "@/components/landing/DemoTosModal";
  import Cookies from "@/components/landing/Cookies";

  export default {
    name: "loginLayout",
    components: {TopBar, Footer , DemoTosModal, Cookies },
    middleware: ['cookies'],
    created(){
      if(this.$auth.loggedIn){
        this.$router.push('/admin');
      }
    },
    computed:{
      isDemoModalVisible(){
        return this.$store.state.isDemoModalVisible;
      },
      hasCookiesAccepted(){
        return this.$store.state.CookiesAccepted;
      },
    }
  }
</script>
<style scoped lang="scss">
@import "./assets/scss/_var.scss";
html, body{
  height: 100%;
}
.site{
  min-height: calc(100vh - 230px);
}
.Nuxt{
  display: flex;
  max-width: 1400px;
}

</style>
