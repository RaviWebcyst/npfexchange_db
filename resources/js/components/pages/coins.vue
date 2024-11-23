<template>
    <div>
        <Header />
        <div class="container my-5 text-dark pt-5">
            <div class="d-flex align-items-center">
                <!-- <h3 class="text-white"><a href="#" class="fa-solid fa-arrow-left me-3  text-white" @click="$router.go(-1);">Spot</a></h3> -->
                <h2 class="text-white"><a href="#" class="fa-solid fa-arrow-left me-3 text-white" @click="$router.go(-1);"></a>Spot</h2>

                <div class="ms-auto"> </div>
            </div>
            <div class="card my-4">
                <div class="card-body">
                    <div class="d-block d-sm-flex justify-content-between">
                        <div class="text-white pt-0 mt-0">Estimated Balance </div>
                        <h3 class="text-white mt-3 mt-sm-0 d-block d-sm-none">{{ Number(total_balance).toFixed(4) }}  <small class="pt-2">USDT</small></h3>
                        <div class="pt-1">
                            <router-link :to="{name:'deposit'}" class=" text-white bg-black px-2 py-1 ">Deposit</router-link>
                            <router-link :to="{name:'withdraw'}"  class=" text-white bg-black px-2 py-1">Withdraw</router-link>
                            <a href="#" class=" text-white bg-black px-2 py-1" data-bs-toggle="modal" data-bs-target="#transfer_usdt" @click="setCoin('epin')">Transfer</a>
                            <router-link :to="{name:'transfer_history'}"  class=" text-white bg-black px-2 py-1">History</router-link>
                            <!-- <a href="#" class="px-2 text-white">Transfer</a> -->
                             <!-- transfer modal for usdt  -->
                             <div class="modal fade " id="transfer_usdt" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="transfer_usdtModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog centered " style="margin-top:150px">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="transfer_usdt">Transfer USDT
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close" ></button>
                                                </div>
                                                <div class="modal-body" >
                                                        <div class="form-floating my-2">
                                                                <input type="text" class="form-control rounded" v-model="from_coin" readonly >
                                                                <label  class="text-white">From</label>
                                                            </div>
                                                            <div class="text-end">
                                                                 <i class="fas fa-exchange-alt text-main rotate-icon rotate-90" @click="swapValues"></i>
                                                            </div>


                                                            <div class="form-floating my-2">
                                                                <input type="text" class="form-control rounded"  v-model="to_coin" readonly>
                                                                <label  class="text-white">To</label>
                                                            </div>
                                                            <div class="form-floating my-3">
                                                                <input type="text" class="form-control rounded"  value="USDT" readonly>
                                                                <label  class="text-white">Coin</label>
                                                            </div>
                                                            <div class="text-end text-info"> Avaliable: {{ usdt }} USDT</div>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" v-model="transfer.amount" required>
                                                                <span class="input-group-text pointer text-main " id="basic-addon1" @click="setMaxAmount" style="background-color: #495c6e; border: none; ">
                                                                   MAX
                                                                </span>
                                                            </div>

                                                            <!-- <input type="text" class="form-control" v-model="transfer.amount" required  >
                                                            <span   id="basic-addon1"
                                                                @click="setMaxAmount">
                                                                <i :class="showPassword ? 'fa fa-eye' : 'fa fa-eye-slash'" class="text-white" @click="setMaxAmount"></i>
                                                            </span> -->
                                                             <button class="btn butn rounded text-dark my-4" type="button" @click="transferCoin" :disabled="disable || coin.balance<=0">Confirm
                                                                <span class="spinner-grow spinner-grow-sm pl-2" role="status" aria-hidden="true" v-if="disable"></span>
                                                            </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        </div>
                    </div>

                    <h3 class="text-white mt-3 mt-sm-0 d-none d-sm-block">{{ Number(total_balance).toFixed(4) }}  <small class="pt-2">USDT</small></h3>

                </div>
            </div>

            <div class="card px-5">
                <div class="card-header">
                    <!-- <h3 class="card-title text-white">Spot</h3> -->
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-center shadow table-borderless">
                        <thead class="table-head rounded-top">
                            <tr>
                                <th scope="col" class="text-light">Coin</th>
                                <th scope="col" class="text-light">Balance</th>
                             </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="3" class="text-center">
                                    <div class="spinner-border text-light" role="status"> </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-if="!loading && coins.length == 0">
                            <tr>
                                <td colspan="4" class="text-center text-white">
                                    <div>No Data Found</div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-if="coins.length > 0">
                            <tr>
                                <td class="text-white"><span class="me-2"><img :src="apiUrl + 'coins/USDT.png'" alt="" width="30" class="rounded-circle"></span>USDT</td>
                                <td class="text-white">{{ usdt }}</td>
                            </tr>
                            <tr v-for="(coin, i) in coins">
                                <td class="text-white"><span class="me-2"><img :src="apiUrl + (coin.name).toLowerCase() + '.svg'" alt="" width="30" class="rounded-circle"></span>{{ coin.name }}</td>
                                <td class="text-white">{{ coin.balance }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
<script>
import QrcodeVue from 'qrcode.vue'
export default {
    components:{
        QrcodeVue
    },
    data() {
        return {
            url: process.env.mix_api_url,
            apiUrl: process.env.mix_api_url,
            coins: [],
            from_coin : 'Spot',
            to_coin : 'USDⓈ-M Futures',

            disable: false,
            address: "",
            loading: false,
            error: false,
            recieve:{
                coin:""
            },
            errors:{},
            gasFees:"",
            bnb:0,
            total_balance:0,
            size:300,
            total: "",
            usdt : 0,
            coin: localStorage.coin??"BTC",
            transfer:{
                amount:"",
                coin:""
            }
        };
    },
    created() {
        this.getCoins();
        this.setBalance();
        this.getTotalBalance();
    },
    methods: {
        swapValues() {
            const temp = this.from_coin;
            this.from_coin = this.to_coin;
            this.to_coin = temp;
            this.setBalance();
        },
        setMaxAmount() {
            this.transfer.amount = this.usdt;
        },
        transferCoin(){
            if(confirm('Are you sure want to transfer')){

                this.disable = true;
                axios.post(this.apiUrl+"api/transfer_usdt",{
                    token:localStorage.token,
                    amount:this.transfer.amount,
                    to_coin:this.to_coin,
                    from_coin: this.from_coin,
                }).then(res=>{
                    console.log(res);
                    this.transfer.amount = '',
                    this.disable = false;
                    this.setBalance();
                    this.getTotalBalance();
                    this.$toaster.success(res.data.message);
                    $('.btn-close').click();

                }).catch(err=>{
                    console.log(err);
                    this.disable = false;
                    this.$toaster.error(err.response.data.message);
                });
            }
        },
        async  setBalance(){

            var type = this.from_coin == 'Spot' ? 'epin': 'usd';
         this.usdt = await this.getBalance(type);
         },

        async getBalance(coin) {

            var res =  await axios
                .post(this.url + "api/coinBalance", {
                    coin: coin,
                    token: localStorage.token,
                });

            return res.data;
        },
        getTotalBalance(){
            axios
                .get(this.url + "api/total_balance",{
                    params:{
                        token:localStorage.token
                    }
                })
                .then((res) => {



                    this.total_balance = res.data.total_balance;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        value() {
            this.$toaster.success("Link Copied");
         },
        moment(date) {
            return moment(date);
        },

        setCoin(value){
            this.transfer.coin = value;
        },
        getCoins() {
            this.coins=[];
            this.loading = true;
            axios.post(this.apiUrl + "api/getCoins", {
                token: localStorage.token,
                coin: this.recieve.coin,
            }).then(res => {
                this.coins = res.data.coins;
                this.bnb= res.data?.coins[0].balance;
                 this.loading = false;
            }).catch(err => {
                console.log(err);
                this.loading = false;
            });
        },

        setRecieve(coin) {
            this.recieve.coin = coin;
        },
    }
};
</script>
<style scoped>

 .rotate-90 {
    transform: rotate(90deg);
    transform-origin: center;
}</style>
