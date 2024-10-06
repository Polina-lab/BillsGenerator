<template>
  <div>
    <main id="main" class="mt-5">
      <div class="container">
        <div class="card bg-light mb-5">
          <article class="card-body mx-auto">
            <h4 class="card-title mt-3 text-center">{{ $t('letter.res_password') }}</h4>
            <p class="text-center text-black">{{ $t('register.reset_text') }}</p>
            <p class="text-center text-black"></p>
            <div class="form-group input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"> <i class="fa fa-envelope fa-2x"></i> </span>
              </div>
              <input  class="form-control" :placeholder="$t('register.enter_mail')" maxlength="30" type="email" v-model="user.email" required>
            </div> <!-- form-group -->
            <div class="form-group">
              <button  class="btn btn-success btn-block" @click="reset">{{ $t('letter.res_password') }}</button>
            </div> <!-- form-group -->
              <a class="text-center text-black" ><n-link class="hover__login" to="login">{{ $t('register.log_in') }}</n-link></a>
          </article>
        </div> <!-- card -->
      </div>
    </main>
  </div>
</template>

<script>
import isEmailValid from '../mixins/isEmailValid';

export default {
  name: "reset_password",
  layout:"login",
  mixins: [ isEmailValid ],
  data() {
    return {
      user: {
        email: '',
      },
    };
  },
  methods:{

    async reset(){
      if(this.isEmailValid(this.user.email)) {
        await this.$axios.post(`${process.env.serverUrl}/api/reset_password`, this.user).then(
          res => {
            this.$toast.success(res.msg).goAway(2500);
            this.$router.push('/notify/confirm?email=' + this.user.email)
          },
          err => {
            throw err;
          }).catch((error) => {
          if (error.response) {
            this.$toast.error(error.response.data.msg).goAway(2500);
          } else {
            console.log('Error', error.message);
          }
        });
      }else{
        this.$toast.error("Please enter email").goAway(2500);
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
    padding-bottom: 50px;
  }
}

h4{
  font-family: 'Montserrat', sans-serif;
  color: $logo-green;
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
  color:#000000;
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
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  text-align: center !important;
  font-weight: bold;
  font-size: 21px;
  max-width: 230px;
  margin: 20px auto 40px auto;
  height: 50px;
}
a.hover__login:hover{
  text-decoration: underline;
  color:#11781c;
}

</style>
