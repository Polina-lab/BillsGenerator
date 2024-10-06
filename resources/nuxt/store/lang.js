import {SET_OBJ} from "../assets/js/mutations";
import {headersJSON} from "../assets/js/constants";

export const state = () => ({
  langList: [],
  translateGroups: {},
  translateList: {},
  translateFilter: {
    page: 1,
    per_page: 15,
    group: '',
    search: ''
  }
})

export const mutations = {
  SET_OBJ,

  SET_TRANSLATE_FILTER_GROUP(state, data){
    state.translateFilter.group = data;
  }

}

export const actions = {
  async fetchLanguages({commit}) {
    try {
      const languages = await this.$axios.$get(`${process.env.serverUrl}/api/admin/langs`) //ok
      commit('SET_OBJ', {name: 'langList', value: languages})
    } catch (e) {
      console.log(e)
    }
  },
  async fetchTranslateGroups({commit}) {
    try {
      const groups = await this.$axios.$get(`${process.env.serverUrl}/api/admin/trans/groups`)
      commit('SET_OBJ', {name: 'translateGroups', value: groups})
    } catch (e) {
      console.log(e)
    }
  },

  async createGroup({commit , dispatch} , name ){
    const res = await  this.$axios.$post(`${process.env.serverUrl}/api/admin/trans/group/add` , name )
    this.$toast[res.status](res.msg).goAway(3500)
    if(res.code === 200) {
      await dispatch('fetchTranslateGroups')
    }
    return name;
  },

  async exportTrans(){
    const res  = await  this.$axios.$get(`${process.env.serverUrl}/api/admin/trans/export`)
    return res;
  },

  async exportTransBackend(){
    const res  = await  this.$axios.$get(`${process.env.serverUrl}/api/admin/trans/export_backend`)
    return res;
  },

  async fetchTranslateList({state, commit}) {
    let url = '/api/admin/trans'
      url += '?'

      for (const [key, value] of Object.entries(state.translateFilter)) {
        if (value) {
          url += `${key}=${value}&`
        }
      }

    try {
      const list = await this.$axios.$get(`${process.env.serverUrl}${url}`)
      commit('SET_OBJ', {name: 'translateList', value: list})
    } catch (e) {
      console.log(e)
    }
  },
  async createTranslateKey({dispatch}, key) {
    try {
      const jsonKey = JSON.stringify(key)
      const res = await this.$axios.$post(`${process.env.serverUrl}/api/admin/trans/key/add`, jsonKey, headersJSON)
      this.$toast[res.status](res.msg).goAway(3500)
      dispatch('fetchTranslateList')
    } catch (e) {
      console.log(e)
    }
  },
  async updateTranslateValue({commit}, payload) {
    try {
      const res = await this.$axios.$patch(`${process.env.serverUrl}/api/admin/trans/key/update`, payload)
    } catch (e) {
      console.log(e)
    }
  },
  async deleteTranslateKey({ dispatch }, key) {
      const res = await this.$axios.$delete(
        `${process.env.serverUrl}/api/admin/trans/key/delete?group=${key.group}&key=${key.key}`
      );
      this.$toast[res.status](res.msg).goAway(3500)
      dispatch('fetchTranslateList')
  },
}

export const getters = {}