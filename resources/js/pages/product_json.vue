<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-7 col-12">
        <form @submit.prevent="submitFile" class="product-json-form">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="inputEmail4">
                Titulli
                <span class="ylli">*</span>
              </label>
              <input type="text" class="form-control" required name="name" v-model="title" />
            </div>
            <div class="form-group col-md-6">
              <label for="inputPassword4">
                Përshkrimi
                <span class="ylli">*</span>
              </label>
              <input
                type="text"
                name="description"
                required
                v-model="description"
                class="form-control"
              />
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="inputPassword4">
                Çmimi
                <span class="ylli">*</span>
              </label>
              <input type="text" required class="form-control" v-model="price" name="price" />
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">
                Çmimi i mëparshëm
                <!-- <span class="ylli">*</span> -->
              </label>
              <input
                type="text"
                v-model="previous_price"
                class="form-control"
                name="previous_price"
              />
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="inputEmail4">
                Sasia
                <span class="ylli">*</span>
              </label>
              <input type="text" required v-model="quantity" class="form-control" name="quantity" />
            </div>
            <div class="form-group col-md-6">
              <label for="inputPassword4">
                Pesha
                <span class="ylli">*</span>
              </label>
              <input type="text" required class="form-control" v-model="weight" name="weight" />
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="selecttype">
                Zgjedheni
                <span class="ylli">*</span>
              </label>
              <select class="form-control" id="selecttype" v-model="selecttype" required>
                <option disabled selected>Zgjedh:</option>
                <option value="url">Url</option>
                <option value="file">File</option>
              </select>
            </div>
            <div class="form-group col-md-6" v-if="selecttype == 'url'">
              <label for="inputPassword4">
                url
                <span class="ylli">*</span>
              </label>
              <input type="text" class="form-control" v-model="url" name="url" />
            </div>
            <div class="form-group col-md-6" v-if="selecttype == 'file'">
              <label for="file">
                file
                <span class="ylli">*</span>
              </label>
              <input
                type="file"
                class="form-control"
                v-on:change="handleFileUpload()"
                id="file"
                ref="file"
              />
            </div>
          </div>

          <button type="submit" class="btn btn-primary pull-right">Dërgo</button>
        </form>
      </div>
      <div class="col-md-5 col-12">
        <p class="json-file" style>
          <code>
            <div class="d-block">
              <span class="s3">[</span>
            </div>
            <div class="ml-4">
              <div class="d-block">
                <span class="s1">{</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{title ? title : `title`}}"</span>
                <span class="o">:</span>
                <span class="s1">"Titulli i produktit"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{description ? description : `description`}}"</span>
                <span class="o">:</span>
                <span class="s1">"Pershkrimi i produktit"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{ price ? price : `price`}}"</span>
                <span class="o">:</span>
                <span class="s1">"Çmimi"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{previous_price ? previous_price : 'previous_price'}}"</span>
                <span class="o">:</span>
                <span class="s1">"Çmimi i meparshem"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{quantity ? quantity : `quantity`}}"</span>
                <span class="o">:</span>
                <span class="s1">"Sasia"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{ weight ? weight : `weight` }}"</span>
                <span class="o">:</span>
                <span class="s1">"Pesha"</span>
              </div>
              <div class="d-block">
                <span class="s1">}</span>
                <span class="p">,</span>
              </div>
            </div>
          </code>
          <code>
            <div class="ml-4">
              <div class="d-block">
                <span class="s1">{</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{title ? title : `title`}}"</span>
                <span class="o">:</span>
                <span class="s1">"Titulli i produktit"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{description ? description : `description`}}"</span>
                <span class="o">:</span>
                <span class="s1">"Pershkrimi i produktit"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{ price ? price : `price`}}"</span>
                <span class="o">:</span>
                <span class="s1">"Çmimi"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{previous_price ? previous_price : 'previous_price'}}"</span>
                <span class="o">:</span>
                <span class="s1">"Çmimi i meparshem"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{quantity ? quantity : `quantity`}}"</span>
                <span class="o">:</span>
                <span class="s1">"Sasia"</span>
                <span class="p">,</span>
              </div>
              <div class="d-block">
                <span class="s1 s2">"{{ weight ? weight : `weight` }}"</span>
                <span class="o">:</span>
                <span class="s1">"Pesha"</span>
              </div>
              <div class="d-block">
                <span class="s1">}</span>
              </div>
            </div>
            <div class="d-block">
              <span class="s3">]</span>
            </div>
          </code>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  middleware: "auth",
  layout: "basic",
  components: {},
  metaInfo() {
    return {
      title: this.$t("home")
    };
  },
  data() {
    return {
      url: "",
      title: "",
      description: "",
      quantity: "",
      weight: "",
      previous_price: "",
      price: "",
      file: "",
      selecttype: ""
    };
  },
  methods: {
    submitFile() {
      if (this.selecttype == "url") {
        this.file = "";
      } else {
        this.url = "";
      }
      if (this.url == "" || this.field) {
        alert("please complete all fields");
      }
      let formData = new FormData();
      formData.append("url", this.url);
      formData.append("title", this.title);
      formData.append("description", this.description);
      formData.append("quantity", this.quantity);
      formData.append("weight", this.weight);
      formData.append("previous_price", this.previous_price);
      formData.append("price", this.price);
      formData.append("file", this.file);
      this.axios
        .post("/api/product/json", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(function() {
          console.log("SUCCESS!!");
        })
        .catch(function() {
          console.log("FAILURE!!");
        });
    },
    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    }
  }
};
</script>
