import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'
import VueTabs from 'vue-nav-tabs'
import 'vue-nav-tabs/themes/vue-tabs.css'
import SocialSharing from 'vue-social-sharing'
import Snackbar from 'vuejs-snackbar';
import VueHtmlToPaper from 'vue-html-to-paper';

// const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')

import axios from 'axios'
import VueAxios from 'vue-axios'

import '~/plugins'
import '~/components'

const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css'
  ]
}

Vue.component('snackbar', Snackbar);

Vue.use(VueHtmlToPaper, options);
// or, using the defaults with no stylesheet

Vue.use(VueHtmlToPaper);

Vue.config.productionTip = false
Vue.use(BootstrapVue)
Vue.use(VueTabs)
Vue.use(SocialSharing)
Vue.use(VueAxios, axios)
/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App
})
