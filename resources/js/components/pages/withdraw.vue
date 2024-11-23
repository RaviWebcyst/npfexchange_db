<template>
    <div>
        <Header />
        <div class="container-fluid mt-5 px-4 pt-5">
            <div class="row col-lg-6 mx-auto">
                <div class="d-flex">
                    <h3 style="color:white !important;"><a href="#" class="fa-solid fa-arrow-left me-3 text-white" @click="$router.go(-1);"></a>Withdraw</h3>
                </div>
                <div class="card d-block d-sm-none" >
                    <div class="card-body">
                        <div class="row align-items-center  my-3">
                            <div class="col-lg-3 mx-auto text-center mt-2">

                            </div>
                            <div class="col-lg-9 deposit_form mt-3">
                                <div class="">
                                    <div class="  rounded opacity_70_color">
                                        <form class="mt-4" @submit.prevent="withdraw">
                                        <div class="pb-2 border-bottom bg-transparent justify-content-between bg-light">


                                            <div class="  w-100">
                                                <label for="address" class="form-label ">Address </label>
                                                <input type="text"  v-modal="address" class="form-control" placeholder="Enter Address" @change="SelectAddress($event.target.value)">
                                                <div
                                                class="mb-4 w-100 mt-3 pb-2 border-bottom bg-transparent justify-content-between bg-light">
                                                <div class="text-muted">Network   <i class="fas fa-info-circle pointer" ></i>
                                                </div>
                                                <div class="d-flex bg-transparent justify-content-between bg-light"
                                                    @click="openModal">
                                                    <p class="mb-0"> {{ selectedNetwork }} </p>
                                                    <!-- <i class="fas fa-exchange-alt   pointer ms-3"></i> -->
                                                    <i class="fas fa-caret-down pointer"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-100">
                                            <label for="address" class="form-label ">Withdraw amount</label>
                                            <div class="mb-4 w-100"  >
                                                <input type="text" v-model="amount" class="form-control w-100" placeholder="Minimum 10">
                                            </div>
                                        </div>

                                        <li class="d-block" >
                                            <div class="  d-flex justify-content-between border-bottom pb-3">
                                                <div class="  ">Available Withdraw</div>
                                                <div class=" ">0.001 USDT </div>
                                            </div>

                                                <div class="row   ">
                                                    <div class="col-6">
                                                        <h6 class="pt-3 text-white">Receive amount</h6>
                                                        <h6 class="text-white">{{ amount }} USDT</h6>
                                                        <span style="font-size:10px"> Network Fee 1 USDT </span>
                                                    </div>
                                                    <div class="  col-6">
                                                        <button class="my-auto btn btn-info px-3 mt-4 " type="submit" style="min-width:auto !important" :disabled="spin"> Withdraw
                                                            <div class="spinner-grow spinner-grow-sm text-success" role="status" v-if="spin" ></div>
                                                        </button>
                                                    </div>
                                                </div>
                                        </li>
                                    </div>
                                </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" card d-none d-sm-block">
                    <div class=" card-body timeline-container">
                        <form class="mt-4" @submit.prevent="withdraw">
                      <ul class="tl">
                        <li>
                          <div class="item-icon"></div>
                            <div class="mb-4 w-100">
                                <label for="address" class="form-label ">Coin</label>
                                <select class="form-control  border-0">
                                    <option value="USDT" selected>USDT </option>
                                </select>
                            </div>
                        </li>
                        <li class="tl-item">
                          <div class="item-icon"></div>
                          <div class="  w-100">
                            <label for="address" class="form-label ">Withdraw to </label>
                            <input type="text"  v-modal="address" class="form-control" placeholder="Enter Address" @change="SelectAddress($event.target.value)">

                            <div class="mb-4 w-100 mt-3">
                                <select class="form-control  border-0"  @change="selectNetwork($event.target.value)">
                                    <option value="" selected>Select Network </option>
                                    <option value="tron">Tron (TRC20)</option>
                                    <option value="bnb">BNB Smart Chain (BEP20)</option>
                                    <option value="eth">Ethereum (ERC20)</option>
                                </select>
                            </div>
                        </div>
                        </li>


                        <li class="tl-item" >
                            <div class="item-icon"></div>
                            <div class="w-100">

                                <label for="address" class="form-label ">Withdraw amount</label>
                                <div class="mb-4 w-100" :class="{ 'd-none': !address, 'd-block': address }">

                                    <input type="text" v-model="amount" class="form-control w-100" placeholder="Minimum 10">
                                </div>
                          </div>

                        </li>
                        <li class="d-block" :class="{ 'd-none': !address, 'd-block': address }">
                            <div class="row d-flex justify-content-between border-bottom pb-3">
                            <div class="col-7 ">Available Withdraw</div>
                            <div class="col-5 text-end">{{ usdt }} USDT </div>
                          </div>
                          <div>
                            <div class="pt-3">Receive amount</div>
                            <div class="  d-flex justify-content-between ">
                                <div>
                                    <h3  class=" text-white pt-2 ">{{ amount }} USDT</h3>
                                    <span> Network Fee 0 USDT </span>
                                </div>
                                <!-- <div class="my-auto btn btn-info">Withdraw </div> -->
                                <button class="my-auto btn btn-info" type="submit" :disabled="spin">Withdraw
                                    <div class="spinner-grow spinner-grow-sm text-success" role="status"  v-if="spin"></div>
                                </button>

                              </div>
                          </div>
                        </li>
                      </ul>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8 mx-auto">
                    <!-- recent withdraw start  -->
                    <div class="d-flex align-items-center mt-5 ">
                      <h3 class="text-white my-auto">Recent Withdraws</h3>
                      <router-link :to="{name:'withdraw_history'}" class="text-white ms-auto my-auto">More ></router-link>
                  </div>
                  <div class="card shadow-lg mt-2">
                      <div class="card-body">
                          <div class="col-sm-12 table-responsive">
                              <table id="ordertabtwo" class="  table table-hover ">
                                  <thead>
                                      <tr role="row" class="border odd">
                                          <!-- <th scope="col" class="text-white border-white">Hash</th> -->
                                          <th scope="col" class="text-white border-white">Amount</th>
                                          <th scope="col" class="text-white border-white">Status</th>
                                          <!-- <th scope="col" class="text-white border-white">Remarks</th> -->
                                          <th scope="col" class="text-white border-white">Address</th>
                                          <th scope="col" class="text-white border-white">Network</th>
                                          <th scope="col" class="text-white border-white">Time</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr role="row" class="odd" v-for="(his, i) in history">
                                          <!-- <td class="text-white">{{ his.hash }}</td> -->
                                          <td class="text-white">${{ his.amount }}</td>
                                           <td class="text-white">{{ his.status }}</td>
                                           <td class="text-white">{{ his.address }}</td>
                                           <td class="text-white">{{ his.network }}</td>
                                          <!-- <td class="text-white">{{ his.remarks }}</td> -->
                                          <td class="text-white">{{ moment(his.created_at).format('DD-MM-YYYY, hh:mm:ss A') }} </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
              </div>

              <!-- recent withdraw end  -->
            </div>
        </div>
        <div class="modal fade" id="networkModal" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="networkModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <div class="modal-title text-white" id="networkModalLabel">Choose Network</div>
                    </div>
                    <div class="modal-body">
                        <div class="card bg-dark mb-3 pointer" @click="selectNetwork('Tron (TRC20)')"
                            :class="{ 'border border-white': selectedNetwork === 'Tron (TRC20)' }">
                            <div class="card-body">
                                <div class="card-title">Tron (TRC20)</div>
                            </div>
                        </div>
                        <div class="card  bg-dark mb-3 pointer" @click="selectNetwork('BNB Smart Chain (BEP20)')"
                            :class="{ 'border border-white': selectedNetwork === 'BNB Smart Chain (BEP20)' }">
                            <div class="card-body">
                                <div class="card-title">BNB Smart Chain (BEP20)</div>
                            </div>
                        </div>
                        <div class="card bg-dark mb-3 pointer" @click="selectNetwork('Ethereum (ERC20)')"
                            :class="{ 'border border-white': selectedNetwork === 'Ethereum (ERC20)' }">
                            <div class="card-body">
                                <div class="card-title">Ethereum (ERC20)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import pagination from 'vue-pagination-2';
import moment  from "moment";

export default {
    name: 'withdraw',
    components: {
        pagination
    },
    data() {
        return {
            apiUrl: process.env.mix_api_url,

            form: {
                hash: ""
            },
            disabled: false,
            spin: false,
            image: "",
            error: false,
            success: false,
            balance: 0,
            usdt: 0,
            message: "",
            is_deposit: false,
            form_pending: true,
            payments: false,
            toast: "hide",
            qrCodeUrl: '',
            selectedNetwork: '',
            depositAddress: '',
             address: '',
            set_address: true,
            amount : '',
            history:[]
        }
    },
    mounted() {
        this.set_address = false;
    },
    created() {
        this.getBalance();
        this.withdraw_history();
     },
    methods: {
    getBalance() {
         axios.post(this.apiUrl + "api/coinBalance", {
                    coin: "epin",
                    token: localStorage.token,
                }).then((res) => {
                    this.usdt = res.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        withdraw() {
            // this.disabled = true;
            var forms = new FormData();
            this.spin = true;
            forms.append('amount', this.amount);
            forms.append('network', this.selectedNetwork);
            forms.append('address', this.address);
            forms.append('token', localStorage.token);
            axios.post(this.apiUrl + "api/withdraw_live", forms).then(res => {
                this.$toaster.success(res.data.message);
                this.getBalance();
                this.withdraw_history();

                this.network = "";
                this.address = "";
                this.amount = "";
                // this.disabled = false;
                this.spin = false;
            }).catch(err => {
                this.spin = false;
                // this.disabled = false;
                console.log("errorrrrrrrrrrrr "+err);
                var message = err.response.data.message;

                if(typeof (message) == 'object'){
                    Object.values(message).forEach(msg => {
                        this.$toaster.error(msg[0]);
                });
                }
                else{
                    this.$toaster.error(message);
                }
                this.error = message;
                this.set_address = true;

            });
        },

        value() {
            this.toast = "show bg-success text-white";
            this.text = "Success";
        },
        openModal() {

            $('#networkModal').modal('show');
        },

        selectNetwork(network) {
            // if (network == 'tron' || network == 'Tron (TRC20)') {
            //     this.$toaster.error('This network is not available');
            //     return false;
            // }
            this.selectedNetwork = network;

            this.set_address = true;
            this.closeModal();

        },
        SelectAddress(address) {

            this.address = address;
             this.set_address = true;
            this.closeModal();

        },
        closeModal() {
            $('#networkModal').modal('hide');
        },
        moment(date) {
             return moment(date);
         },
         withdraw_history(){
          axios.post(this.apiUrl+ "api/withdraws?page=" + this.page,{
              token:localStorage.token
          }).then(res=>{
            console.log("res "+res);
              this.history = res.data.withdraw.data;
          }).catch(err=>{
              console.log(err);
          });
         },
    }
}
</script>


<style scoped>
.card_hover:hover {
    border: 1px solid #F0B90B !important;
}

.card_hover:hover .desktop_qr_image {
    padding: 0px !important;
    animation-duration: 0.8s;
    transition: padding 0.8s ease;
}

.timeline-container{
    font-family: "Roboto",sans-serif;
    margin:auto;
    display:block;
    position:relative;
  }

  .timeline-container ul.tl li {
      list-style: none;
      margin:auto;
      min-height:50px;
      border-left:1px solid rgb(255, 255, 255);
      padding:0 0 10px 30px;
      position:relative;
      display: flex;
      flex-direction: row;
  }


  .timeline-container ul.tl li:last-child{ border-left:0;}


  .timeline-container ul.tl li .item-icon {
    position: absolute;
    left: -11px;
    top: 0px;
    border: 2px solid rgb(45, 42, 42);
    border-radius: 25% !important;
    rotate: 45deg;
    background: #ffffff;
    height: 20px;
    width: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.timeline-container ul.tl li .item-icon::before {
    content: counter(timeline-counter);
    counter-increment: timeline-counter;
    color: rgb(45, 42, 42); /* Color of the number */
    font-size: 12px; /* Adjust the size */
    transform: rotate(-45deg);
}

.timeline-container ul.tl {
    counter-reset: timeline-counter; /* Reset the counter */
}
</style>
