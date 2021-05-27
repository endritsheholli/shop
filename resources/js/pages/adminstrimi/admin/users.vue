<template>
  <main class="main">
    <Loader v-if="loadingall" :position="'inline'" />
    <div class="container">
      <div class="mb-4" />
      <div class="col-12 p-0">
        <div class="mob-sec-header mb-3">
          <div class="container p-0">
            <button v-on:click="triggerSidebar()" id="triside">
              <i class="fa fa-bars"></i> Paneli
            </button>
          </div>
        </div>
        <h2 class="step-title">Përdoruesit</h2>
      </div>
      <div class="row">
        <div class="col-12">
          <table>
            <thead>
              <tr>
                <th scope="col">Emri Mbiemri</th>
                <th scope="col">Kontakti</th>
                <th scope="col">Vendi</th>
                <th scope="col">Roli</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in users" :key="item.id">
                <td data-label="Emri Mbiemri">
                  <h2 class="product-title">{{ item.name }} {{ item.last_name }}</h2>
                </td>
                <td data-label="Kontakti">
                  {{ item.email }}
                  <br />
                  {{ item.phone }}
                </td>
                <td data-label="Vendi">{{ item.address }}</td>
                <td data-label="Roli">{{ item.role }}</td>
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
    <div class="mb-6" />
  </main>
</template>
<script>
import NumberInputSpinner from "vue-number-input-spinner";
import Paginate from "vuejs-paginate";
export default {
  components: {
    Paginate
  },
  data: () => ({
    users: [],
    totalPage: 1,
    actualPage: 1,
    loadingall: true
  }),
  mounted: function() {
    this.getUsers();
  },
  methods: {
    triggerSidebar() {
      let $ = require("jquery");

      $(document).ready(function() {
        $(".sidebar").toggleClass("mobile-sidebar");
        $(".content-transform").toggleClass("content-transform-show");
      });
    },
    getUsers() {
      let self = this;
      self.axios.get("/api/users?page=" + self.actualPage).then(res => {
        self.users = res.data.response.data;
        self.totalPage = res.data.response.last_page;
      });
      this.loadingall = false;
    },
    paginate(page) {
      this.actualPage = page;
      this.getUsers();
    }
  }
};
</script>
<style scoped>
table {
  border: 1px solid #ccc;
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  width: 100%;
  table-layout: fixed;
}

table caption {
  font-size: 1.5em;
  margin: 0.5em 0 0.75em;
}

table tr {
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  padding: 0.35em;
}

table th,
table td {
  padding: 0.625em;
  text-align: center;
}

table th {
  font-size: 0.85em;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

@media screen and (max-width: 600px) {
  table {
    border: 0;
  }

  table caption {
    font-size: 1.3em;
  }

  table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

  table tr {
    border-bottom: 3px solid #ddd;
    display: block;
    margin-bottom: 0.625em;
  }

  table td {
    border-bottom: 1px solid #ddd;
    display: block;
    font-size: 0.8em;
    text-align: right;
  }

  table td::before {
    /*
    * aria-label has no advantage, it won't be read inside a table
    content: attr(aria-label);
    */
    content: attr(data-label);
    float: left;
    font-weight: bold;
    text-transform: uppercase;
  }

  table td:last-child {
    border-bottom: 0;
  }
}
</style>