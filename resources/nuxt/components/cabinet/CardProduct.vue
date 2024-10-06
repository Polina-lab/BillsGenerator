<template>
    <div>
        <div class="container-fluid border__style">
            <h1 id="card-product-title">{{ $t('products.product_name') }}: {{ order.name }}</h1>
            <h2 v-if="firm" id="card-product-subtitle">{{ $t('bills.firm_name') }}: {{ firm.name }}</h2>
            <div class="row">
                <div class="col-12 col-md-5 order-md-first">
                    <table>
                        <tr>
                            <td class="blue__text">{{ $t('products.explantation') }}</td>
                            <td v-if="order.desc" class="black__text"><span v-html="order.name"></span></td>
                            <td v-else>---</td><!-- check -->
                        </tr>
                        <tr>
                            <td class="blue__text">{{ $t('bills.piece_price') }}</td>
                            <td  v-if="order.unit_price" class="black__text">{{ order.unit_price }}</td>
                            <td v-else>---</td>
                        </tr>
                        <tr>
                            <td class="blue__text">{{ $t('common.created_at') }}</td>
                            <td v-if="order.created_at" class="black__text">{{ order.created_at }}</td>
                            <td v-else>---</td>
                        </tr>
                        <tr>
                            <td class="blue__text">{{ $t('company.description') }}</td>
                            <td v-if="order.desc" class="black__text"><span v-html="order.desc"></span>  </td>
                            <td v-else>---</td>
                        </tr>
                        <tr>
                            <td class="blue__text">{{ $t('products.categories') }}</td>
                            <td v-if="order.categories_id" class="black__text">{{ order.categories_id }}</td>
                            <td v-else>---</td>
                        </tr>
                    </table>
                </div>
                <div class="col-12 col-md-4">
                    <table class="right__table">
                        <tr></tr>
                        <tr>
                            <td class="blue__text">{{ $t('bills.unit') }}</td>
                            <td v-if="order.unit" class="black__text">{{ order.unit }}</td>
                            <td v-else>---</td>
                        </tr>
<!--                        <tr>
                            <td class="blue__text">{{ $t('bills.publisher') }}</td>
                            <td  class="black__text">{{ currentUserName ? currentUserName.username : '-&#45;&#45;' }} ></td>
                        </tr>-->
                    </table>
                </div>
                <div class="col-12 col-md-3 order-first order-md-last button__float mt-md-3">
                    <n-link :to="'/admin/orders/'+$route.params.id+'/'" class="btn btn-outline-secondary btn-blue mt-4 width__setting float-right">{{ $t('bills.edit') }}</n-link>
                    <button class="btn btn-outline-secondary btn-red mt-4 width__setting float-right" @click="openConfirmation()">{{ $t('bills.delete') }}</button>
                </div>
            </div>
            <Modal :title="$t('common.delete')"
                    @closeModal="isConfirmationModalVisible = $event;"
                    v-if="isConfirmationModalVisible">
                <div class="alert alert-light">
                    <p class="text-danger">{{ $t('products.delete_product') +'?' }}</p>
                </div>
                <template v-slot:footer>
                    <button type="button"
                            class="btn btn-secondary"
                            @click="isConfirmationModalVisible = false">
                            {{ $t("common.cancel")}}
                    </button>
                    <button type="button" class="btn btn-danger"
                        @click="deleteProduct(); isConfirmationModalVisible = false;">{{ $t("common.delete") }}
                    </button>
                </template>
            </Modal>
        </div>
    </div>
</template>
<script>
import Modal from "@/components/common/Modal";
export default {
    name: "CardProduct",
    middleware:[ 'auth' ],
    components: { Modal },
    props:['order' , 'firm'],
    data() {
        return {
            isConfirmationModalVisible: false
        }
    },

    methods: {
        openConfirmation(){
            return this.isConfirmationModalVisible = true;
        },
        async deleteProduct(){
            await this.$store.dispatch("orders/deleteOrder", this.order.id )
            .then(
            resp => {
                this.closeFirmModal();
                this.$store.dispatch("orders/fetchOrdersList");
                this.$toast.success(resp.msg).goAway(3000);
            }).catch(error => { this.$toast.error(error.msg).goAway(3000); })
        },
    }
}
</script>
<style lang="scss" scoped>
@import '~assets/scss/_var.scss';

.border__style{
    font-family: 'Montserrat', sans-serif;
    border: 1px solid #7fcfed;
    padding-left: 40px;
}
#card-product-title{
    font-size: 24px;
    font-weight: 600;
    margin-top: 38px;
    @media screen and (max-width:768px){
        position: relative;
        display: flex;
        justify-content: center;
    }
}
#card-product-subtitle{
    font-size: 20px;
    color: $logo-blue;
    line-height: 0.8;
    @media screen and (max-width:768px){
        position: relative;
        display: flex;
        justify-content: center;
    }
}
table{
    font-size: 15px;
    margin-top: 30px;
    margin-bottom: 30px;
    @media screen and (max-width:768px){
        width: 100%;
    }
}
table tr {
    height: 35px;
}
table tr td:first-of-type{
    width: 180px;
    text-align: left;
     @media screen and (max-width:768px){
        width: 50%;
        text-align: center;
    }
}
table tr td:last-of-type{
    text-align: left;
    @media screen and (max-width:768px){
        text-align: center;
    }
}
.right__table{
    @media screen and (max-width:768px){
        margin-top: -60px;
    }
}
.button__float{
    position: static;
    float: right;
    @media screen and (max-width:768px){
        position: relative;
        display: flex;
        justify-content: center;
    }
}
.width__setting{
    width: 140px;
}
.blue__text{
    color: $logo-blue;
    vertical-align: top;
}
.btn-blue{
    border-color: $logo-blue;
    color: $logo-blue;
    height: 40px;
    &:hover,
    &:focus,
    &:active{
        background-color: $logo-blue;
        color: #ffffff;
        outline: 3px solid #6fbfdf;
    }
}
.btn-red{
    border-color: red;
    color: red;
    height: 40px;
    &:hover,
    &:focus,
    &:active{
        background-color: red;
        color: #ffffff;
        outline: 3px solid rgb(230, 136, 136);
    }
}
</style>
