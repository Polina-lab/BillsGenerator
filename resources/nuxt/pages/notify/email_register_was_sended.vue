<template>
  <div>
    <div class="text-center p-5">
      <h1 id="main-notify-title">{{ $t('register.welcom_to') }}</h1>
    </div>
    <RegistrationArrows  page="1" />
    <main id="notify">
    <div class="container">
      <div class="card bg-light mt-6 mb-5">
        <article class="card-body mx-auto">
          <div class="text-center p-3 pt-5">
            <h3 id="notify-title">{{ $t('register.password_has_been') }}</h3>
            <p class="text-black p-2">{{ $t('register.if_you_need') }}</p>
            <p class="text-grey p-2">{{ $t('register.you_need') }}</p>
          </div>
          <div class="p-2 text-center">
            <n-link class="btn btn-success btn-block" :to="'/login?email=' + $route.query.email">{{ $t('register.login') }}</n-link>
          </div>
        </article>
      </div>
    </div>
    </main>
  </div>
</template>

<script>
import RegistrationArrows from "@/components/common/RegistrationArrows";
export default {
  components: { RegistrationArrows },
  layout: "login",
  name: "email_register_was_sended",
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
#notify{
  margin-bottom: 200px;
}
.bg-light{
  display: flex;
  margin: 0 auto;
  max-width: 800px;
  width: 800px;
  background-color: #ffffff !important;
  -webkit-box-shadow: -4px 4px 36px 0px rgba(34, 60, 80, 0.2);
  -moz-box-shadow: -4px 4px 36px 0px rgba(34, 60, 80, 0.2);
  box-shadow: -4px 4px 36px 0px rgba(34, 60, 80, 0.2);
  @media screen and (max-width: 990px) {
      max-width: auto;
      width: auto;
  }
      & .card-body{
    margin: 30px auto 90px;
    max-width: 740px;
  }
}

#main-notify-title{
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
  font-size: 40px;
  font-weight: bold;
  line-height: 1;
  text-align: center;
  margin: 0px auto 10px auto;
  max-width: 750px;
  letter-spacing: 1.2px;
}

#notify-title{
  justify-content: center;
  text-align: center;
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
  font-variant: small-caps;
  line-height: 1.25;
  font-size: 28px;
  max-width: 680px;
  min-height: 10px;
}

.text-black{
  font-family: 'Montserrat', sans-serif;
  font-variant: small-caps;
  color: rgb(72, 72, 72);
  line-height: 1.25;
  text-align: center;
  font-size: 20px;
  letter-spacing: 1.4px;
  margin: 30px auto;
}
.text-grey{
  font-family: 'Montserrat', sans-serif;
  color: rgb(190, 190, 190);
  font-variant: small-caps;
  font-weight: 500;
  line-height: 1.25;
  text-align: center;
  font-size: 20px;
  letter-spacing: 1.4px;
  margin: 30px auto;
}
.btn-block{
  display: flex;
  justify-content: center;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  text-align: center !important;
  font-weight: bold;
  font-size: 21px;
  max-width: 230px;
  margin: 0px auto 0px auto;
  height: 50px;
}

</style>
