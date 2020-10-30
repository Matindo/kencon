<template>
  <b-container fluid>
    <b-row align-v="stretch">
      <b-col class="w-100">
        <b-tabs>
          <b-tab :title="Stock">
            <b-card no-body>
              <b-tabs vertical card pills>
                <b-tab v-for="(cat, index) in stock" :key="index" :title="cat.category">
                  <b-card-text>
                    <b-tabs content-class="mt-3" justified>
                      <b-tab v-for="(sub, index) in cat.subcat " :key="index" :title="sub.subcategory">
                        <div class="accordion" id="accordion1" role="tablist">
                          <b-card v-for="(item, index) in sub.list" :key="index" no-body class="mb-1">
                            <b-card-header header-tag="header" class="p-1" role="tab">
                              <b-row class="w-100">
                                <b-col class="w-40">
                                  <img :src="item.img" style="height: 8rem; width: 8rem;">
                                </b-col>
                                <b-col class="w-60 accordion" id="accordion2">
                                  <h4>{{ item.name }}</h4>
                                  <p>In Stock: {{ item.quantity }}</p>
                                  <b-button block v-b-toggle.collapse-add variant="success">Add To Stock</b-button>
                                  <b-collapse id="collapse-add" accordion="accordion2">
                                    <b-form inline>
                                      <label class="sr-only" for="addition">Quantity:</label>
                                      <b-input v-model="stockInfluence" id="addition" placeholder="e.g 100" type="number"></b-input>
                                      <b-button :class="visibleA ? null : 'collapsed'" :aria-expanded="visibleA ? 'true' : 'false'" aria-controls="collapse-add" @click="addStock()">Add</b-button>
                                    </b-form>
                                  </b-collapse>
                                  <b-button block v-b-toggle.collapse-remove variant="danger">Remove from Stock</b-button>
                                  <b-collapse id="collapse-remove" accordion="accordion2" role="tabpanel">
                                    <b-form inline>
                                      <label class="sr-only" for="removal">Quantity:</label>
                                      <b-input v-model="stockInfluence" id="removal" placeholder="e.g 5" type="number"></b-input>
                                      <b-button :class="visibleB ? null : 'collapsed'" :aria-expanded="visibleB ? 'true' : 'false'" aria-controls="collapse-remove" @click="removeStock()">Remove</b-button>
                                    </b-form>
                                  </b-collapse>
                                  <b-button block :v-b-toggle="'accordion-' + index" variant="info">View Stock Trends</b-button>
                                </b-col>
                              </b-row>
                            </b-card-header>
                            <b-collapse id="'accordion-' + index" accordion="accordion1" role="tabpanel">
                              <b-card-body>
                                <b-card-text>{{ text }}</b-card-text>
                              </b-card-body>
                            </b-collapse>
                          </b-card>
                        </div>
                      </b-tab>
                    </b-tabs>
                  </b-card-text>
                </b-tab>
              </b-tabs>
            </b-card>
          </b-tab>
          <b-tab :title="Sales">
            <b-card no-body>
              <b-tabs pills vertical card>
                <b-tab v-for="(office, index) in staff" :key="index" :title="office.name">
                  <b-card-text>
                    <h3>{{ office.name }}</h3>
                    <div class="accordion" id="mainAccordion">
                      <b-card v-for="salesperson in office.list" :key="salesperson.idNo">
                        <b-card-header header-class="header">
                          <b-row>
                            <b-col class="w-40">
                              <img :src="salesperson.img" style="height: 8rem; width: 8rem;">
                            </b-col>
                            <b-col class="w-60">
                              <h4>{{ salesperson.name }}</h4>
                              <p>ID:{{ salesperson.idNo }}</p>
                              <p>Work Status: {{ salesperson.status }}</p>
                              <b-button block v-b-toggle.collapse-sp variant="success">Edit Details</b-button>
                              <b-collapse id="collapse-sp">
                                <b-form inline>
                                  <label class="sr-only" for="1name">First Name:</label><b-input v-model="salesperson.fname" id="1name" placeholder="e.g Alex" type="text"></b-input>
                                  <label class="sr-only" for="2name">Other Names:</label><b-input v-model="salesperson.oname" id="2name" placeholder="e.g Doe Tomkins" type="text"></b-input>
                                  <label class="sr-only" for="idn">ID:</label><b-input v-model="salesperson.idNo" id="idn" placeholder="e.g 12345678" type="number"></b-input>
                                  <label class="sr-only" for="mail">E-mail:</label><b-input v-model="salesperson.email" id="mail" placeholder="e.g alextom@mail.com" type="e-mail"></b-input>
                                  <label class="sr-only" for="phone">Mobile No:</label><b-input v-model="salesperson.phone" id="phone" placeholder="e.g 254712345678" type="tel"></b-input>
                                  <b-button :class="visible ? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" aria-controls="collapse-sp" @click="editSalesperson(index)">Edit</b-button>
                                </b-form>
                              </b-collapse>
                              <b-button block v-b-toggle.collapse-sales variant="info" @click="fetchRecords(salesperson.num)">View Sales Records</b-button>
                              <b-collapse id="collapse-sales">
                                <h5>Employee No: {{ salesperson.num }}</h5>
                              </b-collapse>
                            </b-col>
                          </b-row>
                        </b-card-header>
                      </b-card>
                    </div>
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
</script>
