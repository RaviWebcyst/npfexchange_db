<template>
    <img :src="img_url" alt="" width="30" class="rounded-circle">
</template>


<script>
export default {
    props: {
        coin: {
            type: String,
            required: true
        }
    },
    data(){
        return {
            url:process.env.mix_api_url,
            // coin:this.$attrs.coin,
            img_url : ""
        }
    },
    created(){
        this.coinDetails();
        
    },
    watch: {
        coin(newCoin) {
            this.coinDetails(); // Call coinDetails whenever coin changes
        }
    },
    methods:{
        coinDetails(){
            if(this.coin == 'USDT'){
                return this.img_url = this.url+'usdt.svg';
            }
            // else if(this.coin == 'NPF'){
            //     return this.img_url = "https://via.placeholder.com/250";
            // }

            axios
                .get(this.url + "api/coin_details",{
                    params:{
                        coin:this.coin
                    }
                })
                .then((res) => {
                    console.log("res");
                    var icon = Object.values(res.data)[0]?.icon;
                   this.img_url = this.url+icon;
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    }
}
</script>