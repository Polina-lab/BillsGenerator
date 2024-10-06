<template>
  <div>

  <section class="card border-success mt-4">
    <div class="card-header">
      {{ $t("company.info")}}
    </div>

  <div class="row card-body">

   <div class="col-12" v-if="company.clients">
     {{ $t("company.client") }} : <n-link :to="`/admin/clients/` + company.clients.id">
                                  {{company.clients.firstname}} {{company.clients.lastname}}
                                  </n-link>
   </div>

    <div class="col-md-6 col-12">
      <label for="name" >{{ $t('company.name') }}<sup class="red">*</sup></label>
      <input type="text" class="form-control"
             id="name" v-model="company.name" :class="{'is-invalid': !company.name}"
             @change="updateStore('clients' , 'CompanyDetail', company)"/>

      <label for="reg_num">{{ $t('bills.reg_num') }}<sup class="red">*</sup></label>
      <input type="number" class="form-control"
             v-model="company.reg_num" :class="{'is-invalid': !company.reg_num}"
             id="reg_num" @change="updateStore('clients', 'CompanyDetail', company)"/>

      <div class="row">
        <div class="col-12">
          <label for="phone">{{ $t('User.phone') }}<sup class="red">*</sup></label>
          <div class="form-inline">
            <vue-country-code id="phone_flag" className="form-control" class="col-2" style=" "
                              :disabledFetchingCountry="true"
                              :preferredCountries="['ee', 'fi', 'lt', 'lv']"
                              @onSelect="setPhoneCode($event.dialCode)">
            </vue-country-code>
            <input type="tel"
                   class="form-control col-10 border-left-0 bl0"
                   id="phone"
                   v-model="company.phone"
                   :class="{ 'is-invalid': !company.phone }"
                   autocomplete="tel-national"
                   @change="updateStore('clients', 'ClientDetail', company)"
                   ref='phone'
            />
          </div><!--form-inline-->
        </div>
      </div>
      <label for="email">{{ $t('User.email') }}</label>
      <input type="email" class="form-control"
             v-model="company.email" id="email"
              :class="{ 'is-invalid': (!company.email || !(isEmailValid(company.email))) }"
             @change="updateStore('clients', 'CompanyDetail', company)"/>

      <label for="address">{{ $t('bills.address') }}</label>
      <input type="text" class="form-control"
             v-model="company.address" :class="{'is-invalid': !company.address}"
             @input="handleAddress($event)" list="userAddressList"
             id="address" @change="updateStore('clients', 'CompanyDetail', company)"/>
      <datalist id="userAddressList">
        <option v-for="address in address_search_results"
                :value="address"
                :key="address">
          {{ address }}
        </option>
      </datalist>
    </div>
    <div class="col-md-6 col-12">
      <label for="link">{{ $t("company.link") }}</label>
      <input type="url" class="form-control"
             id="link" v-model="company.link"
             placeholder="https://"
             @change="updateStore('clients' , 'CompanyDetail', company)"/>

      <label for="comment">{{ $t("company.description") }}</label>
      <textarea v-model="company.comment"
                class="form-control"
                @change="updateStore('clients', 'CompanyDetail', company)"
                id="comment" cols="30" rows="4">
      </textarea>

      <div class="row mt-20">
        <div class="col-12">
          <input type="checkbox" id="lepping" v-model="company.lepping" @change="!company.lepping; updateCheck()"/>
          <label for="lepping">{{ $t("company.contract") }}</label>
        </div>
      </div>

    </div>
  </div><!--row-->
  </section>

    <div class="row" v-if="$route.name.includes('admin-clients-company-id')" >
      <div class="col-12">
        <button type="button" v-if="company.id == null" class="btn btn-success float-right m20" @click="add" >{{ $t("common.save")}}</button>
        <button type="button" v-else class="btn btn-success float-right m20" @click="update">{{ $t("common.update")}}</button>
      </div>
    </div>
  </div>
</template>

<script>
import isEmailValid from '../../mixins/isEmailValid';

export default {
  name: "CompanyCreate",
  props: [ "showCreateBnt" ],
  mixins: [ isEmailValid ],
  data(){
    return{
      error:false,
      phoneNumberMask: '+### ### ########',
      address_search_results: null
    }
  },

  computed:{
    company() {
      return this.$store.getters['clients/getCompanyDetail']
    }
  },
  methods: {
    updateCheck(){
      this.$store.commit('clients/SET_OBJ', {name: 'CompanyDetail', value: this.company})
    },
    updateStore(module, name, value){
      if(this.company.id !== null && this.$store.state.clients.canChange === false ){
        console.log("company __ emit :" + { data : module , status : true , name : name , value : value })
        this.$emit('showConfirm' , { data : module , status : true , name : name , value : value });
      }else {
        this.$store.commit(`${module}/SET_OBJ`, {name, value})
      }
    },
    save() {
      this.$store.dispatch("clients/saveCompany", this.company).then(
        resp => {
          this.$toast.success(resp.msg).goAway(3000);
        }
      ).catch(err => {
        this.$toast.error(err.msg).goAway(3000);
      })
    },
    add(){
      if(this.company.reg_num === ""){
        this.error = true;
        this.$toast.error("Check reg_num").goAway(3000);
      }else if(this.company.name === ""){
        this.error = true;
        this.$toast.error("Check name").goAway(3000);
      }else{
        this.$store.commit("clients/PUSH_COMPANY", this.company)
      }
    },
    update(){
      this.company.lepping=!!this.company.lepping;
      this.$store.dispatch("clients/updateCompany" , this.company)
    },

    /**
     * Wraps address setting to store
     * @param {string} address address to handle
     */
    async handleAddress(data) {
      let search_querry = data.target.value;
      if (search_querry.length < 3) {
        return;
      }
      let addresses = await this.$store.dispatch('users/fetchAddresses', search_querry);
      if (addresses == undefined) {
        this.address_search_results = [];
      } else {
        this.address_search_results = addresses.map(address => address.ipikkaadress);
      }
      search_querry += ',';
      const regex = /^.+[\w|\s]+(\s\d+\w*),/g;
      if (this.address_search_results.length > 0 // check if addresses were found
        && data.inputType !== 'deleteContentBackward' // check if backspace or similar on touch was used
        && this.address_search_results[0].match(regex) // check if is matched
        && search_querry.match(regex) // check if is matched
        && this.address_search_results[0].match(regex)[0] !== search_querry.match(regex)[0] // check if they differ
        ) {
        data.target.value = this.address_search_results[0].match(regex)[0].replace(/,/g, '');
      }
      this.$store.commit('clients/SET_OBJ', { name: 'CompanyDetail.address', value: data.target.value });
    },
    /*
     * Sets country code in front of phone number
     * @param {number|string} phone_code code to set
     */
    setPhoneCode(phone_code) {
      let phone_number = null;
      if (this.company.phone.indexOf(' ') !== -1) {
        phone_number = this.company.phone.split(' ').slice(1)
          .join(' ').replace(/\+/g, '');
      } else {
        phone_number = this.company.phone.replace(/\+/g, '');
      }
      this.$store.commit('clients/SET_OBJ', {
        name: 'CompanyDetail.phone', value: `+${phone_code} ${phone_number}`
      });
      this.$refs.phone.focus();
    }
  }
}
</script>

<style scoped>
.mt-20{
  margin-top: 20px;
}
#phone_flag {
  border-bottom-right-radius: 0;
  border-top-right-radius: 0;
}

.bl0{
  border-bottom-left-radius: 0;
  border-top-left-radius: 0;
}
</style>
