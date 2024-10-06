<template>
    <div>
        <div class="people__box">
            <h1 id="people__header">{{ $t('about.meet') }}</h1>
        </div>
        <div class="triangle"></div>

    <div class="mobile">
        <swiper class="swiper-container" ref="peopleSwiper" :options="swiperOptions">
            <swiper-slide v-for="(i) in peoples" :key="i.id" class="card" :class="[{green__theme: i.id === 1}, {blue__theme: i.id === 2}, {yellow__theme: i.id === 3}, {card__even: (i.id % 2) == 0 }]">
                <div class="card__thumb">
                    <div class="img__box">
                        <img class="portret" :src="getImage(i.url)" :alt="i.alt">
                    </div>
                </div>
                <div class="card__content">
                    <div class="text__box">
                        <h2 class="person-name">{{ i.title }}</h2>
                        <h3 class="person-position">{{ i.subtitle }}</h3>
                        <p class="person-description">{{ i.text }}</p>
                        <div class="icon__box">
                            <img :src="getIcon(i.icon)" :alt="i.iconAlt">
                            <a href="">href</a>
                        </div>
                    </div>
                </div>
            </swiper-slide>
        </swiper>
        <div class="strip">
            <div class="row justify-content-center mt-3 mt-md-0 ring__box">
                <div class="ring swiper-pagination" id="bullet" slot="pagination"></div>
            </div>
        </div>
    </div>
    
    <div class="desktop">
        <article v-for="(i) in peoples" :key="i.id" class="card" :class="[{green__theme: i.id === 1}, {blue__theme: i.id === 2}, {yellow__theme: i.id === 3}, {card__even: (i.id % 2) == 0 },((i.id === isSelectedPeople()) ? 'visible' : 'hidden')]">
            <div class="card__thumb">
                <div class="img__box">
                    <img class="portret" :src="getImage(i.url)" :alt="i.alt">
                </div>
            </div>
            <div class="card__content">
                <div class="text__box">
                    <h2 class="person-name">{{ i.title }}</h2>
                    <h3 class="person-position">{{ i.subtitle }}</h3>
                    <p class="person-description">{{ i.text }}</p>
                    <div class="icon__box">
                        <img :src="getIcon(i.icon)" :alt="i.iconAlt">
                        <a href="">href</a>
                    </div>
                </div>
            </div>
        </article>
    </div>

    </div>
</template>
<script>
export default {
    name: 'People',
    data:()=>({
    swiperOptions: {
        slidesPerView: 1,
        spaceBetween: 10,
        centeredSlides: true,
        loop: true,
        speed: 1000,
        autoHeight: true,
        autoplay: {
        delay: 4000,
        disableOnInteraction: true
      },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
      },
        realIndex: 0,
        selected: '',
        peoples: [
            {
                id: 1,
                url: "person1.png",
                alt: "Person 1.",
                title: "Name Surname",
                subtitle: "digital director",
                text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
                icon: "insta.svg",
                iconAlt: "",
                href: "@namesurname"
            },
            {
                id: 2,
                url: "person2.png",
                title: "Name",
                subtitle: "frontend developer",
                text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
                icon: "facebook.svg",
                href: "@name"
            },
            {
                id: 3,
                url: "person3.png",
                title: "Surname",
                subtitle: "backend developer",
                text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
                icon: "insta.svg",
                href: "@surname"
            }
        ]       
    }),
    methods:{
      getImage(url) {
        return require('@/assets/img/about/persons/' + url);
      },
      isSelectedPeople(){
        return this.selected = this.peoples[0].id;
      },
      getIcon(icon){
        return require('@/assets/img/about/' + icon);
      },
      toNext(){
        this.peoples.push(this.peoples.shift());
      }
    }
}
</script>
<style lang="scss" scoped>
@import "./assets/scss/_var.scss";
.people__box{
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #474747;
    width: 100%;
    height: 135px;

}
#people__header{
    font-family: 'Montserrat', sans-serif;
    color: #ffffff;
}
.triangle{
    height: 0px;
    width: 100%;
    &:after{
        content: '';
        position: relative;
        display: flex;
        flex-direction: column;
        top: -20px;
        margin-right: auto;
        margin-left: auto;
        z-index: 5;
        width: 40px;
        height: 40px;
        background-color: #474747;
        transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        }
    }
.card__even{
    direction: rtl; 
    grid-auto-flow: dense;
}
.card{
    position: relative;
    display: grid;
    padding: 60px 210px 0px 120px;
    border: none;
    overflow: hidden;
    @media screen and (max-width: 1200px){
        padding: 60px 110px 0px 20px;
    }
    @media screen and (max-width: 991px){
        padding: 60px 5px 0px 0px;
    }
}
.green__theme{
    background-image: linear-gradient(to top, #e7f6ec, #e6f6f5, #ecf5f9, #f2f5f8, #f5f5f5);
    .person-position{
        color: $logo-green;
    }
    .icon__box{
        color: $logo-green;
    img{
        filter: invert(57%) sepia(7%) saturate(5540%) hue-rotate(85deg) brightness(89%) contrast(74%);
        }
    }
}
.blue__theme{
    background-image: linear-gradient(to top, #dff5fb, #e6f4fc, #edf4fb, #f2f4f9, #f5f5f6);
    padding-left: 210px;
    padding-right: 240px;
    .portret{
        transform:translate(-55%,-50%);
    }
    .person-position{
        color: $logo-blue;
    }
    .icon__box{ 
        color: $logo-blue;
    img{
        filter: invert(61%) sepia(87%) saturate(2269%) hue-rotate(161deg) brightness(93%) contrast(85%);
        }
    }
    @media screen and (max-width: 1200px){
        padding-left: 110px;
        padding-right: 140px;
    }
    @media screen and (max-width: 991px){
        padding-left: 10px;
        padding-right: 0px;
    }
}
.yellow__theme{
    padding-left: 170px;
    background-image: linear-gradient(to top, #fff8ea, #fff5ef, #fff5f6, #f9f6f8, #f6f6f6);
    .person-position{
        color: $logo-orange;
    }
    .icon__box{ 
        color: $logo-orange;
    img{
        filter: invert(86%) sepia(18%) saturate(4021%) hue-rotate(335deg) brightness(104%) contrast(98%);
        }
    }
    @media screen and (max-width: 1200px){
        padding-left: 70px;
    }
    @media screen and (max-width: 991px){
        padding-left: 0px;
    }
}
.card__content {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
}
.card__thumb,
.card__content {
    grid-column: 1/2;
    grid-row: 1/2;
}
.text__box{
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    font-family: 'Montserrat', sans-serif;
    width: 440px;
    padding-top: 20px;
    @media screen and (max-width: 1000px){
        background-color: rgba(255, 255, 255, 0.9);
        padding: 20px 20px 0px 20px;
    }
    @media screen and (max-width: 991px){
        width: 65%;
    }
    @media screen and (max-width: 700px){
        width: 45%;
        padding: 10px 10px 0px 10px;
    }
}
.person-name{
    font-size: 36px;
    @media screen and (max-width: 991px){
        font-size: 30px;
        text-align: center;
        padding-top: 20px;
    }
}
.person-position{
    font-size: 22px;
    line-height: 2;
    text-transform: uppercase;
    @media screen and (max-width: 991px){
        font-size: 20px;
        text-align: center;
    }
}
.person-description{
    font-size: 16px;
    color: $secondary-color;
    padding: 16px 0px;
    min-height: 380px;
    text-align: justify;
    direction: initial;
    @media screen and (max-width: 991px){
        padding: 6px 0px;
        font-size: 20px;
        min-height: 510px;
    }
    @media screen and (max-width: 700px){
        font-size: 16px;
        min-height: 455px;
        max-height: 455px;
        overflow: hidden;
    }
}
.icon__box{
    display: flex;
    flex-direction: row;
    direction: initial;
    align-items: center;
    img{
        width: 40px;
        height: auto;
    }
    a{
        font-size: 26px;
        font-weight: 600;
        margin-left: 20px;
        color: inherit;
    }
    @media screen and (max-width: 991px){
        justify-content: center;
    }
}
.img__box{
    position: relative;
    z-index: 1;
    height: 670px;
    width: 390px;
    background: none;  
    @media screen and (max-width: 991px){
        height: 760px;
        width: 190px;
    } 
}
.portret{
    position: absolute;
    width: auto;
    height: 100%;
    object-fit: contain;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
}
.strip{
    width: 100%;
    height: 8px;
    background-color: #474747;
    display: flex;
    justify-content: center;
    align-items: center;
    @media screen and (max-width: 991px){
        height: 35px;
    }
}
.ring__box{
  position: relative;
  display: flex;
  margin: auto;
}
.ring{
  display: none;
  @media screen and (max-width: 991px){
    display: block;
    margin: -10px auto 0px auto;
  }
}
.active__green{
  background-color: $logo-green;
}
.swiper-pagination {
  position: relative;
  z-index: 999;
  transform: scale(2.8);
  width: 60px;
  height: 20px;
  color: #ffffff;
  background-color: translate;
  display: flex;
  justify-content: space-around;
  align-items: center;
  filter: invert(52%) sepia(11%) saturate(2920%) hue-rotate(83deg) brightness(97%) contrast(88%) !important;
}
@media (max-width: 999px){
  .desktop{
    display: none;
  }
}
@media (min-width: 1000px){
  .mobile{
    display: none;
  }
}

</style>