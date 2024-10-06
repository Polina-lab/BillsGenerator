import { SET_OBJ } from "../assets/js/mutations";

export const state = () => ({
    key: null,
    current_team : {},
    payCur: [
        { id: 0, name: '%' },
        { id: 1, name: '€' },
    ],
    showModalToSelectTeam: false,
    isDemoModalVisible: false,
    CookiesAccepted: true,
    show_client_notification : false,
})

export const mutations = {
    SET_OBJ,
}

export const actions = {
    async nuxtServerInit({ dispatch }) {
      await dispatch('SET_KEY');
      await dispatch('payment/fetchPaymentsPlans')
    },

    async SET_KEY({commit}) {
        if (!this.$axios.baseUrl) {
            if (this.$auth.user) {
               commit("SET_OBJ", {name : 'show_client_notification' , value :  Boolean(this.$auth.user.disable_suggestions) })
                try {
                  if(this.$auth.user.available_teams.length === 1 ) {
                    this.$axios.baseUrl = process.env.serverUrl + "/api/" + this.$auth.user.available_teams[0].key;
                    commit("SET_OBJ", {name: 'current_team', value: this.$auth.user.available_teams[0]});
                  }
                  else if(this.$auth.user.default_team !== null){
                    let team =  this.$auth.user.available_teams.find(e => e.id == this.$auth.user.default_team);
                    this.$axios.baseUrl = process.env.serverUrl + "/api/" + team.key;
                    commit("SET_OBJ" , { name:'current_team' , value : team});
                  }else{
                     this.$axios.baseUrl = process.env.serverUrl + "/api/" + this.$auth.user.available_teams[0].key;
                     commit("SET_OBJ" , { name:'current_team' , value : this.$auth.user.available_teams[0]});
                     commit('SET_OBJ' , { name: 'showModalToSelectTeam' , value : true});
                  }
                } catch (e) {
                    this.$router.push("/login")
                }
            }
        }
    },
}

export const getters = {

}
