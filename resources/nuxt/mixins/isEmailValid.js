export default {
    methods: {

        /**
         * Returns true if given email is valid
         * @param {string} email email to validate
         * @returns {boolean}
         */
        isEmailValid(email) {
            let email_regex = /^[A-Za-z]{2,}[A-Za-z0-9]*([.]?[A-Za-z0-9]+)*@[A-Za-z]+\.[A-Za-z]{2,4}([.][A-Za-z]{2,4})*?$/;
            return email_regex.test(email);
        }
    }
}