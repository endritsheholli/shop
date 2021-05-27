<template>
  <label :class="classList">
    <input type="checkbox"
           class="switch-input"
           :value="value"
           :checked="isChecked"
           @change="handleChange"
    >
    <template v-if="isOn">
      <span class="switch-label" :data-on="on" :data-off="off" />
    </template>
    <template v-else>
      <span class="switch-label" />
    </template>
    <span class="switch-handle" />
  </label>
</template>

<script>
export default {
  computed: {
    classList () {
      return [
        'switch',
        this.switchType,
        this.switchVariant,
        this.switchPill,
        this.switchSize
      ]
    },
    switchType () {
      return this.type ? `switch-${this.type}` : `switch-default`
    },
    switchVariant () {
      return this.variant ? `switch-${this.variant}` : `switch-secondary`
    },
    switchPill () {
      return !this.pill ? null : `switch-pill`
    },
    switchSize () {
      return this.size ? `switch-${this.size}` : ''
    },
    isChecked () {
      return this.checked === this.value
    },
    isOn () {
      return !this.on ? null : true
    }
  },
  methods: {
    handleChange ({ target: { checked } }) {
      this.$emit('change', checked ? this.value : this.uncheckedValue)
    }
  }
}
</script>
