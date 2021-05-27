<template>
  <button
    v-if="facebookAuth"
    class="btn btn-dark ml-auto"
    type="button"
    @click="login"
  >
    <fa :icon="['fab', 'facebook']" /> {{ $t("login_with") }} Facebook
  </button>
</template>

<script>
import { mapState } from 'vuex';
export default {
  name: "LoginWithFacebook",

  computed: {
    facebookAuth: () => window.config.facebookAuth,
    url: () => `/api/oauth/facebook`,
  },

  mounted() {
    window.addEventListener("message", this.onMessage, false);
  },

  beforeDestroy() {
    window.removeEventListener("message", this.onMessage);
  },

  methods: {
    async login() {
      // const newWindow = openWindow("", this.$t("login"));

      const url = await this.$store.dispatch("auth/fetchOauthUrl", {
        provider: "facebook",
      });
      window.location.href = url;
    },

    /**
     * @param {MessageEvent} e
     */
    async onMessage(e) {
      if (e.origin !== window.origin || !e.data.id) {
        return;
      }

      this.$store.dispatch("auth/saveToken", {
        token: e.data.token,
      });

      this.$router.push({ name: "home" });
    },
  },
};

/**
 * @param  {Object} options
 * @return {Window}
 */
function openWindow(url, title, options = {}) {
  if (typeof url === "object") {
    options = url;
    url = "";
  }

  options = { url, title, width: 600, height: 720, ...options };

  const dualScreenLeft =
    window.screenLeft !== undefined ? window.screenLeft : window.screen.left;
  const dualScreenTop =
    window.screenTop !== undefined ? window.screenTop : window.screen.top;
  const width =
    window.innerWidth ||
    document.documentElement.clientWidth ||
    window.screen.width;
  const height =
    window.innerHeight ||
    document.documentElement.clientHeight ||
    window.screen.height;

  options.left = width / 2 - options.width / 2 + dualScreenLeft;
  options.top = height / 2 - options.height / 2 + dualScreenTop;

  const optionsStr = Object.keys(options)
    .reduce((acc, key) => {
      acc.push(`${key}=${options[key]}`);
      return acc;
    }, [])
    .join(",");

  const newWindow = window.open(url, title, optionsStr);

  if (window.focus) {
    newWindow.focus();
  }

  return newWindow;
}
</script>
