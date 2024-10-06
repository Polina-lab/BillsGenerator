<template>
    <div>


        <div class="container-fluid m-1">
        <div class="d-sm-block d-md-none d-lg-none d-xl-none" style="width: 100%;">
            <div class="search__box">
                <no-ssr>
                    <v-datepicker id="date"
                                class="select__date br-none"
                                value-type="format"                             
                                type="date"
                                :lang="lang"
                                :placeholder="$t('bills.select_date')"><!-- @change="billsFilterDate($event)" -->
                    </v-datepicker>
                </no-ssr>
                <select id="serch__user" name="user" class="form-control">
                    <option :value="null" >{{ $t('bills.select_user')}}</option>
                    <option v-for="(user, idx) in  $store.state.users.users" :key="idx" :value="user.id"><div v-if="user.username !== null">{{ user.username }}</div>
                    </option>
                </select>
            </div>
        </div>
        <div class="table-responsive mt-1 tableFixHead">

        <table class="table table-hover">
        <thead>
          <tr>

          <th scope="col" class="p-1 data__box" >
              <no-ssr>
                <v-datepicker id="date"
                              class="select__date br-none"
                              value-type="format"                             
                              type="date"
                              :lang="lang"
                              :placeholder="$t('bills.select_date')"><!-- @change="billsFilterDate($event)" -->
                </v-datepicker>
              </no-ssr>
          </th>

            <th scope="col" class="p-1">
              <select id="serch__user" name="user" class="form-control display">
                <option :value="null" >{{ $t('bills.select_user')}}</option>
                <option v-for="(user, idx) in  $store.state.users.users" :key="idx" :value="user.id"><div v-if="user.username !== null">{{ user.username }}</div>
                </option>
              </select>
            </th>

            <th scope="col" class="p-1">
              <select id="serch__action" name="action" class="form-control display">
                <option :value="null" >{{ $t('bills.actions')}}</option>
                <option v-for="(log, idx) in  logs" :key="idx" :value="log.id">{{ log.action }}
                </option>
              </select>
            </th>

          </tr>
        </thead>


        <tbody>
        <tr>
          <td :data-label="$t('bills.select_date')">log.date</td>
          <td :data-label="$t('common.status')" class="text-center">log.user</td>
          <td :data-label="$t('bills.actions')" class="text-center">log.action</td>
        </tr>
        </tbody>
        </table>
        </div>
        </div>
    </div>
</template>
<script>
export default {
    
}
</script>
<style lang="scss" scoped>
@import "./assets/scss/_var.scss";
.search__box{
    display: flex;
    flex-direction: row;
    margin-bottom: 50px;
}
table {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  padding: 0.3rem;
  width: 100%;
}
table thead tr th:first-of-type(2){
    width: auto;
}
table thead tr th:nth-of-type(2){
    width: 30%;
}
.mx-datepicker /deep/ svg{
  width: 2em;
  font-size: 26px;
  margin-right: -6px;
}
.mx-datepicker /deep/ .mx-input{
  padding: 0px 10px !important;
  height: 38px !important;
  font-size: 16px;
  width: 100%;
}
.mx-datepicker /deep/{
    width: auto;
}
.pointer{
  cursor: pointer;
}
table > tbody > tr {
  border-bottom: 1px solid $logo-green;
}
table > tbody > tr > td{
  vertical-align: middle;
}
table > thead > tr > th{
  vertical-align: middle;
  line-height: 1.2;
  white-space: nowrap;
}
table thead tr th:last-of-type{
    width: 50%;
}
  @mixin properties{
   .table thead{
    display: none;
  }
   .table tbody tr{
      display: block;
      text-align: right;
    td{
      display: flex;
      justify-content: space-between;
    }
    td::before {
      content: attr(data-label);
      font-weight: bold;
    }
  }
   .span{
    display: flex;
    flex-direction: column;
  }
  .display{
    display: none;
  }
}
  .button__delete{
    width:25px;
  }
  .icons__box{
    display: flex;
  }
  .button__edit{
    padding-right: 10px;
    width: 40px;
  }

@media screen and (max-width: 768px){
  @include properties;
}
</style>