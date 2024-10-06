import { SET_OBJ, DELETE_ITEM_ARRAY } from "../assets/js/mutations";

export const state = () => ({
    permissions: [],
    permissionsTable: [],
    has_changed: 0,
    roles: []
})

const mutations = {
    SET_OBJ,
    DELETE_ITEM_ARRAY
}

export const actions = {

    /* to get all roles */
    async fetchRoles({commit}) {
      const roles = await this.$axios.$get(`${this.$axios.baseUrl}/roles`)
      commit('SET_OBJ', {name: 'roles', value: roles})
    },

    /*to create role*/
    async createNewRole({} , data){
      return await this.$axios.$post(`${this.$axios.baseUrl }/role/new`, data)
    },

    /*to delete role*/
    async deleteRole({} , id){
      return await this.$axios.$delete(`${this.$axios.baseUrl}/role/` +  id )
    },

    /*to update role*/
    async updateRole({} ,data ){
      return  await this.$axios.$patch(`${this.$axios.baseUrl}/role/`+ data.id , data )
    },

    /* to get permission table role includes { permissions , users } */
    async fetchRolesWithPermissions({commit}){
      const table = await this.$axios.$get(`${this.$axios.baseUrl}/roles_with_permissions`)
      commit('SET_OBJ' , {name : 'permissionsTable' , value : table})
    },

    /* to get all permissions */
    async fetchPermissions({commit}){
      const roles = await this.$axios.$get(`${this.$axios.baseUrl}/permissions`)
      commit('SET_OBJ', {name: 'permissions', value: roles})
    },

    /*??*/
    async getPermission_table({ commit }) {
        this.$axios.$get(`${this.$axios.baseUrl}/permission_table`)
            .then(resp => {
                commit('SET_OBJ', { name: 'permissionsTable', value: resp });
            })
    },


    updatePermissions({ commit, state }, data) {
      this.$axios.$post(`${this.$axios.baseUrl}/update_permission`, {
        permissions: state.permissionsTable[data.key].permissions ,
        role: data.id
      })
        .then(resp => {
          this.$toast.success(resp.msg).goAway(3000);
        })
    },

    /* that for user */
    async loadRoles({ commit }) {
        await this.$axios.$get(`${this.$axios.baseUrl}/user/roles`)
            .then(resp => {
                commit('SET_OBJ', { name: 'roles', value: resp });
            })
    },

}

const getters = {
    getRoles() {
        return state.roles;
    },
    getPermissions: state => {
        let perms = state.permissions;
        perms.forEach(perm => {
            perm.name = perm.sys_name;
        });
        return perms;
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
