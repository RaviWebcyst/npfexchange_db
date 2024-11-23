<template>
    <div>
        <Header />
        <div class="container-fluid mt-5 px-4 pt-5">
            <div class="row col-lg-6 mx-auto">
                <div class="d-flex">
                    <h3 style="color:white !important;">Deposit</h3>
                </div>
                <div class="card d-block d-sm-none"
                    :class="{ 'd-none': !isNetworkSelected, 'd-block': isNetworkSelected }">
                    <div class="card-body">
                        <div class="row align-items-center  my-3">
                            <div class="col-lg-3 mx-auto text-center mt-2">
                                <div v-if="qrCodeUrl" class="mx-auto text-center">
                                    <img :src="qrCodeUrl" alt="QR Code" class="img-fluid " style="max-width: 250px;" />
                                </div>
                            </div>
                            <div class="col-lg-9 deposit_form mt-3">
                                <div class="">
                                    <div class="border rounded p-3 opacity_70_color">
                                        <div
                                            class=" pb-2 border-bottom bg-transparent justify-content-between bg-light">
                                            <div class="text-muted">Network</div>
                                            <div class="d-flex bg-transparent justify-content-between bg-light"
                                                @click="openModal">
                                                <p class="mb-0"> {{ selectedNetwork }} </p>
                                                <i class="fas fa-exchange-alt fs-5 pointer ms-3"></i>
                                            </div>
                                        </div>
                                        <div class=" mt-2   bg-transparent justify-content-between bg-light">
                                            <div class="text-muted">Deposit Address</div>
                                            <div class="d-flex bg-transparent justify-content-between bg-light">
                                                <p class="mb-0" style="word-wrap: break-word; max-width: 80%;">
                                                    {{ depositAddress }} </p>
                                                <i class="fa-solid fa-copy fs-5 pointer ms-3"
                                                    @click="copyToClipboard(depositAddress)"></i>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" card d-none d-sm-block">
                    <div class=" card-body timeline-container">
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
                          <div class="mb-4 w-100">
                            <label for="address" class="form-label">Select Network</label>
                            <select class="form-control  border-0" @change="selectNetwork($event.target.value)">
                                <option value="" selected disabled>Select Network </option>
                                <option value="tron">Tron (TRC20)</option>
                                <option value="bnb">BNB Smart Chain (BEP20)</option>
                                <option value="eth">Ethereum (ERC20)</option>
                            </select>
                        </div>
                        </li>
                        <li class="tl-item  dashed">
                          <div class="item-icon"></div>
                          <div class="mb-4 w-100" >
                            <label for="address" class="form-label ">Deposit Address</label>
                            <div class="card  border d-flex p-2 card_hover" :class="{ 'd-none': !desktop_card, 'd-block': desktop_card }">
                                <div class="row d-flex justify-content-between my-auto">
                                    <div v-if="qrCodeUrl" class="my-auto col-3">
                                        <img :src="qrCodeUrl" alt="QR Code" class="img-fluid desktop_qr_image p-2" style="height:100px; width:100px;" />
                                    </div>
                                    <div class="col-7 my-auto">
                                        <div class="text-muted">Address</div>
                                        <div>{{ depositAddress }}</div>
                                    </div>
                                    <div class="col-1 my-auto me-4 text-end">
                                        <i class="fa-solid fa-copy fs-5 pointer ms-3" @click="copyToClipboard(depositAddress)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </li>
                      </ul>
                    </div>
                    <!-- <div class="mb-4">
                        <label for="address" class="form-label ">Coin</label>
                        <select class="form-control  border-0">


                            <option value="USDT" selected>USDT </option>

                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="form-label ">Select Network</label>
                        <select class="form-control  border-0" @change="selectNetwork($event.target.value)">

                            <option value="" selected>Select Network </option>

                            <option value="tron">Tron (TRC20)</option>
                            <option value="bnb">BNB Smart Chain (BEP20)</option>
                            <option value="etc">Ethereum (ERC20)</option>
                        </select>
                    </div>
                    <div class="mb-4" :class="{ 'd-none': !desktop_card, 'd-block': desktop_card }">
                        <label for="address" class="form-label ">Deposit Address</label>
                        <div class="card  border d-flex p-2 card_hover">
                            <div class="row d-flex justify-content-between my-auto">
                                <div v-if="qrCodeUrl" class="my-auto col-3">
                                    <img :src="qrCodeUrl" alt="QR Code" class="img-fluid desktop_qr_image p-2"
                                        style="height:100px; width:100px;" />

                                </div>
                                <div class="col-7 my-auto">
                                    <div class="text-muted">Address</div>
                                    <div>{{ depositAddress }}</div>
                                </div>
                                <div class="col-1 my-auto me-4 text-end">
                                    <i class="bi bi-clipboard fs-5 pointer ms-3"
                                        @click="copyToClipboard(depositAddress)"></i>
                                </div>
                            </div>
                        </div>

                    </div> -->

                </div>
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
                        <div class="card bg-dark mb-3 pointer" @click="selectNetwork('tron')"
                            :class="{ 'border border-white': selectedNetwork === 'tron' }">
                            <div class="card-body">
                                <div class="card-title">Tron (TRC20)</div>
                            </div>
                        </div>
                        <div class="card  bg-dark mb-3 pointer" @click="selectNetwork('bnb')"
                            :class="{ 'border border-white': selectedNetwork === 'bnb' }">
                            <div class="card-body">
                                <div class="card-title">BNB Smart Chain (BEP20)</div>
                            </div>
                        </div>
                        <div class="card bg-dark mb-3 pointer" @click="selectNetwork('eth')"
                            :class="{ 'border border-white': selectedNetwork === 'eth' }">
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
import QRCode from 'qrcode';

export default {
    name: 'deposit',
    components: {
        pagination
    },
    data() {
        return {
            apiUrl: process.env.mix_api_url,

            form: {
                amount: "",
                hash: ""
            },
            disabled: false,
            spin: true,
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
            isNetworkSelected: true,
            desktop_card: true,
        }
    },
    mounted() {
        this.getDepositAddress();
        this.getLiveDeposits();
        // this.getHistory();
        // this.openModal();
        this.isNetworkSelected = false;
        this.desktop_card = false;
        this.checkScreenSize(); // Check screen size on component mount
        window.addEventListener('resize', this.checkScreenSize);

    },
    created() {
        this.qrCodeUrl = QRCode.toDataURL(this.depositAddress, { margin: 110 })
    },
    methods: {
        getLiveDeposits() {
            axios.get(this.apiUrl + "api/getLiveDeposits", {
                params: {
                    token: localStorage.token
                }
            })
                .then(res => {

                }).catch(err => {

                    console.log(err);
                });
        },
        getDepositAddress(type="address") {

            axios
                .post(this.apiUrl + "api/get_address", {
                    token: localStorage.token,
                    type:type
                })

                .then(res => {
                    console.log(res.data.address);
                    if(res.data.address == null || res.data.address == ''){
                        this.$toaster.error("Deposit address not found ,contact Admin");
                        return false;
                    }
                    this.depositAddress = res.data.address;
                    
                    this.generateQRCode();
                    // this.openModal();
                    // this.isNetworkSelected = false;
                    // this.desktop_card = false;
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        // getHistory() {
        //     this.loading = true;
        //     axios.post(this.apiUrl + "api/upi", {

        //     }).then(res => {

        //     }).catch(err => {
        //         console.log(err);
        //         this.loading = false;
        //     });
        // },
        async generateQRCode() {
            try {

                this.qrCodeUrl = await QRCode.toDataURL(this.depositAddress, {
                    margin: 2, color: {
                        dark: '#000000',

                        light: '#ffffff'
                    }
                })
            } catch (error) {
                console.error('Error generating QR code', error)
            }
        },

        async uploadFile(e) {
            this.image = e.target.files[0];
        },
        deposit_live() {
            axios.post(this.apiUrl + "api/deposit", {
                amount: this.form.amount,
                hash: this.form.hash,

                token: localStorage.token
            }).then(res => {

                this.payments = res.data;
                this.is_deposit = true;
                this.form_pending = false;
            }).catch(err => {
                console.log(err);
                this.success = false;
                this.error = message;
                this.form.hash = "";
                this.form.amount = "";
            });
        },
        deposit() {
            this.disabled = true;
            var forms = new FormData();
            // forms.append('image', this.image);
            this.spin = false;
            forms.append('amount', this.form.amount);
            forms.append('hash', this.form.hash);
            forms.append('upi', this.form.depositAddress);
            forms.append('token', localStorage.token);
            axios.post(this.apiUrl + "api/post_deposit", forms).then(res => {


                this.$toaster.success(res.data.message);
                this.success = res.data.message;

                this.form.hash = "";
                this.form.amount = "";
                this.disabled = false;
                this.spin = true;
            }).catch(err => {
                this.spin = true;
                this.disabled = false;
                console.log(err);
                this.$toaster.error(err.response.data.message);
                this.error = message;
                this.form.hash = "";
                this.form.amount = "";
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
            //     this.$toaster.error('Deposit Address not found');
            //     return false
            // }
            this.selectedNetwork = network;
            this.isNetworkSelected = true;
            this.desktop_card = true;
            var type = network == 'tron' ? 'tron_address':'address';
            console.log(type);
            
            this.getDepositAddress(type);
            this.closeModal();

        },
        // closeModal() {

        //     const close_modal =  document.getElementById('networkModal');
        //     alert(close_modal);
        //      this.$refs.close_modal.style.display = 'none';

        // },

        closeModal() {
            $('#networkModal').modal('hide');

        },
        copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            this.$toaster.success('Deposit Address copied to clipboard!');
        },
        checkScreenSize() {
            if (window.innerWidth <= 768) {
                this.openModal();
                // this.isNetworkSelected = false;

            } else {
                this.closeModal();
            }
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
      padding:0 0 40px 30px;
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
