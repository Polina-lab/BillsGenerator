<template>
  <div>
    <div class="container">
      <div class="row justify-content-between">
        <div class="arrow__container">
          <nuxt-link to="/admin/orders" prefetch class="pointer breadcrumbs__style">
            <i class="fas fa-arrow-left"></i>
            <h6>{{ $t('products.product_list') }} / <strong>{{ $t('products.categories') }}</strong></h6>
          </nuxt-link>
        </div>
      </div>
      <div id="categories">
        <div class="row mb-3">
          <label for="company">Select firm</label>
          <select id="company" class="form-control " v-model="selected_firm" @change="getCategories">
            <option :value="null">Select firm</option>
            <option v-for="company in firmsList"
                    :key="company.id"
                    :selected_firm="company.id === selected_firm"
                    :value="company.id"
            >
              {{ company.name }}
            </option>
          </select>
        </div>
        <div v-if="selected_firm === null">
          <b>Please select a firm </b>
        </div>
        <draggable v-bind="dragOptions"
                   tag="div" v-model="tree"
                   @change="update" @end="test"
        >
          <TreeNode v-for="(base_node, index) in tree" :key="index"
                    :node_object="base_node"
                    :nesting_lvl="nesting_lvl"
                    :parent_id="null"
                    class="indent"
                    @deleteCategory="deleteCategory($event)"
                    @refresh="getCategories"/>
          <div v-if="selected_firm" class="row">
            <div class="col-12 addition-btn text-center select__box-inline" @click="addNewCategory()">
              <i class="fa fa-plus plus-icon"></i>
              {{ $t("products.add_new_category") }}
            </div>
          </div>
        </draggable>
      </div>
    </div>
  </div>
</template>

<script>
import TreeNode from '../../../../components/elements/TreeNode.vue';
import draggable from "vuedraggable";

export default {
  name: 'categories',
  layout: "admin",
  components: { TreeNode, draggable },
  middleware: ['auth'],
  data() {
    return {
      nesting_lvl: 1,
      selected_firm: "",
      extra_data : null,
      tree :[]
    }
  },
  async asyncData({store , route}) {
    await store.dispatch('bills/fetchFirmsList');
    var firm = null
    if(route.query.firm ){
       firm  = route.query.firm
    }

    return { selected_firm : firm  };
  },
  beforeMount(){
    if (this.selected_firm !== null) {
      this.getCategories();
    }
  },

  computed: {
    firmsList() {
      return this.$store.state.bills.firmsList;
    },

    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
        updateWhenDestroy : false
      };
    },
    // this.value when input = v-model
    // this.list  when input != v-model
    realValue() {
      return this.value ? this.value : this.list;
    },

  },

  methods: {
    async getCategories() {
      if (this.selected_firm !== null) {
        this.$router.push({ path: this.$route.path, query: { ...this.$route.query, firm: this.selected_firm } })
        this.tree = await this.$store.dispatch('orders/fetchCategoriesList', this.selected_firm);
      } else {
        this.tree = [];
      }
    },

    addNewCategory() {
      this.tree.push({
        id: null,
        parent_id: null,
        name: '',
        children: [],
        desc: '',
        order: null,
        firms_id: this.selected_firm,
      });
    },


    deleteCategory(category_id) {
      this.$store.dispatch('orders/deleteCategory', category_id).then(
        resp => {
          this.$toast.success(resp.msg).goAway(3000);
          this.getCategories();
        }
      ).catch(err => {
        this.$toast.error(err.msg).goAway(3000);
      })
    },
    emitter(value) {
      this.$emit("input", value);
    },

    test(event){
      if( typeof event.to.parentNode === 'undefined')
        this.this.extra_data = null;
      else this.extra_data = event.to.parentNode.dataset.id
    },

    timeout(ms) { //pass a time in milliseconds to this function
      return new Promise(resolve => setTimeout(resolve, ms));
    },

    async update($event  ) {
      await this.timeout(500);//👀
      var data = {};
      if($event.added){
        data = $event.added;
        data.status = "added";
      }
      else if($event.removed){
        data = $event.removed;
        data.status = "removed";
      }
      else if($event.moved){
        data = $event.moved;
        data.status = "moved";
      }
       data.parent = this.extra_data;
       const res  =  await  this.$store.dispatch('orders/rebuildCategoryTree', data );

       const tree_data =  await  this.$store.dispatch('orders/fetchCategoriesList', data.element.firms_id);
      this.tree = tree_data
    },
  }
}
</script>

<style lang="scss" scoped>
@import './assets/scss/_var.scss';

.select__box{
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    padding-bottom: 5px;
}

.breadcrumbs__style{
  display: flex;
  justify-content: center;
  align-items: center;
  color: $secondary-color;
  text-decoration: none;
   margin: 20px 0 30px 10px;
}
.breadcrumbs__style h6{
    position: relative;
    margin-top: auto;
    margin-bottom: auto;
    text-align: center;
}
.breadcrumbs__style h6 strong{
  color: #000000;
}
.indent .indent {
  margin-left: 30px;
}
.select__box-inline {
  display: flex;
  flex-direction: row;
  padding-right: 20px;
  justify-content: center;
  padding-top: 5px;
  padding-bottom: 5px;
}
.plus-icon {
  margin-top: 5px;
  margin-right: 15px;
}
.addition-btn {
  background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='3' ry='3' stroke='black' stroke-width='1' stroke-dasharray='6%2c 4' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
  border-radius: 3px;
}
.save-btn {
  width: 100%
}
</style>
