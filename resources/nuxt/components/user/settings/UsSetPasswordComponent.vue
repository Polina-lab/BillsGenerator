<template>
  <div>
    <div class="card-title">
      <h2 class="m-2">{{ $t('User.password') }}</h2>
    </div>
    <div class="card-body pt-0">
      <div class="row">
        <div class="col-12">
          <label for="old_password">{{ $t('User.old_password') }}<sup class="red">*</sup></label>
          <input type="password"
                 class="form-control"
                 id="old_password"
                 v-model="password.password_old"
                 autocomplete="current-password"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <label for="password">{{ $t('User.password') }}<sup class="red">*</sup></label>
          <input type="password"
                 class="form-control"
                 id="password"
                 v-model="password.password"
                 autocomplete="new-password"
          />
        </div>
        <div class="col-sm-12 col-md-6">
          <label for="password2">{{ $t('User.repeat_password') }}<sup class="red">*</sup></label>
          <input type="password"
                 class="form-control"
                 id="password2"
                 v-model="password.password2"
                 autocomplete="new-password"
          />
        </div>
        <div class="col-12" v-if="password_notification.status"
             id="mismatch_notification">
          <div class="alert" 
               :class="{ 
                 'alert-success': password_notification.type === 'success',
                 'alert-danger': password_notification.type === 'error' }" 
               role="alert">
            {{ password_notification.msg }}
          </div>
        </div>
      </div>
    </div>
    <div class="pt-2">
      <button class="btn float-right btn-outline-success" @click="changePassword">{{ $t('common.save') }}</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UsSetPasswordComponent',
  components: {},
  mixins: [],
  data: () => ({
    password:{
      password_old: null,
      password: null,
      password2: null,
    },
    password_notification: {
      status: false,
      msg: null,
      type: null
    },
  }),

  computed: {
    user() {
      return this.$store.state.users.userDetail.local_data;
    },
  },

  methods: {
    /**
     * Set data to store
     * @param {{ target: { id: string, value: object } }} data object that wraps values
     * @param data.target.id string representing a field to modify
     * @param data.target.value value to set
     */
    updateUser(data){
      this.$store.commit('users/SET_OBJ', { name: `userDetail.local_data[${data.target.id}]`, value: data.target.value })
    },

    changePassword(){
      if(this.password.password_old === null ){
        this.password_notification.msg =  "Old password need be required";
        this.password_notification.type = 'error';
        this.password_notification.status = true;
      }else if(this.password.password !== this.password.password2){
        this.password_notification.msg =  this.$t('User.passwords_not_matching');
        this.password_notification.type = 'error';
        this.password_notification.status = true;
      }else if(this.password.password.length < 5){
        this.password_notification.msg =  'Password must be at least 6 characters';
        this.password_notification.type = 'error';
        this.password_notification.status = true;
      }
      else{
        this.password_notification.msg =  null;
        this.password_notification.type = null;
        this.password_notification.status = false;
        this.$store.dispatch('users/updatePassword' , {
          'id' : this.$route.params.id ,
          'password_old' : this.password.password_old,
          'password' : this.password.password,
        }).then( res => {
          this.password_notification = res ;
          this.password_notification.status = true
        })
      }
    },
  }
}
</script>

<style lang="scss" scoped>
.red {
  color: red;
}

#mismatch_notification {
  padding-top: 10px;
  width: 90%;
  text-align: center;
}
</style>