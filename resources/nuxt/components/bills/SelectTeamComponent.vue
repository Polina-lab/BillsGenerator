<template>
  <div>
    <Modal :title="$t('common.Select_currentFirm')"
           @closeModal="$store.commit('SET_OBJ' , {name : 'showModalToSelectTeam' , value : false })"
           v-if="isModalVisible"
    >
      <div class="alert alert-light">

        <div v-if="!$auth.user.has_buyer"  style="height: 90px; background-color: #0ca3db ;   display: flex; align-items: center;">
         <div style="width: 100%; text-align: center; color: #000000;" >BANNER
           <p>you can add your firm here </p>
         </div>
        </div>

        <table class="table table-borderless table-hover">
          <thead style="border-bottom: 1px solid #000000;">
          <tr>
            <td colspan="2" class="text-center">Team</td>
            <td class="text-center">Default</td>
            <td class="text-center">Actions</td>
          </tr>
          </thead>
        <tr v-for="(r, i)  in teams" :key="i">
          <td colspan="2" class="text-center">
           <span class="pointer" @click="setApiandNext(r.id)" v-tooltip="$t('User.team')">{{ r.name.length > 2 ? r.name : 'team_name_not_set'  }}</span>
              <i v-if="r.id == current_team.id" class="fa fa-check" style="color:darkseagreen"></i>
          </td >
          <td class="text-center">
           <input type="radio" :id="'radioButton_'+ i" name="default_team" @change="setDefaultTeam($event)"
                  :checked="r.id == $auth.user.default_team"
                  :value="r.id">
          </td>
          <td class="text-center">
            <span class="pointer" @click="goEdit(r.id)" >
              <i class="fa fa-edit p-1 pointer" v-tooltip="$t('User.edit_personal_data')" style="color: green"></i>
            </span>
            <n-link prefetch to="/bills">
              <i class="fas fa-chevron-right p-1 pointer" @click="setApiandNext(r.id)"  v-tooltip="$t('User.goto')" style="color: #0ca3db"></i>
            </n-link>
          </td>
        </tr>
        </table>
      </div>
    </Modal>
  </div>
</template>


<script>

import Modal from "@/components/common/Modal";
export default {
  name: "SelectTeamComponent",
  props: ["teams", "isModalVisible", "title"],
  components: {Modal },

  computed:{
    current_team() {
      return  JSON.parse(JSON.stringify(this.$store.state.current_team))
    },
  },

  methods:{
     setDefaultTeam(data){
       this.$store.dispatch('users/setDefaultTeam' , {'id' :  data.target.value}).then(
         res => {
           this.$auth.fetchUser();
           this.$toast.success(res['msg']).goAway(3000);
         },
         error => {
           this.$toast.error(error['msg']).goAway(3000);
         }
       )
    },

    async goEdit(id){
       await this.setApi(id);
       this.$store.commit("SET_OBJ" , {name : "showModalToSelectTeam" , value : false} );
       this.redirect('/admin/users/edit/' + this.$auth.user.local_data.id )
    },

    async setApiandNext(id){
      await  this.setApi(id);
      await this.$store.commit("SET_OBJ" , {name : "showModalToSelectTeam" , value : false} );
      this.redirect('/admin')
    },

    redirect(to){
      if (this.$router.currentRoute.path === to) {
        this.$forceUpdate()
      } else {
        this.$router.push(to)
      }
    },


    async setApi(id){
       let data =  this.teams.find(e => e.id == id);
       if(data) {
         this.$axios.baseUrl = process.env.serverUrl + "/api/" + data.key;
         await this.$store.commit("SET_OBJ", {name: 'current_team', value: data});
       }
    }

  }

}
</script>

