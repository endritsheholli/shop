<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="container">
      <div id="errAlert" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <h3>{{ errMsg }}</h3>
            </div>
          </div>
        </div>
      </div>
      <div id="successAlert" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <h3>{{ sccMsg }}</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 p-0">
          <div class="mob-sec-header">
            <div class="container">
              <button v-on:click="triggerSidebar()" id="triside">
                <i class="fa fa-bars"></i> Paneli
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="mb-4" />
      <div class="row content-transform">
        <div class="col-12">
          <h2 class="step-title">Order</h2>
        </div>

        <div class="col-lg-12">
          <div class="col-md-7 col-12" v-if="this.myProducts || this.myProducts.length < 0">
            <div class="no-orders mb-3">
              <p>You have not any orders yet.</p>
            </div>
            <button class="btn btn-primary">Shop now</button>
          </div>
          <div v-else class="cart-table-container">
            <table class="table table-cart">
              <thead>
                <tr>
                  <th class="product-col">Bleresi</th>
                  <th class="price-col">Tel</th>
                  <th class="qty-col">Adresa</th>
                  <th class="qty-col">Data</th>
                  <th>
                    <div class="float-right">Aksionet</div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class="product-row" v-for="(myprod,index) in myProducts" :key="myprod.id">
                  <td>{{ myprod.buyer_name }}</td>
                  <td>{{ myprod.phone_number }}</td>
                  <td>{{ myprod.address }}</td>
                  <td>{{ (myprod.created_at).substring(0,10) }}</td>

                  <td>
                    <div class="float-right">
                      <button
                        class="btn btn-primary-outline"
                        data-toggle="modal"
                        @click="openmodal(index)"
                        data-target="#prodInfo"
                      >Detajet</button>
                    </div>
                  </td>
                  <div class="modal" id="prodInfo">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                          <h4 class="modal-title">Detajet e bleresit</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                          <h4 class="sec-title">Te dhenat e bleresit</h4>
                          <ul class="m-personal-info order-detail">
                            <li class="order-det mr-1">
                              <p>
                                <label for="Bleresi">Bleresi</label>
                                <span>{{ myprod.buyer_name ? myprod.buyer_name : '' }}</span>
                              </p>
                            </li>
                            <li class="order-det mr-1">
                              <p>
                                <label for="tel">Telefoni</label>
                                <span>{{ myprod.phone_number ? myprod.phone_number : '' }}</span>
                              </p>
                            </li>
                            <li class="order-det">
                              <p>
                                <label for="address">Adresa</label>
                                <span>{{ myprod.address ? myprod.address : '' }}</span>
                              </p>
                            </li>
                          </ul>
                          <div v-if="myProducts[item].preorders_details.length > 0">
                            <h4 class="sec-title">Te dhenat e Porosive</h4>
                            <div class="order-detail">
                              <div
                                v-for="proddet in myProducts[item].preorders_details"
                                :key="proddet.id"
                                class="order-det mb-2"
                              >
                                <p>
                                  <label for="produkti">Produkti</label>
                                  <span>{{proddet.product.title ? proddet.product.title : ''}}</span>
                                </p>
                                <p>
                                  <label for="qmimi">Qmimi</label>
                                  <span>{{proddet.product.price ? proddet.product.price + '&euro;' : "--" }}</span>
                                </p>
                                <p>
                                  <label for="pesha">Pesha</label>
                                  <span>{{proddet.product.weight ? proddet.product.weight + ' kg': "--" }}</span>
                                </p>
                                <p>
                                  <label for="sasia">Sasia</label>
                                  <span>{{ proddet.quantity ? proddet.quantity + ' copë' : '--' }}</span>
                                </p>
                                <p>
                                  <a
                                    class="btn btn-primary-outline"
                                    target="_blank"
                                    :href="'/product/' + proddet.product.slug"
                                  >Shiko</a>
                                </p>
                                <!--  <p>madhsia {{proddet.product.size}}</p> -->
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="mb-6" />
  </main>
</template>
<script>
import Loader from "~/components/Loader";

export default {
  components: { Loader },
  data: () => ({
    myProducts: [],
    loadingall: true,
    proddet: [],
    posts: [],
    item: 0,
    errMsg: "",
    sccMsg: ""
  }),
  mounted: function() {
    this.getProductsHistory();
  },
  methods: {
    openmodal(i) {
      this.item = i;
    },
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function() {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },

    getProductsHistory() {
      this.loadingall = true;
      this.axios
        .get("/api/preorders/buyer")
        .then(res => {
          const { response } = res.data;
          this.myProducts = response;
          this.loadingall = false;
        })
        .catch(function() {});
    }
    // getPosts() {
    //   this.loadingall = true;
    //   this.loadingall = false;
    // }
  }
};
</script>
