import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// state
export const state = {
  user: null,
  token: Cookies.get('token'),
  token_posta: Cookies.get('token_posta'),
}

// getters
export const getters = {
  user: state => state.user,
  token: state => state.token,
  token_posta: state => state.token_posta,
  check: state => state.user !== null,
  role: state => state.user !== null ? state.user.role : ''
}

// mutations
export const mutations = {
  [types.SAVE_TOKEN](state, { token, remember }) {
    state.token = token
    Cookies.set('token', token, { expires: remember ? 365 : null })
  },
  [types.SAVE_TOKEN_POSTA](state, { token, remember }) {
    state.token_posta = token
    Cookies.set('token_posta', token, { expires: remember ? 365 : null })
  },

  [types.FETCH_USER_SUCCESS](state, { user }) {
    state.user = user
  },

  [types.FETCH_USER_FAILURE](state) {
    state.token = null
    Cookies.remove('token')
  },

  [types.LOGOUT](state) {
    state.user = null
    state.token = null

    Cookies.remove('token')
    Cookies.remove('token_posta')
  },

  [types.UPDATE_USER](state, { user }) {
    state.user = user
  }
}

// actions
export const actions = {
  saveToken({ commit, dispatch }, payload) {
    commit(types.SAVE_TOKEN, payload)
  },
  saveTokenPosta({ commit, dispatch }, payload) {
    commit(types.SAVE_TOKEN_POSTA, payload)
  },

  async fetchUser({ commit }) {
    try {
      const { data } = await axios.get('/api/user')

      commit(types.FETCH_USER_SUCCESS, { user: data })
    } catch (e) {
      commit(types.FETCH_USER_FAILURE)
    }
  },

  updateUser({ commit }, payload) {
    commit(types.UPDATE_USER, payload)
  },

  async logout({ commit }) {
    try {
      await axios.post('/api/logout')
    } catch (e) { }

    commit(types.LOGOUT)
  },

  async fetchOauthUrl(ctx, { provider }) {
    const { data } = await axios.post(`/api/oauth/${provider}`)

    return data.url
  }
}
