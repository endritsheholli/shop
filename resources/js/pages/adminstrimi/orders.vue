<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'fixed'" style="z-index: 1060;" />
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
          <!-- <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb pull-left">
              <li class="breadcrumb-item">
                <a href="index.html">
                  <i class="fa fa-home" />
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Porosit per shitje</li>
            </ol>
            <!- <div class="pull-right">
              <a href="#" class="btn btn-primary">Shto Produkt</a>
            </div>--
          </nav>-->
        </div>
      </div>
      <div class="mb-4" />
      <div class="row content-transform">
        <div class="col-12">
          <h2 class="step-title">Porositë në pritje</h2>
        </div>
        <div class="col-12">
          <button
            data-toggle="modal"
            data-target="#myPriceList"
            class="btn btn-primary-outline pull-right mr-4"
          >Qmimorja</button>
          <div class="modal fade" id="myPriceList" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Qmimorja</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <div v-for="(pr, index) in posts" :key="index" class="price-for-c">
                    <p class="sec-title">{{pr.name}}</p>
                    <div class="info-price" v-for="pri in pr.price_companies" :key="pri.id">
                      <div class="order-det">
                        <p>
                          <label for="from">Prej</label>
                          <span>{{pri.from}} kg</span>
                        </p>
                        <p>
                          <label for="to">Deri</label>
                          <span>{{pri.to}} kg</span>
                        </p>
                        <p>
                          <label for="price">Qmimi</label>
                          <span>{{pri.price}} €</span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="cart-table-container">
            <table class="table table-cart">
              <thead>
                <tr>
                  <th class="product-col">Bleresi</th>
                  <th class="price-col">Tel</th>
                  <th class="qty-col">Adresa</th>
                  <th class="qty-col">Data</th>
                  <th class="qty-col">Zgjedh posten</th>
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
                    <!-- required -->
                    <select
                      name="postman"
                      v-model="myprod.company_id"
                      class="form-control"
                      required
                    >
                      <option value="0">Zgjedh Posten</option>
                      <option v-for="post in posts" :key="post.id" :value="post.id">{{ post.name }}</option>
                    </select>
                  </td>
                  <td>
                    <div class="float-right">
                      <button
                        class="btn btn-decline mr-2"
                        data-toggle="modal"
                        data-target="#remove-order"
                        @click="openDissmisModal(index, myprod.id)"
                      >Refuzo</button>
                      <button
                        class="btn btn-primary-outline"
                        data-toggle="modal"
                        @click="openmodal(index)"
                        data-target="#prodInfo"
                      >Detajet</button>
                      <button class="btn btn-primary" @click="savePost(index)">Ruaj Posten</button>
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
                                <!-- <p>
                                  <label for="pesha">Pesha</label>
                                  <span>{{proddet.product.weight ? proddet.product.weight + ' kg': "--" }}</span>
                                </p>-->

                                <p>
                                  <label for="sasia">Sasia</label>
                                  <span>{{ proddet.quantity ? proddet.quantity + ' copë' : '--' }}</span>
                                </p>
                                <p class="comment-details">
                                  <label for="comment">Komenti</label>
                                  <span>{{proddet.comment ? proddet.comment : '--' }}</span>
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
          <div id="remove-order" class="modal fade" role="dialog" style="z-index: 1050!important;">
            <div class="modal-dialog mb-5">
              <div class="modal-content">
                <div class="modal-body">
                  <h3>Arsyeja e refuzimit te porosise</h3>
                  <textarea name v-model="orderComment" cols="30" style="width:100%"></textarea>
                  <!-- {{selectedOrderId}} - {{selectedOrderIndex}} -->
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Anulo</button>
                  <button
                    title="Ruaj komentin"
                    class="btn btn-primary"
                    @click="dissmisOrder(selectedOrderIndex, selectedOrderId)"
                  >Refuzo Porosin</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mb-6" />
  </main>
</template>
<script>
import Loader from "~/components/Loader";
import swal from "sweetalert2";

export default {
  components: { Loader },
  data: () => ({
    myProducts: [],
    loadingall: true,
    proddet: [],
    posts: [],
    item: 0,
    errMsg: "",
    sccMsg: "",
    orderComment: "",
    selectedOrderIndex: "",
    selectedOrderId: ""
  }),
  mounted: function() {
    this.getPosts();
    this.getMainCategories();
    window.Swal = swal;
  },
  methods: {
    openmodal(i) {
      this.item = i;
    },
    openDissmisModal(index, id) {
      this.selectedOrderIndex = index;
      this.selectedOrderId = id;
      let $ = require("jquery");
      $("#remove-order").modal("show");
    },
    dissmisOrder(index, id) {
      let data = {
        id: id,
        comment: this.orderComment
      };
      this.loadingall = true;
      this.axios.post(`/api/preorder/remove`, data).then(result => {
        if (result.data.status === "success") {
          this.myProducts.splice(index, 1);
          let $ = require("jquery");
          $("#remove-order").modal("hide");
          this.loadingall = false;
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
    savePost(index) {
      let $ = require("jquery");
      if (this.myProducts[index].company_id === 0) {
        this.errMsg = "Ju lutem zgjedheni postën !";
        $("#errAlert").modal("show");
        setTimeout(function() {
          $("#errAlert").modal("hide");
        }, 3000);
        return;
      }
      let data = this.myProducts[index];
      this.loadingall = true;
      this.axios.post(`/api/orders/assign/status`, data).then(result => {
        if (result.data.status === "success") {
          this.sccMsg = "Porosia u dergua me sukses te posta";
          $("#successAlert").modal("show");
          this.myProducts.splice(index, 1);
          setTimeout(function() {
            $("#successAlert").modal("hide");
          }, 3000);
        } else {
          this.errMsg = result.data.message;
          $("#errAlert").modal("show");
          setTimeout(function() {
            $("#errAlert").modal("hide");
          }, 3000);
        }
        this.loadingall = false;
      });
    },
    getMainCategories() {
      this.loadingall = true;
      this.axios
        .get("/api/orders/getFromShopId")
        .then(res => {
          const { response } = res.data;
          this.myProducts = response;
          this.loadingall = false;
        })
        .catch(function() {});
    },
    getPosts() {
      let vm = this;
      fetch(`/api/posta/company`, {
        method: "get"
      })
        .then(result => result.json())
        .then(result => {
          vm.posts = result.response;
        })
        .catch(function() {});
    }
  }
};
</script>
