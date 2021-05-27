import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  usershopping: [],
  total: 0,
  length_shoping: 0
}

// getters
export const getters = {
  usershopping: state => state.usershopping,
  total: state => state.total,
  length_shoping: state => state.length_shoping
}

// mutations
export const mutations = {
  [types.ADD_ITEM] (state, { usershopping }) {
    state.usershopping = usershopping
  },

  [types.REMOVE_ITEM] (state, { usershopping }) {
    state.usershopping = usershopping
  },

  [types.FETCH_ITEMS_SUCCESS] (state, { usershopping, total, length_shoping }) {
    state.usershopping = JSON.parse(JSON.stringify(usershopping))
    state.total = total
    state.length_shoping = length_shoping
  },

  [types.FETCH_ITEMS_FAILURE] (state) {
    state.usershopping = state.usershopping
  },
  [types.FETCH_TOTAL_SUCCESS] (state, { total }) {
    state.total = total
  }
}

// actions
export const actions = {

  addItem ({ commit }, payload) {
    commit(types.ADD_ITEM, payload)
  },

  removeItem ({ commit }, payload) {
    commit(types.REMOVE_ITEM, payload)
  },

  fetchTotal ({ commit, total }, payload) {
    commit(types.FETCH_TOTAL_SUCCESS, { total: total })
  },
  async fetchItems ({ commit }) {
    try {
      const { data } = await axios.get('/api/shoppingCard/getThreeProduct')

      commit(types.FETCH_ITEMS_SUCCESS, { usershopping: data.response.shopping_card, total: data.response.total, length_shoping: data.response.length_shoping })
    } catch (e) {
      commit(types.FETCH_ITEMS_FAILURE)
    }
  }
}
