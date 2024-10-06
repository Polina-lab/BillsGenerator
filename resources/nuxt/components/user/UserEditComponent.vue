<template>
<div>
  <div class="headers">
    <h1 id="settings__header">{{ $t('menu.settings') }}</h1>
    <h3 id="settings__subtitle">{{ $t('profile.profile_description') }}</h3>
  </div>
  <div class="container settings__container">
    <div class="sidepanel__box">
      <div class="sidepanel">
        <div class="row row__sidepanel">
          <div class="col-12 col-ms-10 sidepanel__horizontal">
            <p id="menu__button" @click='openMenu = !openMenu'>{{ $t('menu.menu') }}</p>
            <p class="list__menu"  v-for="group in settings_groups" :key="group"
               @click="$emit('changeSettingsPage', group)"
               :class="[{ 'black': settings_group === group }, {'active' : openMenu }]">
              <i v-if="group === 'package' && daysRemainingActive <= 3"
                 class="fas fa-exclamation-circle red">
              </i>
              {{ $t(getSettingsGroupLabel(group)) }}
            </p>
          </div>
        </div>
      </div>
      <div class="main">
        <div v-if="settings_group === 'account'" class="card border-light mt-5">
          <UsSetAccountComponent/>
        </div>
        <div v-if="settings_group === 'firm'" class="card border-light mt-5">
          <UsSetFirmComponent/>
        </div>
        <div v-if="settings_group === 'password'" class="card border-light mt-5">
          <UsSetPasswordComponent/>
        </div>
        <div v-if="settings_group === 'package'" class="card border-light mt-0 mt-md-5">
          <UsSetPackageComponent/>
        </div>
        <div v-if="settings_group === 'team'" class="card border-light mt-0 mt-md-5">
          <UsSetTeamComponent/>
        </div>
        <div v-if="settings_group === 'help'" class="card border-light mt-5">
          <UsSetHelpComponent/>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
import UsSetAccountComponent from './settings/UsSetAccountComponent.vue';
import UsSetTeamComponent from "./settings/UsSetTeamComponent";

export default {
  components: {
    UsSetTeamComponent,
    UsSetAccountComponent,
    UsSetFirmComponent: () => import('./settings/UsSetFirmComponent.vue'),
    UsSetPasswordComponent: () => import('./settings/UsSetPasswordComponent.vue'),
    UsSetPackageComponent: () => import('./settings/UsSetPackageComponent.vue'),
    UsSetHelpComponent: () => import('./settings/UsSetHelpComponent.vue'),
  },
  name: "UserEditComponent.vue",
  props: ['settings_group'],
  mixins: [],
  data(){
    return {
      openMenu: true,
      settings_groups: [ 'account', 'firm', 'password', 'package', 'team' , 'help' ],
      serverUrl: process.env.serverUrl,
      current_date: new Date(),
      active_until_date: null,
    }
  },

  async beforeMount(){
    await this.$store.dispatch('bills/fetchFirmsList'); // couldn't move to the corresponding settings group component
    await this.$store.dispatch('bills/fetchRepresentivesList');
  },

  created(){
    this.$nextTick(() => {
      this.active_until_date = new Date(this.$store.state.current_team.active_until);
    });
  },

  computed: {
    daysRemainingActive() {
      var diffTime = (this.active_until_date - this.current_date);
      return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    }
  },
  methods: {
    getSettingsGroupLabel(group) {
      switch (group) {
        case 'account':
          return 'User.account';
        case 'firm':
          return 'User.firm_data';
        case 'password':
          return 'User.password';
        case 'package':
          return 'register.package';
        case 'help':
          return 'menu.help';
        case 'team':
          return 'menu.team'
        default:
          break;
      }
    }
  }
}

</script>

<style lang="scss" scoped>
@import "@/assets/scss/var";
.headers{
  font-family: 'Montserrat', sans-serif;
  display: flex;
  flex-direction: column;
  margin-left: 4%;
}
.settings__container{
  font-family: 'Montserrat', sans-serif;
  margin-left: 5%;
  padding-right: 5%;
  @media screen and (max-width: 768px){
    min-width: 95%;
    position: relative;
    margin-left: auto;
    margin-right: auto;
    padding-right: 0%;
  }
}
#settings__header{
  font-size: 25px;
  line-height: 1;
 @media screen and  (max-width: 768px){
    position: relative;
    margin-right: auto;
    margin-left: auto;
  }
}
#settings__subtitle{
  font-size: 15px;
  color: $secondary-color;
  @media screen and  (max-width: 768px){
    position: relative;
    margin-right: auto;
    margin-left: auto;
  }
}

%propertis{
  margin-top: 20px;
  margin-bottom: 15px;
  height: 40px;
  white-space: nowrap;
  font-size: 13px;
  border-radius: 6px;
    @media (max-width: 1220px){
    margin-right: 20px;
    height: 30px;
    width: 120px !important;
    font-size: 10px;
  }
  @media (max-width: 991px){
    height: 40px;
    width: 180px !important;
  }
}
.sidepanel__box{
  display: flex;
  justify-content: space-between;
  @media screen and (max-width: 768px){
    flex-direction: column;
  }
}

.sidepanel {
  width: 15%;
  height: fit-content;
  overflow: clip;
  position: -webkit-sticky;
  position: sticky;
  top: 5%;
  text-align: right;
  line-height: 1.2;
  border-right: 1px solid #dfdbdb;
  margin-top: 4%;
  padding-top: 22px;
  font-size: 15px;
  @media screen and (max-width: 768px){
    position: relative;
    display: flex;
    justify-content: space-between;
    font-size: 18px;
    border-right: none;
    border-bottom: 1px solid #dfdbdb;
    min-width: 100%;
  }
  @media screen and (max-width: 600px){
    /*display: block;
    min-width: auto;
    width: 28%;
    position: -webkit-sticky;
    position: sticky;
    font-size: 12px;
    border-bottom: none;
    border-right: 1px solid #dfdbdb;*/
    text-align: left !important;
  }
}
.row__sidepanel{
  width: 100%;
}
.sidepanel__horizontal{
  @media screen and (max-width: 768px){
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    overflow-x: scroll;
    overflow-y: hidden;
    white-space: nowrap;
  }
  @media screen and (max-width: 600px){
    width: 20%;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
    white-space: normal;
    overflow-x: hidden;
  }
}
#menu__button{
  display: none;
  color: $logo-green;
  @media screen and (max-width: 600px){
    display: block;
  }
  &:hover{
    color: $logo-blue;
  }
}
.sidepanel__horizontal p {
  color: $secondary-color;
  margin: 16px 10px;
}
.sidepanel__horizontal p:hover {
  color: black;
}
.main {
  margin-top: 8px;
  width: 80%;
  display: flex;
  flex-direction: column;
  @media screen and (max-width: 768px){
    width: 100%;
  }
  .border-light{
    border: none;
  }
}

.black {
  color: black !important;
}

.red {
  color: red;
}
.list__menu{
  @media screen and (max-width: 600px){
    display: none;
  }
}
 .active{
  @media screen and (max-width: 600px){
    display: block;
  }
}
</style>
