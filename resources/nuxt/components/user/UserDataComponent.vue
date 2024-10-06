<template>
  <div >
      <div class="card border-primary mt-5 mx-auto mw-1400"  >
          <div class="card-title">
            <h3 class="m-2 blue p-3">{{ $t('User.personal_info') }}</h3>
          </div>
          <div class="card-body pt-0">
            <div class="row">
              <div class="col-sm-12 col-md-5 col-lg-5">
                <label for="firstname" >{{ $t('User.firstname') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       id="firstname"
                       maxlength="30"
                       :value="buyer.firstname"
                       :class="{ 'is-invalid': !buyer.firstname }"
                       @change="updateBuyer($event)"
                />
              </div>
              <div class="col-sm-12 col-md-5 col-lg-5 ">
                <label for="lastname" >{{ $t('User.lastname') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control "
                       id="lastname"
                       maxlength="30"
                       :value="buyer.lastname"
                       :class="{ 'is-invalid': !buyer.lastname }"
                       @change="updateBuyer($event)"
                />
              </div>
              <div class="col-sm-12 col-md-2 col-lg-2">
                <label for="language">{{ $t('User.lang') }}<sup class="red">*</sup></label>
                <select :value="buyer.langs_id" class="form-control" id="language" @change="updateBuyer($event)" required>
                  <option v-for="loc in locale" :key="loc.id" :value="loc.id">{{ loc.name }}</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <label for="address">{{ $t('bills.address') }}<sup class="red">*</sup></label>
                <input type="text"
                       class="form-control"
                       maxlength="100"
                       id="address"
                       :value="buyer.address"
                       @input="handleAddress($event);"
                       :class="{ 'is-invalid': !buyer.address }"
                       list="userAddressList"
                       @change="updateBuyer($event)"
                       :placeholder="$t('User.address_placeholder')"
                />
                <datalist id="userAddressList">
                  <option v-for="address in address_search_results"
                          :value="address"
                          :key="address">
                    {{ address }}
                  </option>
                </datalist>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-6 col-lg-6">
                 <label for="phone">{{ $t('User.phone') }}<sup class="red">*</sup></label>
                 <div class="form-inline">
                    <vue-country-code id="phone_flag" className="form-control" class="col-2"
                                      :disabledFetchingCountry="true"
                                      :preferredCountries="['ee', 'fi', 'lt', 'lv']"
                                      @onSelect="setPhoneCode($event.dialCode)">
                    </vue-country-code>
                    <input type="tel"
                           class="form-control col-10 border-left-0 bl0"
                           id="phone"
                           maxlength="15"
                           :value="buyer.phone"
                           :class="{ 'is-invalid': !buyer.phone }"
                           autocomplete="tel-national"
                           @change="updateBuyer($event)"
                           ref='phone'
                    />
                 </div><!--form-inline-->
              </div><!--col-6-->
              <div class="col-sm-12 col-md-6 col-lg-6">
                <label for="email">{{ $t('User.email') }}<sup class="red">*</sup></label>
                <input type="email" maxlength="30" class="form-control" id="email" :value="this.$auth.user.email" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-6 col-lg-6">
                <label for="password">{{ $t('User.password') }}<sup class="red">*</sup></label>
                <input type="password"
                       class="form-control"
                       :class="{ 'is-invalid': !buyer.password }"
                       id="password"
                       maxlength="30"
                       :value="buyer.password"
                       autocomplete="new-password"
                       @input="updateBuyer($event); reloadStore()"
                />
              </div>
              <div class="col-sm-12 col-md-6 col-lg-6">
                <span class="container pl-0">
                  <label for="password2">{{ $t('User.repeat_password') }}<sup class="red">*</sup></label>
                  <label v-if="buyer.password2 && (buyer.password2 !== buyer.password)"
                         id="password_notificationg"
                         class="pl-2"
                         for="password2"
                  >
                    {{ $t('User.passwords_not_matching') }}
                  </label>
                </span>
                <input type="password"
                       class="form-control"
                       :class="{ 'is-invalid': (!buyer.password2 || buyer.password2 !== buyer.password) }"
                       maxlength="30"
                       id="password2"
                       :value="buyer.password2"
                       autocomplete="new-password"
                       @input="updateBuyer($event); reloadStore()"
                />
              </div>
            </div>
            <div class="row">
              <div class="offset-7 col-5 p-2">
                <input type="checkbox" id="firm_data_checkbox" class="m-2" v-model="is_firm_data_visible">
                {{ $t('User.fill_firm_data') }} <!--add data about firm-->
              </div>
            </div>
          </div>
        </div><!--card-->

        <div class="card border-info mt-4 mx-auto mw-1400" v-if="is_firm_data_visible">
          <div class="card-title">
            <h2 class="m-2 green">{{ $t('User.firm_data') }}</h2>
          </div>
          <div class="card-body pt-0">
            <div class="row">
              <div class="col-sm-12 col-md-6 col-lg-6">
                <label for="name">{{ $t('company.name') }}</label>
                <input type="text"
                       class="form-control"
                       :class="{ 'is-invalid': !buyer.firm.name }"
                       id="name"
                       maxlength="50"
                       :value="buyer.firm.name"
                       @change="updateBuyerFirm($event); setFirmTitle(); reloadStore()"
                />
              </div>
              <div class="col-sm-12 col-md-6 col-lg-6">
                <label for="firm_title">{{ $t('company.title') }}</label>
                <div class="input-group ">
                  <div class="input-group-prepend">
                      <div class="input-group-text">
                          <input type="checkbox" v-tooltip="$t('User.title_same_as_name')"
                                 :checked="is_title_same"
                                 @change="is_title_same = !is_title_same; setFirmTitle()"
                          />
                    </div>
                  </div>
                <input type="text"
                       class="form-control"
                       :class="{ 'is-invalid': !buyer.firm.firm_title }"
                       id="firm_title"
                       maxlength="50"
                       :value="buyer.firm.firm_title"
                       @input="updateBuyerFirm($event); is_title_same = false; reloadStore()"
                />
                </div>
              </div>
            </div><!--.row-->
            <div class="row">
              <div class="col-12">

                <label for="firm_address">{{ $t('bills.address') }}</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <input type="checkbox"
                             id="bank_data_checkbox"
                             :checked="is_address_same"
                             @click="is_address_same = !is_address_same; setFirmAddress();" v-tooltip="$t('User.address_is_same')"
                      />
                    </div>
                  </div>
                  <input type="text"
                         class="form-control "
                         name="firm_address"
                         id="firm_address"
                         :value="buyer.firm.address"
                         @input="handleAddress($event);"
                         list="firmAddressList"
                         @change="updateBuyerFirm($event)"
                         :placeholder="$t('User.address_placeholder')"
                  />
                  <datalist id="firmAddressList">
                    <option v-for="address in address_search_results"
                            :value="address"
                            :key="address">
                      {{ address }}
                    </option>
                  </datalist>
                </div>
              </div><!--.col-12-->
              <div class="col-sm-12 col-md-6 col-lg-6">
                <label for="reg_num">{{ $t('bills.reg_num') }}</label>
                <input type="text"
                       class="form-control"
                       :class="{ 'is-invalid': !buyer.firm.reg_num }"
                       id="reg_num"
                       maxlength="50"
                       :value="buyer.firm.reg_num"
                       @input="updateBuyerFirm($event); reloadStore()"
                />
              </div>
              <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="vat_number">{{ $t('bills.km_reg_num') }} <i class="fa fa-circle-info" v-tooltip="$t('info.if_vat_number_not_set')"></i></label>
                    <!-- TODO: remove is-invalid class and check UI for no red border -->
                    <input type="text"
                           class="form-control"
                           :class="{ 'is-invalid':  (!buyer.firm.vat_number) }"
                           id="vat_number"
                           maxlength="30"
                           :value="buyer.firm.vat_number"
                           @input="updateBuyerFirm($event); is_vat_missing = !is_vat_missing;"
                    />
                <div class="p-2 float-left">
                  <addVatComponent :vat-data="buyer.firm.vat" :add-new="is_vat_missing" from="register"
                  @update="updateVat" @delete="deleteVat" @addNew="addNewVat" @change="changeDefaultVat"
                  ></addVatComponent>
                </div>

              </div><!--col-12-->
            </div><!--row-->

              <hr/>
              <h2 class="m-2 green" :key="bank_data_key">{{ $t('User.bank_data') }}</h2>
              <p>{{ $t('register.bank_data_filled_later') }}</p>
              <div v-for="(bank, idx) in buyer.firm.banks" :key="bank.bank_km_reg_num">
                <div class="row">
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="bank_name">{{ $t("bills.bank_name") }}</label>
                    <input type="text" class="form-control" id="bank_name"
                           maxlength="30"
                           :value="bank.bank_name"
                           @change="updateFirmBanks(idx, $event)"
                    />
                    <label for="bank_address">{{ $t("bills.bank_address") }}</label>
                    <input type="text" class="form-control" id="bank_address"
                           :value="bank.bank_address"
                           maxlength="100"
                           @change="updateFirmBanks(idx, $event)"
                    />
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="bank_num">{{ $t("bills.bank_num") }}</label>
                    <input type="text" class="form-control" id="bank_num"
                           :value="bank.bank_num"
                           @change="updateFirmBanks(idx, $event)"
                           maxlength="30"
                    />
                    <label for="bank_swift">{{ $t("bills.bank_swift") }}</label>
                    <input type="text" class="form-control" id="bank_swift"
                           :value="bank.bank_swift"
                           @change="updateFirmBanks(idx, $event)"
                           maxlength="30"
                    />
                  </div>
                </div><!--row-->
                <div class="row">
                  <div class="col-6">
                    <button class="btn btn-outline-primary mt-4 form-control"
                            @click="addBankRequisites" :disabled="!isRequisitesFilled">
                      {{ $t("bills.add_bank_requisites") }}
                    </button>
                  </div>
                  <div class="col-6">
                    <button v-if="buyer.firm.banks.length > 1"
                            class="btn btn-outline-danger mt-4 form-control"
                            @click="deleteBankRequisites(idx)">
                      {{ $t("bills.delete_requisites") }}
                    </button>
                  </div>
                </div><!--row-->
              </div>
            </div>
          </div><!--card-body-->
        <div class="row mw-1400 mx-auto">
          <div class="col-12">
            <div class="btn-container-right">
              <button class="btn btn-success m-2 float-right" @click="createUser" >{{ $t('common.save') }}</button>
            </div>
          </div>
    </div>
  </div>
</template>

<script>
import addVatComponent from "@/components/bills/common/addVatComponent";
import { VAT } from '../../assets/js/models/Bill';

export default {
  name: "UserDataComponent",
  components:{ addVatComponent },
  data: () => ({
    is_firm_data_visible: false,
    is_title_same: false,
    is_address_same: false,
    is_vat_missing: false,
    address_search_results: [],
    bank_data_key: 0,
  }),
  async beforeCreate(){
    await this.$store.dispatch('users/fetchUser');
  },

  mounted() {
    this.$nextTick().then(() => {
      this.$store.commit('users/SET_OBJ', { name: 'buyer', key: 'email', value: this.$auth.user.email });
      this.$store.commit('users/SET_OBJ', { name: 'buyer.firm.vat[0].default', value: true });
    });
  },
  computed: {
    locale() {
      return this.$i18n.locales;
    },
    buyer() {
      return this.$store.state.users.buyer;
    },

    /**
     * Evaluates if all required fields of the last requisite are filled
     * @returns {Boolean}
     */
    isRequisitesFilled(){
      return !(this.buyer.firm.banks[this.buyer.firm.banks.length -1].bank_name &&
        this.buyer.firm.banks[this.buyer.firm.banks.length -1].bank_address &&
        this.buyer.firm.banks[this.buyer.firm.banks.length -1].bank_swift &&
        this.buyer.firm.banks[this.buyer.firm.banks.length -1].bank_num);
    },
  },
  methods: {
    createUser() {
      let user_data_fields_list = [ 'firstname', 'lastname', 'phone', 'address', 'password', 'password2' ];
      if (this.is_firm_data_visible) {
        let firm_data_fields_list = [ 'name', 'firm_title', 'reg_num' ];
        user_data_fields_list = user_data_fields_list.concat(firm_data_fields_list);
      }
      let is_value_missing = user_data_fields_list.some((element) => {
        let el = document.getElementById(element);
        if (!el.value || (element == 'password2' && this.buyer.password2 !== this.buyer.password)) {         
          el.scrollIntoView();
          return true;
        }
      });
      if (is_value_missing) {
        return;
      }

      this.$store.dispatch("users/createBuyer").then(
        res => {
          if(res.type === 'success')
            this.$emit("next", 4);
        },
        err =>{
          this.$toast.error(err.msg);
        }
      )
    },

    /**
     * Wraps address setting to store
     * @param {string} address address to handle
     */
    async handleAddress(data) {
      let search_querry = data.target.value;
      if (search_querry.length < 3  || search_querry.length >= 10) {
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
      switch (data.target.id) {
        case 'address':
          this.updateBuyer(data);
          break;
        case 'firm_address':
          this.updateBuyerFirm({ target: { id: 'address', value: data.target.value } });
          if (this.is_address_same) {
            this.is_address_same = false;
          }
          break;
        default:
          break;
      }
    },

    addBankRequisites() {
      this.$store.commit('users/ARRAY_PUSH_NEW_BANK');
      this.bank_data_key += 1;
    },

    deleteBankRequisites(idx) {
      this.$store.commit('users/ARRAY_DELETE_BANK', idx);
      this.bank_data_key += 1;
    },

    /**
     * Updates stored data given data to update with
     * @param {object} data data that comes with `$event` parameter
     * @param {object} data.target argumet wrapper
     * @param {string} data.target.id key to edit
     * @param {string} data.target.value value to set
     */
    updateBuyer(data) {
      this.$store.commit('users/SET_OBJ', { name: 'buyer', key: data.target.id, value: data.target.value })
    },

    reloadStore() {
      let temporary_value = this.buyer.firstname;
      this.updateBuyer({ target: { id: 'firstname', value: 'null'} });
      this.updateBuyer({ target: { id: 'firstname', value: temporary_value} });
    },

    updateBuyerFirm(data) {
      this.$store.commit('users/SET_OBJ', { name: 'buyer', key: 'firm', subkey: data.target.id, value: data.target.value })
    },

    updateFirmBanks(idx, data) {
      this.$store.commit('users/SET_OBJ', {
        name: `buyer.firm.banks[${idx}][${data.target.id}]`, 'value':  data.target.value
      });
    },

    setFirmTitle() {
      if (this.is_title_same) {
        this.updateBuyerFirm({ target: { id: 'firm_title', value: this.buyer.firm.name } });
      }
    },

    setFirmAddress() {
      if (this.is_address_same) {
        this.updateBuyerFirm({ target: { id: 'address', value: this.buyer.address } });
      }
    },

    setPhoneCode(phone_code) {
      let phone_number = null;
      if(this.buyer.phone) {
        if (this.buyer.phone.indexOf(' ') !== -1) {
          phone_number = this.buyer.phone.split(' ').slice(1).join(' ').replace(/\+/g, '');
        } else {
          phone_number = this.buyer.phone.replace(/\+/g, '');
        }
        this.$store.commit('users/SET_OBJ', {name: 'buyer.phone', value: `+${phone_code} ${phone_number}`});
        this.$refs.phone.focus();
      }
    },

    addNewVat() {
      this.$store.commit('users/ADD_ITEM_ARRAY_BOTTOM', {
        name: 'buyer.firm.vat', 'value': new VAT()
      });
       /* this.reloadStore();
        this.$nextTick().then(() => {
          this.$refs[`vat_vat_field_${this.buyer.firm.vat.length - 1}`][0].focus();
        });*/
    },

    deleteVat(idx) {
      if (this.buyer.firm.vat[idx].default) {
        this.$store.commit('users/SET_OBJ', { name: 'buyer.firm.vat[0].default', value: true });
      }
      this.$store.commit('users/DELETE_ITEM_ARRAY', { name: 'buyer.firm.vat',  value: idx, elementReference: 'idx' });
      this.reloadStore();
    },

    changeDefaultVat(idx) {
      let currentDefaultID = this.buyer.firm.vat.find(v => v.default == true).id;
      let currentDefaultIndex = this.buyer.firm.vat.map(v => v.id).indexOf(currentDefaultID);
      this.$store.commit('users/SET_OBJ', { name: `buyer.firm.vat[${currentDefaultIndex}].default`, value: false });
      this.$store.commit('users/SET_OBJ', { name: `buyer.firm.vat[${idx}].default`, value: true });
    },

    updateVat(vat_value, idx) {
      this.$store.commit('users/SET_OBJ', { name: `buyer.firm.vat[${idx}].vat`, value: +vat_value });
    }
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";
.red {
  color:red;
}

.mw-1400{

  max-width: 1400px;
}


#phone_flag {
  border-bottom-right-radius: 0;
  border-top-right-radius: 0;
}

.bl0{
  border-bottom-left-radius: 0;
  border-top-left-radius: 0;
}


.blue{
  color: #009fdc;
}

.container{
  display:flex;
}

.green{
  color: $logo-green;
}
#phone_flag >>> .dropdown-list{
  width:300px;
}

.btn-container-right {
  direction: rtl;
  * {
    direction: ltr;
  }
}

</style>
