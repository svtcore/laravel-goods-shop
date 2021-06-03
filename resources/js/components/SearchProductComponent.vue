<template>
    <div class="main_search_block">
          <input type="text" id="input_search" size="50" maxlength="50" class="form-control" placeholder="" v-model="keyword" />
          <div class="list-group" v-for="product in Products" :key="product.product_id">
            <a onmouseover="this.style.border='1px solid #66c7ed'" onmouseout="this.style.border='1px solid lightgrey'" v-if="Products.length > 0" v-bind:href="'/products/' + 
                product.product_id" class="product_search_result list-group-item list-group-item-action search_result">{{ product.user_language_product_name }} - <b>{{ product.product_price }} ₴</b> ({{ product.user_language_category_name }})</a>
          </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            keyword: null,
            Products: []
        };
    },
    watch: {
        keyword(after, before) {
            if (this.keyword.length > 3) this.getResults();
        }
    },
    methods: {
        getResults() {
                axios.get("/products/search", { params: { query: this.keyword } })
                    .then(res => this.Products = res.data)
                    .catch(error => {});
        }
    }
}
</script>