import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    currentUser: null,
    error: null,
    staff: [],
    displayStaff: [],
    stock: [],
    displayStock: [],
    sales: [],
    displaySales: []
  },

  getters: {
    CURRENT_USER: function (state) {
      return state.currentUser
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
    DISPLAY_SALES: function (state) {
      return state.displaySales
    },
    ERROR: function (state) {
      return state.error
    }
  },

  mutations: {
    LOAD_STOCK: function (state) {
      axios.get('@/api/Stock.php/action=read').then(function (response) {
        state.stock = response.data.stock
      })
    },
    LOAD_STAFF: function (state) {
      axios.get('@/api/Staff.php?action=read').then(function (response) {
        state.staff = response.data.staff
      })
    },
    LOAD_SALES: function (state) {
      axios.get('@/api/Sales.php?action=read').then(function (response) {
        state.sales = response.data.sales
      })
    },
    STAFF_SALES: function (state, sp) {
      const formData = new FormData()
      formData.append('salesperson', sp)
      axios({
        method: 'get',
        url: '@/api/Sales.php?action=read',
        data: formData,
        config: { headers: { 'Content-Type': 'multipart/form-data' } }
      }).then(function (response) {
        state.displaySales = response.data.sales
      })
    },
    ITEM_SALES: function (state, item) {
      const formData = new FormData()
      formData.append('item', item)
      axios({ method: 'get', url: '@/api/Sales.php?action=read', data: formData, config: { headers: { 'Content-Type': 'multipart/form-data' } } }).then(function (response) {
        state.displaySales = response.dara.sales
      })
    },
    CATEGORIZE_STOCK: function (state) {
      var stock = state.stock
      var displayStock = []
      function categorize (arr) {
        var cat = arr[0].category
        var catArr = null
        for (var i = 0; i < arr.length; i++) {
          if (arr[i].category === cat) {
            catArr.push(arr[i])
            arr.splice(i, 1)
          }
        }
        return { category: cat, subcat: catArr }
      }
      function subCategorize (arr) {
        var subCat = arr[0].sub_category
        var subCatArr = null
        for (var i = 0; i < arr.length; i++) {
          if (arr[i].sub_category === subCat) {
            subCatArr.push(arr[i])
            arr.splice(i, 1)
          }
        }
        return { subcategory: subCat, list: subCatArr }
      }
      while (stock.length > 0) {
        var catIndex = 0
        var tempCatStock = categorize(stock)
        displayStock.push({ category: tempCatStock.category, subcat: [] })
        while (tempCatStock.subcat.length > 0) {
          var tempSubCatStock = subCategorize(tempCatStock.subcat)
          displayStock[catIndex].subcat.push({ subcategory: tempSubCatStock.subcategory, list: tempSubCatStock.list })
        }
        catIndex += 1
      }
      state.displayStock = displayStock
    },
    CATEGORIZE_EMPLOYEES: function (state) {
      var staff = state.staff
      var displayStaff = []
      function categorize (arr) {
        var off = arr[0].office
        var officeArr = null
        for (var i = 0; i < arr.length; i++) {
          if (arr[i].office === off) {
            officeArr.push(arr[i])
            arr.splice(i, 1)
          }
        }
        return { office: off, employees: officeArr }
      }
      while (staff.length > 0) {
        var tempOffice = categorize(staff)
        displayStaff.push({ office: tempOffice.office, employees: tempOffice.employees })
      }
      state.displayStaff = displayStaff
    },
    SIGN_IN: function (state, payload) {
      axios.post('@/api/Users.php?action=login', payload).then(function (response) {
        if (!response.data.error) {
          state.currentUser = response.data.user
          console.log(response.data.message)
        } else {
          state.error = response.data.message
        }
      })
    },
    RESET_DETAILS: function (state) {
      state.currentUser = null
    },
    UPDATE_USER: function (state, payload) {
      const formData = new FormData()
      formData.append('id', payload.id)
      formData.append('name', payload.name)
      formData.append('email', payload.email)
      formData.append('role', payload.role)
      formData.append('pword', payload.pword)
      axios({ method: 'post', url: '@/api/Users.php?action=update', data: formData }).then(function (response) { if (response.data.error) { state.error = response.data.message } else { state.message = response.data.message } })
    },
    UPDATE_STOCK: function (state, payload) {
      const formData = new FormData()
      formData.append('id', payload.id)
      formData.append('name', payload.name)
      formData.append('quantity', payload.quantity)
      formData.append('price', payload.price)
      formData.append('category', payload.category)
      formData.append('subcategory', payload.subcategory)
      formData.append('img', payload.img)
      axios({
        method: 'post',
        url: '@/api/Stock.php?action=update',
        data: formData,
        config: { headers: { 'Content-Type': 'multipart/form-data' } }
      }).catch(function (err) { state.error = err })
    },
    UPDATE_STAFF: function (state, payload) {
      const formData = new FormData()
      formData.append('id', payload.num)
      formData.append('idNo', payload.idNo)
      formData.append('fname', payload.fname)
      formData.append('oname', payload.oname)
      formData.append('address', payload.address)
      formData.append('phone', payload.phone)
      formData.append('office', payload.office)
      formData.append('img', payload.img)
      formData.append('status', payload.status)
      axios({
        method: 'post',
        url: '@/api/Staff.php?action=update',
        data: formData,
        config: { headers: { 'Content-Type': 'multipart/form-data' } }
      }).catch(function (err) { state.error = err })
    },
    ADD_SALE: function (state, payload) {
      const formData = new FormData()
      formData.append('item', payload.item)
      formData.append('quantity', payload.quantity)
      formData.append('salesperson', state.currentUser.idNo)
      formData.append('price', payload.tot)
      axios({
        method: 'post',
        url: '@/api/Sales.php?action=create',
        data: formData,
        config: { headers: { 'Content-Type': 'multipart/form-data' } }
      }).then(function (response) { if (response.data.error) { state.error = response.data.message } else { state.message = response.data.message } })
    },
    ADD_STAFF: function (state, payload) {
      const formData = new FormData()
      formData.append('idNo', payload.idNo)
      formData.append('fname', payload.fname)
      formData.append('oname', payload.oname)
      formData.append('address', payload.address)
      formData.append('phone', payload.phone)
      formData.append('office', payload.office)
      formData.append('img', payload.img)
      formData.append('status', payload.status)
      axios({
        method: 'post',
        url: '@/api/Staff.php?action=create',
        data: formData,
        config: { headers: { 'Content-Type': 'multipart/form-data' } }
      }).then(function (response) {
        if (response.data.error) {
          state.error = response.data.message
        } else {
          state.message = response.data.message
        }
      })
    },
    ADD_STOCK: function (state, payload) {
      const formData = new FormData()
      formData.append('name', payload.name)
      formData.append('quantity', payload.quantity)
      formData.append('price', payload.price)
      formData.append('category', payload.category)
      formData.append('subcategory', payload.subcategory)
      formData.append('img', payload.img)
      axios({ method: 'post', url: '@/api/Stock.php?action=create', data: formData, config: { headers: { 'Content-Type': 'multipart/form-data' } } }).then(function (response) { if (response.data.error) { state.error = response.data.message } else { state.message = response.data.message } })
    },
    ADD_USER: function (state, payload) {
      const formData = new FormData()
      formData.append('name', payload.name)
      formData.append('email', payload.email)
      formData.append('role', payload.role)
      formData.append('pword', payload.pword)
      axios({
        method: 'post',
        url: '@/api/Users.php?action=create',
        data: formData
      }).then(function (response) { if (response.data.error) { state.error = response.data.message } else { state.message = response.data.message } })
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
      context.commit('LOAD_STOCK')
      context.commit('CATEGORIZE_STOCK')
    },
    UPDATE_STAFF: function (context, payload) {
      context.commit('UPDATE_STAFF', payload)
      context.commit('LOAD_STADF')
      context.commit('CATEGORIZE_STAFF')
    },
    SET_USER: function (context, payload) {
      context.commit('SET_USER', payload)
    },
    ADD_SALE: function (context, payload) {
      context.commit('ADD_SALE', payload)
    },
    STAFF_SALES: function (context, sp) {
      context.commit('STAFF_SALES', sp)
    },
    ITEM_SALES: function (context, item) {
      context.commit('ITEM_SALES', item)
    }
  },

  modules: {
  }
})

export default store
