<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'inline'" />
    <div class="col-lg-12">
      <div class="mob-sec-header mb-3">
        <div class="container p-0">
          <button v-on:click="triggerSidebar()" id="triside">
            <i class="fa fa-bars"></i> Paneli
          </button>
        </div>
      </div>
    </div>
    <div class="product-single-tabs">
      <ul class="nav nav-tabs" role="tablist">
        <li v-for="(param,index) in parameters" :key="param.id" class="nav-item">
          <a
            class="nav-link"
            :class="{ 'active': index == 0 }"
            data-toggle="tab"
            :href="'#product-'+param.id"
            role="tab"
            :aria-controls="'product-'+param.id"
            aria-selected="false"
            @click="getParametersbyID(param.id)"
          >{{ param.name }}</a>
        </li>
        <li class="nav-item" style="margin-right: 0; margin-left: auto;">
          <button
            class="nav-link btn btn-primary"
            style="color: #fff"
            @click="addButton()"
          >Shto Parameter</button>
        </li>
      </ul>
      <div class="tab-content">
        <div
          v-for="(param,index) in parameters"
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
            <div class="col-12">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="input-group col-12">
                    <label>
                      <b-form-checkbox
                        v-model="colorpicker"
                        class="mb-2 mr-sm-2 mb-sm-0"
                        :disabled="editable"
                        @change="colorPicker"
                      >Te perdoret ColorPicker</b-form-checkbox>
                    </label>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="pull-right mb-3">
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
                </div>
              </div>
            </div>
            <div class="col-12 col-md-5">
              <h3>Ndrysho Emrin</h3>
              <form class="input-group" @submit.prevent="editParameters(param.id)">
                <input
                  v-model="formEdit.name"
                  type="text"
                  class="form-control"
                  :disabled="editable"
                  placeholder="Emri"
                />
                <v-button :loading="form.busy" class="btn btn-primary">Ruaj</v-button>
              </form>
              <h3>Shto Nen Parametra</h3>
              <form
                class="input-group"
                style="width: 100%;"
                @submit.prevent="addParameters(param.id)"
              >
                <input
                  v-if="!colorpicker"
                  v-model="form.name"
                  type="text"
                  class="form-control"
                  :disabled="editable"
                  placeholder="Emri"
                />
                <photoshop-picker v-if="colorpicker" v-model="colors" class="form-control" />
                <v-button :loading="form.busy" class="btn btn-primary">
                  Shto {{ param.name }}
                  <i class="fa fa-arrow-right" />
                </v-button>
              </form>
            </div>
            <div class="col-12 col-md-1" />
            <div class="col-12 col-sm-6">
              <h3>Nen Parametrat</h3>
              <table class="table">
                <thead>
                  <tr>
                    <th>Emri</th>
                    <th style="width: 100px">Aksionet</th>
                  </tr>
                </thead>
                <tr v-for="item in selectedParams" :key="item.id">
                  <td>
                    <input
                      :id="'subcat_'+ item.id + '' + param.id"
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
                      @click="updatesubParam(item.id, param.id)"
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
    <b-modal ref="add-modal" hide-footer :title="'Shto Parameter'" id="parameterModal">
      <form @submit.prevent="addParameters(false)">
        <label for>Emri i parametrit</label>
        <input v-model="form.name" type="text" class="form-control" placeholder="Emri i parametrit" />
        <label for>Parametri parent</label>
        <select v-model="form.parent_id" name="ParentParameter" class="form-control">
          <option value selected>Zgjedh parametrin parent</option>
          <option v-for="pr in paramid" :key="pr.id" :value="pr.id">{{pr.name}}</option>
        </select>
        <label for>Kategoria</label>
        <select
          name="category"
          v-model="form.category_id"
          :class="!form.parent_id ? 'form-control': 'form-control disabled-select'"
        >
          <option value selected>Zgjedh nje kategori</option>
          <option v-for="cat in category" :key="cat.id" :value="cat.id">{{cat.name}}</option>
        </select>
        <div class="mb-2" />
        <v-button :loading="form.busy" class="btn btn-primary">Ruaj</v-button>
      </form>
    </b-modal>
    <!-- Modal -->

    <b-modal
      ref="delete-modal"
      hide-footer
      :title="'Jeni te sigurt qe deshironi ta fshini parametrin' + actualName"
    >
      <div class="modal-footer">
        <button class="btn btn-primary" @click="removeParameters(actualId)">Fshij</button>
      </div>
    </b-modal>
    <b-modal ref="deletesub-modal" hide-footer :title="'Jeni te sigurt qe deshironi te fshini'">
      <div class="modal-footer">
        <button class="btn btn-primary" @click="removeSubCategory(deleteIDSub)">Fshij</button>
      </div>
    </b-modal>
  </main>
</template>
<script>
import Form from "vform";
import Loader from "~/components/Loader";
import { Compact } from "vue-color";
var colors = "#194d33";
export default {
  components: {
    Loader,
    "photoshop-picker": Compact
  },
  metaInfo() {
    return {
      title: "Parametrat"
    };
  },
  data: () => ({
    parameters: [],
    category: [],
    categoryselect: "",
    colorpicker: false,
    colors: colors,
    editable: true,
    selectedParams: [],
    loading: true,
    loadingall: true,
    deleteID: "",
    actualId: 0,
    actualName: "",
    paramid: "",
    form: new Form({
      name: "",
      parent_id: "",
      category_id: ""
    }),
    formEdit: new Form({
      name: "",
      id: ""
    })
  }),
  mounted: function() {
    this.getParameters();
    this.getCategory();
  },
  methods: {
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function() {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    getCategory() {
      this.axios.get("/api/categories/getParents").then(res => {
        const { data } = res;
        this.category = data.response;
      });
      this.getParametersId();
    },
    getParametersId() {
      this.axios.get("/api/parameters/getParents").then(res => {
        const { data } = res;
        this.paramid = data.response;
      });
    },

    getParameters(id = null) {
      let self = this;
      self.axios.get("/api/parameters/getParents").then(res => {
        const { data } = res;
        self.parameters = data.response;
        if (!id) {
          self.getParametersbyID(self.parameters[0].id);
        } else {
          self.getParametersbyID(id);
        }
        this.loadingall = false;
      });
    },
    getParametersbyID(id) {
      let self = this;
      self.actualId = self.formEdit.id = id;
      this.loading = true;
      self.colorpicker = false;
      self.editable = true;
      self.axios.get("/api/parameters/" + id).then(res => {
        const { data } = res;
        self.actualName = self.formEdit.name = data.response.name;
        self.selectedParams = data.response.child;
        this.loading = false;
      });
    },
    addButton() {
      this.$refs["add-modal"].show();
    },
    addParameters(id) {
      let self = this;
      this.loading = true;
      if (id) {
        self.form.parent_id = id;
        // self.form.category_id = id;
      }
      if (this.colorpicker) {
        self.form.name = self.colors.hex;
      }
      let parminfo = {
        parent_id: self.form.parent_id,
        category_id: self.form.category_id,
        name: self.form.name
      };
      if (self.form.category_id) {
        parminfo.parent_id = "";
      }
      self.axios.post("/api/create/parameters", parminfo).then(res => {
        const { data } = res;
        self.form.parent_id = "";
        self.form.category_id = "";
        self.form.name = "";
        self.getParameters(id);
        this.$refs["add-modal"].hide();
      });
    },
    editParameters() {
      let self = this;
      self.loading = true;
      self.axios.post("/api/update/parameters", self.formEdit).then(res => {
        this.getParameters(self.formEdit.id);
      });
    },
    updatesubParam(id, parentId) {
      let self = this;
      let reff = "subcat_" + id + "" + parentId;
      const name = document.getElementById(reff).value;
      self.loading = true;
      let formedit = {
        name: name,
        parent_id: parentId,
        id: id
      };
      self.axios.post("/api/update/parameters", formedit).then(res => {
        self.getParameters(parentId);
      });
    },
    removeParameters(id) {
      let self = this;
      self.axios.post("/api/delete/parameters/" + id).then(res => {
        const { data } = res;
        self.getParameters(false);
        self.$refs["delete-modal"].hide();
      });
    },
    showModal() {
      this.$refs["delete-modal"].show();
    },
    editMode() {
      this.editable = !this.editable;
    },
    colorPicker() {
      this.colorpicker = !this.colorpicker;
    },
    removeSubCategory(id) {
      let self = this;
      this.$refs["deletesub-modal"].hide();
      self.axios.post("/api/delete/parameters/" + id).then(res => {
        self.getParametersbyID(self.actualId);
      });
    },
    showModalSub(id) {
      this.deleteIDSub = id;
      this.$refs["deletesub-modal"].show();
    }
  }
};
</script>
