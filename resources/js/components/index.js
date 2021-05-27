import Vue from 'vue'
import Card from './Card'
import Cards from './Cards'
import Child from './Child'
import Button from './Button'
import Checkbox from './Checkbox'
import Callout from './Callout'
import Loader from './Loader'
import Shopping from './AddCard'
import Wishlist from './LikedProduct'
import { HasError, AlertError, AlertSuccess } from 'vform'

// Components that are registered globaly.
[
  Card,
  Cards,
  Callout,
  Child,
  Loader,
  Button,
  Checkbox,
  HasError,
  AlertError,
  Shopping,
  Wishlist,
  AlertSuccess
].forEach(Component => {
  Vue.component(Component.name, Component)
})
