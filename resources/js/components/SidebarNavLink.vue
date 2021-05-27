<template>
  <div v-if="isExternalLink">
    <a :href="url" :class="classList">
      <i :class="icon" />
      {{ name }}
      <b-badge v-if="badge && badge.text" :variant="badge.variant">{{ badge.text }}</b-badge>
    </a>
  </div>
  <div v-else>
    <router-link :to="url" :class="classList" v-on:click.native="exitSidebar()">
      <i :class="icon" />
      {{ name }}
      <b-badge v-if="badge && badge.text" :variant="badge.variant">{{ badge.text }}</b-badge>
    </router-link>
  </div>
</template>

<script>
export default {
  name: "SidebarNavLink",
  props: {
    name: {
      type: String,
      default: ""
    },
    url: {
      type: String,
      default: ""
    },
    icon: {
      type: String,
      default: ""
    },
    badge: {
      type: Object,
      default: () => {}
    },
    variant: {
      type: String,
      default: ""
    }
  },
  computed: {
    classList() {
      return ["nav-link", this.linkVariant];
    },
    linkVariant() {
      return this.variant ? `nav-link-${this.variant}` : "";
    },
    isExternalLink() {
      if (this.url.substring(0, 4) === "http") {
        return true;
      } else {
        return false;
      }
    }
  },
  methods: {
    exitSidebar() {
      let $ = require("jquery");
      $(document).ready(function() {
        $(".sidebar").removeClass("mobile-sidebar");
      });
    }
  }
};
</script>
