<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="container">
      <div id="successAlert" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <h3>{{ sccMsg }}</h3>
            </div>
          </div>
        </div>
      </div>
      <div id="errAlert" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <h3>{{ errMsg }}</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-lg-8">
          <div v-if="this.step === 1" class="cart-table-container">
            <div v-if="this.shopping.length==0" class="no-products">
              <p>Nuk keni asnjë produkt në shportë</p>
              <img src="/img/rocket.png" />
            </div>
            <table v-if="this.shopping.length!==0" class="table table-cart">
              <thead>
                <tr>
                  <th class="product-col">Foto</th>
                  <th>Produkti</th>
                  <th>Çmimi</th>
                  <th>Sasia</th>
                  <th>Ne stock</th>
                  <th>Komenti</th>
                  <th>Largo</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item ,index) in shopping" :key="item.id" class="product-row position-r">
                  <td>
                    <figure class="product-image-container shopping-cart-prod">
                      <a :href="'/product/' + item.product.slug" class="product-image">
                        <v-zoom
                          v-if="item.product.image_urls && item.product.image_urls.length !== 0"
                          :img="item.product.image_urls[0]"
                          width="100%"
                          :src="item.product.image_urls[0]"
                        />
                        <img v-else src="../../../img/noimg.jpg" alt />
                      </a>
                      <h2 class="product-title shopping-cart-title">
                        <a :href="'/product/' + item.product.slug">{{ item.product.title }}</a>
                        <p
                          class="no-quantity-res"
                          v-if="item.id === errId"
                        >Ky produkt nuk eshte ne stock</p>
                      </h2>
                    </figure>
                  </td>
                  <td class="comment-section">
                    <small>{{ item.comment }}</small>
                  </td>
                  <td>{{ item.product ? item.product.price:"" }}&euro;</td>
                  <td>
                    <div class="count-content">
                      <button class="btn count-btn" @click="removeQuantity(index)">-</button>
                      <p class="count-input">{{ item.quantity }}</p>
                      <button class="btn count-btn" @click="addQuantity(index)">+</button>
                    </div>
                  </td>
                  <td>
                    <p class="m-0">{{ item.product.quantity }}</p>
                  </td>
                  <td>
                    <a
                      class="btn text-btn"
                      data-toggle="modal"
                      :data-target="'#commentAlert' + index"
                    >{{ item.comment != '' ? 'Ndrysho komentin' : 'Shto koment'}}</a>
                  </td>
                  <td>
                    <a
                      class="remove-btn"
                      @click="openRemoveShopping(item.id,index,item.product.price,item.quantity)"
                    >
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                  <div :id="'commentAlert' + index" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-body">
                          <h4 style="margin: 2rem 0;">Shtoni komentin e juaj ketu</h4>
                          <textarea
                            name="comment"
                            id="comment"
                            v-model="item.comment"
                            style="width: 100%;height: 100px;"
                            maxlength="250"
                          ></textarea>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Anulo</button>
                          <button
                            title="Ruaj komentin"
                            class="btn btn-primary"
                            @click="commentShopp(item.id, item.comment, index)"
                          >Ruaj komentin</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </tr>
              </tbody>

              <tfoot>
                <tr>
                  <td colspan="7" class="clearfix">
                    <!-- End .float-left -->
                    <div class="float-right">
                      <a
                        class="btn btn-outline-secondary btn-clear-cart"
                        @click="removeAllShopping()"
                      >Fshij te gjitha</a>
                    </div>
                    <!-- End .float-right -->
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div v-else>
            <form @submit.prevent="createOrder()">
              <div class="col-md-4 col-12 col-sm-12 p-0">
                <input v-model="buyer_name" name="name" class="form-control" placeholder="Emri" />
              </div>
              <div class="col-md-4 col-12 col-sm-12 p-0">
                <input
                  v-model="phone_number"
                  name="phone"
                  class="form-control"
                  placeholder="Numri i telefonit"
                />
              </div>
              <div class="col-md-4 col-12 col-sm-12 p-0">
                <input v-model="address" class="form-control" name="address" placeholder="Adresa" />
              </div>
              <div class="col-md-4 col-12 col-sm-12 p-0">
                <select class="form-control" name="City Id" v-model="city_id" id>
                  <option value="0" selected disabled>Qyteti</option>
                  <option
                    v-for="(city, index) in cities"
                    :value="city.id"
                    :key="index"
                  >{{ city.name }}</option>
                </select>
              </div>
            </form>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="cart-summary">
            <h3>Fatura</h3>
            <table class="table table-totals">
              <tbody>
                <tr v-for="item in shopping" :key="item.id">
                  <td>{{ item.product.title }}</td>
                  <td>{{ item.product ? item.product.price : "" }} &euro; {{ item.quantity > 1 ? '* ' + item.quantity : ''}}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td>Totali</td>
                  <td>{{ total }}&euro;</td>
                </tr>
              </tfoot>
            </table>

            <div class="checkout-methods">
              <button
                v-if="this.step === 2"
                @click="decrementStep()"
                class="btn btn-block btn-sm btn-primary-outline m-0 mr-1"
              >Prapa</button>
              <button
                v-if="this.step === 1"
                @click="incrementStep()"
                class="btn btn-block btn-sm btn-primary m-0"
                :class="this.shopping.length == 0 ? 'btn-disable' : ''"
              >Vazhdo</button>
              <button
                v-if="this.step === 2"
                @click="createOrder()"
                class="btn btn-block btn-sm btn-primary m-0"
              >Porosit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mb-6" />
  </main>
</template>
<script>
import vZoom from "vue-zoom";
import router from "~/router";
import Loader from "~/components/Loader";
import swal from "sweetalert2";

export default {
  layout: "nosidebar",
  middleware: "auth",

  components: { vZoom, Loader },
  data() {
    return {
      cities: [],
      buyer_name: "",
      phone_number: "",
      loadingall: true,
      address: "",
      city_id: 0,
      step: 1,
      appName: window.config.appName,
      shopping: [],
      total: 0,
      width: 200,
      quantityItem: [],
      prodQuantity: 1,
      errMsg: "",
      sccMsg: "",
      errId: 0,
      comment: "this is comment"
    };
  },
  created() {
    this.getShopping();
    window.Swal = swal;
    // this.shopping[index].quantity = this.prodQuantity;
  },
  mounted: function() {
    this.getCities();
  },
  methods: {
    incrementStep() {
      this.step += 1;
    },
    decrementStep() {
      this.step -= 1;
    },
    getCities() {
      this.axios.get("/api/get/cities").then(response => {
        this.cities = response.data.response;
      });
    },
    getShopping() {
      this.errId = 0;
      let vm = this;
      this.loadingall = true;
      this.axios.get(`/api/shoppingCard/getByUserId`).then(result => {
        vm.shopping = result.data.response.shopping_card;
        vm.total = result.data.response.total;
        this.loadingall = false;
      });
    },
    commentShopp(id, comment, index) {
      let data = {
        id: id,
        comment: comment
      };
      let $ = require("jquery");
      this.axios.post(`/api/shoppingCard/comment`, data).then(result => {
        if (result.data.status === "success") {
          let $ = require("jquery");
          $("#commentAlert" + index).modal("hide");
        }
      });
    },
    openRemoveShopping(id, index, price, quantity) {
      Swal.fire({
        title: "A jeni i sigurt qe deshironi t'a fshini produktin nga shporta?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e02b27",
        cancelButtonColor: "#65b891",
        confirmButtonText: "Po, jam i sigurt"
      }).then(result => {
        if (result.value) {
          this.removeShopping(id, index, price, quantity);
        }
      });
    },
    removeShopping(id, index, price, quantity) {
      this.axios.post(`/api/shoppingCard/remove/${id}`).then(result => {
        if (result.data.status === "success") {
          this.shopping.splice(index, 1);
          this.total = this.total - price * quantity;
        }
      });
    },
    removeAllShopping() {
      this.errId = 0;
      let array_ids = [];
      this.shopping.map(x => array_ids.push(x.id));
      this.axios
        .post(`/api/shoppingCard/remoceAllProduct`, { array_id: array_ids })
        .then(result => {
          if (result.data.status === "success") {
            this.shopping = [];
            this.total = 0;
            location.reload();
          }
        });
    },
    addQuantity(index) {
      if (
        this.shopping[index].quantity < this.shopping[index].product.quantity
      ) {
        this.shopping[index].quantity = this.shopping[index].quantity + 1;
        let price = this.shopping[index].product.price;
        this.total = this.total + price;
      }
    },
    removeQuantity(index) {
      if (this.shopping[index].quantity > 1) {
        this.shopping[index].quantity = this.shopping[index].quantity - 1;
        let price = this.shopping[index].product.price;
        this.total = this.total - price;
      }
    },
    createOrder() {
      this.loadingall = true;
      let data = {
        buyer_name: this.buyer_name,
        phone_number: this.phone_number,
        address: this.address,
        city_id: this.city_id ? this.city_id : 0,
        shopping_card: this.shopping
      };
      if (this.city_id === 0) {
        this.loadingall = false;
        this.errMsg = "Ju lutem zgjedheni qytetin!";
        $("#errAlert").modal("show");
        setTimeout(function() {
          $("#errAlert").modal("hide");
        }, 3000);
        return;
      }
      for (let i = 0; i < this.shopping.length; i++) {
        if (this.shopping[i].id === this.errMsg) {
          this.errId = this.shopping[i].product_id;
        }
      }
      this.axios.post(`/api/orders/createfromZogu`, data).then(result => {
        if (result.data.status === "success") {
          this.loadingall = false;
          this.sccMsg = "Porosia u krye me sukses";
          $("#successAlert").modal("show");
          setTimeout(function() {
            $("#successAlert").modal("hide");
            window.location.href = "/";
          }, 3000);
          this.errId = 0;
          this.removeAllShopping();
        } else {
          this.loadingall = false;
          this.step -= 1;
          this.errMsg = result.data.message;
          this.errId = this.errMsg;
          // $("#errAlert").modal("show");
          // setTimeout(function() {
          //   $("#errAlert").modal("hide");
          // }, 3000);
        }
      });
    }
  }
};
</script>
