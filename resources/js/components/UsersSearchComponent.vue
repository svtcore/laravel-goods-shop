
<template>
    <div>
        <input type="text" id="input_search" placeholder="" class="form-control" v-model="keyword">
        <div class="list-group" v-for="user in Users" :key="user.user_id">
            <a onmouseover="this.style.border='1px solid #66c7ed'" onmouseout="this.style.border='1px solid lightgrey'" v-if="Users.length > 0" v-bind:href="'/admin/users/' + 
                user.user_id" class="product_search_result list-group-item list-group-item-action search_result">{{ user.user_fname + " " + user.user_lname}} <b>|</b> {{ user.user_phone }} </a>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            keyword: null,
            Users: []
        };
    },
    watch: {
        keyword(after, before) {
            if (this.keyword.length > 3) this.getResults();
        }
    },
    methods: {
        getResults() {
                axios.get("/admin/users/search", { params: { query: this.keyword } })
                    .then(res => this.Users = res.data)
                    .catch(error => {});
        }
    }
}
</script>