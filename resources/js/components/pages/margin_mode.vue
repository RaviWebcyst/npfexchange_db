<template>
    <div>


            <div class="modal fade " id="crossModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="leverageModalLabel" aria-hidden="true">
                <div class="modal-dialog centered " style="margin-top:150px">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h5 class="modal-title" id="transfer_usdt">Margin Mode

                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
                        </div>
                        <div class="modal-body">


                            <ul style="list-style: disc">
                                <div class="text-white"><span class="h5">{{coin}}USDT </span> <small class="  badge bg-black text-white">Perp</small></div>
                                <div class="d-flex my-3">
                                    <button :class="['w-50','bg-transparent', 'p-2', 'text-white',   'border-1', { 'border-white': localSelectedButton === 'cross' }]" @click="selectButton('cross')" >
                                          Cross </button>
                                    <button  :class="['w-50','bg-transparent', 'p-2', 'text-white',  'border-1', 'ms-3', { 'border-white': localSelectedButton === 'Isolated' }]"  @click="selectButton('Isolated')" >
                                    Isolated </button>
                                </div>


                                <li class="text-muted mb-3 small">  Switching the margin mode will only apply it to the selected contract.</li>
                                <li class="text-muted mb-3 small">Cross Margin Mode: All cross positions under the same margin asset share the same asset cross margin balance. In the event of liquidation, your assets full margin balance along with any remaining open positions under the asset may be forfeited.  </li>
                                <li class="text-muted mb-3 small"> Isolated Margin Mode: Manage your risk on individual positions by restricting the amount of margin allocated to each. If the margin ratio of a position reached 100%, the position will be liquidated. Margin can be added or removed to positions using this mode. </li>
                            </ul>
                            <div class="d-flex justify-content-center mx-5">
                                <button class="btn butn rounded text-dark mb-4 mt-3 w-100" type="button" :disabled="marginMode"  @click="setMarginMode">Confirm
                                    <span class="spinner-grow spinner-grow-sm pl-2" role="status" aria-hidden="true" v-if="marginMode"></span>
                                </button>
                            </div>
                          </div>

                    </div>
                </div>
            </div>

    </div>
</template>

<script>

export default {
    name: "margin_mode",

    props: {
    coin: {

      type: String,
      required: false
    },
    selectedButton: {
      type: String,
      required: false

     }
  },

    data() {
        return {
            url: process.env.mix_api_url,
            final_margin: 'Isolated',
            marginMode:false,
            localSelectedButton: this.selectedButton,

        };
    },
    mounted(){

    },
    methods: {
        selectButton(button) {
                this.localSelectedButton = button;
             this.$emit('update:selectedButton', button);
        },
        setMarginMode() {

            this.marginMode = true;
            axios.post(this.url + "api/setMargin", {
                token: localStorage.token,
                coin: this.coin,
                margin: this.localSelectedButton,

            }).then(res => {
                // console.log(res);
                this.$emit('callParentFunction'),

                this.$toaster.success(res.data.message);
                this.marginMode = false;
                $(".btn-close").click();
            }).catch(err => {
                this.$toaster.error(err.response.data.message);
                this.marginMode = false;
            });
        },
    },
};
</script>
