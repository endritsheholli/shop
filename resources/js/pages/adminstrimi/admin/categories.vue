<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'inline'" />
    <div>
      <div class="mb-2" />
      <div class="row">
        <div class="col-lg-12">
          <div class="col-lg-12">
            <div class="mob-sec-header mb-3">
              <div class="container p-0">
                <button v-on:click="triggerSidebar()" id="triside">
                  <i class="fa fa-bars"></i> Paneli
                </button>
              </div>
            </div>
          </div>
          <!-- <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb pull-left">
              <li class="breadcrumb-item">
                <router-link :to="{name: 'dashboard'}">
                  <i class="fa fa-home" />
                </router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Kategorit
              </li>
            </ol>
            <div class="pull-right">
              <router-link
                :to="{name: 'categoryedit', params:{id: 'new'}}"
                class="btn btn-primary"
              >
                Shto kategori
              </router-link>
            </div>
          </nav>-->
          <div class="pull-right">
            <router-link
              :to="{name: 'categoryedit', params:{id: 'new'}}"
              class="btn btn-primary"
            >Shto kategori</router-link>
          </div>
        </div>
      </div>
      <div class="mb-4" />

      <div class="product-single-tabs">
        <ul class="nav nav-tabs" role="tablist">
          <li v-for="(param,index) in categories" :key="param.id" class="nav-item">
            <a
              class="nav-link"
              :class="{ 'active': index == 0 }"
              data-toggle="tab"
              :href="'#product-'+param.id"
              role="tab"
              :aria-controls="'product-'+param.id"
              aria-selected="false"
              @click="getCategoriesbyID(param.id)"
            >{{ param.name }}</a>
          </li>
        </ul>
        <div class="tab-content">
          <div
            v-for="(param,index) in categories"
            :id="'product-'+param.id"
            :key="param.id"
            class="tab-pane fade"
            :class="{ 'show active': index == 0 }"
            role="tabpanel"
            :aria-labelledby="'product-'+param.id"
            style="position: relative"
          >
            <Loader v-if="loading" :position="'inline'" />
            <div class="row">
              <div class="col-12 mb-3">
                <button
                  class="pull-right btn btn-primary"
                  data-toggle="modal"
                  data-target="#removeParam"
                  @click="showModal"
                >Fshije {{ param.name }}</button>
                <button
                  v-if="editable"
                  class="pull-right btn btn-primary"
                  @click="editMode()"
                >Ndrysho</button>
                <button
                  v-if="!editable"
                  class="pull-right btn btn-primary"
                  @click="editMode()"
                >Anulo</button>
              </div>
              <div class="col-12 col-md-5">
                <form @submit.prevent="CreateEdit" @keydown="formCat.onKeydown($event)">
                  <div class="row">
                    <div class="input-group col-12">
                      <h4>Te dhenat e Kategoris</h4>
                    </div>
                    <div class="input-group col-12">
                      <input
                        v-model="formCat.name"
                        required
                        :class="{ 'is-invalid': formCat.errors.has('name') }"
                        class="form-control inputs mb-3"
                        type="text"
                        name="name"
                        placeholder="Emri *"
                        :disabled="editable"
                      />
                      <has-error :form="formCat" field="name" />
                    </div>

                    <div class="input-group col-12">
                      <label>
                        <b-form-checkbox
                          v-model="formCat.isImportant"
                          class="mb-2 mr-sm-2 mb-sm-0"
                          :class="{ 'is-invalid': formCat.errors.has('isImportant') }"
                          name="isImportant"
                          placeholder="Te shfaqet ne Ballin *"
                          :disabled="editable"
                        >Te shfaqet ne Ballin</b-form-checkbox>
                      </label>
                      <has-error :form="formCat" field="isImportant" />
                    </div>
                    <div class="input-group col-12">
                      <input
                        type="file"
                        :disabled="editable"
                        class="form-control"
                        v-on:change="handleFileUpload(index)"
                        id="image"
                        ref="image"
                      />
                    </div>
                    <div class="col-12 text-left">
                      <v-button
                        v-if="!editable"
                        :loading="formCat.busy"
                        class="orangebtn pull-right"
                      >Ruaj</v-button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-1" />
              <div class="col-md-6 col-12">
                <h4>Nen Kategorite</h4>
                <div class="row">
                  <div class="input-group col-12 mb-3">
                    <input
                      v-model="subcat"
                      :class="{ 'is-invalid': formCat.errors.has('namesub') }"
                      class="form-control inputs"
                      type="text"
                      name="namesub"
                      placeholder="Emri i nenkategoris *"
                      :disabled="editable"
                    />
                    <has-error :form="formCat" field="name" />
                    <button v-if="!editable" class="btn btn-primary" @click="addsubCat()">SHTO</button>
                  </div>
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Emri</th>
                      <th style="width: 100px">Aksionet</th>
                    </tr>
                  </thead>
                  <tr v-for="item in subCategories" :key="item.id">
                    <td>
                      <input
                        :id="'subcat_'+item.id"
                        required
                        :value="item.name"
                        class="form-control mb-0"
                        type="text"
                        placeholder="Emri *"
                        :disabled="editable"
                      />
                    </td>
                    <td>
                      <button
                        v-if="!editable"
                        class="btn btn-inline"
                        @click="updatesubCat(item.id)"
                      >
                        <i class="fa fa-check" title="Ruaj" />
                      </button>
                      <button
                        v-if="!editable"
                        class="btn btn-inline"
                        data-toggle="modal"
                        data-target="#removeParam"
                        @click="showModalSub(item.id)"
                      >
                        <i class="fa fa-trash" />
                      </button>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <!-- End .tab-pane -->
        </div>
        <!-- End .tab-content -->
      </div>
      <!-- Modal -->
      <b-modal
        ref="delete-modal"
        hide-footer
        :title="'Jeni te sigurt qe deshironi te fshini ' + deleteName"
      >
        <div class="modal-footer">
          <button class="btn btn-primary" @click="removeCategory(deleteID)">Fshij</button>
        </div>
      </b-modal>
      <b-modal ref="deletesub-modal" hide-footer :title="'Jeni te sigurt qe deshironi te fshini'">
        <div class="modal-footer">
          <button class="btn btn-primary" @click="removeSubCategory(deleteIDSub)">Fshij</button>
        </div>
      </b-modal>
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
      title: "Kategorite"
    };
  },
  data: () => ({
    categories: [],
    loadingall: true,
    loading: true,
    parameters: [],
    subcat: "",
    subCategories: [],
    deleteID: "",
    deleteIDSub: "",
    deleteName: "",
    activeId: 0,
    editable: true,
    form: new Form({
      name: "",
      parent_id: ""
    }),
    formCat: new Form({
      name: "",
      id: null,
      parentId: 0,
      parameters: [],
      isImportant: true
    }),
    file: ""
  }),
  mounted: function() {
    this.getCategories();
    this.getAllParameters();
  },
  methods: {
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function() {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    async getCategories() {
      let self = this;
      await self.axios.get("/api/categories/getParents").then(res => {
        const { data } = res;
        self.categories = data.response;
        self.activeId = data.response[0].id;
        self.getCategoriesbyID(data.response[0].id);
        self.loadingall = false;
      });
    },
    getCategoriesbyID(id) {
      let self = this;
      self.editable = true;
      self.loading = true;
      self.formCat.id = id;
      self.deleteID = self.activeId = id;
      self.axios.get("/api/categories/getById/" + id).then(res => {
        const { data } = res;
        self.deleteName = self.formCat.name = data.response.name || "";
        self.subCategories = data.response.children || [];
        self.loading = false;
      });
    },
    async getAllParameters() {
      try {
        await this.axios
          .get("/api/parameters/getParents", this.form)
          .then(res => {
            let resParams = res.data.response || [];
            this.parameters = resParams;
          });
      } catch (e) {
        console.log(e);
      }
    },
    getParents(option) {
      option.map(item => {
        this.formCat.parameters.forEach(element => {
          if (element.id != item.id) return item;
        });
      });
    },
    customBrandName(option) {
      return option.name;
    },
    CreateEdit() {
      let self = this;
      self.loading = true;
      let formData = new FormData();
      for (var key in self.formCat) {
        formData.append(key, self.formCat[key]);
      }
      formData.append("images", self.file);

      self.axios
        .post("/api/categories/update", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(res => {
          const { data } = res;
          self.formCat.name = data.response.name || "";
          self.formCat.isImportant = data.response.isImportant == 1;
          self.formCat.parameters = data.response.params;
          self.subCategories = data.response.children || [];
          self.loading = false;
          this.getCategories();
        });
    },
    handleFileUpload(index) {
      this.file = this.$refs.image[index].files[0];
    },
    addCategory(id) {
      let self = this;
      if (id) {
        self.form.parent_id = id;
      }
      self.axios.post("/api/create/parameters", self.form).then(res => {
        const { data } = res;
        self.getParameters();
      });
    },
    removeCategory(id) {
      let self = this;
      self.loading = true;
      this.$refs["delete-modal"].hide();
      self.axios.post("/api/categories/remove/" + id).then(res => {
        self.getCategories();
      });
    },
    showModal() {
      this.$refs["delete-modal"].show();
    },
    removeSubCategory(id) {
      let self = this;
      self.loading = true;
      this.$refs["deletesub-modal"].hide();
      self.axios.post("/api/categories/remove/" + id).then(res => {
        self.getCategories();
      });
    },
    showModalSub(id) {
      this.deleteIDSub = id;
      this.$refs["deletesub-modal"].show();
    },
    async addsubCat() {
      this.loading = true;
      let formSubCat = {
        name: this.subcat,
        id: null,
        parentId: this.activeId,
        parameters: [],
        isImportant: false
      };
      try {
        await this.axios
          .post("/api/categories/create", formSubCat)
          .then(res => {
            this.subcat = "";
            this.getCategoriesbyID(this.activeId);
          });
      } catch (e) {
        console.log(e);
      }
    },
    editMode() {
      this.editable = !this.editable;
    },
    updatesubCat(id) {
      let reff = "subcat_" + id;
      const name = document.getElementById(reff).value;
      let formSubCat = {
        name: name,
        id: id,
        parentId: this.activeId,
        parameters: [],
        isImportant: false
      };
      let self = this;
      self.loading = true;
      self.axios.post("/api/categories/update", formSubCat).then(res => {
        self.getCategoriesbyID(this.activeId);
      });
    }
  }
};
</script>
