<template>
    <div>

    <div  id="help_bg2" ref="help">
        <div class="container-fluid">
        <ul class="nav nav-tabs nav-justified menu">
            <li class="nav-item" :class="{created: isCreated}">
                <a class="nav-link" @click="calculateWidth();" :class="{ active: isActive('home') }" href="/register">{{ $t('menu.register') }}</a>
            </li>
            <li class="nav-item" :class="{created: isCreated}">
                <a class="nav-link" @click="setActive('terms');calculateWidth();" :class="{ active: isActive('terms') }" href="#terms">{{ $t('menu.terms') }}</a>
            </li>
            <li class="nav-item" :class="{created: isCreated}">
                <a class="nav-link" @click="setActive('subscriptions');calculateWidth()" :class="{ active: isActive('subscriptions') }" href="#subscriptions">{{ $t('menu.subscriptions') }}</a>
            </li>
            <li class="nav-item" :class="{created: isCreated}">
                <a class="nav-link" @click="setActive('cancel');calculateWidth();" :class="{ active: isActive('cancel') }" href="#cancel">{{ $t('menu.cancel') }}</a>
            </li>
            <li class="nav-item" :class="{created: isCreated}">
                <a class="nav-link" @click="setActive('payment');calculateWidth();" :class="{ active: isActive('payment') }" href="#payment">{{ $t('menu.methods') }}</a>
            </li>
        </ul>

                <div class="tab-content py-3" :class="{white__block: isWhiteBlock}">
                    <div class="vertical__shadow"></div>
                    <div class="tab-pane fade" :class="{ 'active show': isActive('terms') }">
                        <h1 class="blue__header">{{ $t('menu.terms') }}</h1>
                        <ul class="blue__root">
                            <li>
                                <span class="root"></span>
                                <span  class="num">1.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.using_the_bills') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span  class="num">2.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.bills_uses_customer') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span class="num">3.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.client_has_right') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span class="num">4.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.agreement') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span class="num">5.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.disagreement') }}{{ $t('menu.negotiations') }}</b>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" :class="{ 'active show': isActive('subscriptions') }">
                        <h1 class="blue__header">{{ $t('menu.subscriptions') }}</h1>
                        <ul class="blue__root">
                            <li>
                                <span class="root"></span>
                                <span  class="num">1.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.use_apply') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span  class="num">2.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.when_registering') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span  class="num">3.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.reserves') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span  class="num">4.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.order_before') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span  class="num">5.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.prices_on_website') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span  class="num">6.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.to_change_prices') }}</b>
                                </div>
                            </li>
                            <li>
                                <span class="root"></span>
                                <span  class="num">7.</span>
                                <div class="box__text">
                                    <b>{{ $t('menu.price_indicated') }}</b>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" :class="{ 'active show': isActive('cancel') }">
              <h1 class="element__header">{{ $t('menu.cancel') }}</h1>
              <ul class="blue__root">
                  <li id="element"><div class="box__text"><b>
                      {{ $t('menu.return_money') }} <a href="info@gloreal.ee">info@gloreal.ee</a> {{ $t('menu.reason') }}
                      </b>
                      </div>
                    </li>
              </ul>
                    </div>
                    <div class="tab-pane fade" :class="{ 'active show': isActive('payment') }">
                <h1 class="blue__header">{{ $t('menu.methods') }}</h1>
                <h2 class="small__header">{{ $t('menu.several_ways') }}</h2>
                    <ul class="blue__root">
                        <li>
                            <span class="root"></span>
                            <span  class="num">1.</span>
                            <div class="box__text">
                                <b>{{ $t('menu.using_visa') }}</b>
                            </div>
                        </li>
                        <li>
                            <span class="root"></span>
                            <span  class="num">2.</span>
                            <div class="box__text">
                                <b>{{ $t('menu.following') }}</b>
                            </div>
                        </li>
                        <li>
                            <span class="root"></span>
                            <span  class="num">3.</span>
                            <div class="box__text">
                                <b>{{ $t('menu.send_invoice_by_email') }}</b>
                            </div>
                        </li>
                        <li><span class="root"></span>
                            <span  class="num">4.</span>
                            <div class="box__text">
                                <b>{{ $t('menu.with_coupon') }}</b>
                            </div>
                        </li>
                    </ul>
                    </div>
                </div>
        </div>


        <div class="blue__block">
            <div class="blue__text">
                <p>{{ $t('menu.find_the_answer') }}<br>
                {{ $t('menu.contact_us') }}<br>
                +372 555 99 313<br>
                info@gloreal.ee</p>
            </div>
        </div>

        </div>
    </div>
</template>
<script>


export default{
    name: "HelpTabs",
      data() {
          return{
        activeItem: 'terms',
        width: 0,
        isCreated: false,
        isWhiteBlock: true
    }

  },

  methods: {
  	isActive (menuItem) {
      return this.activeItem == menuItem;
    },
    setActive (menuItem) {
      this.activeItem = menuItem;
    },
     calculateWidth() {
      this.width = this.$refs.help.clientWidth;
      if (this.width < 750){
       this.isCreated = true;
       this.isWhiteBlock = false;
       }else{
          this.isWhiteBlock = true;
          this.isCreated = false;
       }
    }
   }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/var";
#help_bg2{
    width:100%;
    height: 100%;
    display: flex;
    margin-top: -16%;
    max-width: 1300px;
    background: -webkit-linear-gradient(185deg, rgb(38, 166, 71) 10%, rgb(3, 186, 131), rgb(1, 158, 216), rgb(127, 206, 235));
    background: -moz-linear-gradient(185deg, rgb(38, 166, 71) 10%,rgb(3, 186, 131), rgb(1, 158, 216), rgb(127, 206, 235));
    background: linear-gradient(185deg, rgb(38, 166, 71) 10%, rgb(3, 186, 131), rgb(1, 158, 216), rgb(127, 206, 235));
    border-radius: 0 0 0 210px;
    @media screen and (min-width: 1300px) {
        border-radius: 0 0 0 0;
        margin-top: -18vw;
    }
    @media screen and (max-width: 749px) {
        height: 100%;
        background-image:url('@/assets/img/feathers_help_mobile.svg'),linear-gradient(190deg, rgb(2, 172, 92) 5%,rgb(4, 165, 201));
       -webkit-background-size: cover, contain;
        -moz-background-size:  cover, contain;
        -o-background-size: cover, contain;
        background-size: cover, contain;
        background-repeat: no-repeat;
        background-position:  center;
        border-radius: 0 0 0 0;
    }
}

.container-fluid{
    @media screen and (max-width: 749px) {
        display: flex;
        min-width: 100% !important;
        padding-left: 0px !important;
        padding-right: 0px !important;
        overflow-x: hidden;
    }
}
.menu{
  display: flex;
  //flex-wrap: nowrap;
  overflow: hidden;
  align-items: center;
  max-width: 100%;
  font-size: 16px;
  font-family: 'Montserrat', sans-serif;
  color: rgb(255, 255, 255);
  font-weight: 600;
  font-variant: small-caps;
  text-align: center;
  border-bottom: none !important;
    @media screen and (max-width: 900px) {
        font-size: 10px;
    }
    @media screen and (max-width: 749px) {
        flex-direction: column;
        font-size: 14px;
        max-width: 20%;
        margin-left: auto;
        margin-right: auto;
    }
    @media screen and (max-width: 500px) {
        max-width: 25%;
        font-size: 10px;
    }
}
.menu .nav-item{
    display: flex;
    width: 195px;
    height: 80px;
     @media screen and (max-width: 749px) {
         display: flex;
         margin-bottom: 45px;
     }
}
.menu .nav-item .nav-link{
    display: flex;
    justify-content: center;
    align-items: center;
    color: rgb(255, 255, 255) !important;
    margin: 10px;
    line-height: 1.3;
    height: 60px;
    @media screen and (max-width: 749px) {
        max-width: 120px;
        height: 70px;
        margin: 0px;
    }
}
.menu .nav-item .active{
    border-left: none;
    border-right: none;
    border-top: 3px solid #ffffff;
    border-bottom: 3px solid #ffffff;
    background-color: $logo-green;
}
.created{
    line-height: 1.5 !important;
    text-align: right !important;
}

.white__block{
    width: 100%;
    height: auto;
    float:left;
    background-color: #ffffff;
    border-radius: 0 0 0 190px;
    padding-bottom: 100px;
    @media screen and (min-width: 1400px) {
        border-radius: 0 0 0 0;
    }
    @media screen and (max-width: 749px) {
        display: none;
        border-radius: 0 0 0 0;
    }
}
.blue__block{
    display: flex;
    width: 20%;
    margin-top: auto;
    margin-bottom: 0;
    color: #ffffff;
    font-weight: 500;
    padding: 10px;
    @media screen and (max-width: 749px) {
        display: none;
    }
}
.blue__text p{
  font-size: 14px;
  font-family: "Montserrat";
  color: #ffffff;
  text-align: center;
  width: 111px;
  line-height: 1.7;
 @media screen and (max-width: 749px) {
        display: none;
    }
}
.tab-content{
  position: relative;
  color: rgb(0, 158, 217);
  line-height: 1.5;
  width: 100%;
  margin-left: -2vw !important;
  background-color: #ffffff;
  border-radius: 0 0 0 190px;
  @media screen and (min-width: 1300px) {
      border-radius: 0 0 0 0;
  }
  @media screen and (max-width: 1000px) {
       margin-left: -5vw !important;
  }
  @media screen and (max-width: 900px) {
       margin-left: -3vw !important;
  }
  @media screen and (max-width: 749px) {
      min-height: 675px;
      margin-top: 0px;
      margin-right: -50px;
      padding-left: 0;
      max-width: 75%;
      border-radius: 0 0 0 0;
  }
}
.vertical__shadow{
    display: none;
    @media screen and (max-width: 749px) {
      display: block;
      position: absolute;
      z-index: 9999;
      width: 3px;
      margin-top: -10px;
      height: 99%;
      background-color: #b6b4b4;
      margin-right: 22px;
      box-shadow: 4px 0 8px 0 rgba(0,0,0,0.5);
    }
}
.blue__root{
    list-style: none;
    counter-reset: myCounter;
    @media screen and (max-width: 749px) {
        padding-left: 5px;
        width: 100%;
    }
}
.blue__root li{
    margin-bottom: 40px;
    padding-left: 15px;
    padding-right: 15px;
}
.root{
    display: inline-flex;
    margin-top: auto;
    margin-bottom: auto;
    width: 86px;
    height: 40px;
    margin-right: 30px;
    background: linear-gradient(-90deg, #a3d9ee 20%, #0ca3db 80%);
}
.box__text{
    display: inline-flex;
    width: 80%;
    @media screen and (max-width: 749px){      
       display: flex;
       width: 80%;
    }
}
 .box__text b{
    position: relative;
    margin-top: 30px;
    display: flex;
    word-break: break-all;
    white-space: wrap;
    flex-wrap: wrap;
    text-align: justify;
    margin-bottom: auto;
    padding-right: 15px;
    font-size: 16px;
    font-weight: 500 !important;
    font-family: 'Montserrat', sans-serif;
}
.num{
  display: inline-flex;
  margin-top: auto;
  margin-bottom: auto;
  min-width: 50px;
  width: 50px;
  font-size: 48px;
  font-family: 'Montserrat', sans-serif;
  color: $logo-blue;
  font-weight: bold;
  line-height: 1.25;
  text-align: left;
   background: linear-gradient(55deg, #74caeb 33%, #0ca3db 66%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.blue__header{
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  font-size: 48px;
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
  font-weight: 600;
  line-height: 1.25;
  width: 100%;
  margin-right: auto;
  margin-left: auto;
  padding-top: 10px;
  @media screen and (max-width: 1050px) {
      justify-content: start;
      margin-left: 50px;
      width: 90%;
  }
   @media screen and (max-width: 749px) {
       margin-left: 0px;
       font-size: 38px;
       width: 80%;
       padding: 30px 10px 0px 10px;
   }
   @media screen and (max-width: 600px) {
       font-size: 34px;
   }
}
.element__header{
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  font-size: 48px;
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
  font-weight: 600;
  line-height: 1.25;
  width: 100%;
  margin-right: auto;
  margin-left: auto;
  padding-top: 10px;
  @media screen and (max-width: 1050px) {
      justify-content: start;
      margin-left: 50px;
      width: 90%;
  }
   @media screen and (max-width: 749px) {
       margin-left: 15px;
       font-size: 36px;
       width: 80%;
       //padding: 30px 10px 0px 10px;
       padding-top: 30px;
   }
   @media screen and (max-width: 450px) {
       margin-left: 0px;
       font-size: 34px;
       width: 80%;
   }
}
.small__header{
  display: flex;
  justify-content: center;
  font-size: 16px;
  font-family: 'Montserrat', sans-serif;
  color: rgb(0, 158, 217);
  font-weight: 600;
  line-height: 1.5;
  width: 100%;
  text-align: center;
  @media screen and (max-width: 749px) {
       width: 75%;
       //margin-left: 20px;
       padding: 0px 10px;
  }
}
/*#element{

    white-space: normal;
    word-wrap: break-word;
    flex-wrap: wrap;
    width: 80%;
    text-align: justify;
    margin-top: 40px;
    padding-right: 15px;
    font-size: 16px;
    font-family: 'Montserrat', sans-serif;
}*/

</style>
