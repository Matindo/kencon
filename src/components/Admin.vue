<template>
  <b-container fluid>
    <b-row align-v="stretch">
      <b-col class="w-100">
        <b-tabs justified>
          <b-tab title="Stock">
            <b-card no-body>
              <b-tabs vertical card pills>
                <b-tab v-for="(cat, index) in stock" :key="index" :title="cat.category">
                  <b-card-text>
                    <b-tabs content-class="mt-3" justified>
                      <b-tab v-for="(sub, index1) in cat.subcat " :key="index1" :title="sub.subcategory">
                          <b-card v-for="(item, index2) in sub.list" :key="index2" no-body class="accordion" id="accordion1" role="tablist">
                            <b-card-header header-tag="header" class="p-1" role="tab">
                              <b-row class="w-100">
                                <b-col class="w-40">
                                  <img :src="require(`../assets/${item.img}.png`)" style="height: 8rem; width: 8rem;">
                                </b-col>
                                <b-col class="w-60 accordion" id="accordion2" role="tablist">
                                  <h4>{{ item.name }}</h4>
                                  <p>In Stock: {{ item.quantity }}</p>
                                  <b-button block v-b-toggle="'collapse-add' + index2" variant="success">Add To Stock</b-button>
                                  <b-collapse :id="'collapse-add' + index2" accordion="accordion2" role="tabpanel">
                                    <b-form inline>
                                      <label class="sr-only" for="addition">Quantity:</label>
                                      <b-input v-model="stockInfluence" id="addition" placeholder="e.g 100" type="number"></b-input>
                                      <b-button :class="visibleA ? null : 'collapsed'" :aria-expanded="visibleA ? 'true' : 'false'" :aria-controls="'collapse-add' + index2" @click="item.quantity += Number(stockInfluence); stockInfluence = 0">Add</b-button>
                                    </b-form>
                                  </b-collapse>
                                  <b-button block v-b-toggle="'collapse-remove' + index2" variant="danger">Remove from Stock</b-button>
                                  <b-collapse :id="'collapse-remove' + index2" accordion="accordion2" role="tabpanel">
                                    <b-form inline>
                                      <label class="sr-only" for="removal">Quantity:</label>
                                      <b-input v-model="stockInfluence" id="removal" placeholder="e.g 5" type="number"></b-input>
                                      <b-form-text v-show="stockInfluence > item.quantity">Your deduction is more than the available stock</b-form-text>
                                      <b-button v-if="stockInfluence <= item.quantity" :class="visibleB ? null : 'collapsed'" :aria-expanded="visibleB ? 'true' : 'false'" :aria-controls="'collapse-remove' + index2" @click="item.quantity -= stockInfluence; stockInfluence = 0">Remove</b-button>
                                    </b-form>
                                  </b-collapse>
                                  <b-button block v-b-toggle="'collapse-trend' + index2" variant="info">View Stock Trends</b-button>
                                </b-col>
                              </b-row>
                            </b-card-header>
                            <b-collapse :id="'collapse-trend' + index2" accordion="accordion1" role="tabpanel">
                              <h5>{{item.name}}</h5>
                            </b-collapse>
                          </b-card>
                      </b-tab>
                    </b-tabs>
                  </b-card-text>
                </b-tab>
              </b-tabs>
            </b-card>
          </b-tab>
          <b-tab title="Sales">
            <b-card no-body>
              <b-tabs pills vertical card>
                <b-tab v-for="(office, index) in staff" :key="index" :title="office.office">
                  <b-card-text>
                    <h3>{{ office.name }}</h3>
                      <b-card v-for="salesperson in office.employees" :key="salesperson.idNo" class="accordion" id="accordionMain" role="tablist">
                        <b-card-header header-class="header">
                          <b-row>
                            <b-col class="w-40">
                              <img :src="require(`../assets/avatars/${salesperson.img}.png`)" style="height: 8rem; width: 8rem;">
                            </b-col>
                            <b-col class="w-60">
                              <h4>{{ salesperson.fullName }}</h4>
                              <p>ID:{{ salesperson.idNo }}</p>
                              <p>Work Status: {{ salesperson.status }}</p>
                              <b-button block v-b-toggle="'collapse-sp' + salesperson.idNo" variant="success">Edit Details</b-button>
                              <b-collapse :id="'collapse-sp' + salesperson.idNo">
                                <b-form inline>
                                  <label class="sr-only" for="1name">First Name:</label><b-input v-model="salesperson.fname" id="1name" placeholder="e.g Alex" type="text"></b-input>
                                  <label class="sr-only" for="2name">Other Names:</label><b-input v-model="salesperson.oname" id="2name" placeholder="e.g Doe Tomkins" type="text"></b-input>
                                  <label class="sr-only" for="idn">ID:</label><b-input v-model="salesperson.idNo" id="idn" placeholder="e.g 12345678" type="number"></b-input>
                                  <label class="sr-only" for="mail">E-mail:</label><b-input v-model="salesperson.email" id="mail" placeholder="e.g alextom@mail.com" type="e-mail"></b-input>
                                  <label class="sr-only" for="phone">Mobile No:</label><b-input v-model="salesperson.phone" id="phone" placeholder="e.g 254712345678" type="tel"></b-input>
                                  <b-button :class="visible? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" aria-controls="collapse-sp" @click="editSalesperson(index)">Edit</b-button>
                                </b-form>
                              </b-collapse>
                              <b-button block v-b-toggle="'accordion' + salesperson.idNo" variant="info" @click="fetchRecords(salesperson.num)">View Sales Records</b-button>
                            </b-col>
                          </b-row>
                        </b-card-header>
                        <b-collapse :id="'accordion' + salesperson.idNo" accordion="accordionMain" role="tabpanel">
                          <h5>Employee No: {{ salesperson.num }}</h5>
                        </b-collapse>
                      </b-card>
                  </b-card-text>
                </b-tab>
              </b-tabs>
            </b-card>
          </b-tab>
        </b-tabs>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
export default {
  data: function () {
    return {
      staff: [
        {
          office: 'Main',
          employees: [
            {
              idNo: '12345671', fullName: 'Cashier One', fname: 'Cashier', oname: 'One', phone: '254712345678', num: '001', img: 'avatar_1', status: 'On Duty'
            },
            {
              idNo: '19845671', fullName: 'Cashier Two', fname: 'Cashier', oname: 'Two', phone: '254798765432', num: '002', img: 'avatar_2', status: 'On Duty'
            }
          ]
        },
        {
          office: 'Thika',
          employees: [
            {
              idNo: '19823745', fullName: 'Cashier Three', fname: 'Cashier', oname: 'Three', phone: '254776123984', num: '003', img: 'avatar_3', status: 'On Vacation'
            },
            {
              idNo: '32456123', fullName: 'Cashier Four', fname: 'Cashier', oname: 'Four', phone: '254777542365', num: '004', img: 'avatar_4', status: 'On Duty'
            }
          ]
        }
      ],
      stock: [
        {
          category: 'Gutters',
          subcat: [{ subcategory: 'Plastic', list: [{ name: 'Red Gutter', img: 'redplasticgutter', price: 3000, quantity: 100 }, { name: 'Green Gutter', img: 'greenplasticgutter', price: 3000, quantity: 70 }] }, { subcategory: 'Metallic', list: [{ name: 'Silver Gutter', img: 'silvermetalgutter', price: 1800, quantity: 50 }, { name: 'Black Gutter', img: 'blackmetalgutter', price: 1600, quantity: 65 }] }]
        }, {
          category: 'Decra',
          subcat: [{ subcategory: 'Shingles', list: [{ name: 'Black Shingles', img: 'blackshingles', price: 500, quantity: 80 }, { name: 'Green Shingles', img: 'greenshingles', price: 500, quantity: 55 }, { name: 'Coffee Shingles', img: 'coffeeshingles', price: 500, quantity: 77 }] },
            {
              subcategory: 'Classic',
              list: [
                { name: 'Black Decra', img: 'blackclassicdecra', price: 600, quantity: 50 },
                { name: 'Green Decra', img: 'greenclassicdecra', price: 600, quantity: 90 },
                { name: 'Red Decra', img: 'redclassicdecra', price: 600, quantity: 88 }]
            }]
        },
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
      ],
      stockInfluence: 0
    }
  }
}
</script>
