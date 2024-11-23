<template>
    <div >
      <Header />
      <div class="container mt-5 text-dark pt-5">
                  <div class="d-flex align-items-center ">
                      <h3 class="text-white"><a href="#" class="fa-solid fa-arrow-left me-3 text-white" @click="$router.go(-1);"></a>Transfer History</h3>
                  </div>
                  <!-- <form class=" d-block d-sm-flex justify-content-end ml-0 ml-sm-3   " action="" method="get"  >
                    <div class="ml-0 mt-2 mt-sm-0">
                        <input class="search_input " type="date" placeholder="Search"  v-model="to_date"   />

                   </div>
                   <div class="fa-solid fa-arrow-right text-white my-auto ps-2 d-none d-sm-block"> </div>
                   <div class="ms-0 ms-lg-2 mt-2 mt-sm-0">
                    <input class=" search_input" type="date" placeholder="Search" v-model="from_date"  />
                </div>
                   <div class="ml-sm-3 mt-2 mt-sm-0 d-flex gap-3">
                        <a href="#" class="page-link  text-white ms-0 ms-sm-3 search_input bg-success" @click="transfer_history">Search</a>
                        <a href="#" class="page-link text-white  search_input bg-danger"  @click="reset_btn">Reset</a>

                   </div>
               </form> -->
                  <div class="card shadow-lg mt-2">
                      <div class="card-body">
                          <div class="col-sm-12 table-responsive">
                              <table id="ordertabtwo" class="  table table-hover ">
                                  <thead>
                                      <tr role="row" class="border odd">
                                          <th scope="col" class="text-white border-white">#</th>
                                          <!-- <th scope="col" class="text-white border-white">Hash</th> -->
                                          <th scope="col" class="text-white border-white">Amount</th>
                                           <th scope="col" class="text-white border-white">Description</th>
                                           <th scope="col" class="text-white border-white">Date</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr role="row" class="odd" v-for="(his, i) in history">
                                          <th class="text-white" scope="row">{{ i + 1 }}</th>
                                          <!-- <td class="text-white">{{ his.hash }}</td> -->
                                          <td class="text-white">${{ his.amount }}</td>
                                            <td class="text-white">{{ his.description }}</td>
                                          <td class="text-white">{{ moment(his.created_at).format('DD-MM-YYYY, hh:mm:ss A') }} </td>
                                      </tr>
                                  </tbody>
                              </table>
                              <!-- <pagination v-model="page" :records="records" @paginate="history" /> -->
                              <div class="row mt-5">
                                  <div class="col-sm-12 col-md-5">
                                      <div class="dataTables_info" id="ordertabtwo_info"
                                          role="status" aria-live="polite">

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
      name: "transfer_history",
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
              from_date : '',
              to_date : '',
          };
      },
      created() {
          this.transfer_history();
      },
      methods:{
          moment(date) {
             return moment(date);
         },
         reset_btn() {
            this.to_date = '',
            this.from_date = '',
            this.transfer_history();
         },
         transfer_history(){
          // axios.post(this.apiUrl+"api/transfer_history",{
          axios.post(this.apiUrl+ "api/transfer_history?page=" + this.page,{
              token:localStorage.token,
              to_date : this.to_date,
              from_date : this.from_date,
          }).then(res=>{
            console.log("res "+res);

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

      }
  };
  </script>
  <style>

  input[type="date" i] {
    color: #ffffff !important;

  }
  .search_input {
    background: #495c6e ;
    border: transparent !important;
    color: white ;
    padding: 0.375rem 0.75rem;
    border-radius: 10px !important;
  }
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
