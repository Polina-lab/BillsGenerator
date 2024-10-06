export default function({ app ,store }) {
  if (app.$cookies.get('acceptCookies')) {
      store.commit('SET_OBJ', {name: 'CookiesAccepted', value: true})
    } else {
      store.commit('SET_OBJ', {name: 'CookiesAccepted', value: false})
  }
}
