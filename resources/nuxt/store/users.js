import { SET_OBJ, ADD_ITEM_ARRAY_BOTTOM, DELETE_ITEM_ARRAY } from "@/assets/js/mutations";
import { User, Buyer } from "@/assets/js/models/Users";
import { Bank } from "@/assets/js/models/Bill";

export const state = () => ({
  userDetail: new User(),
  users: [],
  roles: [],
  buyer: new Buyer(),
  timezones : [],
  currences : []
//  invoice_data: { isCustomInvoiceData: false }
});

export const mutations = {
  SET_OBJ,
  ADD_ITEM_ARRAY_BOTTOM,
  DELETE_ITEM_ARRAY,
  ARRAY_PUSH_NEW_BANK(state) {
    state.buyer.firm.banks.push(new Bank());
  },
  ARRAY_DELETE_BANK(state, idx) {
    state.buyer.firm.banks.splice(idx, 1);
  },
  SET_READED_NOTIF({ state }, data) {
    const notif = this.$auth.user.notifications.find(i => i.id === data);
    if (notif) {
      notif.read_at = Date.now();
    }
  }
}

export const getters = {
  getUserDetail: (state)=> {
    if(typeof state.userDetail !== 'undefined')
    return new User(JSON.parse(JSON.stringify(state.userDetail)))
  }
}

export const actions = {

  /**
   *  To get Users list
   * @param commit
   * @returns {Promise<void>}
   */
  async fetchUsersList({ commit}) {
    const users = await this.$axios.$get( this.$axios.baseUrl +'/users')
    commit('SET_OBJ', {name: 'users', value: users})
    return  users;
  },

  /** set Default team
   * @param commit
   * @param data
   * @returns {Promise<*>}
   */

  async setDefaultTeam({commit} , data ){
    return  await this.$axios.$post( '/api/admin/set_default_team' , data)
  },

  /** to get Timezones
   * @param commit
   * @returns {Promise<void>}
   */

  async getTimeZones({commit} ){
    const timezones  = await this.$axios.$get('/api/admin/settings/getTimeZone');
    commit('SET_OBJ' , {name : 'timezones' , value : timezones});
  },

    /** to get Timezones
   * @param commit
   * @returns {Promise<void>}
   */

  async getCurrences({commit} ){
    const currences  = await this.$axios.$get('/api/admin/settings/getCurrences');
    commit('SET_OBJ' , {name : 'currences' , value : currences});
  },

  /** To fetch user Details
   * @param commit
   * @param id
   * @returns {Promise<void>}
   */

  async fetchUserDetail({commit }, id) {
    if(!id) {
      //why not ?
      commit('SET_OBJ', {name: 'userDetail', value: this.$auth.user})
    }else {
      const res = await this.$axios.$get(`${this.$axios.baseUrl}/user/${id}`)
      if(res.code === 200) {
        commit('SET_OBJ', {name: 'userDetail', value: res.user})
      }else{
        this.$router.push('error')
      }
    }
  },

  async disable_notifications({} , data){
    return await this.$axios.$post(`/api/admin/disable_notifications` , {status : data})
  },

  async sendMessage({} , data){
    return await this.$axios.$post(`${this.$axios.baseUrl}/send_message` , data)
  },

  async sendInvite({} , data){
    return await this.$axios.post(`${this.$axios.baseUrl}/invite-user` , data );
  },

  async readNotif({commit} , data ){
    await  this.$axios.$put(`${this.$axios.baseUrl}/read-notif/` + data.data.id)
    commit("SET_READED_NOTIF", data.data.id)
  },

  async updateUser({}, data) {
    return  await this.$axios.$post(`${this.$axios.baseUrl}/user/update/${data.id}`, data);
  },

  async updatePassword({}, data) {
    return await this.$axios.$post(`${this.$axios.baseUrl}/user/updatePassword/${data.id}`,data);
  },

  async createBuyer({ commit, state }) {
    const res = await this.$axios.$post(`${this.$axios.baseUrl}/user/create`, state.buyer);
    commit('SET_OBJ', { name: 'user', value: res });
    return res;
  },

  async createUser({ commit, state }) {
    const res = await this.$axios.$post(`${this.$axios.baseUrl}/user/create`, state.userDetail);
    commit('SET_OBJ', { name: 'user', value: res });
    this.$router.push("/admin/users/");
    return res;
  },

  async deleteUser({commit, dispatch} , id){
    try {
      const res = await this.$axios.$delete(`${this.$axios.baseUrl}/user/` + id.id)
      this.$toast[res.type](res.msg).goAway(3000)
      await dispatch('fetchUsersList')
    }catch (e) {
      this.$toast.error("Oops:" + e.toString()).goAway(3000)
    }
  },

  async fetchAddresses({ commit }, address) {
    let instance = this.$axios.create();
    delete instance.defaults.headers.common['Authorization'];
    try {
        const data = await instance.$get(`https://inaadress.maaamet.ee/inaadress/gazetteer?address=${address}`);
        return data.addresses;
    } catch (e) {
        console.log(e)
    }
  },

}
