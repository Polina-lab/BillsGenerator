export default {
    methods: {

        /**
         * Returns true if action is permittable
         * @param {string} action action to check for
         * @param {number} objectOwnedByUserID id of user to check if it is self
         * @returns {boolean}
         */
        isElementInteractible(action, objectOwnedByUserID = null) {
          if(this.$auth.user.local_data) {
            if (objectOwnedByUserID === this.$auth.user.local_data.id) {
              return true;
            }
          }
            // return this.$auth.user.assign.includes(action); //TODO: fix that
          return true;
        }
    }
}
