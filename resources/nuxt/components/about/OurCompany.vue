<template>
<div>
  <div class="box" ref="about">
    <div class="companies__teams">
        <h1 id="header__company">{{ $t('about.our') }}</h1>

        <div class="row justify-content-center mt-3 mt-md-0 ring__box">
          <option v-for="(i, idx) in 4" 
                  :key="i" 
                  class="ring" 
                  :class="{ 'active__green': (idx === selectedCompanyIndex) }"
                  @click="changeStage(idx, 'company')">
          </option>
        </div>

        <div class="row align-items-center">
          <div class="col-2 col-sm-1 text-center">
            <i class="fas fa-arrow-left fa-3x green"></i>
          </div>
          <swiper @slideChange="slideChangeHandler('company')"
                  class="swiper-container col-8 col-sm-10" ref="companySwiper" :options="swiperOptionsCompany">
            <swiper-slide class="grid__green" v-for="(company) in companies" :key="company.id">
              <div class="grid__object">
                <div class="img__box">
                  <img class="grid__img" :src="getImage(company.url)" alt="">
                </div>
                <h2 class="grid__header">{{ company.header }}<span> . </span></h2>
                <div class="grid__text">{{ company.text }}</div>
                <div class="additional__text" v-if="selectedGreen === company.id">
                  {{ company.additional__text }}
                </div>
                <button @click.stop="openGreenText(company.id)" class="show__text" :class="{open: selectedGreen === company.id }">{{ $t('common.read_more') }}...</button>
                <button  @click="openGreenText(company.id)" class="hide__text" :class="{open: selectedGreen === company.id }">{{ $t('common.hide_text') }}</button>
              </div>
            </swiper-slide>
            <pre style="display: hidden">{{ isMobile }}</pre>
          </swiper>

          <div class="col-2 col-sm-1 text-center">
            <i class="fas fa-arrow-right fa-3x green"></i>
          </div>
        </div>

        <h1 id="header__teams">{{ $t('about.our_team') }}</h1>

        <div class="row justify-content-center mt-3 mt-md-0 ring__box">
          <option v-for="(i, idx) in 4" 
                  :key="i" 
                  class="ring" 
                  :class="{ 'active__blue': (idx === selectedTeamIndex) }"
                  @click="changeStage(idx, 'team')">
          </option>
        </div>

        <div class="row align-items-center">
          <div class="col-2 col-sm-1 text-center">
            <i class="fas fa-arrow-left fa-3x blue"></i>
          </div>
          <swiper @slideChange="slideChangeHandler('team')" 
                  class="swiper-container col-8 col-sm-10" ref="teamSwiper" :options="swiperOptionsTeam">
            <swiper-slide class="grid__blue" v-for="(team) in teams" :key="team.id">
              <div class="grid__object">
                <div class="img__box">
                  <img class="grid__img" :src="getImage(team.url)" alt="">
                </div>
                <h2 class="grid__header">{{ team.header }}<span> . </span></h2>
                <div class="grid__text">{{ team.text }}</div>
                <div class="additional__text" v-if="selectedBlue === team.id">
                  {{ team.additional__text }}
                </div>
                <button @click="openBlueText(team.id)" class="show__text" :class="{open: selectedBlue == team.id }">{{ $t('common.read_more') }}...</button>
                <button @click="openBlueText(team.id)" class="hide__text" :class="{open: selectedBlue == team.id }">{{ $t('common.hide_text') }}</button>
              </div>
            </swiper-slide>
            <pre style="display: hidden">{{ isMobile }}</pre>
          </swiper>

          <div class="col-2 col-sm-1 text-center">
            <i class="fas fa-arrow-right fa-3x blue"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
    name: 'OurCompany',
    data() {
      let swiperOptions = {
        slidesPerView: 1,
        spaceBetween: 60,
        centeredSlides: false,
        loop: true,
        cash: true,
        grabCursor: true,
        autoHeight: true,
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        }
      }
      return {
        isActive: false,
        selectedGreen: null,
        selectedBlue: null,
        selectedCompanyIndex: 0,
        selectedTeamIndex: 0,
        companies:[
            {
              id: 1,
              url: "objective.svg",
              header: "objective",
              text: "Your don't need special accounting or finansial skills to use our service",
              additional__text: "Your don't need special accounting or finansial skills to use our service"
            },
            {
              id: 2,
              url: "mission.svg",
              header: "mission",
              text: "Your don't need special accounting or finansial skills to use our service",
              additional__text: "Your don't need special accounting or finansial skills to use our service"
            },
            {
              id: 3,
              url: "legend.svg",
              header: "legend",
              text: "Your don't need special accounting or finansial skills to use our service",
              additional__text: "Your don't need special accounting or finansial skills to use our service"
            },
            {
              id: 4,
              url: "strategy.svg",
              header: "strategy",
              text: "Your don't need special accounting or finansial skills to use our service",
              additional__text: "Your don't need special accounting or finansial skills to use our service"
            }
        ],
        teams:[
            {
              id: 1,
              url: "teamwork.svg",
              header: "teamwork",
              text: "Your don't need special accounting or finansial skills to use our service",
              additional__text: "Your don't need special accounting or finansial skills to use our service"
            },
            {
              id: 2,
              url: "potential.svg",
              header: "potential",
              text: "Your don't need special accounting or finansial skills to use our service",
              additional__text: "Your don't need special accounting or finansial skills to use our service"
            },
            {
              id: 3,
              url: "creative.svg",
              header: "creative",
              text: "Your don't need special accounting or finansial skills to use our service",
              additional__text: "Your don't need special accounting or finansial skills to use our service"
            },
            {
              id: 4,
              url: "focus.svg",
              header: "focus",
              text: "Your don't need special accounting or finansial skills to use our service",
              additional__text: "Your don't need special accounting or finansial skills to use our service"
            }
        ],
      
        swiperOptionsCompany: {
          ...swiperOptions,
          navigation: {
            nextEl: '.fa-arrow-right.green',
            prevEl: '.fa-arrow-left.green'
          },
        },
        swiperOptionsTeam: {
          ...swiperOptions,
          navigation: {
            nextEl: '.fa-arrow-right.blue',
            prevEl: '.fa-arrow-left.blue'
          }
        }
      }
    },
    computed: {
      isMobile() {
        if (process.client) {
          if (window.innerWidth < 750) {
            this.swiperOptionsCompany.slidesPerView = 1;
            this.swiperOptionsTeam.slidesPerView = 1;
          } else {
            this.swiperOptionsCompany.slidesPerView = 4;
            this.swiperOptionsTeam.slidesPerView = 4;
            if('ontouchstart' in window){  //if it is posible to use
            this.swiperOptionsTeam.loop = true; //if true, the text is blurry
            this.swiperOptionsCompany.loop = true; 
            }else{
            this.swiperOptionsTeam.loop = false; 
            this.swiperOptionsCompany.loop = false;
            }
          }
        } else {
          return false;
        }
      }
    },

    methods:{
      openGreenText(selectedItem){
         if(this.selectedGreen === selectedItem )
            this.selectedGreen = null
          else
          this.selectedGreen = selectedItem;
          this.selectedBlue = null;
      },
      openBlueText(selectedItem){
         if(this.selectedBlue === selectedItem )
            this.selectedBlue = null
          else
          this.selectedBlue = selectedItem;
          this.selectedGreen = null;
      },
      getImage(url) {
        return require('@/assets/img/about/icons/' + url);
        },

      slideChangeHandler(swiper_name) {
        switch (swiper_name) {
          case 'company':
            this.selectedCompanyIndex = this.$refs.companySwiper.$swiper.realIndex;
            break;
          case 'team':
            this.selectedTeamIndex = this.$refs.teamSwiper.$swiper.realIndex;
            break;
          default:
            break;
        }
      },

      changeStage(e, swiper_name) {
        this.$refs[swiper_name + 'Swiper'].$swiper.slideToLoop(e, 500, false);
        this.slideChangeHandler(swiper_name);
      },
    }
}
</script>
<style lang="scss" scoped>
@import "./assets/scss/_var.scss";
.box{
  width: 100%;
}
.companies__teams{
    font-family: 'Montserrat', sans-serif;
    margin-bottom: 50px;
    @media screen and (max-width: 749px){
       margin: 80px 30px 30px 30px;
    }
}
.ring__box{
  position: relative;
  margin-right: auto;
  margin-left: auto;
  max-width: 280px;
}
.ring{
  display: flex;
  width: 17px;
  height: 8px;
  background-color: #e3e3e3;
  border-radius: 50px;
  border: none;
  display: none;
  @media screen and (max-width: 749px){
    display: flex;
    margin: -20px auto -40px auto;
  }
}
.active__green{
  background-color: $logo-green;
}
.active__blue{
  background-color: $logo-blue;
}
#header__company{
    color: $logo-green;
}
#header__teams{
    color: $logo-blue;
    text-align: center;
    font-size: 30px;    
    margin-top: 120px;
    @media screen and (max-width: 749px){
      margin-bottom: 50px;
      margin-top: 0px;
      font-size: 38px;
      font-weight: 600;
    }
}
#header__company{
    text-align: center;
    font-size: 30px;
    @media screen and (max-width: 749px){
      margin-bottom: 50px;
      font-size: 38px;
      font-weight: 600;
    }
}
.grid__green,
.green{
    color: $logo-green;
}
.grid__blue,
.blue{
     color: $logo-blue;
}
.grid__green,
.grid__blue{
  position: relative;
  display: grid;
  height: 35vw;
  margin-top: 15%;
  grid-template-rows: 100px;
  grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
  .img__box{
    position: relative;
    background-color: #ffffff;
    margin-left: auto;
    margin-right: auto;
    width: auto;
    min-height: 110px;
    @media screen and (max-width: 749px){
      height: 130px;
    }
    .grid__img{
      position: absolute;
      top:80%;
      left:50%;
      transform:translate(-50%,-50%);
      width:auto;
      height: 80%;
    }
  }
    .grid__header{
        text-align: center;
        font-size: 23px;
        text-transform: uppercase;
        margin: 20px 0px;
        white-space: nowrap;
        span{
            color: $logo-orange;
            font-size: 40px;
        }
        @media screen and (max-width: 749px){
          font-size: 4.5vw;
        }
    }
    .grid__text,
    .additional__text{
        display: flex;
        text-align: center;
        font-size: 14px;
        height: auto;
        @media screen and (max-width: 749px){
          font-size: 4vw;
          line-height: 1.3;
        }
    }
    .additional__text{
      margin-top: 0px;
      padding-top: 0px;
    }
    button{
        display: flex;
        margin-right: auto;
        margin-left: auto;
        background-color: #ffffff;
        border: none;
        text-decoration: underline;
        color: inherit;
        font-size: 15px;
        font-weight: 600;
        @media screen and (max-width:749px) {
          font-size: 25px;
        }
    }
    button:active,
    button:focus{
      outline: 0 !important;
    }
    .hide__text{
        display: none;
    }
    .hide__text.open{
        display: flex;
        margin-top: 20px;
        margin-bottom: 30px;
    }
    .show__text{
        margin-top: 20px;
    }
    .show__text.open{
        display: none;
    }
    @media screen and (min-width: 1300px){
      height: 400px;
    }
    @media screen and (max-width: 991px){
       margin-top: 20%;
       height: 400px;
    }
    @media screen and (max-width: 749px){
       display: flex;
       height: 500px;
       padding: 0px 40px 0px 40px;
       margin-top: 25px;
    }
}

.grid__object{
    display: flex;
    flex-direction: column;
    justify-content: center;
    max-width: 100%;
}
.fa-arrow-left,
.fa-arrow-right{
  display: none;
  @media screen and(max-width: 749px){
    display: block;
  }
}
.visible{
  @media screen and(max-width: 749px){
    display: block;
  }
}
.hidden{
  @media screen and(max-width: 749px){
    display: none;
  }
}

.swiper-pagination {
  bottom: 95% !important;
}
</style>