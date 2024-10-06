export default {
  methods: {
    // https://stackoverflow.com/a/1349426
    /**
     * Generates a string of random chracters to use as a coupon
     * @param {number} length lengh of string to generate
     * @returns {string} generated string
     */
    generateString(length) {
      let result = '';
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      const charactersLength = characters.length;
      for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      return result;
    },

    /**
     * Generates a random positive number of given lenght
     * @param {number} length lengh of string to generate
     * @returns {number} generated number
     */
    generateNumber(length) {
      return Math.floor(Math.random() * ('1' + '0'.repeat(length)));
    }
  }
};