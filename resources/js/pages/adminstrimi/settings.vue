<template>
  <main class="main">
    <div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-md-10 offset-md-1">
            <div class="mb-6" />
            <h2 class="step-title">
              Permirso te dhenat
            </h2>
            <div class="mb-3" />
            <form
              id="step-1"
              class="row"
              @submit.prevent="register"
              @keydown="form.onKeydown($event)"
            >
              <div class="input-group mb-3 col-12 col-md-6">
                <input v-model="form.name" required :class="{ 'is-invalid': form.errors.has('name') }" class="form-control inputs" type="text" name="name" placeholder="Emri">
                <has-error :form="form" field="name" />
              </div>
              <div class="input-group mb-3 col-12 col-md-6">
                <input v-model="form.last_name" :class="{ 'is-invalid': form.errors.has('last_name') }" class="form-control inputs" type="text" name="last_name" placeholder="Mbiemri">
                <has-error :form="form" field="last_name" />
              </div>
              <div class="input-group mb-3 col-12 col-md-6">
                <input v-model="form.email" required :class="{ 'is-invalid': form.errors.has('email') }" class="form-control inputs" type="email" name="email" placeholder="E-Mail">
                <has-error :form="form" field="email" />
              </div>
              <div class="input-group mb-3 col-12 col-md-6">
                <input v-model="form.phone" required :class="{ 'is-invalid': form.errors.has('phone') }" class="form-control inputs" type="text" name="phone" placeholder="Numri i telefonit">
                <has-error :form="form" field="phone" />
              </div>
              <div class="input-group col-12 col-md-6">
                <b-form-select v-model="form.city" class="form-control inputs mb-3">
                  <option :value="0" :selected="true">
                    Qyteti
                  </option>
                  <option :value="1">
                    Artanç
                  </option>
                  <option :value="2">
                    Deçan
                  </option>
                  <option :value="2">
                    Ferizaj
                  </option>
                  <option :value="2">
                    Podujev
                  </option>
                  <option :value="3">
                    Prishtine
                  </option>
                </b-form-select>
              </div>
              <div class="input-group col-12 col-md-6">
                <input v-model="form.address" :class="{ 'is-invalid': form.errors.has('address') }" class="form-control inputs" name="address" placeholder="Adresa" rows="false">
                <has-error :form="form" field="address" />
              </div>
              <!-- Pjesa per shop -->
              <div class="input-group mb-3 col-12 col-md-6">
                <input v-model="form.shop_name" :class="{ 'is-invalid': form.errors.has('shop_name') }" class="form-control inputs" type="text" name="shop_name" placeholder="Emri i shopit">
                <has-error :form="form" field="shop_name" />
              </div>
              <div class="input-group mb-3 col-12 col-md-6">
                <b-form-select v-model="form.interest_id" class="form-control inputs mb-1">
                  <option :value="0" :selected="true">
                    Me intereson..
                  </option>
                  <option v-for="cats in categories" :key="cats.id" :value="cats.id">
                    {{ cats.name }}
                  </option>
                </b-form-select>
                <has-error :form="form" field="category" />
              </div>
              <!-- Perfundimi i pjeses se shopit -->
              <div class="col-12">
                <hr style="margin:10px 0;">
              </div>
              <div class="col-md-6 col-12">
                <button type="submit" class="btn btn-primary">
                  Permirso
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="mb-6" />
    </div>
  </main>
</template>
<script>
import Form from 'vform'

export default {

  metaInfo () {
    return {
      title: 'Preferencat'
    }
  },

  data: () => ({
    categories: [],
    form: new Form({
      name: '',
      last_name: '',
      email: '',
      city: 0,
      shop_name: '',
      phone: '',
      address: '',
      interest_id: 0,
      country_id: ''
    })
  }),
  mounted: function () {
    this.getMainCategories()
    this.getUserData()
  },
  methods: {
    getMainCategories () {
      this.axios.get('/api/categories/getParents').then(res => {
        const { response } = res.data
        this.categories = response
      })
    },
    getUserData () {
      var self = this
      this.axios.get('/api/userDate').then(res => {
        const { response } = res.data
        self.form.name = response.name
        self.form.last_name = response.last_name
        self.form.email = response.email
        self.form.city = response.city
        self.form.shop_name = response.shop_name.name
        self.form.phone = response.phone
        self.form.address = response.address
        self.form.interest_id = response.interest_id
      })
    }
  }
}
</script>
