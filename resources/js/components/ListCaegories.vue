<template>
  <ul class="listCategories">
    <button @click="clearCat()" class="clear-btn">Reseto kategorite</button>
    <div v-for="(item, index) in categories" :key="index" class="dropdown dropright">
      <a
        v-if="$props.urlcatid == item.id || $props.urlcatid == 0"
        class="btn text-btn mb-1 w-100"
        :href="'#showParameters2' + index"
        data-toggle="collapse"
      >
        <div class="d-flex justify-content-between">
          <span @click="showCategories(item.id)">{{item.name}}</span>
          <span class="cat-arrow">
            <i class="fa fa-chevron-down"></i>
          </span>
        </div>
      </a>
      <div
        class="collapse"
        :id="'showParameters2' + index"
        v-for="subc in item.children"
        :key="subc.id"
      >
        <label class="custom-ch">
          <span>{{ subc.name }}</span>
          <input
            :id="subc.name"
            v-model="multiselectCh"
            type="checkbox"
            :value="subc.id"
            :name="subc.name"
            @change="$parent.getCatId()"
          />
          <span class="checkmark"></span>
        </label>
      </div>
    </div>
  </ul>
</template>
<script>
import Multiselect from "vue-multiselect";
import Loader from "~/components/Loader";
export default {
  name: "CategoryList",
  components: {
    Loader
  },
  props: {
    urlcatid: [Number, String]
  },
  data: () => {
    return {
      categories: [],
      multiselectCh: [],
      category_id: "",
      id: "",
      loadingall: true,
      loading: true
    };
  },
  mounted: function() {
    this.clearCat();
    this.getCategories();
    this.showCategories();
  },
  methods: {
    clearCat() {
      this.multiselectCh = [];
    },
    getCategories(id = null) {
      let self = this;
      self.loadingall = true;
      self.axios.get("/api/categories/getParents").then(res => {
        const { data } = res;
        this.clearCat();

        self.categories = data.response;
        if (!id) {
          self.showCategories(self.categories.id);
        } else {
          self.showCategories(id);
        }
        this.loadingall = false;
      });
    },
    showCategories(id) {
      let vm = this;
      // this.clearCat();
      vm.loadingall = true;
      id = this.$route.params.id ? this.$route.params.id : 0;
      vm.axios.get("/api/categories/getMainCatById/" + id).then(res => {
        const { data } = res;
        vm.categories = res.data.response;
        this.loadingall = false;
      });
      /* fetch(`/api/categories/getAllCategories`, {
        method: "get"
      });*/
    },
    inputCheck() {
      let $ = require("jquery");
      $(".dropdown-menu").click(function(event) {
        event.stopPropagation();
      });
      $(".dropdown-menu.show").on("click", function(e) {
        e.stopPropagation();
      });
    },
    getMultiselect() {
      return this.multiselectCh;
    }
  }
};
</script>
