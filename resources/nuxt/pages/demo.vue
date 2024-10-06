<script>
export default {
  fetch ({  redirect }) {
    redirect(301, `/login?email=demo@test.ru&password=password`)
  }
}
</script>
