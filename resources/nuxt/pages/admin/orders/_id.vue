<template>
  <div>
    <div class="container">
      <div class="arrow__container">
        <n-link to="/admin/orders/" prefetch class="pointer delete__styles">
          <i class="fas fa-arrow-left"></i>
          <h6 id="href-title">{{ $t('products.product_list') }} /
            <strong v-if="$route.path !== null && !($route.path.includes('admin/orders/create'))">{{ $t('products.edit_product') }}</strong>
            <strong v-else>{{ $t('products.create_product') }}</strong>
          </h6>
        </n-link>
      </div>
      <div class="container card__container">

        <div class="row">
          <div class="col-12">
            <label for="explanation">{{ $t("products.explanation") }}<sup class="red">*</sup></label>
            <input type="text" class="form-control" id="explanation" name="explanation" v-model="product.name"/>
          </div>
          <div class="col-12">
            <label for="text-product">{{ $t("products.additional_info") }}</label>
            <vue-editor style="width:100%;"
                        :editor-toolbar="customToolbar"
                        id="text-product"
                        v-model="product.desc">
            </vue-editor>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-sm-6 col-lg-3">
            <label for="price_unit">{{ $t("common.unit_price") }}<sup class="red">*</sup></label>
            <input type="number" id="price_unit" class="form-control width__control" v-model="product.unit_price">
          </div>
          <div class="col-12 col-sm-6 col-lg-3" >
            <label for="name_unit">{{ $t("bills.unit") }}<sup class="red">*</sup></label>
            <select name="name_unit" class="p-1 form-control width__control" v-model="product.unit" >
              <option v-for="unit in unitsList"
                      name="name_unit"
                      id="name_unit"
                      :key="unit.id"
                      :selected="unit.name === product.unit"
                      :value="unit.name">
                {{ unit.name }}
              </option>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <label for="firm_unit">{{ $t('User.company') }}<sup class="red">*</sup></label>
            <select name="companies" class="p-1 form-control width__control"  @change="selectFirm" v-model="product.firms_id">
              <option v-for="firm in firmsList"
                      id="firm_unit"
                      name="firm_unit"
                      :key="firm.id"
                      :value="firm.id">
                {{ firm.name }}
              </option>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-lg-3" >
            <div class="row" v-if="product.firms_id && openCategories">

              <div class="col-9" v-if="categories && categories.length > 0" >
                <label>{{ $t('product.categories')}}</label>



                <v-menu-multi-level :nodes="categories"
                                    @set-path="setPath"
                                    :open="openCategories" :path="path" :level="0"
                                    @click-item="selectCategory"  label="name"></v-menu-multi-level>
              </div>
              <div class="col-9" v-else >
                <label>{{ $t('product.categories')}}</label>
                <span @click="selectFirm" >{{ selected_category }}</span>
              </div>
              <div class="image__box col-1 pt-4">
                <n-link :to="'/admin/orders/categories?firm='+ product.firms_id">
                  <i class="fa fa-cog fa-2x" v-tooltip="{ content :'Settings cotegories'}"></i>
                </n-link>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row p-2">
        <div class="col-12 btn__box">
          <button class="btn btn-outline-secondary m20" @click="updateProduct">{{ $t("common.update") }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>


import VMenuMultiLevel from '@/components/elements/multi-level-menu/v-menu-multi-level'
import 'v-menu-multi-level/dist/v-menu-multi-level.css'

let VueEditor;
if (process.client) {
  VueEditor = require('vue2-editor').VueEditor;
}

export default {
  name: "OrdersEdit",
  layout : 'admin',
  components: { VueEditor , VMenuMultiLevel },
  middleware:[ 'auth' ],
  data(){
    return{
      showModal: false,
      path:[],
      new_category:{},
      categories:[],
      selected: null,
      unit_selected: null,
      customToolbar: [
        ["bold", "italic", "underline"],
        [{list: "ordered"}, {list: "bullet"}],
      ],
      name_category: "Please select",
    }
  },
  async asyncData({store , params }) {
     await store.dispatch('bills/fetchFirmsList');
     const product = await store.dispatch('orders/fetchOrder' , params.id);
     return {product : product , openCategories : product.categories && product.categories.name ?  true  : false ,  selected_category : product.categories ?  product.categories.name : "Please select"};
  },


  computed: {
    firmsList() {
      return this.$store.state.bills.firmsList;
    },
    unitsList(){
      return JSON.parse(JSON.stringify(this.$store.state.bills.unit));
    },
  },
  methods:{
    linkOnList(){
      return this.$router.back();
    },
    async selectFirm(){
      const res = await  this.$store.dispatch('orders/fetchCategoriesList', this.product.firms_id);
      if(res.length > 0) {
        this.openCategories = true;
        this.categories = [{
          name: this.name_category,
          children: res
        }];
      }else {
        this.openCategories = false;
        this.selected_category = null;
        this.categories = [];
      }
    },
    selectCategory(event , item){
      this.selected_category = item.name;
      this.product.categories_id  = item.id
      //this.name_current_category = this.path.slice(-1).pop();
      this.categories[0].name = this.selected_category
      this.openCategories = true;
    },
    reloadCategoryList(name){
      if(name){
        this.selected_category = name
        this.openCategories = false;
      }
      this.selectFirm();
    },
    setPath(event ,level , item ){
      this.path[level] = item.name;
      this.path = this.path.slice( 0 ,level+1 );
    },


    addNewCategory(){
      this.showModal = true;
      this.new_category = {
        id : null,
        name : '' ,
        parent_id: this.product.categories_id ,
        firms_id: this.product.firms_id
      }
    },
    async updateProduct() {
      //TODO: need data validation
      await this.$store.dispatch('orders/updateProduct', this.product).then(
        resp => {
          //this.$toast.success(resp.msg).goAway(3000);
          this.$router.push('/admin/orders');
        }
      ).catch(err => {
        //TODO: вывод ошибок .. если роут не сработает
        this.$toast.error(err.msg).goAway(3000);
      })
    }
  }
}

</script>
<style lang="scss" scoped>
@import './assets/scss/_var.scss';
.card__container{
  margin: 0px 60px;
  max-width: 87%;
}
.btn__box{
  display: flex;
  justify-content: center;
  margin: 20px 0px;
}
.btn-outline-secondary{
  @extend %all-buttons;
  max-height: 40px;
  min-height: 40px;
  background-color: $logo-green;
  border-color:  $logo-green;
  padding: 0% 5%;
  color: #ffffff;
}
label{
  white-space: nowrap;
}
.width__control{
  max-width: 220px;
  @media screen and (max-width:576px){
    min-width: 100%;
  }
}
.image__box{
  position: relative;
  margin-bottom: 2px;
  margin-top: auto;
}
.fa-arrow-left{
  font-size: 25px;
  letter-spacing: 10px;
  @media screen and (max-width: 768px){
    font-size: 36px;
  }
}
.delete__styles{
  display: flex;
  justify-content: left;
  align-items: center;
  color: $secondary-color;
  text-decoration: none;
  margin: 20px 0px 30px -20px;
}
#href-title{
  position: relative;
  margin-top: auto;
  margin-bottom: auto;
  text-align: center;
}
.delete__styles h6 strong{
  color: #000000;
}
.delete__styles strong{
  color: #000000;
}
</style>
