<template>

<section>
  <div class="card border-success">
    <div class="card-header">
      {{ $t("clients.addition")}}
    </div>

    <div class="card-body row">
    <div class="col-md-3 col-12" v-if="$nuxt.$route.name !== 'estate-id'"> <!--в этой форме данные о договоре  не нужны ли - давай так уберем (изначально не было)  -->
      <label for="work_status">{{ $t('clients.work_status')}}<sup class="text-danger">*</sup></label>
      <select class="form-control"
              id="work_status"
              required
              v-model="client.work_status"
              @change="updateStore('clients', 'ClientDetail',client)">
        <option value="">{{ $t('estate.please_select')}}</option>
        <option :value="arr.value"
                v-for="(arr, idx) in $store.state.clients.workStatuses"
                :key="idx">
          {{ arr.name}}
        </option>
      </select>
    </div>

    <div class="col-md-3 col-12">
      <label for="lepping">{{ $t('clients.lepping_array')}}<sup class="text-danger">*</sup></label>
      <select class="form-control"
              id="lepping"
              required
              v-model="client.lepping"
              @change="updateStore('clients', 'ClientDetail',client)">
        <option value="">{{ $t('estate.please_select')}}</option>
        <option :value="arr.value"
                v-for="(arr, idx) in $store.state.clients.leppingArray"
                :key="idx">
          {{ arr.name }}
        </option>
      </select>
    </div>

    <div class="col-md-3 col-12">
      <label for="client">{{ $t('clients.client_array')}}<sup class="text-danger">*</sup></label>
      <select class="form-control"
              id="client"
              required
              v-model="client.client"
              @change="updateStore('clients', 'ClientDetail' ,client)">
        <option value="">{{ $t('estate.please_select')}}</option>
        <option :value="arr.value"
                v-for="(arr, i) in $store.state.clients.clientArray"
                :key="i">
          {{ arr.name }}
        </option>
      </select>
    </div>


      <div class="col-md-3 col-12" v-if="$nuxt.$route.name !== 'estate-id'"><!--брокер выбран ранее на estate-->
        <label for="agent">{{ $t('clients.broker')}}<sup class="text-danger">*</sup></label>
        <select class="form-control"
                id="agent"
                required
                v-model="client.agent"
                @change="updateStore('clients', 'ClientDetail',client)">
          <option :value="arr.id" v-for="(arr, idx) in $store.state.users.brokers" :key="idx">
            {{ arr.username }}
          </option>
        </select>
      </div>
  </div><!--card-body row-->
  </div><!--card-->
</section>
</template>

<script>
import updateStore from "../../mixins/updateStore";

export default {
  name: "clientAddition",
  mixins: [updateStore],
  computed: {
    client() {
      return this.$store.getters['clients/getClientDetail']
    }
  },



}
</script>

<style scoped>

</style>
