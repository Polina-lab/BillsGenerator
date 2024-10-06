<template>

    <div class="footer">

        <div class="container">
            <div class="row">
                <div class=" offset-lg-0 offset-md-0 offset-6 col-lg-5 col-md-5 col-6">
                    <div class="container__social ">
                        <h3>{{ $t('menu.join_us') }}</h3>
                        <div class="row social text-right" >
                            <a href="https://www.instagram.com/gloreal_estate/"><span class="fab fa-instagram fa-2x"></span></a>
                            <a href="https://www.facebook.com/glorealkinnisvara/"><img id="messenger" src="@/assets/img/about/messenger.svg" alt="messenger"></a>
                            <a href="https://www.facebook.com/glorealkinnisvara/"><span class="fab fa-facebook fa-2x"></span></a>
                        </div>
                    </div>
                </div><!--col-6-->
            </div>
            <div class="row reverse">
                <div class="col-lg-5 col-md-5 col-12 text-center">
                    <n-link to="/"><img class="logo__white" src="@/assets/img/logoWhite.svg" alt="Logo"/></n-link>
                    <div class="row justify-content-between add__position">
                        <n-link class="help" to="/help">{{ $t('menu.help')}}</n-link>
                        <n-link class="privacy" to="/privacy">{{ $t('menu.privacy_policy')}}</n-link>
                    </div>
                </div><!--col-6-->

                <div class="col-lg-7 col-md-7 col-12 ">
                    <h4>{{ $t('menu.contact_us')}}</h4>
                    <div class="company-contacts mt-2">
                        <div class="row">
                            <div class="col-6 col-sm-6 text-left pl-5">
                                Glo Real Network OÜ<br>
                                Estonia pst 5, III {{ $t('menu.korrus')}}<br>{{ $t('menu.tallinn_harjumaa')}}<br>
                                {{ $t('bills.email')}}: info@gloreal.ee<br>
                                {{ $t('bills.reg')}}: 14118122<br>
                            </div>
                            <div class="col-6 col-sm-6 text-right pr-4">
                            <br>
                                {{ $t('bills.phone')}}:<br> +372 555 99 313<br>
                                www.gloreal.ee
                            </div>
                        </div>
                    </div>
                </div><!--col-6-->
            </div><!--row-->
        </div>

    </div>

</template>
<script>

export default{
    name: "FooterLogin",
}
</script>
<style scoped lang="scss">
@import "./assets/scss/var";
.row{
    margin-right: 0px !important;
}
.footer{
    min-width: 100%;
    width: 100%;
    background-color: $logo-green;
    background-position: bottom;
    padding-bottom: 30px;
  }
 h3{
  text-align: left;
  color: #ffffff;
  font-size: 28px;
  font-family: 'Montserrat', sans-serif;
  font-weight: 500;
  white-space: nowrap;
  @media screen and (max-width: 1200px) {
      position: relative;
      margin-top: -20px;
  }
  @media screen and (max-width: 1000px) {
      margin-top: -30px;
  }
    @media screen and (max-width: 768px) {
        text-align: left;
        margin-left: 20px;
    }
}
h4{
  margin-top: 5%;
  white-space: nowrap;
  text-align: center;
  color: #ffffff;
  font-size: 30px;
  justify-content: flex-end;
  font-family: 'Montserrat', sans-serif;
  font-weight: 500;
  padding-bottom: 10px;
  @media screen and (max-width: 768px) {
      position: absolute;
      margin-top: -93px;
      text-align: left;
      padding-left: 30px;
  }
}
.container__social{
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top:  30px;
  padding-left: 30px;
  margin-top: auto;
  margin-bottom: -50%;
  @media screen and (max-width: 768px) {
    flex-direction: column;
    padding: 30px 30px 0px;
    margin-bottom: 0%;
    margin-left: auto;
    margin-right: 0px;
    padding-top: 40px;
  }
}
.social{
  justify-content:center;
  display: flex;
  margin-top: auto;
  margin-bottom: auto;
  flex-wrap: nowrap;
  direction: ltr;
  z-index: 99;

    & a{
        display: inline-block;
        color: #ffffff;
        width: 60px;
        height: 50px;
        border-radius: 50%;
        position: relative;
        text-align: right;
        transition: .3s color ease;
        }
        & a:hover{
          color: #e3e3e3;
        }
    & #messenger{
        max-width: 33px;
        filter: invert(98%) sepia(5%) saturate(305%) hue-rotate(127deg) brightness(117%) contrast(100%);
    }
    & #messenger:hover{
       -webkit-filter: brightness(90%);
       filter: brightness(90%);
    }
}

.help,
.privacy{
    color: #ffffff;
  &:hover{
  color: #e3e3e3;
  text-decoration: none;
  cursor: pointer;
}
}
.reverse{
    @media screen and (max-width: 770px) {
        display: flex;
        flex-direction: column-reverse;
    }
}
.add__position{
    padding: 0px 40px 0px;
}
.text-left,
.text-right{
    color: #ffffff;
    font-family: 'Montserrat', sans-serif;
}
.text-right,
.text-center{
   display: flex-box;
   margin-top: auto;
   //margin-bottom: 0px;
}
.logo__white{
    width: 40%;
    margin-top: 30px;
    @media screen and (max-width: 749px) {
        max-width: 40%
    }
}
</style>
