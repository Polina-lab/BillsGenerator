import { SET_OBJ } from "../assets/js/mutations";

export const state = () => ({
    paymentPlans: [],
    selectedPlan: {}
})

export const mutations = {
    SET_OBJ,
}

export const getters = {
    getPaymentsPlans: state => {
        return JSON.parse(JSON.stringify(state.paymentPlans))
    },
    getSelectedPlan: state => {
        return JSON.parse(JSON.stringify(state.selectedPlan))
    }
}

export const actions = {
    async fetchPaymentsPlans({ commit }) {
        const res = await this.$axios.get(`${process.env.serverUrl}/api/payment_plans`)
        commit("SET_OBJ", { name: 'paymentPlans', value: res.data });
    },

    async setPaymentPlans({ commit }, data) {
        const res = await this.$axios.post(`${process.env.serverUrl}/api/admin/payment_plan`, data)
        return res;
    }

    /**
     * getPaymentPlan ??
     */

}
