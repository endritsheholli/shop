<template>
  <div :class="classList">
    <slot />
  </div>
</template>

<script>
export default {
  name: 'Callout',

  props: {
    variant: {
      type: String,
      default: ''
    }
  },
  computed: {
    classList () {
      return [
        'callout',
        this.calloutVariant
      ]
    },
    calloutVariant () {
      return this.variant ? `callout-${this.variant}` : ''
    }
  }
}
</script>
