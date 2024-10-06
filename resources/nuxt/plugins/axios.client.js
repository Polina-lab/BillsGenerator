export default function({ $axios, redirect, store }) {
  if (store.state.key === null) {
    if (store.state.auth.user) {
      let teams_count = store.state.auth.user.available_teams.length;
      if (store.state.current_team.key && store.state.current_team.key !== "undefined") {
        store.commit("SET_OBJ", {name: 'key', value: store.state.current_team.key}, {root: true});
        $axios.baseUrl = process.env.serverUrl + "/api/" + store.state.current_team.key;
      } else if (teams_count === 0) {
        redirect("/notify/database_not_yet");
      } else if (teams_count === 1) {
        store.commit("SET_OBJ", {name: 'key', value: store.state.auth.user.available_teams[0].key}, {root: true});
        store.commit("SET_OBJ", {name: 'current_team', value: state.auth.user.available_teams[0]}, {root: true})
        $axios.baseUrl = process.env.serverUrl + "/api/" + store.state.auth.user.available_teams[0].key;
      } else if (teams_count > 1) {
        store.commit("SET_OBJ", {name: 'showModalToSelectTeam', value: true}, {root: true});
        redirect("/admin");
      } else {
        redirect("/login");
      }
    }
  }
}
