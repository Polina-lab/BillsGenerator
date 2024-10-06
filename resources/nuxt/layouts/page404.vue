<template>
    <div class="page">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

            <div id="error">
                <div class="wrapper">
                    <TopBar/>
                    <Nuxt/>
                    <footer class="footer" id="bg6" >
                        <div class="wrapper" >
                            <NewFooter/>
                        </div>
                    </footer>
                </div>
            </div>    
                
    </div>
</template>
<script>
import TopBar from "@/components/common/TopBar";
import NewFooter from "@/components/common/NewFooter";
export default {
    components:{ TopBar, NewFooter },
        computed:{
      hasCookiesAccepted(){
        return this.$store.state.CookiesAccepted;
      },
    },
    methods: {
        linkOnBackPage(){
      return this.$router.back();
    }
  } 
}
</script>
<style scoped lang="scss">
@import "./assets/scss/_var.scss";
html,body{
  height: 100%;
  margin: 0;
}
.page{
  overflow-y:hidden;
}
.wrapper{
    max-width: 1300px;
}
  #error{
    position: relative;
    z-index: 10;
    width:100%;
    height: 100vh;
    min-height: 1000px;
    background-color: $logo-green;
    background-image: url('@/assets/img/header/bgImages.svg'),url('@/assets/img/header/Header_404.svg'),url('@/assets/img/whitespace404.svg');
    -webkit-background-size: contain, contain, contain;
    -moz-background-size: contain, contain, contain;
    -o-background-size: contain, contain, contain;
    background-size: contain, contain, contain;
    background-repeat: no-repeat;
    background-position: top, top, top;
    @media(min-width: 1300px){
      background-position: top, top, top;
      -webkit-background-size: contain, contain, contain;
      -moz-background-size: contain, contain, contain;
      -o-background-size: contain, contain, contain;
      background-size: contain, contain, contain;
    }
    @media(max-width: 800px){
      background-image: url('@/assets/img/header/Header_404.svg'),url('@/assets/img/whitespace404.svg');
      background-position: top, top;
      -webkit-background-size: contain, contain, contain;
      -moz-background-size: contain, contain, contain;
      -o-background-size: contain, contain, contain;
      background-size: contain, contain, contain;
    }
  }
.footer {
      position: relative;
       margin-top: 10vw;
       @media screen and (min-width: 2200px){
           margin-top: 50%;
       }
      @media screen and (min-width: 1500px) and (max-width: 2199px){
           margin-top: 35%;
       }
       @media screen and (min-width: 1400px) and (max-width: 1499px){
           margin-top: 20%;
       }
       @media screen and (max-width: 1100px){
           margin-top: 50px;
       }
       @media screen and (max-width: 900px){
          margin-top: 0px;
       }
    }
</style>
