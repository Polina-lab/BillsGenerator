export default {
  methods: {
    changeNavTabs(e) {
      const tabs = document.querySelectorAll('.nav-link')
      const tabPanes = document.querySelectorAll('.tab-pane')
      tabs.forEach((i, idx) => {
        if (i.hash.includes(e)) {
          i.classList.add('active')
        } else {
          i.classList.remove('active')
        }
        if (tabPanes[idx].id === e ) {
          tabPanes[idx].classList.add('active')
          tabPanes[idx].classList.add('show')
        } else {
          tabPanes[idx].classList.remove('active')
          tabPanes[idx].classList.remove('show')
        }
      })
    }
  }
}
