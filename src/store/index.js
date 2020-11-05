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
      { name: 'Cashier One', address: 'cash_1@gmail.com', pword: 'T3rm!n0l', role: 'Cashier' }
    ],
    staff: [{ office: 'Main', employees: [{ idNo: '12345671', fullName: 'Cashier One', fname: 'Cashier', oname: 'One', phone: '254712345678', num: '001', img: 'avatar_1', status: 'On Duty', email: 'cash_1@gmail.com' }, { idNo: '19845671', fullName: 'Cashier Two', fname: 'Cashier', oname: 'Two', phone: '254798765432', num: '002', img: 'avatar_2', status: 'On Duty', email: 'cash_2@gmail.com' }] },
      {
        office: 'Thika',
        employees: [{
          idNo: '19823745', fullName: 'Cashier Three', fname: 'Cashier', oname: 'Three', phone: '254776123984', num: '003', img: 'avatar_3', status: 'On Vacation', email: 'cash_3@gmail.com'
        },
        { idNo: '32456123', fullName: 'Cashier Four', fname: 'Cashier', oname: 'Four', phone: '254777542365', num: '004', img: 'avatar_4', status: 'On Duty', email: 'cash_1@gmail.com' }]
      }],
    stock: [{ category: 'Gutters', subcat: [{ subcategory: 'Plastic', list: [{ name: 'Red Gutter', img: 'redplasticgutter', price: 3000, quantity: 100 }, { name: 'Green Gutter', img: 'greenplasticgutter', price: 3000, quantity: 70 }] }, { subcategory: 'Metallic', list: [{ name: 'Silver Gutter', img: 'silvermetalgutter', price: 1800, quantity: 50 }, { name: 'Black Gutter', img: 'blackmetalgutter', price: 1600, quantity: 65 }] }] }, { category: 'Decra', subcat: [{ subcategory: 'Shingles', list: [{ name: 'Black Shingles', img: 'blackshingles', price: 500, quantity: 80 }, { name: 'Green Shingles', img: 'greenshingles', price: 500, quantity: 55 }, { name: 'Coffee Shingles', img: 'coffeeshingles', price: 500, quantity: 77 }] }, { subcategory: 'Classic', list: [{ name: 'Black Decra', img: 'blackclassicdecra', price: 600, quantity: 50 }, { name: 'Green Decra', img: 'greenclassicdecra', price: 600, quantity: 90 }, { name: 'Red Decra', img: 'redclassicdecra', price: 600, quantity: 88 }] }] },
      {
        category: 'Curtain Rods',
        subcat: [{
          subcategory: '2mm',
          list: [{ name: 'Red 2mm Rod', img: 'red2mmrods', price: 250, quantity: 250 }, {
            name: 'Grey 2mm Rod',
            img: 'grey2mmrods',
            price: 250,
            quantity: 300
          }]
        }, { subcategory: '3mm', list: [{ name: 'Red 3mm Rod', img: 'red3mmrods', price: 300, quantity: 550 }, { name: 'Grey 3mm Rod', img: 'grey3mmrods', price: 300, quantity: 300 }] }]
      }
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
    },
    STOCK: function (state) {
      return state.stock
    },
    STAFF: function (state) {
      return state.staff
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
    },
    UPDATE_STOCK: function (state, payload) {
      state.stock = payload
    },
    UPDATE_STAFF: function (state, payload) {
      state.staff = payload
    }
  },

  actions: {
    SIGN_IN: function (context, payload) {
      context.commit('SIGN_IN', payload)
    },
    RESET: function (context) {
      context.commit('RESET_DETAILS')
    },
    UPDATE_STOCK: function (context, payload) {
      context.commit('UPDATE_STOCK', payload)
    },
    UPDATE_STAFF: function (context, payload) {
      context.commit('UPDATE_STAFF', payload)
    }
  },

  modules: {
  }
})

export default store
