<template>
  <div class="text-center row">
    <div class="mb-6 col-12" />
    <div class="mb-6 col-12" />
    <h3 class="mb-4 col-12">
      {{ $t('page_not_found') }}
    </h3>
    <div class="links col-12">
      <router-link :to="{ name: 'home' }">
        {{ $t('go_home') }}
      </router-link>
    </div>
    <div class="mb-6 col-12" />
    <div class="mb-6 col-12" />
  </div>
</template>

<script>
export default {
  name: 'NotFound',
  layout: 'basic'
}
</script>
