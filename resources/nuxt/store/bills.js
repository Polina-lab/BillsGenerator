import { SET_OBJ, ADD_ITEM_ARRAY_BOTTOM, ADD_ITEM_ARRAY_TOP, DELETE_ITEM_ARRAY } from "../assets/js/mutations";
import { FilterBill, Bill, VAT } from "../assets/js/models/Bill"
import { Client } from "../assets/js/models/Client";
import { Company } from "../assets/js/models/Company";

export const state = () => ({
  billsList: [],
  filterBills: new FilterBill(),
  eventBill: new Bill(),
  firmsList: [],
  status: [{ "name": "Fix", "id": 1 }, { "name": "Var", "id": 0 }],
  deal: [
    { "name": "Management", "id": 0 },
    { "name": "Commission", "id": 1 },
    { "name": "Booking", "id": 2 },
    { "name": "Credit", "id": 3 },
    { "name": "Other", "id": 4 }
  ],
  invoice_type: [{ name: "invoice", id: 0, }, { name: "prepaid", id: 1, }],
  unit: [
    { "name": "Object", "id": 0 },
    { "name": "Pieces", "id": 1, },
    { "name": "m2", "id": 2 },
    { "name": "m3", "id": 3 },
    { "name": "Another", "id": 4 }
  ],
  currency: [
    { "name": "EUR", "icon": "\u20AC", "id": 0, },
    { "name": "USD", "icon": "\u0024", "id": 1, },
    { "name": "RUB", "icon": "\u20BD", "id": 2 },
    { "name": "Another", "icon": null, "id": 3 }
  ],
  representative_status_list: [],
  chooseBankModal: false,
})


export const mutations = {
  SET_OBJ,
  ADD_ITEM_ARRAY_BOTTOM,
  ADD_ITEM_ARRAY_TOP,
  DELETE_ITEM_ARRAY,
  UPDATE_BILL_LIST(state, data) {
    if (data.sub && !data.new ) state.billsList[data.id][data.sub][data.sub_id][data.key] = data.value;
    else if(data.sub && data.new ) state.billsList[data.id][data.sub].push(data.value);
    else state.billsList[data.id][data.key] = data.value;
    state.billsList[data.id]['updated'] = 1;
  },
  DELETE_ORDER_FROM_BILL_LIST(state, data){
    if(data.sub && data.sub_id && !data.value ) state.billsList[data.id][data.sub].splice(data.sub_id , 1);
  },
  UPDATE_BILL_LIST_BY_KEY(state, data){
    state.billsList.find(i => i.id === data.id)[data.key] = data.value;
  },
  ADD_ORDERS(state, data) {
    state.eventBill.orders.push(data);
  },

}

export const actions = {
  setDefaultEventBill({ commit, dispatch }) {
    commit("SET_OBJ", { name: "eventBill", value: new Bill({ user_id: this.$auth.user.local_data.id, act_user_id: this.$auth.user.local_data.id }) })
    dispatch('clients/createClient');
    dispatch('clients/createCompany');
  },

  async saveGen({ commit }, data) {
    const res = await this.$axios.$post(`${this.$axios.baseUrl}/bills_gen/create`, data)
    commit("SET_OBJ", { name: "eventBill", value: res })
    return res;
  },

  async updateGen({ commit }, data) {
    const res = await this.$axios.$patch(`${this.$axios.baseUrl}/bill_gen/update/` + data.id, data.data);
    if (res["type"] === "success") {
      this.$toast.success(res.msg).goAway(3000);
    } else
      this.$toast.error(res.msg).goAway(3000);
    return res;
  },

  async sendMail({ commit }, data) {
    const res = await this.$axios.$post(`${this.$axios.baseUrl}/bills_send_mail`, data);
    if (res["type"] === "success") {
      this.$toast.success(this.$t('bills.invoice_sent')).goAway(3000);
    } else
      this.$toast.error(res.msg).goAway(3000);
    return res;
  },


  async sendNext({ }, data) {
    return await this.$axios.$post(`${this.$axios.baseUrl}/bills_send_next`, data)
  },


  async getDataById({ commit }, id) {
    const res = await this.$axios.$get(`${this.$axios.baseUrl}/bills/` + id)
     commit("SET_OBJ", { name: "eventBill", value: new Bill(res) })
    if (res.clients) {
      commit('clients/SET_OBJ', { name: 'ClientDetail', value: new Client(res.clients) }, { root: true });
    } else {
      commit('clients/SET_OBJ', { name: 'ClientDetail', value: new Client() }, { root: true });
    }
    if (res.companies) {
      commit('clients/SET_OBJ', { name: 'CompanyDetail', value: new Company(res.companies) }, { root: true });
    } else {
      commit('clients/SET_OBJ', { name: 'CompanyDetail', value: new Company() }, { root: true });
    }
  },

    async fetchFirmsList({ commit }) {
        const res = await this.$axios.$get(`${this.$axios.baseUrl}/firms`)
        res.forEach(firm => {
            if (firm.vat.length === 0) {
                firm.vat.push(new VAT({ default: true }));
            }
        });
        commit('SET_OBJ', { name: 'firmsList', value: res })
    },

    async fetchRepresentivesList({ commit }) {
        const res = await this.$axios.$get(`${this.$axios.baseUrl}/respresentatives`)
        commit('SET_OBJ', { name: 'representative_status_list', value: res })
    },

    async cloneBill({ commit }, id) {
        return await this.$axios.$get(`${this.$axios.baseUrl}/bill/clone/` + id)
    },

   getUniqNumber({ state, commit }, data) {
     return new Promise((resolve, reject) => {
       this.$axios.$post(`${this.$axios.baseUrl}/bill/getUniq`, data).then(
         resp => {
           if (resp['bills']) {
             commit('ADD_ITEM_ARRAY_BOTTOM', {name: "billsList", value: resp['bills']});
           }
           resolve(resp)
         },
         error => {
           reject(error)
         })
     });
   },
    async updateBill({}, data) {
        return await this.$axios.$post(`${this.$axios.baseUrl}/bills/update/` + data.id, data)
    },

    async saveObj({}, data) {
      return  await this.$axios.$post(`${this.$axios.baseUrl}/bills/update/${data.id}`, data)
    },

    /**
     * Sends requset to server to delete requisites
     * @param {Object} arg0 - empty object to pass
     * @param {Number} bank_id id of bank requisites to delete
     * @returns
     */
    async deleteRequisites({}, requisite_id) {
        return this.$axios.$delete(`${this.$axios.baseUrl}/firm/deleteBank/${requisite_id}`);
    },

    async deleteObj({}, id ) {
      return await this.$axios.$delete(`${this.$axios.baseUrl}/bills/` + id);
    },

    async createFirm({}, data) {
        return await this.$axios.$post(`${this.$axios.baseUrl}/firm/create`, data)
    },

    async deleteFirm({}, data) {
        return await this.$axios.delete(`${this.$axios.baseUrl}/firm/delete/` + data)
    },

    async updateFirm({}, data) {
        return await this.$axios.$post(`${this.$axios.baseUrl}/firm/update/` + data.id, data.data)
    },

    async  createBill({}, data) {
        return await this.$axios.$post(`${this.$axios.baseUrl}/bills/create`, data)
    },

    async fetchIncomingBills({commit}){
      const data = await this.$axios.$get(`${this.$axios.baseUrl}/bills?incoming=true`)
      commit('SET_OBJ', { name: 'billsList', value: data })
    },

    async fetchBillsList({ state, commit  }) {
        const search = state.filterBills
        let url = '/bills?';
            if (search != null) {
              for (const [key, value] of Object.entries(search)) {
                if (value) {
                  url += url === '/bills?' ? `${key}=${value}` : `&${key}=${value}`
                }
              }
            }
        const data = await this.$axios.$get(`${this.$axios.baseUrl}${url}`)
        commit('SET_OBJ', { name: 'billsList', value: data })
    },

    /**
     * Retrieves list of values from server given list to retrieve
     * @param {{state: object, commit: object}} - object to read data from
     * @param {'bills'|'cadaster'|'keys'} table table to populate
     */
    async fetchTableDataList({ state, commit }, table) {
        const search = state.filterParameters
        let url = `/${table}?`
        if (search != null) {
            for (const [key, value] of Object.entries(search)) {
                if (value) {
                    url += url === `/${table}?` ? `${key}=${value}` : `&${key}=${value}`
                }
            }
        }
            const data = await this.$axios.$get(`${this.$axios.baseUrl}${url}`)
            commit('SET_OBJ', { name: `${table}List`, value: data })
    }
}

export const getters = {
    getFirmList: state => {
        return JSON.parse(JSON.stringify(state.firmsList))
    },
    getEventBill: state => {
        return JSON.parse(JSON.stringify(state.eventBill))
    },
    countBillsList : state => {
        return state.billsList.length;
    },
    getStatus : state => {
        return state.status;
    },
    getDeal : state => {
        return state.deal;
    }


}
