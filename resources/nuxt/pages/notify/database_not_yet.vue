<template>
  <div>
    <TopBar />
    <RegistrationArrows page="1" />
    <div class="container ">
      <div class="card bg-light mt-6 mb-5">
        <div class="p-5">
          <h3>Oops database not found.</h3>
          <p>Please try again after 2 minutes or contact us.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TopBar from "@/components/common/TopBar";
import RegistrationArrows from "@/components/common/RegistrationArrows";
export default {
  layout: "login",
  name: "database_not_yet",
  components: {TopBar, RegistrationArrows},
}
</script>

<style scoped>

</style>
