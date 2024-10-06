<template>
  <div class="tool-tip">
    <slot></slot>
  </div>
</template>

<script>
  export default {
    name: "ToolTip"
  }
</script>

<style scoped>
  .tool-tip {
    position: absolute;
    display: none;
    left: 50px;
    z-index: 10000;
    padding: 10px 10px;
    border-radius: 4px;
    background: rgba(27, 30, 33, 0.5);
    font-size: 14px;
    font-weight: 400;
    color: #ffffff;
  }
  .tool-tip.active {
    display: block;
  }
</style>
