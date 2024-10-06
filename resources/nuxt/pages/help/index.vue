<template>
    <div>
        <div id="help_bg1">
            <h1 id="help_header">FAQ</h1>
            <p id="help_content">{{ $t('menu.help_content') }}</p>
            <div class="question__mark"><img id="question" src="@/assets/img/header/questionmark.svg" alt="Question-mark."></div>
            <div class="slogan__box">
              <h2 id="slogan" :class="language(i18n)">{{ $t('menu.slogan') }}</h2>
            </div>
        </div>
    </div>
</template>
<script>


export default{
    name: "help",
    layout:"help",
      data() {
          return{
        activeItem: 'home'
    }

  },
    computed:{
    i18n(){
      return this.$i18n.locale;
    }
  },
  methods: {
  	isActive (menuItem) {
      return this.activeItem == menuItem
    },
    setActive (menuItem) {
      this.activeItem = menuItem
    },
    language(data){
      let cla = '';
      if (data  === 'ru')
      cla = 'ru-size';
       else if(data  === 'en')
      cla = 'en-size';
      else if (data  === 'ee')
      cla = 'ee-size'; 
      return cla;
    }
   }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/var";
.ru-size{
  font-size: 50px;
}
.en-size,
.ee-size{
  font-size: 60px;
}
#help_bg1{
    position: relative;
    height: 750px;
     height: 60vw;
     height: 750px;
    margin-top: -400px;
  @media screen and (min-width: 1700px){
      min-height:  750px;
      height: 55vw;
      height: 750px;
    }
  @media screen and (max-width: 768px) {
        margin-top: -550px;
        height: 90vw;
    }
  @media screen and (max-width: 650px) {
        margin-top: -580px;
        height: 100vw;
    }
  @media screen and (max-width: 450px) {
        margin-top: -600px;
        height: 110vw;
    }
}
#about_bg1{
    position: relative;
    max-height:  950px;
    height: 750px;
    @media screen and (min-width: 1700px){
      min-height:  750px;
      height: 900px;
    }
    @media screen and (max-width: 768px) {
        max-height:  450px;
        height:  450px;
    }
}
#help_header{
  position: relative;
  font-size: 72px;
  font-family: "Montserrat";
  color: rgb(254, 255, 255);
  font-weight: 600;
  line-height: 0.8;
  text-align: left;
  margin-left: 100px;
  width: 156px;
  height: 63px;
  @media screen and (max-width: 768px) {
    position: absolute;
    font-size: 42px;
    margin-top: -60px;
  }
  @media screen and (max-width: 400px) {
    margin-top: -30px;
  }
}
#help_content{
  font-size: 18px;
  font-family: "Montserrat";
  color: rgb(255, 255, 255);
  font-variant: small-caps;
  line-height: 1.3;
  text-align: left;
  margin-left: 100px;
  width: 47vw;
  height: 111px;
   @media screen and (min-width: 1300px) {
    width: 547px;
  }
   @media screen and (max-width: 1000px) {
    width: 37vw;
  }
  @media screen and (max-width: 768px) {
    width: 30vw;
    font-size: 2.5vw;
    position: absolute;
    margin-top: -10px;
  }
  @media screen and (max-width: 650px) {
    font-size: 2vw;
  }
  @media screen and (max-width: 400px) {
    display: none;
  }
}
.slogan__box{
  float: right;
  line-height: 1;
  text-align: right;
  width: 100%;
  margin-top: 4vw;
}
#slogan{
  font-family: "Montserrat";
  color: $logo-green;
  font-weight: bold;
  line-height: 1;
  text-align: right;
  float: right;
  margin-right: 20px;
  width: 574px;
  height: 105px;
  @media screen and (min-width: 1400px) {
    font-size: 60px;
     top: 7vw;
    margin-right:15px;
  }
  @media screen and (max-width: 768px) {
      display: flex;
      justify-content: center;
      width: 100%;
      font-size: 60px;
      text-align: center;
      padding-top: 25px;
      margin-right: 15px;
      margin-left: auto;
      top: 260px;
      width: 50%;
      font-size: 6vw;
      left: auto;
      right: 20px;
      line-height: 1.3;
  }
  @media screen and (max-width: 650px) {
      top: 200px;
  }
}
.question__mark{
  display: flex;
  background-color: #ffffff;
  width:300px;
  height: 300px;
  border-radius: 50%;
  float: right;
  margin-top: -180px;
  margin-right: 8%;
   @media(min-width: 1400px){
     margin-top: -180px;
   }
  @media(max-width: 768px){
    margin-top: 0px;
     width:28vw;
     height: 28vw;
   }
  @media(max-width: 450px){
      margin-top: -30px;
   }
}
#question{
  position: relative;
  margin: auto;
  max-height: 200px;
  @media(max-width: 768px){
     max-height: 20vw;
   }
}
</style>
