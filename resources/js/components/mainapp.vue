<template>
        <router-view />
</template>

<script>
// import $ from 'jquery';

export default {
    data(){
        return {
            reload:false,
            apiUrl:process.env.mix_api_url
        }
    },
    created(){
        this.userDetails();
        // this.getLiveDeposits();
    },
    methods: {
        userDetails(){
            axios.post(this.apiUrl+'api/getUserDetails',{
                    token:localStorage.token
                }).then(res=>{
                    if (res.data[0] == "token_expired") {
                        localStorage.removeItem('token');
                    }
                }).catch(err=>{
                    console.log(err);
            });
        },
        // getLiveDeposits(){
        //     console.log("working herrrr");
            
        //     axios.get(this.apiUrl+"api/getLiveDeposits",{
        //         params:{
        //             token:localStorage.token
        //         }
        //     })
        //     .then(res=>{
        //         console.log("resdddddddddddddddd");
        //         console.log(res);
        //     }).catch(err=>{
        //         console.log("err");
        //         console.log(err);
        //     });
        // },
  
        
    },
    mounted() {

}


};
</script>
