<template>
  <div>
    <div class="card-title">
      <h2 class="m-2">{{ $t('menu.team') }}</h2>
    </div>
    <div class="card-body pt-0">
      <div class="row first__green">
        <div class="col-12 col-sm-6">
          <label for="name">{{ $t('User.team_name') }}<sup class="red">*</sup></label>
          <input type="text"
                class="form-control"
                id="name"
                :value="team.name"
                @change="changeTeam($event)"
          />
        </div> 
        <div class="col-12 col-sm-6 box__logs">
          <button v-if="isTeamSettingsVisible" class="btn float-right btn-outline-success show__logs" @click="isTeamSettingsVisible = false; isTeamHistoryVisible = true;">{{ $t('User.show_logs') }}</button>
           <button v-if="isTeamHistoryVisible" class="btn float-right btn-outline-success show__settings" @click="isTeamSettingsVisible = true; isTeamHistoryVisible = false;">Return to team settings</button>
        </div>
      </div> 

      <div v-if="isTeamSettingsVisible">
        <div class="row">
            <div class="col-12 col-sm-6">
            <label for="payer">{{ $t('User.payer') }}<sup class="red">*</sup></label>
            <select class="form-control" id="payer"><!-- @change="changeTeam($event)" -->
              <option v-for="(payer, index) in $store.state.users.users" 
                      :key="index" :value="payer.id" 
                      :selected="team.payer">
                {{ payer.username }}
              </option>
            </select>
          </div>
          <div class="col-12 col-sm-6">
            <label for="timezone">{{ $t('User.current_timestamp') }}<sup class="red">*</sup></label>
            <select class="form-control" @change="changeTeam($event)" id="timezone">
              <option v-for="(zone, index) in timezones" 
                      :key="index" :value="index" 
                      :selected="index == team.timezone">
                {{ zone }}
              </option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-sm-6">
            <label for="currency">{{ $t('User.current_currency') }}<sup class="red">*</sup></label>
            <select class="form-control"  id="currency"><!-- @change="changeTeam($event)" -->
              <option v-for="(currency, index) in currences" 
                      :key="index" :value="index" 
                      :selected="index == team.currency">
                {{ currency }}
              </option>
            </select>
          </div>
          <div class="col-12 col-sm-6">
            <label for="font">{{ $t('User.current_font') }}<sup class="red">*</sup></label>
            <select class="form-control"  id="font"><!-- @change="changeTeam($event)" -->
              <option v-for="(font, index) in fonts" 
                      :key="index" :value="index" 
                      :selected="index == team.font">
                {{ font }}
              </option>
            </select>
          </div>
        </div>
        <div class="pt-2">
          <button class="btn float-right btn-outline-success save">{{ $t('common.save') }}</button><!-- @click="saveTeam"  -->
        </div>
      </div>
    </div>

    <div v-if="isTeamHistoryVisible">
      <TeamHistory></TeamHistory>
    </div>

  </div>
</template>

<script>
import TeamHistory from '../../user/TeamHistory.vue';
export default {
  name: "UsSetTeamComponent",
  components: { TeamHistory },
    data: () => ({
      isTeamSettingsVisible: true,
      isTeamHistoryVisible: false
  }),
  computed: {
    team() {
      return this.$store.state.current_team;
    },
    user() {
      return this.$store.getters['users/getUserDetail'];
    },
    timezones() {
      return JSON.parse(JSON.stringify(this.$store.state.users.timezones));
      },
    currences() {
      return JSON.parse(JSON.stringify(this.$store.state.users.currences));
      }
    },
    beforeMount(){
      this.$store.dispatch('users/getTimeZones');
      this.$store.dispatch('users/getCurrences');
    },
  methods: {
    changeTeam(data){
      this.$store.commit("SET_OBJ", { name: 'current_team', key: data.target.id, value: data.target.value }, { root: true });
    },
    saveTeam(){
      //
    }
  }
}
</script>

<style lang="scss" scoped>
@import "./assets/scss/_var.scss";
.box__logs{
  display: flex;
  margin-top: auto;
  margin-bottom: 0;
  justify-content: flex-end;
  @media screen and (max-width: 575px){
    padding-top: 30px;
  }
}
.btn-outline-success:focus {
    box-shadow: none;
}
.show__logs{
  height: 51%;
  vertical-align: bottom;
  width: 100%;
  background-color: $secondary-light;
  color: $logo-green;
  border: none;
  font-weight: 600;
  &:hover{
    background-color: $secondary-color;
    color: #ffffff;
  }
}
.show__settings{
  height: 51%;
  vertical-align: bottom;
  width: 100%;
  background-color: $secondary-color;
  color: #ffffff;
  border: none;
  font-weight: 600;
  &:hover{
    background-color: $secondary-light;
    color: $logo-green;
  }
}
.first__green{
  border-bottom: 2px solid $logo-green;
  margin-bottom: 30px;
  padding-bottom: 30px;
}
.save{
  margin-top: 60px;
  background-color: $logo-green;
  color: #ffffff;
  width: 150px;
  &:hover{
    background-color: #228138;
  }
}
</style>
