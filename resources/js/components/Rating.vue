<template>
  <div class="d-flex">
    <img src="../icons/star.svg" />
    <img src="../icons/star.svg" />
    <img src="../icons/star.svg" />
    <img src="../icons/star.svg" />
    <img src="../icons/half-star.svg" />
    <span class="mx-2">{{ ratingNumber }}</span>
  </div>
</template>

<script>
export default {
  name: "rating",
  props: {
    ratingNumber: {
      default: 0,
    },
  },
};
</script>
