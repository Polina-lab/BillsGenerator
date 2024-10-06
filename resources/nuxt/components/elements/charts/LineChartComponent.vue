<template>
  <div class="container-fluid small">
    <div class="row">
      <div class="col-12 col-md-3">
        <div class="row no-gutters">
          <div class="col-6">
            <button class="btn btn-outline-secondary width__setting">{{ $t('products.earnings') }}</button>
          </div>
          <div class="col-6">
            <button class="btn btn-outline-secondary width__setting">{{ $t('products.count') }}</button>
          </div>
        </div>
      </div>
      <div class="w-0"></div>
      <div class="col-12 col-md-9">
        <div class="row no-gutters ml-0 ml-md-5">
          <div class="col-4 col-md-2"><button class="btn btn-outline-secondary width__setting" 
                                     @click="fetchData('day', new Date())">{{ $t('products.day') }}</button></div>
          <div class="col-4 col-md-2"><button class="btn btn-outline-secondary width__setting" 
                                     @click="fetchData('week', new Date())">{{ $t('products.week') }}</button></div>
          <div class="col-4 col-md-2"><button class="btn btn-outline-secondary width__setting" 
                                     @click="fetchData('month', new Date(new Date().getFullYear(), new Date().getMonth(), 1))">{{ $t('bills.month') }}</button></div>
          <div class="col-4 col-md-2"><button class="btn btn-outline-secondary width__setting" 
                                     @click="fetchData('year', new Date().getFullYear().toString())">{{ $t('bills.year') }}</button></div>
          <div class="col-4 col-md-2"><button class="btn btn-outline-secondary width__setting" 
                                     @click="fetchData('all', '2020')">{{ $t('products.all') }}</button></div>
          <div class="col-4 col-md-2"><button class="btn btn-outline-secondary width__setting" 
                                     @click="fetchData('range')">{{ $t('products.range') }}</button></div>
        </div>
        <div class="row dynamic__buttons ml-n4 ml-md-3">
          <div class="col-1">
            <button class="btn">
              <i class="fas fa-chevron-left fa-1x" @click="fetchData(periodType, labelText.previous)"></i>
            </button>
          </div>
          <!-- Iterate through labels and create dynamic buttons which differ only in length -->
          <div v-for="label in Object.keys(labelText)" :key="label" class="col-2"> 
            <button v-if="labelText[label].text" class="single__text" :class="{ 'active-period-text' : label === 'currentSelected' }"
                    @click="fetchData(periodType, labelText[label].date)">{{ labelText[label].text }}
            </button>
          </div>
          <div class="col-1 ml-n4">
            <button class="btn">
              <i class="fas fa-chevron-right fa-2x" @click="fetchData(periodType, labelText.next.date)"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <LineChart :chartData="datacollection" :options="chartOptions"/>
    <button @click="fillData()">Randomize</button>
  </div>
</template>

<script>
import  Chart  from 'chart.js/auto';
import { LineChart } from 'vue-chart-3';

export default {
  name: 'LineChartComponent',
  components: { LineChart },
  data () {
    return {
      datacollection: {},
      chartOptions: {
        elements: {
          point: { radius: 3 },
          line: { tension: 0.3 }
        },
        scales: {
          y: { beginAtZero: true },
          x: { beginAtZero: true },
        }
      },
      testData: {
        months: [  // TODO: solve leap year issue
          { name: "jan", days: 31 },
          { name: "feb", days: 28 },
          { name: "mar", days: 31 },
          { name: "apr", days: 30 },
          { name: "may", days: 31 },
          { name: "jun", days: 30 },
          { name: "jul", days: 31 },
          { name: "aug", days: 31 },
          { name: "sept", days: 30 },
          { name: "okt", days: 31 },
          { name: "nov", days: 30 },
          { name: "dec", days: 31 },
        ],
        weekdays: [ 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun' ]
      },
      labelText: {
        prePrevious: { text: null, date: null},
        previous: { text: null, date: null},
        currentSelected: { text: null, date: null},
        next: { text: null, date: null},
        nextNext: { text: null, date: null}
      },
      periodType: null
    }
  },
  mounted () {
    this.fillData()
  },
  methods: {
    fillData () {
      this.datacollection = {
        labels: [], //
        datasets: [
          {
            label: `${this.$t('product.count')}`,
            backgroundColor: '#f87900',
            data: []
          }
        ]
      }
      this.fetchData('year', '2020');
    },

    getRandomInt () {
      return Math.floor(Math.random() * 100);
    },

    /**
     * Generate mock data to be used in chart
     * @param {('day'|'week'|'month'|'year'|'all'|'range')} periodType data period to display
     * @param {Date} periodStartDate date object representing start of period
     * @param {Date} periodEndDate date object representing end of period
     */
    fetchData(periodType, periodStartDate, periodEndDate = null) {
      let all_sales = 100000;
      let generated_dates = [];
      let periodDivider = 1;
      switch (periodType) {
        case 'day':
          periodDivider = 1000;
          periodEndDate = new Date(
            new Date(periodStartDate).getFullYear(),
            new Date(periodStartDate).getMonth(),
            new Date(periodStartDate).getDate() + 1
          );
          break;
        case 'week':
          periodDivider = 100;
          // src https://www.w3resource.com/javascript-exercises/javascript-date-exercise-50.php
          periodStartDate = new Date(
            periodStartDate.setDate(
              periodStartDate.getDate() - periodStartDate.getDay() + (periodStartDate.getDay() === 0 ? -6 : 1)));
          periodStartDate.setHours(0, 0, 0, 0);
          periodEndDate = new Date(
            new Date(periodStartDate).getFullYear(), 
            new Date(periodStartDate).getMonth(), 
            new Date(periodStartDate).getDate() + 7
          );
          if (periodEndDate > new Date((new Date().getFullYear() + 1).toString())) periodEndDate = new Date(new Date().getFullYear(), 11, 31);
          break;
        case 'month':
          periodDivider = 10;
          periodEndDate = new Date(
            new Date(periodStartDate).getFullYear(),
            new Date(periodStartDate).getMonth() + 1,
            1
          );
          break;
        case 'year':
          periodDivider = 4;
          periodStartDate = new Date(periodStartDate);
          periodEndDate = new Date((+new Date(periodStartDate.toString()).getFullYear() + 1).toString());
          break;
        case 'all':
          periodDivider = 1;
          periodStartDate = new Date(0);
          periodEndDate = new Date();
          break;
        case 'range':
          periodDivider = 100;
          break;
        default:
          break;
      }
      // src https://stackoverflow.com/a/9035732
      for (let j = 0; j < all_sales/periodDivider; j++) {
        generated_dates.push(new Date(
          new Date(periodStartDate.toString()).getTime() // starting from
          + Math.random() * (new Date(periodEndDate).getTime() // up to
          - new Date(periodStartDate.toString()).getTime()
          )));
      }
      periodStartDate = new Date(periodStartDate).toISOString().split('T')[0];
      periodEndDate = new Date(periodEndDate).toISOString().split('T')[0];
      let product_data = this.$store.dispatch('orders/fetchProductData', { product_id: +this.$route.params.id, periodStartDate, periodEndDate });
      console.log(product_data);
      // this.formatChartData(periodType, generated_dates);
      this.formatChartData(periodType, generated_dates);
    },

    /**
     * Formats data to be ready to pushed to array of data points of the chart
     * Should work with real data with no changes //TODO: remove this line from documentation when server responds with actual data
     * @param {('day'|'week'|'month'|'year'|'all'|'range')} period data period to display
     * @param {Array.<Date>} salesData array of dates of sales
     */
    formatChartData(period, salesData) {
      this.datacollection.datasets[0].data = []; // purge data
      this.datacollection.labels = []; // purge labels
      switch (period) {
        case 'day':
          break;
        case 'week':
          let daysOfWeek = [];
          salesData.forEach(sale_date => {
            daysOfWeek.push(sale_date.getDay()); // extract days of week (monday as 0, tuesday as 1, etc)
          });
          let map_weekdays = daysOfWeek.reduce((acc, e) => acc.set(e, (acc.get(e) || 0) + 1), new Map());
          for (let i = 0; i < this.testData.weekdays.length + 1; i++) {
            this.datacollection.labels.push(this.testData.weekdays[i]);
            this.datacollection.datasets[0].data.push(map_weekdays.get(i) ? map_weekdays.get(i) : 0);
          }
          let mondayOfWeek = new Date(
            salesData[0].setDate(
              salesData[0].getDate() - salesData[0].getDay() + (salesData[0].getDay() === 0 ? -6 : 1)));
          for (let j = 0; j < Object.keys(this.labelText).length; j++) {
            let element = this.labelText[Object.keys(this.labelText)[j]];
            element.date = new Date(salesData[0].getFullYear(), salesData[0].getMonth(), mondayOfWeek.getDate() + (7 * (j - 2)));
            let nextDate = new Date(element.date.getFullYear(), element.date.getMonth(), element.date.getDate() + 7);
            if (nextDate > new Date((salesData[0].getFullYear() + 1).toString())) nextDate = new Date(salesData[0].getFullYear(), 11, 31);
            element.text = element.date.getDate() + '.' 
              + (element.date.getMonth() + 1) + ' - ' + nextDate.getDate() + '.' + (nextDate.getMonth() + 1) + '.' + salesData[0].getFullYear();
            if (element.date > new Date((salesData[0].getFullYear() + 1).toString())) element.text = undefined;
          }
          this.periodType = period;
          break;
        case 'month':
          let daysOfMonth = [];
          salesData.forEach(sale_date => {
            daysOfMonth.push(sale_date.getDate()); // extract days of month (3rd, 5th, etc)
          });
          let map_months = daysOfMonth.reduce((acc, e) => acc.set(e, (acc.get(e) || 0) + 1), new Map());
          for (let i = 1; i < this.testData.months[salesData[0].getMonth()].days + 1; i++) {
            this.datacollection.labels.push(i);
            this.datacollection.datasets[0].data.push(map_months.get(i) ? map_months.get(i) : 0);
          }
          for (let j = 0; j < Object.keys(this.labelText).length; j++) {
            let element = this.labelText[Object.keys(this.labelText)[j]];
            element.date = new Date(salesData[0].getFullYear(), (salesData[0].getMonth() + j - 2), 1);
            element.text = this.testData.months[element.date.getMonth()].name + ' ' + element.date.getFullYear();
          }
          this.periodType = period;
          break;
        case 'year':
          let daysOfYear = [];
          salesData.forEach(sale_date => {
            daysOfYear.push(sale_date.getMonth() + ' ' + sale_date.getDate()); // extract days of year (3rd of May, 5th of Aug, etc)
          });
          // src https://stackoverflow.com/a/57028486
          let map_years = daysOfYear.reduce((acc, e) => acc.set(e, (acc.get(e) || 0) + 1), new Map());
          // generate labels and populate
          let i, month, day;
          for (i = 0, month = 0, day = 1; i < 365; i++, day++) {
            if (day > this.testData.months[month].days) {
              month++; day = 1;
            }
            this.datacollection.labels.push(this.testData.months[month].name + ' ' + day);
            this.datacollection.datasets[0].data.push(
              map_years.get(month.toString() + ' ' + day.toString()) ? map_years.get(month.toString() + ' ' + day.toString()) : 0);
          }
          this.datacollection.labels.sort(function(a, b){return a - b});
          this.labelText.currentSelected.text = salesData[0].getFullYear();
          this.labelText.next.text = this.labelText.currentSelected.text + 1;
          this.labelText.nextNext.text = this.labelText.next.text + 1;
          this.labelText.previous.text = this.labelText.currentSelected.text - 1;
          this.labelText.prePrevious.text = this.labelText.previous.text - 1;
          Object.keys(this.labelText).forEach(entry => { this.labelText[entry].date = new Date(this.labelText[entry].text.toString()); });
          this.periodType = period;
          break;
        case 'all':
          break;
        case 'range':
          break;
        default:
          break;
      }
    }
  }
}
</script>

<style scoped lang="scss">
@import "./assets/scss/var";

.single__text{
  position: relative;
  background-color: transparent;
  color: $secondary-light;
  vertical-align: middle;
  border: none;
  display: table-cell ;
  justify-content: center;
  margin: auto auto;
  height: 40px;
  font-size: 16px;
}
// .single__text:active,
// .single__text:focus,
.single__text:hover{
  transition: background-color 0s;
  background-color: transparent;
  color: #474747;
  outline: 0 !important;
  border: 0 !important;
  border-color: transparent;
  -moz-transition: none;
  -webkit-transition: none;
  -o-transition: color 0 ease-in;
  transition: none;
}
.single__text:active,
.single__text:focus,
.single__text:hover{
  border-color: transparent !important;
  background-color: transparent !important;
  border: 0 !important;
}
.active-period-text {
  background-color: transparent;
  color: #474747;
}
.btn-outline-secondary:active,
.btn-outline-secondary:hover,
.btn-outline-secondary:focus{
  background-color: #474747;
  color: #ffffff;
}
.width__setting{
  width: 100%;
  margin: 0 !important;
  white-space: nowrap;
  font-size: 16px;
  padding-left: 0 !important;
  padding-right: 0 !important;
}
.small {
  max-width: 1080px;
  margin-top: 60px;
} 
.dynamic__buttons{
  margin-top: 10px;
}
.w-0{
  @media screen and (max-width: 768px){
    width: 100%;
    height: 30px;
  }
}
</style>
