<template>
  <div class="container-fluid">
    <div class="row">
      <div class="justify-content-align justify-content-left box__404">
        <h1 id="title-404">{{ $t('error.oh_no')}}</h1>
        <p id="description-404">{{ $t('error.page')}}</p>
        <h2 id="subtitle-404">{{ $t('error.code')}}</h2>
        <button class="go__back" @click="linkOnBackPage()"><i class="fas fa-arrow-left fa-2x"></i>Go back</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "page404",
  props: ['error'],
  layout:"page404"
}
</script>

<style scoped lang="scss">
@import "./assets/scss/_var.scss";
.box__404{
  max-width: 60%;
  padding-top: 2vw;
  padding-left: 12vw;
  width: 60%;
  color: #ffffff;
  font-family: 'Montserrat', sans-serif;
  min-height: 400px;
  @media screen and (min-width: 2001px){
    padding-left: 0%;
    min-height: 30vw;
  }
  @media (min-width: 1300px) and (max-width: 2000px){
    padding-left: 100px;
    min-height: 500px;
  }
  @media screen and (max-width: 749px){
    max-width: 100%;
    width: 100%;
    padding-left: 50px;
  }
}
#title-404{
  font-size: 3.4vw;
  letter-spacing: 2px;
  font-weight: 700;
  @media screen and (min-width: 1300px){
    font-size: 42px;
  }
  @media screen and (max-width: 749px){
    font-size: 5vw;
    max-width: 50%;
  }
  @media screen and (max-width: 400px){
    font-size: 18px;
    max-width: 55%;
  }
}
#subtitle-404{
  font-size: 2.3vw;
  font-weight: 600;
  letter-spacing: 2px;
  @media screen and (min-width: 1300px){
    font-size: 26px;
  }
  @media screen and (max-width: 749px){
    text-align: justify;
    font-size: 20px;
    margin-top: 2vw;
  }
}
#description-404{
  font-size: 1.5vw;
  max-width: 80%;
  width: 80%;
  line-height: 2;
  padding-top: 1.2vw;
  margin-bottom: 0px;
  @media screen and (min-width: 1300px){
    font-size: 20px;
  }
  @media screen and (max-width: 749px){
     max-width: 100%;
     font-size: 14px;
     margin-top: 30vw;
  }
}
.go__back{
  display: inline-flex;
  justify-content: center;
  align-items: center;
  background: none;
  border: none;
  color: #ffffff;
  font-family: 'Montserrat', sans-serif;
  font-size: 1.9vw;
  font-weight: 600;
  letter-spacing: 1px;
  text-decoration: underline;
  padding-top: 1vw;
  @media screen and (min-width: 1300px){
    font-size: 26px;
  }
  @media screen and (max-width: 749px){
    font-size: 20px;
  }
}
.fa-arrow-left{
  display: inline-flex;
  padding-right: 20px;
  text-decoration: none;
}
</style>