<template>
  <div class="contacts">
    <div class="contacts__map">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2028.916245629115!2d24.749519716157813!3d59.43447140962977!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x469293603d53eb33%3A0xb74b792866d0af3d!2sESTONIA%20PST.%205!5e0!3m2!1sru!2sru!4v1594432294339!5m2!1sru!2sru"
        width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
        tabindex="0"></iframe>
    </div>
    <div class="contacts__detail">
      <img src="/img/svg/logo-horizontal.svg" alt="GLO Real OU">
      <p class="mt-3">Glo Real OU<br>
        Estonia pst. 5, III korrus. Tallin
      </p>
      <p>Tel. +372 6 035 352<br>
        Email: <a href="mailto:info@gloreal.ee" class="text-muted">info@gloreal.ee</a>
      </p>
      <a href="#" class="btn btn-outline-success">VOTA UHENDUST</a>
    </div>
  </div>
</template>

<script>
  export default {
    name: "ContactsBlock"
  }
</script>

<style scoped lang="scss">
  .contacts {
    position: relative;
    display: flex;
    /*box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.50);*/
    height: 438px;
    background: #ffffff;
  }
  .contacts:after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 90%;
    height: 4px;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
    -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
    z-index: -1;
  }

  .contacts__map {
    width: 50%;
    height: 100%;
    padding: 0;
  }

  .contacts__detail {
    width: 50%;
    padding: 80px 90px;
  }
  @media (max-width: 1200px) {
    .contacts {
      flex-direction: column;
      height: 876px;
    }
    .contacts__map, .contacts__detail {
      width: 100%;
    }
 .contacts__detail {
   text-align: center;
 }
  }

  .contacts__detail p {
    padding-left: 30px;
  }

  .contacts__detail a.btn {
    margin-left: 30px;
  }
</style>
