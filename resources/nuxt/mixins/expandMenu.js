export default {
  methods: {
    expandMenu(e) {
      const allRootElms = document.querySelectorAll('.has-children')
      if (e.target.parentElement.classList.contains('has-children')) {
        allRootElms.forEach(e => e.classList.remove('expanded'))
        e.target.parentElement.classList.toggle('expanded')
      } else {
        e.target.parentElement.classList.add('expanded')
      }

    }
  }
}
