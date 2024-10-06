<template>
  <!--<div class="to-top"
    @click="scrollToTop"><i class="fas fa-angle-up"></i>
  </div>-->
  <div class="to-top" v-show="show"
    @click="scrollToTop"><i class="fas fa-chevron-up"></i>
  </div>
</template>

<script>
    export default {
      name: "ToTop",
      data : () => ({
        show : false,
      }),

      created () {
        if (process.client) {
          window.addEventListener('scroll', this.handleScroll);
        }
      },
      destroyed () {
        if (process.client) {
          window.removeEventListener('scroll', this.handleScroll);
        }
      },
      methods: {
        scrollToTop() {
          scroll(0, 0)
        },
        handleScroll () {
          if(process.client){
            if(window.scrollY > 200){
              this.show = true;
            }else{
              this.show = false;
            }
          }
        }

      }
    }
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
.to-top {
   position: fixed;
   width: 64px;
   height: 64px;
   text-align: center;
   line-height: 3;
   right: 45px;
   bottom: 35px;
   z-index: 9999;
}
 .to-top i {
   color: $secondary-light;
   font-size: 64px;
   position: absolute;
   top: 8px;
   left: 10px;
   z-index: 999999 !important;
  @media (max-width: 768px) {
     bottom: 15px;
  }
  &:hover {
    color: $secondary-color;
  }
 }
  /*.to-top {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    height: 50px;
    position: fixed;
    right: 20px;
    bottom: 20px;
    background: rgba(31, 166, 221, 0.5);
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 27px;
    transition: .3s;
  }
  .to-top:hover {
    background: rgba(31, 166, 221, 0.8);
  }*/

</style>
