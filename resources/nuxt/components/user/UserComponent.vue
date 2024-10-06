<template>
  <div>
    <div class="card border-primary mt-5 mx-auto mw-900">
      <button class="btn btn-outline-warning" @click="fillAllData" v-if="is_fake_data_buttons_visible">
        Fill all fields
      </button>
      <div class="card-title">
        <h3 class="m-2 blue p-3">{{ $t('User.personal_info') }}</h3>
        <button class="btn btn-outline-warning" v-if="is_fake_data_buttons_visible" @click="fillUserData">
          Fill user with fake data
        </button>
        <button class="btn btn-outline-danger" v-if="is_fake_data_buttons_visible" @click="is_fake_data_buttons_visible = false">
          Hide fake data buttons
        </button>
      </div>
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-sm-12 col-md-5 col-lg-5 pt-1">
            <label for="firstname" >{{ $t('User.firstname') }}<sup class="red">*</sup></label>
            <input type="text"
                   class="form-control"
                   id="firstname"
                   maxlength="30"
                   :class="{ 'is-invalid': !buyer.firstname }"
                   :value="buyer.firstname"
                   @change="$emit('update' , $event)"
            />
          </div>
          <div class="col-sm-12 col-md-5 col-lg-5 pt-2">
            <label for="lastname" >{{ $t('User.lastname') }}<sup class="red">*</sup></label>
            <input type="text"
                   class="form-control "
                   id="lastname"
                   maxlength="30"
                   :value="buyer.lastname"
                   :class="{ 'is-invalid': !buyer.lastname }"
                   @input="$emit('update' , $event)"
            />
          </div>
          <div class="col-sm-12 col-md-2 col-lg-2">
            <label for="langs_id">{{ $t('User.lang') }}<sup class="red">*</sup></label>
            <select :vlaue="buyer.langs_id" class="form-control" id="langs_id" @change="$emit('update' , $event)" required>
              <option v-for="loc in locale" :key="loc.id" :value="loc.id">{{ loc.name }}</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-12 pt-2">
            <label for="address">{{ $t('bills.address') }}<sup class="red">*</sup></label>
            <input type="text"
                   class="form-control"
                   maxlength="100"
                   id="address"
                   :value="buyer.address"
                   @input="handleAddress($event);"
                   :class="{ 'is-invalid': !buyer.address }"
                   list="userAddressList"
                   @change="$emit('update' , $event)"
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
          <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
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
                     @change="$emit('update' , $event)"
                     ref='phone'
              />
            </div><!--form-inline-->
          </div><!--col-6-->
          <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
            <label for="email">{{ $t('User.email') }}<sup class="red">*</sup></label>
            <input type="email" maxlength="30" class="form-control" id="email" :value="this.$auth.user.email" disabled>
          </div>
        </div>
        <div class="row">
         <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
            <label for="password">{{ $t('User.password') }}<sup class="red">*</sup></label>
            <input type="password"
                   class="form-control"
                   :class="{ 'is-invalid': !buyer.password }"
                   id="password"
                   maxlength="30"
                   :value="buyer.password"
                   autocomplete="new-password"
                   @input="$emit('update' , $event);"
            />
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
                <span class="container pl-0">
                  <label for="password2">{{ $t('User.repeat_password') }}<sup class="red">*</sup></label>
                </span>
            <input type="password"
                   class="form-control"
                   maxlength="30"
                   id="password2"
                   :value="buyer.password2"
                   :class="{ 'is-invalid': (!buyer.password2 || buyer.password2 !== buyer.password) }"
                   autocomplete="new-password"
                   @input="$emit('update' , $event);"
            />
          </div>
          <div class="col-12 pt-2">
            <div class="alert alert-danger text-center" role="alert"
              v-if="buyer.password && buyer.password2 && (buyer.password2 !== buyer.password)"
              id="password_notificationg"
            >
              {{ $t('User.passwords_not_matching') }}
            </div>

          </div>

        </div>

        <div class="row" v-if="Boolean($auth.user.has_buyer)">
          <div class="col-12 pt-3">
            <input type="checkbox" id="firm_data_checkbox" class="m-2" v-model="is_firm_data_visible">
            {{ $t('User.fill_firm_data') }}
            <i class="fa fa-info-circle" v-tooltip=" $t('info.can_do_it_later')"> </i>
            <!--add data about firm-->
          </div>
        </div>
      </div>
    </div><!--card-->


    <div class="card border-info mt-4 mx-auto mw-900" v-if="is_firm_data_visible">
      <div class="card-title">
        <h2 class="m-2 green p-3">{{ $t('User.firm_data') }}</h2>
        <button class="btn btn-outline-warning" v-if="is_fake_data_buttons_visible" @click="fillFirmData">
          Fill firm with fake data
        </button>
      </div>
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
            <label for="name">{{ $t('company.name') }}<sup class="red">*</sup></label>
            <input type="text"
                   class="form-control"
                   :class="{ 'is-invalid': !buyer.firm.name }"
                   id="name"
                   maxlength="50"
                   :value="buyer.firm.name"
                   @change="updateBuyerFirm($event);"
            />
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
            <label for="company_name">{{ $t('company.title') }}<sup class="red">*</sup></label>
            <div class="input-group ">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <input type="checkbox" v-tooltip="$t('User.title_same_as_name')"
                         :checked="is_title_same"
                         @change="is_title_same = !is_title_same; checkTitle();  "
                  />
                </div>
              </div>
              <input type="text"
                     class="form-control"
                     :class="{ 'is-invalid': !buyer.firm.company_name }"
                     id="company_name"
                     maxlength="50"
                     :value="buyer.firm.company_name"
                     @input="updateBuyerFirm($event); is_title_same = false; "
              />
            </div>
          </div>
        </div><!--.row-->
        <div class="row">
          <div class="col-12 pt-2">

            <label for="address">{{ $t('bills.address') }}<sup class="red">*</sup></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <input type="checkbox"
                         id="bank_data_checkbox"
                         :checked="is_address_same"
                         @click="is_address_same = !is_address_same; checkAddress(); " v-tooltip="$t('User.address_is_same')"
                  />
                </div>
              </div>
              <input type="text"
                     class="form-control "
                     name="firm_address"
                     id="address"
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

            <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
              <label for="phone">{{ $t('bills.phone') }}<sup class="red">*</sup></label>
              <input type="text"
                     class="form-control"
                     id="phone"
                     :value="buyer.firm.phone"
                     @input="updateBuyerFirm($event)"
              />
            </div>


          <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
            <label for="reg_num">{{ $t('bills.reg_num') }}<sup class="red">*</sup></label>
            <input type="text"
                   class="form-control"
                   :class="{ 'is-invalid': !buyer.firm.reg_num }"
                   id="reg_num"
                   maxlength="50"
                   :value="buyer.firm.reg_num"
                   @input="updateBuyerFirm($event); "
            />
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
            <label for="vat_reg_num">{{ $t('bills.km_reg_num') }}<sup class="red">*</sup><i class="fa fa-circle-info" v-tooltip="$t('info.if_vat_number_not_set')"></i></label>
            <input type="text"
                   class="form-control"
                   :class="{ 'is-invalid':  (!buyer.firm.vat_reg_num) }"
                   id="vat_reg_num"
                   maxlength="30"
                   :value="buyer.firm.vat_reg_num"
                   @input="updateBuyerFirm($event); checkVat(buyer.firm.vat_reg_num.length) ;"
            />
          </div>
            <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
              <addVatComponent :vat-data="buyer.firm.vat" :add-new="is_vat_missing" :show-add="false"  from="register"
                               @update="updateVat" @delete="deleteVat" @addNew="addNewVat" @change="changeDefaultVat"
              ></addVatComponent>
          </div><!--col-12-->
        </div><!--row-->

        <hr/>
        <button class="btn btn-outline-warning" @click="fillBankData" v-if="is_fake_data_buttons_visible">
          {{ 'Fill bank with (LHV & Swedbank) data' }}
        </button>
        <h2 class="m-2 green" :key="bank_data_key">{{ $t('User.bank_data') }}</h2>
        <p>{{ $t('register.bank_data_filled_later') }}</p>
        <div v-for="(bank, idx) in buyer.firm.banks" :key="bank.bank_km_reg_num">
          <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
              <label for="bank_name">{{ $t("bills.bank_name") }}<sup class="red">*</sup></label>
              <input type="text" class="form-control" id="bank_name"
                     maxlength="30"
                     :value="bank.bank_name"
                     @change="updateFirmBanks(idx, $event)"
              />
              <label for="bank_address" class="pt-2">{{ $t("bills.bank_address") }}<sup class="red">*</sup></label>
              <input type="text" class="form-control" id="bank_address"
                     :value="bank.bank_address"
                     maxlength="100"
                     @change="updateFirmBanks(idx, $event)"
              />
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 pt-2">
              <label for="bank_num">{{ $t("bills.bank_num") }}<sup class="red">*</sup></label>
              <input type="text" class="form-control" id="bank_num"
                     :value="bank.bank_num"
                     @change="updateFirmBanks(idx, $event)"
                     maxlength="30"
              />
              <label for="bank_swift" class="pt-2">{{ $t("bills.bank_swift") }}<sup class="red">*</sup></label>
              <input type="text" class="form-control" id="bank_swift"
                     :value="bank.bank_swift"
                     @change="updateFirmBanks(idx, $event)"
                     maxlength="30"
              />
            </div>
          </div><!--row-->
          <div class="row">
            <div class="col-12 text-center pt-2">
              <button class="btn btn-primary mt-4 btn-lg " v-if="!isRequisitesFilled"
                      @click="addBankRequisites" >
                {{ $t("bills.add_bank_requisites") }}
              </button>
              <button v-if="buyer.firm.banks.length > 1"
                      class="btn btn-outline-danger mt-4 btn-lg "
                      @click="deleteBankRequisites(idx)">
                {{ $t("bills.delete_requisites") }}
              </button>
            </div>
          </div><!--row-->
        </div>
      </div>
    </div><!--card-body-->
    <div class="row mw-900 mx-auto">
      <div class="col-12">
        <div class="btn-container-right">
          <button class="btn btn-success m-2 float-right" :disabled="buyer.password < 3 && buyer.password !== buyer.password2" @click="createUser" >{{ $t('common.save') }}</button>
        </div>
      </div>
    </div>


  </div>
</template>

<script>
import addVatComponent from "../../components/bills/common/addVatComponent";

export default {
  name: "UserComponent",
  props : ['buyer'],
  components:{ addVatComponent },
  data: () => ({
    is_firm_data_visible: false,
    is_bank_data_visible: false,
    is_title_same: false,
    is_address_same: false,
    is_vat_missing: false,
    address_search_results: [],
    bank_data_key: 0,
    keys_pressed: [],
    is_fake_data_buttons_visible: false
  }),
  computed: {
    locale() {
      return this.$i18n.locales;
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

  beforeMount() {
    if(process.client) {
      document.body.addEventListener('keydown', (event) => {
        this.keys_pressed.push(event.key);
        if (['d', 'b'].every((key) => this.keys_pressed.includes(key))) {
          this.is_fake_data_buttons_visible = true;
        }
      });
      document.body.addEventListener('keyup', (event) => {
        this.keys_pressed = [];
      })
    }
  },

  methods:{
    /**
     * Wraps address setting to store
     * @param {string} address address to handle
     */
   async handleAddress(data) {
      let search_query = data.target.value;
      if (search_query.length < 3  || search_query.length > 7) {
        return;
      }
      let addresses = await this.$store.dispatch('users/fetchAddresses', search_query);
      if (addresses == undefined) {
        this.address_search_results = [];
      } else {
        this.address_search_results = addresses.map(address => address.ipikkaadress);
      }
      search_query += ',';
      const regex = /^.+[\w|\s]+(\s\d+\w*),/g;
      if (this.address_search_results.length > 0 // check if addresses were found
        && data.inputType !== 'deleteContentBackward' // check if backspace or similar on touch was used
        && this.address_search_results[0].match(regex) // check if is matched
        && search_query.match(regex) // check if is matched
        && this.address_search_results[0].match(regex)[0] !== search_query.match(regex)[0] // check if they differ
        ) {
        data.target.value = this.address_search_results[0].match(regex)[0].replace(/,/g, '');
      }
      switch (data.target.id) {
        case 'address':
          this.$emit('update', {target: { id: 'address', value: data.target.value } } );
          break;
        case 'firm_address':
          this.$emit('update', { to: 'firm', data: { target: { id: 'address', value: data.target.value } } })
          if (this.is_address_same) {
            this.is_address_same = false;
          }
          break;
        default:
          break;
      }
    },
    setPhoneCode(phone_code) {
      let phone_number = null;
      if(this.buyer.phone) {
        if (this.buyer.phone.toString().indexOf(' ') !== -1) {
          phone_number = this.buyer.phone.toString().split(' ').slice(1).join(' ').replace(/\+/g, '');
        } else {
          phone_number = this.buyer.phone.toString().replace(/\+/g, '');
        }
        this.$emit('update', { name: 'buyer.phone', value: `+${phone_code} ${phone_number}`});
        this.$refs.phone.focus();
      }
    },

    updateBuyerFirm(data){
      this.$emit('update' , { to: 'firm' ,  data})
    },

    addNewVat() {
      this.$emit('update' , { to: 'vat' })
      // не понимаю почему не обновляет
      let val =  this.buyer.firm.vat[0].vat;
      this.$store.commit('users/SET_OBJ', { name: 'buyer.firm.vat[0].vat', value: val });

    },

    deleteVat(idx) {
      if (this.buyer.firm.vat[idx].default) {
        this.$store.commit('users/SET_OBJ', { name: 'buyer.firm.vat[0].default', value: true });
      }
      this.$store.commit('users/DELETE_ITEM_ARRAY', { name: 'buyer.firm.vat',  value: idx, elementReference: 'idx' });
    },


    setFirmTitle() {
      if (this.is_title_same) {
        this.updateBuyerFirm({ target: { id: 'company_name', value: this.buyer.firm.name } });
      }
    },

    changeDefaultVat(idx) {
      let currentDefaultID = this.buyer.firm.vat.find(v => v.default == true).id;
      let currentDefaultIndex = this.buyer.firm.vat.map(v => v.id).indexOf(currentDefaultID);
      this.$store.commit('users/SET_OBJ', { name: `buyer.firm.vat[${currentDefaultIndex}].default`, value: false });
      this.$store.commit('users/SET_OBJ', { name: `buyer.firm.vat[${idx}].default`, value: true });
    },


    updateFirmBanks(idx, data) {
      this.$store.commit('users/SET_OBJ', {
        name: `buyer.firm.banks[${idx}][${data.target.id}]`, 'value':  data.target.value
      });
    },

    addBankRequisites() {
      this.$store.commit('users/ARRAY_PUSH_NEW_BANK');
      this.bank_data_key += 1;
    },

    deleteBankRequisites(idx) {
      this.$store.commit('users/ARRAY_DELETE_BANK', idx);
      this.bank_data_key += 1;
    },

    updateVat(data) {
      this.$store.commit('users/SET_OBJ', { name: `buyer.firm.vat[${data.idx}].vat`, value: data.value });
    },

    checkVat(l){
      if(l > 3){
        this.is_vat_missing = true;
      }
    },

    checkTitle(){
     if(this.is_title_same)
       this.updateBuyerFirm({target : { id: 'company_name' , value : this.buyer.firm.name  } })
    },

    checkAddress(){
     if(this.is_address_same)
       this.updateBuyerFirm({target : { id: 'address' , value : this.buyer.address  } })
    },

    createUser() {
      let user_data_fields_list = [ 'firstname', 'lastname', 'phone', 'address', 'password', 'password2' ];
      if (this.is_firm_data_visible) {
        this.updateBuyerFirm({target : { id: 'main_firm' , value : true  } })
        let firm_data_fields_list = [ 'name', 'company_name', 'address', 'reg_num', 'vat_reg_num' ];
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

      this.updateBuyerFirm({target : { id: 'main_firm' , value : true  } })

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

    fillUserData() {
      this.$emit('update', { target: { id: 'firstname', value: this.generateString(6) } })
      this.$emit('update', { target: { id: 'lastname', value: this.generateString(8) } })
      this.$emit('update', { target: { id: 'phone', value: this.generateNumber(8) } })
      this.$emit('update', { target: { id: 'address', value: this.generateString(30) } })
      let pwrd = this.generateString(8);
      this.$emit('update', { target: { id: 'password', value: pwrd } })
      this.$emit('update', { target: { id: 'password2', value: pwrd } })

    },

    fillFirmData() {
       this.updateBuyerFirm({ target: { id: 'name', value: this.generateString(8) } })
       this.updateBuyerFirm({ target: { id: 'company_name', value: this.generateString(16) } })
       this.updateBuyerFirm({ target: { id: 'address', value: this.generateString(30) } })
       this.updateBuyerFirm({ target: { id: 'reg_num', value: this.generateNumber(10) } })
       this.updateBuyerFirm({ target: { id: 'vat_reg_num', value: this.generateNumber(10) } })
    },

    fillBankData() {
      if (this.buyer.firm.banks.length > 2) {
        return;
      }
      this.updateFirmBanks(0, { target: { id: 'bank_name', value: 'LHV' } })
      this.updateFirmBanks(0, { target: { id: 'bank_address', value: 'Tartu mnt 2, 10145 Tallinn' } })
      this.updateFirmBanks(0, { target: { id: 'bank_num', value: 'EE' + this.generateNumber(18) } })
      this.updateFirmBanks(0, { target: { id: 'bank_swift', value: 'LHVBEE22' } })

      this.addBankRequisites();

      this.updateFirmBanks(1, { target: { id: 'bank_name', value: 'SWEDBANK' } })
      this.updateFirmBanks(1, { target: { id: 'bank_address', value: 'LIIVALAIA 8, 15040 Tallinn' } })
      this.updateFirmBanks(1, { target: { id: 'bank_num', value: 'EE' + this.generateNumber(18) } })
      this.updateFirmBanks(1, { target: { id: 'bank_swift', value: 'HABAEE2X' } })
    },

    fillAllData() {
      this.fillUserData();
      this.fillFirmData();
      this.fillBankData();
    },

    // https://stackoverflow.com/a/1349426
    /**
     * Generates a string of random chracters to use as a coupon
     * @param {number} length lengh of string to generate
     * @returns {string} generated string
     */
    generateString(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    },

    /**
     * Generates a random positive number of given lenght
     * @param {number} length lengh of string to generate
     * @returns {number} generated number
     */
    generateNumber(length) {
      return Math.floor(Math.random() * ('1' + '0'.repeat(length)));
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

.mw-900{
  max-width: 900px;
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
