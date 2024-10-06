<template>
  <div>
    <div class="row justify-content-center text-center">
      <div class="col-md-10 col-12">
        <div class="card" style="padding-bottom: 40px;">
          <div class="card-body" style="padding-top:100px; padding-bottom: 30px; ">
            <h3 class="text-center" style="color: #00ba3c;">{{ $t('register.enjoy_our_system') }}</h3>
          </div>
          <div class="card-footer border-top-0" style="background-color: #FFFFFF"  >
            <n-link to="/bills" class="p-4 grey" >{{ $t('menu.bills') }}</n-link>
            <n-link to="/admin/users" class="p-4 grey">{{ $t('menu.users')}}</n-link>
            <n-link to="/admin/clients" class="p-4 grey">{{ $t('menu.clients')}}</n-link>
            <n-link to="/admin/profile" class="p-4 grey">{{ $t('menu.profile')}}</n-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CompleteComponent",
  created(){
    this.$store.commit('SET_OBJ' , { name : 'show_client_notification' , value: true })
     this.delay(9000);
    if(window){
      window.location.href='/bills'
    }
  },
  methods:{
    async delay(val = 3000) {
      await new Promise(resolve => setTimeout(resolve, val));
    }
  }
}
</script>

<style scoped>
.grey{
  color: #969696;
}

</style>
