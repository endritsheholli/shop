<template>
  <ul v-if="parameters.length" class="listCategories">
    <button @click="clearCat()" class="clear-btn">Reseto parametrat</button>
    <div v-for="(item, index) in parameters" :key="index" class="dropdown dropright">
      <a class="btn text-btn mb-1 w-100" :href="'#collapseExample' + index" data-toggle="collapse">
        <div class="d-flex justify-content-between">
          <!-- @click="showParameters(item.id)" -->
          <span>{{item.name}}</span>
          <span class="cat-arrow">
            <i class="fa fa-chevron-down"></i>
          </span>
        </div>
      </a>
      <div
        class="collapse"
        :id="'collapseExample' + index"
        v-for="subc in item.children"
        :key="subc.id"
      >
        <label class="custom-ch">
          <svg v-if="item.name == 'Ngjyra'" width="55" height="40">
            <rect
              x="20"
              y="0"
              rx="40"
              ry="0"
              width="25"
              height="25"
              :style="'fill:'+ subc.name + ';stroke: black;' "
            />
          </svg>
          <span v-if="item.name != 'Ngjyra'">{{ subc.name }}</span>
          <input
            :id="subc.name"
            v-model="brandselect"
            type="checkbox"
            :value="subc.id"
            :name="subc.name"
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
  name: "BrandList",
  components: {
    Loader
  },
  props: {
    cateId: [Number, String]
  },
  data: () => {
    return {
      categories: [],
      parameters: [],
      brandselect: [],
      category_id: "",
      id: "",
      category: 0,
      loadingall: true,
      loading: true
    };
  },
  mounted: function() {
    // this.getCategories();
    this.category = this.$props.cateId ? this.$props.cateId : 0;

    this.showParameters(this.category);
  },
  updated() {
    if (this.category != this.$props.cateId) {
      this.category = this.$props.cateId ? this.$props.cateId : 0;
      this.showParameters(this.category);
    }
  },
  methods: {
    clearCat() {
      this.brandselect = [];
    },
    showParameters(id) {
      let caid = id ? id : 0;
      let vm = this;
      vm.loadingall = true;
      // id = this.$route.params.id ? this.$route.params.id : 0;
      vm.axios.get("/api/parameters/getByCategoryId/" + caid).then(res => {
        const { data } = res;
        vm.parameters = res.data.response;
        this.loadingall = false;
      });
      /* fetch(`/api/categories/getAllCategories`, {
        method: "get"
      });*/
    },
    // inputCheck() {
    //   let $ = require("jquery");
    //   $(".dropdown-menu").click(function(event) {
    //     event.stopPropagation();
    //   });
    //   $(".dropdown-menu.show").on("click", function(e) {
    //     e.stopPropagation();
    //   });
    // },
    // getCategories(id = null) {
    //   let self = this;
    //   self.loadingall = true;
    //   self.axios.get("/api/parameters/getParents").then(res => {
    //     const { data } = res;
    //     self.categories = data.response;
    //     if (!id) {
    //       self.showParameters(self.categories.id);
    //     } else {
    //       self.showParameters(id);
    //     }
    //     this.loadingall = false;
    //   });
    // },
    getMultiselect() {
      return this.brandselect;
    }
  }
};
</script>
