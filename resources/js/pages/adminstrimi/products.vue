<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="container">
      <div class="mb-2" />
      <div class="row">
        <div class="col-12 p-0">
          <div class="mob-sec-header mb-3">
            <div class="container">
              <button v-on:click="triggerSidebar()" id="triside">
                <i class="fa fa-bars"></i> Paneli
              </button>
            </div>
          </div>
          <div class="col-12">
            <h2 class="step-title">Produktet e mia</h2>
          </div>
        </div>
      </div>
      <form @submit.prevent="myProducts()">
        <div class="row w-100 m-0 custom-form">
          <div class="col-md-2 col-12 p-0 pr-1">
            <label>Emri</label>
            <input v-model="title" class="form-control custom-input" placeholder="Emri i produktit" />
          </div>
          <div class="col-md-1 col-12 p-0 pr-1">
            <label>Cmimi</label>
            <input
              v-model="price"
              class="form-control custom-input"
              placeholder="Cmimi i produktit"
            />
          </div>
          <div class="col-md-2 col-12 p-0 pr-1">
            <label>Prej</label>
            <input
              v-model="startDate"
              class="form-control custom-input"
              type="date"
              @input="dateChange($event.target.value,1)"
            />
          </div>
          <div class="col-md-2 col-12 p-0 pr-1">
            <label>Deri</label>
            <input
              v-model="endDate"
              class="form-control custom-input"
              type="date"
              @input="dateChange($event.target.value,0)"
            />
          </div>
          <div class="col-md-1 col-12 p-0 pr-1">
            <label>Pelqimet</label>
            <input
              v-model="likes"
              class="form-control custom-input"
              placeholder="Pelqimet e produktit"
            />
          </div>
          <div class="col-md-2 col-12 p-0 pr-1">
            <label>Statusi</label>
            <select v-model="status" class="form-control custom-input">
              <option :value="0">Te gjitha produktet</option>
              <option :value="2">Produkt normal</option>
              <option :value="1">Produkt ne ballin</option>
            </select>
          </div>
          <div class="col-md-2 col-12 p-0" style="margin-top: 25px;">
            <button type="submit" class="btn btn-primary w-100">Kërko</button>
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
                  <th>Pelqimet</th>
                  <th class="price-col">Çmimi</th>
                  <th>Aksionet</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item,index) in products" :key="item.id" class="product-row">
                  <td>
                    <div class="product-col">
                      <figure class="product-image-container">
                        <img
                          v-if="item.image_urls.length !== 0"
                          alt="product"
                          :src="item.image_urls[0]"
                        />
                        <img v-if="item.image_urls.length == 0" src="../../../img/noimg.jpg" />
                      </figure>
                      <h2 class="product-title">{{ item.title }}</h2>
                    </div>
                  </td>
                  <td>{{ item.likedProduct }} Pelqime</td>
                  <td>{{ item.price }} &euro;</td>
                  <td>
                    <div class="prod-edit pull-right">
                      <!-- <button @click="soldProd(item.id)" class="btn btn-primary">U shit</!-->
                      <!-- <br /> -->
                      <router-link
                        class="btn btn-primary-outline mr-1"
                        :to="{ name: 'edit-product',params: {id: item.id}}"
                      >Ndrysho</router-link>
                      <!-- <a href="#" class="btn btn-primary">Ndrysho</a> -->
                      <button
                        class="btn btn-primary-outline delete-btn"
                        @click="openDelete(index,item.id)"
                      >Fshije</button>
                      <!-- @click="remove(index,item.id)" -->
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
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
    <div class="mb-6" />
  </main>
</template>
<script src="https://unpkg.com/vuejs-paginate@0.9.0"></script>
<script>
import Paginate from "vuejs-paginate";
import Loader from "~/components/Loader";
import swal from "sweetalert2";

export default {
  metaInfo() {
    return {
      title: "Produktet e mija"
    };
  },
  components: {
    Paginate,
    Loader
  },
  data: () => ({
    loadingall: true,
    products: [],
    totalPage: 1,
    actualPage: 1,
    title: "",
    price: "",
    likes: "",
    statuses: [],
    status: 0,
    startDate: 0,
    endDate: 0
  }),
  mounted: function() {
    this.myProducts();
    this.getStatuses();
    window.Swal = swal;
  },
  methods: {
    openDelete(index, id) {
      Swal.fire({
        title: "A jeni i sigurt qe deshironi t'a fshini produktin tuaj?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e02b27",
        cancelButtonColor: "#65b891",
        confirmButtonText: "Po, jam i sigurt"
      }).then(result => {
        if (result.value) {
          this.remove(index, id);
        }
      });
    },
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function() {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    paginate(page) {
      this.actualPage = page;
      this.myProducts();
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
    dateChange(date, from) {
      if (from === 1) {
        this.startDate = date;
      } else {
        this.endDate = date;
      }
    },
    myProducts() {
      this.loadingall = true;
      this.products = [];
      let price = this.price;
      let title = this.title;
      let likes = this.likes;
      let startDate = this.startDate;
      let endDate = this.endDate;
      let status = this.status;
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
      if (!likes) {
        likes = 0;
      }
      fetch(
        "/api/products/my-product-paginate?title=" +
          title +
          "&price=" +
          price +
          "&startDate=" +
          startDate +
          "&endDate=" +
          endDate +
          "&likes=" +
          likes +
          "&status=" +
          status +
          "&page=" +
          this.actualPage,
        {
          method: "get"
        }
      )
        .then(result => result.json())
        .then(result => {
          this.products = result.response.data;
          this.totalPage = result.response.last_page;
          this.loadingall = false;
        })
        .catch(err => console.log(err));
    },
    soldProd(id) {
      fetch(`/api/products/sold-product/` + id, {
        method: "post"
      })
        .then(result => result.json())
        .then(result => {
          this.myProducts();
        })
        .catch(err => err);
    },
    remove(index, id) {
      this.loadingall = true;
      fetch(`/api/products/remove/` + id, {
        method: "post"
      })
        .then(result => result.json())
        .then(result => {
          this.products.splice(index, 1);
          this.loadingall = false;
        })
        .catch(err => err);
    }
  }
};
</script>
