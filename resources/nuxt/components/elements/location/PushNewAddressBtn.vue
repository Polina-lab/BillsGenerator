<template>
  <button class="btn btn-primary mt-4"
          @click="pushNewAddress">Push new
  </button>
</template>

<script>
import {Address} from "../../../assets/js/models/Location";

export default {
  name: "PushNewAddressBtn",
  props: ['module', 'name', 'field'],
  computed: {
    fullAddress() {
      return this.$store.getters['location/getFullAddress']
    }
  },
  methods: {
    pushNewAddress() {
      if (this.fullAddress.full_address.length) {
        // const location = JSON.parse(JSON.stringify(this.$store.state.owner.ownerDetail.location))

        const location = JSON.parse(JSON.stringify(this.$store.state[this.module][this.name][this.field]))

        location.unshift(this.fullAddress)
        this.$store.commit(`${this.module}/SET_OBJ`,
          {name: this.name, key: this.field, value: location})
        this.$store.commit('location/SET_OBJ', {name: 'fullAddress', value: new Address()})

        // Hide component "Location"
        this.$store.commit('location/SET_OBJ', {name: 'locationComponent', value: false})
        this.$store.commit('location/SET_OBJ', {name: 'pushNewAddressBtn', value: false})
      }
    }
  }
}
</script>

<style scoped>

</style>
