<template>
  <div>
    <label v-if="label" class="dropdown__label">{{label}}</label>
    <div class="dropdown" @click="activeToggle"
         :style="square ? 'border-radius: 0' : 'border-radius: .25rem'"
         :ref="btnName" :id="btnName">
      <span class="dropdown__name">{{name}}</span>
      <i class="fas fa-chevron-down"/>
      <div class="dropdown__items"
           :style="alignRight ? 'right: 0' : 'left: 0'">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "DropdownInput1",
    props: ['name', 'btnName', 'square', 'label', 'square', 'alignRight'],

    mounted() {

      this.$nextTick(() => {
        const currentBtn = document.querySelector(`#${this.btnName}`);
        document.addEventListener('click', function (e) {
          // console.log(e.target.id)
          if (!(currentBtn == e.target || currentBtn.contains(e.target))) {
            currentBtn.classList.remove('active')
          }
        });
      })

    },
    methods: {
      activeToggle() {
        this.$refs[this.btnName].classList.toggle('active')
      },

    }
  }
</script>

<style scoped lang="scss">
  .dropdown {
    position: relative;
    padding: .25rem .5rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    white-space: nowrap;
    cursor: pointer;
    transition: 0.3s;

    &__name {
      margin-right: 15px;
    }

    &.active {
      border-color: #80bdff;
      outline: 0;
      box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25)
    }
  }

  .dropdown__items {
    display: none;
    position: absolute;
    top: 29px;
    background-color: #ffffff;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    max-height: 200px;
    padding: 10px 20px;
    z-index: 10000;
    text-align: left;
    overflow-y: auto;
  }

  .dropdown.active {
    z-index: 100;
  }

  .dropdown.active .dropdown__items {
    display: block;
  }

  .dropdown i {
    position: absolute;
    right: 5px;
    top: 10px;
    font-size: 10px;
    transition: transform .2s;
  }

  .dropdown.active i {
    transform: rotate(180deg)
  }


  .dropdown__items__item input[type="checkbox"] {
    margin-left: 0px;
  }

  .dropdown__items__item input[type="checkbox"] + label {
    margin-left: 17px;
  }


</style>
