<template>
<div>


  <div v-if="show_text && list.find(el => el.step === page)" class="text-center p-4">
    <h3 class="blue" v-if="list.find(el => el.step === page).text !== undefined">
      {{ $t('register.'+ list.find(el => el.step === page).text  )}}
    </h3>
  </div>


  <Arrows :step_list="list"
          :steps_amount="list.length"
          :page="page"
          :first_child_different="true"
          :style="arrow_styles"
          :customTranslationGroup="'register'"/>
</div>
</template>

<script>
import Arrows from '../elements/Arrows.vue';
export default {
  name: "RegistrationArrows",
  components: { Arrows },
  props: ['page', 'show_text'],
  beforeMount(){
    if( this.$auth.user && Boolean(this.$auth.user.has_buyer))
      this.list = this.list_for_buyer;
    else
      this.list = this.list_for_invited_user
  },
  data(){
    return{
      list:[],
      list_for_buyer: [
        { step: 1, name: "get_password" },
        { step: 2, name: "log_proceed" },
        { step: 3, name: 'enter_personal_data', text: 'personal_data' },
        { step: 4, name: 'select_service_package', text: 'package' },
        { step: 5, name: 'choose_payment_method', text: 'payment' },
        { step: 6, name: 'complete_order', text:'welcome'}
      ],
      list_for_invited_user: [
        { step: 1, name: "get_password" },
        { step: 2, name: "log_proceed" },
        { step: 3, name: 'enter_personal_data', text: 'personal_data' },
        { step: 6, name: 'complete_order', text:'welcome'}
      ],
    }
  },

  methods:{
  },

  computed: {
    arrow_styles() {
      return {
      '--width': '190px',
      '--height': '60px',
      '--padding-inline-start': '0px'
      }
    }
  }
}

</script>
<style lang="scss" scoped>

.blue{
  color: #44afe6;
}
</style>
