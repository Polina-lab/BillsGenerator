import { SET_OBJ} from "../assets/js/mutations";

export const state = () => ({
  firmsList: [],
})

export const mutations = {
  SET_OBJ
}

export const actions = {
  async fetchFirmsList({ commit }, id) {
    const res = await this.$axios.$get(`${this.$axios.baseUrl}/firms`)
    commit('SET_OBJ', { name: 'firmsList', value: res })
  },

  createFirm({ state, commit }, data) {
    return new Promise((resolve, reject) => {
      this.$axios.$post(`${this.$axios.baseUrl}/firm/create`, data.data).then(
        resp => {
          resolve(resp);
        },
        error => {
          reject(error);
        })
    })
  },

  async deleteFirm({ state, commit }, data) {
    return await this.$axios.$delete(`${this.$axios.baseUrl}/firm/delete/` + data)
  },

  async updateFirm({ state, commit }, data) {
    return  await this.$axios.$post(`${this.$axios.baseUrl}/firm/update/` + data.id, data.data)
  },

}
