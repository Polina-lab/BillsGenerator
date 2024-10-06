import Vue from "vue"
import { Swiper as SwiperClass, Pagination, Navigation, Autoplay } from "swiper/swiper.esm"
import getAwesomeSwiper from "vue-awesome-swiper/dist/exporter"

// import style
import "swiper/swiper-bundle.min.css"

SwiperClass.use([Pagination, Navigation, Autoplay])
Vue.use(getAwesomeSwiper(SwiperClass))

const { directive } = getAwesomeSwiper(SwiperClass)
Vue.directive("swiper", directive)

// Source https://github.com/surmon-china/vue-awesome-swiper/issues/570#issuecomment-922110920