<template>
<div>
  <ul class="d-flex justify-content-center register-heading" style="padding-inline-start: var(--padding-inline-start)">
    <li v-for="(step, idx) in steps_amount"
        class="breadcrumb"
        :class="[ { 'breadcrumb_0': first_child_different }, getClass(step) ]"
        :key="idx"
        @click="$emit('arrow_clicked', idx)">
      <span>
        <section v-if="!show_steps">
          {{ $t('bills.step', { step: step }) }}
        </section>
        <section v-if="step_list" class="break"></section>
        <section v-if="step_list">
          {{ $t(`${customTranslationGroup}.${step_list[idx].name}`) }}
        </section>
      </span>
    </li>
  </ul>
</div>
</template>
<script>

export default {
  name: "Arrows",
  props: ['steps_amount', 'step_list', 'page', 'first_child_different', 'show_steps', 'customTranslationGroup' ],
  data(){
    return{
      max_finished_stage: 0
    }
  },

  methods:{
    getClass(step){
      if (this.max_finished_stage < this.page) {
        this.max_finished_stage = this.page;
      }
      if (step == this.page) {
        return 'current';
      }
      else if(step < this.page || this.max_finished_stage > step) { // change to strict comparison (>) if you want unfinished stage to be gray
        return 'passed';
      }
      else {
        return 'pending';
      }
    }
  }
}

</script>
<style lang="scss" scoped>
@import "./assets/scss/var";
.container-fluid{
    max-width: 1000px
}
.current{
  background-color: $logo-green !important;
  filter: brightness(1.4);
  &:hover{
    filter: brightness(1.4);
  }
}

.passed{
  background-color: $logo-green !important;
  &:hover{
    filter: brightness(1.2);
  }
}

.pending{
  background-color: $secondary-color !important;
  filter: brightness(1.2);
}

.break {
  flex-basis: 100%;
  height: 5px;
  flex-shrink: 0;
}

$arrow_angle: 93%; // 100% = no angle
%arrow{
  background-color: $secondary-color;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  color: #fff;
  padding:0px;
  border-radius: 0;
  height: 60px;
  width: var(--width);
  background-color: var(--bg-color);
  height: var(--height);
  background: $secondary-color;
  font-family: 'Montserrat', sans-serif;
  font-weight: 400;
  font-size: 12px;
  text-align: center;
  @media screen and (max-width: 750px){
    display: none;
  }
  &:hover{
    cursor: pointer;
    filter: brightness(1.4);
  }
  &:active{
    background-color: $logo-green;
  }
  &:hover{
    background-color: $logo-green;
  }
  span {
    line-height: 1;
    padding-left: 11px;
    padding-right: 11px;
  }
}
br{
  line-height: 1;
}

.breadcrumb {
  // src https://bennettfeely.com/clippy/
  @extend %arrow;
  clip-path: polygon($arrow_angle 0%, 100% 50%, $arrow_angle 100%, 0% 100%, (100%-$arrow_angle) 50%, 0% 0%);
}

.breadcrumb_0:first-child {
  @extend %arrow;
  clip-path: polygon($arrow_angle 0%, 100% 50%, $arrow_angle 100%, 0% 100%, 0% 0%);
}

</style>
