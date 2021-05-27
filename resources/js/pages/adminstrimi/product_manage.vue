<template>
  <main class="main">
    <div class="container">
      <div class="mb-2" />
      <div class="row">
        <div class="col-lg-12">
          <div class="mob-sec-header mb-3">
          <div class="container p-0">
            <button v-on:click="triggerSidebar()" id="triside">
              <i class="fa fa-bars"></i> Paneli
            </button>
          </div>
        </div>
          <h2 class="step-title">Menagjo produktet</h2>
          <!-- <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb pull-left">
              <li class="breadcrumb-item">
                <a href="#">
                  <i class="fa fa-home" />
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Menagjo produkte
              </li>
            </ol>
          </nav>-->
        </div>
      </div>
      <form @submit.prevent="getProducts()" class="custom-form">
        <div class="row w-100 m-0">
          <div class="col-md-2 col-12 p-1">
            <label>Emri</label>
            <input v-model="title" class="form-control custom-input" placeholder="Emri i produktit" />
          </div>
          <div class="col-md-2 col-12 p-1">
            <label>Çmimi</label>
            <input
              v-model="price"
              class="form-control custom-input"
              placeholder="Cmimi i produktit"
            />
          </div>
          <div class="col-md-2 col-12 p-1">
            <label>Prej</label>
            <input
              v-model="startDate"
              class="form-control custom-input"
              type="date"
              @input="dateChange($event.target.value,1)"
            />
          </div>
          <div class="col-md-2 col-12 p-1">
            <label>Deri</label>
            <input
              v-model="endDate"
              class="form-control custom-input"
              type="date"
              @input="dateChange($event.target.value,0)"
            />
          </div>
          <div class="col-md-2 col-12 p-1">
            <label>Statusi</label>
            <select v-model="status" class="form-control custom-input">
              <option :value="0">Te gjitha produktet</option>
              <option :value="2">Produkt normal</option>
              <option :value="1">Produkt ne ballin</option>
            </select>
          </div>
          <div class="col-md-2 col-12 p-1">
            <label>Emri i shitesit</label>
            <input v-model="shop" class="form-control custom-input" placeholder="Emri i shitesit" />
          </div>
          <div class="col-12 p-1">
            <button type="submit" class="btn btn-primary pull-right">Kërko</button>
          </div>
        </div>
      </form>
      <div class="mb-4" />
      <div class="row">
        <div class="col-lg-12">
          <div class="cart-table-container">
            <table class="table table-cart">
              <thead>
                <tr>
                  <th class="product-col">Produkti</th>
                  <th class="price-col">Çmimi</th>
                  <th class="qty-col">Liked</th>
                  <th class="qty-col">Statusi</th>
                  <th>Aksionet</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="product in products" :key="product.id" class="product-row">
                  <td>
                    <div class="product-col">
                      <figure class="product-image-container">
                        <a :href="'/product/' + product.slug" class="product-image">
                          <img v-if="product.image_urls.length !== 0" :src="product.image_urls[0]" />
                          <img v-if="product.image_urls.length == 0" src="../../../img/noimg.jpg" />
                        </a>
                      </figure>
                    </div>
                  </td>
                  <td>{{ product.price }} &euro;</td>
                  <td>
                    <div class="product-single-qty spin">{{ product.likes }}</div>
                  </td>
                  <td class="custom-form">
                    <b-form-select
                      v-model="product.isImportant"
                      class="form-control custom-input"
                      style="border: 1px solid #c4c4c4;"
                    >
                      <option :value="0" :selected="true">produkt normal</option>
                      <option :value="1">produkt ne slider ballin</option>
                      <option :value="2">produkt ne ballin</option>
                    </b-form-select>
                  </td>
                  <td>
                    <div class="float-right">
                      <button
                        class="btn btn-primary"
                        @click="changeImportant(product.id,product.isImportant)"
                      >
                        <span>Ndrysho</span>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-12">
        <nav class="toolbox toolbox-pagination">
          <paginate
            :page-count="totalPage"
            :click-handler="paginate"
            :prev-text="'Prev'"
            :next-text="'Next'"
            :container-class="'pagination'"
          />
        </nav>
      </div>
    </div>
    <div class="mb-6" />
  </main>
</template>
<script>
import Paginate from "vuejs-paginate";
import Form from "vform";
import FormError from "~/components/FormError.vue";

export default {
  components: {
    Paginate
  },
  data() {
    return {
      products: [],
      totalPage: 1,
      actualPage: 1,
      title: "",
      price: "",
      status: "",
      shop: "",
      statuses: [],
      status: 0,
      startDate: 0,
      endDate: 0
    };
  },
  mounted: function() {
    this.getProducts();
    this.getStatuses();
  },
  methods: {
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function() {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    getStatuses() {
      this.loadingall = true;
      fetch("/api/get/status/products", { method: "get" })
        .then(result => result.json())
        .then(result => {
          this.statuses = result.response.data;
          this.loadingall = false;
        });
    },
    changeImportant(id, status) {
      let data = {
        id: id,
        status: status
      };
      this.axios
        .post(`/api/product/change/important`, data, {})
        .then(result => {
          if (result.data.status === "success") {
            alert("Produkti u ndryshua me sukses");
          }
        });
    },
    dateChange(date, from) {
      if (from === 1) {
        this.startDate = date;
      } else {
        this.endDate = date;
      }
    },
    paginate(page) {
      this.actualPage = page;
      this.getProducts();
    },
    getProducts() {
      this.products = [];
      let self = this;
      self.loadingall = true;
      let status = this.status;
      let price = this.price;
      let title = this.title;
      let startDate = this.startDate;
      let endDate = this.endDate;
      let shop = this.shop;

      if (!price) {
        price = 0;
      }
      if (!title) {
        title = 0;
      }
      if (!startDate) {
        startDate = 0;
      }
      if (!endDate) {
        endDate = 0;
      }
      if (!shop) {
        shop = 0;
      }
      self.axios
        .get(
          "/api/products/getAllProductsWithPagination?title= " +
            title +
            "&price=" +
            price +
            "&startDate=" +
            startDate +
            "&endDate=" +
            endDate +
            "&status=" +
            status +
            "&shop=" +
            shop +
            "&page=" +
            this.actualPage
        )
        .then(res => {
          const { data } = res;
          self.products = data.response.data;
          self.totalPage = data.response.last_page;
        });
    }
  }
};
</script>
