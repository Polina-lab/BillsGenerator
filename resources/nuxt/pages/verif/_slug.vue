<template>
<div>
  <div class="container text-center">
    <h2>{{ result.msg }}</h2>
    <p v-if="result.type === 'success'">
      Please <n-link to="/login">login</n-link>
    </p>
    <p v-else>
      Please <n-link to="/register">register</n-link> or contact <a href="mailto:german@gloreal.ee">site administrator</a>
    </p>
  </div>
</div>
</template>

<script>
export default {
  name: "Verif",
  async asyncData({params , $axios }){
    const show_login = true;
    const slug = params.slug;
    const result = await $axios.$get(`${process.env.serverUrl}/api/verif/${params.slug}`).then(res => {
      return { type : res.type , msg :  res.msg};
    }).catch( (err) => {
      return { type : 'error' ,msg : err.response.data.msg}
    })
    return {
      result : result,
      slug : slug
    }
  }
}
</script>

<style scoped>

</style>
