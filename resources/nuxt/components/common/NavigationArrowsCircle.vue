<template>
  <div class="arrows-circle" @click="goToPage">
    <n-link :id="'page-1'" :to="'?page=1'" class="circle" v-if="prev>0"
       @click="currentPage(prev)">
       <i class="fa fa-ch"></i>
       <i class="fas fa-angle-double-left"></i>
    </n-link>
    <n-link :id="'page-' + prev" :to="'?page='+prev" class="circle" v-if="prev>1"
      @click="currentPage(prev)">
      <i class="fa fa-ch"></i>
      <i class="fas fa-chevron-left"></i>
    </n-link>
    <div class="pagination-center">
      <div class="pagination-center__pages" v-if="current <= pages">
          <!-- the v-if below shows number page in cases
            p-current-1 is current active page displayed to
            - - from current page show X pages to forward and backward (total = extra *2 sided + current) &&
            - - given current page, don't show more than X pages backward &&
            (more 2nd row explanation) (it is used to core 1st, 2nd, etc page edge case when current < offset)
            second '1' is required, because p in pages starts from 1
            - - limit maximum page number by total number of pages (pages var) &&
            - - cover edge case when pages with index less that 1 were shown
           -->
        <n-link
          v-for="(p, idx) in pages"
          v-if="p<=(extraFirstPagesOffset*2+1)&&
          !(current==p&&p<(extraFirstPagesOffset+1-p+1))&&
          (p+current-1-extraFirstPagesOffset <= pages)&&
          ((p+current-1-extraFirstPagesOffset)>0)"
          :to="'?page='+(p+current-1-extraFirstPagesOffset)"
          :id="'page-'+(p+current-1-extraFirstPagesOffset)"
          class="page-num circle"
          :class="{'active' : (p+current-1-extraFirstPagesOffset) === current}"
          :key="idx">
            {{p+current-1-extraFirstPagesOffset}}
        </n-link>
      </div>
      <div class="pagination">{{from}}-{{from+(+perPage) >= total ? total : from+(+perPage-1)}}/{{total}}</div>
    </div>
    <n-link :id="'page-' + next" :to="'?page='+next" class="circle" v-if="next <= pages-1"
      @click="currentPage(next)">
      <i class="fas fa-chevron-right"></i>
    </n-link>
    <n-link :id="'page-' + pages" :to="'?page='+pages" class="circle" v-if="next <= pages"
       @click="currentPage(pages)">
       <i class="fa fa-ch"></i>
       <i class="fas fa-angle-double-right"></i>
    </n-link>
  </div>
</template>

<script>
  export default {
    name: "NavigationArrowsCircle",
    props: ['current', 'from', 'perPage', 'total'],
    data() {
      return {
        prev: null,
        next: null,
        extraFirstPagesOffset: 2
      }
    },
    created() {
      this.prev = +this.current - 1
      this.next = +this.current + 1
    },
    computed: {
      pages() {
        return Math.ceil(this.total / this.perPage)
      }
    },
    methods: {
      currentPage(page) {
        this.$emit('goToPage', page);
      },
      goToPage(e) {
        if (e.target.id.includes('page-')) {
          const splitResult = e.target.id.split('-')
          this.$emit('goToPage', splitResult[1]);
        }
      }
    }
  }
</script>

<style scoped>
  .arrows-circle, .circle, .pagination-center__pages {
    display: flex;
    justify-content: center;
  }

  .circle {
    align-items: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #68bd45;
    color: #ffffff;
    margin: 0 5px;
    transition: .3s;
  }

  .circle:hover {
    background-color: #4f8737;
  }
  .circle.active {
    cursor: default;
    background-color: #ffffff;
    color: #a6a6a6;
    border: 1px solid #a6a6a6;
  }

  .circle i {
    font-size: 10px;
  }

  a:hover {
    text-decoration: none;
  }

  .pagination {
    position: relative;
    top: 2px;
  }

  .pagination-center {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

</style>
