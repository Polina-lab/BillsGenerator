<template>
  <div>
    <table v-for="(vat, idx) in vatData" :key="idx">
      <thead>
        <tr>
          <th>{{ $t('bills.KM') }}</th>
          <th>{{ $t('common.default') }}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="input-group">
              <input type="number" class="form-control" id="vat" :value="vat.vat"
                     @change="updateVat($event.target.value, idx)"
                     :disabled="!addNew"
                     :ref="`vat_vat_field_${idx}`" min="0" max="100">
              <div class="input-group-prepend">
                <span class="input-group-text">%</span>
             </div>
           </div>
         </td>
          <td class="text-center">
            <input type="radio" class="mt-2 ml-2" name="vat_id" :id="`radio_vat_${idx}`"
                   :checked="vat.default" @change="changeDefaultVat(idx)">
          </td>
          <td>
            <div v-if="idx > 0">
              <i class="fa fa-trash p-2 red pointer" :title="$t('common.delete')" @click="deleteVat(idx)"></i>
            </div>
            <div v-if="showAdd && addNew && idx === 0 ">
              <i class="fa fa-plus p-2 btn-action pointer" :title="$t('common.add_vat')" @click="addNewVat()"></i>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "addVatComponent",
  props:['vatData', 'addNew', 'from', 'showAdd'],
  beforeCreated(){
    // хз почему не работает .. пока так - если не работает + не будем его показывать
    if(this.showAdd && this.showAdd !== false)
      this.showAdd = true;
  },
  data: () => ({
    pagesToEmitEvent: ['register', 'settings']
  }),
  methods:{
    updateVat(value, idx){
      if(this.pagesToEmitEvent.includes(this.from)) this.$emit('update', { value, idx });
    },

    changeDefaultVat(idx){
      if(this.pagesToEmitEvent.includes(this.from)) this.$emit('changeDefault', idx );
    },

    deleteVat(idx){
      if(this.pagesToEmitEvent.includes(this.from)) this.$emit('delete', idx);
    },

    addNewVat(){
      if(this.pagesToEmitEvent.includes(this.from)) this.$emit('addNew');
    }

  }

}
</script>

<style scoped>

</style>
