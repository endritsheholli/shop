<template>
  <main class="main">
    <div class="mb-4"></div>
    <Loader v-if="loadingall" :position="'fixed'" />
    <div class="container">
      <div class="col-12 p-0" v-if="this.shops">
        <h2 class="step-title">Të gjitha dyçanet</h2>
      </div>
      <div v-if="!this.shops">
        <div class="col-6 col-md-6 mx-auto">
          <img src="../../img/nomarket.png" alt="client" />
          <h2 class="step-title text-center">Nuk ka dyçane</h2>
        </div>
      </div>
      <div class="row" v-if="this.shops">
        <div v-for="(item, index) in shops" :key="index" class="col-6 col-md-2">
          <div class="shop">
            <div class="shop-img-parent">
              <router-link :to="{ name: 'profile', params: {name: item.shop_name.slug} }">
                <img v-if="item.img_path" :src="item.img_path" />
                <img v-if="!item.img_path" alt src="../../img/noimg.jpg" />
              </router-link>
            </div>
            <div class="shop-title">
              <router-link
                :to="{ name: 'profile', params: {name: item.shop_name.slug} }"
              >{{ item.shop_name.name }}</router-link>
            </div>
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
  </main>
</template>

<script>
import Loader from "~/components/Loader";
import Paginate from "vuejs-paginate";
export default {
  layout: "basic",
  components: {
    Paginate,
    Loader
  },

  data() {
    return {
      appName: window.config.appName,
      shops: [],
      totalPage: 1,
      actualPage: 1,
      loadingall: false
    };
  },
  mounted() {
    this.getShops();
  },
  methods: {
    getShops() {
      let self = this;
      self.axios.get("/api/shop?page=" + self.actualPage).then(res => {
        self.shops = res.data.response.data;
        self.totalPage = res.data.response.last_page;
        self.loadingall = false;
      });
    },
    paginate(page) {
      this.actualPage = page;
      this.getShops();
    }
  }
};
</script>
