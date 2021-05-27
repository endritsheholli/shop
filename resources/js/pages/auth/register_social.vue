<template>
  <div class="app flex-row align-items-center">
    <div class="container">success</div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  middleware: "guest",
  layout: "nosidebar",

  metaInfo() {
    return {
      title: this.$t("registerclient"),
    };
  },

  data: () => ({
    errors: {},
  }),
  created() {
    let uri = window.location.search.substring(1);
    let params = new URLSearchParams(uri);

    this.registerclient(params.get("id_token"));
  },
  methods: {
    async registerclient(code) {
      // Register the user.
      const { data } = await axios.get(
        "/api/oauth/microsoft/callback?code=" + code
      );
      console.log(data);
      if (data.status === "failed") {
        this.errors = data.message;
        return;
      }

      const { token } = data;
      // Save the token.
      this.$store.dispatch("auth/saveToken", {
        token,
      });

      // Update the user.
      await this.$store.dispatch("auth/updateUser", {
        user: data,
      });
      

      this.$router.push({ name: "home" });
    },
  },
};
</script>
