import { SET_OBJ } from "../assets/js/mutations";

export const state = () => ({
    order: {},
    orders: [],
    can_create_new_category: true,
});

export const mutations = {
    SET_OBJ,
}

export const actions = {

    async fetchOrdersList({ commit }, data) {
        var url = this.$axios.baseUrl + '/orders?'
        if (data != null) {
            for (const [key, value] of Object.entries(data)) {
                if (value) {
                    url += url === `/orders?` ? `${key}=${value}` : `&${key}=${value}`
                }
            }
        }
        const orders = await this.$axios.$get(url)
        commit('SET_OBJ', { name: 'orders', value: orders })
        return orders;
    },

    async searchOrderBy({commit} , data){
      var url = this.$axios.baseUrl + '/orders?paginate=false&'
      if (data != null) {
        for (const [key, value] of Object.entries(data)) {
          if (value) {
            url += `&${key}=${value}`
          }
        }
      }
      return  await this.$axios.$get(url);
    },

    async fetchOrder({}, id) {
        return await this.$axios.$get(this.$axios.baseUrl + `/order/` + id);
    },

    async deleteOrder({ state, commit }, data) {
        return await this.$axios.$delete(`${this.$axios.baseUrl}/orders/` + data)
    },

    async saveProduct({ dispatch }, data) {
        await this.$axios.$post(this.$axios.baseUrl + `/orders${data.create}`, data.data).then(
            resp => {
                this.$toast.success(resp.msg).goAway(3000);
            }
        ).catch(err => {
            this.$toast.error(err.msg).goAway(3000);
        })
    },

    async updateProduct({}, data) {
        await this.$axios.$patch(this.$axios.baseUrl + '/order/' + data.id, data).then(
            resp => {
                this.$toast.success(resp.msg).goAway(3000);
            }
        ).catch(err => {
            this.$toast.error(err.msg).goAway(3000);
        })
    },

    async fetchProductData({}, data) {
        await this.$axios.$get(
            `${this.$axios.baseUrl}/order_stat/${data.product_id}/?start=${data.periodStartDate}&end=${data.periodEndDate}`
        ).then(resp => {
            return resp;
        })
    },

    deleteProduct({ dispatch }, id) {
        this.$axios.$delete(this.$axios.baseUrl + '/order/' + id).then(
            resp => {
                this.$toast.success(resp.msg).goAway(3000);
                dispatch('fetchOrdersList');
            }
        ).catch(err => {
            this.$toast.error("Oops: not deleted").goAway(3000);
        })

    },

    async fetchCategoriesList({ commit }, id) {
        const res = await this.$axios.$get(this.$axios.baseUrl + '/categories/' + id)
        return res;
    },

    /**
     * Sends request to server with new object data to store in categories table
     * @param {object} - empty object to pass as first argument
     * @param {object} data data to store on backend
     * @param {object} data.data - object cointaining new category data
     * @param {number} data.firms_id id of firm to which new catwegory should belong
     */
    async createCategory({}, data) {
        await this.$axios.$post(this.$axios.baseUrl + `/categories/create`, { data: data, firms_id: data.firms_id }).then(
            res => {
                this.$toast.success(res.msg).goAway(3000);
            },
            err => {
                this.$toast.error(err.msg).goAway(3000);
            }
        )
    },
    /**
     * Sends request to server with new object data to store in categories table
     * @param {object} - empty object to pass as first argument
     * @param {object} data data to store on backend
     * @param {object} data.data - data of category to be updated
     * @param {number} data.id id of category to update
     */
    async updateCategory({}, data) {
        await this.$axios.$post(this.$axios.baseUrl + `/categories/` + data.id, data).then(
            res => {
                this.$toast.success(res.msg).goAway(3000);
            },
            err => {
                this.$toast.error(err.msg).goAway(3000);
            }
        )
    },


    async saveCategoryTree({}, data) {
        return await this.$axios.$post(this.$axios.baseUrl + `/categories/create`, {
            firms_id: data.firms_id,
            data: data.tree
        });
    },

    async rebuildCategoryTree({ commit, dispatch }, data) {
        const res = await this.$axios.$post(this.$axios.baseUrl + `/categories/rebuild`, data).then(
            res => {
                this.$toast.success(res.msg).goAway(3000);
            },
            err => {
                this.$toast.error(err.msg).goAway(3000);
            }
        )
        return res;
    },

    async deleteCategory({}, id) {
        return await this.$axios.$delete(this.$axios.baseUrl + '/categories/' + id);
    },
}

export const getters = {
    getOrders: (state) => {
        return JSON.parse(JSON.stringify(state.orders))
    }
}
