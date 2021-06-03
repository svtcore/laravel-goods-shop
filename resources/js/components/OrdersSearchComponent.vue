
<template>
    <div>
        <input type="text" id="input_search" placeholder="" class="form-control" v-model="keyword">
        <div class="list-group" v-for="order in Orders" :key="order.order_id">
            <a onmouseover="this.style.border='1px solid #66c7ed'" onmouseout="this.style.border='1px solid lightgrey'" v-if="Orders.length > 0" v-bind:href="'/admin/orders/' + order.order_id" class="product_search_result list-group-item list-group-item-action search_result">{{ order.created_at }} <b>|</b> {{ order.order_phone }} <b>|</b> {{ order.user_str_name + ", "+ order.user_house_num + ", " +order.user_apart_num }}</a>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            keyword: null,
            Orders: []
        };
    },
    watch: {
        keyword(after, before) {
            if (this.keyword.length > 3) this.getResults();
        }
    },
    methods: {
        getResults() {
                axios.get("/admin/orders/search", { params: { query: this.keyword } })
                    .then(res => this.Orders = res.data)
                    .catch(error => {});
        }
    }
}
</script>