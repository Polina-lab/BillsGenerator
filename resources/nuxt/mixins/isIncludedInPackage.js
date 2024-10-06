export default {
    /*    async beforeMount() {
            await this.$store.dispatch('payment/fetchPaymentsPlans');
        },*/

    methods: {

        /**
         * Returns true if action is included in package tier
         * Compares current user package to list of existing packages
         * @param {string} tier tier to check availibility
         * @returns {boolean}
         */
        isIncludedInPackage(tier) {
            let tier_list = this.$store.state.payment.paymentPlans.map((pkg) => ({ name: pkg.string, id: pkg.id }));
            let user_pkg_id = this.$store.state.current_team.payment_plan_id;
            let pkg_id = tier_list.find(t => t.name === tier).id;
            if (user_pkg_id === null) return false;
            return (user_pkg_id >= pkg_id ? true : false);
        },
    }
}