<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'fixed'" />
    <div v-if="!form.category_id">
      <div class="input-group mb-3 col-12 col-md-6">
        <b-form-select
          v-model="form.category_id"
          class="form-control inputs mb-1"
        >
          <option :value="0" :selected="true"></option>
          <option v-for="cats in categories" :key="cats.id" :value="cats.id">
            {{ cats.name }}
          </option>
        </b-form-select>
        <has-error :form="form" field="category" />
      </div>
    </div>
    <div v-else>
      <Steps :active="1" :steps="4" />
      <div class="mb-4" />
      <div class="container">
        <div class="row">
          <div class="col-lg-12 col-12">
            <div class="">
              <h2 class="step-title">Add new products</h2>
              <div class="modal fade" id="myPriceList" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Qmimorja</h4>
                      <button type="button" class="close" data-dismiss="modal">
                        &times;
                      </button>
                    </div>
                    <div class="modal-body">
                      <div
                        v-for="(pr, index) in posts"
                        :key="index"
                        class="price-for-c"
                      >
                        <p class="sec-title">{{ pr.name }}</p>
                        <div
                          class="info-price"
                          v-for="pri in pr.price_companies"
                          :key="pri.id"
                        >
                          <div class="order-det">
                            <p>
                              <label for="from">Prej</label>
                              <span>{{ pri.from }} kg</span>
                            </p>
                            <p>
                              <label for="to">Deri</label>
                              <span>{{ pri.to }} kg</span>
                            </p>
                            <p>
                              <label for="price">Qmimi</label>
                              <span>{{ pri.price }} €</span>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-4" />
            <form
              id="step-1"
              class="row custom-form"
              @submit.prevent="register"
              @keydown="form.onKeydown($event)"
            >
              <div class="col-12 col-lg-12 border-bottom">
                <div class="row mb-3">
                  <div class="col-12">
                    <div class="row">
                      <div class="col-12">
                        <h1 class="small-title">Upload photos</h1>
                      </div>
                      <div class="col-md-4 col-12 p-0">
                        <div class="input-group mb-3 col-12 p-0">
                          <picture-input
                            id="file"
                            ref="pictureInput"
                            type="file"
                            :prefill="url1"
                            width="300"
                            height="300"
                            margin="15"
                            accept="image/*"
                            size="10"
                            button-class="btn"
                            :custom-strings="{
                              upload: '<h1>Bummer!</h1>',
                              drag: 'Add main photo',
                            }"
                            @change="onChangePicture(1)"
                          />
                          <has-error :form="form" field="picture" />
                        </div>
                      </div>
                      <!-- picture -->
                      <div class="col-12 col-md-8">
                        <div class="row">
                          <div class="mb-3 col-3">
                            <picture-input
                              ref="pictureInput2"
                              :prefill="url2"
                              width="300"
                              height="300"
                              margin="15"
                              accept="image/*"
                              size="10"
                              button-class="btn"
                              :custom-strings="{
                                upload: '<h1>Bummer!</h1>',
                                drag: 'Add more',
                              }"
                              @change="onChangePicture(2)"
                            />
                            <has-error :form="form" field="picture" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- form inputs -->
                  <div class="col-12 p-0">
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <div class="form-group mb-3 col-12">
                          <div
                            class="d-flex justify-content-between align-items-center"
                          >
                            <h1 class="small-title">Info</h1>
                            <button class="btn btn-secondary btn-sm">
                              Default
                            </button>
                          </div>
                          <label class="m-0" for="name">
                            <h4 class="m-0">Title</h4>
                          </label>
                          <input
                            v-model="form.title"
                            required
                            :class="{ 'is-invalid': form.errors.has('name') }"
                            class="form-control inputs custom-input"
                            type="text"
                            name="name"
                          />
                          <has-error :form="form" field="title" />
                          <a class="btn btn-black add-other-lang">
                            <img src="../../icons/plus.svg" alt="plus" />
                            Add in another language
                          </a>
                        </div>
                        <div class="form-group col-12 mb-3">
                          <label class="m-0" for="price">
                            <h4 class="m-0">Short Description</h4>
                          </label>
                          <textarea
                            v-model="form.short_description"
                            style="max-width: 100%; resize: none"
                            :class="{
                              'is-invalid': form.errors.has('description'),
                            }"
                            class="form-control inputs custom-input"
                            name="description"
                            required
                            rows="false"
                          />
                          <has-error :form="form" field="short_description" />
                          <a class="btn btn-black add-other-lang">
                            <img src="../../icons/plus.svg" alt="plus" />
                            Add in another language
                          </a>
                        </div>
                        <div class="form-group col-12 mb-3">
                          <label class="m-0" for="price">
                            <h4 class="m-0">Description</h4>
                          </label>
                          <textarea
                            v-model="form.description"
                            style="max-width: 100%; resize: none"
                            :class="{
                              'is-invalid': form.errors.has('description'),
                            }"
                            class="form-control inputs custom-input"
                            name="description"
                            required
                            rows="false"
                          />
                          <has-error :form="form" field="description" />
                          <a class="btn btn-black add-other-lang">
                            <img src="../../icons/plus.svg" alt="plus" />
                            Add in another language
                          </a>
                        </div>
                      </div>
                      <div class="col-12 col-md-6">
                        <div class="col-12 mb-1">
                          <div
                            class="d-flex justify-content-between align-items-center"
                          >
                            <h1 class="small-title">Color</h1>
                          </div>
                        </div>
                        <div class="form-group mb-3 col-12">
                          <multiselect
                            v-model="color"
                            :options="colorOptions"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :preserve-search="true"
                            placeholder="Choose the colors"
                            label="name"
                            track-by="name"
                            :preselect-first="false"
                          >
                            <template
                              slot="selection"
                              slot-scope="{ values, search, isOpen }"
                            ></template>
                          </multiselect>
                          <!-- <div v-if="color.length > 0">
                          <div v-for="(item, index) in color" :key="index">
                            {{ item.name }}
                          </div>
                        </div> -->
                        </div>
                        <div class="col-12 mb-1">
                          <div
                            class="d-flex justify-content-between align-items-center"
                          >
                            <h1 class="small-title">Size</h1>
                          </div>
                        </div>
                        <div class="form-group mb-3 col-12">
                          <div
                            v-for="(item, index) in form.size"
                            :key="index"
                            class="d-flex mb-1"
                          >
                            <input
                              v-model="item.name"
                              :class="{ 'is-invalid': form.errors.has('size') }"
                              class="form-control inputs custom-input"
                              type="text"
                              name="size"
                            />
                            <a @click="removeSize(index)" class="icon-btn">
                              <!-- :class="item.name == '' ? 'disable-btn' : ''" -->
                              <img src="../../icons/trash.svg" alt="plus" />
                            </a>
                          </div>
                          <a
                            :class="
                              form.size[form.size.length - 1].name == ''
                                ? 'disable-btn'
                                : ''
                            "
                            class="btn btn-black add-other-field"
                            @click="addSize()"
                          >
                            <img src="../../icons/plus.svg" alt="plus" />
                            Add more
                          </a>
                          <has-error :form="form" field="price" />
                        </div>
                        <div class="col-12 mb-1">
                          <div
                            class="d-flex justify-content-between align-items-center"
                          >
                            <h1 class="small-title">Specification</h1>
                          </div>
                        </div>
                        <div class="form-group mb-3 col-12">
                          <div
                            v-for="(item, index) in form.specs"
                            :key="index"
                            class="d-flex mb-1 justify-content-between w-100"
                          >
                            <div class="d-flex w-100">
                              <div class="w-100 mr-3">
                                <label class="m-0" for="price">
                                  <h4 class="m-0">Title</h4>
                                </label>
                                <input
                                  v-model="item.name"
                                  class="form-control inputs custom-input mr-3"
                                  type="text"
                                  name="specs"
                                />
                              </div>
                              <div class="w-100">
                                <label class="m-0" for="price">
                                  <h4 class="m-0">Specification</h4>
                                </label>
                                <input
                                  v-model="item.spec"
                                  class="form-control inputs custom-input m-0"
                                  type="text"
                                  name="specs"
                                />
                              </div>
                            </div>
                            <a
                              @click="removeSpecs(index)"
                              class="icon-btn"
                              style="margin-top: 24px"
                            >
                              <img src="../../icons/trash.svg" alt="remove" />
                            </a>
                          </div>
                          <a
                            class="btn btn-black add-other-field"
                            :class="
                              form.specs[form.specs.length - 1].name == '' ||
                              form.specs[form.specs.length - 1].spec == ''
                                ? 'disable-btn'
                                : ''
                            "
                            @click="addSpecs()"
                          >
                            <img src="../../icons/plus.svg" alt="plus" />
                            Add more
                          </a>
                          <has-error :form="form" field="price" />
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Perfundimi i pjeses se shopit -->
                </div>
              </div>
              <div class="col-12 mt-3 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary mb-5">
                  {{ this.edit ? "Edit product" : "Post new products" }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import Form from "vform";
import FormError from "~/components/FormError.vue";
import PictureInput from "vue-picture-input";
import Loader from "~/components/Loader";
import Steps from "~/components/Steps";
import Multiselect from "vue-multiselect";

export default {
  middleware: "auth",
  layout: "nosidebar",
  components: {
    FormError,
    PictureInput,
    Loader,
    Steps,
    Multiselect,
  },
  metaInfo() {
    return {
      title: "Shto Produkt",
    };
  },
  props: {
    id: {
      type: [Number, String],
      default: null,
    },
  },
  data: () => ({
    color: [],
    colorOptions: [
      { name: "Red" },
      { name: "Black" },
      { name: "Yellow" },
      { name: "Green" },
      { name: "Blue" },
    ],
    loadingall: true,
    categories: [],
    category: null,
    nenparameter: [],
    parameters: [],
    parametersChild: [],
    parms: [],
    subcategories: [],
    posts: [],
    files: [],
    editParams: [],
    url1: "",
    url2: "",
    url3: "",
    url4: "",
    edit: false,
    errors: [],
    parambycat: [],
    childParamId: Number,
    childParamName: "",
    parentParamName: "",
    form: new Form({
      id: 0,
      name: "",
      weight: "",
      quantity: 1,
      title: "",
      price: "",
      size: [
        {
          name: "",
        },
      ],
      specs: [
        {
          name: "",
          spec: "",
        },
      ],
      posta_free: false,
      previous_price: "",
      short_description: "",
      description: "",
      category_id: 0,
      subcategory: 0,
    }),
  }),
  mounted: function () {
    this.getMainCategories();
    this.getPosts();
    if (this.$props.id && this.$props.id > 0) {
      this.showProduct(this.$props.id);
    }
  },
  methods: {
    addSize() {
      this.form.size.push({
        name: "",
      });
      console.log("index add", this.form.size);
    },
    removeSize(index) {
      console.log("index remove", this.form.size, index);
      if (this.form.size.length > 0) {
        this.form.size.splice(index, 1);
      }
      console.log("index remove 2", this.form.size, index);
    },
    addSpecs(index) {
      console.log("index add", index);
      this.form.specs.push({
        name: "",
        spec: "",
      });
    },
    removeSpecs(index) {
      console.log("index remove", index);
      if (this.form.specs.length > 1) {
        this.form.specs.splice(index, 1);
      }
    },
    getPosts() {
      let vm = this;
      fetch(`/api/posta/company`, {
        method: "get",
      })
        .then((result) => result.json())
        .then((result) => {
          vm.posts = result.response;
        })
        .catch(function () {});
    },
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function () {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    parentParam(id) {
      this.form.category_id = id;
      let $ = require("jquery");
      $("#parametersModalP").modal("show");
    },
    addParentParameters(id) {
      this.loading = true;
      let self = this;
      let parminfop = {
        category_id: id,
        parent_id: "",
        name: self.parentParamName,
      };
      self.axios.post("/api/create/parameters", parminfop).then((res) => {
        const { data } = res;
        self.form.category_id = 0;
        self.parentParamName = "";
        self.parambycat = [];
        this.loading = false;
        let $ = require("jquery");
        $("#parametersModalP").modal("hide");
      });
    },
    childParam(id) {
      this.childParamId = id;
      let $ = require("jquery");
      $("#parametersModal").modal("show");
    },
    addChildParameters(id) {
      this.loading = true;
      let self = this;
      let parminfo = {
        parent_id: id,
        name: self.childParamName,
      };
      self.axios.post("/api/create/parameters", parminfo).then((res) => {
        const { data } = res;
        self.childParamId = "";
        self.childParamName = "";
        self.form.category_id = 0;
        self.getSubCategories(id);
        let $ = require("jquery");
        $("#parametersModal").modal("hide");
      });
    },
    addQuantity() {
      this.form.quantity++;
    },
    removeQuantity() {
      if (this.form.quantity > 1) {
        this.form.quantity--;
      }
    },
    showProduct(id) {
      this.loadingall = true;
      this.edit = true;
      fetch(`/api/products/shop-by-id/${id}`, {
        method: "get",
      })
        .then((result) => result.json())
        .then((result) => {
          this.editProduct(result.response);
          this.loadingall = false;
        })
        .catch((err) =>
          swal({
            type: "error",
            title: i18n.t("error_alert_title"),
            text: err,
            reverseButtons: true,
            confirmButtonText: i18n.t("ok"),
          })
        );
    },

    editProduct(product) {
      this.edit = true;
      this.form.id = product.id;
      this.form.title = product.title;
      this.form.weight = product.weight;
      this.form.quantity = product.quantity;
      this.form.price = product.price;
      this.form.previous_price = product.previous_price;
      this.form.description = product.description;
      this.form.category_id = product.category_id;
      this.form.posta_free = product.posta_free;
      if (this.form.category_id > 0) {
        this.getSubCategories(this.form.category_id);
      }
      this.form.subcategory = product.subcategory_id;
      this.editParams = product.parameters ? product.parameters : [];
      if (product.image_urls.length > 0) {
        this.url1 = product.image_urls[0];
      }
      if (product.image_urls.length > 1) {
        this.url2 = product.image_urls[1];
      }
      if (product.image_urls.length > 2) {
        this.url3 = product.image_urls[2];
      }
      if (product.image_urls.length > 3) {
        this.url4 = product.image_urls[3];
      }
    },
    selectParameters(item, index) {
      if (this.edit) {
        if (this.editParams.includes(item)) {
          this.parms[index] = item;
          return true;
        }
      } else {
        return false;
      }
    },
    addParams(item, index) {
      this.parms[index] = item;
    },
    getMainCategories() {
      this.loadingall = true;
      this.axios
        .get("/api/categories/getParents")
        .then((res) => {
          const { response } = res.data;
          this.categories = response;
          this.loadingall = false;
        })
        .catch(function () {});
    },
    getSubCategories(id) {
      this.errors = [];
      this.parametersChild = [];
      this.axios.get("/api/categories/getById/" + id).then((res) => {
        const { response } = res.data;
        this.subcategories = response ? response.children : [];
        let paramsParents = (this.parameters = response ? response.params : []);
        this.callParams(response.params);
        // return;
      });
      this.getParameters(id);
    },
    getParameters(id) {
      this.parambycat = [];
      this.axios.get("/api/parameters/getByCategoryId/" + id).then((res) => {
        const { response } = res.data;
        this.parambycat = response;
      });
    },
    callParams(paramsParents) {
      paramsParents.forEach((element) => {
        this.getParams(element.id);
      });
    },
    onChangePicture(index) {
      // if (index == 1) {
      //   this.files.push(this.$refs.pictureInput.file);
      // } else if (index == 2) {
      //   this.files.push(this.$refs.pictureInput2.file);
      // } else if (index == 3) {
      //   this.files.push(this.$refs.pictureInput3.file);
      // } else if (index == 4) {
      //   this.files.push(this.$refs.pictureInput4.file);
      // }
    },
    register() {
      this.loadingall = true;
      if (this.form.category_id <= 0) {
        this.errors = {
          category: ["Category is required!"],
        };
        return;
      }
      if (!this.form.subcategory) {
        this.form.subcategory = 0;
      }
      let formData = new FormData();
      for (let i = 0; i < Object.keys(this.form).length; i++) {
        formData.append(Object.keys(this.form)[i], Object.values(this.form)[i]);
      }
      if (this.$refs.pictureInput.file !== undefined) {
        this.files.push(this.$refs.pictureInput.file);
      }
      if (this.$refs.pictureInput2.file !== undefined) {
        this.files.push(this.$refs.pictureInput2.file);
      }
      if (this.$refs.pictureInput3.file !== undefined) {
        this.files.push(this.$refs.pictureInput3.file);
      }
      if (this.$refs.pictureInput4.file !== undefined) {
        this.files.push(this.$refs.pictureInput4.file);
      }

      formData.append("countImages", this.files.length);
      for (var i = 0; i < this.files.length; i++) {
        let file = this.files[i];
        formData.append("images" + i, file);
      }

      var parameters_arr = JSON.stringify(this.nenparameter);
      formData.append("parameters", parameters_arr);
      let url = "";
      if (this.edit) {
        url = "/api/products/update";
      } else {
        url = "/api/products/create";
      }
      this.axios
        .post(url, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((data) => {
          this.loadingall = false;
          this.$router.push({ name: "all-products" });
          console.log("SUCCESS!!");
        })
        .catch((err) => console.log(err));
    },
    getParams(id) {
      let params = this.axios.get("/api/parameters/" + id).then((res) => {
        const { response } = res.data;
        let childs = response ? response.child : [];
        this.parametersChild.push(Object.assign({}, childs));
      });
    },
  },
};
</script>

<style>
.form-group label h4 {
  font-size: 1.4rem;
}
.color-palete {
  display: block;
  margin: 0;
  padding: 0;
  list-style: none;
}
.color-palete li {
  display: inline-block;
  width: 16px;
  height: 16px;
  font-size: 0;
  margin: 0 3px 3px 0;
  /* float: left; */
  border: 1px solid #b4b4b4;
  cursor: pointer;
}
.color-palete li:last-child {
  width: auto;
  height: auto;
  font-size: 12px;
  border: none;
}
.toggle-switch {
  position: relative;
  display: inline-block;
  width: 70px;
  height: 30px;
}
.toggle-switch input {
  display: none;
}
.toggle-switch-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #e8e8e8;
  -webkit-transition: 0.4s;
  transition: 0.4s;
  border-radius: 0;
}
.toggle-switch-slider:before {
  position: absolute;
  content: "";
  height: 22px;
  width: 30px;
  left: 4px;
  bottom: 4px;
  background-color: #65b891;
  -webkit-transition: 0.4s;
  transition: 0.4s;
  border-radius: 0%;
}
input:checked + .toggle-switch-slider {
  background-color: #65b891;
}
input:focus + .toggle-switch-slider {
  box-shadow: 0 0 1px #65b891;
}
input:checked + .toggle-switch-slider:before {
  background-color: white;
}
input:focus + .toggle-switch-slider:before {
  background-color: white;
}
input:checked + .toggle-switch-slider:before {
  -webkit-transform: translateX(30px);
  -ms-transform: translateX(30px);
  transform: translateX(30px);
}
</style>
