<template>
  <div class="row p20" >
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="form-row">
        <div class="col-md-6 offset-md-3">
          <label for="locale">{{ $t("bills.language") }}</label>
          <select id="locale" class="form-control" v-model="eventBill.locale" @change="updateBill($event)">
            <option v-for="lang in langsList" :value="lang.id" :key="lang.id">{{ lang.name }}</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 offset-md-3">
          <label for="deal">{{ $t("bills.deal") }}</label>
          <select id="deal" class="form-control" v-model="eventBill.deal" @change="updateBill($event)">
            <option v-for="d in deal" :key="d.id" :value="d.id">{{ d.name }}</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 offset-md-3">
          <label for="status">{{ $t("bills.status") }}</label>
          <select id="status" class="form-control" v-model="eventBill.status" @change="updateBill($event)" >
            <option v-for="s in statusList" :key="s.id" :value="s.id">{{ s.name }}</option>
          </select>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="form-row">
        <div class="col-md-6 offset-md-3">
          <label for="responsible_user">{{ $t("bills.responsible_user") }}</label>
          <select id="responsible_user" class="form-control" v-model="eventBill.sender_user_id">
            <option v-for="user in userList" :key="user.id" :value="user.id">{{ user.username }}</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 offset-md-3">
          <label for="sender_user">{{ $t("bills.publisher") }}</label>
          <select id="sender_user" class="form-control" v-model="eventBill.act_user_id">
            <option v-for="user in userList" :key="user.id" :value="user.id">{{ user.username }}</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Page3Component",
  props: [ "eventBill" ],

  mounted() {
    if (this.eventBill.locale === null) {
      this.$store.commit('bills/SET_OBJ', {
        name: 'eventBill',
        key: 'locale',
        value: +this.langsList.find((language) => language.code == 'ee').id
      });
    } else if (typeof(this.eventBill.locale) === 'string' && isNaN(this.eventBill.locale)) {
      this.$store.commit('bills/SET_OBJ', {
        name: 'eventBill',
        key: 'locale',
        value: this.langsList.find((language) => language.code == this.eventBill.locale).id
      });
    }
  },

  computed: {
    userList() {
      return JSON.parse(JSON.stringify(this.$store.state.users.users));
    },
    langsList() {
      //return JSON.parse(JSON.stringify(this.$store.state.locales));
      // return this.$i18n.locales.filter(i => i.code !== this.$i18n.locale);
      return this.$i18n.locales;
    },
    statusList() {
      return this.$store.getters["bills/getStatus"];
    },
    deal() {
      return this.$store.state.bills.deal;
    }
  },

  methods: {
    /**
     * Updates bill data in store given data object
     * @param {Object} data - data object to store
     * @param {Number} data.target.id - id of object to store
     * @param {Object} data.target.value - value of object to store
     */
    updateBill(data) {
      this.eventBill[data.target.id] = data.target.value;
      this.$store.commit('bills/SET_OBJ', { name: 'eventBill', value: this.eventBill });
    },
  }
}
</script>

<style scoped>

</style>
