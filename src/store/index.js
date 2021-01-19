import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import moment from 'moment'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    currentUser: null,
    isError: false,
    message: null,
    route: null,
    staff: [],
    displayStaff: [],
    stock: [],
    displayStock: [],
    sales: [],
    users: [],
    itemSales: [],
    staffSales: []
  },

  getters: {
    CURRENT_USER: function (state) {
      return state.currentUser
    },
    ROUTE: function (state) {
      return state.route
    },
    STOCK: function (state) {
      return state.stock
    },
    DISPLAY_STOCK: function (state) {
      return state.displayStock
    },
    STAFF: function (state) {
      return state.staff
    },
    DISPLAY_STAFF: function (state) {
      return state.displayStaff
    },
    USERS: function (state) {
      return state.users
    },
    STAFF_SALES: (state) => (nombre) => {
      for (var i = 0; i < state.sales.length; i++) {
        if (state.sales[i].salesperson === nombre) {
          state.staffSales.push(state.sales[i])
        }
      }
      return state.staffSales
    },
    ITEM_SALES: (state) => (itemName) => {
      for (var i = 0; i < state.sales.length; i++) {
        if (state.sales[i].item_name === itemName) {
          state.itemSales.push(state.sales[i])
        }
      }
      return state.itemSales
    },
    RESPONSE: function (state) {
      return { message: state.message, isError: state.isError }
    }
  },

  mutations: {
    SET_STOCK: function (state, stock) {
      state.stock = stock
    },
    SET_STAFF: function (state, staff) {
      state.staff = staff
    },
    SET_SALES: function (state, sales) {
      state.sales = sales
    },
    SET_USERS: function (state, users) {
      state.users = users
    },
    SET_ROUTE: function (state, route) {
      state.route = route
    },
    SET_RESPONSE: function (state, payload) {
      state.message = payload.message
      state.isError = payload.error
    },
    SET_USER: function (state, payload) {
      state.currentUser = payload
    },
    CATEGORIZE_STOCK: function (state) {
      state.displayStock.splice(0, state.displayStock.length)
      var stock = state.stock
      var innerList = []
      while (stock.length > 0) {
        var cat = stock[0].category
        var catArray = []
        for (var i = 0; i < stock.length; i++) {
          if (stock[i].category === cat) {
            catArray.push(stock[i])
          }
        }
        while (catArray.length > 0) {
          var subcat = catArray[0].sub_category
          var subcatArray = []
          for (var j = 0; j < catArray.length; j++) {
            if (catArray[j].sub_category === subcat) {
              subcatArray.push(catArray[j])
            }
          }
          innerList.push({ subcategory: subcat, list: subcatArray })
          catArray = catArray.filter(item => !item.sub_category.includes(subcat))
        }
        state.displayStock.push({ category: cat, subcat: innerList })
        stock = stock.filter(({ category }) => !category.includes(cat))
      }
    },
    CATEGORIZE_EMPLOYEES: function (state) {
      state.displayStaff.splice(0, state.displayStaff.length)
      var staff = state.staff
      while (staff.length > 0) {
        var off = staff[0].office
        var officeArr = []
        for (var i = 0; i < staff.length; i++) {
          if (staff[i].office === off) {
            officeArr.push(staff[i])
          }
        }
        state.displayStaff.push({ office: off, employees: officeArr })
        staff = staff.filter(({ office }) => !office.includes(off))
      }
    },
    RESET_DETAILS: function (state) {
      state.currentUser = null
      state.message = ''
      state.isError = false
    }
  },

  actions: {
    RESET: function (context) {
      context.commit('RESET_DETAILS')
    },
    SET_ROUTE: function (context, r) {
      context.commit('SET_ROUTE', r)
    },
    SET_RESPONSE: function (context, payload) {
      context.commit('SET_RESPONSE', payload)
    },
    SET_USER: function (context, payload) {
      context.commit('SET_USER', payload)
    },
    SET_USERS: function (context, payload) {
      context.commit('SET_USERS', payload)
    },
    SET_SALES: function (context, payload) {
      context.commit('SET_SALES', payload)
    },
    SET_STAFF: function (context, payload) {
      context.commit('SET_STAFF', payload)
      context.commit('CATEGORIZE_EMPLOYEES')
    },
    SET_STOCK: function (context, payload) {
      context.commit('SET_STOCK', payload)
      context.commit('CATEGORIZE_STOCK')
    }
  },

  modules: {
  }
})

export default store
