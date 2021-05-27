<template>
  <div class="invalid-feedback">
    <slot />
  </div>
</template>

<script>
export default {
  props: {
    errors: {
      type: [Number, String],
      default: 0
    }
  }
}
</script>
