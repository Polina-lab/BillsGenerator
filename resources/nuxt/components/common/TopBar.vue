<template>
  <nav class="top-bar">
    <ul class="top-bar__left mt-20">
      <li class="container-burger">
        <div class="dropdown-block-burger">
          <div class="burger-header">
            <div class="container-i">
              <i class="fas fa-bars fa-2x icon-burger"></i>
              <div class="height"></div>
            </div>
          </div>
          <div v-if="($auth.loggedIn) && !($route.path.includes('page404'))" class="dropdown-burger">
<!--            <n-link to="/admin/roles">{{ $t('menu.permission_roles') }}</n-link>-->
            <n-link to="/admin/users">{{ $t('menu.users') }}</n-link>
            <n-link to="/admin/clients">{{ $t('menu.clients') }}</n-link>
            <n-link  v-if="$auth.user && $auth.user.role.name ==='superAdmin'" :to="'/admin/user/edit/'+ $auth.user.id + '?page=package' ">{{ $t('menu.settings') }}</n-link>
            <n-link to="/admin/trans">{{ $t('menu.translations') }}</n-link>
          </div>
          <div v-else class="dropdown-burger">
            <n-link v-if="!($route.path !== null && ($route.path.includes('register') || $route.path.includes('notify/email_register_was_sended') || $route.path.includes('help') || $route.path.includes('page404')))" to='/register'>{{ $t('menu.register') }}</n-link>
            <n-link v-if="$route.path !== null && $route.path.includes('login')" to="/login">{{ $t('menu.login') }}</n-link>
            <n-link to="/help">{{ $t('menu.help') }}</n-link>
            <n-link v-if="$route.path !== null && !($route.path.includes('help/about') || $route.path.includes('page404'))" to="/help/about">{{ $t('menu.about_us')}}</n-link>
            <a v-if="!($route.path !== null && ($route.path.includes('page404')||$route.path.includes('login')))" @click="$store.commit('SET_OBJ' , {name : 'isDemoModalVisible' , value : true})">{{ $t('menu.demo') }}</a>
            <button v-if="($route.path != null && $route.path.includes('help'))" @click="linkOnBackPage()">{{ $t('menu.back')}}</button>
          </div>

        </div>
      </li>

      <li>
        <n-link :to="linkOnHomePage()">
          <img class="logo__white" src="@/assets/img/logoWhite.svg" alt="Logo">
        </n-link>
      </li>
      <li v-if="$auth.loggedIn" id="menu_center">
        <ul v-if="$auth.user && $auth.user.username"> <!---при регистрации (новый клиент)-->
          <li><a v-if="!($route.path !== null && ($route.path.includes('help') || $route.path.includes('page404')))" href="javascript:window.location.href='/bills'">{{ $t('menu.bills') }}</a></li>
          <!--<li><n-link v-if="!($route.path !== null && $route.path.includes('help'))" to="/services">{{ $t('menu.services') }}</n-link></li>-->
          <li><n-link v-if="!($route.path !== null && ($route.path.includes('help') || $route.path.includes('page404')))" to="/admin/users">{{ $t('menu.users') }}</n-link></li>
          <li><n-link v-if="!($route.path !== null && ($route.path.includes('help') || $route.path.includes('page404')))" to="/admin/orders">{{ $t('menu.orders') }}</n-link></li>
          <li
            v-tooltip="{ content: $t('menu.can_add_clients'),
                            placement: 'bottom-center',
                            classes: ['info'],
                            show: show_client_notification,
                            trigger: 'manual',
                          }"
          ><n-link v-if="!($route.path !== null && ($route.path.includes('help') || $route.path.includes('page404')))"  @click="disableNotifications" to="/admin/clients">
            {{ $t('menu.clients') }}</n-link></li>
        </ul>

      </li>
        <li v-else>
              <a v-if="!($route.path !== null && (($route.path.includes('help') || $route.path.includes('page404')||$route.path.includes('login'))))" class="demo" @click="$store.commit('SET_OBJ' , {name : 'isDemoModalVisible' , value : true})" >{{ $t('menu.demo') }}</a>
        </li>
    </ul>

    <ul class="top-bar__right">
      <li v-if="!($route.path !== null && ($route.path.includes('help/about')|| $route.path.includes('page404')) || $auth.loggedIn)">
        <n-link :to="'/help/about'" class="help">{{ $t('menu.about_us')}}</n-link>
      </li>
      <li v-if="$route.path !== null && !(($route.path.includes('help') || $route.path.includes('page404'))) && !($route.path.includes('login')) && !$auth.loggedIn">
        <n-link :to="'/login'" class="login">{{ $t('menu.login')}}</n-link>
      </li>

      <li v-if="$route.path !== null && $route.path.includes('help')" class="p-2" >
        <button class="back" @click="linkOnBackPage()">{{ $t('menu.back')}}</button>
      </li>
      <li v-if="$auth.loggedIn && !$route.path.includes('page404')" @click='openMenu = !openMenu'>
        <div v-if="$auth.user && $auth.user.username" class="dropdown-block-name" ><!---при регистрации(новый клиент)-->
          <span  >{{ $auth.user.username }} </span>
          <i class="fas fa-user"></i>
          <div class="dropdown-menu" :class="{'active' : openMenu }">
            <n-link class="dropdown-item" to="/admin/profile">{{ $t('menu.profile')}}</n-link>
            <a v-if="this.$auth.user && this.$auth.user.available_teams.length > 1" class="dropdown-item" @click="$store.commit('SET_OBJ' , {name : 'showModalToSelectTeam' , value : true })">{{ $t('menu.teams')}}</a>
            <n-link class="dropdown-item" v-if="this.$auth.user && this.$auth.user.has_buyer" to="/bills/my_bills">{{ $t('menu.my_bills')}}</n-link>
            <n-link class="dropdown-item" to="/admin/settings">{{ $t('menu.settings')}}</n-link>
            <a class="dropdown-item" @click="logout" >{{ $t('menu.logout')}}</a>
          </div>
        </div>
        <div v-else>
          <img src="@/assets/img/exit.svg" style="height: 40px;" @click="logout" alt="logout">
        </div>
      </li>
      <li class="p-2" >
        <n-link :to="'/help'" class="help">{{ $t('menu.help')}}</n-link>
      </li>
      <li>
        <n-link v-if="!($route.path !== null && ($route.path.includes('help') || $route.path.includes('page404'))) && !($nuxt.$route.path !== null && $route.path.includes('register')) && !$auth.loggedIn"
         :to="'/register'" class="register">{{ $t('menu.register')}}</n-link>
      </li>
      <li class="dropdown-block p-2" v-if="locale">
        <i class="fas fa-chevron-down p-2"></i>{{locale.name}}
        <div class="dropdown-lang">
          <a v-for="locale in availableLocales" @click="switchLocalePathFallback(locale.code)" :key="locale.code">{{ locale.name }}</a>
        </div>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "TopBar",
  data : () => ({
    openLang : false,
    openMenu: false
  }),

  computed: {
    locale(){
      return this.$i18n.locales.filter(i => i.code === this.$i18n.locale)[0]
    },
    availableLocales () {
      return this.$i18n.locales.filter(i => i.code !== this.$i18n.locale)
    },
    show_client_notification(){
      return false;
    }
  },
  methods: {
    linkOnHomePage(){
      if(this.$auth.loggedIn) return '/bills'
      else return '/'
    },
    disableNotifications(){
      this.$store.commit("SET_OBJ" , {name : 'show_client_notification' , value : false})
      this.$store.dispatch('users/disable_notifications', 0)
    },

    linkOnBackPage(){
      return this.$router.back();
    },
    async logout() {
      //process.env.serverUrl = process.env.serverUrlDefault
      await this.$auth.logout()
      this.$router.push('/')
    },
    switchLocalePathFallback(code) {
      this.$i18n.locale = code;
      this.$i18n.setLocaleCookie(code);
    }
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
.top-bar {
  display: flex;
  justify-content: space-between;
  width: 100%;
  height: 70px;
  padding: 0px 40px 0px 40px;
    @media (max-width:800px) {
    padding: 0px 20px 0px 20px;
  }
  @media (max-width:600px) {
    padding: 0px 10px 0px 10px;
  }
}
.top-bar__left, .top-bar__right {
  display: flex;
  align-items: center;
}

.dropdown-menu .dropdown-item:active{
  background-color: $logo-green;
  color: #fff;
}

.top-bar__left a {
  text-decoration: none;
  color: #ffffff;
  position: relative;
  margin-right: 15px;
  & :hover{
    text-decoration: none;
  }
  @media screen and (max-width: 400px) {
     margin-right: -35px;
  }
}

/*///////////////////////menu__burger//////////////////////*/
.container-burger{
  width: 0px;
  height: 90px;
  margin-top: -20px;
  margin-left: auto;
  margin-right: auto;
  text-align: center;
  color: #ffffff;
  z-index: 9999;
}
.container-i{
  @media (max-width: 1050px) {
    display: table-column-group;
    margin-top: 40px;
  }
}
.icon-burger{
  display: none;
  @media (max-width:1050px) {
    display: block;
  }
}
.height{
  height: 60px;
}
.dropdown-burger {
  display: none;
  position: absolute;
  text-align: center;
  margin-left: -46px;
  margin-top: 90px;
  width: 310px;
  color: #ffffff;
  box-shadow: 0 7px 22px -5px rgba(0, 0, 0, .2);
  background-color: $logo-green;
  z-index: 9999;
  @media (max-width:600px) {
    display: none;
    margin-left: -46px;
    width: 264px;
  }
}
.burger-header{
  display: flex;
  color: #ffffff;
  font-weight: 600;
}
.dropdown-block-burger{
  display:flex;
  direction: ltr;
}
.dropdown-block-burger:hover .dropdown-links,
.dropdown-block-burger:hover .dropdown-burger {
  display: block;
}
.dropdown-block-burger .dropdown-burger a:hover,
.dropdown-block-burger .dropdown-burger button:hover{
  background-color: $logo-orange;
  width: 310px;
  @media (max-width:600px) {
    width: 264px;
  }
}
.container-burger:hover + .white{
  background-color: rgb(19, 146, 51);
}
@media (max-width:1050px) {
  #menu_center{
    display: none;
  }
  .dropdown-block-burger:hover{
    background-color: rgb(13, 158, 49);
    z-index: 9999;
  }
}
.dropdown-links {
  display: none;
  position: absolute;
  right: -5px;
  text-align: center;
  background-color: #ffffff;
  box-shadow: 0 7px 22px -5px rgba(0, 0, 0, .2);
}
.dropdown-burger a,
.dropdown-burger button{
  margin-left: 0;
  padding: 4px 10px;
  font-size: 22px;
  line-height: 62px;
  height: 70px;
  width: 310px;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 500;
  color: #ffffff;
  background-color: $logo-green;
  border-bottom: 1px solid rgb(185, 247, 156);
  border-top: none;
  border-left: none;
  border-right: none;
  z-index: 9999;
  @media (max-width:600px) {
    margin-left: 0;
    padding: 4px 10px;
    font-size: 20px;
    line-height: 50px;
    height: 60px;
    width: 260px;
  }
}
a:hover{
  text-decoration: none !important;
}
.user{
  border-radius: 25px;
  color: #ffffff;
  border: 1px solid #ffffff;
  height: 50px;
  min-width: 160px;
  text-align: center;
  line-height:50px;
  font-size: 22px;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
}
.m-r-30{
  margin: 20px 20px 0 20px;
}
li{
  list-style-type: none;
}
/*-----------buttons----------*/
.demo,
.login,
.help,
.register,
.back{
  @extend %all-buttons;
  border-radius: 25px;
  width: auto;
  height: 45px;
  font-size: 18px;
  padding: 0px 30px;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
}
@media (max-width:1050px) {
  .demo,
  .register,
  .help,
  .login,
  .user,
  .back{
    display: none;
  }
}
.demo{
  margin-left: 10px;
  background-color: $logo-orange;
  color: #ffffff;
  &:hover{
    background-color: #be891c;
    cursor: pointer;
   }
}
.login,
.back{
  background-color: $logo-orange;
  color: #ffffff;
  &:hover{
    background-color: #be891c;
    border-color: #be891c;
   }
}
.back{
  border: 1px solid $logo-orange;
}
.help{
  color: #ffffff;
  border: 2px solid #ffffff;
  &:hover {
    color: #e3e3e3;
    border: 2px solid #e3e3e3;
  }
}
.register{
  background-color: #ffffff;
  color: $logo-green;
  &:hover{
     background-color: #e3e3e3;
     color:#11781c;
  }
}
.logo__white{
  min-width: 170px;
  width: 170px;
  &:hover{
    -webkit-filter: brightness(85%);
    filter: brightness(85%);
  }
  @media (max-width: 1050px) {
   padding: 10px 20px;
    max-width: 250px;
    width: 100%;
    margin-left: 30px;
  }
  @media (max-width:749px) {
    -webkit-filter: none;
    filter: none;
  }
  @media (max-width:600px) {
    margin-right: 10px;
    min-width: 200px;
    width: 200px;
  }
}
/*========menu__admin=======*/
.menu__admin li a{
  &:hover{
    color:#e3e3e3;
  }
}
/*========lang========*/
.dropdown-block {
  position: relative;
  cursor: pointer;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  font-size: 17px;
  color: #ffffff;
  text-align: center;
  line-height:40px;
  border-radius: 20px 20px 0px 0px;
  @media (max-width:749px) {
    margin-right: 5%;
  }
}
.dropdown-block:hover .fa-chevron-down{
  transform: rotate(180deg);
}
.dropdown-block:hover .dropdown-links,
.dropdown-block:hover .dropdown-lang {
  display: block;
  z-index: 9999;
}
@media (max-width: 749px){
  .dropdown-block:hover .dropdown-lang{
    background-color: #ffffff;
    color: $logo-green;
  }
}
.dropdown-lang a:hover{
  background-color: #eee7e7;
}
.dropdown-lang {
  display: none;
  position: absolute;
  text-align: center;
  color: $logo-green;
  width: 70px;
  height: auto;
  background-color: #ffffff;
  box-shadow: 0 7px 22px -5px rgba(0, 0, 0, .2);
  border-radius: 0px 0px 10px 10px;
  z-index: 9999;
  @media (max-width:749px) {
    background: none;
    color: #ffffff;
    width: 66px !important;
  }
}
.dropdown-lang a {
  margin-left: 0;
  padding: 4px 10px;
  font-size: 17px;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 500;
  z-index: 9999;
}
.dropdown-lang a:nth-child(2):hover{
  background-color: #eee7e7;
  border-radius: 0px 0px 10px 10px;
}
.dropdown-links {
  display: none;
  position: absolute;
  right: -5px;
  text-align: right;
  background-color: #ffffff;
  width: 200px;
  box-shadow: 0 7px 22px -5px rgba(0, 0, 0, .2);
  z-index: 20;
}
/*=======lang end======*/

/*======= name =======*/
.dropdown-block-name {
  position: relative;
  display: flex;
  align-items: center;
  cursor: pointer;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  font-size: 17px;
  white-space: normal;
  background-color: #ffffff;
  text-align: center;
  width: 180px;
  height: 50px;
  margin-left: 0px;
  padding: 10px;
  border-radius: 10px 10px 0px 0px;
  span {
    color: $logo-green;
    line-height: 1.5;
    font-size: 15px;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
  }
    @media (max-width:500px) {
    width: 50px;
   span {
      display: none;
    }
    i{
     margin: auto;
    }
  }
}

.dropdown-name {
  display: none;
  position: absolute;
  margin-top: 320px;
  text-align: right;
  width: 180px;
  height: auto;
  background-color: #ffffff;
  box-shadow: 0 7px 22px -5px rgba(0, 0, 0, .2);
  border-radius: 0px 0px 20px 20px;
  z-index: 9999;
  @media(max-width: 1200px) {
    height: auto;
  }
  @media (max-width:749px) {
    background: none;
    color: #ffffff;
    width: 66px !important;
  }
  & a {
    margin-left: 0px !important;
    padding: 0px 10px;
    font-size: 17px;
    font-family: 'Montserrat', sans-serif !important;
    font-weight: 500;
    z-index: 9999;
    line-height: 2;
    &:hover{
      background-color: #eee7e7;
    }
  }
}
.fa-user{
  color: #ffffff;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 30px;
  height: 30px;
  border-radius: 25px;
  background-color: $logo-green;
  margin-left: auto;
  margin-right: 5px;
}
/*======= name end =======*/
.dropdown-block {
  position: relative;
  cursor: pointer;
}
.dropdown-menu {
  max-width: 180px !important;
  width: 180px;
  padding-top: 0px;
  padding-bottom: 0px;
  .dropdown-item {
    max-width: 180px;
    width: 180px;
    color: $logo-green;
    padding-right: 20px;
    font-family: 'Montserrat', sans-serif;
    font-weight: 500;
    font-size: 18px;
    height: 50px;
    display: flex;
    justify-content: right;
    align-items: center;
    margin-top: -2px;
    &:hover{
      border-bottom: 2px solid $logo-green;
      border-top: 2px solid $logo-green;
    }
    &:after{
      content: "";
      position: absolute;
      width: 100px;
      height: 50px;
      border-top: 2px solid $logo-green;
    }
    &:nth-child(1):after{
      border-top: none;
    }
    &:hover + :after{
      border-top: none;
    }
  }
}
.active{
  max-width: 180px !important;
  width: 180px;
  display:block;
}
.dropdown-lang {
  display: none;
  position: absolute;
  text-align: center;
  background-color: #ffffff;
  width: 70px;
  box-shadow: 0 7px 22px -5px rgba(0, 0, 0, .2);
  z-index: 1;
}
.dropdown-lang a {
  margin-left: 0;
  padding: 4px 10px;
}
nav ul {
  padding:0;
  margin:0;
  list-style: none;
  position: relative;
}
nav ul li {
  display:inline-block;
  float:none;
  white-space:nowrap;
}
nav a {
  display:block;
  padding:0 0;
  font-size:16px;
  line-height:30px;
  text-decoration:none;
}

</style>
