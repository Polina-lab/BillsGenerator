<template>
  <div>
    <div class="card-title">
      <h2 class="m-2">{{ $t('User.personal_info') }}</h2>
    </div>
    <div class="card-body pt-0">
      <div id="dragndrop_area"
           @dragover="dragover($event)"
           @dragleave="dragleave($event)"
           @drop="drop($event)">
        <div class="row">
          <div class="col-xs-12 col-sm-5 ">
            <label for="firstname">{{ $t('User.firstname') }}<sup class="red">*</sup></label>
            <input type="text"
                   class="form-control"
                   id="firstname"
                   maxlength="50"
                  :value="user.firstname"
                  :class="{ 'is-invalid': !user.firstname }"
                   @change="updateUser($event)"/>
          </div>
          <div class="col-xs-12 col-sm-5">
            <label for="lastname">{{ $t('User.lastname') }}<sup class="red">*</sup></label>
            <input type="text"
                   class="form-control"
                   id="lastname"
                   maxlength="50"
                   :value="user.lastname"
                   :class="{ 'is-invalid': !user.lastname }"
                   @change="updateUser($event)"
            />
          </div>
          <div class="col-xs-12 col-sm-2">
            <label for="langs_id">{{ $t('User.lang') }}<sup class="red">*</sup></label>
            <select :value="user.langs_id" class="form-control" id="langs_id"
                    @change="updateUser($event); saveUser(); switchLanguage($event.target.value)">
              <option v-for="loc in locale" :key="loc.id" :value="+loc.id">{{ loc.name }}</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <label for="address">{{ $t('bills.address') }}<sup class="red">*</sup></label>
            <input type="text"
                   class="form-control"
                   id="address"
                   :value="user.address"
                   @input="handleAddress($event);"
                   @change="updateUser($event);"
                   :class="{ 'is-invalid': !user.address }"
                   list="userAddressList"
                   :placeholder="$t('User.address_placeholder')"
            />
            <datalist id="userAddressList">
              <option v-for="address in address_search_results"
                      :value="address"
                      :key="address">
                {{ address }}
              </option>
            </datalist>
            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <label for="phone">{{ $t('User.phone') }}<sup class="red">*</sup></label>
                <div class="form-inline">
                  <vue-country-code id="phone_flag" className="form-control" class="col-2"
                                    :disabledFetchingCountry="true"
                                    :preferredCountries="['ee', 'fi', 'lt', 'lv']"
                                    @onSelect="setPhoneCode($event.dialCode)">
                  </vue-country-code>
                  <input type="tel"
                         class="form-control col-10"
                         id="phone"
                         :value="user.phone"
                         :class="{ 'is-invalid': !user.phone }"
                         autocomplete="tel"
                         @change="updateUser($event)"
                         ref='phone'
                  />
                </div>
              </div>
              <div class="col-xs-12 col-sm-6">
                <label for="email">{{ $t('User.email') }}<sup class="red">*</sup></label>
                <input type="email" class="form-control" id="email" :value="user.email" disabled>
              </div>
            </div>
          </div>
        </div>
        <div class="row p-3">
          <div class="col-md-12 col-lg-10 ">
            <div class="row pt-4" >
              <div class="col-sm-12 col-md-6 col-lg-4 p-2" style="border: dashed 1px dimgrey; background-color: #f3f3f3">
                <input ref="uploadForm"
                       type="file"
                       hidden
                       accept="image/*"
                       @change="uploadPhoto">
                <div v-if="user.avatar">
                  <img :src="user.avatar.length < 100 ? serverUrl + user.avatar : user.avatar" :alt="user.username" width="100%">
                </div>
                <div v-else class="text-center p-5 fa__box">
                  <i class="fa fa-image" style="font-size:4em; color:#c1c1c1;"></i>
                </div><!--bordered-->
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3 p-1 d-flex flex-column">
                <div class="mt-2">
                  <button class="m-2 btn btn-success col-12" @click.prevent="$refs.uploadForm.click()">
                    <i class="fa fa-cloud-upload-alt"></i>
                    {{ $t("User.upload_photo")}}
                  </button>
                </div>
                <div class="mt-auto">
                  <button class="btn m-2 btn-danger col-12" @click="updateUser({ target: { id: 'avatar', value: null } });">
                    <i class="fa fa-trash-alt"></i>
                    {{ $t("User.delete_photo")}}
                  </button>
                </div>
              </div><!--col-4-->
            </div><!--row-->
          </div>
        </div>
      </div>
    </div><!--card-body-->
    <div class="pt-2">
      <button class="btn float-right btn-outline-success" @click="saveUser">{{ $t('common.save') }}</button>
    </div>
  </div>
</template>

<script>
import dragNdropArea from '../../../mixins/dragNdropArea';

export default {
  name: 'UsSetAccountComponent',
  components: {},
  mixins: [ dragNdropArea ],
  data: () => ({
    keys: {
      profile_pic: 0
    },
    address_search_results: null,
    serverUrl: process.env.serverUrl,
  }),
  computed: {
    locale() {
      return this.$i18n.locales;
    },
    user() {
      return this.$store.state.users.userDetail.local_data;
    },
  },

  methods: {
    setPhoneCode(phone_code) {
      let phone_number = null;
      if(this.user.phone) {
        if (this.user.phone.indexOf(' ') !== -1) {
          phone_number = this.user.phone.split(' ').slice(1).join(' ').replace(/\+/g, '');
        } else {
          phone_number = this.user.phone.replace(/\+/g, '');
        }
        this.$store.commit('users/SET_OBJ', { name: 'userDetail.local_data.phone', value: `+${phone_code} ${phone_number}` });
        //this.$refs.phone.focus();
      }
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
      this.updateUser({ target: { id: 'address', value: data.target.value } })
    },

    uploadPhoto(event){
      const image = event.target.files[0];
      let reader = new FileReader();
      reader.onload = (e) => {
      //  this.updateUser({ target: { id: 'profile_pic_key', value: 0 } });
        this.updateUser({ target: { id: 'avatar', value: reader.result } });
     /*   this.keys.profile_pic += 1;
        this.updateUser({ target: { id: 'profile_pic_key', value: this.keys.profile_pic } });
     */ };
      reader.readAsDataURL(image);
    },

    /**
     * Wraps local file upload functionality
     * @param {Object[]} files list of files to interact handle
     */
    fileUploadHandler(files){
      this.uploadPhoto({ target: { files: files } });
    },

    switchLanguage(lang_id) {
      let locationg_code = this.$i18n.locales.find(loc => loc.id == lang_id).code
      this.$i18n.locale = locationg_code;
      this.$i18n.setLocaleCookie(locationg_code);
    },

    /**
     * Set data to store
     * @param {{ target: { id: string, value: object } }} data object that wraps values
     * @param data.target.id string representing a field to modify
     * @param data.target.value value to set
     */
    updateUser(data){
      this.$store.commit('users/SET_OBJ', { name: `userDetail.local_data[${data.target.id}]`, value: data.target.value })
    },

   async saveUser() {
      let user = {
        'firstname': this.user.firstname,
        'lastname': this.user.lastname,
        'langs_id': this.user.langs_id,
        'address': this.user.address,
        'phone': this.user.phone,
        'avatar': this.user.avatar, //?? null,
        'id': +this.$route.params.id
      };
      await this.$store.dispatch('users/updateUser', user).then(
        resp => {
          this.$auth.fetchUser()
          this.$toast.success(resp.msg).goAway(3000);
        }
      ).catch(err => {
        this.$toast.error(err.msg).goAway(3000);
      });
    },
  }
}
</script>

<style lang="scss" scoped>

</style>
