import { SET_OBJ } from "../assets/js/mutations";
import { Client } from "../assets/js/models/Client";
import { Company } from "../assets/js/models/Company";
import { headersJSON } from "assets/js/constants";


export const state = () => ({
    ClientsList: [],
    ClientDetail: new Client(),
    CompanyDetail: {},
    canChange: false,
   /* clientArray: [
        { name: "Jozhik", value: "1" },
        { name: "Jurgen", value: "2" },
        { name: "Estate client", value: "3" },
        { name: "GR Member", value: "4" }
    ],
   */ leppingArray: [
        { name: 'yes', value: "1" },
        { name: "no", value: "2" },
        { name: "Loyal customer", value: "3" },
        { name: "under negotiations", value: "4" }
    ],
 /*   workStatuses: [
        { name: "passive-refused", value: "1", color: "yellow" },
        { name: "under negotivation", value: "2", color: "green" },
        { name: "active client", value: "3", color: "blue" },
        { name: "sold rented", value: "4", color: "red" }
    ],
*/
})

export const mutations = {
    SET_OBJ,

    PUSH_COMPANY(state, data) {
        state.ClientDetail.companies.push(data);
        state.CompanyDetail = new Company();
    },


}

export const getters = {
    getClientDetail: state => {
        return JSON.parse(JSON.stringify(state.ClientDetail))
    },
    getCompanyDetail: state => {
        return JSON.parse(JSON.stringify(state.CompanyDetail))
    },
    getClientsList: state => {
      return JSON.parse( JSON.stringify(state.ClientsList))
    }
}

export const actions = {

    async fetchCompany({ commit }, id) {
        try {
            const val = await this.$axios.$get(`${this.$axios.baseUrl}/company/` + id)
            commit("SET_OBJ", { name: 'CompanyDetail', value: val });
        } catch (e) {
            console.log(e);
        }
    },

    async deleteCompany({ commit }, id) {
        const val = await this.$axios.$delete(`${this.$axios.baseUrl}/company/` + id)
        return val.data;
    },


    async saveCompany({ commit }, data) {
        try {
            const value = await this.$axios.$post(`${this.$axios.baseUrl}/company/create`, data)
            this.$toast[value.data['type']](value.data.msg).goAway(3000)
        } catch (e) {
            this.$toast.error("Oops: " + e.toString()).goAway(3000)
        }
    },

    async updateCompany({ commit }, data) {
        try {
            const res = await this.$axios.$post(`${this.$axios.baseUrl}/company/update/` + data.id, data)
            this.$toast[res.type](res.msg).goAway(3000)
            this.$router.push("/admin/clients");
        } catch (e) {
            this.$toast.error("Oops: " + e.toString()).goAway(3000)
        }
    },


    createClient({ commit }) {
        commit('SET_OBJ', { name: 'ClientDetail', value: new Client({status : false}) })
    },

    async fetchClient({ commit }, id) {
        try {
            const val = await this.$axios.get(`${this.$axios.baseUrl}/client/` + id)
            commit('SET_OBJ', { name: 'ClientDetail', value: val.data })
        } catch (e) {
            console.log(e);
        }
    },

    async saveClient({ state }, data) {
        try {
            const res = await this.$axios.post(`${this.$axios.baseUrl}/client/create`, data)
            this.$toast.success(res.data["msg"]).goAway(3000)
              //this.$router.push("/admin/clients");
        } catch (e) {
            this.$toast.error("Oops: " + e.toString()).goAway(3000)
        }
    },

    async updateClient({ state, commit }, data) {
        try {
          if(data.id) {
            const res = await this.$axios.post(`${this.$axios.baseUrl}/client/update/` + data.id, {"client": data})
            this.$toast.success(res.data["msg"]).goAway(3000)
           // this.$router.push("/admin/clients");
          }else{
            this.saveClient({state}, data );
          }
        } catch (e) {
            this.$toast.error("Oops: " + e.toString()).goAway(3000)
        }
    },
    async deleteClient({ commit }, id) {
    },

    createCompany({ commit } , data ) {
        commit('SET_OBJ', { name: 'CompanyDetail', value: new Company(data) })
    },

    async clientsDelete({ commit, dispatch }, data) {
        if (Array.isArray(data)) {
            const deleteArrJson = JSON.stringify({ selected: data })
            await this.$axios.$post(this.$axios.baseUrl + '/client/deleteList', deleteArrJson, headersJSON)
            await dispatch("fetchClientsList");
            this.$toast.success('Delete success').goAway(3000)
        } else {
            await this.$axios.$delete(`${this.$axios.baseUrl}/client/delete/${data}`)
            this.$toast.success('Delete success').goAway(3000)
        }
    },

    async fetchClients({commit} , data ){
      const res = await this.$axios.$get(`${this.$axios.baseUrl}/clients/find?${data.name}=`+ data.value)
      return res
    },

    async fetchClientsOrCompanies({ commit }, data) {
            const res = await this.$axios.$get(`${this.$axios.baseUrl}/clients/find?name=` + data)
            return res.data
    },

    async fetchClientsList({ commit }, req) {
        let url = "/clients/getAll"
        if (req != null) {
            url += req;
        }
        const value = await this.$axios.$get(this.$axios.baseUrl + url)
        commit('SET_OBJ', { name: 'ClientsList', value })
    },

    async deleteOwner({ commit }, ) {
        try {
            await this.$axios.$delete(`${this.$axios.baseUrl}/client/delete/${id}`)
        } catch (e) {
            console.log(e)
        }
    },


    changeStatus({ state }, data) {
        return new Promise((resolve, reject) => {
            this.$axios.$post('/client/change_status', data).then(
                resp => {
                    resolve(resp);
                },
                error => {
                    reject(error);
                }
            )
        })
    }

}
