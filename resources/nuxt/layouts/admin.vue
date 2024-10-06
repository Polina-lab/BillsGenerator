<template>
  <div>
    <div class="site">
      <TopBar id="topbar_background"/>
      <div id="wrapper">
        <!-- Page Content -->
        <div :class="{  'page-content-wrapper' : (!$route.path.includes('bills'))}" >
             <Nuxt />
        </div>

        <SelectTeamComponent
          :title="$t('company.select_team')"
          :is-modal-visible="showModalToSelectTeam"
          :teams="$auth.user.available_teams"
        />
      </div>
    </div>
    <div class="footer">
      <Footer v-if="$route.name != null && $route.name.includes('admin')" />
      <ToTop></ToTop>
    </div>
  </div>
</template>

<script>
import ToTop from "../components/common/ToTop";
import SelectTeamComponent from "@/components/bills/SelectTeamComponent";
import TopBar from "@/components/common/TopBar";
import Footer from "@/components/common/FooterLogin";
export default {
  name: "admin",
  middleware: ['auth'],
  components: { SelectTeamComponent, TopBar, Footer, ToTop },
  data() {
    return {
      firm: {}
    };
  },

  computed:{
    showModalToSelectTeam(){
      return this.$store.state.showModalToSelectTeam;
    }
  },


};
</script>

<style scoped>

/* Toggle Styles */
html,body{
  height: 100%;
}
.site{
   min-height: calc(100vh - 230px);
}
#wrapper {
  padding-left: 0;
  -webkit-transition: all 0.5s ease;
  -moz-transition: all 0.5s ease;
  -o-transition: all 0.5s ease;
  transition: all 0.5s ease;
}

.page-content-wrapper {
  width: 100%;
  padding: 15px;
}

@media (min-width: 768px) {
  #wrapper.toggled #sidebar-wrapper {
    width: 0;
  }

  .page-content-wrapper {
    padding: 20px;
    position: relative;
  }

  #wrapper.toggled #page-content-wrapper {
    position: relative;
    margin-right: 0;
  }
}
</style>
