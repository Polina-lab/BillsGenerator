<template>
  <section class="card border-primary mt-4">
    <div class="card-header">{{ $t("clients.info")}}</div>
    <div class="card-body">
      <div class="row" >
        <div class="col-md-3 col-12">
          <label for="firstname">{{$t('User.firstname')}}<sup class="red">*</sup></label>
          <input type="text" class="form-control"
                 id="firstname" list="firstname_list"
                 v-model="client.firstname"
                 :class="{ 'is-invalid': !client.firstname }"
                 @input="saveSelectionAndReset('firstname', $event)"
                 @change="updateStore('clients', 'ClientDetail', client)"
          />
          <datalist id="firstname_list">
            <option v-for="(l , i ) in list" :key="i" :value="l.firstname">{{ l.firstname + ' ' + l.lastname }}</option>
          </datalist>
        </div>
        <div class="col-md-3 col-12">
          <label for="lastname">{{$t('User.lastname')}}<sup class="red">*</sup></label>
          <input type="text" class="form-control"
                 id="lastname" list="lastname_list"
                 v-model="client.lastname"
                 :class="{ 'is-invalid': !client.lastname }"
                 @input="saveSelectionAndReset('lastname' , $event)"
                 @change="updateStore('clients', 'ClientDetail', client)"
          />
          <datalist id="lastname_list">
            <option v-for="(l , i) in list" :key="i" :value="l.lastname">{{ l.firstname + ' ' + l.lastname }}</option>
          </datalist>
        </div>
        <div class="col-md-6 col-12">
          <label for="id_code">{{$t('clients.id_code')}}</label>
          <input type="number" class="form-control"
                 list="id_code_list"
              id="id_code" :disabled="showSup"
              v-model="client.id_code"
              :class="{ 'is-invalid':  (!client.id_code) }"
              @input="saveSelectionAndReset('id_code', $event)"
              @change="updateStore('clients', 'ClientDetail', client)"/>
        </div>
        <datalist id="id_code_list">
          <option v-for="(l, i) in list" :key="i" :value="l.id_code">{{ l.id_code }}</option>
        </datalist>
      </div>
      <div class="row">
        <div class="col-md-6 col-12">
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
                   v-model="client.phone"
                   :class="{ 'is-invalid': !client.phone }"
                   autocomplete="tel-national"
                   @change="updateStore('clients', 'ClientDetail', client)"
                   ref='phone'
            />
          </div><!--form-inline-->
          <datalist id="phone_list">
            <option v-for="(l, i) in list" :key="i" :value="l.phone">{{ l.phone }}</option>
          </datalist>
          <label for="email">{{ $t('User.email') }}<sup class="red">*</sup></label>
          <input type="email" class="form-control"
                 id="email"
                 v-model="client.email"
                 :class="{
                   'is-invalid': (!client.email || !(isEmailValid(client.email))) }"
                 @input="saveSelectionAndReset('email', $event)"
                 @change="updateStore('clients', 'ClientDetail', client)"
                 list="email_list"
          />
          <datalist id="email_list">
            <option v-for="(l ,i) in list" :key="i" :value="l.email">{{ l.email }}</option>
          </datalist>
          <label for="address">{{ $t('bills.address')}}</label>
          <input type="text" class="form-control" v-model="client.address" :disabled="showSup" id="address"
                 @input="saveSelectionAndReset('address', $event)"
                 @change="updateStore('clients', 'ClientDetail', client)"
                 list="address_list"
                 :placeholder="$t('User.address_placeholder')"/>
          <datalist id="address_list">
            <option v-for="(address, i) in list" :key="i" :value="address">{{ address }}</option>
          </datalist>
        </div>
        <div class="col-md-6">
          <label for="comment">{{$t('clients.comment')}}</label>
          <textarea class="form-control"
                    rows="5"
                    id="comment"
                    v-model="client.comment"
                    @change="updateStore('clients', 'ClientDetail', client)">
          </textarea>
        </div>
      </div><!--row-->
    </div><!--card-body-->
    <div class="card-footer"  >
      <div class="row">
        <div class="col-12" v-if="!this.$route.name.includes('admin-clients')">
          <span class="p-2" @click="setCompany(true)" >
            <i class="fa fa-plus" :title='$t("clients.search")'></i>{{ $t('clients.add_new_company') }}
          </span>
          <select v-if="client.companies.length > 0" v-model="selected_company" id="selected_company" @change="setCompany(false)">
            <option value="">{{`-- ${$t('clients.select_company').toUpperCase()} --`}}</option>
            <option  v-for="company in client.companies" :key="company.id" :value="company">{{ company.name }}</option>
          </select>
        </div>
        <div class="col-12" v-else>
          <label for="status"> {{ $t('common.set_active')}}</label>
          <input type="checkbox" id="status" v-model="client.status" @change="updateStore('clients', 'ClientDetail' , client)">
        </div>
      </div>
    </div>

  </section>
</template>

<script>
import isEmailValid from '../../mixins/isEmailValid';

  export default {
    name: "ClientInfo",
    props: [ 'showSup' ],
    mixins: [ isEmailValid ],
    data(){
      return {
        hasCreateCompany:false,
        phoneNumberMask: '+### ### ########',
        list :[],
        selected_company :''
      }
    },
    destroyed(){
      this.list = [];
    },
    computed: {
      client() {
        return this.$store.getters['clients/getClientDetail']
      },
    },
    methods:{
      updateStore(module, name, value){
        if(this.client.id !== null && this.$store.state.clients.canChange === false && this.$route.name !== 'admin-clients-id' ){
          this.$emit('showConfirm', { data: module, status: true, name: name , value: value });
        }else {
          this.$store.commit(`${module}/SET_OBJ`, {name, value})
        }
      },

      async saveSelectionAndReset(name, e) {
        let val = e.target.value;

        if(!this.$route.name.includes('admin-clients-create')) {
          var client = this.list.find(el => el[e.target.id] === val);
          if (client) {
            this.updateStore('clients', 'ClientDetail', client);
            this.updateStore('clients', 'canChange', false);
          }
          if (val.length >= 2 && val.length <= 4) {
            let res = null;
            if (name === 'address') {
              res = await this.$store.dispatch('users/fetchAddresses', val);
              if(typeof res === 'object' )
              this.list = res.map(address => address.ipikkaadress);
            } else {
              res = await this.$store.dispatch("clients/fetchClients", { name: name, value: this.client[name] });
              res.forEach(el => {
                if (!this.list.find(e => e.id === el.id)) this.list.push(el);
              });
            }
          }
        }
       },

      setCompany(has_empty){
        if (has_empty) {
          this.$store.dispatch('clients/createCompany');
          this.$store.commit("clients/SET_OBJ", { name: 'CompanyDetail', key: 'email', value: this.client.email });
          this.$store.commit("clients/SET_OBJ", { name: 'CompanyDetail', key: 'phone', value: this.client.phone });
        }
        else {
          this.$store.commit("clients/SET_OBJ", { name: 'CompanyDetail', value: this.selected_company });
        }
        this.$emit('setCompanyVisible', true)
      },


      /**
       * Sets country code in front of phone number
       * @param {number|string} phone_code code to set
       */
      setPhoneCode(phone_code) {
        let phone_number = null;
        if (this.client.phone.indexOf(' ') !== -1) {
          phone_number = this.client.phone.split(' ').slice(1)
            .join(' ').replace(/\+/g, '');
        } else {
          phone_number = this.client.phone.replace(/\+/g, '');
        }
        this.$store.commit('clients/SET_OBJ', {
          name: 'ClientDetail.phone', value: `+${phone_code} ${phone_number}`
        });
        this.$refs.phone.focus();
      }
    },
  }
</script>

<style scoped>

.pointer{
  cursor: pointer;
}

.makeBrighter:hover {
  filter: brightness(1.2);
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
