<template>
  <div :data-id="node_object.id" :data-order="node_object.order" :data-lvl="nesting_lvl">
    <div class="row justify-content-between category-row">
      <div v-if="nesting_lvl < 5 && node_object.children.length > 0" class="col-1 chevron-btn btn" @click="isChildrenVisible = !isChildrenVisible">
        <i class="fas" :class="[ isChildrenVisible ? 'fa-chevron-up' : 'fa-chevron-down' ]"></i>
      </div>
      <div v-else class="col-1"></div>
      <div class="p-2 col-9">
        <span v-if="!isEditMode" class="p-2 col-10">{{ node_object.name }}</span>
        <input v-else type="text"
               minlength="3" maxlength="30"
               class="form-control"
               :placeholder="$t('products.category_placeholder')"
               v-model="node_object.name"
               @keyup.enter="saveOrStore">
      </div>
      <div class="col-2 text-center">
        <i v-if="!isEditMode"
           class="fa fa-arrow-down fa-2x pr-2 pt-1 green"
           :class="{ 'pointer': nesting_lvl < 5, 'zeroOpacity': !(nesting_lvl < 5) }"
           @click="(nesting_lvl < 5) && addNewCategory(node_object.id)"></i>
        <i v-if="isEditMode" class="fa fa-check-square fa-2x pr-2 pt-2 green pointer  " @click="saveOrStore"></i>
        <i v-if="isEditMode" class="fa fa-window-close fa-2x pr-2 pt-2 yellow pointer  " @click="isEditMode = false"></i>
        <img v-if="!isEditMode"
             src="/icons/edit_color.svg"
             alt="Edit"
             @click="isEditMode = true"
             class="button__edit pr-2 pointer"/>
        <img v-if="!isEditMode"
             src="/icons/delete_red.svg"
             alt="Delete"
             @click="deleteOrCancel"
             class="button__delete "/>
      </div>
    </div>
    <draggable v-bind="dragOptions"
               tag="div" v-model="node_object.children" @change="update">
      <slot v-if="node_object.children && node_object.children.length > 0 && isChildrenVisible">
        <TreeNode v-for="(child, index) in node_object.children"
                  :key="index"
                  :parent_id="node_object.id"
                  :node_object="child" :nesting_lvl="nesting_lvl + 1"
                  class="indent"
                  @deleteCategory="$emit('deleteCategory', $event)"
                  @refresh="$emit('refresh')"
        />
      </slot>
    </draggable>
  </div>
</template>

<script>
import draggable from "vuedraggable";
export default {
  name: 'TreeNode',
  props: ["node_object", 'nesting_lvl' , 'parent_id'],
  components: { draggable },
  created() {
    if (this.node_object.id === null) {
      this.isEditMode = true;
    }
  },
  data() {
    return {
      current_node: {},
      isChildrenVisible: true,
      isEditMode: false,
    };
  },
  computed: {
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      };
    },
    // this.value when input = v-model
    // this.list  when input != v-model
    realValue() {
      return this.value ? this.value : this.list;
    }
  },
  methods: {
    updateParent() {
      if (typeof(this.node_object.children) !== 'array') {
        this.node_object.children = [];
      }
    },

    addNewCategory(parent_id) {
      this.node_object.children.push({
        id: null,
        parent_id: parent_id,
        name: '',
        children: [],
        order: null,
        firms_id: this.node_object.firms_id,
        can_create_new_category: true,
      });
    },

    async saveOrStore() {
      if (this.node_object.id === null) {
        await this.$store.dispatch('orders/createCategory', this.node_object);
        this.$emit('refresh');
      } else {
        await this.$store.dispatch('orders/updateCategory', this.node_object);
        this.$emit('refresh');
      }
      this.isEditMode = !this.isEditMode;
    },

    async deleteOrCancel() {
      if (!this.isEditMode && this.node_object.id !== null) {
        await this.$store.dispatch('orders/deleteCategory', this.node_object.id).then(
          res => {
            this.$toast.success(res.msg).goAway(3000);
            this.$emit('refresh');
          },
          err => {
            this.$toast.error(err.msg).goAway(3000);
          }
        )
      } else if (this.node_object.name.length < 3) {
        this.$emit('refresh');
      }
      this.isEditMode = false;
    },
    emitter (value) {
      this.$emit("input", value);
    },

    update(event){
      this.$emit( 'update', event)
    },

    handleChange() {
      console.log('changed !!!!!!!!!');
    },
    inputChanged(value) {
      this.activeNames = value;
    },

    onMove(evt) {
      console.log('here');
      console.log(evt.draggedContext.element.id)
    },
    getComponentData() {
      return {
        on: {
          change: this.handleChange,
          input: this.inputChanged
        },
        attrs:{
          wrap: true
        },
        props: {
          value: this.activeNames
        }
      };
    }
  }
}
</script>

<style lang="scss" scoped>

.plus-icon {
  margin-top: 5px;
  margin-right: 15px;
}
.indent .indent {
  margin-left: 30px;
}

.green{
  color: #26a647;
}

.yellow{
  color: #fdbc2c
}

.addition-btn {
  background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='3' ry='3' stroke='black' stroke-width='1' stroke-dasharray='6%2c 4' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
  border-radius: 3px;
  margin-bottom: 10px;
}
.chevron-btn {
  align-self: center;
}
.category-row {
  border: solid 1px;
  border-radius: 3px;
  box-shadow: 0 0 10px #88888888;
  margin-bottom: 10px;
}
.category-text {
  align-self: center;
}
.button__edit{
  margin-top: -15px;
  width: 40px;
}
.button__delete{
  margin-top: -15px;
  width: 25px;
}

.item-container {
  // max-width: 20rem;
  margin: 0;
}
.item {
  padding: 1rem;
  border: solid black 1px;
  background-color: #fefefe;
}
.item-sub {
  margin: 0 0 0 1rem;
}
</style>
