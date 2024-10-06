<template>
  <section class="card border-success mt-3">
    <div class="card-header">
      {{ $t("clients.list_address")}}
    </div>

    <div class="card-body">
      <div class="d-flex">
          <label>Address</label>
          <InputSearch id="new_full_search"
          dispatchModule="location" dispatchName="searchAddressLocalDb"
          name="pikkaadress" value="pikkaadress"
          @SelectedAddress="saveAddressToState($event)"/>

        <button class="btn btn-success ml-3 add-new"
                @click="pushNewAddressBtnHandler">Add new
        </button>
      </div>
      <div class="row">
        <div class="col-md-4 col-12">
          <table class="table table-borderless mt-3" v-if="locationList.length">
            <tr v-for="(loc, idx) in locationList">
              <td>{{idx+1}}</td>
              <td>{{loc.full_address}}</td>
              <td>
                <i class="fas fa-times text-danger del-item"
                   @click="deleteAddress(idx)"></i>
              </td>
            </tr>
          </table>
        </div>
<!--        <div class="col-md-8 col-12">
          <FullAddress/>
        </div>-->
      </div>
    </div>
  </section>
</template>

<script>

  //import FullAddress from "../common/FullAddress";
  import InputSearch from "@/components/elements/InputSearch";
  import {Address} from "../../assets/js/models/Location";

  export default {
    name: "ClientAddresses",
    components: { InputSearch},
    data() {
      return {
        searchAddress: "",
        locationComponent: false,
        addNewLocation: false,
        locationList: []
      }
    },
    computed: {
      client() {
        return this.$store.getters['clients/getClientDetail']
      },
      locations() {
        return this.$store.state.clients.locations
      },
      streets() {
        return this.$store.state.location.streets
      }
    },
    methods: {
      searchAddressLocalDb() {
        this.$store.dispatch('location/searchAddressLocalDb', this.searchAddress);
      },
      selectedAddress(e) {
        const address = e.target.value
        const fullAddress = this.streets.find(s => s.full_address === address)
        if (this.streets.length && fullAddress) {
          this.locationList.unshift(address)
          this.client.location.unshift(fullAddress.id)
          this.$store.commit('clients/SET_OBJ', {name: 'ClientDetail', key: 'location', value: this.client.location})
          this.$store.commit('location/SET_OBJ', {name: 'fullAddress', value: fullAddress})
          this.$store.commit('location/SET_OBJ', {name: 'pushNewAddressBtn', value: true})
          this.$store.dispatch('location/fetchTowns', fullAddress.county_id)
          this.$store.dispatch('location/fetchDistricts', fullAddress.town_id)

          this.searchAddress = ''
          this.$store.commit('location/SET_OBJ', {name: 'streets', value: []})
        }
      },

      deleteAddress(idx) {
        this.locationList.splice(idx, 1)
        //this.client.location.splice(idx, 1)
        //this.$store.commit('clients/SET_OBJ', {name: 'clientDetail', key: 'location', value: this.client.location})
        this.$store.commit('location/SET_OBJ', {name: 'fullAddress', value: new Address()})
        this.$store.commit('location/SET_OBJ', {name: 'pushNewAddressBtn', value: false})
      },
      pushNewAddressBtnHandler() {
        this.$store.commit('location/SET_OBJ', {name: 'fullAddress', value: new Address()})
        this.$store.commit('location/SET_OBJ', {name: 'pushNewAddressBtn', value: true})
      },
      saveAddressToState(addressData){
        this.locationList.push({ 'id' :addressData.id ,'full_address': addressData.full_address});
        this.$store.commit('location/SET_OBJ', {name: 'fullAddress', value: addressData})
      }

    },
    destroyed() {
      this.$store.commit('location/SET_OBJ', {name: 'fullAddress', value: {}}, {root:true})
    }
  }
</script>

<style scoped>
  .add-new {
    white-space: nowrap;
  }

  .del-item {
    cursor: pointer;
  }

</style>
