import { SET_OBJ } from "../assets/js/mutations";

export const state = () => ({
    apiList: [],
    apiFilter: {
        name: "",
        url: "",
        method: null,
        users: null,
        paginate: 30,
        page: 1,
    }
})

export const mutations = {
    SET_OBJ,
    ADD_REQUEST(state, id) {
        state.apiList.data[id].request.push({ type: "", value: "", name: '', has_required: false, desc: "" })
    },
    ADD_RESPONSE(state, id) {
        state.apiList.data[id].response.push({ type: "", name: '', code: "", example: "", desc: "" })
    },
    SET_APILIST_VAL(state, data) {
        state.apiList.data.find(i => i.id === data.id)[data.name] = data.value;
    },
    SPLICE_REQUEST(state, data) {
        state.apiList.data[data.idx].request.splice(data.id_request, 1);
    },
    SET_REQUEST(state, data) {
        state.apiList.data[data.idx].request[data.ids][data.name] = data.value;
    },
    SET_RESPONSE(state, data) {
        console.log(data);
        state.apiList.data[data.idx].response[data.idf][data.name] = data.value;
    }
}

export const actions = {
    async fetchApiList({ state, commit }) {
        const search = state.apiFilter;
        let url = '/api/admin/api_list?'
        for (const [key, value] of Object.entries(search)) {
            if (value) {
                url += url === '/api/admin/api_list?' ? `${key}=${value}` : `&${key}=${value}`
            }
        }
        const data = await this.$axios.$get(`${process.env.serverUrl}${url}`)
        commit('SET_OBJ', { name: 'apiList', value: data })
        commit('SET_OBJ', { name: 'apiFilter', key: 'page', value: data.current_page })
    },

    async updateApi({}, data) {
        const res = await this.$axios.$patch(`${process.env.serverUrl}/api/admin/api_list/` + data.id, data);
        return res;
    },

    async deleteApi({}, id) {
        const res = await this.$axios.$delete(`${process.env.serverUrl}/api/admin/api_list/` + id);
        return res;
    },

    async deleteRequest({}, id) {
        const res = await this.$axios.$delete(`${process.env.serverUrl}/api/admin/api_list/req/` + id)
        return res;
    },

    async deleteResponse({}, id) {
        const res = await this.$axios.$delete(`${process.env.serverUrl}/api/admin/api_list/resp/` + id)
        return res;
    },

    async reloadRoutes() {
        const res = await this.$axios.$get(`${process.env.serverUrl}/api/admin/api_list/reload`);
        return res;
    },

    async refreshApiList() {
        const res = await this.$axios.get(`${process.env.serverUrl}/api/admin/api_list/refresh`);
        return res;
    },

    async fetchStatistics() {
       const res = await this.$axios.get(`${process.env.serverUrl}/api/statistic`);
       return res;
    }
}

export const getters = {}
