<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'inline'" />
    <div class="container">
      <div class="row">
        <div class="col-12">
          <router-link class="pull-right btn btn-primary" :to="{name: 'categories'}">
            <i class="fa fa-align-justify" /> Lista e Kategorive
          </router-link>
        </div>
      </div>
      <div class="mb-4" />
      <form @submit.prevent="CreateEdit" @keydown="form.onKeydown($event)">
        <div class="row">
          <div class="input-group col-md-6 col-12">
            <input
              v-model="form.name"
              required
              :class="{ 'is-invalid': form.errors.has('name') }"
              class="form-control inputs mb-3"
              type="text"
              name="name"
              placeholder="Emri *"
            />
            <has-error :form="form" field="name" />
          </div>
          <div class="input-group col-md-6 col-12">
            <b-form-select
              v-model="form.parentId"
              :class="{ 'is-invalid': form.errors.has('parentId') }"
              class="form-control inputs mb-3"
              type="text"
              placeholder="Kategoria Prind *"
            >
              <option value :selected="true">Kategoria Parent</option>
              <option v-for="cats in categories" :value="cats.id">{{ cats.name }}</option>
            </b-form-select>
            <has-error :form="form" field="parentId" />
          </div>
          <div class="input-group col-md-3 col-12">
            <label>
              <input
                v-model="form.isImportant"
                required
                :class="{ 'is-invalid': form.errors.has('isImportant') }"
                class="inputs mb-3"
                type="checkbox"
                name="isImportant"
                style="width: 20px; height: 20px; margin-right: 10px"
                placeholder="Te shfaqet ne Ballin *"
              /> Te shfaqet ne Ballin *
            </label>
            <has-error :form="form" field="isImportant" />
          </div>
        </div>

        <div class="input-group col-12">
          <input
            type="file"
            class="form-control"
            v-on:change="handleFileUpload()"
            id="file"
            ref="file"
          />
        </div>

        <hr />
        <div class="social-login-wrapper">
          <div class="row">
            <div class="col-12 text-right">
              <v-button :loading="form.busy" class="orangebtn pull-right">Ruaj</v-button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>
</template>

<style>
.dropdown-toggle::after {
  visibility: hidden;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<script>
import Form from "vform";
import Multiselect from "vue-multiselect";
import Loader from "~/components/Loader";

export default {
  components: {
    Multiselect,
    Loader
  },

  metaInfo() {
    return {
      title: "Edito Kategorin "
    };
  },

  data: () => ({
    parameters: [],
    categories: [],
    loadingall: true,
    form: new Form({
      name: "",
      parentId: "",
      parameters: [],
      isImportant: true
    }),
    file: ""
  }),

  mounted: function() {
    this.getAllParameters();
    this.getCategoryParent();
  },
  methods: {
    async CreateEdit() {
      this.loadingall = true;
      let formData = new FormData();
      for (var key in this.form) {
        formData.append(key, this.form[key]);
      }
      formData.append("images", this.file);

      try {
        await this.axios
          .post("/api/categories/create", formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          })
          .then(res => {
            this.loadingall = false;
            this.form.name = "";
            this.form.parentId = "";
            this.form.parameters = [];
            this.form.isImportant = true;
          });
      } catch (e) {
        console.log(e);
      }
    },
    async getCategoryParent() {
      try {
        await this.axios.get("/api/categories/getParents").then(res => {
          const { data } = res;
          this.categories = data.response;
          this.loadingall = false;
        });
      } catch (e) {
        console.log(e);
      }
    },
    async getAllParameters() {
      try {
        await this.axios
          .get("/api/parameters/getParents", this.form)
          .then(res => {
            const { data } = res;
            let resParams = data.response || [];

            this.parameters = resParams;
          });
      } catch (e) {
        console.log(e);
      }
    },
    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    customBrandName(option) {
      return option.name;
    }
  }
};
</script>
