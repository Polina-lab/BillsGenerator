<template>
  <div>
    <div>
      <DemoTosModal v-if="isDemoModalVisible" :isDemoModalVisible="isDemoModalVisible" @closeModal="$store.commit('SET_OBJ' , { name : 'isDemoModalVisible' , value: false})"/>
      <div id="bg1">
        <img src="@/assets/img/header/bgImages.svg" class="bg-images">
        <div class="wrapper">
           <div class="top-bar">
            <TopBar/>
          </div>
            <div id="section1">
              <Section1/>
            </div>
        </div>
      </div> <!---bg1 --->
        <div class="container__line">
          <svg viewBox="0 20 500 500" preserveAspectRatio="xMinYMin meet">
            <path d="M0,60 C250,180 340,0 530,100 L500,00 L0,0 Z" style="stroke: none; fill:#26a647;"></path>
          </svg>
        </div>
      <!--<div id="bg_line"></div>-->
        <div id="nuxt__section" >
          <Nuxt/>
        </div>
      </div>
      <div>
        <footer class="footer" id="bg4" ><!--footer-->
          <div class="wrapper" >
            <NewFooter/>
          </div>
        </footer><!--footer-->
      </div>
    <Cookies v-if="!hasCookiesAccepted"></Cookies>
    <ToTop></ToTop>
  </div>
</template>

<script>
import Section1 from "../components/landing/Section1";
import TopBar from "@/components/common/TopBar";
  export default {
    components: {
      Section1,
      TopBar,
      DemoTosModal: () => import("@/components/landing/DemoTosModal"),
      NewFooter: () => import("@/components/common/NewFooter"),
      Cookies: () => import("@/components/landing/Cookies"),
      ToTop: () => import("@/components/common/ToTop"),
    },
    name: 'defaultLayout',
    middleware:['cookies'],
    computed:{
      isDemoModalVisible(){
        return this.$store.state.isDemoModalVisible;
      },
      hasCookiesAccepted(){
        return this.$store.state.CookiesAccepted;
      },
    },
  }
</script>
<style scoped lang="scss">
@import "./assets/scss/_var.scss";
  #bg1{
    width:100%;
    max-height: 34vw;
    min-height: 34vw;
    background-color: $logo-green;
    @media(max-width: 768px){
      max-height:  94vw;
      min-height: 94vw;
      padding-bottom: 30px;
      background-image: url('@/assets/img/header/headerMobile2.svg');
      -webkit-background-size: 100%;
      -moz-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-repeat: no-repeat;
      background-color: transparent;
    }
    @media(min-width: 1300px){
      max-height: 460px;
      min-height: 460px;
    }
    @media(max-width: 550px){
      max-height:  500px;
    }
  }
    .footer {
      position: relative;
      bottom: 0;
      height: 100%;
      min-height: 100%;
      padding-bottom: 30px;
      @media(min-width: 1400px){
       height: 30vw;
       max-height: 50vw;
      }
    }
  #bg4{
    margin-top: 70px;
    position: relative;
    z-index: 9;
    width: 100%;
    min-height: 100%;
    height: 100%;
    background-image: url('@/assets/img/bgFooterImages.svg');
    -webkit-background-size: 100%;
    -moz-background-size: 100%;
    -o-background-size: 100%;
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: top;
    background-color: $logo-green;
    -webkit-box-shadow: 2px 3px 21px 8px rgba(34, 60, 80, 0.36) inset;
    -moz-box-shadow: 2px 3px 21px 8px rgba(34, 60, 80, 0.36) inset;
    box-shadow: 2px 3px 21px 8px rgba(34, 60, 80, 0.36) inset;
    @media(min-width: 1400px){
      -webkit-background-size: 100%;
      -moz-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: right;
    }
    @media(max-width: 768px){
      background-image: none;
    }
  }
  .Section1{
    position: relative;
    z-index: 2;
  }
  .bg-images{
    position: absolute;
    display: block;
    z-index: 1;
    @media screen and (max-width: 768px){
      display: none;
    }
  }
  .css__line{
    min-height: 200px;
    width: 100%;
    display: block;
    @media screen and (min-width: 1700px){
      max-height:  10vw;
      min-height:  10vw;
    }
    @media screen and (max-width: 768px){
      display: none;
    }
  }
  svg {
    display: inline-block;
    position: absolute;
    top: 0;
    left: 0;
  }
  .container__line {
    display: inline-block;
    position: relative;
    margin-top: -8%;
    width: 100%;
    padding-bottom: 30%;
    margin-bottom: -20%;
    vertical-align: middle;
    overflow: hidden;
    max-height: 200px;
    height: 200px;
    @media screen and (max-width: 768px){
      display: none;
      }
    }
  #bg_line{
    min-height: 200px;
    width: 100%;
    background-image: url('@/assets/img/header/line_header.svg');
    background-repeat: no-repeat;
    -webkit-background-size: 100%;
    -moz-background-size: 100%;
    -o-background-size: 100%;
    background-size: 100%;
    background-position: top;
    width:100%;
    max-height:  150px;
    min-height:  150px;
    display: block;
    @media screen and (min-width: 1700px){
      max-height:  10vw;
      min-height:  10vw;
    }
    @media screen and (max-width: 768px){
      display: none;
    }
  }
  #section1{
    position: relative;
    z-index: 2;
  }
  #nuxt__section{
    position: relative;
    z-index: 3;
  }
</style>
