import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    userExist: false,
    truePassword: false,
    currentUser: null,
    users: [
      { name: 'Boss', address: 'admin_kc@gmail.com', pword: 'H9rdW@r3', role: 'Admin' },
      { name: 'Cashier 1', address: 'cash_1@gmail.com', pword: 'T3rm!n0l', role: 'Cashier' }
    ]
  },

  getters: {
    USER_EXIST: function (state) {
      return state.userExist
    },
    TRUE_PASSWORD: function (state) {
      return state.truePassword
    },
    CURRENT_USER: function (state) {
      return state.currentUser
    }
  },

  mutations: {
    SIGN_IN: function (state, payload) {
      var i
      for (i = 0; i < state.users.length; i++) {
        if (state.users[i].address === payload.address) {
          state.userExist = true
          if (state.users[i].pword === payload.pword) {
            state.truePassword = true
            state.currentUser = state.users[i]
          }
        }
      }
    },
    RESET_DETAILS: function (state) {
      state.userExist = false
      state.truePassword = false
      state.currentUser = null
    }
  },

  actions: {
    SIGN_IN: function (context, payload) {
      context.commit('SIGN_IN', payload)
    },
    RESET: function (context) {
      context.commit('RESET_DETAILS')
    }
  },

  modules: {
  }
})

export default store
