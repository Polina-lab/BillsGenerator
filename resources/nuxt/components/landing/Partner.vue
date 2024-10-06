<template>
    <div>
        <div class="mw1140">
        <div class="row justify-content-center text-center" style="padding-top:60px;">
            <h5>{{ $t('menu.our_partners')}}</h5>
        </div>
        <div class="container-fluid">
            <div class="img__container">
                <div class="partner__container">
                    <a href="https://about.google">
                        <img class="partner pointer" src="@/assets/img/Google_2015_logo.svg" alt="Google">
                    </a>
                </div>
                <div class="partner__container">
                    <a href="https://gloreal.ee/">
                        <img class="partner pointer" src="@/assets/img/logo-horizontal.svg" alt="Glo real">
                    </a>
                </div>
                <div class="partner__container">
                    <a href="https://www.swedbank.ee/about">
                        <img class="partner pointer" src="@/assets/img/swedbank-logo.svg" alt="Swedbank">
                    </a>
                </div>
                <div class="partner__container">
                    <a href="https://coin24.ee/about">
                        <img class="partner pointer" src="@/assets/img/coin-24-logoest.png" alt="Coin 24">
                    </a>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>

export default {
    name:"Partner",

}
</script>
<style scoped lang="scss">
@import "./assets/scss/_var.scss";
.row{
    margin-right: 0px !important;
    margin-left: 0px !important;
}
.mw1140{
   position: relative;
   margin-left: auto;
   margin-right: auto;
   max-width: 1140px;
}

h5{
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
  font-weight: bold;
  font-variant: small-caps;
  line-height: 2.6;
  font-size: 30px;
  margin-top: -2vw;
}
.img__container{
  margin-top: 1vw;
  display: inline-grid;
  grid-template-columns: repeat(auto-fit, minmax(6vw, 17%));
  width: 100%;
  justify-content:center;
  align-items: center;
  gap: 8%;
}
.partner__container{
  position: relative;
  width:20vw;
  height:5vw;
  @media screen and (min-width:1300px) {
    width:13vw;
    height:3vw;
  }
}
.partner{
  position: absolute;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%);
  width:auto;
  height: 100%;
}
</style>
