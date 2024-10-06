export default {
    methods: {

        /**
         * Returns translated string given feature to translate and packet it belongs to
         * @param {stirng} feature feature to translate
         * @param {number} idx index of packet the feature belongs to
         * @returns {string} translated string
         */
        getPricingTKey(feature, idx) {
            switch (feature) {
                case 'limited_invoices':
                    return this.$t('pricing.limited_invoices', { invoices_amount: 10 });
                case 'multiple_user_accounts':
                    return this.$t('pricing.max_account_amount', { accounts_amount: idx == 0 ? 3 : 20 });
                case 'available_languages':
                    return this.$t(`pricing.${feature}`, { lang_list: this.$i18n.locales.map(lang => lang.code).join(' ') });
                case 'invoice_styles':
                    return (idx != 0) ?
                        this.$t('pricing.invoice_styles_more', { amount: 10 }) : this.$t(`pricing.${feature}`);
                case 'tech_support_time':
                    if (idx !== 3) {
                        return this.$t(`pricing.${feature}`, { start_time: '9:00', end_time: '19:00', timezone: 'GMT' });
                    }
                default:
                    return this.$t(`pricing.${feature}`);
            }
        }
    }
}