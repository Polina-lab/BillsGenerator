export default {
  methods: {
    slideNext(val) {
      if (val === 'sliderBig') {
        this.activeImage++
        if (this.activeImage > this.images.length - 1) {
          this.activeImage = 0
        }
        this.$refs.sliderThumb.goTo(this.activeImage)
      }
      this.$refs[val].next();

    },
    slidePrev(val) {
      if (val === 'sliderBig') {
        this.activeImage--
        if (this.activeImage < 0) {
          this.activeImage = this.images.length - 1
        }
        this.$refs.sliderThumb.goTo(this.activeImage)
      }
      this.$refs[val].prev();
    },
    sliderGoTo(idx) {
      this.activeImage = idx
      this.$refs.sliderBig.goTo(idx)
    }
  }
}
