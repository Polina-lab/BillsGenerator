<template>
<div class="mt-5 mb-5">
  <RegistrationArrows v-if="$route.query.email && $route.query.email !== 'demo@test.ru'" page="2"/>
  <div class="container card">
    <h4 class="text-center p-2">{{ $t('menu.login') }}</h4>
    <form>
      <label for="email">{{ $t('User.email') }}</label>
      <input class="form-control"
             type="text" v-model="user.username"
             maxlength="30"
             autocomplete="off" id="email"
             @keyup.enter="login">

      <label for="password">{{ $t('User.password') }}</label>
      <input class="form-control" id="password" maxlength="30"
             type="password" v-model="user.password"
             @keyup.enter="login">
    </form>
    <button class="btn btn-success mt-3 mb-3" type="submit" @click.prevent="login">{{ $t('menu.login') }}</button>
    <span class="text-center grey">If you have problems logging in</span>
    <n-link class="text-center green" to="/reset_password">Reset password</n-link>
  </div>
</div>
</template>

<script>
import RegistrationArrows from "@/components/common/RegistrationArrows";
import isEmailValid from '../mixins/isEmailValid';
export default {
  name: "login",
  layout:"login",
  components: { RegistrationArrows },
  mixins: [isEmailValid],
  data() {
    return {
      user: {
        username: this.$route.query.email ?? '',
        password: this.$route.query.password ?? '',
      },
      is_second_login: false
    };
  },
  methods: {
    async login() {
      if (!(this.user.username.length > 0) ||
      !this.isEmailValid(this.user.username) ||
      !(this.user.password.length > 0)) {
        return;
      }
      try {
        const data = await this.$auth.loginWith('local', {
          data: this.user
        })
        if (data.data.key) {
          this.$store.commit("SET_OBJ", { name: "key", value: data.data.key }, { root: true });
          let code = this.$i18n.localeCodes[this.$store.state.auth.user.local_data.langs_id];
          this.$store.dispatch('SET_KEY');
          this.$i18n.locale = code;
          this.$i18n.setLocaleCookie(code);
          this.$axios.baseUrl = process.env.serverUrl + "/api/" + data.data.key;
        }
        this.$router.push('/admin')
      } catch (e) {
        if (this.is_second_login) {
          this.$router.push('/admin');
        } else {
          this.$toast.error(e.response.data.msg).goAway(3000);
          this.is_second_login = true;
          this.login();
        }
      }
    },
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
.card {
  max-width: 350px;
}
h4{
  font-size: 26px;
  font-family: 'Montserrat', sans-serif;
  font-weight: bold;
  color: $logo-green;
}
label{
  color: $secondary-color;
}
.green{
  text-decoration: none;
  color: $logo-green;
}

.grey{
  color: grey;
}

</style>
