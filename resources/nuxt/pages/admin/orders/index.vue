<template>
 <div >
  <div class="container-fluid ">
    <div class="row">
      <span v-if="selected !== null">
        <span @click="getCategories" class="m-1 pointer"> {{ companies.find(e => e.id === selected).name }}
          <img src="/icons/right_arrow.svg"  class="arrow m-1 pointer"   />
       </span>
      </span>
      <span v-if="path.length > 0">
        <span v-for="(p , index) in path " :key="index">
          <span v-if="index > 0" class="m-1 pointer" @click="getCategoriesWithData(p.id)">  {{ p.name }}
              <img src="/icons/right_arrow.svg"  class="arrow m-1 pointer"   />
          </span>
        </span>
      </span>
    </div>
  </div>


    <div class="container-fluid container__row" :class="{'pt-4': selected === null } ">
    <div class="select__box" >
      <select name="company" class="form-control" style="width: auto;"  v-model="selected" @change="getCategories" >
        <option :value="null">{{ $t('bills.select_firm') }}</option>
        <option v-for="company in companies"
                :key="company.id"
                :selected="company.id === selected"
                :value="company.id"
        >
        {{ company.name }}
        </option>
      </select>
    </div>
    <div class="select__box pt-1" >
      <div v-if="selected && categories.length > 0" class="category-header">
          <v-menu-multi-level :nodes="categories" @click-item="selectCategory"
                              :icon-root-after="iconRootAfter"
                              :icon-root-before="iconRootBefore"
                              @set-path="setPath"
                              :open="openCategories" :path="path" :level="0"
                              label="name"></v-menu-multi-level>
      </div>
    </div>
    <div class="pt-3" v-if="selected ">
        <n-link :to="'/admin/orders/categories?firm='+ selected">
          <i class="fa fa-cog fa-2x" v-tooltip="{ content :'Settings'}"></i>
        </n-link>
    </div>
    <div class="container-fluid container__button m-1">
      <div class="p-1">
        <input type="text" class="form-control" v-model="search_name" placeholder="Search by name" @input="searchByName">
      </div>
      <div class="p-1">
      <button class="btn btn-outline-success" @click="getUrlToCreate">{{ $t("products.new_product")}}</button>
      </div>
    </div>
  </div>

  <div class="table-responsive tableFixHead">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col" class="p-1 text-white">{{ $t("products.product_name") }}</th>
          <th scope="col" class="p-1 text-white">{{ $t("company.description") }}</th>
          <th scope="col" class="p-1 text-white text-center">{{ $t("bills.select_firm")}}</th>
          <th scope="col" class="p-1 text-white text-center">{{ $t('bills.unit')}}</th>
          <th scope="col" class="p-1 text-white text-center">{{ $t("common.unit_price") }}</th>
          <th scope="col" class="p-1 text-white text-center">{{ $t("common.actions") }}</th>
        </tr>
      </thead>

      <tbody >
        <tr v-for="(order, idx) in AllOrdersList.data" :key="idx">
          <td :data-label="$t('products.product_name')"><n-link :to="'/admin/orders/preview/'+order.id+'/'">{{ order.name }}</n-link></td>
          <td :data-label="$t('company.description')"><span v-html="order.desc"></span></td>
          <td :data-label="$t('bills.select_firm')" class="text-center">{{ order.firms ? order.firms.name : '--'   }}</td>
          <td :data-label="$t('bills.unit')" class="text-center">{{ order.unit }}</td>
          <td :data-label="$t('products.unit_price')" class="text-center">{{ order.unit_price }}</td>
          <td :data-label="$t('common.actions')" class="text-center">
            <span class="float-right">
              <div class="icons__box">
                <n-link :to="'/admin/orders/'+order.id+'/'">
              <img
                src="/icons/edit_color.svg"
                alt="Edit."
                class="button__edit pointer"/>
                  </n-link>
              <img
                src="/icons/delete_red.svg"
                alt="Delete."
                @click="deleteOrder(order.id)"
                class="button__delete" />
              </div>
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>


  <div class="d-flex justify-content-center">
    <NavigationArrowsCircle
      :total="AllOrdersList.total"
      :current="AllOrdersList.current_page"
      :from="AllOrdersList.current_page * AllOrdersList.per_page - AllOrdersList.per_page+1"
      :perPage="AllOrdersList.per_page"
      :key="AllOrdersList.current_page"
      @goToPage="changePaginate($event)"/>
  </div>

</div>
</template>
<script>
import NavigationArrowsCircle from "../../../components/common/NavigationArrowsCircle";
import isElementInteractible from "@/mixins/isElementInteractible";
import VMenuMultiLevel from '@/components/elements/multi-level-menu/v-menu-multi-level'
import 'v-menu-multi-level/dist/v-menu-multi-level.css'

export default {
  name: "orders",
  layout: "admin",
  middleware: ['auth'],
  components: {  VMenuMultiLevel , NavigationArrowsCircle},
  mixins: [isElementInteractible],
  data() {
    return {
      isConfirm: false,
      active: 0,
      currentAction: null,
      action_client: {},
      selected: null, //for firm
      unit_selected: null,
      search_address: "",
      search_broker: "",
      search_email: "",
      search_status: "",
      isCategoriesModalVisible: false,
      categories:[],
      category: {},
      company: {},
      search_name:"",
      name_category :[],
      name_current_category :"Please select",
      openCategories : true,
      path:[],
      page:1,

      iconRootBefore : {
        opened: { icon: '', style: '' },
        closed: { icon: '', style: '' }
      },
      iconRootAfter : {
        opened: {
          icon: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
          style: {
            fontSize: '12px',
            top: '0em',
            position: 'relative',
            marginLeft: '.8em',
            webkitTransform: 'rotate(270deg)',
            transform: 'rotate(270deg)',
            transition: 'transform 0.3s, -webkit-transform 0.3s'
          }
        },
        closed: {
          icon: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
          style: {
            fontSize: '12px',
            top: '0em',
            position: 'relative',
            marginLeft: '.8em',
            webkitTransform: 'rotate(90deg)',
            transform: 'rotate(90deg)',
            transition: 'transform 0.3s, -webkit-transform 0.3s'
          }
        }
      },
    }
  },
  async asyncData({store}) {
    await store.dispatch('orders/fetchOrdersList');
    await store.dispatch('bills/fetchFirmsList');
  },

  computed: {
    AllOrdersList() {
      return JSON.parse(JSON.stringify(this.$store.state.orders.orders));
    },
    companies() {
      return this.$store.state.bills.firmsList;
    },
    units(){
      return JSON.parse(JSON.stringify(this.$store.state.bills.unit));
    },
  },

  methods: {
    deleteOrder(el){
      if(el !== null) this.$store.dispatch('orders/deleteProduct', el);
    },
    searchByName(){
        this.$store.dispatch('orders/fetchOrdersList', {name: this.search_name});
    },
    getUrlToCreate(){
      var url = '/admin/orders/create';
      if(this.selected !== null){
        url += '?firm='+ this.selected  + "&category_name=" + this.name_category ;
      }
      return this.$router.push(url);
    },

    async getCategories(){
      this.path = [];
      this.categories = [];
      if(this.selected !== null) {
        const res = await this.$store.dispatch('orders/fetchCategoriesList', this.selected);
        this.name_current_category = 'Please select';
        this.openCategories = false;
        if(res.length > 0) {
          this.openCategories = true;
          this.categories = [{
            name: this.name_current_category,
            children: res
          }];
        }
        if(this.category.hasOwnProperty('id')){
          this.$store.dispatch('orders/fetchOrdersList' , {firm : this.selected  , category : this.category.id  });
        }else this.$store.dispatch('orders/fetchOrdersList' , {firm : this.selected });
      }else {
        //this.categories = [];
        await this.$store.dispatch('orders/fetchOrdersList');
      }
    },

    async getCategoriesWithData(id){
      this.categories = [];
      if(this.selected !== null) {
        const res = await this.$store.dispatch('orders/fetchCategoriesList', this.selected);
        this.name_current_category = 'Please select';
        this.openCategories = false;
        if(res.length > 0) {
          this.openCategories = true;
          this.categories = [{
            name: this.name_current_category,
            children: res
          }];
        }
          this.$store.dispatch('orders/fetchOrdersList' , {firm : this.selected  , category : id  });
      }else {
        //this.categories = [];
        await this.$store.dispatch('orders/fetchOrdersList');
      }
    },

    setPath(event ,level , item ){
      this.path[level] = { 'name' : item.name, 'id' : item.id };
      this.path = this.path.slice( 0 ,level+1 );
    },

    selectCategory(event, item){
      this.name_category = this.path;
      this.name_current_category = this.path.slice(-1).pop().name;
      this.categories[0].name = this.name_current_category;
      this.$store.dispatch('orders/fetchOrdersList' , {category : item.id ,  firm : this.selected });
    },
    createNewCategory(){
      if(this.selected) {
        this.category = {name: "", desc: '', 'firms_id': this.selected};
        this.isCategoriesModalVisible = true;
      }
    },
     async changePaginate(page){
        this.page = page;
        this.$store.dispatch('orders/fetchOrdersList', {page: this.page});
     }
  }
}

</script>

<style lang="scss" scoped>
@import './assets/scss/_var.scss';
.arrow{
  height: 20px;
}

.firm-header{
  font-family: 'Montserrat', sans-serif;
  font-size: 25px;
  white-space: nowrap;
  text-decoration: underline;
}
.category-header{
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  white-space: nowrap;
}
table {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  padding: 0.3rem;
  width: 100%;
}
 .button__delete{
    width:25px;
  }
  .icons__box{
    display: flex;
  }
  table > thead > tr > th{
    vertical-align: middle;
    white-space: nowrap;
  }
  .button__edit{
    padding-right: 10px;
    width: 40px;
  }
  .container__button{
    display: flex;
    justify-content: flex-end;
    min-height: 100%;
    padding-bottom: 0px;
  }
  .btn-outline-success{
    @extend %all-buttons;
    position: relative;
    margin-bottom: 0px;
    margin-top: auto;
    max-height: 40px;
    min-height: 40px;
    @media screen and (max-width: 749px){
      margin-right: 10px;
    }
  }
  .container__row{
    display: flex;
    justify-content: center;
    margin: 15px 0 15px 0;
    @media screen and (max-width: 749px){
      flex-direction: column;
    }
  }
  .select__box{
    display: flex;
    flex-direction: column;
    padding-right: 20px;
    justify-content: flex-end;
    padding-bottom: 5px;
  }


@media only screen and (max-width: 768px) {
  .table thead {
    display: none;
  }
  .table tr {
    display: block;
  }
  .table td {
    display: flex;
    justify-content: space-between;
  }
  table > tbody > tr {
    border-bottom: 2px solid cornflowerblue;
  }
  .table td::before {
    content: attr(data-label);
    font-weight: bold;
    margin-right: 20px;
    text-align: left;
  }
}
</style>
