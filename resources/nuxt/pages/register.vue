<template>
  <div>
    <main id="main">
      <h1 id="welcome">{{ $t('register.welcome') }}</h1>
      <RegistrationArrows :page="1" />
    <div class="container">
      <div class="card bg-light mb-5">
        <article class="card-body mx-auto">
          <h4 class="card-title mt-3 text-center">{{ $t('register.create_your') }}</h4>
          <p class="text-center text-black">{{ $t('register.to_register') }}</p>
          <p class="text-center text-black"></p>
            <div class="form-group input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"> <i class="fa fa-envelope fa-2x"></i> </span>
              </div>
              <input class="form-control" :placeholder="$t('register.enter_mail')"
                     type="email" maxlength="30" v-model="user.email" required
                     @keyup.enter="register">
            </div> <!-- form-group// -->
            <div class="form-group">
              <button  class="btn btn-success btn-block" @click.once.prevent="register">{{ $t('register.create_account') }}</button>
            </div> <!-- form-group// -->
            <p class="text-center text-black" >{{ $t('register.have_an') }}<n-link to="login">{{ $t('register.log_in') }}</n-link> </p>
            <p class="notification">{{ $t('register.by_creating') }}</p>
        </article>
      </div> <!-- card.// -->
    </div>
    </main>
  </div>
</template>

<script>
import RegistrationArrows from "../components/common/RegistrationArrows";
import isEmailValid from '../mixins/isEmailValid';

export default {
  name: "register",
  layout:"login",
  components: { RegistrationArrows },
  mixins: [ isEmailValid ],
  data() {
    return {
      user: {
        email: '',
      },
    };
  },
  methods:{

    async register(){
        if(this.isEmailValid(this.user.email)) {
          await this.$axios.post(`${process.env.serverUrl}/api/register`, this.user).then(
            res => {
              this.$toast.success(res.msg).goAway(2500);
              this.$router.push('/notify/email_register_was_sended?email=' + this.user.email)
            },
            err => {
              throw err;
            }).catch((error) => {
            if (error.response) {
                this.$toast.error(this.$t(error.response.data.msg)).goAway(3000);
            }
            });
        }else{
          this.$toast.error("Please enter email").goAway(3000);
        }
    },
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
#main{
  margin-bottom: 200px;
}
.bg-light{
  display: flex;
  margin: 0 auto;
  max-width: 700px;
  width: 700px;
  background-color: #ffffff !important;
  -webkit-box-shadow: -4px 4px 36px 0px rgba(34, 60, 80, 0.2);
  -moz-box-shadow: -4px 4px 36px 0px rgba(34, 60, 80, 0.2);
  box-shadow: -4px 4px 36px 0px rgba(34, 60, 80, 0.2);
  @media screen and (max-width: 900px) {
      width: auto;
  }
    & .card-body{
    max-width: 500px;
  }
}
#welcome{
  font-size: 35px;
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
  font-weight: 600;
  line-height: 1;
  text-align: center;
  margin: 30px auto 30px auto;
  max-width: 960px;
  letter-spacing: 1.2px;
}
h4{
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
  //font-variant: small-caps;
  line-height: 1;
}
.input-group{
  display: flex;
  margin: 0 auto;
  max-width: 360px;
  width: 400px;
 @media screen and (max-width: 430px) {
    max-width: 300px;
    width: 300px;
  }
}
.input-group-prepend,
.form-control{
  border: 2px solid $logo-green;
  height: 60px;
}
.input-group-text{
  background-color: rgb(183, 221, 183);
}
 .form-control{
  font-size: 18px;
  font-family: 'Montserrat', sans-serif;
  font-weight: 500;
  line-height: 1.25;
  letter-spacing: 1.3px;
}
.fa-envelope{
  color: $logo-green;
}
a{
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
  line-height: 1.25;
  font-weight: 600;
}
.text-black{
  font-family: 'Montserrat', sans-serif;
  color: rgb(72, 72, 72);
  line-height: 1.25;
  text-align: center;
  font-size: 18px;
  letter-spacing: 1.4px;
  margin: 30px auto;
}
.notification{
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
  font-variant: small-caps;
  line-height: 1.25;
  font-size: 18px;
  text-align: center;
  letter-spacing: 1.2px;
  margin-bottom: 40px;
}
.btn-block{
  display: flex;
  justify-content: center;
  font-family: 'Montserrat', sans-serif;
  text-align: center !important;
  font-weight: bold;
  font-size: 21px;
  max-width: 230px;
  margin: 20px auto 40px auto;
  height: 50px;
}


</style>
