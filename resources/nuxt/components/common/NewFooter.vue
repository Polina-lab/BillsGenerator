<template>
    <div>

        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-5" >
                <button  v-if="($nuxt.$route.path == '/help')" class="back" @click="linkOnBackPage()">{{ $t('menu.back')}}</button>
                <button v-if="($nuxt.$route.path == '/')" class="try__demo" @click="$store.commit('SET_OBJ' , {name : 'isDemoModalVisible' , value : true})">{{ $t('menu.try_live') }}</button>
            </div>
        </div>

        <div class="container-fluid">

            <div class="row reverse">
                <div class="col-12 col-md-6">
                    <h4 id="header-contact" class="text-center">{{ $t('menu.contact_us')}}</h4>
                    <div class="company-contacts mt-2">
                        <div class="row">
                            <div class="col-6 col-sm-6 text-left pl-5">
                                Glo Real Network OÜ<br>
                                Estonia pst 5, III {{ $t('menu.korrus')}}<br>
                                {{ $t('menu.tallinn_harjumaa')}}<br>
                                {{ $t('bills.email')}}: info@gloreal.ee<br>
                                {{ $t('bills.reg')}}: 14045466<br>
                            </div>
                            <div class="col-6 col-sm-6 text-right pr-5">
                                <br>
                                {{ $t('bills.phone')}}:<br> +372 555 99 313<br>
                                www.gloreal.ee
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                        <h3 id="header-social" class="text-center">{{ $t('menu.join_us') }}</h3>
                        <div class="mt-1">
                            <div class="social" >
                                <a href="https://www.instagram.com/gloreal_estate/" target="_blank"><img class="image__icons" src="@/assets/img/about/insta.svg" alt="Instagram icon."></a>
                                <a href="https://www.facebook.com/glorealkinnisvara/" target="_blank"><img class="image__icons" src="@/assets/img/about/messenger.svg" alt="Messenger icon."></a>
                                <a href="https://www.facebook.com/glorealkinnisvara/" target="_blank"><img class="image__icons" src="@/assets/img/about/facebook.svg" alt="Facebook icon."></a>
                            </div>
                            <div class="social-buttons row mt-5">
                                    <n-link  to="/login" class="login">{{ $t('menu.login')}}</n-link>
                                    <n-link  to="/register" class="register" :class="{'blue' : ($nuxt.$route.path == '/help') }">{{ $t('menu.register')}}</n-link>
                            </div>
                        </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <n-link to="/"><img class="logo__white pb-4" src="@/assets/img/footer/logoFooter2.svg" alt="Logo"/></n-link>
                    <div class="row justify-content-around privacy__box">
                        <n-link class="help" to="/help">{{ $t('menu.help')}}</n-link>
                        <n-link class="privacy" to="/privacy">{{ $t('menu.privacy_policy')}}</n-link>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
export default {
    name: "NewFooter",
    methods:{
      linkOnBackPage(){
        return this.$router.back();
        }
    }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/_var.scss";
#header-contact{
  white-space: nowrap;
  text-align: center;
  color: #ffffff;
  font-size: 30px;
  justify-content: flex-end;
  font-family: 'Montserrat', sans-serif;
  font-weight: 600;
  padding-bottom: 40px;
  @media screen and (max-width: 768px){
    padding-bottom: 20px;
    padding-top: 80px;
    font-size: 5vw;
  }
}
 #header-social{
  text-align: center;
  color: #ffffff;
  font-size: 28px;
  justify-content: flex-end;
  font-family: 'Montserrat', sans-serif;
  font-weight: 600;
  padding-bottom: 20px;
  @media screen and (max-width: 768px){
    font-size: 5vw;
  }
}
.try__demo,
.back{
  position: relative;
  background-color: #fdb92d;
  border-radius: 10vw;
  border: 2px solid #fdb92d;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: bold;
  justify-content: center;
  align-items: center;
  color: #ffffff !important;
  font-size: 28px;
  text-decoration: none;
  height: 70px;
  padding: 0px 50px;
  width: auto;
  text-align: center;
  line-height: 1;
  margin-left: -54vw;
  margin-top: -30px;
  z-index: 5;
  position: relative;
  white-space: nowrap;
  @media screen and (max-width: 768px) {
    margin-left: auto;
    margin-right: auto;
    height: 10vw;
    width: auto;
    font-size: 4.5vw;
    padding: 0vw 5vw;
    border-radius: 10vw;
    box-shadow: 0px 20px 15px 5px rgba(0, 0, 0, 0.4);
  }
    &:hover {
      background-color: rgb(190, 137, 28);
      border-color: rgb(190, 137, 28);
  }
}
.login{
  @extend %all-buttons;
  border-radius: 10vw;
  background-color: $logo-orange;
  color: #ffffff;
  height: 50px;
  width: 160px;
  text-align: center;
  font-size: 1.5vw;
  padding: 0px 30px;
  font-family: 'Montserrat', sans-serif;
  font-weight: 600;
   @media screen and (min-width: 1300px) {
     font-size: 20px;
   }
  @media screen and (max-width: 768px) {
    font-size: 4.5vw;
    height: 10vw;
    width: auto;
    padding: 0px 50px;
    padding: 0vw 6vw;
    box-shadow: 0px 20px 15px 5px rgba(0, 0, 0, 0.4);
  }
  &:hover{
  background-color: #be891c;
  cursor: pointer;
  text-decoration: none;
}
}
.text-right,
.text-center{
   display: flex-box;
   margin-top: auto;
   margin-bottom: 0px;
}
.help,
.privacy{
    color: #ffffff;
    @media screen and (max-width: 768px){
      font-size: 4vw;
    }
}
.help:hover,
.privacy:hover{
  color: #e3e3e3;
  cursor: pointer;
  text-decoration: none;
}
.register{
  @extend %all-buttons;
  border-radius: 10vw;
  background-color: #ffffff;
  color: #26a647;
  height: 50px;
  width: 160px;
  text-align: center;
  font-size: 1.5vw;
  padding: 0px 30px;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  z-index: 5;
   @media screen and (min-width: 1300px) {
     font-size: 20px;
   }
  @media screen and (max-width: 768px) {
    font-size: 4.5vw;
    height: 10vw;
    width: auto;
    padding: 0vw 4vw;
    box-shadow: 0px 20px 15px 5px rgba(0, 0, 0, 0.4);
  }
  &:hover{
    background-color: #e3e3e3;
    color:#11781c;
    text-decoration: none;
    cursor: pointer;
  }
}
.social-buttons{
  display: flex;
  direction: ltr;
  max-width: 70%;
  margin-left: auto;
  margin-right: auto;
  justify-content:space-evenly;
  z-index: 99;
  flex-wrap: nowrap;
  @media screen and (max-width: 768px) {
    min-width: 100%;
    width: 100%;
    justify-content:space-around;
    flex-wrap: nowrap;
  }
}
.text-left,
.text-right{
    color: #ffffff;
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    white-space: nowrap;
    @media screen and (max-width: 900px) {
      font-size: 14px;
      line-height: 1.7;
  }
    @media screen and (max-width: 768px) {
    font-size: 3.5vw;
    line-height: 1.4;
  }
}
.logo__white{
    width: 17vw;
    margin-top: 30px;
  @media screen and (min-width: 1300px) {
    width: 230px;
  }
  @media screen and (max-width: 768px) {
    margin-top: 10vw;
    width: 40vw;
  }
}
.reverse{
  position: relative;
  display: flex;
  flex-direction: row;
  margin-top: 80px;
    @media screen and (max-width: 768px){
        display: flex;
        flex-direction: column-reverse;
    }
}
.social{
  justify-content:center;
  display: flex;
  direction: ltr;
  z-index: 99;
    & a{
        display: inline-block;
        color: #ffffff;
        width: 100px;
        height: 50px;
        border-radius: 50%;
        position: relative;
        text-align: center;
        transition: .3s color ease;
        @media screen and (max-width: 768px){
           height: 12vw;
            width: 17vw;
          }
        }
    & a:hover{
            color: #e3e3e3;
        }
    & .image__icons{
        max-width: 47px;
        width: 47px;
        filter: invert(98%) sepia(5%) saturate(305%) hue-rotate(127deg) brightness(117%) contrast(100%);
        @media screen and (max-width: 768px){
            max-width: 10vw;
            width: 10vw;
            margin: 20px;
          }
        }
    & .image__icons:hover{
        filter: invert(90%) sepia(0%) saturate(176%) hue-rotate(157deg) brightness(101%) contrast(70%);
      }
    }
.help__social{
  margin-top: 250px;
}
.privacy__box{
  max-width: 20%;
  position: relative;
  margin-left: auto;
  margin-right: auto;
  letter-spacing: 2px;
  padding-left: 15px;
  @media screen and(max-width:1000px){
    max-width: 30%;
  }
  @media screen and(max-width:768px){
    max-width: 60%;
  }
}
.blue{
  color: $logo-blue;
}
.blue:hover{
    color: rgb(24, 94, 120);
  }

</style>