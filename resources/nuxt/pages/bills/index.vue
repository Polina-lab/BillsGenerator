<template>
  <section>
    <div v-if="$auth.user.role && $auth.user.role.name === 'superAdmin'"  class="notification__size">
      <Notification :unpaid="unpaid" @closeNotification="closeNotification"/>
    </div>

    <div v-if="$auth.user.role && $auth.user.role.name === 'superAdmin' && Object.keys(this.$store.state.current_team).length > 0" class="statistic__size"    >
      <BillsStatistic />
    </div>
    <!-- #region Uppersection -->
    <!--<div class="col-sm-8 col-lg-12">-->
    <div class="col-12" v-if="!hasMybills">
      <div class="container-fluid">
        <div class="row p-1">
          <div class="col-12 col-sm-6  col-md-4 col-lg-4">
            <div class="d-flex justify-content-center" >
              <div class="row align-items-end align-content-center">
              <div class="col-2 p-1 text-center align-bottom">
                <img src="/icons/left_arrow.svg" alt="Left"  class="arrow m-1 pointer" :title="$t('bills.month')" @click="selectMonth('previous')" >
              </div>
              <div class="col-4 p-1">
                <label for="month">{{ $t("bills.month")}}</label>
                <select name="month" id="month" class="form-control" v-model="filterBills.month" @change="billsFilter">
                  <option :value="null">All</option>
                  <option v-for="id in 12" :value="id" :key="id">{{id--}}</option>
                </select>
              </div>
              <div class="col-4 p-1" >
                <label for="year">{{ $t("bills.year")}}</label>
                <select name="year" id="year" class="form-control" v-model="filterBills.year"  @change="billsFilter">
                  <option :value="null">All</option>
                  <option v-for="y in years" :value="y" :key="y">{{ y }}</option>
                </select>
              </div>
              <div class="col-2  p-1  text-center align-bottom">
                <img src="/icons/right_arrow.svg" alt="Left" class="arrow m-1 pointer"  :title="$t('bills.month')" @click="selectMonth('next')"/>
              </div>
            </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-4">
            <div class="d-flex justify-content-center">
                <div class="row align-items-end align-content-center">
                  <div class="col-8 p-1  align-bottom">
                <label for="firm">{{ $t("bills.firm")}}*</label>
                <select v-model="filterBills.firm2" class="form-control" id="firm" @change="billsFilter">
                  <option v-for="fi in firmList" :key="fi.id" :value="fi.id">
                    {{ fi.name }}
                  </option>
                </select>
                <span v-if="checkfirm">
                  <i style="color: red">{{ $t("bills.select_firm")}}</i>
                </span>
              </div>
                  <div class="col-2 p-1 text-center align-bottom">
                    <img src="/icons/edit.svg"
                         :title="$t('bills.edit_firm')"
                         @click="editFirm" style="width: 40px;"
                         class="p-1 pointer"
                         :class="{
                        '': isElementInteractible('edit-firm'),
                        'zeroOpacity': !isElementInteractible('edit-firm')}"
                         :disabled="!isElementInteractible('edit-firm')" alt="Edit Firm ">
                  </div>
                  <div class="col-2 p-1  text-center align-bottom">
                      <img src="/icons/plus_green.svg" :title="$t('bills.add_new_firm')" alt="add"
                           style="width: 35px;"
                           @click="setNewFirm"
                           class="p-1 pointer"
                         :class="{
                        '': isElementInteractible('add-new-firm'),
                        'zeroOpacity': !isElementInteractible('add-new-firm')
                        }"
                         :disabled="!isElementInteractible('add-new-firm')"/>
                  </div>
              </div>
          </div>
          </div>
          <div class="col-sm-12 col-md-4 col-lg-4 mt-4 align-self-start">
            <div class="d-flex justify-content-sm-between justify-content-md-between justify-content-lg-start ">
              <button class="btn btn-primary m-lg-2 m-md-1  m-sm-2 mx-auto"
                @click="addItem" :disabled="!isElementInteractible('create-bill')">
                {{ $t("bills.add_new_bill") }}
              </button>
              <button class="btn btn-success m-lg-2 m-md-1  m-sm-2 mx-auto"
                @click="createEventBill" :disabled="!isElementInteractible('create-full-bill')">
                {{ $t("bills.create_new_invoice") }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
   <!-- <div class="d-sm-block d-md-none d-lg-none d-xl-none" >
      <select name="firm" class="form-control" v-model="filterBills.firm" @change="billsFilter">
        <option v-for="fi in firmList" :key="fi.id" :value="fi.id">
          {{ fi.name }}
        </option>
      </select>
      <span v-if="checkfirm">
        <i style="color: red" :value="null">Filter: {{ $t("bills.select_firm")}}</i>
      </span>
    </div>  -->
    <!-- #endregion Uppersection -->
      <div class="table-responsive-sm tableFixHead" ref="table">
      <table class="table mt-1 table-hover">
        <!-- #region TableSearch -->
        <thead>
        <tr>
          <th scope="col" class="p-1">
            <div class="input-group">
              <input type="text"
                     class="form-control border-right-0" size="1"
                     v-model="filterBills.invoice" @change="billsFilter" placeholder="№">
              <div class="bg-white border-icon sort-arrow-container">
                <div class="sort-arrow"
                     @click="setOrder('invoice', 'ASC')">
                  <i class="fas fa-sort-up sort-arrow"
                     :class="switchIcon('invoice', 'ASC')"></i>
                </div>
                <div class="sort-arrow"
                     @click="setOrder('invoice', 'DESC')">
                  <i class="fas fa-sort-down sort-arrow"
                     :class="switchIcon('invoice', 'DESC')"></i>
                </div>
              </div>
            </div>
          </th>
          <th scope="col" v-if="!hasMybills" class="p-1" style="min-width: 200px; max-width: 320px;">
              <input type="text" class="form-control border-right-0"
                     v-model="filterBills.name" @change="billsFilter" :placeholder="$t('bills.order_name')">
          </th>

          <th scope="col" v-if="!hasMybills && clientsList.length > 0" class="text-center mw220 p-1" style="min-width: 210px; max-width: 220px;" >
            <div class="input-group text-center" >
              <select name="client" class="form-control border-right-0 "  v-model="filterBills.client" @change="billsFilter">
                <option :value="null">{{ $t('bills.select_client')}}</option>
                <option v-for="client in clientsList" :value="client.id" :key="client.id" @change="billsFilter">{{ client.username }}</option>
              </select>
              <div class="bg-white border-icon sort-arrow-container">
                <div class="sort-arrow"
                     @click="setOrder('client_id', 'ASC')">
                  <i class="fas fa-sort-up sort-arrow"
                     :class="switchIcon('client_id', 'ASC')"></i>
                </div>
                <div class="sort-arrow"
                     @click="setOrder('client_id', 'DESC')">
                  <i class="fas fa-sort-down sort-arrow"
                     :class="switchIcon('client_id', 'DESC')"></i>
                </div>
              </div>
            </div>
          </th>

          <th scope="col" v-if="hasMybills" class="p-1 align-middle text-center text-white">
            {{ $t('bills.payment_plan')}}
          </th>
          <th scope="col" v-if="!hasMybills" class="text-center mw220 p-1" style=" min-width:210px; max-width: 220px" >
            <div class="input-group text-center">
              <select name="user_id" class="form-control border-right-0" v-model="filterBills.user" @change="billsFilter">
                <option :value="null">{{ $t('bills.select_user')}}</option>
                <option v-for="user in userList" :value="user.id" :key="user.id" v-if="user.firstname !== null">{{ user.username }}</option>
              </select>
              <div class="bg-white border-icon sort-arrow-container">
                <div class="sort-arrow"
                     @click="setOrder('user_id', 'ASC')">
                  <i class="fas fa-sort-up sort-arrow"
                     :class="switchIcon('user_id', 'ASC')"></i>
                </div>
                <div class="sort-arrow"
                     @click="setOrder('user_id', 'DESC')">
                  <i class="fas fa-sort-down sort-arrow"
                     :class="switchIcon('user_id', 'DESC')"></i>
                </div>
              </div>
            </div>
          </th>
          <th scope="col" class="p-1">
            <div class="input-group" style="min-width: 180px; max-width:180px;">
              <select name="firm" class="form-control border-right-0" v-model="filterBills.firm" @change="billsFilter">
                <option :value="null">{{ $t('bills.select_firm')}}</option>
                <option v-for="fi in firmList" :key="fi.id" :value="fi.id">
                  {{ fi.name }}
                </option>
              </select>
              <div class="bg-white border-icon sort-arrow-container">
                <div class="sort-arrow"
                     @click="setOrder('firm_id', 'ASC')">
                  <i class="fas fa-sort-up sort-arrow"
                     :class="switchIcon('firm_id', 'ASC')"></i>
                </div>
                <div class="sort-arrow"
                     @click="setOrder('firm_id', 'DESC')">
                  <i class="fas fa-sort-down sort-arrow"
                     :class="switchIcon('firm_id', 'DESC')"></i>
                </div>
              </div>
            </div>
          </th>
          <th scope="col" class="p-1" >
            <div class="input-group text-center" style="min-width: 145px; max-width:150px;">
              <no-ssr>
                <v-datepicker id="date"
                              class="select__date br-none"
                              value-type="format"
                              v-model="filterBills.date"
                              type="date"
                              :lang="lang"
                              :placeholder="$t('bills.select_date')"
                              @change="billsFilterDate($event) ">
                </v-datepicker>
              </no-ssr>
                <div class="bg-white border-icon sort-arrow-container">
                  <div class="sort-arrow"
                       @click="setOrder('date', 'ASC')">
                    <i class="fas fa-sort-up sort-arrow"
                       :class="switchIcon('date', 'ASC')"></i>
                  </div>
                  <div class="sort-arrow"
                       @click="setOrder('date', 'DESC')">
                    <i class="fas fa-sort-down sort-arrow"
                       :class="switchIcon('date', 'DESC')"></i>
                  </div>
                </div>
            </div>
          </th>
          <th scope="col" class="p-1" style="min-width: 180px;max-width:200px;">
            <div class="input-group" style="min-width: 180px;max-width:200px;">
              <input type="number"
                     class="form-control"
                     v-model="filterBills.price"
                     @change="billsFilter"
                     size="5"
                     :placeholder="$t('bills.price')"
                     style="min-width: 100px; max-width:170px;">
              <div class="bg-white border-icon sort-arrow-container">
                <div class="sort-arrow"
                     @click="setOrder('price', 'ASC')">
                  <i class="fas fa-sort-up sort-arrow"
                     :class="switchIcon('price', 'ASC')"></i>
                </div>
                <div class="sort-arrow"
                     @click="setOrder('price', 'DESC')">
                  <i class="fas fa-sort-down sort-arrow"
                     :class="switchIcon('price', 'DESC')"></i>
                </div>
              </div>
            </div>
          </th>
          <th scope="col" v-if="!hasMybills" class="text-center p-1" style="min-width: 100px; max-width:180px;">
            <select name="deal" class="form-control" v-model="filterBills.deal" @change="billsFilter">
              <option :value="null">{{ $t("bills.select_deal") }}</option>
              <option v-for="de in deal" :key="de.id" :value="de.id">
                {{ de.name }}
              </option>
            </select>
          </th>
          <th scope="col" class="p-1" id="bills__paid">
            <div class="input-group">
              <span class="text-white p-2">{{ $t("bills.paid") }}</span>
              <span class="sort-arrow-container pl-2">
                <div class="sort-arrow"
                      @click="setOrder('was_paid', 'ASC')">
                  <i class="fas fa-sort-up sort-arrow"
                      :class="switchIcon('was_paid', 'ASC')"></i>
                </div>
                <div class="sort-arrow"
                      @click="setOrder('was_paid', 'DESC')">
                  <i class="fas fa-sort-down sort-arrow"
                      :class="switchIcon('was_paid', 'DESC')"></i>
                </div>
              </span>
            </div>
          </th>
          <th scope="col" colspan="3" class="p-1 align-middle text-center text-white">
            {{ $t("common.actions")}}
          </th>
        </tr>
        </thead>
        <!-- #endregion TableSearch -->
        <tbody>

          <template v-for="(item, idx) in billsList" >
            <tr :key="idx" :class="changeColor(item)">
                <td class="p-1 text-center align-middle mobile__header"
                      style="max-width: 100px; width: 100px;"
                      :class="{
                      'showAshref' : !(hasInputById == item.id)&&
                        isElementInteractible('view-bill-details', item.act_user_id),
                      'pointer': !(hasInputById == item.id)&&
                        isElementInteractible('view-bill-details', item.act_user_id)
                      }"
                      @click="!(hasInputById == item.id)&&
                      isElementInteractible('view-bill-details', item.act_user_id)&&
                      showPdfByClick(item.id)">
                  <span v-if="hasInputById === item.id">
                    <input :value="item.number ? item.number : 1 "
                            class="form-control " min="0" size="2" disabled>
                  </span>
                  <span id="find__number" v-else>
                        <b  class="p-1" :title="$t('bills.preview')">{{ item.invoice }}</b>
                  </span>

                  <div v-if="widthOfScreen || showItem != item.id" class="mobile">
                    <div v-if="hasInputById === item.id" class="d-inline-flex">
                      <no-ssr>
                        <v-datepicker type="date"
                                      class="select__date__input"
                                      :lang="lang"
                                      value-type="format"
                                      v-model="item.date"
                                      @change="updateBillsDate(idx, $event)">
                        </v-datepicker>
                      </no-ssr>
                    </div>
                    <div  v-else class="d-inline-flex">{{ dateFormat(item.date) }}</div>
                  </div>


                  <div v-if="widthOfScreen || showItem != item.id" class=" text-center align-middle mobile">
                    <span v-if="item.was_paid === null" class="align-middle d-inline-flex">
                      <button
                        v-if="item.was_paid === null"
                        class="btn btn-warning"
                        @click="itemModal = item.id; confirmationModalAction = 'pay'"
                        :disabled="(!isElementInteractible('mark-bill-paid'))||(hasInputById === item.id)">
                        {{ $t("bills.paid")}}
                      </button>
                  </span>
                  <span v-else-if="hasInputById === item.id" class="d-inline-flex">
                        <no-ssr>
                          <v-datepicker
                                        type="date"
                                        style="max-width: 90px;"
                                        value-type="format"
                                        :lang="lang"
                                        v-model="item.was_paid"
                                        @change="updateBillsWasPaid(idx, $event)">
                        </v-datepicker>
                     </no-ssr>
                  </span>
                  <span v-else class="unpayButtonRegion">
                        <span class="paidDate d-inline-flex">
                          {{ dateFormat(item.was_paid) }}
                        </span>
                        <span class="unpayButton">
                          <button
                            class="btn btn-warning p-0"
                            @click="itemModal = item.id; confirmationModalAction = 'unpay'"
                            :disabled="(!isElementInteractible('mark-bill-paid'))||(hasInputById === item.id)">
                            {{ $t("bills.unpay")}}
                          </button>
                        </span>
                      </span>
                </div>


                  <div class="mobile">
                    <div class="d-inline-flex" v-if="!hasMybills">
                        <i v-if="hasInputById === item.id" @click="saveObj(item)"
                          :title="$t('common.save')" class="fa fa-check-square pointer"
                          style="color:green; font-size: 2em; padding-right: 12px;"></i>
                        <i v-if="hasInputById === item.id" @click="CancelBtn"
                          :title="$t('common.cancel')" class="fa fa-window-close pointer"
                          style="color:yellow; font-size: 2em;"></i>
                      <img
                        src="/icons/download_blue.svg"
                        v-if="hasInputById !== item.id"
                        alt="download"
                        :title="$t('bills.download')"
                        @click="isElementInteractible('download-bill', item.act_user_id)&&download(item.id)"
                        :class="{
                            'zeroOpacity': !isElementInteractible('download-bill', item.act_user_id),
                            'pointer': isElementInteractible('download-bill', item.act_user_id)
                            }"
                        style="padding-right: 10px; width: 35px;"/>
                      <img
                        alt="edit"
                        v-if="hasInputById !== item.id"
                        src="/icons/edit_color.svg"
                        :title="$t('bills.edit')"
                        @click="isElementInteractible('edit-bill', item.act_user_id)&&(hasInputById = item.id)"
                        :class="{
                            'zeroOpacity': !isElementInteractible('edit-bill', item.act_user_id),
                            'pointer': isElementInteractible('edit-bill', item.act_user_id)
                            }"
                        style="padding-right: 10px; width: 40px;"/>

                      <img
                        alt="clone"
                        v-if="hasInputById !== item.id"
                        src="/icons/copy_green.svg"
                        @click="isElementInteractible('clone-bill', item.act_user_id)&&
                            (itemModal = item.id)&&
                            (confirmationModalAction = 'clone')"
                        :title="$t('bills.clone')"
                        :class="{
                            'zeroOpacity': !isElementInteractible('clone-bill', item.act_user_id),
                            'pointer': isElementInteractible('clone-bill', item.act_user_id)
                            }"
                        style="padding-right: 10px; width: 35px;" />
                      <img
                        alt="delete"
                        v-if="hasInputById !== item.id"
                        src="/icons/delete_red.svg"
                        @click="isElementInteractible('delete-bill', item.act_user_id)&&
                            (itemModal = item.id)&&
                            (confirmationModalAction = 'delete')"
                        :title="$t('common.delete')"
                        :class="{
                            'zeroOpacity': !isElementInteractible('delete-bill', item.act_user_id),
                            'pointer': isElementInteractible('delete-bill', item.act_user_id)
                            }"
                        style="width:25px;">
                    </div>
                    <div v-else>
                    <img src="/icons/download_blue.svg"
                      alt="download"
                      :title="$t('bills.download')"
                      class="pointer"
                      style="padding-right: 10px; width: 35px;"/>
                    </div>
                  </div>

                </td>
                <td v-if="widthOfScreen || showItem == item.id" :data-label="$t('bills.order_name')" class="p-1 align-middle open">
                <div class="p-0" id="order__name" style="max-width: 350px;">
                   <slot v-if="hasInputById !== item.id" class="p-0">
                        {{ item.orders[0] ? item.orders[0].name :  "---" }}
                   </slot>
                  <slot v-else class="p-0">
                    <center>Add new order here 👇</center>
                  </slot>
                </div>
                </td>
                <td v-if="(widthOfScreen || showItem == item.id) && (!hasMybills && clientsList.length > 0)" class="align-middle p-1 open" :data-label="$t('bills.select_client')">
                  <div  id="cell__client" class="p-1 align-middle mw200" style="width:220px">
                    <slot v-if="hasInputById === item.id" >
                          <select class="form-control" style="width:200px;"  v-model="item.client_id" id="client_id" @change="updateBills(idx ,$event)">
                            <option v-for="(client , index )  in clientsList"
                                    v-model="item.client_id"
                                    :selected="client.id === item.client_id"
                                    :value="client.id"
                                    :key="'client'+index">
                                {{ client.username}}
                            </option>
                          </select>
                        </slot>
                      <slot v-else>
                        {{ item.client ? item.client.username : '---'}}
                      </slot>
                  </div>
                </td>
                <td v-if="widthOfScreen || showItem == item.id" :data-label="$t('bills.select_user')" class="align-middle text-center p-1 user__td open">
                  <div v-if="!hasMybills" id="cell__user" class="p-0 align-middle mw200" style="width:200px;" >
                      <span v-if="hasInputById === item.id" >
                        <select class="form-control mw200" id="act_user_id" v-model="item.act_user_id" @change="updateBills(idx ,$event)">
                          <option v-for="(user , index) in userList"
                                  :value="user.id"
                                  class="p-0"
                                  :selected="user.id === item.act_user_id"
                                  :key="'user' + index">
                            {{ user.username }}
                          </option>
                        </select>
                      </span>
                    <span id="find__user" v-else-if="typeof userList.find(e => e.id === item.act_user_id ) === 'object'" class="p-0">
                          {{ userList.find(e => e.id === item.act_user_id ).username }}
                        </span>
                    <span id="find" v-else class="p-0">
                          --
                        </span>
                  </div>
                </td>
                <td v-if="widthOfScreen || showItem == item.id" :data-label="$t('bills.firm')" class="text-center align-middle p-1 open">
                  <span v-if="item.firms" id="cell__firm">{{ item.firms.name}}</span>
                  <span v-else>--</span>
                </td>
                <td v-if="widthOfScreen || showItem == item.id" :data-label="$t('bills.select_date')" class="align-middle p-1 data__td open">
                  <div v-if="hasInputById === item.id" class="data__middle" style="width:135px;">
                    <no-ssr>
                      <v-datepicker type="date"
                                    class="select__date__input p-1"
                                    :lang="lang"
                                    value-type="format"
                                    v-model="item.date"
                                    @change="updateBillsDate(idx, $event)">
                      </v-datepicker>
                    </no-ssr>
                  </div>
                  <div  v-else class="data__middle" style="width:135px;">{{ dateFormat(item.date) }}</div>
                </td>
                <td v-if="widthOfScreen || showItem == item.id" :data-label="$t('bills.price')"  class="align-middle p-1" id="cell__price">
                  <span v-if="item.orders.length > 1 && item.orders[0].amount  && (item.orders[0].amount != 1)"  style="width: 180px">
                        <span v-if="isElementInteractible('view-bill-price', $auth.user.local_data.id)">
                         <!--TODO: has_KM need be integer -->
                          {{ item.price }} <span v-if="item.vat_id !== null && item.vat_value != 0 " v-tooltip="item.price_no_km">with Vat({{ item.vat_value}} %)</span>
                        </span>
                        <span v-else> 0.00 </span>
                      </span>
                  <span v-else style="width: 180px">
                        <span v-if="isElementInteractible('view-bill-price', $auth.user.local_data.id)">
                          {{ item.price }} <span v-if="item.vat_id !== null && item.vat_value != 0" v-tooltip="item.price_no_km"> with Vat({{ item.vat_value }} %)</span>
                        </span>
                        <span v-else>0.00</span>
                  </span>
                </td>
                <td v-if="widthOfScreen || showItem == item.id" :data-label="$t('bills.deal')" class=" align-middle p-1 deal__td">
                <div v-if="!hasMybills" class="align-middle" style="width:150px;" >
                      <span class="mw150" v-if="hasInputById === item.id">
                        <select id="deal" v-model="item.deal" class="form-control" @change="updateBills(idx, $event)">
                          <option :value="null">{{ $t('bills.select_deal')}}</option>
                          <option v-for="(de, index) in deal" :key="'deal' + index" :value="de.id">
                            {{ de.name }}
                          </option>
                        </select>
                      </span>
                  <span class="mw150" id="find__deal" v-else >
                        <span v-if="typeof deal.find( i  => i.id === item.deal)  === 'object'">
                          {{deal.find( i  => i.id === item.deal).name }}
                        </span> <span v-else>select deal</span>
                  </span>
                </div>
                </td>
                <td v-if="widthOfScreen || showItem == item.id" class="text-center align-middle p-1 buttons__paid">
                  <button
                    v-if="item.was_paid === null"
                    class="btn btn-warning"
                    @click="itemModal = item.id; confirmationModalAction = 'pay'"
                    :disabled="(!isElementInteractible('mark-bill-paid'))||(hasInputById === item.id)">
                    {{ $t("bills.paid")}}
                  </button>
                  <span v-else-if="hasInputById === item.id">
                        <no-ssr>
                          <v-datepicker
                                        type="date"
                                        style="max-width: 90px;"
                                        value-type="format"
                                        :lang="lang"
                                        v-model="item.was_paid"
                                        @change="updateBillsWasPaid(idx, $event)">
                        </v-datepicker>
                     </no-ssr>
                  </span>
                  <span v-else class="unpayButtonRegion">
                        <span class="paidDate">
                          {{ dateFormat(item.was_paid) }}
                        </span>
                        <span class="unpayButton">
                          <button
                            id="was_paid"
                            class="btn btn-warning p-0"
                            @click="itemModal = item.id; confirmationModalAction = 'unpay'"
                            :disabled="(!isElementInteractible('mark-bill-paid'))||(hasInputById === item.id)">
                            {{ $t("bills.unpay")}}
                          </button>
                        </span>
                      </span>
                </td>
                <!-- #region BillActions -->
                <td colspan="3" class="p-1 text-center align-middle bill__actions" >
                  <div class="d-inline-flex" v-if="!hasMybills">
                      <i v-if="hasInputById === item.id" @click="saveObj(item)"
                         :title="$t('common.save')" class="fa fa-check-square pointer"
                         style="color:green; font-size: 2em; padding-right: 12px;"></i>
                      <i v-if="hasInputById === item.id" @click="CancelBtn"
                         :title="$t('common.cancel')" class="fa fa-window-close pointer"
                         style="color:yellow; font-size: 2em;"></i>
                    <img
                      src="/icons/download_blue.svg"
                      v-if="hasInputById !== item.id"
                      id="lower__fa-download"
                      alt="download"
                      :title="$t('bills.download')"
                      @click="isElementInteractible('download-bill', item.act_user_id)&&download(item.id)"
                      :class="{
                          'zeroOpacity': !isElementInteractible('download-bill', item.act_user_id),
                          'pointer': isElementInteractible('download-bill', item.act_user_id)
                          }"
                      style="padding-right: 10px; width: 35px;"/>
                    <img
                      alt="edit"
                      v-if="hasInputById !== item.id"
                      src="/icons/edit_color.svg"
                      id="lower__fa-edit"
                      :title="$t('bills.edit')"
                      @click="isElementInteractible('edit-bill', item.act_user_id)&& editOrderBill(idx, item)"
                      :class="{
                          'zeroOpacity': !isElementInteractible('edit-bill', item.act_user_id),
                          'pointer': isElementInteractible('edit-bill', item.act_user_id)
                          }"
                      style="padding-right: 10px; width: 40px;"/>

                    <img
                      alt="clone"
                      v-if="hasInputById !== item.id"
                      src="/icons/copy_green.svg"
                      @click="isElementInteractible('clone-bill', item.act_user_id)&&
                          (itemModal = item.id)&&
                          (confirmationModalAction = 'clone')"
                      :title="$t('bills.clone')"
                      :class="{
                          'zeroOpacity': !isElementInteractible('clone-bill', item.act_user_id),
                          'pointer': isElementInteractible('clone-bill', item.act_user_id)
                          }"
                      style="padding-right: 10px; width: 35px;" />
                    <img
                      alt="delete"
                      v-if="hasInputById !== item.id"
                      src="/icons/delete_red.svg"
                      @click="isElementInteractible('delete-bill', item.act_user_id)&&
                          (itemModal = item.id)&&
                          (confirmationModalAction = 'delete')"
                      :title="$t('common.delete')"
                      :class="{
                          'zeroOpacity': !isElementInteractible('delete-bill', item.act_user_id),
                          'pointer': isElementInteractible('delete-bill', item.act_user_id)
                          }"
                      style="width:25px;">
                  </div>
                  <div v-else>
                  <img src="/icons/download_blue.svg"
                    alt="download"
                    id="fa-download"
                    :title="$t('bills.download')"
                    class="pointer"
                    style="padding-right: 10px; width: 35px;"/>
                  </div>
                </td>
                <!-- #endregion BillActions -->
                <td class="mobile__button">
                    <button @click="visible(item.id)" class="button__caret">
                      <i v-if="showItem !== item.id" class="fas fa-caret-down fa-3x"></i>
                      <i v-if="showItem === item.id" class="fas fa-caret-up fa-4x"></i>
                    </button>
                </td>
            </tr>

            <tr  v-if="!hasMybills && hasInputById === item.id">
              <td class="p-0"></td>
              <td class="p-0" :colspan="(clientsList.length >= 0) ? '7' : '6'">
                <table class="mt-0 mb-1" >
                  <tr v-for="(order, index) in item.orders" :key="index">
                    <td class="p-1">
                      <input :value="order.name"
                           id="name" :list="'order-name-' + index"
                           :data-sub_id="index"
                           :placeholder="$t('User.name')"
                           class="form-control"
                           :class="{'is-invalid' : (!order.name)}"
                           @input="fetchOrdersList(idx, $event);"
                      >
                      <datalist :id="'order-name-' + index" >
                        <option v-for="(o, i) in orders_data_list"  :key="i" :value="o.name" >[{{ o.id }}]{{ o.name  }}</option>
                      </datalist>
                    </td>
                    <td class="p-1">
                      <input type="text" class="form-control" id="desc" v-empty-html v-model="order.desc"
                             :data-sub_id="index"
                             @change="updateBillsOrder(idx, $event)"

                             :placeholder="$t('company.description')">
                    </td>
                    <td class="p-1 right" style="text-align: right;width:380px">
                      <div class="float-right" >
                        <div class="input-group" >
                          <input type="number" class="form-control" id="amount"
                                 :data-sub_id="index"
                                 style="min-width:75px;max-width:80px" :placeholder="$t('bills.amount')"
                                 :value="order.amount" @change="updateBillsOrder(idx, $event);"
                                 min="0">
                          <div class="input-group-prepend">
                            <input v-if="(order.unit === 'Another' || (order.unit && !($store.state.bills.unit.map(x => x.name).includes(order.unit))))"
                                   type="search"
                                   class="form-control mw100" id="unit_input"
                                   @change="updateBillsOrder(idx, {
                                     target: { id: 'unit', value: $event.target.value.length > 0 ? $event.target.value : 'Object',
                                     // TODO: investigate why $event.target.dataset.sub_id returns undefined here and value has to be hardcoded
                                       dataset: { sub_id: '0' }
                                     }
                                   })"
                                   v-model="order.unit"/>
                            <select v-else-if="($store.state.bills.unit.map(x => x.name).includes(order.unit)) || order.unit === ''"
                                    id="unit" class="form-control" style="min-width:80px;max-width:90px;"
                                    :data-sub_id="index"
                                    v-model="order.unit"
                                    @change="updateBillsOrder(idx, $event);">
                              <option v-for="s in $store.state.bills.unit" :key="s.name" :value="s.name">{{ s.name }}</option>
                            </select>
                          </div>
                        <div class="input-group-prepend">
                             <span class="input-group-text">x</span>
                        </div>
                        <div class="input-group-prepend">
                          <input type="number" class="form-control"
                                 :data-sub_id="index"
                                 style="width:140px;" id="unit_price"
                                 @change="updateBillsOrder(idx, $event);" :value="order.unit_price">
                        </div>
                        <div class="input-group-prepend">
                          <span class="input-group-text">=</span>
                        </div>
                        </div>
                      </div>
                    <td class="p-1 text-center" style="width:180px;">
                     {{ sum_order( index , idx , order) }}
                    </td>
                    <td class="p-1 text-center">
                      <button class="btn btn-outline-secondary" @click="addNewOrder(idx)" ><i class="fa fa-plus"></i></button>
                      <button class="btn btn-outline-danger" v-if="item.orders.length > 1"  @click="deleteOrder(idx, index)" ><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                </table>
              </td>
              <td class="text-center align-middle p-1">
                  <select id="vat_id" class="form-control" v-model="item.vat_id" style="max-width:100px;min-width:80px;" @change="updateBills(idx, $event)"  >
                    <option v-for="(s , index) in  item.firms.vat" :key=" + index " :value="s.id">{{ s.vat +'%' }}</option>
                  </select>
              </td>
              <td class=" text-center align-middle p-1">
                        <select id="status" v-model="item.status" class="form-control" @change="updateBills(idx, $event)">
                          <option v-for="st in statusList" :value="st.id" :key="st.id">{{ st.name }}</option>
                        </select>
              </td>
              <td colspan="2" class="p-0"></td>
            </tr>
          </template>
        </tbody>
        <tfoot>
        <tr class="summa__background">
          <td :colspan="getColSpanForPrice()"></td>
          <td class="summa" >
            <div class="p-2 summa__number">{{ countAllPrice()}}</div></td>
          <td colspan="5"></td>
        </tr>
        </tfoot>
      </table>
    </div>
    <ToTop></ToTop>

    <Modal :title="`${$t('bills.'+confirmationModalAction)}
                    ${$t('bills.invoice')}
                    #${itemFromBillsList(itemModal) ? itemFromBillsList(itemModal).invoice : ''}:
                    ${(itemFromBillsList(itemModal) ? itemFromBillsList(itemModal).orders[0] : false ) ? itemFromBillsList(itemModal).orders[0].name : '---'}?`"
           @closeModal="itemModal = $event;"
           v-if="itemModal">
      <div v-if="itemFromBillsList(itemModal)" class="alert alert-light">

        <p v-if="">{{ `${$t('bills.invoice')}: ${(itemFromBillsList(itemModal).orders[0]) ? itemFromBillsList(itemModal).orders[0].name : '---'}` }}</p>
        <p>{{ `${$t('bills.price')}: ${itemFromBillsList(itemModal).price}` }}</p>
        <p>{{ `${$t('bills.publisher')}: ${itemFromBillsList(itemModal).users.username}` }}</p>
        <p class="text-danger">{{ $t('bills.'+`${confirmationModalAction}`+'Confirmation') }}</p>

      </div>
      <template v-slot:footer>
        <button type="button" class="btn btn-secondary"
                @click="itemModal=false;"
        >{{ $t("common.cancel")}}
        </button>
        <button type="button" class="btn btn-danger"
                @click="confirmationModalHandler(billInModal); billInModal = {}"> {{ $t("bills."+confirmationModalAction)}}
        </button>
      </template>
    </Modal>

    <Modal :title="$t('bills.newBill')"
           size="modal-xl"
           @closeModal="closeGenModal"
           v-if="showGenModal">
      <div class="modal-body">
        <GeneratorComponent ref="generator" :display="this.display" @addNewFirm="setNewFirm" @changeFirm="openModalToChangeFirm" @correctFirm="openModalToCorrectFirm" ></GeneratorComponent>
      </div>
    </Modal>

    <LazyFirmModalComponent :firm="firm"
                            :isCorrect = "isCorrect"
                            :isFirmModalVisible="isFirmModalVisible"
                            :title="FirmTitle"
                            @closeFirmModal="isFirmModalVisible = $event; $store.dispatch('bills/fetchFirmsList');">
    </LazyFirmModalComponent>
  </section>
</template>
<script>
import GeneratorComponent from '../../components/bills/GeneratorComponent';
import {Client } from "assets/js/models/Client";
import isElementInteractible from "../../mixins/isElementInteractible";
import dateFormat from "../../mixins/dateFormat";
import { Firm, VAT } from '../../assets/js/models/Bill';
import BillsStatistic from "@/components/bills/BillsStatistic";

export default {
  name: "BillsList",
  components: {
    ToTop: () => import("@/components/common/ToTop"),
    Modal:() => import('@/components/common/Modal'),
    Notification : () => import("@/components/common/Notification"),
    BillsStatistic,
    LazyFirmModalComponent: () => import('@/components/bills/FirmModalComponent'),
    GeneratorComponent
  },
  layout: "admin",
  mixins:[isElementInteractible, dateFormat],
  middleware: ['auth'],
  data() {
    return {
      widthOfScreen:true,
      orders_data_list :[],
      showItem: null,
      current_date: new Date(),
      active_until_date: new Date(this.$auth.user.available_teams[0].active_until),
      diffDays: 0,
      display: 0,
      hasInput: null,
      hasMybills: this.$route.query.hasOwnProperty('incoming'),
      showSelect: false,
      hasInputById: null,
      maxNumerBull:null,
      itemModal: false,
      showGenModal: false,
      isFirmModalVisible: false,
      isCorrect: false,
      FirmTitle : "Edit Company",
      saveBtn: false,
      checkfirm: false,
      firm: new Firm({ vat: [ new VAT({ default: true }) ] }),
      unpaid : false,
      lang: {
        formatLocale: {
          firstDayOfWeek: 1,
        },
      },
      confirmationModalAction: null
    }
  },

  async asyncData({ store  , route  }) {
     await store.dispatch('bills/fetchFirmsList');
       store.dispatch('users/fetchUsersList');
    if(!route.query.hasOwnProperty('incoming')) {
    await   store.dispatch('bills/fetchRepresentivesList');
    }else{
    await   store.commit('bills/SET_OBJ', {name: 'filterBills', key: 'incoming', value: true})
    }
    await store.dispatch('bills/fetchBillsList');
    await store.dispatch('clients/fetchClientsList');
  },


  created(){
    if(this.clientsList.length === 0 ){
      this.$store.commit('SET_OBJ' , { name : 'show_client_notification' , value : true } );
    }

    let diffTime = Math.abs(this.active_until_date - this.current_date);
    this.diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (this.diffDays <= 3) {
      this.unpaid = true;
    }

    if(this.$store.state.bills.firmsList && this.$store.state.bills.firmsList.length === 0){
      this.setNewFirm();
    }else if(this.$store.state.bills.firmsList &&  this.$store.state.bills.firmsList.length === 1){
      this.$store.commit('bills/SET_OBJ', {name: 'filterBills', key: 'firm2', value: this.firmList[0].id})
    }
  },

  beforeMount() {
    if (process.client) {
      if (window.innerWidth < 768) {
        this.widthOfScreen = false;
      }
    }
  },

  destroyed() {
    this.$store.commit('SET_OBJ', { name: 'firmsList', value: {} })
    this.$store.commit('SET_OBJ', { name: 'filterBills', value: {} })
    this.$store.commit("SET_OBJ", { name: "eventBill", value: {} })
    this.$store.commit("clients/SET_OBJ", { name: "ClientDetail", value: new Client() });
  },

  computed: {
    billsList() {
      const data = JSON.parse(JSON.stringify(this.$store.state.bills.billsList))
      this.selected = []
      return data
    },
    userList() {
      return  JSON.parse(JSON.stringify(this.$store.state.users.users))
    },
    firmList() {
      return JSON.parse(JSON.stringify(this.$store.state.bills.firmsList))
    },
    filterBills() {
      return JSON.parse(JSON.stringify(this.$store.state.bills.filterBills))
    },
    years() {
      let currentYear = new Date().getFullYear();
      return [ currentYear-1, currentYear, currentYear+1 ];
    },
    deal() {
      return this.$store.getters["bills/getDeal"];
    },
    statusList() {
      return this.$store.getters["bills/getStatus"];
    },
    clientsList(){
      return this.$store.getters["clients/getClientsList"]
    },
  },
  directives:{
    emptyHtml:{
      bind(el){
        el.value =  el.value ? el.value.replace(/<[^>]*>?/gm, '') : '';
      },
      update(el){
        el.value =  el.value ? el.value.replace(/<[^>]*>?/gm, '') : '';
      }
    }

  },

  methods: {
    visible(bill){
        if(this.showItem === bill)
          this.showItem = null
        else
          this.showItem = bill;
      },
    closeNotification(){
      this.unpaid = false;
    },
    setNewFirm(){
      this.isFirmModalVisible = true;
      this.FirmTitle = 'Create Company';
      this.firm = new Firm({ vat: [ new VAT({ default: true }) ] });
    },
    selectMonth(monthDirection) {
      switch (monthDirection) {
        case 'previous':
          this.filterBills.month--;
          if (this.filterBills.month === 0) {
            this.filterBills.month = 12;
            this.filterBills.year--;
          }
          break;
        case 'next':
          this.filterBills.month++;
          if (this.filterBills.month === 13) {
            this.filterBills.month = 1;
            this.filterBills.year++;
          }
          break;
        default:
          break;
      }
      this.commitBillFilterValues();
      this.$store.dispatch('bills/fetchBillsList');
    },

    openModalToChangeFirm(data){
      this.isFirmModalVisible = true;
      this.FirmTitle = 'Edit Company';
      this.firm = data
    },
    openModalToCorrectFirm(object){
      this.isCorrect = true;
      this.isFirmModalVisible = true;
      this.FirmTitle = 'Edit Company';
      this.firm = object;
    },
    editOrderBill(idx , item){
      this.orders_data_list = [];
      this.hasInputById = item.id;
      if(item.orders.length === 0){
         this.addNewOrder(idx)
      }
    },

    addNewOrder(idx){
      let newOrder =  {
        'name': null,
        'desc': null,
        'amount': 1,
        'unit': 1,
        'unit_price': 0.00
      }
      this.$store.commit("bills/UPDATE_BILL_LIST", { id: idx, sub: "orders", new: true, value: newOrder });
    },

    deleteOrder(idx , order_idx){
      this.$store.commit("bills/DELETE_ORDER_FROM_BILL_LIST", { id: idx, sub: "orders", sub_id:  order_idx});
    },

     sum_order( index , idx ,order){
      let price  = (order.amount * order.unit_price).toFixed(2);
        this.$store.commit("bills/UPDATE_BILL_LIST", {
          id: idx,
          sub: "orders",
          sub_id: index,
          key: 'price',
          value: price
        })
      return price ;
    },

    getColSpanForPrice() {
      var res = 5 ;
      if(this.hasMybills) res = 4;
      else if(!this.hasMybills && this.clientsList.length > 0) res =  6;
      return res;
    },

    async wasPaid(item, dateSet=true){
      let d = new Date();
      if (dateSet) {
        this.$store.commit("bills/UPDATE_BILL_LIST_BY_KEY", { id: item.id, key: "was_paid", value: d.toISOString().slice(0,10) })
      } else {
        this.$store.commit("bills/UPDATE_BILL_LIST_BY_KEY", { id: item.id, key: "was_paid", value: null })
      }
      await this.$store.dispatch('bills/updateBill', item).then(
        resp => {
          this.$toast.success(resp.msg).goAway(3000);
          this.$store.dispatch('bills/fetchBillsList');
        },
        error => {
          this.$toast.error(error.msg).goAway(3000);
        }
      ).catch(
        err => {
          this.$toast.error(err).goAway(3000);
        }
      )
    },

    /**
     * Handles confirmation window actions
     * @param {Object} bill - bill to act upon
     */
    confirmationModalHandler(bill){
      if(bill) {
        switch (this.confirmationModalAction) {
          case 'delete':
            this.deleteObj(bill.id, false)
            break;
          case 'delete_confirm':
            this.deleteObj(bill.id, true)
            break;
          case 'pay':
            this.wasPaid(bill);
            break;
          case 'unpay':
            this.wasPaid(bill, false);
            break;
          case 'clone':
            this.cloneBill(bill.id);
            break;
          default:
            break;
        }
      }
      this.itemModal = false;
    },

    /**
     * Returns bill object given bill ID in table view
     * @param {Number} itemID - id to search for
     *
     * @returns {Object | boolean} - bill to return
     */
    itemFromBillsList(itemID){
      var billToReturn = false;
      if(itemID) {
        billToReturn = this.$store.state.bills.billsList.find(x => x.id === itemID);
        this.billInModal = billToReturn;
      }
      return billToReturn;
    },

    async showPdfByClick(id) {
      await this.$store.dispatch("bills/getDataById", id)
      this.showGenModal = true;
      this.display = 4;
    },

    async createObj(item) {
      if (item.orders[0].name === null ||
        item.deal === null) {
        return;
      }
      if (!item.invoice) {
        item.invoice = this.getPrevNumber(item);
      }
      await this.$store.dispatch('bills/createBill', item).then(
        resp => {
          this.$toast.success(resp.data.msg).goAway(3000);
          this.$store.dispatch('bills/fetchBillsList');
          this.hasInputById = null;
        }).catch(
        e => {
          this.$toast.error("Ops" + e.toString()).goAway(3000);
          this.$store.dispatch('bills/fetchBillsList');
        });
    },

    changeColor(data) {
      let cl = '';
      if(data) {
        if (data.status === 1) {
          cl = 'fixed'
          if (data.deal === 3) {
            cl += " credit";
          }
        } else if (data.deal === 2) {
          cl = 'booking'
        } else if (data.deal === 3) {
          cl += " credit";
        }
        if (data.was_paid !== null) {
          cl += " color-green";
        }
      }
      return cl;
    },

    /**
     * Return invoice number
     * @returns {String}
     */
    getPrevNumber(item) {
      let dateParts;
      try {
        dateParts =  new Date(item.date).toISOString().slice(0, 10).split("-");
      }catch (e){
        let parts  = item.date.split(".");
        dateParts = new Date(parts[0], parts[1] - 1, parts[2]).toISOString().slice(0, 10).split("-");
      }

      let invoiceIndex = 1;
      if (item.number) {
        invoiceIndex = item.number.toString().padStart(3, 0);
      }
      return dateParts[1] + dateParts[0].slice(2, 4) + invoiceIndex;
    },

    async CancelBtn() {
      this.$store.dispatch('bills/fetchBillsList');
      this.hasInputById = null;
      this.filterBills.MaxNumberBill = null;
      this.commitBillFilterValues();
      this.saveBtn = false;
    },

    async cloneBill(id) {
      await this.$store.dispatch("bills/cloneBill", id).then(
        resp => {
          this.$toast.success(resp.msg).goAway(3000);
          if(resp.code == 200)  this.$store.dispatch('bills/fetchBillsList');
        }).catch(error => { this.$toast.error(error.msg).goAway(3000); })
    },

    async editFirm() {
      if (this.filterBills.firm2) {
        this.firm = new Firm(this.firmList.find(el => (el.id === this.filterBills.firm2)));
        this.isFirmModalVisible = true;
        this.FirmTitle = 'Edit Company'
      } else {
        this.checkfirm = true;
      }
    },

    async deleteFirm() {
      await this.$store.dispatch("bills/deleteFirm", this.firm.id)
        .then(
          resp => {
            this.isFirmModalVisible = false;
            this.$store.dispatch("bills/fetchFirmsList");
            this.$toast.success(resp.msg).goAway(3000);
          }).catch(error => { this.$toast.error(error.msg).goAway(3000); })
    },

    async saveFirm() {
      if (this.firm.id != null) {
        await this.$store.dispatch("bills/updateFirm", { id: this.firm.id, data: this.firm })
          .then(
            resp => {
              this.isFirmModalVisible = false;
              this.$toast.success(resp.msg).goAway(3000);
            },
            error => {
              this.$toast.error(error.msg).goAway(3000)
            })
      } else {
        await this.$store.dispatch('bills/createFirm', { data: this.firm })
          .then(
            resp => {
              this.isFirmModalVisible = false;
              this.$toast.success(resp.msg).goAway(3000);
              this.$store.dispatch('bills/fetchFirmsList');
            },
            error => {
              this.$toast.error(error.msg).goAway(3000)
            });
      }
    },

    createEventBill() {
      this.commitBillFilterValues();
      this.$store.dispatch("bills/setDefaultEventBill");
      this.display = 0;
      this.showGenModal = true;
    },

    billsFilterDate(data) {
      this.filterBills.date = data;
      this.commitBillFilterValues();
      this.$store.dispatch('bills/fetchBillsList');
    },

    billsFilter() {
      this.checkfirm = false;
      if(this.filterBills.firm2)
        this.showSelect = true;
      else
        this.showSelect = false;

      this.filterBills.MaxNumberBill = null;
      this.commitBillFilterValues();
      this.$store.dispatch('bills/fetchBillsList');
    },

    setOrder(name, order){
      this.filterBills.orderType =
        (this.filterBills.orderType == order && this.filterBills.orderBy == name) ? null : order;
      this.filterBills.orderBy = name;
      this.commitBillFilterValues();
      this.$store.dispatch('bills/fetchBillsList');
    },

    switchIcon(name, order){
      var res = '';
      if (this.filterBills && this.filterBills.orderBy === name) {
        res = this.filterBills.orderType === order ? 'grey_selected' : 'grey';
      }
      else res = 'fa-sort grey';
      if (name === 'was_paid') {
        res = this.filterBills.orderType === order ? 'text-white' : 'grey';
      }
      return res;
    },

   async fetchOrdersList( idx , data ){
      if(data.target.value.length < 5 ) {
        let name = data.target.id;
        let request = {name: data.target.value};
        const res = await this.$store.dispatch("orders/searchOrderBy", request);
        this.orders_data_list = res.data;
      }
     this.updateBillsOrder(idx, data)

   },

    async updateBillsOrderCommit(id, data){
      console.log(data);
      console.log(data.target.dataset.sub_id);
      console.log(data.target.id);
      console.log(data.target.value);
      await this.$store.commit("bills/UPDATE_BILL_LIST", {
        id: id,
        sub: "orders",
        sub_id : data.target.dataset.sub_id,
        key: data.target.id,
        value: data.target.value ? data.target.value : ""
      })
    },


   async updateBillsOrder(id, data) {
      this.updateBillsOrderCommit(id , data);

     if(data.target.id === 'name' && this.orders_data_list.length > 0 ) {
       const res = this.orders_data_list.find(e => e.name === data.target.value);
       for (const [key, value] of Object.entries(res)) {
         console.log('key' + key);
         console.log('value' + value);
          let res_val = {
           target: {
             dataset: {
               sub_id: data.target.dataset.sub_id,
             },
             id: key,
             value: value
           }
         };
         this.updateBillsOrderCommit(id, res_val);
       }
     }

      if(data.target.id ==='unit_price' || data.target.id ==='amount' ){
        var total_sum = this.checkTotalSum(id);
        this.updateBills(id , {target:{ id: 'price' , value : total_sum }} )
      }
      this.saveBtn = true;
    },

    checkTotalSum(id){
      var total_sum = 0;
      if(this.billsList[id].orders.length  === 1 ){
         total_sum  = (this.billsList[id].orders[0].unit_price * this.billsList[id].orders[0].amount).toFixed(2);
      }else {
         total_sum = this.billsList[id].orders.reduce(function (result, item) {
          let res = (result.unit_price * result.amount)
          return res += (item.unit_price * item.amount);
        });
      }
      if(this.billsList[id].vat_id !== null && this.billsList[id].vat_value != 0)
        total_sum = (parseFloat(total_sum) + parseFloat(Math.floor(total_sum * this.billsList[id].vat_value) / 100)).toFixed(2);

      return total_sum;
    },

    updateBillsNumber(id, data) {
      this.updateBills(id, data);
      let invoice = this.getPrevNumber(this.billsList[id]);
      this.$store.commit("bills/UPDATE_BILL_LIST", { id: id, key: "invoice", value: invoice })
    },

    updateBillsWasPaid(id, data) {
      this.$store.commit("bills/UPDATE_BILL_LIST", { id: id, key: "was_paid", value: data.target.value })
    },

    /**
     * Sets bill date given bill id and value to set
     * @param {number} id id of row to edit
     * @param {string} data date value in ISO format
     */
    updateBillsDate(id, data) {
      this.$store.commit("bills/UPDATE_BILL_LIST", { id: id, key: "date", value: data })
    },

    updateBills(id, data) {
      this.$store.commit("bills/UPDATE_BILL_LIST", { id: id, key: data.target.id, value: data.target.value })
      if(data.target.id  === 'vat_id'){
        let vat_value = this.billsList[id].firms.vat.find( e => e.id == data.target.value)
           this.$store.commit("bills/UPDATE_BILL_LIST", {id: id, key: 'vat_value', value: vat_value.vat})
           var total =  this.checkTotalSum(id)
          this.$store.commit("bills/UPDATE_BILL_LIST", {id: id, key: 'price', value: total})
      }
      this.saveBtn = true;
    },

    commitBillFilterValues() {
      this.$store.commit('bills/SET_OBJ', { name: 'filterBills', value: this.filterBills })
    },

    countAllPrice() {
      let count = 0;
      if(this.$auth.user.local_data) {
        let currentUserID = this.$auth.user.local_data.id;
        if (this.isElementInteractible('view-bill-price')) {
          this.billsList.forEach(function (e) {
            if (e.price) count += parseFloat(e.price.toString().replace(',', '.'));
          });
        } else {
          this.billsList.forEach(function (e) {
            if (e.user_id === currentUserID) {
              if (e.price) count += parseFloat(e.price.toString().replace(',', '.'));
            }
          });
        }
      }
      return count.toFixed(2);
    },

    saveObj(data) {
      if(data.id === '') {
        if(data.orders.length === 0 ){
          this.$toast.error("The first order must be filled").goAway(3000)
        }else {
          if (data.orders[0].name == null ) {
            this.$toast.error("The order name must be filled").goAway(3000)
          }else if( data.orders[0].name.length < 3){
            this.$toast.error("The order name must contain more than three letters").goAway(3000)
          }
          else {
            this.$store.dispatch('bills/createBill', data).then(
              resp => {
                this.saveBtn = false;
                this.hasInputById = null;
                this.$toast.success(resp.msg).goAway(3000)
                this.$store.dispatch('bills/fetchBillsList');
              });
          }
        }
      }else {
        this.$store.dispatch('bills/updateBill', data).then(
          resp => {
            this.saveBtn = false;
            this.hasInputById = null;
            this.$toast.success(resp.msg).goAway(3000)
            this.$store.dispatch('bills/fetchBillsList');
          })
      }
    },

    closeGenModal() {
      this.showGenModal = false;
      this.$store.commit("bills/SET_OBJ", { name: "eventBill", value: {} });
      this.$store.dispatch('bills/setDefaultEventBill')
      this.$store.dispatch('bills/fetchBillsList');
    },

    deleteObj(id , confirm = false) {
      if(confirm){
        id = id + '?confirm=true'
      }
      this.$store.dispatch('bills/deleteObj', id ).then(
        resp => {
          if(resp.code === 206){
            this.itemModal = id;
            this.confirmationModalAction = 'delete_confirm'
          } else {
            this.itemModal = false;
            this.confirmationModalAction = null
          }
          this.$toast.success(this.$t('bills.invoice_deleted')).goAway(3000)
          this.$store.dispatch('bills/fetchBillsList');
        }).catch((err) => {
          this.$toast.error(err).goAway(3000)
          this.$store.dispatch('bills/fetchBillsList');

      });


    },

    async download(id){
      await this.showPdfByClick(id);
      await this.$refs.generator.$children[0].$refs.download.click();
      await this.$nextTick();
      this.showGenModal = false;
    },

    addItem() {
      if (this.filterBills.firm2 === null) {
        this.checkfirm = true;
      } else {
        if (this.filterBills.month === null) {
          this.filterBills.month = new Date().getMonth() + 1;
        }
        if (this.filterBills.year === null) {
          this.filterBills.year = new Date().getFullYear();
        }
        this.$store.dispatch('bills/getUniqNumber', {
          month: this.filterBills.month,
          year: this.filterBills.year,
          firm: this.filterBills.firm2
        }).then(
          resp => {
            if (resp['number'] <= this.filterBills.MaxNumberBill) {
              ++this.filterBills.MaxNumberBill;
              this.commitBillFilterValues();
            } else {
              if (resp['number']) {
                this.filterBills.MaxNumberBill = Number(resp['number']);
                this.commitBillFilterValues();
              } else {
                this.filterBills.MaxNumberBill = 1;
              }
            }

            let date = new Date().getDate();
            let d = new Date(this.filterBills.year, this.filterBills.month - 1, date);
            d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
            // let res = new Bill({ // TODO solve data in bill object in not visible
            let res = {
              'id': "",
              "number": this.filterBills.MaxNumberBill,
              'invoice': this.filterBills.MaxNumberBill,
              //this.getPrevNumber({ date : this.dateFormat(d.toISOString().slice(0, 10)), number : this.filterBills.MaxNumberBill }),
              "user_id": this.$auth.user.local_data.id,
              "orders": [{  'name': null,
                            'desc': null,
                            'amount': 1,
                            'unit': 1,
                            'unit_price': 0.00}],
              "act_user_id": this.$auth.user.local_data.id,
              users: {
                "username": this.$auth.user.username
              },
              firms: {
                "name": this.firmList.find(el => el.id === this.filterBills.firm2).name,
                "vat": this.firmList.find(el => el.id === this.filterBills.firm2).vat,
              },
              "firm_id": this.filterBills.firm2,
              "date": this.dateFormat(d.toISOString().slice(0, 10)),
              "price": 0,
              "deal": 4,
              'client_id': null,
              'company_id': null,
              "was_paid": null,
              "vat_id": null,
              "vat_value": null,
              "status": 0,
            };

            this.$store.commit('bills/ADD_ITEM_ARRAY_TOP', { name: 'billsList', value: res })
            this.hasInputById = "";
            this.saveBtn = true;
          },
          error => {
            this.$toast.error("Oops: " + error.toString()).goAway(3500);
          })
      }
    },
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";

table {
  font-family: 'Montserrat', sans-serif;
  font-size: 15px;
  padding: 0.3rem;
}
.arrow {
  height: 30px;
}
.grey{
  color: lightgrey;
}
.border-icon{
  border-color: rgb(206, 212, 218);
  border-style: solid;
  border-width: 1px;
  border-left: none;
  border-top-right-radius: .25rem;
  border-bottom-right-radius: .25rem;
}
table > tbody {
  border-bottom: 3px solid $secondary-color; /* Параметры линии */
}
table > tbody > tr > td > div{
  display: inline;
}
.form-inline > label {
  margin: 12px;
}
.select__date{
  min-width: 110px;
  max-width: 125px;
}
.select__date__input{
  min-width: 128px;
  max-width: 140px;
}
/*only without "style scoped"*/
.mx-datepicker svg{
  width: 2em;
  font-size: 26px;
}
.mx-datepicker /deep/ .mx-input{
  padding: 0px 10px !important;
  height: 38px !important;
  font-size: 16px;
  width: 100%;
}
.br-none{
  border-right: none;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}
@media screen and (max-width: 1200px){
  .hide-1200{
    display: none;
  }
}
@media screen and (max-width: 1500px){
  .hide-1500{
    display: none;
  }
}
.mw115 svg{
  display: none;
}
.mw150{
  max-width: 150px;
}
.mw200{
  max-width: 200px;
}
.mw220{
  max-width: 220px;
}
.fixed {
  background-color: lightskyblue;
  @media screen and (max-width: 768px){
    background-image: linear-gradient(180deg, rgb(226, 246, 251), rgb(248, 253, 254));
    background-color: transparent;
  }
}
.fixed:hover {
  background-color: rgb(200, 231, 250);
}
.color-green {
  color: green;
}
.color-green:hover {
  color: rgb(76, 129, 76);
}
.grey_selected{
  color: #969696;
}
.booking {
  background-color: #fff1d5;
  @media screen and (max-width: 768px){
    background-image: linear-gradient(180deg, rgb(252, 240, 224), rgb(255, 255, 244));
    background-color: transparent;
  }
}
.booking:hover {
  background-color:  rgb(250, 250, 228);
}
.credit {
  color: red;
}
.credit:hover {
  color: rgb(250, 134, 134);
}
table {
  border-collapse: collapse;
  width: 100%;
  margin: 20px 0;
}
#month #year {
  width: 75px;
}
.pointer {
  cursor: pointer;
}
.showAshref:hover {
  color: #00bb00;
  text-decoration: underline;
}
.makeBrighter:hover {
  filter: brightness(1.2);
}
.mw120{
  max-width: 120px;
}
.mw115{
  max-width: 115px;
}
.mw100{
  max-width: 100px;
}
.mw40 .p-2{
  max-width: 40px;
}
.mt-35px{
  margin-top: 35px;
}
.mobile__button{
  display: none;
  background-color: #f2f2f2;
}
.mobile{
  display: none;
}
.deal__td,
#find{
  text-align: center;
}
.button__caret{
  display: flex;
  position: relative;
  padding-top: 0 !important;
  background-color: transparent;
  border: none;
  color: $secondary-color;
}
.fa-caret-down{
  position: relative;
  margin-top: -60% !important;
}
.fa-caret-up{
  position: relative;
  margin-top: -100% !important;
}
.button__caret:active,
.button__caret:focus{
  outline: 0 !important;
  border: 0 !important;
}
.paidDate{
  display: block;
}
.unpayButton{
  display: none;
}
.unpayButtonRegion:hover .paidDate{
  display: none;
}
.unpayButtonRegion:hover .unpayButton{
  display: block;
}
.zeroOpacity{
  opacity: 0;
}
#firm{
  min-width: 106px;
}
.firm__select{
  min-width: 106px;
}
#for__firm,
#was_paid{
  max-width: 85px;
  vertical-align:middle;
  text-align: center !important;
}
#for__price{
  vertical-align: middle;
}
#for__KM,
#find__status,
#cell__user{
  text-align: center !important;
}
#find__status,
.unpayButton{
  vertical-align: middle;
}
#find__user,
#find__name,
#find__number,
#find__date,
#find__deal,
#find__status,
#for__KM,
.paidDate,
#cell__price,
#cell__firm,
#cell__user,
#user,
#order__name{
  line-height:50px;
  white-space: nowrap;
}
#act_user_id{
  max-width: 115px;
}
#bills__paid{
  min-width:92px;
  text-align: center;
}
.notification__size{
  margin: 20px 80px 20px 80px;
  @media screen and (max-width: 749px){
    margin: 30px 40px 20px 40px;
  }
}
.statistic__size{
  margin: 50px 20px;
}

.sort-arrow {
  max-height: 10px;
  width: 16px;
}
.sort-arrow-container {
  padding-top: 1px;
  padding-right: 3px;
}
.input-group{
  display: flex;
  flex-wrap: nowrap;
}
.nowrap{
  white-space: nowrap;
}
.data__td,
#cell__price{
  text-align: center;
}
.summa{
  padding-top: 0px;
}
.summa__number{
  background-color: $secondary-color;
  color: #ffffff;
  text-align: center;
}
@media only screen and (max-width: 768px) {
  .deal__td,
  #for__KM,
  #find__status,
  #cell__user,
  .user__td,
  #find,
  .data__td,
  #cell__price,
  #order__name{
    text-align: left !important;
  }
  .data__td{
    min-height: 58px;
  }
  .data__middle{
    display: flex;
    margin-top: auto;
    margin-bottom: auto;
  }
  .form-control{
    max-width: 240px;
  }
  .table{
    border-top: 2px solid #dee2e6;
    font-size: 18px;
  }
  .open::after{
    content: "";
    position: absolute;
    border-right: 2px solid #dee2e6;
    width: 180px;
    height: 30vw;
  }
  .table thead {
    display: none;
  }
  .table tr {
    display: block;
  }
  .table td {
    display: flex;
    justify-content: flex-start;
    border-top: none !important;
  }
  table > tbody {
    border: none !important;
  }
  table > tbody > tr {
    border-bottom: 2px solid $secondary-color;
  }
  table > tbody > tr:last-of-type{
    border: none !important;
  }
  .table td::before {
    content: attr(data-label);
    display: flex;
    color: $secondary-color;
    align-items: center;
    width: 240px;
  }
  #find__number{
    color: $secondary-color;
  }
  .table td:last-of-type::before{
    width: 0px;
  }
  .buttons__paid:before,
  .summa:before{
    content: attr(data-label);
    width: 0px !important;
  }
  .summa{
    display: flex;
    justify-content: center !important;
    margin-top: -20px !important;
  }
  .summa__background{
    background: linear-gradient(180deg, rgb(242, 242, 242), rgb(255, 255, 255));
    min-height: 100vw;
    }
  .summa__number{
    background-color: #ffffff;
    color: #000000;
    }
  .buttons__paid{
    display: flex;
    justify-content: flex-end !important;
    margin-bottom: 40px;
    margin-right: 10px;
  }
  .table td:first-of-type::before{
    content: attr(data-label);
    display: none;
  }
  .bill__actions{
    display: none !important;
  }
  .mobile__button{
    display: flex;
    justify-content: center !important;
    padding: 0 !important;
    height: 30px;
  }
  .mobile{
    display: flex;
    align-items: center;
  }
  .mobile__header{
    display: flex;
    justify-content: space-between !important;
    width: 100%;
    min-width: 100%;
  }
}
@media only screen and (max-width: 500px) {
  .table{
    font-size: 12px;
  }
  .table td::before {
    content: attr(data-label);
    display: flex;
    color: $secondary-color;
    align-items: center;
    width: 130px;
  }
  .btn-warning{
    font-size: 12px;
    padding: 5px !important;
  }
  .open::after{
    content: "";
    position: absolute;
    border-right: 2px solid #dee2e6;
    width: 115px;
    height: 30vw;
  }
}
</style>
