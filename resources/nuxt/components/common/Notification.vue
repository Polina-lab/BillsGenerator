<template>
    <div><!--if invoice is unpaid--->
      <div v-if="unpaid" class="alert gray__block fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="closeNotification()">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="row">
          <div class="col-12 col-sm-2 col-md-2 col-lg-1  my-lg-auto pl-0 pl-md-5">
            <div class="row justify-content-center align-items-center vcenter ">
                <img id="triangle"  src="@/assets/img/cabinet/warning_bluespace.svg" alt="Triangle.">
            </div>
          </div>
          <div class="col-12 col-sm-8 col-md-8 col-lg-9 ml-0 ml-lg-5">
            <div class="cabinet__text">
              <p>{{ $t('User.your_monthly') }}<span id="daysRemainingActive"> {{ daysRemainingActive }} </span>
                <span v-if="$i18n.localeProperties.code === 'ru'">
                  <span v-if="daysRemainingActive === 0">
                    {{ $t('User.days_plural') }} <!-- 0, >= 5 -->
                  </span>
                  <span v-if="daysRemainingActive === 2 || daysRemainingActive === 3">
                    {{ $t('User.ru_days_few') }} <!-- 2-4 -->
                  </span>
                  <span v-if="daysRemainingActive === 1">
                    {{ $t('User.days_single') }} <!-- 1 -->
                  </span>
                </span>
                <span v-else>
                  <span v-if="daysRemainingActive === 0 || daysRemainingActive > 1">
                    {{ $t('User.days_plural') }} <!-- 0, >= 5 -->
                  </span>
                  <span v-if="daysRemainingActive === 1">
                    {{ $t('User.days_single') }} <!-- 1 -->
                  </span>
                </span>
                {{ $t('User.cabinet_blocked') }}
                <a :href="mailLink"> info@gloreal.ee</a>
               </p>
              <a id="gray__href" :href="`/admin/users/edit/${$auth.user.local_data.id}?page=package`">{{ $t('User.click_here') }}</a>
            </div>
          </div>
          <div class="col-12 col-sm-1 col-md-1 col-lg-1 my-auto ml-0 ml-xl-3 bottom-wallet">
            <div v-if="$route.path !== null && ($route.path.includes('bills'))" class="wallet__img">
              <img id="wallet" src="@/assets/img/bills/wallet_blue.svg" alt="Wallet.">
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
export default {
    name: "Notification",
    props:['unpaid'],
    computed: {
      daysRemainingActive() {
        var diffTime = (new Date(this.$store.state.current_team.active_until) - new Date());
        let diffInDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffInDays < 0 ? 0 : diffInDays;
      },
      mailLink() { // https://mailtolinkgenerator.com/
        switch (this.$i18n.localeProperties.code) {
          case 'ee':
            return 'mailto:info@gloreal.ee?'
            +'subject=Makseteatis&body=Lugupeetud%20tugimeeskond%2C%0A%0AOlen%20oma%20konto%20edukalt%20uuesti%20aktiveerinud%2C%20kuid'
            +'%20b%C3%A4nner%2C%20mis%20n%C3%A4itab%20mulle%2C%20et%20minu%20konto%20eest%20pole%20tasutud%2C%20on%20endiselt%20n%C3%A4htav.'
            +'%20Palun%20aidake%20mind%20selle%20probleemiga.%0A%0AKasutaja%20'
            + this.$auth.user.local_data.id + '.';
          case 'en':
            return 'mailto:info@gloreal.ee?'
              +'subject=Payment%20notification&body=Dear%20Support%20Team%2C%0A%0AI%20have%20successfully%20reactivated%20my%20account%2C%20'
              +'but%20the%20banner%20showing%20me%20that%20my%20account%20has%20not%20been%20paid%20for%20is%20still%20visible.'
              +'%20Please%20help%20me%20out%20with%20this%20issue.%0A%0AUser%20'
              + this.$auth.user.local_data.id + '.';
          case 'ru':
            return 'mailto:info@gloreal.ee?'
              +'subject=%D0%A3%D0%B2%D0%B5%D0%B4%D0%BE%D0%BC%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BE%20%D0%BF%D0%BB%D0%B0%D1%82%D0%B5%D0%B6%D0%B5'
              +'&body=%D0%A3%D0%B2%D0%B0%D0%B6%D0%B0%D0%B5%D0%BC%D0%B0%D1%8F%20%D1%81%D0%BB%D1%83%D0%B6%D0%B1%D0%B0%20%D0%BF%D0%BE%D0%B4%D0%B4%D0'
              +'%B5%D1%80%D0%B6%D0%BA%D0%B8%2C%0A%0A%D0%AF%20%D1%83%D1%81%D0%BF%D0%B5%D1%88%D0%BD%D0%BE%20%D0%BF%D0%BE%D0%B2%D1%82%D0%BE%D1%80%D0'
              +'%BD%D0%BE%20%D0%B0%D0%BA%D1%82%D0%B8%D0%B2%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BB%20%D1%81%D0%B2%D0%BE%D1%8E%20%D1%83%D1%87%D0%B5%D1'
              +'%82%D0%BD%D1%83%D1%8E%20%D0%B7%D0%B0%D0%BF%D0%B8%D1%81%D1%8C%2C%20%D0%BD%D0%BE%20%D0%B1%D0%B0%D0%BD%D0%BD%D0%B5%D1%80%2C%20%D0%BF'
              +'%D0%BE%D0%BA%D0%B0%D0%B7%D1%8B%D0%B2%D0%B0%D1%8E%D1%89%D0%B8%D0%B9%20%D0%BC%D0%BD%D0%B5%2C%20%D1%87%D1%82%D0%BE%20%D0%BC%D0%BE%D1'
              +'%8F%20%D1%83%D1%87%D0%B5%D1%82%D0%BD%D0%B0%D1%8F%20%D0%B7%D0%B0%D0%BF%D0%B8%D1%81%D1%8C%20%D0%BD%D0%B5%20%D0%B1%D1%8B%D0%BB%D0%B0'
              +'%20%D0%BE%D0%BF%D0%BB%D0%B0%D1%87%D0%B5%D0%BD%D0%B0%2C%20%D0%B2%D1%81%D0%B5%20%D0%B5%D1%89%D0%B5%20%D0%BE%D1%82%D0%BE%D0%B1%D1%80%'
              +'D0%B0%D0%B6%D0%B0%D0%B5%D1%82%D1%81%D1%8F.%20%D0%9F%D0%BE%D0%B6%D0%B0%D0%BB%D1%83%D0%B9%D1%81%D1%82%D0%B0%2C%20%D0%BF%D0%BE%D0%BC%'
              +'D0%BE%D0%B3%D0%B8%D1%82%D0%B5%20%D0%BC%D0%BD%D0%B5%20%D1%81%20%D1%8D%D1%82%D0%BE%D0%B9%20%D0%BF%D1%80%D0%BE%D0%B1%D0%BB%D0%B5%D0%BC%D0%BE%D0%B9.'
              +'%0A%0A%D0%9F%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%20'
              + this.$auth.user.local_data.id + '.';
          default:
            return 'mailto:info@gloreal.ee';
        }
      }
    },
    methods: {
      closeNotification() {
        this.$emit('closeNotification', false);
      },
    }
}
</script>
<style scoped lang="scss">
@import "./assets/scss/var";

.gray__block{
  padding: 30px 0px 40px 10px;
  border-radius: 10px;
  background-color: #e9ebeb;
  font-size: 15px;
  line-height: 1.8;
  @media screen and (max-width: 749px){
    font-size: 20px;
    line-height: 1.6;
  }
}

#triangle{
  max-width: 50px;
  width: 50px;
  height: auto;
}
.cabinet__text{
  color: #7e7d7d;
  @media screen and (max-width: 578px){
    margin: 10px;
  }
}
.cabinet__text p {
  text-align: justify;
}
.cabinet__text p #daysRemainingActive{
  color: #646464;
  font-weight: 600;
}
#gray__href{
  color: #646464;
  font-weight: 600;
  margin-top: 0px;
  font-size: 15px;
  line-height: 1.8;
  text-decoration: underline !important;
  text-decoration-line: underline !important;
  &:hover{
    color: $logo-blue;
  }
  @media screen and (max-width: 749px){
    font-size: 20px;
    line-height: 1.6;
    text-align: center;
  }
}
.close{
  position: absolute;
  top: 20px;
  right: 20px;  
}
#wallet{
  width: 50px;
  height: auto;
  @media screen and (max-width: 749px){
    width: 60px;
  }
}
.bottom-wallet{
  @media screen and (max-width: 900px) {
  position: absolute;
  left: auto;
  right: 6%;
  top: auto;
  bottom: 30px;
  }
  @media screen and (max-width: 578px){
    position: static;
    display: flex;
    justify-content: center;
    bottom: 10px;
  }
}

</style>
