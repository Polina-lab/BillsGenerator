<template>
 <section class="yellow__1" >
    <div class="container" id="mw900">
      <div class="row">
        <div class="col-md-4 text-center feature-1">
           <div class="wrap-icon icon-1 text-center">
              <img class="cercle__icon" src="@/assets/img/clients_orange.svg" alt="icon">
            </div>
            <h3>{{ statistics ? statistics.users : 123  }}</h3>
            <p>{{ $t('menu.clients') }}</p>
        </div>
        <div class="col-md-4 text-center feature-1">
            <div class="wrap-icon icon-1">
              <img class="cercle__icon" src="@/assets/img/days_orange.svg" alt="icon">
            </div>
            <h3>{{ statistics ? statistics.days  : 19344}}</h3>
            <p>{{ $t('menu.days') }}</p>
        </div>
        <div class="col-md-4 text-center feature-1">
            <div class="wrap-icon icon-1">
              <img class="cercle__icon" src="@/assets/img/bills_orange.svg" alt="icon">
            </div>
            <h3>{{ statistics ? statistics.bills : 12451 }}</h3>
            <p>{{ $t('menu.bills') }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "Statistic",
  props: ['statistics']
}
</script>

<style scoped lang="scss">

@import "./assets/scss/_var.scss";

.yellow__1{
  padding-top: 25px;
  padding-bottom: 15px;
  @media screen and (max-width:749px){
    padding-top: 10px;
    padding-bottom: 0px;
  }
}
.feature-1 .wrap-icon {
  margin: 0 auto;
  height: 100px;
  width: 100px;
  border-radius: 50%;
  position: relative;
}

.feature-1 .wrap-icon.icon-1 {
  background-color: #ffffff;
  display: flex;
  justify-content: center;
  @media (max-width:749px) {
  display: none;
  }
}
.feature-1 h3 {
  margin-top: 18px;
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: bold;
  color: #ffffff;
  letter-spacing: 0.05em;
  @media (max-width:749px) {
    padding-top: 0px;
  }
}
.feature-1 p {
  font-family: 'Montserrat', sans-serif;
  font-size: 20px;
  font-weight: inherit;
  color: #ffffff;
  letter-spacing: 0.05em;
  line-height: 0.5;
  @media (max-width:749px) {
    padding-bottom: 30px;
  }
}
.cercle__icon{
  text-align: center;
  width: 50px;
}

</style>
