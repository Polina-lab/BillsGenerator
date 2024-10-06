
<script>
export default {
  name: "settings",
  layout: "admin",
  middleware:["auth"],
  fetch ({ store,  redirect }) {
    redirect(301, `/admin/users/edit/${store.$auth.user.local_data.id}?page=account`);
  }
}
</script>

