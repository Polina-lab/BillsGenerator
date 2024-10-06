<template>
  <div>
    <div class="card-title">
      <div class="d-flex justify-content-start button__block">
        <div class="row">
          <h2 id="button__text">{{ $t('bills.firm') }}</h2>
          <button v-if="!mainCompany" class="add__new">{{ $t('bills.add_new_firm') }}</button>
        </div>
      </div>
    </div>
    <div class="card-body pt-0">
      <div v-if="mainCompany" class="row p-2">
        <div class="col-10">
          <div class="row">
            <div class="col-12 col-sm-6">
              <label for="name">
                {{ $t('bills.firm_name') }}
                <sup class="red" :title=" $t('bills.firm_name_need_be_required')">*</sup>
              </label>
              <input type="text"
                     id="name"
                     class="form-control"
                     :value="mainCompany.name"
                     :class="{ 'is-invalid': isFirmNameExists() }"
                     @input="updateFirmName($event)"/>
            </div>
            <div class="col-12 col-sm-6">
              <div class="row">
                <div class="col-10">
                  <label for="representative_status">
                    {{ $t("bills.representative_status") }}
                    <sup class="red" :title=" $t('bills.representative_status_need_be_required')">*</sup>
                  </label>
                  <span v-if="mainCompany.representative_custom != null || isCustomRepresentativeStatus">
                    <input type="text" class="form-control"
                           id="representative_custom"
                           :value="mainCompany.representative_custom"
                           @change="updateFirm($event)"/>
                  </span>
                  <span v-else>
                    <select id="representative_status" class="form-control"
                            :value="mainCompany.representatives"
                            @change="updateFirm($event)">
                      <option v-for="(rep_status, idx) in representative_statuses"
                              :key="idx" :value="rep_status.id">
                        {{ $t(`representative.${rep_status.name}`) }}
                      </option>
                    </select>
                  </span>
                </div>
                <div class="text-plus col-2 text-left pl-0">
                  <label for="add_custom_rep_button" :title="$t('bills.add_custom_representative_status_title')">
                    {{ $t('bills.add_custom_representative_status') }}
                  </label>
                  <i id="add_custom_rep_button"
                     class="fa btn-action"
                     :class="{
                       'fa-plus': !isCustomRepresentativeStatus,
                       'fa-times': isCustomRepresentativeStatus
                     }"
                     @click="
                      isCustomRepresentativeStatus = !isCustomRepresentativeStatus;
                      updateFirm({ target: { id: 'representative_custom', value: null } })
                     ">
                  </i>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6">
              <label for="company_name">{{ $t('bills.company_name') }}</label>
              <input type="text" 
                     id="company_name" 
                     class="form-control" 
                     :value="mainCompany.company_name" 
                     @change="updateFirm($event)"/>
              <label for="email">{{ $t("bills.email") }}</label>
              <input type="email" id="email" class="form-control" :value="mainCompany.email"
               :class="{ 'is-invalid' : (!mainCompany.email|| !(isEmailValid(mainCompany.email))) }" 
               @change="updateFirm($event)"/>
              <label for="reg_num">
                {{ $t("bills.reg_num") }}
                <sup class="red" :title=" $t('bills.reg_num_need_be_required')">*</sup>
              </label>
              <input type="text" 
                     id="reg_num" 
                     class="form-control" 
                     :value="mainCompany.reg_num" 
                     @change="updateFirm($event)"/>
            </div>
            <div class="col-12 col-sm-6">
              <label for="representative_name">{{ $t("bills.representative_name") }}</label>
              <input type="text" 
                     id="representative_name" 
                     class="form-control" 
                     :value="mainCompany.representative_name" 
                     @change="updateFirm($event)"/>
              
              <label for="phone">{{ $t("bills.phone") }}</label>
              <input type="tel" id="phone" class="form-control" :value="mainCompany.phone" @change="updateFirm($event)"/>              
              <label for="km_reg_num">{{ $t("bills.km_reg_num")}}</label>
              <input type="text" 
                     id="km_reg_num" 
                     class="form-control" 
                     :value="mainCompany.km_reg_num" 
                     @change="updateFirm($event)"/>
            </div>
            <div class="col-12">
              <label for="address">{{ $t("bills.address") }}</label>
              <input type="text" 
                     id="address" 
                     class="form-control" 
                     :value="mainCompany.address"
                     @input="handleAddress($event)"
                     @change="updateFirm($event)"
                     :class="{ 'is-invalid': !mainCompany.address }"
                     list="userAddressList"
                     :placeholder="$t('User.address_placeholder')"/>
              <datalist id="userAddressList">
                <option v-for="address in address_search_results"
                        :value="address"
                        :key="address">
                  {{ address }}
                </option>
              </datalist>
            </div>
          </div>
          <div class="row" style="min-height: 160px" >
            <div class="col-12 col-sm-6 col-md-12 col-lg-12 col-xl-12">
              <div class="d-flex pt-3">
                <button class="btn btn-success col-5 mr-auto" @click.prevent="$refs.uploadForm.click()">
                  <i class="fa fa-cloud-upload-alt"></i>
                  {{ $t("bills.upload_logo")}}
                </button>
                <button class="btn btn-danger col-5 ml-auto" @click="deleteLogo">
                  <i class="fa fa-trash-alt"></i>
                  {{ $t("bills.delete_logo")}}
                </button>
              </div>
              <div class="d-flex pt-3">
                <input type="checkbox" 
                       id="view_in_invoice" 
                       :checked="mainCompany.view_in_invoice" 
                       class="m-2" 
                       @change="$event.target.value = $event.target.checked ? 1 : 0; updateFirm($event)">
                <label for="view_in_invoice" class="m-2">
                  {{ $t("bills.view_in_invoice") }}
                </label>
              </div>
            </div><!--col-6-->
            <div class="col-12 pt-3">
              <div id="dragndrop_area"
                   @dragover="dragover($event)"
                   @dragleave="dragleave($event)"
                   @drop="drop($event)">
              <div style="border: dashed 1px dimgrey; background-color: #f3f3f3 ">
                <input ref="uploadForm"
                       type="file"
                       hidden
                       accept="image/*"
                       @change="uploadLogo">
                <div v-if="mainCompany.logo">
                  <img :src="mainCompany.logo.length < 100 ? serverUrl + mainCompany.logo : mainCompany.logo" 
                       :alt="mainCompany.name" width="100%">
                </div>
                <div v-else class="text-center p-5">
                  <i class="fa fa-image" style="font-size:4em; color:#c1c1c1;"></i>
                  <p style="color:dimgrey;">{{ $t("bills.drag_and_drop") }}</p>
                </div>
              </div>
              </div>
            </div><!--preview_form-->
          </div><!--upload logo-->
          <hr/>
          <PrintBankRequisite :firm_id="mainCompany.id"
                              class="p-2">
          </PrintBankRequisite>
          <hr/>
          <AddVatComponent :vatData="mainCompany.vat" :from="'settings'" :addNew="true" :showAdd="true" 
                           @addNew="addNewVat"
                           @changeDefault="changeDefaultVat($event)"
                           @updateVat="updateVat($event.value, $event.idx)"
                           @delete="deleteVat($event)"/>
          <hr/>
          <div class="form-inline float-right">
            <input type="checkbox" 
                   class="form-control m-2" 
                   id="requisites_visible" 
                   :checked="mainCompany.requisites_visible" 
                   @change="$event.target.value = $event.target.checked ? 1 : 0; updateFirm($event)"/>
            <label for="requisites_visible" >{{ $t('bills.requisites_visible')}}</label>
          </div>
          <div class="p-2">
            <label for="is_footer_visible">{{ $t("bills.is_footer_visible")}}</label>
            <input type="checkbox" 
                   id="is_footer_visible" 
                   :checked="mainCompany.is_footer_visible" 
                   @change="$event.target.value = $event.target.checked ? 1 : 0; updateFirm($event)"/>
            <label for="footer"></label>
            <vue-editor style="width:100%;"
                        :value="mainCompany.footer"
                        :editor-toolbar="customToolbar"
                        id="footer"
                        @input="updateFirm({ target: { id: 'footer', value: $event } })">
            </vue-editor>
          </div>
        </div>
      </div>
    </div>
    <div class="pt-2">
      <button class="btn float-right btn-outline-success" @click="saveFirm">{{ $t('common.save') }}</button>
    </div>
  </div>
</template>

<script>
import { VAT } from '../../../assets/js/models/Bill';
import dragNdropArea from '../../../mixins/dragNdropArea';
import isEmailValid from '../../../mixins/isEmailValid';
import AddVatComponent from '../../bills/common/addVatComponent.vue';
import PrintBankRequisite from '../../bills/common/printBankRequisite.vue';

let VueEditor;
if (process.client) {
  VueEditor = require('vue2-editor').VueEditor;
}

export default {
  name: 'UsSetFirmComponent',
  components: { VueEditor, PrintBankRequisite, AddVatComponent },
  mixins: [ isEmailValid, dragNdropArea ], 
  data: () => ({
    isCustomRepresentativeStatus: false,
    isFirmNameEdited: false,
    previous_firm_name: null,
    serverUrl: process.env.serverUrl,
    customToolbar: [
      ["bold", "italic", "underline"],
      [{list: "ordered"}, {list: "bullet"}],
    ],
    address_search_results: []
  }),

  computed: {
    mainCompany() {
      return this.$store.state.bills.firmsList.find(firm => firm.main_firm == 1);
    },

    mainCompanyIndex() {
      return this.$store.state.bills.firmsList.map(firm => firm.id).indexOf(this.mainCompany.id);
    },

    representative_statuses(){
      return JSON.parse(JSON.stringify(this.$store.state.bills.representative_status_list));
    }
  },

  methods: {
    isFirmNameExists() {
      if (!this.isFirmNameEdited) {
        return false;
      }
      let firmList = JSON.parse(JSON.stringify(this.$store.state.bills.firmsList));
      for (let i = 0; i < firmList.length; i++) {
        if (i != this.mainCompanyIndex && firmList[i].name === this.mainCompany.name) {
          return true;
        }
      }
      return false;
    },

    /**
     * Uploads logo on fired event and stores in local variable
     * @param {Object} event fired on file upload input change
     */
    uploadLogo(event) {
      const images = event.target.files[0];
      this.mainCompany.logo = null;
      let reader = new FileReader();
      reader.onload = (e) => {
        this.mainCompany.logo = reader.result;
      };
      reader.readAsDataURL(images);
    },

    /**
     * Wraps local file upload functionality
     * @param {Object[]} files list of files to interact handle
     */
    fileUploadHandler(files) {
      this.uploadLogo({ target: { files: files } });
    },

    /**
     * Removes locally stored logo data
     */
    deleteLogo() {
      this.mainCompany.logo = null;
    },

    /**
     * Saves currently opened firm
     */
    async saveFirm() {
      this.previous_firm_name = null;
      this.isFirmNameEdited = false;
      await this.$store.dispatch("bills/updateFirm", { id: this.mainCompany.id, data: this.mainCompany })
        .then(
          resp => {
            this.$toast.success(resp.msg).goAway(3000);
            this.$store.dispatch('bills/fetchFirmsList');
          },
          error => {
            this.$toast.error(error.msg).goAway(3000)
          });
    },

    /**
     * Updates stored data given data to update with
     * @param {{ target: { id: string, value: object } }} data object that wraps values
     * @param data.target.id string representing a field to modify
     * @param data.target.value value to set
     */
    updateFirm(data) {
      this.$store.commit('bills/SET_OBJ', { name: `firmsList[${this.mainCompanyIndex}][${data.target.id}]`, value: data.target.value });
    },

    updateFirmName(data) {
      if (!this.isFirmNameEdited) {
        this.previous_firm_name = this.mainCompany.name;
        this.isFirmNameEdited = true;
      }
      if (this.previous_firm_name === data.target.value) {
        this.isFirmNameEdited = false;
      }
      this.updateFirm({ target: { id: 'name', value: data.target.value } })
    },

    /**
     * Wraps address setting to store
     * @param {string} address address to handle
     */
    async handleAddress(data) {
      let search_querry = data.target.value;
      console.log(search_querry);
      if (search_querry.length < 3) {
        return;
      }
      let addresses = await this.$store.dispatch('users/fetchAddresses', search_querry);
      if (addresses == undefined) {
        this.address_search_results = [];
      } else {
        this.address_search_results = addresses.map(address => address.ipikkaadress);
      }
      this.updateFirm(data);
    },

    addNewVat() {
      this.$store.commit('bills/ADD_ITEM_ARRAY_BOTTOM', {
        name: `firmsList[${this.mainCompanyIndex}].vat`, 'value': new VAT()
      });
    },

    deleteVat(idx) {
      if (this.mainCompany.vat[idx].default) {
        this.$store.commit('bills/SET_OBJ', { 
          name: `firmsList[${this.mainCompanyIndex}].vat[0].default`, value: true });      
      }
      this.$store.commit('bills/DELETE_ITEM_ARRAY', { 
        name: `firmsList[${this.mainCompanyIndex}].vat`,  value: idx, elementReference: 'idx' });
    },

    changeDefaultVat(idx) {
      let currentDefaultID = this.mainCompany.vat.find(v => v.default == true).id;
      let currentDefaultIndex = this.mainCompany.vat.map(v => v.id).indexOf(currentDefaultID);
      this.$store.commit('bills/SET_OBJ', { 
        name: `firmsList[${this.mainCompanyIndex}].vat[${currentDefaultIndex}].default`, value: false });
      this.$store.commit('users/SET_OBJ', { 
        name: `firmsList[${this.mainCompanyIndex}].vat[${idx}].default`, value: true });      
    },

    updateVat(vat_value, idx) {
      this.$store.commit('bills/SET_OBJ', { 
        name: `firmsList[${this.mainCompanyIndex}].vat[${idx}].vat`, value: +vat_value });      
    }
  }
}
</script>

<style lang="scss" scoped>

@import "./assets/scss/var";

.add__new{
  width: 160px;
  margin-left: 60px;
  background-color: #ffffff;
  border: 2px solid $secondary-color;
  border-radius: 5px;
  height: 40px;
}
</style>