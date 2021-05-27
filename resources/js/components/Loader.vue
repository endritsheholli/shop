<template>
  <div :class="classList">
    <div class="lds-dual-ring" />
  </div>
</template>

<script>
// https://github.com/nuxt/nuxt.js/blob/master/lib/app/components/nuxt-loading.vue
export default {
  name: 'Loader',

  props: {
    position: {
      type: String,
      default: ''
    }
  },
  computed: {
    classList () {
      return [
        'loader',
        this.calloutVariant
      ]
    },
    calloutVariant () {
      return this.position ? `loader-${this.position}` : ''
    }
  }
}
</script>

<style scoped>
.loader {
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  z-index: 10010;
  display: flex;
  align-items: center;
  justify-content: center;
}
.loader.loader-fixed{
    position: fixed;
}
.lds-dual-ring {
  display: inline-block;
  width: 64px;
  height: 64px;
}
.lds-dual-ring:after {
  content: " ";
  display: block;
  width: 46px;
  height: 46px;
  margin: 1px;
  border-radius: 50%;
  border: 5px solid #fff;
  border-color: #fff transparent #fff transparent;
  animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
