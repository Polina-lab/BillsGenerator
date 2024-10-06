<template>
    <div>
        <div v-if="($nuxt.$route.path == '/help')" id="help">
        <img src="@/assets/img/header/bgImages.svg" class="bg-images">
            <div class="wrapper top-bar">
                <TopBar/>
            </div>
        </div>

        <div v-if="($nuxt.$route.path == '/help/about')" id="about">
        <img src="@/assets/img/header/bgImages.svg" class="bg-images">
            <div class="wrapper top-bar">
              <TopBar/>
            </div>
        </div>

        <div id="bg_line"></div>
          <div class="wrapper">
            <Nuxt/>
          </div>
        <div v-if="($nuxt.$route.path == '/help/about')" class="bg__white">
          <div class="wrapper">
            <People></People>
          </div>
        </div>



        <div v-if="($nuxt.$route.path == '/help')" id="HelpTabs">
            <HelpTabs/>
        </div>

          <footer v-if="($nuxt.$route.path == '/help')" class="footer" id="bg5">
            <div class="wrapper" >
              <NewFooter/>
            </div>
          </footer>

          <footer v-if="($nuxt.$route.path == '/help/about')" class="footer about__footer"  >
            <div id="about__bg4">
            </div>
            <div class="new__footer">
              <div class="container">
                    <a href="https://www.google.com/maps/place/ESTONIA+PST.+5/@59.4344687,24.7495197,17z/data=!3m1!4b1!4m5!3m4!1s0x469293603d53eb33:0xb74b792866d0af3d!8m2!3d59.4344687!4d24.7517084" target="_blank"><div class="marker" aria-label="Go to google maps"></div></a>
                <div class="form__box">
                  <article>
                    <h1 id="ask" @click='openDiv = !openDiv'>{{ $t('about.ask') }}<i v-if="!openDiv" class="fas fa-caret-right fa-2x closed__form"></i><i v-else class="fas fa-times opened__form"></i></h1>
                    <div class="form-group" :class="{'active' : openDiv }">
                      <input type="text" class="form-control" v-model="feedback.name" id="inputName" aria-describedby="emailHelp" :placeholder="$t('about.your_name')">
                    </div>
                    <div class="form-group" :class="{'active' : openDiv }">
                      <input type="email" maxlength="30" v-model="feedback.email" class="form-control" id="inputEmail" aria-describedby="emailHelp" :placeholder="$t('about.your_email')">
                    </div>
                    <div class="form-group" :class="{'active' : openDiv }">
                      <textarea class="form-control" id="formControlTextarea" v-model="feedback.text" type="text" :maxlength="limit" :placeholder="$t('about.your_message')" rows="4" ></textarea>
                    </div>
                    <div class="form-group" :class="{'active' : openDiv }">
                      <button  @click="sendMail" class="btn btn-primary" id="formControlButton">{{ $t('about.send') }}</button>
                    </div>
                  </article>
                </div>
              </div>
              <div class="wrapper">
                <NewFooter/>
              </div>
            </div>
          </footer>
      <Cookies v-if="!hasCookiesAccepted"></Cookies>
      <ToTop></ToTop>
    </div>
</template>
<script>
import ToTop from "@/components/common/ToTop";
import TopBar from "@/components/common/TopBar";
import HelpTabs from "@/components/landing/HelpTabs";
import NewFooter from "@/components/common/NewFooter";
import People from "@/components/about/People";
import isEmailValid from '../mixins/isEmailValid';

  export default {
    components:{ TopBar ,HelpTabs, NewFooter, People, ToTop },
    name: 'help',
    mixins: [ isEmailValid ],
    middleware:['cookies'],
      data() {
    return {
      openDiv: false,
      limit: 200,
      feedback: {
        email: '',
        name: '',
        text: ''
      },
    };
  },
    computed:{
      hasCookiesAccepted(){
        return this.$store.state.CookiesAccepted;
      },
    },
    methods:{
      async sendMail(){
        if(this.isEmailValid(this.feedback.email) && this.feedback.name > 3 && this.feedback.text > 10 ) {
        await this.$axios.post(`${process.env.serverUrl}/api/feedback`, this.feedback).then(
          res => {
            this.$toast.success(res.msg).goAway(2500);
            console.log('Sucsess');
          },
          error => {
            throw error;
          }).catch((error) => {
          if (error.response) {
            this.$toast.error(error.message).goAway(2500);
          } else {
            console.log('Error', error.message);
          }
        });
      }else{
        this.$toast.error("Please enter email").goAway(2500);
        }
      }
    }
  }

</script>
<style scoped lang="scss">

@import "./assets/scss/_var.scss";
.wrapper{
    max-width: 1300px;
}
   #help{
    background-color: $logo-green;
    height: 450px;
    position: relative;
    width:100%;
    @media(max-width: 768px){
      height: 750px;
      background-image: url('@/assets/img/header/headerMobile2.svg');
      -webkit-background-size: 100%;
      -moz-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-repeat: no-repeat;
       background-color: transparent;
    }
  }
  .bg__white{
    position: relative;
    z-index: 10;
    background-color: #ffffff;
  }
  #about{
    background-color: $logo-green;
    height: 450px;
    position: relative;
    width:100%;
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
  }
  #HelpTabs{
    height: 100%;
    position: relative;
    z-index: 10;
    @media screen and(min-width: 1300px) {
      background-image: linear-gradient(185deg, rgb(38, 166, 71) 10%, rgb(3, 186, 131), rgb(1, 158, 216), rgb(127, 206, 235));
      -webkit-background-size:  contain;
      -moz-background-size:  contain;
      -o-background-size:  contain;
      background-size:  contain;
      background-repeat: no-repeat;
      margin-top: 40px;
    }
    @media screen and(min-width: 500px) {
        margin-top: -40px;
    }
  }
    #about__bg4{
    position: relative;
    z-index: 9;
    width: 100%;
    max-width: 1300px;
    margin-right: auto;
    margin-left: auto;
    min-height: 40vw;
    margin-top: -5vw;
    background-image:url('@/assets/img/about/map_desktop.svg'),url('@/assets/img/about/map_desktop.png');
    -webkit-background-size: 100%, 100%;
    -moz-background-size:100%, 100%;
    -o-background-size:100%, 100%;
    background-size:100%, 100%;
    background-repeat: no-repeat;
    background-position:  bottom, bottom;
    @media (min-width: 1400px) and (max-width: 1799px){
       margin-top: -10%;
    }
    @media screen and (min-width: 1800px){
      max-height: 400px;
      margin-top: -20%;
    }
    @media(max-width: 1000px){
      margin-top: 0vw;
    }
   @media(max-width: 749px){
      margin-top: -10vw;
      min-height: 0px;
      background-image: none;
    }
  }
  .new__footer{
    background-color: $logo-green;
    position: relative;
    min-height: 100%;
    padding-bottom: 20px;
    -webkit-box-shadow: 0px 15px 15px 1px rgba(34, 60, 80, 0.2) inset;
    -moz-box-shadow: 0px 15px 15px 1px rgba(34, 60, 80, 0.2) inset;
    box-shadow: 0px 15px 15px 1px rgba(34, 60, 80, 0.2) inset;
    background-image:url('@/assets/img/bgFooterImages.svg');
    -webkit-background-size: 100% 100%;
    -moz-background-size: 100% 100%;
    -o-background-size: 100% 100%;
    background-size: 100% 100%;
    background-position: right bottom;
    background-repeat: no-repeat;
    @media(max-width: 749px){
      background-image: none;
      -webkit-box-shadow: none;
      -moz-box-shadow: none;
      box-shadow: none;
      padding-top: 8vw;
    }
  }
  #bg5{
    position: relative;
    z-index: 15;
    background-image: url('@/assets/img/bgFooterImages.svg');
    -webkit-background-size: 100%;
    -moz-background-size: 100%;
    -o-background-size: 100%;
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: top;
    background-color: $logo-blue;
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
    .footer {
      bottom: 0;
      height: 100%;
      min-height: 100%;
      padding-bottom: 30px;
      @media(max-width: 749px){
        padding-top: 8vw;
      }
    }
.marker{
  position: absolute;
  margin-top: -25vw;
  left: auto;
  right: 22vw;
  width: 6vw;
  height: 6vw;
  border-radius: 50%;
  z-index: 9999;
  &:hover{
    width: 6vw;
    height: 6vw;
    background-color: rgba(223, 57, 57, 0.4);
    border: 7px solid rgba(223, 57, 57, 0.4);
    box-shadow:
      0 0 0 15px rgba(214, 86, 86, 0.4),
      0 0 0 25px rgba(221, 128, 128, 0.3);
    @media screen and (min-width: 2000px){
      width: 5vw;
      height: 5vw;
      border: 5px solid rgba(223, 57, 57, 0.4);
    }
  }
  @media screen and (min-width: 1600px){
    position: relative;
    width: 5vw;
    height: 5vw;
    margin-top: -344px;
    right: auto;
    margin-left: 817px;
  }
  @media screen and (min-width: 1400px) and (max-width: 1599px){
    position: relative;
    width: 5vw;
    height: 5vw;
    margin-top: -338px;
    right: auto;
    margin-left: 823px;
  }
  @media screen and (max-width: 749px){
    display: none;
  }
}
[aria-label] {
    position: absolute;
    cursor: help;
}
[aria-label]:after {
    content: attr(aria-label);
    display: none;
    position: absolute;
    top: 100%;
    left: 10px;
    z-index: 20;
    padding: 14px;
    line-height: 15px;
    white-space: nowrap;
    color: #000000;
    background-color: #62ccbc;
    border-radius: 5px;
}
[aria-label]:hover:after, [aria-label]:hover:before {
    display: block;
}
 .form__box{
  position: absolute;
  height: 335px;
  min-height: 335px;
  max-height: 335px;
  margin-top: -335px;
  z-index: 30;
  margin-left: -1vw;
  padding: 5px 40px 70px 40px;
  width: 44%;
  max-width: 570px;
  border-radius: 10px 10px 0px 0px;
  background-color: rgba(255, 255, 255, 0.9);
  @media screen and (max-width: 849px){
    height: 300px;
    min-height: 300px;
    max-height: 300px;
    margin-top: -300px;
  }
  @media screen and (max-width: 749px){
    height: inherit;
    min-height: inherit;
    max-height: inherit;
    margin-top: -3vw;
    position: relative;
    margin-left: auto;
    margin-right: auto;
    border-radius: 10px;
    padding: 5px 40px 10px 40px;
    width: 100%;
    z-index: 100;
    background-color: rgba(255, 255, 255, 1);
    -webkit-box-shadow: -6px -4px 47px 13px rgba(34, 60, 80, 0.5);
    -moz-box-shadow: -6px -4px 47px 13px rgba(34, 60, 80, 0.5);
    box-shadow: -6px -4px 47px 13px rgba(34, 60, 80, 0.5);
  }
  @media screen and (max-width: 500px){
    margin-left: 0;
    width: 96%;
  }
}
.form-control{
  border-radius: 30px;
  background-color: $secondary-light;
  margin-top: 20px;
  @media screen and (max-width: 749px){
    min-height: 60px;
  }
}
#formControlTextarea{
    position: relative;
    margin-bottom: 24px;
  @media screen and (max-width: 849px){
    line-height: 1;
  }
    @media screen and (max-width: 749px){
    min-height: 160px;
    line-height: 1;
  }
}
#formControlButton{
  display: flex;
  border-radius: 40px;
  justify-content: center;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 600;
  min-height: 40px;
  line-height: 0.8;
  background-color: $logo-orange;
  border: 2px solid $logo-orange;
  color: #ffffff;
  width: 50%;
  float: right;
  letter-spacing: 1.6px;
  @media screen and (max-width: 1100px){
    width: 100%;
    float: none;
  }
  @media screen and (max-width: 749px){
    font-size: 24px;
    min-height: 60px;
  }
}
#formControlButton:hover,
#formControlButton.active,
#formControlButton:focus{
  background-color: #be891c !important;
  border-color: #be891c !important;
  outline: 3px solid #be891c !important;
}
#ask{
  display: flex;
  align-items: center;
  justify-content: left;
  min-height: 40px;
  color: $logo-green;
  font-size: 2vw;
  font-weight: 600;
  line-height: 1.5;
  letter-spacing: 1.6px;
  @media screen and (min-width: 1300px){
    font-size: 26px;
  }
  @media screen and (max-width: 1100px){
    text-align: center;
  }
  @media screen and (max-width: 749px){
    font-size: 32px;
  }
  @media screen and (max-width: 650px){
    justify-content: center;
  }
  &:hover{
    color: #9b9b9b;
  }
}
#ask:hover .closed__form{
  transform: translate(5%, 5%) rotate(90deg);
  color: #9b9b9b;
}
.closed__form{
  display: none;
  @media screen and (max-width: 749px){
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding-left: 25px;
  }
}
.opened__form{
    display: none;
  @media screen and (max-width: 749px){
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding-top: 10px;
    padding-left: 25px;
  }
}
.form-group{
    display: block;
  @media screen and (max-width: 749px){
    display: none;
  }
}
.active{
  @media screen and (max-width: 749px){
    display: block;
  }
}
.bg-images{
  position: absolute;
  display: block;
  z-index: 1;
  @media screen and (max-width: 768px){
    display: none;
  }
}

#bg_line{
    min-height: 200px;
    background-image: url('@/assets/img/header/line_header.svg');
    background-repeat: no-repeat;
    -webkit-background-size: 100%;
    -moz-background-size: 100%;
    -o-background-size: 100%;
    background-size: 100%;
    background-position: top;
    width:100%;
    margin-left: 5px;
    max-height:  150px;
    min-height:  150px;
    display: block;
    overflow: hidden;
    @media screen and (min-width: 1700px){
      max-height:  10vw;
      min-height:  10vw;
    }
    @media screen and (max-width: 768px){
      display: none;
    }
  }
  </style>
