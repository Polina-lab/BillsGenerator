<template>
<div>

<CompanyCreate></CompanyCreate>

</div>
</template>

<script>

import CompanyCreate from "@/components/bills/CompanyCreate";

export default {
  middleware:[ 'auth' ],
  layout: "admin",
  name: "CompanyEdit",
  async asyncData({store , params}) {
      await store.dispatch('clients/fetchCompany' , params.id);
  },

  components: {CompanyCreate },

}
</script>

<style scoped>

</style>
