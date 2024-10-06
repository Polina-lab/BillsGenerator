<template>
  <Modal id="DemoTosModal" 
         :title="$t('demo_notification.title')"
         size="modal-lg" 
         theme="demo__window"
         @closeModal="$emit('closeModal', false)">
    <div class="alert alert-light">
      <p class="font__size">{{ $t('demo_notification.thanking')}}</p>
      <p class="font__size margin__size">{{ $t('demo_notification.tos_header')}}</p>
      <p class="pl-4">{{ $t('demo_notification.tos_p1') }}</p>
      <p class="pl-5">{{ $t('demo_notification.tos_p1_1') }}</p>
      <p class="pl-4">{{ $t('demo_notification.tos_p2') }}</p>
      <p class="pl-4">{{ $t('demo_notification.tos_p3') }}</p>
      <p class="pl-4">{{ $t('demo_notification.tos_p4') }}</p>
      <p class="pl-4">{{ $t('demo_notification.tos_p5') }}</p>
      <p class="pl-4">{{ $t('demo_notification.tos_p6') }}</p>
      <p class="font__size">{{ $t('demo_notification.footer') }} <a class="yellow__href" href="/register">{{ $t('demo_notification.footer_link') }}</a></p>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" v-model="toggle" id="flexCheckChecked" >
        <label class="form-check-label" for="flexCheckChecked">{{ $t('demo_notification.check_box') }}</label>
      </div>
    </div>
    <template v-slot:footer>
      <div class="button__box">
        <button type="button"
              class="demo"
              @click="$emit('closeModal', false); $router.push('/register');">
              {{ $t('demo_notification.register')}}
        </button>
        <button v-if="toggle" type="button" class="btn btn-secondary"
              @click="$emit('closeModal', false); $router.push('/demo');">
              {{ $t('demo_notification.continue') }}
        </button>
        <button v-else type="button" class="btn btn-secondary"
              @click="$store.commit('SET_OBJ' , { name : 'show_client_notification' , value: true })"
               v-tooltip="{ content: $t('demo_notification.must_agree'),
                            placement: 'top-center',
                            classes: ['tos-modal'],
                            show: show_client_notification,
                            trigger: 'auto'
                          }">
              {{ $t('demo_notification.continue') }}
        </button>
        </div>
    </template>
  </Modal>
</template>

<script>
import Modal from '../common/Modal.vue'
export default {
  name: 'DemoTosModal',
  components: { Modal },
  props: ['isDemoModalVisible'],
  data: () => ({
    toggle: false
  }),
  computed:{
    show_client_notification(){
      return this.$store.state.show_client_notification;
     }
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
.alert-light{
  background-color: #ffffff;
  font-size: 14px;
  font-weight: 500;
  line-height: 1.3;
  font-family: 'Montserrat', sans-serif;
  margin: -20px 0px -10px 0px;
  padding-left: 30px;
  padding-right: 30px;
  text-align: justify;
}
.alert-light{
  font-size: 13px;
  line-height: 1.5;
}
%propertis{
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 25px;
  height: 50px;
  border: 1px solid;
  font-family: 'Montserrat', sans-serif !important;
  font-weight: 600;
  padding-left: 30px;
  padding-right: 30px;
  letter-spacing: 0.5px;
  line-height: 1;
}
.demo{
  @extend %propertis;
  margin-left: 10px;
  background-color: $logo-orange;
  color: #ffffff;
  border-color: $logo-orange;
  &:hover{
    background-color: #be891c;
    border-color: #be891c;
   }
  @media screen and (max-width: 991px){
    height: 60px;
  }
  @media screen and (max-width: 450px){
    position: relative;
    margin-left: auto;
    margin-right: auto;
  }
}
.btn-secondary{
  @extend %propertis;
  background-color: $secondary-light;
  &:hover{
    background-color: $secondary-color;
    border-color: $secondary-color;
  }
  @media screen and (max-width: 991px){
    height: 60px;
  }
  @media screen and (max-width: 450px){
    position: relative;
    margin-left: auto;
    margin-right: auto;
  }
}
.font__size{
  font-size: 18px;
  font-weight: 600;
  letter-spacing: 0.3px;
  line-height: 1.3;
}
.margin__size{
  position: relative;
  margin-top: -17px;
}
.yellow__href{
  color: $logo-orange;
  &:hover{
    color: #be891c;
  }
}
input[type=checkbox] + label::before{
   display: flex;
   justify-content: center;
   align-items: center;
   width: 30px;
   height: 30px;
   background-color: #ffffff;
   border: 1px solid $secondary-color;
   color: $secondary-color;
   content: '';
   position: absolute;
   font-size: 26px;
   margin-left: -40px;
   margin-top: -5px;
   z-index: 99;
}
input[type=checkbox]:checked + label::before{
  content: '\2714';
  color: $secondary-color;
}
input[type=checkbox]:hover + label::before{
  background-color: $logo-orange;
}
input[type=checkbox]:checked:hover + label::before{
  content: '\2714';
  background-color: $logo-orange;
  color:#ffffff;
  border: none;
}
input[type=checkbox]{
    overflow: hidden;
    color: #ffffff;
}
input[type=checkbox] + label {
    position: relative;
    cursor: pointer;
}
.form-check-label{
  margin-left: 20px;
}
.button__box{
  position: relative;
  display: flex;
  min-width: 100%;
  justify-content: space-between !important;
  padding-right: 20px;
  padding-left: 10px;
  @media screen and (max-width: 450px){
    flex-direction: column;
  }
}
</style>
