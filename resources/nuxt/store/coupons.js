import { SET_OBJ, ADD_ITEM_ARRAY_TOP, DELETE_ITEM_ARRAY } from "../assets/js/mutations";

export const state = () => ({
    couponsList: [],
    couponsData: {}
});

export const mutations = {
    SET_OBJ,
    ADD_ITEM_ARRAY_TOP,
    DELETE_ITEM_ARRAY
}
export const actions = {
    /**
     * Retrieves list of coupons from server
     * @param {{state: object, commit: object}} - object to read data from
     */
    async fetchCouponsList({ state, commit }) {
        var search = null;
        search = state.filterCoupons;
        let url = `/api/admin/coupons?`
        if (search != null) {
            for (const [key, value] of Object.entries(search)) {
                if (value) {
                    url += url === `/api/admin/coupons?` ? `${key}=${value}` : `&${key}=${value}`
                }
            }
        }
        const coupons = await this.$axios.$get(`${process.env.serverUrl}${url}`)
        try {
            // if (Array.isArray(coupons)) {
            // commit(`coupons/SET_OBJ`, { name: `couponsList`, value: coupons }, { root: true })
            // } else {
            commit(`coupons/SET_OBJ`, { name: `couponsList`, value: coupons.data }, { root: true })
            commit(`coupons/SET_OBJ`, { name: `couponsData`, value: coupons }, { root: true })
            commit(`coupons/SET_OBJ`, { name: `couponsData`, key: 'data', value: null }, { root: true })
                // }
        } catch (e) {
            console.log(e)
        }
    },

    /**
     * Sends request to server with new data to store in coupons table
     * @param {object} - empty object to pass as first argument
     * @param {object} data data to store on backend
     * @returns {Promise<void>} `promise`
     */
    async saveCoupons({}, data) {
        return await this.$axios.$post(`${process.env.serverUrl}/api/admin/coupons/create`, data);
    },

    /**
     * Sends request to server with updated data to store in coupons table
     * @param {object} - empty object to pass as first argument
     * @param {object} data data to store on backend
     * @returns {Promise<void>} `promise`
     */
    async updateCoupons({}, data) {
        return await this.$axios.$patch(`${process.env.serverUrl}/api/admin/coupons/${data.id}`, data);
    },

    /**
     * Sends requset to server to delete data
     * @param {number} id id of data to delete
     * @returns {Promise<void>} `promise`
     */
    async deleteCoupons({}, id) {
        return  await this.$axios.$delete(`${process.env.serverUrl}/api/admin/coupons/${id}`)
    },

    async fetchCoupon({}, code) { //TODO: uncomment when route is created
        return  await this.$axios.$get(`${process.env.serverUrl}/api/admin/coupon/${code}`);
    }
}

export const getters = {

}
