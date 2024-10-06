<template>
  <div class="modal show fade"
       ref="modal" 
       style='display: block;'>
    <div class="modal-dialog" :class="size">
      <div class="modal-content" :class="theme" v-ouside-click="clickOutsideHandler">
        <div class="modal-header">         
          <h5 class="modal-title alert-light" :class="theme">            
            <slot name="header" style="width: auto;">{{title}}</slot>
            </h5>
          <button type="button" class="close" aria-label="Close"
                  @click="closeModal">
            <span>&times;</span>
          </button>
        </div>
        <slot>
        </slot>
        <div class="modal-footer">
          <slot name="footer"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { OutsideClick } from "../../assets/js/utils/OutsideClick.js";

export default {
  name: "Modal",
  props: ['title', 'active', 'size' , 'theme', 'border-top'],
  directives:{ OutsideClick },
  methods: {
    /**
     * Emits close event to opened modal
     */
    closeModal() {
      this.$emit('closeModal', false)
    },

    /**
     * Redirects clicking outside of modal to closing function
     */
    clickOutsideHandler(e) {
      if (e && 
          e.target.localName == 'div' && 
          e.target.classList.contains('modal') &&
          e.target.id === 'DemoTosModal') {
        this.closeModal();
      }
    },
  }

}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
.modal {
  z-index: 10000;
  overflow-y: scroll;
}
.fade {
  background: rgba(27, 30, 33, 0.4);
}
.modal-title{
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 600;
}
.modal-content {
  height: auto;
  border-radius: 0.6rem;
}
button{
  font-size: 18px;
}
.demo__window{
  background-color: #ffffff;
  font-size: 37px;
  padding-top: 8px;
  padding-left: 10px;
  color: $logo-orange;
}
.dark{
  background-color: #494949;
  color: #ffffff;
  font-size: 36px;
  padding-top: 8px;
  padding-left: 10px;
  @media screen and (max-width: 650px) {
    font-size: 30px;
  }
  @media screen and (max-width: 550px) {
    font-size: 22px;
  }
  @media screen and (max-width: 443px) {
    display: flex;
    min-width: 100%;
    justify-content: center;
    align-items: center;
    text-align: center;
  }
}
@media only screen and (max-width: 768px) {
  .modal {
    z-index: 10000;
    overflow-y: scroll;
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0 !important;
  }
  .fade {
    background: rgba(27, 30, 33, 0.4);
  }
  .modal-dialog {
    width: 100%;
    max-width: none;
    height: 100%;
    margin: 0;
  }
  .modal-content {
    height: auto;
    border-radius: 0;
  }
}

</style>
