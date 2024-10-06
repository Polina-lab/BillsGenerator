<template>
  <div>
    <no-ssr>
    <Modal :title="title" size="modal-lg"
           @closeModal="closeFirmModal()"
           v-if="isFirmModalVisible">
      <div class="modal-body p-0 m-0">
        <ArrowsComponent :steps_amount="3"
                         :show_steps="true"
                         :style="arrow_styles"
                         :page="title === 'Create Company' ? selected_page + 1 : 4"
                         :step_list="pages_list"
                         :customTranslationGroup="'bills'"
                         @arrow_clicked="changeStage($event)">
        </ArrowsComponent>
          <div class="content">
            <div class="mt-1">
              <div class="row p-2">
                <div slot="button-prev"
                     class="p-0 justify-content-center
                            align-self-center pointer swiper-btn swiper-button-prev">
                  <i class="fas fa-chevron-left fa-2x makeBrighter p-2"></i>
                </div>
                <swiper @slideChange="slideChangeHandler"
                        class="swiper-container"
                        ref="firmSwiper"
                        :options="swiperOptions"
                        >
                  <swiper-slide>
                    <div class="tab-pane">
                    <div class="row">
                      <div class="col-12 col-sm-6">
                        <label for="name">{{ $t('bills.firm_name') }}<sup class="red" :title=" $t('bills.firm_name_need_be_required') ">*</sup></label>
                        <input type="text"
                               id="name"
                               class="form-control"
                               :value="firm.name"
                               :class="{ 'is-invalid': isFirmNameExists() }"
                               @input="updateFirm($event)"/>
                      </div>
                      <div class="col-12 col-sm-6">
                        <div class="row">
                          <div class="col-10">
                            <label for="representative_status">{{ $t("bills.representative_status") }}<sup class="red" :title=" $t('bills.representative_status_need_be_required') ">*</sup></label>
                            <span v-if="firm.representative_custom != null || isCustomRepresentativeStatus">
                              <input type="text" class="form-control"
                                     id="representative_custom"
                                     v-model="firm.representative_custom"/>
                            </span>
                            <span v-else>
                              <select id="representative_status" class="form-control"
                                      v-model="firm.representatives"  >
                                <option v-for="(rep_status, idx) in representative_statuses"
                                        :key="idx" :value="rep_status.id">
                                  {{ $t(`representative.${rep_status.name}`) }}
                                </option>
                              </select>
                            </span>
                          </div>
                          <div class="text-plus col-2 text-left pl-0">
                            <label for="add_custom_rep_button" :title="$t('bills.add_custom_representative_status_title')">{{ $t('bills.add_custom_representative_status') }}</label>
                            <i id="add_custom_rep_button"
                               class="fa btn-action"
                               :class="{
                                 'fa-plus': !isCustomRepresentativeStatus,
                                 'fa-times': isCustomRepresentativeStatus
                               }"
                               @click="
                                isCustomRepresentativeStatus = !isCustomRepresentativeStatus;
                                isCustomRepresentativeStatus ? firm.representatives : firm.representative_custom = null
                               ">
                            </i>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-6">
                        <label for="company_name">{{ $t('bills.company_name') }}
                          <i class="fa fa-info-circle p-1" v-tooltip="$t('info.second_firm_name')"></i>
                        </label>
                        <input type="text" id="company_name"  class="form-control" v-model="firm.company_name"/>

                        <label for="phone">{{ $t("bills.phone") }}</label>
                        <div class="form-inline">
                          <vue-country-code id="phone_flag" className="form-control" class="col-2" style=" "
                                            :disabledFetchingCountry="true"
                                            :preferredCountries="['ee', 'fi', 'lt', 'lv']"
                                            @onSelect="setPhoneCode($event.dialCode)">
                          </vue-country-code>
                          <input type="tel" id="phone" class="form-control col-10 bl0" v-model="firm.phone"/> 
                        </div>  



                          <label for="reg_num">{{ $t("bills.reg_num") }}<sup class="red" :title=" $t('bills.reg_num_need_be_required') ">*</sup></label>
                          <input type="text" id="reg_num" class="form-control" :class="{ 'is-invalid': (isCorrect && !firm.reg_num) }" v-model="firm.reg_num" />
                      </div>
                      <div class="col-12 col-sm-6">
                        <label for="representative_name">{{ $t("bills.representative_name") }}</label>
                        <input type="text" id="representative_name" class="form-control" v-model="firm.representative_name"/>

                        <label for="email">{{ $t("bills.email") }} <sup class="red">*</sup></label>
                        <input type="email" id="email" class="form-control" v-model="firm.email"
                         :class="{ 'is-invalid' : (!firm.email|| !(isEmailValid(firm.email))) }"/>
                        <label for="vat_reg_num">{{ $t("bills.km_reg_num")}}</label>
                        <input type="text" id="vat_reg_num" class="form-control"   v-model="firm.vat_reg_num"/>
                      </div>
                      <div class="col-12">
                        <label for="address">{{ $t('bills.address') }}</label>
                        <input type="text"
                               class="form-control"
                               :class="{ 'is-invalid': !firm.address }"
                               name="address"
                               id="address"
                               :value="firm.address"
                               @input="handleAddress($event);"
                               list="firmAddressList"
                               @change="firm.address = $event.target.value;"
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
                          <input type="checkbox" id="view_in_invoice" v-model="firm.view_in_invoice" class="m-2">
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
                          <div v-if="firm.logo">
                            <img :src="firm.logo.length < 100 ? serverUrl + firm.logo : firm.logo" alt="firm.name" width="100%">
                          </div>
                          <div v-else class="text-center p-5">
                            <i class="fa fa-image" style="font-size:4em; color:#c1c1c1;"></i>
                            <p style="color:dimgrey;">{{ $t("bills.drag_and_drop") }}</p>
                          </div>
                        </div>
                        </div>
                      </div><!--preview_form-->
                    </div><!--upload logo-->
                    </div>
                  </swiper-slide>
                  <swiper-slide>
                    <div class="tab-pane">
                      <div class="row">
                        <div class="col-10 col-sm-6">
                          <div class="row" v-for="(vat, idx) in firm.vat" :key="idx">
                            <div class="col-12">
                              <div class="form-inline">
                                <div class="col-8">
                                  <label for="vat" style="justify-content: left" ><sup class="red">*</sup>{{ $t("bills.KM") }}</label>
                                  <input type="number" id="vat" class="form-control" v-model="vat.vat"/>
                                </div>
                                <div class="col-2 align-items-start" >
                                  <label for="vat" style="justify-content: left">{{ $t('common.default') }}</label>
                                  <input type="radio" :id="`radio_vat_${idx}`" name="default_vat"
                                         @change="changeDefaultVat(idx)"
                                         :checked="vat.default"
                                         />
                                </div>
                                <div class="col-2" v-if="idx >= 1">
                                  <i class="fa fa-trash mt-3 p-2 red pointer" :title="$t('common.delete')" @click="deleteVat(idx)"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                          <div class="col-2">
                            <label for="add_vat" style="justify-content: left;margin-bottom:0">{{ $t('bills.add_vat') }}</label>
                              <i class="fa fa-plus btn-action" id="add_vat" @click="addNewVat" :title="$t('bills.add_vat')"></i>
                          </div>
                      </div><!--row-->
                      <hr/>

                      <PrintBankRequisite :banks="firm.banks" :isCorrect="isCorrect" class="p-2"></PrintBankRequisite>
                      <div class="form-inline float-right">
                        <input type="checkbox" class="form-control m-2"
                               id="requisites_visible" v-model="firm.requisites_visible"/>
                        <label for="requisites_visible">{{ $t('bills.requisites_visible') }}</label>
                      </div>
                    </div>
                  </swiper-slide>
                  <swiper-slide>
                    <div class="tab-pane">
                      <div class="p-2">
                        <label for="is_footer_visible">{{ $t("bills.is_footer_visible")}}</label>
                        <input type="checkbox" id="is_footer_visible" v-model="firm.is_footer_visible"/>
                        <label for="footer"></label>
                        <vue-editor style="width:100%;"
                                    v-model="firm.footer"
                                    :editor-toolbar="customToolbar"
                                    id="footer">
                        </vue-editor>
                      </div>
                    </div>
                  </swiper-slide>
                </swiper>
                <div slot="button-next"
                     class="p-0 justify-content-center
                            align-self-center pointer swiper-btn swiper-button-next">
                  <i class="fas fa-chevron-right fa-2x makeBrighter p-2"></i>
                </div>
              </div>
            </div>
          </div><!--content-->
        </div><!--modal-body-->
      <template v-slot:footer>
        <div v-if="firmList.length > 1">
          <button v-if="firm.id" type="button" class="btn btn-outline-danger"
                @click="isConfirmationModalVisible = true">{{ $t("bills.delete_firm") }}
          </button>
        </div>
        <div v-else-if="title === 'Create Company'"></div>
        <div v-else>
          <button type="button" class="btn btn-outline-danger button__notification"
                @click="$toast.error($t('bills.cant_delete_last_firm')).goAway(5000)">
            {{ $t("bills.delete_firm") }}
          </button>
          <div class="text__hover">{{ $t("bills.cant_delete_last_firm") }}</div>
          <i class="fas fa-share fa-2x icon__notification"></i>
        </div>
        <button type="button"
                class="btn btn-success"
                @click="checkRequiredField">
          <span v-if="firm.id">{{ $t("common.update") }}</span>
          <span v-else>{{ $t("common.create") }}</span>
        </button>
      </template>
    </Modal>
    <Modal :title="$t('common.delete')"
           @closeModal="isConfirmationModalVisible = $event;"
           v-if="isConfirmationModalVisible">
      <div class="alert alert-light">
        <p class="text-danger">{{ $t('firm.delete_firm_confirmation') }}</p>
      </div>
      <template v-slot:footer>
        <button type="button"
                class="btn btn-secondary"
                @click="isConfirmationModalVisible = false">
                {{ $t("common.cancel")}}
        </button>
        <button type="button" class="btn btn-danger"
          @click="deleteFirm(); isConfirmationModalVisible = false;">{{ $t("common.delete") }}
        </button>
      </template>
    </Modal>
    </no-ssr>
  </div>

</template>

<script>

import Modal from "@/components/common/Modal";
import dragNdropArea from '../../mixins/dragNdropArea';
import isEmailValid from '../../mixins/isEmailValid';
import ArrowsComponent from "@/components/elements/Arrows";
import PrintBankRequisite from "@/components/bills/common/printBankRequisite";
import { VAT } from '../../assets/js/models/Bill';

let VueEditor;
if (process.client) {
  VueEditor = require('vue2-editor').VueEditor;
}

export default {
  name: "FirmModalComponent",
  props: ["firm", "isFirmModalVisible", "title", "isCorrect"],
  components: { Modal, ArrowsComponent, VueEditor,  PrintBankRequisite },
  mixins: [ dragNdropArea, isEmailValid ],
  data() {
    return {
      pages_list: [{
        name: "general_data_tab",
        id: 1
      }, {
        name: "bank_data_tab",
        id: 2
      }, {
        name: 'footer_data_tab',
        id: 3
      }],
      address_search_results: [],
      phoneNumberMask: '+### ### ########',
      selected_page: 0,
      serverUrl: process.env.serverUrl,
      customToolbar: [
        ["bold", "italic", "underline"],
        [{list: "ordered"}, {list: "bullet"}],
      ],
      isCustomRepresentativeStatus: false,
      isFirmNameEdited: false,
      previous_firm_name: null,
      arrow_styles: {
        '--width': '100%',
        '--height': '40px',
        '--padding-inline-start': '0px'
      },
      swiperOptions: {
        autoHeight: true,
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        grabCursor: false,
        allowTouchMove: false,
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        navigation: {
          nextEl: '.fa-chevron-right',
          prevEl: '.fa-chevron-left'
        }
      },
      isConfirmationModalVisible: false
    }
  },


  destroyed() {
    this.selected_page = 0;
  },

  computed:{
    firmList() {
      return JSON.parse(JSON.stringify(this.$store.state.bills.firmsList))
    },
    /**
     * Returns list of objects of stored representative statuses
     * @returns {Array<{ id: Number, name: String}>} lisdt of representative statuses
     */
    representative_statuses(){
      return JSON.parse(JSON.stringify(this.$store.state.bills.representative_status_list));
    },
    isTouchEnabled() {
      if (process.client) {
          if (window.innerWidth < 749) {
            this.swiperOptions.grabCursor = true;
            this.swiperOptions.allowTouchMove = true;
            return true;
          } else {
            return false;
          }
        } else {
          return false;
        }
      }
  },

  methods:{
    setPhoneCode(phone_code) {
      if(!this.firm.phone.includes(phone_code)){
        this.firm.phone = '+'+ phone_code +' ';
      }
    },

    /**
     * Wraps address setting to store
     */
    async handleAddress(data) {
      let search_querry = data.target.value;
      if (search_querry.length < 3  || search_querry.length >= 10) {
        return;
      }
      let addresses = await this.$store.dispatch('users/fetchAddresses', search_querry);
      if (addresses === undefined) {
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
      this.firm.address = data.target.value;
    },

    deleteVat(idx) {
      if (this.firm.vat[idx].default) {
        this.firm.vat[0].default = true;
      }
      this.firm.vat.splice(idx, 1);
    },

    addNewVat(){
      this.firm.vat.push(new VAT());
    },

    changeDefaultVat(idx) {
      this.firm.vat.find(v => v.default == true).default = false;
      this.firm.vat[idx].default = true;
    },
    /**
     * Deletes currently open firm
     */
    async deleteFirm(){
      await this.$store.dispatch("bills/deleteFirm", this.firm.id )
        .then(
          resp => {
            this.closeFirmModal();
            this.$store.dispatch("bills/fetchFirmsList");
            this.$toast.success(resp.msg).goAway(3000);
          }).catch(error => { this.$toast.error(error.msg).goAway(3000); })
    },

    /**
     * Uploads logo on fired event and stores in local variable
     * @param {Object} event fired on file upload input change
     */
    uploadLogo(event){
      const images = event.target.files[0];
      this.firm.logo = null;
      let reader = new FileReader();
      reader.onload = (e) => {
        this.firm.logo = reader.result;
      };
      reader.readAsDataURL(images);
    },

    /**
     * @param e ?
     **/

    changeStage(e) {
      this.selected_page = e;
      this.$refs.firmSwiper.$swiper.slideToLoop(e, 500, false);
    },

    slideChangeHandler() {
      this.changeStage(this.$refs.firmSwiper.$swiper.realIndex);
    },

    /**
     * Wraps local file upload functionality
     * @param {Object[]} files list of files to interact handle
     */
    fileUploadHandler(files){
      this.uploadLogo({ target: { files: files } });
    },

    /**
     * Removes locally stored logo data
     */
    deleteLogo(){
      this.firm.logo = null;
    },

    /**
     * Saves currently opened firm
     */
    async saveFirm(){
      this.previous_firm_name = null;
      this.isFirmNameEdited = false;
      if (this.firm.id != null) {
        await this.$store.dispatch("bills/updateFirm", { id: this.firm.id, data: this.firm })
          .then(
            resp => {
              this.closeFirmModal();
              this.$toast.success(resp.msg).goAway(3000);
              this.$store.dispatch('bills/fetchFirmsList');
            },
            error => {
              this.$toast.error(error.msg).goAway(3000)
            })
      } else {
        await this.$store.dispatch('bills/createFirm', this.firm)
          .then(
            resp => {
              this.closeFirmModal();
              this.$toast.success(resp.msg).goAway(3000);
              this.$store.dispatch('bills/fetchFirmsList');
            },
            error => {
              //TODO Check data
              if(error.msg){
                this.$toast.error(error.msg).goAway(3000)
              }else
              this.$toast.error(error).goAway(3000)
            });
      }
    },

    /**
     * Emits events to close firm modal element
     */
    closeFirmModal(){
      this.selected_page = 0;
      this.$emit('closeFirmModal', false);
    },

    checkRequiredField(){
      if(this.firm.reg_num && this.firm.reg_num.length > 3 &&
         this.firm.email && this.firm.email.length > 3 &&
        this.firm.name && this.firm.name.length > 3){

         if(this.firm.banks[0] === 'undefined' && !this.firm.banks[0].bank_name && this.firm.banks[0].bank_name.length < 3 ){
           this.changeStage(1);
           this.$toast.error('Please fill in bank requisites form ').goAway(3000);
         }else {
           this.saveFirm();
           return true;
         }
      }else{
        this.$toast.error('Please fill in the data').goAway(3000);
        this.changeStage(0);
        return false;
      }
    },

    isFirmNameExists() {
      if (!this.isFirmNameEdited) {
        return false;
      }
      let firmList = JSON.parse(JSON.stringify(this.$store.state.bills.firmsList));
      for (let i = 0; i < firmList.length; i++) {
        if (firmList[i].name === this.firm.name ) {
          return true;
        }
      }
      return false;
    },

    updateFirm(data) {
      if (!this.isFirmNameEdited) {
        this.previous_firm_name = this.firm.name;
        this.isFirmNameEdited = true;
      }
      if (this.previous_firm_name === data.target.value) {
        this.isFirmNameEdited = false;
      }
      this.firm.name = data.target.value;
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~assets/scss/_var.scss';

#phone_flag {
  border-bottom-right-radius: 0;
  border-top-right-radius: 0;
}

.bl0{
  border-bottom-left-radius: 0;
  border-top-left-radius: 0;
}

.text__hover{
  display: none;
  background-color: #ffffff;
  text-align: center;
  z-index: 3000;
  border: 1px solid red;
  border-radius: 5px;
  margin-top: -115px;
  margin-left: 0;
  color: black;
  position: absolute;
  width: 220px;
  height: 50px;
}
.icon__notification{
  display: none;
  color: $logo-green;
  position: absolute;
  margin-top: -100px;
  margin-left: -33px;
  z-index: 9000;
}
.button__notification:hover + .text__hover + .icon__notification{
  display: block;
}
.button__notification:hover + .text__hover{
  display: block;
}
.container{
  position: absolute;
  display: flex;
  margin-top: calc(100%/2);
  z-index:2;
  margin-left: -16px;
  min-width:auto;
  justify-content: space-between;
  max-width: 100%;
}

.content{
  margin: -0px 20px 0px 20px;
}

input{
  width:inherit;
}

.text-plus label{
  opacity: 0;
}

@media (min-width: 308px) and (max-width: 774px){
  .text-plus label{
    opacity: 1;
  }
}

.is-invalid{
    border: 1px solid red;
  }

.swiper-btn::after {
  content: 'none';
}

// Source to button outside of swiper https://stackoverflow.com/a/63050848

.swiper-container {
  padding-left: 30px;
  padding-right: 30px;
}

</style>
