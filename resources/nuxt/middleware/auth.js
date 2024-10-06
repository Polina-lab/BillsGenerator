export default function({store, redirect ,  $axios }) {
  let path = '/bills'
  if (!store.state.auth.loggedIn) {
    path = '/login';
  }else if (store.state.auth.user) {
      if (store.state.auth.user.available_teams) {
        let teams_count = store.state.auth.user.available_teams.length;
        switch (teams_count) {
          case 0:
             path = "/notify/database_not_yet";
            break;
          case 1:
            if (store.state.auth.user.available_teams[0].key !== "undefined") {
                 store.commit("SET_OBJ", {name: 'key', value: store.state.auth.user.available_teams[0].key}, {root: true});
                 $axios.baseUrl = process.env.serverUrl + "/api/" + store.state.auth.user.available_teams[0].key;
               }
             store.commit("SET_OBJ", {name: 'showModalToSelectTeam', value: true}, {root: true});
             path ="/admin";
            break;
          case teams_count > 1:
            store.commit("SET_OBJ", {name: 'showModalToSelectTeam', value: true}, {root: true});
            path ="/admin";
            break;
        }
      }
  }
  redirect(path)
}
