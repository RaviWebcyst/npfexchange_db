<template>
    <div>
        <Header />
        <div class="container my-5 text-dark pt-5">
            <div class="d-flex align-items-center">
                <h3 class="text-white"><a href="#" class="fa-solid fa-arrow-left me-3  text-white" @click="$router.go(-1);"> Future</a></h3>
                <div class="ms-auto"> </div>
            </div>
            <div class="card my-4">
                <div class="card-body">
                    <div class="d-block d-sm-flex justify-content-between">
                        <h3 class="text-white pt-0 mt-0">Margin Balance </h3>
                        <h3 class="text-white mt-3 mt-sm-0 d-block d-sm-none">{{ Number(usd).toFixed(4) }}  <small class="pt-2">USDT</small></h3>
                        <div class="pt-1">
                            <a href="#" class="text-white bg-black px-2 py-1" data-bs-toggle="modal" data-bs-target="#transfer_usdt" @click="setCoin('epin')">Transfer</a>
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
                                                        <div class="form-floating my-3">
                                                                <input type="text" class="form-control rounded" v-model="from_coin"  readonly >
                                                                <label  class="text-white">From</label>
                                                            </div>
                                                            <div class="text-end">
                                                                <i class="fas fa-exchange-alt text-main rotate-icon rotate-90" @click="swapValues"></i>
                                                           </div>

                                                            <div class="form-floating my-3">
                                                                <input type="text" class="form-control rounded"  v-model="to_coin"  readonly>
                                                                <label  class="text-white">To</label>
                                                            </div>
                                                            <div class="form-floating my-3">
                                                                <input type="text" class="form-control rounded"  value="USDT" readonly>
                                                                <label  class="text-white">Coin</label>
                                                            </div>
                                                            <div class="text-end text-info"> Avaliable: {{ usdt }} USDT</div>
                                                            <!-- <div class="form-floating my-3">
                                                                <input type="number" class="form-control rounded"  v-model="transfer.amount" required>
                                                                <label  class="text-white">Amount</label>
                                                            </div> -->
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" v-model="transfer.amount" required>
                                                                <span class="input-group-text pointer text-main " id="basic-addon1" @click="setMaxAmount" style="background-color: #495c6e; border: none; ">
                                                                   MAX
                                                                </span>
                                                            </div>

                                                             <button class="btn butn rounded text-dark my-4" type="button" @click="transferCoin" :disabled="disable || coin.balance<=0">Confirm
                                                                <span class="spinner-grow spinner-grow-sm pl-2" role="status" aria-hidden="true" v-if="disable"></span>
                                                            </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        </div>
                    </div>

                    <h3 class="text-white mt-3 mt-sm-0 d-none d-sm-block">{{ Number(usd).toFixed(4) }}  <small class="pt-2">USDT</small></h3>
                    <div class="mt-3">
                        <small>Today’s Realized PnL: 0</small>

                    </div>
                    <div class="row mt-4">
                        <div class="col-4">
                            <small>Wallet Balance(USDT)</small>
                       <h5 class="text-white">{{ usd }}</h5>
                       </div>
                       <div class="col-4">
                        <small>Unrealized PNL</small>
                        <h5 class="text-white">0</h5>
                       </div>
                    </div>

                </div>
            </div>

        </div>
        <Footer />
    </div>
</template>
<script>
export default {
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
            usd : 0,
            usdt:0,
            coin: localStorage.coin??"BTC",
            transfer:{
                amount:"",
                coin:""
            }
        };
    },
    created() {
        this.setBalance();
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

         this.usd = await this.getBalance("usd");
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

        setCoin(value){
            this.transfer.coin = value;
        },


    }
};
</script>
