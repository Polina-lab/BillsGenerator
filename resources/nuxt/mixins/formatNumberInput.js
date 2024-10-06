export default {
    methods: {

        /**
         * Formats input number to accept dot or comma as delimiter
         * @param {String} number number to format
         * @returns {String} formatted number
         */
        formatNumberInput(number) {
            let input = number.toString();
            input = input.replace(/[^0-9\,.]/g, '').replace(/\,/g, '.');
            input = input.replace(/[\.]/g, function(match, offset, all) {
                return match === '.' ? (all.indexOf('.') === offset ? '.' : '') : '';
            })
            return input;
        }
    }
}