<template>
  <div >
    <Header />
    <div class="container mt-5 text-dark pt-5">
                <div class="d-flex align-items-center ">
                    <h2 class="text-white"><a href="#" class="fa-solid fa-arrow-left me-3 text-white" @click="$router.go(-1);"></a>Deposit History</h2>
                </div>
                <div class="card shadow-lg">
                    <div class="card-body">
                        <div class="col-sm-12 table-responsive">
                            <table id="ordertabtwo" class="  table table-hover ">
                                <thead>
                                    <tr role="row" class="border odd">
                                        <th scope="col" class="text-white border-white">#</th>
                                        <th scope="col" class="text-white border-white">Asset</th>
                                        <th scope="col" class="text-white border-white">Amount</th>
                                        <th scope="col" class="text-white border-white">Description</th>
                                        <th scope="col" class="text-white border-white">Status</th>
                                        <th scope="col" class="text-white border-white">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr role="row" class="odd" v-for="(his, i) in history">
                                        <th class="text-white" scope="row">{{ i + 1 }}</th>
                                        <td class="text-white">USDT</td>
                                        <td class="text-white">{{ his.amount }}</td>
                                        <td class="text-white">{{ his.description }}</td>
                                        <td class="text-white">{{ his.status }}</td>
                                        <td class="text-white">{{ moment(his.created_at).format('DD-MM-YYYY, hh:mm:ss A') }} </td>
                                    </tr>
                                </tbody>
                            </table>
                            <pagination v-model="page" :records="records" @paginate="history" />
                            <div class="row mt-5">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" id="ordertabtwo_info"
                                        role="status" aria-live="polite">
                                        <!-- Showing 1 to 4 of 4
                                    entries -->
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers"
                                        id="ordertabtwo_paginate">
                                        <ul class="pagination gap-2">
                                            <li class="paginate_button page-item previous"
                                                :class="page == 1 ? 'disabled' : ''"
                                                id="ordertabtwo_previous">
                                                <a href="#" aria-controls="ordertabtwo"
                                                    data-dt-idx="0" tabindex="0"
                                                    class="page-link" @click="prev">Previous</a>
                                            </li>
                                            <li class="paginate_button page-item next"
                                                :class="page ==last_page ? 'disabled' : ''"
                                                id="ordertabtwo_next">
                                                <a href="#" aria-controls="ordertabtwo"
                                                    data-dt-idx="2" tabindex="0"
                                                    class="page-link" @click="next">Next</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
  </div>
</template>


<script>
import pagination from "vue-pagination-2";
import moment  from "moment";


export default {
    name: "deposit_history",
    components: {
        pagination,
    },
    data() {
        return {
            apiUrl:process.env.mix_api_url,
            history:[],
            page:1,
            records:0,

            paginate:0,

            per_page: 1,
            last_page: 1,

        };
    },
    created() {
        this.deposit_history();
    },
    methods:{
        moment(date) {
           return moment(date);
       },
       deposit_history(){

        // axios.post(this.apiUrl+"api/deposit_history",{
        axios.post(this.apiUrl+ "api/deposit_history?page=" + this.page,{
            token:localStorage.token
        }).then(res=>{
            this.history = res.data.history.data;
            this.page = res.data.history.current_page;
            this.records = res.data.history.total;
            this.paginate = res.data.history;
            this.per_page = res.data.history.per_page;
            this.last_page = res.data.history.last_page;
        }).catch(err=>{
            console.log(err);
        });
       },
       prev() {
            if (this.page > 1) {
                this.page = this.page - 1;
                this.deposit_history();
            }
        },
        next() {
            if (this.page < this.last_page) {
                this.page = this.page + 1;
                this.deposit_history();
            }
        },
    }


};
</script>
<style >
.background, .main{
    background: #051c2c  !important;
}
.page-item.disabled .page-link {
    background-color: #24d1e5 !important;
    color: white !important;
    border-color: #24d1e5 !important;
}

.page-item.active .page-link {
    background-color: #24d1e5 !important;
    color: white !important;
    border-color: #24d1e5 !important;
}
</style>
