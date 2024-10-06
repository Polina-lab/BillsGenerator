export default {
  methods: {
    updateStore(module, name, value, key, subkey) {

      if (key && !subkey) {
        this.$store.commit(`${module}/SET_OBJ`, {name, key, value})
      } else if (key && subkey) {
        this.$store.commit(`${module}/SET_OBJ`, {name, key, subkey, value})
      } else if (!key && value) {
        this.$store.commit(`${module}/SET_OBJ`, {name, value})
      } else {
        this.$store.commit(`${module}/SET_OBJ`, {name, value: this[name]})
      }
    }
  }
}
