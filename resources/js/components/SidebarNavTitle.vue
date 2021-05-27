<template>
  <li :class="classList">
    <template v-if="wrapper && wrapper.element">
      <component :is="wrapper.element" v-bind="wrapper.attributes">{{ $t(name) }}</component>
    </template>
    <template v-else>
      <span v-if="icon">
        <i :class="icon" />
      </span>
      {{ $t(name) }}
    </template>
  </li>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      default: ""
    },
    icon: {
      type: String,
      default: null
    },
    classes: {
      type: String,
      default: ""
    },
    wrapper: {
      type: Object,
      default: () => {}
    }
  },
  computed: {
    classList() {
      return ["nav-title", ...this.itemClasses];
    },
    itemClasses() {
      return this.classes ? this.classes.split(" ") : "";
    }
  }
};
</script>
