<template>
<div class="d-flex justify-content-center text-center">
        <div class="row text-center justify-content-center statistic__row">
            <div class="col-3" v-if="data.clients_count !== 'undefined' ">
              <div class="main__statistic">
                <div class="statistic__block">
                    <div class="statistic__number1">{{ data.clients_count}}</div>
                    <div class="statistic__text">{{ $t('menu.clients') }}</div>
                    <div class="statistic__href"><n-link to="/admin/clients">{{ $t('profile.view_all') }}</n-link></div>
                </div>
              </div>
            </div>
           <div class="media__749"></div>
            <div class="col-3" v-if="data.firms_count !== 'undefined' ">
              <div class="main__statistic">
                <div class="statistic__block">
                    <div class="statistic__number2">{{ data.firms_count}}</div>
                    <div class="statistic__text">{{ $t('menu.firm') }}</div>
                  <!-- а  нет firms-->
                  <div class="statistic__href"><n-link to="/admin/">{{ $t('profile.view_all') }}</n-link></div>
                </div>
              </div>
            </div>
            <div class="media__1000"></div>
            <div class="col-3" v-if="data.bills_count !== 'undefined' ">
              <div class="main__statistic">
                <div class="statistic__block">
                    <div class="statistic__number3">{{ data.bills_count}}</div>
                    <div class="statistic__text">{{ $t('bills.name') }}</div>
                    <div class="statistic__href"><n-link to="/bills" >{{ $t('profile.view_all') }}</n-link></div>
                </div>
              </div>
            </div>
            <div class="media__749"></div>
            <div class="col-3" v-if="data.users_count !== 'undefined' ">
              <div class="main__statistic">
                <div class="statistic__block">
                    <div class="statistic__number4">{{ data.users_count}}</div>
                    <div class="statistic__text">{{ $t('menu.users') }}</div>
                    <div class="statistic__href"><n-link to="/admin/users">{{ $t('profile.view_all') }}</n-link></div>
                </div>
              </div>
            </div>
        </div>
</div>
</template>

<script>


export default{
    name: "StatisticCabinet",
    props: ['data'],
}
</script>


<style scoped lang="scss">
@import "./assets/scss/var";
.col-3{
  @media screen and (max-width: 1000px){
  max-width: 35% !important;
  }
  @media screen and (max-width: 700px){
  max-width: 100% !important;
  }
}
.statistic__row{
  display: flex;
  padding-top: 40px;
  justify-content: center;
  align-items: center;
  min-width: 100%;
    @media screen and (max-width: 1000px){
    margin-right: auto;
     margin-left: auto;
   }
    @media screen and (max-width: 649px){
     margin-right: auto;
     margin-left: auto;
   }
}
.media__1000{
    width: 0px;
    @media screen and (max-width: 1000px){
        width: 100%;
        height: 50px;
  }
}
.media__749{
    width: 0px;
    @media screen and (max-width: 1000px){
        width: 100px;
  }
   @media screen and (max-width: 649px){
        width: 100%;
        height: 50px;
  }
}
.main__statistic{
  position: relative;
  display: flex;
  max-width: 200px;
  width: 200px;
  height: 200px;
  z-index: 1;
}
.statistic__block::before{
  content: "";
  position: absolute;
  display: flex;
  min-width: 100%;
  height: 100%;
  bottom: 10px;
  right: 10px;
  border: 2px solid #e9ebeb;
  border-radius: 15px;
  z-index: -1;
}
.statistic__block::after{
  content: "";
  position: absolute;
  display: flex;
  top: 10px;
  left: 10px;
  width: 100%;
  height: 100%;
  border: 2px solid #e9ebeb;
  border-radius: 15px;
  z-index: -1;
}
.statistic__block{
  flex: 1 1 auto;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
}
.statistic__number1,
.statistic__number2,
.statistic__number3,
.statistic__number4{
  font-size: 47px;
  font-weight: 600;
  line-height: 1;
}
.statistic__number1{
  color: $logo-blue;
}
.statistic__number2{
  color: $logo-orange;
}
.statistic__number3{
  color: $logo-green;
}
</style>
