<template>
    <div>
        <Header />
        <div class="main px-lg-4 px-md-4 pt-5">
            <!-- Body: Header -->
            <!-- Body: Titel Header -->
            <!-- <div class="body-header border-bottom d-flex py-3">
                <div class="container-xxl">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h1 class="h4 mt-1 text-white">Exchange</h1>
                        </div>
                        <div class="col-12 col-md-6 text-md-end">
                <a href="https://themeforest.net/user/pixelwibes" title="Download" target="_blank" class="btn btn-white border lift">Download</a>
                <button type="button" class="btn btn-dark lift">Generate Report</button>
            </div>
                    </div>
                </div>
            </div> -->

            <!-- Body: Body -->
            <div class="body d-flex py-3">
                <div class="container-xxl">
                    <div class="dropdown-1 dropend d-flex mb-4 px-5">
                        <i class="fa-solid fa-arrow-right-arrow-left my-auto"></i>
                        <a class="nav-link text-light dropdown-btn fs-5" data-bs-toggle="offcanvas" href="#offcanvasExample"
                            role="button" aria-controls="offcanvasExample">
                            <coinImg :coin="coin" />   {{ coin }}/USDT
                        </a>
                    </div>
                    <div class="offcanvas offcanvas-start bg-black sm-offcanvas" tabindex="-1" id="offcanvasExample"
                        aria-labelledby="offcanvasExampleLabel" style="max-width: 280px" :class="{ show: show }">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="list-unstyled" v-if="symbols.length > 0">
                                <li class="offcanvas-nav active ps-5 nav_items mb-3 mt-3 p-2" v-for="symbol in symbols">
                                    <a class="offcanvas-item text-light fs-6" href="#" @click="
                                coins(symbol.name),
                                setBalance(),
                                getPrice()
                                " data-bs-dismiss="offcanvas" aria-label="Close">
                                <coinImg :coin="symbol.name" />   {{ symbol.name }}/USDT</a>
                                </li>
                            </ul>
                        </div>
                        <!-- <div class="offcanvas-body">
                            <ul class="list-unstyled">
                                <li class="offcanvas-nav active ps-5 nav_items mb-3 mt-3 p-2">
                                    <a class="offcanvas-item text-light fs-6" href="#" @click="coins('BTC'),setBalance(),getPrice()" data-bs-dismiss="offcanvas"
                                aria-label="Close"> BTCUSDT</a>
                                </li>
                                <li class="offcanvas-nav ps-5 nav_items mb-3 p-2">
                                    <a class="offcanvas-item text-light fs-6" href="#" @click="coins('BNB'),setBalance(),getPrice()" data-bs-dismiss="offcanvas"
                                aria-label="Close">BNBUSDT</a>
                                </li>
                                <li class="offcanvas-nav ps-5 nav_items p-2 ">
                                    <a class="offcanvas-item text-light fs-6" href="#" @click="coins('TRX'),setBalance(),getPrice()" data-bs-dismiss="offcanvas"
                                aria-label="Close">TRXUSDT</a>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                    <div class="row g-3 mb-3">
                        <!-- <div class="col-5 col-md-1">
                            <select
                                name="coin"
                                class="form-control text-center border-0"
                                v-model="coin"
                                @change="setBalance(), getPrice()"
                            >
                                <option value="BTC">BTCUSDT </option>
                                <option value="BNB">BNBUSDT</option>
                                <option value="TRX">TRXUSDT</option>
                            </select>
                        </div> -->

                        <ul class="nav nav-tabs tab-body-header rounded d-flex mt-3 d-lg-none" role="tablist">
                            <li class="nav-item col p-0 text-center" role="presentation">
                                <a class="nav-link active text-center" data-bs-toggle="tab" href="#trade" role="tab"
                                    aria-selected="true" @click="showTrade">Trade</a>
                            </li>
                            <li class="nav-item col p-0 text-center" role="presentation">
                                <a class="nav-link text-center" data-bs-toggle="tab" href="#graph" role="tab"
                                    aria-selected="false" tabindex="-1" @click="showChart">Chart</a>
                            </li>
                        </ul>
                        <!-- <div class="col-7 text-center">
                            <span class="icon-GwQQdU8S" @click="graphToggle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" width="28" height="28" fill="currentColor"><path d="M17 11v6h3v-6h-3zm-.5-1h4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5z"></path><path d="M18 7h1v3.5h-1zm0 10.5h1V21h-1z"></path><path d="M9 8v12h3V8H9zm-.5-1h4a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 .5-.5z"></path><path d="M10 4h1v3.5h-1zm0 16.5h1V24h-1z"></path></svg></span>
                        </div> -->
                        <div class="col-md-9 mt-2">
                            <div class="card" :class="{ 'd-none d-md-block': mobile }">
                                <div class="card-body">
                                    <div v-if="coin == 'NPF'">
                                        <!-- <div id="chart" class="d-flex jusify-content-center"></div>
                                        <canvas ref="chartCanvas"></canvas> -->


                                        <!-- <div id="chart">
                                           <apexchart type="area" height="444" :options="chartOptions" :series="series"></apexchart>
                                        </div> -->

                                        <!-- <div v-if="!chartOptions" class="d-flex justify-content-center my-5">
                                            <div class="spinner-border text-primary" role="status">
                                              <span class="sr-only">Loading...</span>
                                            </div>
                                          </div> -->

                                          <!-- Show chart only when loading is false -->
                                          <div  id="chart">
                                            <npf_chart></npf_chart>
                                            <!-- <apexchart type="area" height="350" :options="chartOptions" :series="series"  ></apexchart> -->
                                          </div>
                                    </div>

                                    <!-- TradingView Widget BEGIN -->

                                    <div class="tradingview-widget-container" v-if="coin != 'NPF'">
                                        <div id="tradingview_e05b7" class="chart_height">
                                            <div id="tradingview_319a7-wrapper" style="
                                                    position: relative;
                                                    box-sizing: content-box;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0 auto !important;
                                                    padding: 0 !important;
                                                    font-family: -apple-system,
                                                        BlinkMacSystemFont,
                                                        'Trebuchet MS', Roboto,
                                                        Ubuntu, sans-serif; ">
                                                <div style="
                                                        width: 100%;
                                                        height: 100%;
                                                        background: transparent;
                                                        padding: 0 !important;
                                                    ">
                                                  <div class="chart">
                                                    <!-- <iframe id="tradingview_319a7" src="https://s.tradingview.com/widgetembed/?frameElementId=tradingview_319a7&amp;symbol=BITSTAMP%3ABTCUSD&amp;interval=D&amp;hidesidetoolbar=0&amp;symboledit=1&amp;saveimage=1&amp;toolbarbg=f1f3f6&amp;details=1&amp;calendar=1&amp;hotlist=1&amp;studies=%5B%5D&amp;style=1&amp;timezone=Etc%2FUTC&amp;withdateranges=1&amp;studies_overrides=%7B%7D&amp;overrides=%7B%7D&amp;enabled_features=%5B%5D&amp;disabled_features=%5B%5D&amp;locale=in&amp;utm_source=&amp;utm_medium=widget&amp;utm_campaign=chart&amp;utm_term=BITSTAMP%3ABTCUSD#%7B%22page-uri%22%3A%22__NHTTP__%22%7D" style="width: 100%; height: 100%; margin: 0 !important; padding: 0 !important;" frameborder="0" allowtransparency="true" scrolling="no" allowfullscreen="">
    </iframe> -->
                                                   <iframe id="tradingview_319a7" :src="'https://s.tradingview.com/widgetembed/?frameElementId=tradingview_319a7&amp;symbol=' +coin +'USDT&amp;interval=D&amp;hidesidetoolbar=0&theme=dark&amp;symboledit=1&amp;saveimage=1&amp;toolbarbg=f1f3f6&amp;details=1&amp;calendar=1&amp;hotlist=1&amp;studies=%5B%5D&amp;style=1&amp;timezone=Etc%2FUTC&amp;withdateranges=1&amp;studies_overrides=%7B%7D&amp;overrides=%7B%7D&amp;enabled_features=%5B%5D&amp;disabled_features=%5B%5D&amp;locale=in&amp;utm_source=&amp;utm_medium=widget&amp;utm_campaign=chart&amp;utm_term=BITSTAMP%3ABTCUSD#%7B%22page-uri%22%3A%22__NHTTP__%22%7D'" style="
                                                            width: 100%;
                                                            height: 100%;
                                                            margin: 0 !important;
                                                            padding: 0 !important;
                                                        " frameborder="0" allowtransparency="true" scrolling="no"
                                                        allowfullscreen="">
                                                    </iframe> </div>
                                                  
                                               
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- TradingView Widget END -->
                                </div>
                            </div>
                        </div>


                    <!-- total balance card  -->
                    <div class="col-md-3 mt-2">
                    <div class="card border-dark text-white border-light card-dark">
                        <div class="card-body bg-dark ">
                            <small class="text-white">Total Balance</small>
                            <div class="d-flex gap-2">
                                <h3 class="text-white m-0">{{ Number(total_balance).toFixed(4) }}</h3>
                                <small class="pt-2">USDT</small>
                                 <!-- <div class="dropdown">
                                    <button class="btn btn-link dropdown-toggle text-decoration-none text-white" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <small> USDT </small>
                                    </button>
                                    <div class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item text-white" href="#">BTC</a>
                                        <a class="dropdown-item text-white" href="#">ETH</a>
                                        <a class="dropdown-item text-white" href="#">BNB</a>
                                    </div>
                                </div>  -->
                            </div>
                            <!-- <div class="mt-3">
                                <router-link class="btn w-100 btn-primary font-teko fw-medium bg-primary-secondary" :to="{name:'deposit'}">Deposit</router-link>
                             </div>
                            <div class="mt-3">
                                 <router-link class="btn w-100 btn-info font-teko fw-medium bg-primary-secondary " :to="{name:'withdraw'}">Withdraw</router-link>
                            </div> -->
                            <div class=" d-flex gap-3 mt-4"  >
                                <router-link  class="flex-fill  bg-success text-white w-100   border-0 rounded-pill py-2 text-center" :to="{ name : 'deposit'}">
                                    Deposit
                                 </router-link>
                                <router-link class="flex-fill bg-danger w-100  text-white border-0 rounded-pill py-2 text-center " :to="{ name : 'withdraw'}">
                                    Withdraw
                                 </router-link>
                            </div>
                            <div class="mt-5 d-flex gap-2 w-100">
                                <small class="text-white">Today's PNL(ROE%)</small>
                                <h6 className="ml-auto"><span :class="today_pnl > 0 ? 'text-success' : 'text-danger'"> {{today_pnl != 0 && today_pnl > 0 ? '+' : ''}} {{ Number(today_pnl).toFixed(2) }} ({{ Number(today_pnl_per).toFixed(2)}}%) </span></h6>
                            </div>

                            <div class="d-grid gap-3 pt-5">
                                <div >Balances</div>
                                <div class="card-body p-0" style="max-height:225px;overflow:scroll">
                                    <div class="d-grid gap-5">
                                        <div class="d-flex gap-2 w-100">
                                        <div class="coin-img">
                                               <coinImg coin="USDT" />
                                            </div>
                                            <div class="lh-1">
                                                <div class="text-white">USDT</div>
                                                <small class="text-light" style="font-size:10px">TetherUS</small>
                                            </div>
                                            <div class="ms-auto">{{ usdt }}</div>
                                        </div>
                                        <div class="d-flex gap-2 w-100">
                                        <div class="coin-img">
                                               <coinImg coin="NPF" />
                                            </div>
                                            <div class="lh-1">
                                                <div class="text-white">NPF</div>
                                                <small class="text-light" style="font-size:10px">npf</small>
                                            </div>
                                            <div class="ms-auto">{{npf }}</div>
                                        </div>
                                        <div class="d-flex gap-2 w-100">
                                        <div class="coin-img">
                                               <coinImg coin="BTC" />
                                            </div>
                                            <div class="lh-1">
                                                <div class="text-white">BTC</div>
                                                <small class="text-light" style="font-size:10px">BitCoin</small>
                                            </div>
                                            <div class="ms-auto">{{btc }}</div>
                                        </div>
                                        <div class="d-flex gap-2 w-100">
                                        <div class="coin-img">
                                               <coinImg coin="BNB" />
                                            </div>
                                            <div class="lh-1">
                                                <div class="text-white">BNB</div>
                                                <small class="text-light" style="font-size:10px">BNB</small>
                                            </div>
                                            <div class="ms-auto">{{ bnb }}</div>
                                        </div>
                                        <div class="d-flex gap-2 w-100">
                                            <div class="coin-img">
                                               <coinImg coin="ETH" />
                                            </div>
                                            <div class="lh-1">
                                                <div class="text-white">ETH</div>
                                                <small class="text-light" style="font-size:10px">ethreum</small>
                                            </div>
                                            <div class="ms-auto">{{ eth }}</div>
                                        </div>
                                        <div class="d-flex gap-2 w-100">
                                        <div class="coin-img">
                                               <coinImg coin="TRX" />
                                            </div>
                                            <div class="lh-1">
                                                <div class="text-white">TRX</div>
                                                <small class="text-light" style="font-size:10px">Tron</small>
                                            </div>
                                            <div class="ms-auto">{{ trx }}</div>
                                        </div>
                                        <div class="mt-3">
                                            <router-link class="btn w-100 btn-primary font-teko fw-medium bg-primary-secondary" :to="{name:'balances'}">View More</router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                     <!-- end total balance card  -->
                    </div>
                    <!-- Row End -->
                    <div class="my-5" v-if="trade_on == true">
                        <h3 class="text-center">Trading will be Coming Soon</h3>
                    </div>
                    <div :class="{ 'd-none': trade_on }">
                        <div class="row g-3 mb-3" :class="{ 'd-none d-md-block': trade }">
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div
                                        class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
                                        <h6 class="mb-0 fw-bold text-light">Spot</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs tab-body-header rounded d-inline-flex" role="tablist">
                                            <!-- <li class="nav-item" role="presentation">
                                                <a class="nav-link" href="#">Market</a>
                                            </li> -->
                                            <li class="nav-item" role="presentation"><a class="nav-link active" data-bs-toggle="tab" href="#Market" role="tab" aria-selected="false" tabindex="-1"  @click="EnableInput('market')">Market</a></li>
                                            <li class="nav-item" role="presentation"><a class="nav-link " data-bs-toggle="tab" href="#Market" role="tab" aria-selected="true"  @click="EnableInput('limit')">Limit</a></li>
                                            <!-- <li class="nav-item" role="presentation"><a class="nav-link" data-bs-toggle="tab" href="#Stoplimit" role="tab" aria-selected="false" tabindex="-1">Stop Limit</a></li> -->
                                        </ul>
                                        <div class="tab-content">

                                            <!-- Trade card start -->

                                            <div class="tab-pane fade show active" id="Market" role="tabpanel">
                                                <ul class="nav nav-tabs tab-body-header rounded d-flex mt-3 mx-auto col-8 justify-content-center"
                                                    role="tablist">
                                                    <li class="nav-item col  p-0" role="presentation">
                                                        <a class="nav-link active text-center" data-bs-toggle="tab"
                                                            href="#buy" role="tab" aria-selected="true">Buy</a>
                                                    </li>
                                                    <li class="nav-item col p-0" role="presentation">
                                                        <a class="nav-link text-center" data-bs-toggle="tab"
                                                            href="#sell" role="tab" aria-selected="false"
                                                            tabindex="-1">Sell</a>
                                                    </li>
                                                </ul>
                                                <div class="row g-3 tab-content">

                                                    <!-- Market BUY start -->

                                                    <div class="col-lg-12 tab-pane fade show active" id="buy"
                                                        role="tabpanel">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between my-3">
                                                            <span class="small text-muted">Avbl</span>
                                                            <span class="">{{usdt }}USDT</span>
                                                        </div>
                                                        <div class="pb-3">Mark Price : {{ markPrice(coin+'USDT') }}</div>
                                                        <form>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text custom_rounded" >Price</span>
                                                                <input :type="disable_input ? 'text' : 'number'" class="form-control border"  v-model="price" :disabled="disable_input" @input="setBuyPrices"  />
                                                                <span class="input-group-text" >USDT</span>
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text custom_rounded" :class="{'number-input': disable_input}">{{ coin }}</span>
                                                                <input type="number" min="0" class="form-control border" v-model="coin_amount" @input="calUsdt" :class="{'number-input': disable_input}" :placeholder="'Enter '+coin+' Amount'" />
                                                                <!-- <span class="input-group-text" :class="{'number-input': disable_input}">{{coin}}</span> -->
                                                            </div>

                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text custom_rounded" :class="{'number-input': disable_input}">USDT</span>
                                                                <input type="number" min="0" class="form-control border" v-model="total" @input="totalBuy" :class="{'number-input': disable_input}" placeholder="Enter USDT Amount"/>
                                                                <!-- <span class="input-group-text" :class="{'number-input': disable_input}">USDT</span> -->
                                                            </div>
                                                            <small class="text-danger" v-if="error">{{error}}</small>

                                                            <div class="input-group mb-3 px-3">
                                                                    <div class="mb-2 d-flex justify-content-between align-items-center w-100">
                                                                    <span class="text-muted">0%</span>
                                                                    <span class="text-muted px-2">25%</span>
                                                                    <span class="text-muted px-1">50%</span>
                                                                    <span class="text-muted px-1">75%</span>
                                                                    <span class="text-muted">100%</span>
                                                                </div>
                                                                 <!-- <input type="range" class="form-range custom-range" min="1" max="5" value="1" step="1" v-model="buy_per" @change="buyPer" /> -->
                                                                 <input type="range" class="custom-range" min="1" max="5" value="1" step="1" v-model="buy_per" @change="buyPer" />


                                                            </div>
                                                            <button type="button" class="btn flex-fill btn-light-success fs-5 text-uppercase w-100" @click="buyCoin" :disabled="buy_disable" v-if="auth">
                                                                 BUY {{ coin }}
                                                                <div class="spinner-grow spinner-grow-sm text-success" role="status" :class="{'d-none':spin,}"></div>
                                                            </button>
                                                            <div class=" d-flex gap-3 mt-4" v-if="!auth">
                                                                <router-link  class="flex-fill  bg-success text-white w-100   border-0 rounded-pill py-2 text-center" :to="{ name : 'Login'}">
                                                                    Login
                                                                 </router-link>

                                                            </div>

                                                        </form>
                                                    </div>



                                                    <div class="col-lg-12 tab-pane fade" id="sell" role="tabpanel">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between my-3">
                                                            <span class="small text-muted">Avbl</span>
                                                            <span class="">{{ balance }}
                                                                {{ coin }}</span>
                                                        </div>
                                                        <form>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text custom_rounded" >Price</span>
                                                                <!-- <input type="text" class="form-control readonly" v-model="price"   /> -->
                                                                <input :type="disable_input ? 'text' : 'number'" class="form-control border"  v-model="price
                                                                " :disabled="disable_input" @input="setSellPrices" />
                                                                <span class="input-group-text" >USDT</span>
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text custom_rounded" :class="{'number-input': disable_input}">{{ coin }}</span>
                                                                <input type="number" min="0" class="form-control border" v-model="coin_sell" @input="calSell"
                                                                 :class="{'number-input': disable_input}" :placeholder="'Enter '+coin+' Amount'"/>
                                                                <span class="input-group-text">{{coin}}</span>
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text custom_rounded" :class="{'number-input': disable_input}">USDT</span>
                                                                <input type="number" min="0" class="form-control border" v-model="total_sell" @input="totalSell" :class="{'number-input': disable_input}"  placeholder="Enter USDT Amount" />
                                                                <!-- <span class="input-group-text">USDT</span> -->
                                                            </div>
                                                            <small class="text-danger" v-if="error_sell">{{error_sell}}</small>
                                                                <div class="input-group mb-3 px-3">
                                                                    <div class="mb-2 d-flex justify-content-between align-items-center w-100">
                                                                        <span class="text-muted">0%</span>
                                                                        <span class="text-muted px-2">25%</span>
                                                                        <span class="text-muted px-1">50%</span>
                                                                        <span class="text-muted px-1">75%</span>
                                                                        <span class="text-muted">100%</span>
                                                                    </div>
                                                                <!-- <input type="range" class="form-range  custom-range" min="1" max="5" value="1" step="1" v-model="sell_per" @change="sellPer" /> -->
                                                                 <input type="range" class="custom-range" min="1" max="5" value="1" step="1" v-model="sell_per" @change="sellPer" />


                                                            </div>
                                                            <button type="button"
                                                                class="btn flex-fill btn-danger red_btn  fs-5 text-uppercase px-5 w-100"
                                                                @click="sellCoin" :disabled="sell_disable" v-if="auth">
                                                                SELL {{ coin }}
                                                                <div class="spinner-grow spinner-grow-sm text-danger"
                                                                    role="status" :class="{'d-none':spin}"></div>
                                                            </button>
                                                            <div class=" d-flex gap-3 mt-4" v-if="!auth">
                                                                <router-link  class="flex-fill  bg-danger text-white w-100   border-0 rounded-pill py-2 text-center" :to="{ name : 'Login'}">
                                                                    Login
                                                                 </router-link>

                                                            </div>
                                                        </form>
                                                    </div>
                                                      <!-- Market sell end -->

                                                </div>
                                            </div>

                                              <!-- Trade Card end -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card">
                                    <div
                                        class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
                                        <h6 class="mb-0 fw-bold text-light">
                                            Spot trade Status
                                        </h6>
                                    </div>
                                    <ul class="d-sm-none nav nav-tabs tab-body-header rounded d-inline-flex mb-3 col-12 border-end-0 border-start-0 border-top-0"
                                    role="tablist">




                                    <li class="nav-item col p-0" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#OpenOrder" role="tab"
                                            aria-selected="true" @click="openOrders" style="font-size:11px !important">Open Order</a>
                                    </li>
                                    <li class="nav-item col p-0" role="presentation">

                                            <a class="nav-link" data-bs-toggle="tab" href="#closeOrder" role="tab"
                                            aria-selected="true" @click="closeOrders" style="font-size:11px !important">Close Order</a>
                                    </li>
                                    <li class="nav-item col p-0" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#OrderHistory"
                                            role="tab" aria-selected="false" tabindex="-1" @click="tradeHistory" style="font-size:11px !important">Order History</a>
                                    </li>
                                </ul>
                                    <div class="card-body">
                                        <ul class="d-none d-sm-flex nav nav-tabs tab-body-header rounded d-inline-flex mb-3 col-12 border-end-0 border-start-0 border-top-0"
                                            role="tablist">
                                            <li class="nav-item  p-0 text-center" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#OpenOrder" role="tab"
                                                    aria-selected="true" @click="openOrders">Open Order</a>
                                            </li>
                                            <li class="nav-item  p-0 text-center" role="presentation">

                                                    <a class="nav-link" data-bs-toggle="tab" href="#closeOrder" role="tab"
                                                    aria-selected="true" @click="closeOrders">Close Order</a>
                                            </li>
                                            <li class="nav-item  p-0 text-center" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#OrderHistory"
                                                    role="tab" aria-selected="false" tabindex="-1" @click="tradeHistory">Order History</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">

                                        <!-- Open Order start -->
                                            <div class="tab-pane fade" id="OpenOrder" role="tabpanel">
                                                <div id="ordertabone_wrapper"
                                                    class="dataTables_wrapper dt-bootstrap5 no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 table-responsive">
                                                            <div class="row d-block d-sm-none" v-if="open_order_loading">
                                                                <div>
                                                                    <div colspan="9" class="text-center">
                                                                        <div class="spinner-grow text-info" role="status"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <div class="row d-block d-sm-none" v-if="open_orders.length > 0">
                                                            <div class="col-md-4" v-for="(trade, i) in open_orders" :key="i">
                                                                <div class="card mb-3 d-flex">
                                                                    <div class="card-header">
                                                                        {{ moment(trade.created_at).format("DD-MM-YYYY, hh:mm:ss A") }}
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-3">
                                                                                <!-- <p class="card-text1 m-0">  <span :class="markPrice(trade.symbol) != 'Loading'  && (Number((markPrice(trade.symbol) - trade.price) * trade.quantity) > 0) ? 'text-success' : 'text-danger'">
                                                                                    {{ markPrice(trade.symbol) != 'Loading'  ? Number((markPrice(trade.symbol) - trade.price) * trade.quantity).toFixed(4) : 'Loading...' }}
                                                                                    {{ markPrice(trade.symbol) != 'Loading' ? '(' + Number((markPrice(trade.symbol) - trade.price) / trade.price).toFixed(4) + "%)" : ''}}
                                                                                </span></p> -->


                                                                            </div>
                                                                            <div class="col-6">
                                                                                <h5 class="card-title">{{ trade.symbol }}</h5>
                                                                                <div style="font-size: 12px">Order Type: {{ trade.order_type }}</div>
                                                                                <div style="font-size: 12px">Side: <span :class="trade.type == 'Buy' ? 'text-success' : 'text-danger'">{{ trade.type }}</span></div>
                                                                                <div style="font-size: 12px">Price: {{ Number(trade.price).toFixed(5) }}</div>
                                                                                <div style="font-size: 12px">Quantity: {{ toFixed(trade.quantity, 5) }}</div>
                                                                                <div style="font-size: 12px">Amount: {{ toFixed(trade.amount, 5) }}</div>

                                                                            </div>
                                                                            <div class="col-3">
                                                                                <button class=" bg-transparent text-danger border-0  mobile_btn " v-if="trade.order_type == 'Market'" @click="closeOrder(trade.id)" :disabled="order_closing"  >Close
                                                                                    <div class="spinner-grow spinner-grow-sm text-danger" role="status" v-if="order_closing"></div>
                                                                                </button>
                                                                                <button class="  bg-transparent text-danger mt-5 border-0 mobile_btn" @click="cancelOrder(trade.id)" :disabled="cancel_coin"  >Cancel</button>

                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row d-block d-sm-none py-5 text-center" v-if="!auth">
                                                            <div>
                                                                <router-link :to="{name : 'Login'}" class="text-info">Login</router-link> or <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link> to trade
                                                            </div>
                                                        </div>


                                                            <table   id="ordertabone"
                                                                class="overflow-scroll d-none d-sm-block priceTable table table-hover custom-table-2 align-middle mb-0 nowrap table-borderless no-footer dtr-inline"
                                                                style="
                                                                    width: 100%; max-height:305px !important; overflow-y: scroll;
                                                                " aria-describedby="ordertabone_info">
                                                                <thead>
                                                                    <tr role="row">
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Date
                                                                        </th>
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Pair
                                                                        </th>
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Type
                                                                        </th>
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Side
                                                                        </th>
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Price
                                                                        </th>
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Quant.
                                                                        </th>
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Amount
                                                                        </th>
                                                                           <!-- <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Pnl: profit and loss">
                                                                            PNL(ROE%)
                                                                        </th> -->
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Cancel Orders
                                                                        </th>
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Close Orders
                                                                        </th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody v-if="open_order_loading">
                                                                    <tr>
                                                                        <td colspan="9" class="text-center">
                                                                            <div class="spinner-grow text-info" role="status"></div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tbody v-if="open_orders.length >0 && auth">
                                                                    <tr role="row" class="odd" v-for="(trade,i) in open_orders">
                                                                        <td tabindex="0" class="sorting_1">
                                                                            {{moment(trade.created_at).format("DD-MM-YYYY,hh:mm:ss A")}}
                                                                        </td>
                                                                        <td>
                                                                            <img :src="'user/assets/images/coin/' + trade.symbol.slice(0,-4) +'.png'"
                                                                                alt="coin" class="img-fluid avatar rounded-circle mx-1"
                                                                                v-if="trade.symbol !== 'NPFUSDT'" >
                                                                            <img src="user/assets/images/coin/NPF.png" alt="coin" class="img-fluid avatar rounded-circle mx-1" v-else > {{trade.symbol }}
                                                                        </td>
                                                                        <td> {{ trade.order_type }}</td>
                                                                        <td>
                                                                            <span :class="trade.type == 'Buy'? 'color-price-up': 'color-price-down'">{{trade.type }}</span>
                                                                        </td>
                                                                        <td>{{Number(trade.price).toFixed(5)}}</td>
                                                                        <td>  {{ toFixed(trade.quantity,5)}}</td>
                                                                        <td class="dt-body-right">
                                                                            {{toFixed(trade.amount,5)}}
                                                                        </td>
                                                                           <!-- <td><span :class="trade.pnl_per > 0 ? 'text-success' : 'text-danger'">{{trade.pnl}}({{trade.pnl_per > 0 ? +trade.pnl_per : trade.pnl_per }}%)</span></td> -->
                                                                        <!-- <td>
                                                                            <div >
                                                                                <span :class='markPrice(trade.symbol) != "Loading"  && (Number((markPrice(trade.symbol) - trade.price)*trade.quantity) > 0) ? "text-success":"text-danger"' >
                                                                                    {{ markPrice(trade.symbol) != "Loading"  ? Number((markPrice(trade.symbol) - trade.price)*trade.quantity).toFixed(4) : "Loading......."  }}
                                                                                     {{ markPrice(trade.symbol) != "Loading" ? '('+Number((markPrice(trade.symbol) - trade.price)/trade.price).toFixed(4)+"%)":''}}
                                                                                </span>
                                                                            </div>
                                                                        </td> -->
                                                                        <td >
                                                                            <button class="bg-transparent text-danger border-0" type="button"   @click="cancelOrder(trade.id)" :disabled="cancel_coin" >Cancel </button>
                                                                        </td>

                                                                            <td>
                                                                                <button type="button" class="bg-transparent text-danger border-0"  @click="closeOrder(trade.id)" v-if="trade.order_type == 'Market'" :disabled="order_closing">Close
                                                                                <div class=" spinner-grow  spinner-grow-sm text-danger" role="status" v-if="order_closing"></div>
                                                                            </button>
                                                                            </td>
                                                                    </tr>

                                                                </tbody>
                                                                <tbody v-if="!order_loading && open_orders.length == 0 && auth">
                                                                    <td colspan="10" class="text-center text-white">
                                                                            <svg  width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                           <div>You have no open orders </div>
                                                                        </td>
                                                                </tbody>
                                                                <tbody v-if="!auth">
                                                                    <td colspan="10" class="text-center text-white py-5">
                                                                            <div>
                                                                                <router-link :to="{name : 'Login'}" class="text-info">Login</router-link>
                                                                                or
                                                                                <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link>
                                                                                  to trade </div>
                                                                        </td>
                                                                </tbody>
                                                                <!-- <tbody >
                                                                    <tr>
                                                                        <td colspan="7" class="text-center text-white">
                                                                            <svg  width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                           <div>You have no open order </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody> -->
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-sm-12 col-md-5">
                                                            <div class="dataTables_info" id="ordertabtwo_info"
                                                                role="status" aria-live="polite">

                                                            </div>
                                                        </div>
                                                         <div class="col-sm-12 col-md-7" v-if="auth">
                                                            <div class="dataTables_paginate paging_simple_numbers"
                                                                id="ordertabtwo_paginate">
                                                                <ul class="pagination gap-2">
                                                                    <li class="paginate_button page-item previous"
                                                                        :class="order_page == 1 ? 'disabled' : ''"
                                                                        id="ordertabtwo_previous">
                                                                        <a href="#" aria-controls="ordertabtwo"
                                                                            data-dt-idx="0" tabindex="0"
                                                                            class="page-link" @click="open_prev">Previous</a>
                                                                    </li>
                                                                    <li class="paginate_button page-item next"
                                                                        :class="order_page == order_last_page ? 'disabled' : ''"
                                                                        id="ordertabtwo_next">
                                                                        <a href="#" aria-controls="ordertabtwo"
                                                                            data-dt-idx="2" tabindex="0"
                                                                            class="page-link" @click="open_next">Next</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Open Order end -->


                                             <!-- closeOrder Start -->
                                             <div class="tab-pane fade" id="closeOrder" role="tabpanel">
                                                <div id="ordertabtwo_wrapper"
                                                    class="dataTables_wrapper dt-bootstrap5 no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="dataTables_length" id="ordertabtwo_length">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div id="ordertabtwo_filter" class=""></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 table-responsive">
                                                            <div class="row d-block d-sm-none" v-if="close_order_loading">
                                                                <div>
                                                                    <div   class="text-center">
                                                                        <div class="spinner-grow text-info" role="status"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row  d-block d-sm-none " v-if="close_orders.length > 0">
                                                                <div class="col-md-4" v-for="(close_order,i) in close_orders">
                                                                    <div class="card mb-3 d-flex">
                                                                        <div class="card-header">
                                                                            {{ moment(close_order.created_at).format("DD-MM-YYYY, hh:mm:ss A") }}
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <!-- <div class="col-3">
                                                                                    <span :class="close_order.pnl_per > 0 ? 'text-success' : 'text-danger'">{{close_order.pnl}}({{close_order.pnl_per > 0 ? +close_order.pnl_per : close_order.pnl_per }}%)</span>
                                                                                </div> -->
                                                                                <div class="col-9">
                                                                                    <h5 class="card-title">{{ close_order.symbol }}</h5>
                                                                                    <div style="font-size: 12px">Type: <span
                                                                                        :class="close_order.type == 'Buy'? 'color-price-up': 'color-price-down'">{{close_order.type
                                                                                        }}</span></div>
                                                                                     <div style="font-size: 12px">Price: {{Number(close_order.price).toFixed(5)}}</div>
                                                                                    <div style="font-size: 12px">Close Price	:   {{ close_order.close_price}}</div>
                                                                                    <div style="font-size: 12px">Executed: {{close_order.close_quantity}}</div>
                                                                                    <div style="font-size: 12px">Total:  {{close_order.close_amount}}</div>
                                                                                    <div style="font-size: 12px">Close Time	: {{new Date(close_order.updated_at).toLocaleString()}}</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row d-block d-sm-none py-5 text-center" v-if="!auth">
                                                                <div>
                                                                    <router-link :to="{name : 'Login'}" class="text-info">Login</router-link> or <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link> to trade
                                                                </div>
                                                            </div>
                                                            <table id="ordertabtwo"
                                                                class="overflow-scroll d-none d-sm-block priceTable table table-hover custom-table-2 align-middle mb-0 nowrap table-borderless no-footer dtr-inline"
                                                                style="
                                                                    width: 100%; max-height :305px !important; overflow-y: scroll;
                                                                " aria-describedby="ordertabtwo_info">
                                                                <thead>
                                                                    <tr role="row">
                                                                        <!-- <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Date
                                                                        </th> -->
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Pair: activate to sort column ascending">
                                                                            Pair
                                                                        </th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Type: activate to sort column ascending">
                                                                            Type
                                                                        </th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Side: activate to sort column ascending">
                                                                            Side
                                                                      </th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Price: activate to sort column ascending">
                                                                            Price
                                                                        </th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Price: activate to sort column ascending">
                                                                            Close Price
                                                                        </th>
                                                                        <th class="dt-body-right sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Executed: activate to sort column ascending">
                                                                            Executed
                                                                        </th>
                                                                        <!-- <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Amount: activate to sort column ascending">
                                                                            Amount
                                                                        </th> -->
                                                                        <th class="dt-body-right sorting" tabindex="0"
                                                                        aria-controls="ordertabtwo" rowspan="1"
                                                                        colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Total: activate to sort column ascending">
                                                                            Total
                                                                        </th>
                                                                        <th class="dt-body-right sorting" tabindex="0"
                                                                        aria-controls="ordertabtwo" rowspan="1"
                                                                        colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Total: activate to sort column ascending">
                                                                            Open Time
                                                                        </th>
                                                                        <th class="dt-body-right sorting" tabindex="0"
                                                                        aria-controls="ordertabtwo" rowspan="1"
                                                                        colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Total: activate to sort column ascending">
                                                                            Close Time
                                                                        </th>
                                                                        <!-- <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Pnl: profit and loss">
                                                                            PNL(ROE%)
                                                                        </th> -->
                                                                    </tr>
                                                                </thead>
                                                                <tbody v-if="close_order_loading">
                                                                    <tr>
                                                                        <td colspan="10" class="text-center">
                                                                            <div class="spinner-grow text-info" role="status"></div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tbody v-if="close_orders.length >0 && auth">
                                                                    <tr role="row" class="odd" v-for="(close_order,i) in close_orders">
                                                                        <!-- <td tabindex="0" class="sorting_1">
                                                                            {{moment(close_order.created_at).format("DD-MM-YYYY,hh:mm:ss A")}}
                                                                        </td> -->
                                                                        <td>
                                                                            <img :src="'user/assets/images/coin/' + close_order.symbol.slice(0,-4) +'.png'"
                                                                                alt="coin"
                                                                                class="img-fluid avatar rounded-circle mx-1"
                                                                                v-if="close_order.symbol !== 'NPFUSDT'" >

                                                                            <img src="user/assets/images/coin/NPF.png"
                                                                                alt="coin"
                                                                                class="img-fluid avatar rounded-circle mx-1"
                                                                                v-else >

                                                                                {{close_order.symbol
                                                                            }}
                                                                        </td>
                                                                        <td> {{ close_order.order_type }}</td>
                                                                        <td>
                                                                            <span
                                                                                :class="close_order.type == 'Buy'? 'color-price-up': 'color-price-down'">{{close_order.type
                                                                                }}</span>
                                                                        </td>
                                                                        <td>{{Number(close_order.price).toFixed(5)}}</td>
                                                                        <!-- <td>
                                                                        Market
                                                                    </td> -->
                                                                        <td class="dt-body-right">
                                                                            {{ close_order.close_price}}
                                                                        </td>
                                                                        <td>
                                                                            {{close_order.close_quantity}}
                                                                        </td>
                                                                        <td class="dt-body-right">
                                                                            {{close_order.close_amount}}
                                                                        </td>
                                                                        <td>
                                                                            {{new Date(close_order.created_at).toLocaleString()}}
                                                                        </td>
                                                                        <td class="dt-body-right">
                                                                            {{new Date(close_order.updated_at).toLocaleString()}}
                                                                        </td>
                                                                        <!-- <td>
                                                                            <div>
                                                                                <span :class="close_order.pnl_per > 0 ? 'text-success' : 'text-danger'">{{close_order.pnl}}({{close_order.pnl_per > 0 ? +close_order.pnl_per : close_order.pnl_per }}%)</span>
                                                                            </div>
                                                                        </td> -->
                                                                        <!-- <td>
                                                                            <div >
                                                                                <span :class='markPrice(close_order.symbol) != "Loading"  && (Number((markPrice(close_order.close_price) - close_order.price)*trade.quantity) > 0) ? "text-success":"text-danger"' >
                                                                                    {{ markPrice(close_order.symbol) != "Loading"  ? Number((markPrice(close_order.close_price) - close_order.price)*trade.quantity).toFixed(4) : "Loading......."  }}
                                                                                     {{ markPrice(close_order.symbol) != "Loading" ? '('+Number((markPrice(close_order.close_price) - close_order.price)/trade.price).toFixed(4)+"%)":''}}
                                                                                </span>
                                                                            </div>
                                                                        </td> -->
                                                                     </tr>

                                                                </tbody>
                                                                <tbody v-if="!close_order_loading && close_orders.length == 0  && auth">
                                                                    <td colspan="10" class="text-center text-white">
                                                                            <svg  width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                           <div>You have no close orders </div>
                                                                        </td>
                                                                </tbody>
                                                                <tbody v-if="!auth">
                                                                    <td colspan="10" class="text-center text-white py-5">
                                                                            <div>
                                                                                <router-link :to="{name : 'Login'}" class="text-info">Login</router-link>
                                                                                or
                                                                                <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link>
                                                                                  to trade </div>
                                                                        </td>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-sm-12 col-md-5">
                                                            <div class="dataTables_info" id="ordertabtwo_info" role="status" aria-live="polite">
                                                            </div>
                                                        </div>
                                                         <div class="col-sm-12 col-md-7"  v-if="auth">
                                                            <div class="dataTables_paginate paging_simple_numbers" id="ordertabtwo_paginate">
                                                                <ul class="pagination gap-2">
                                                                    <li class="paginate_button page-item previous" :class="close_order_page == 1 ? 'disabled' : ''" id="ordertabtwo_previous">
                                                                        <a href="#" aria-controls="ordertabtwo" data-dt-idx="0" tabindex="0" class="page-link" @click="close_prev">Previous</a>
                                                                    </li>
                                                                    <li class="paginate_button page-item next" :class="close_order_page == close_order_last_page ? 'disabled' : ''" id="ordertabtwo_next">
                                                                        <a href="#" aria-controls="ordertabtwo" data-dt-idx="2" tabindex="0" class="page-link" @click="close_next">Next</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <pagination v-model="page" :records="records" @paginate="trades" :per-page="per_page" class="mt-4"  /> -->
                                                </div>
                                            </div>
                                            <!-- closeOrder End -->


                                            <!-- Order History start -->
                                            <div class="tab-pane fade show active" id="OrderHistory" role="tabpanel">
                                                <div id="ordertabtwo_wrapper"
                                                    class="dataTables_wrapper dt-bootstrap5 no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="dataTables_length" id="ordertabtwo_info">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div id="ordertabtwo_filter" class=""></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 table-responsive">

                                                            <div class="row d-block d-sm-none" v-if="order_loading">
                                                                <div>
                                                                    <div  class="text-center">
                                                                        <div class="spinner-grow text-info" role="status"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row  d-block d-sm-none " v-if="trades.length >0">
                                                                <div class="col-md-4"  v-for="(trade,i) in trades">
                                                                    <div class="card mb-3 d-flex">
                                                                        <div class="card-header">
                                                                            {{moment(trade.created_at).format("DD-MM-YYYY,hh:mm:ss A")}}                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">

                                                                                <div class="col-12">
                                                                                    <h5 class="card-title"> {{trade.symbol  }}</h5>
                                                                                    <div style="font-size: 12px">Type: {{ trade.order_type }}</div>
                                                                                     <div style="font-size: 12px">Side: <span
                                                                                        :class="trade.type == 'Buy'? 'color-price-up': 'color-price-down'"> {{trade.type }}  </span></div>
                                                                                    <div style="font-size: 12px">Price	:   {{Number(trade.price).toFixed(5)}}</div>
                                                                                    <div style="font-size: 12px">Executed:  {{ toFixed(trade.quantity,5)}}</div>
                                                                                    <div style="font-size: 12px">Amount:  {{ toFixed(trade.quantity,5)}}</div>
                                                                                    <div style="font-size: 12px">Total	:  {{trade.amount}}</div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row d-block d-sm-none py-5 text-center" v-if="!auth">
                                                                <div>
                                                                    <router-link :to="{name : 'Login'}" class="text-info">Login</router-link> or <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link> to trade
                                                                </div>
                                                            </div>
                                                            <table id="ordertabtwo"
                                                                class="overflow-scroll d-none d-sm-block priceTable table table-hover custom-table-2 align-middle mb-0 nowrap table-borderless no-footer dtr-inline"
                                                                style="
                                                                    width: 100%; max-height:305px !important; overflow-y: scroll;
                                                                " aria-describedby="ordertabtwo_info">
                                                                <thead>
                                                                    <tr role="row">
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Date
                                                                        </th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 170px ;
                                                                            "
                                                                            aria-label="Pair: activate to sort column ascending">
                                                                            Pair
                                                                        </th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Type: activate to sort column ascending">
                                                                            Type
                                                                        </th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Side: activate to sort column ascending">
                                                                            Side
                                                                      </th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 150px ;
                                                                            "
                                                                            aria-label="Price: activate to sort column ascending">
                                                                            Price
                                                                        </th>
                                                                        <th class="dt-body-right sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Executed: activate to sort column ascending">
                                                                            Executed
                                                                        </th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Amount: activate to sort column ascending">
                                                                            Amount
                                                                        </th>
                                                                        <th class="dt-body-right sorting" tabindex="0"
                                                                        aria-controls="ordertabtwo" rowspan="1"
                                                                        colspan="1" style="
                                                                                width: 100px;
                                                                            "
                                                                            aria-label="Total: activate to sort column ascending">
                                                                            Total
                                                                        </th>
                                                                        <!-- <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Pnl: profit and loss">
                                                                            PNL
                                                                        </th> -->
                                                                    </tr>
                                                                </thead>
                                                                <tbody v-if="order_loading">
                                                                    <tr>
                                                                        <td colspan="9" class="text-center">
                                                                            <div class="spinner-grow text-info" role="status"></div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tbody v-if="trades.length >0 && auth">
                                                                    <tr role="row" class="odd" v-for="(trade,i) in trades">
                                                                        <td tabindex="0" class="sorting_1">
                                                                            {{moment(trade.created_at).format("DD-MM-YYYY,hh:mm:ss A")}}
                                                                        </td>
                                                                        <td>
                                                                            <img :src="'user/assets/images/coin/' + trade.symbol.slice(0,-4) +'.png'"
                                                                                alt="coin"
                                                                                class="img-fluid avatar rounded-circle mx-1"
                                                                                v-if="trade.symbol !== 'NPFUSDT'" >

                                                                            <img src="user/assets/images/coin/NPF.png"
                                                                                alt="coin"
                                                                                class="img-fluid avatar rounded-circle mx-1"
                                                                                v-else >

                                                                                {{trade.symbol
                                                                            }}
                                                                        </td>
                                                                        <td> {{ trade.order_type }}</td>
                                                                        <td>
                                                                            <span
                                                                                :class="trade.type == 'Buy'? 'color-price-up': 'color-price-down'">{{trade.type
                                                                                }}</span>
                                                                        </td>
                                                                        <td>{{Number(trade.price).toFixed(5)}}</td>
                                                                        <!-- <td>
                                                                        Market
                                                                    </td> -->
                                                                        <td class="dt-body-right">
                                                                            {{ toFixed(trade.quantity,5)}}
                                                                        </td>
                                                                        <td>
                                                                            {{ toFixed(trade.quantity,5)}}
                                                                        </td>
                                                                        <td class="dt-body-right">
                                                                            {{trade.amount}}
                                                                        </td>
                                                                        <!-- <td>
                                                                            <div v-if="trade.type == 'Buy'">
                                                                                <span :class='markPrice(trade.symbol) != "Loading"  && (Number((markPrice(trade.symbol) - trade.price)*trade.quantity) > 0) ? "text-success":"text-danger"' >
                                                                                    {{ markPrice(trade.symbol) != "Loading"  ? Number((markPrice(trade.symbol) - trade.price)*trade.quantity).toFixed(4) : "Loading......."  }}
                                                                                     {{ markPrice(trade.symbol) != "Loading" ? '('+Number((markPrice(trade.symbol) - trade.price)/trade.price).toFixed(4)+"%)":''}}
                                                                                </span>
                                                                            </div>
                                                                            <div >
                                                                                <span :class="trade.pnl_per > 0 ? 'text-success' : 'text-danger'">{{trade.pnl}}({{trade.pnl_per > 0 ? +trade.pnl_per : trade.pnl_per }}%)</span>
                                                                            </div>
                                                                        </td> -->
                                                                        <!-- <td><span :class="trade.pnl_per > 0 ? 'text-success' : 'text-danger'">{{trade.pnl}}</span></td> -->
                                                                    </tr>

                                                                </tbody>
                                                                <tbody v-if="!order_loading && trades.length == 0 && auth">
                                                                    <td colspan="9" class="text-center text-white">
                                                                            <svg  width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                           <div>You have no orders </div>
                                                                        </td>
                                                                </tbody>
                                                                <tbody v-if="!auth">
                                                                    <td colspan="9" class="text-center text-white py-5">
                                                                            <div>
                                                                                <router-link :to="{name : 'Login'}" class="text-info">Login</router-link>
                                                                                or
                                                                                <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link>
                                                                                  to trade </div>
                                                                        </td>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                    <!-- <pagination v-model="page" :records="records" @paginate="trades" :per-page="per_page" class="mt-4"  /> -->
                                                    <div class="row mt-3">
                                                        <div class="col-sm-12 col-md-5">
                                                            <div class="dataTables_info" id="ordertabtwo_info"
                                                                role="status" aria-live="polite">
                                                                <!-- Showing 1 to 4 of 4
                                                            entries -->
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-7" v-if="auth">
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
                                            <!-- OrderHistorytab End -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row End -->
                </div>
            </div>
        </div>
        <!-- toaster -->
        <!-- <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" :class="toast"
                data-bs-animation="true" data-bs-autohide="true" data-bs-delay="5000">
                <div class="toast-header text-white" :class="text == 'Success' ? 'bg-success' : 'bg-danger'">
                    <strong class="me-auto">{{ text }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body"> {{ message }} </div>
            </div>
        </div> -->
    </div>
</template>
<script>
// import Chart from "chart.js/auto";
import pagination from "vue-pagination-2";
import moment from "moment";

import coinImg from "../ui/coinImg.vue";
//import VueApexCharts from "vue-apexcharts"; // Import VueApexCharts
//import npfGraph from "../pages/npf_chart.vue";

export default {
    name: "trade",
    components: {
        pagination,
        coinImg,
       // apexchart: VueApexCharts,
       // npf_chart : npfGraph,

    },
    data() {
        return {
            auth:true,
            chart: null,
            coin: localStorage.coin??"BTC",
            url: process.env.mix_api_url,
            balance: 0,
            usdt: 0,
            price: "",
            coin_amount: "",
            total: "",
            coin_sell: "",
            total_sell: "",
            buy_per: 1,
            sell_per: 1,
            error: false,
            error_sell: false,
            toast: "hide",
            message: "",
            text: "Error",
            trades: [],
            records: 1,
            page: 1,
            per_page: 1,
            last_page: 1,
            mobile: true,
            trade: false,
            show: false,
            buy_disable: false,
            cancel_coin: false,
            sell_disable: false,
            close_order: [],
            spin: true,
            trade_on: false,
            symbols: [],
            data: [],
            date: [],
            chartData:[],
            coinInfo:{},
            eth:0,
            trx:0,
            btc:0,
            bnb:0,
            total_balance:0,
            total_pnl:0,
            total_pnl_per:0,
            today_pnl:0,
            today_pnl_per:0,
            npf:0,
            order_loading:true,
            current_price:{},
            ws:null,
            disable_input : true,
            order_type : 'market',
            open_orders:[],
            order_records: 1,
            order_page: 1,
            order_per_page: 1,
            order_last_page: 1,
            open_order_loading:true,
            order_closing:false,


            close_orders:[],
            close_order_records: 1,
            close_order_page: 1,
            close_order_per_page: 1,
            close_order_last_page: 1,
            close_order_loading:true,

            wsConnection:{},
            npf_coin_loading : true,

        //     series: [
        //     {
        //       name: "",
        //       data: this.npfCoinPrice(),
        //     },
        //   ],
        //   chartOptions: {
        //         chart: {
        //         type: "area",
        //         stacked: false,
        //         height: 350,
        //         zoom: {
        //             type: "x",
        //             enabled: true,
        //             autoScaleYaxis: true,
        //         },
        //         toolbar: {
        //             autoSelected: "zoom",
        //         },
        //         },
        //         theme: {
        //         mode: "dark",
        //         },
        //         grid: {
        //         show: false,
        //         },
        //         dataLabels: {
        //         enabled: false,
        //         },
        //         markers: {
        //         size: 0,
        //         },
        //         title: {
        //         text: "NPF Exchange",
        //         align: "left",
        //         style: {
        //             color: "#ffffff",
        //         },
        //         },
        //         fill: {
        //         type: "gradient",
        //         gradient: {
        //             shadeIntensity: 1,
        //             inverseColors: false,
        //             opacityFrom: 0.5,
        //             opacityTo: 0,
        //         },
        //         },
        //         yaxis: {
        //         labels: {
        //             style: {
        //             colors: "#ffffff",
        //             },
        //             formatter: function (val) {
        //             return val.toFixed(3); // Format as a decimal with 3 places
        //             },
        //         },
        //         },
        //         xaxis: {
        //         type: "datetime",
        //         labels: {
        //             style: {
        //             colors: "#ffffff",
        //             },
        //         },
        //         crosshairs: {
        //             show: false,
        //         },
        //         },
        //         tooltip: {
        //         shared: false,
        //         y: {
        //             formatter: function (val) {
        //             return val.toFixed(3); // Show the exact price in tooltip
        //             },
        //         },
        //     },
        // },
            };
    },

    created() {
        this.getPrice();
        // this.setBalance();
        // this.usdtBalance();
        this.setBalance();
        this.tradeHistory();
        this.getSymbols();
        // this.npfPrices();
        this.coinDetails();
        this.getTotalBalance();
        this.openOrders();
        this.closeOrders();
        // this.tickerPrice();

    },
    beforeMount() {
        // set all values to false
        // this.items.forEach((item, index) => this.$set(this.close_order, index, false))
  },

    methods: {

        // npfCoinPrice() {
        //     const dates = [];
        //     let ts2 = new Date().setHours(0, 0, 0, 0);

        //     for (let i = 0; i < 6; i++) {
        //         // console.log("data is ====    " + res.data.prices);
        //         // const coinPrice = res.data.prices;
        //         const coinPrice = Math.floor(Math.random(2) * 10 + 2);
        //         dates.push([ts2, coinPrice]);
        //         ts2 -= 86400000; // Decrease by one day in milliseconds
        //     }
        //     return dates;

        //     axios.post(this.url + "api/npfPrices", {
        //             coin: this.coin,
        //     })
        //     .then((res) => {
        //     })
        // },

        EnableInput(type){
            type == 'limit' ? this.disable_input = false : this.disable_input = true;

            this.coin_sell = "";
            this.total  = "";
            this.total_sell  = "";
            this.buy_per = 1;
            this.sell_per = 1;
            this.order_type = type;
            type == 'limit' ? this.tradeHistory():this.openOrders();
            this.getPrice();
        },

        toFixed(n, fixed) {
            return ~~(Math.pow(10, fixed) * n) / Math.pow(10, fixed);
        },


        coins(val) {
            this.coin = val;
            localStorage.setItem("coin",val);
            this.show = false;
        },

        //manual paginations
        prev() {
            if (this.page > 1) {
                this.page = this.page - 1;
                this.tradeHistory();
            }
        },
        next() {
            if (this.page < this.last_page) {
                this.page = this.page + 1;
                this.tradeHistory();
            }
        },
        open_prev() {
            if (this.order_page > 1) {
                this.order_page = this.order_page - 1;
                this.openOrders();
            }
        },

        open_next() {
            if (this.order_page < this.order_last_page) {
                this.order_page = this.order_page + 1;
                this.openOrders();
            }
        },

        close_next() {

            if (this.close_order_page < this.close_order_last_page) {
                this.close_order_page = this.close_order_page + 1;
                this.closeOrders();
            }
        },

        close_prev() {
            if (this.close_order_page > 1) {
                this.close_order_page = this.close_order_page - 1;
                this.closeOrders();
            }
        },

        moment(date) {
            return moment.utc(date);
        },
        graphToggle() {
            this.mobile = !this.mobile;
        },

        //show graph only in desktop view
        showTrade() {
            this.mobile = true;
            this.trade = false;
        },
        showChart() {
            this.mobile = false;
            this.trade = true;
        },

        //set real time pnl from ws
        markPrice(asset){
                const symbol = asset.toLowerCase();

                if(asset != "NPFUSDT"){

                if(this.wsConnection[asset]){
                // console.log(`WebSocket for ${symbol} already exists.`);
                return this.current_price[asset] || "Loading"; // Reuse the existing connection
            }


               const ws = new WebSocket(
                   `wss://stream.binance.com:9443/ws/${symbol}@ticker`
                );

                // Handle incoming WebSocket messages (real-time data)
                ws.onmessage = (event) => {
                    const data = JSON.parse(event.data);
                    this.$set(this.current_price,asset,data.c);
                    // return data.c;
                 };

                 this.$set(this.wsConnection, asset, ws);

                }
                else{
                    this.$set(this.current_price,asset,this.price);
                }

                // this.ws.onclose = (event) => {
                //     if (!event.wasClean) {
                //         console.warn('WebSocket closed unexpectedly, retrying...');
                //         setTimeout(connect, 5000);
                //     }
                // };
            return this.current_price[asset] || "Loading";
        },

        // get coins data from coins.json
        coinDetails(){
            axios
                .get(this.url + "api/coin_details",{
                    params:{
                        coin:this.coin
                    }
                })
                .then((res) => {
                    this.coinInfo = res.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        //calculate total usdt balance from all coins
        getTotalBalance(){
            axios
                .get(this.url + "api/total_balance",{
                    params:{
                        token:localStorage.token
                    }
                })
                .then((res) => {

                    console.log("total_balance res "+res);

                    this.total_balance = res.data.total_balance;
                })
                .catch((err) => {
                    console.log('====================================');
                    console.log(err.response);
                    console.log('====================================');
                    console.log(err);
                    if(err.response.data.message.token){
                        this.auth = false;
                    }
                });
        },

        //get and set all coins balance
       async  setBalance(){
         this.usdt = await this.getBalance("epin");
         this.bnb = await this.getBalance("BNB");
         this.btc = await this.getBalance("BTC");
         this.trx = await this.getBalance("TRX");
         this.eth = await this.getBalance("ETH");
         this.npf = await this.getBalance("NPF");
         this.balance = await this.getBalance(this.coin);
        },

        async getBalance(coin) {
            this.coin_amount = "";
            this.total = "";
            this.coin_sell = "";
            this.total_sell = "";

          var res =  await axios
                .post(this.url + "api/coinBalance", {
                    coin: coin,
                    token: localStorage.token,
                });
                    // console.log("resddddd111111222");
                    //     console.log(res.data);
                        return res.data;

                // .then((res) => {
                //     // this.balance = res.data;
                // })
                // .catch((err) => {
                //     console.log(err);
                // });
        },

        //set total usdt and coin price on change price input for buy time
        setBuyPrices(){
            if(this.coin_amount > 0  && this.total > 0){
                console.log("herrer");

                var size = this.coinInfo[0].stepSize;
                if (this.coin == "NPF") {
                    size = 0;
                }
                this.coin_amount = this.toFixed(
                    this.total / this.price,
                    size
                );

                if (this.total > this.usdt) {
                     this.error = "Insufficient Balance1";
                }
            }
        },
                //set total usdt and coin price on change price input for sell time

        setSellPrices(){
            if(this.coin_sell > 0  && this.total_sell > 0){
                var size = this.coinInfo[0].stepSize;
                if (this.coin == "NPF") {
                    size = 0;
                }
                this.coin_sell = this.toFixed(
                this.total_sell / this.price,
                    size
                );
                if (this.coin_sell > this.balance) {
                    this.error_sell = "Insufficient Balance";
                }
            }
        },

        // // get only usdt balance
        // usdtBalance() {
        //     this.coin_amount = "";
        //     this.total = "";
        //     this.coin_sell = "";
        //     this.total_sell = "";
        //     this.sell_per = "";
        //     axios
        //         .post(this.url + "api/getUserDetails", {
        //             token: localStorage.token,
        //         })
        //         .then((res) => {
        //             // console.log(res);
        //             this.usdt = res.data.balance;
        //             if (res.data.user.id == 19) {
        //                 this.trade_on = false;
        //             }
        //         })
        //         .catch((err) => {
        //             console.log(err);
        //         });
        // },


        //get price and set graph only for npf coin


        // npfPrices() {
        //     axios
        //         .post(this.url + "api/npfPrices", {
        //             coin: this.coin,
        //                 })
        //             .then((res) => {
        //             this.data = res.data.prices;
        //             this.date = res.data.date;
        //             this.price = res.data.price.price;

        //             res.data.date.forEach((ress,i)=>{
        //                 console.log('NPF coin prices  = ');

        //             console.log(ress);

        //             console.log(res.data.prices[i]);
        //             var data = {
        //                 x:ress,
        //                 y:[res.data.data[i].open,res.data.data[i].high,res.data.data[i].low,res.data.data[i].close]
        //             }
        //                 this.chartData.push(data);

        //             });
        //             // this.createChart();
        //         })
        //         .catch((err) => {
        //             console.log(err);
        //         });
        // },

        npfPrices() {
            axios.post(this.url + "api/npfPrices", {
                    coin: this.coin,
            })
                .then((res) => {
                    this.data = res.data.prices;
                    this.date = res.data.date;
                    this.price = res.data.price.price;

                    //  this.npfCoinPrice();

                    // res.data.date.forEach((ress,i)=> {
                    //     this.series[0].data =  dates.push(innerArr);

                    //     console.log(res.data.prices[i]);
                    //     var data = {
                    //         x:ress,
                    //         y:[res.data.data[i].open,res.data.data[i].high,res.data.data[i].low,res.data.data[i].close]
                    //     }

                    //     this.chartData.push(data);
                    // });
                    // this.createChart();
                })
                .catch((err) => {
                    console.log(err);
                });
            },
        // get and set asset price of current selected coin
        getPrice() {

            // console.log("coin "+this.coin);
            if (this.coin != 'NPF') {
                axios
                    .post(this.url + "api/getAssetPrice", {
                        coin: this.coin,
                    })
                    .then((res) => {
                        console.log("price response");
                        console.log(res);
                        
                        var size = this.coinInfo[0].stepSize;
                        this.price = Number(res.data.price).toFixed(size);
                        // this.price = res.data.price;
                        console.log('====================================');
                        console.log(res.data.price);
                        console.log('====================================');
                        this.getSymbols();
                    })
                    .catch((err) => {
                        console.log("price error");
                        console.log(err);
                    });
            }
            else{
                this.npfPrices();
            }

        },

        //get all symbols from db
        getSymbols() {
            axios
                .post(this.url + "api/getSymbols")
                .then((res) => {
                    this.symbols = res.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        // calculate and set usdt on change from coin input when buy
        calUsdt() {
            this.error = false;
            this.total = this.toFixed(this.price * this.coin_amount, 4);
            console.log('total_balance '. this.total);
            console.log('usdt_balan '. this.usdt);
            if (this.total > this.usdt) {
                this.error = "Insufficient Balance";
            }
        },

        // calculate and set usdt on change from coin input when sell
        calSell() {
            // var stepSize = [];
            // stepSize["BNB"] = 2;
            // stepSize["BTC"] = 6;
            // stepSize["TRX"] = 1;
            var size = this.coinInfo[0].stepSize;
            this.error_sell = false;
            this.total_sell = this.toFixed(this.price * this.coin_sell, size);
            if (this.coin_sell > this.balance) {
                this.error_sell = "Insufficient Balance";
            }
        },

        // set value acc to percentage when buy
        buyPer() {
            this.error = false;
            var per = [];
            per[1] = 0;
            per[2] = 25;
            per[3] = 50;
            per[4] = 75;
            per[5] = 100;
            var val = per[this.buy_per];

            // console.log("per "+per);

            // console.log("buy_per "+per);

            // console.log("val "+val);


            // var stepSize = [];
            // stepSize["BNB"] = 2;
            // stepSize["BTC"] = 6;
            // stepSize["TRX"] = 1;

            // var size = stepSize[this.coin];
            var size = this.coinInfo[0].stepSize;
            // console.log("size "+size);
            if (this.coin == "NPF") {
                size = 0;
            }
            // console.log(this.toFixed(this.usdt * (val / 100), 4));
            // this.total = this.toFixed(this.usdt * (val / 100), 4);
            this.total = this.usdt * (val / 100);
            this.coin_amount = this.toFixed(
                this.total / this.price,
                size
            );
            if (this.total > this.usdt) {
                this.error = "Insufficient Balance";
            }
        },

        // set value acc to percentage when sell
        sellPer() {
            this.error_sell = false;
            var per = [];
            per[1] = 0;
            per[2] = 25;
            per[3] = 50;
            per[4] = 75;
            per[5] = 100;
            var val = per[this.sell_per];
            // console.log(this.balance * (val / 100));
            // var stepSize = [];
            // stepSize["BNB"] = 2;
            // stepSize["BTC"] = 6;
            // stepSize["TRX"] = 1;
            // this.total_sell = this.usdt * (val / 100);
            var size = this.coinInfo[0].stepSize;
            this.total_sell = this.toFixed(
                this.balance * (val / 100) * this.price,size);

            // var size = stepSize[this.coin];
            var size = this.coinInfo[0].stepSize;
            if (this.coin == "NPF") {
                size = 0;
            }

            this.coin_sell = this.toFixed(
                this.total_sell / this.price,
                size
            );
            if(val == 100){
                this.coin_sell = this.balance;
            }
            if (this.coin_sell > this.balance) {
                this.error_sell = "Insufficient Balance";
            }
        },


        // set coin value oninput usdt input when sell

        totalSell() {
            // var stepSize = [];
            // stepSize["BNB"] = 2;
            // stepSize["BTC"] = 6;
            // stepSize["TRX"] = 1;

            this.error_sell = false;
            this.coin_sell = this.toFixed(
                this.total_sell / this.price,
                this.coinInfo[0].stepSize
            );
            if (this.coin_sell > this.balance) {
                this.error_sell = "Insufficient Balance";
            }
        },

         // set coin value oninput usdt input when buy
        totalBuy() {
            this.error = false;
            // var stepSize = [];
            // stepSize["BNB"] = 2;
            // stepSize["BTC"] = 6;
            // stepSize["TRX"] = 1;
            this.coin_amount = this.toFixed(
                this.total / this.price,
                this.coinInfo[0].stepSize
            );
            // if (this.coin_amount > this.balance) {
            // if (this.total > this.usdt) {
            //     this.error = "Insufficient Balance";
            // }
            if (Number(this.total) > Number(this.usdt)) {
                    this.error = "Insufficient Balance";
            }
        },

        //get and set open orders history
        openOrders() {
            if(this.order_type == 'limit'){
                this.setBalance();
            }
            axios
                .post(this.url + "api/openOrders?page=" + this.order_page, {
                    token: localStorage.token,
                })
                .then((res) => {
                    this.open_orders = res.data.orders.data;
                    this.order_page = res.data.orders.current_page;
                    this.order_records = res.data.orders.total;
                    this.order_per_page = res.data.orders.per_page;
                    this.order_last_page = res.data.orders.last_page;
                    this.open_order_loading = false;
                })
                .catch((err) => {
                    console.log(err);
                    this.open_order_loading = false;
                });
        },

        //get and set close orders history
        closeOrders() {
            if(this.order_type == 'limit'){
                this.setBalance();
            }
            axios
                .post(this.url + "api/closeOrders?page=" + this.close_order_page, {
                    token: localStorage.token,
                })
                .then((res) => {
                    this.close_orders = res.data.orders.data;
                    this.close_page = res.data.orders.current_page;
                    this.close_order_records = res.data.orders.total;
                    this.closeorder_per_page = res.data.orders.per_page;
                    this.close_order_last_page = res.data.orders.last_page;
                    this.close_order_loading = false;
                })
                .catch((err) => {
                    console.log(err);
                    this.close_order_loading = false;
                });
        },

        //get and set trades orders history
        tradeHistory() {
            if(this.order_type == 'limit'){
                this.setBalance();
            }
            axios
                .post(this.url + "api/tradeHistory?page=" + this.page, {
                    token: localStorage.token,
                })
                .then((res) => {
              console.log("res");
              console.log(res);
                    this.trades = res.data.trades.data;
                    this.total_pnl = res.data.totalpnl;
                    this.total_pnl_per = res.data.total_pnl_per;
                    this.today_pnl = res.data.todaypnl;
                    this.today_pnl_per = res.data.today_pnl_per;
                    this.page = res.data.trades.current_page;
                    this.records = res.data.trades.total;
                    this.per_page = res.data.trades.per_page;
                    this.last_page = res.data.trades.last_page;
                    this.order_loading = false;
                })
                .catch((err) => {
                    console.log(err);
                    this.order_loading = false;
                });
        },


        //cancel open order
        cancelOrder(id) {
            if (confirm("Are you sure want to Cancel!")) {
                this.cancel_coin = true;

                axios
                    .post(this.url + "api/cancelOrder", {
                        token: localStorage.token,
                        id:  id,
                    })
                    .then((res) => {
                        console.log(res);
                        var message = res.data.message;
                        this.$toaster.success(message);
                        this.openOrders();
                        this.closeOrders();
                        this.setBalance();
                        this.getTotalBalance();
                        // this.usdtBalance();
                        this.getPrice();
                        this.tradeHistory();
                        this.cancel_coin = false;
                    })
                    .catch((err) => {
                        console.log(err);
                        var message = err.response.data.message;
                        if(typeof (message) == 'object'){
                            Object.values(message).forEach(msg => {
                                this.$toaster.error(msg[0]);
                         });
                        }
                        else{
                            this.$toaster.error(message);
                        }

                        this.cancel_coin = false;
                    });
            }
        },


        // trade buy
        buyCoin() {
          
          //console.log("working");
            if (confirm("Are you sure want to Buy!")) {
                this.buy_disable = true;
                this.spin = false;
                // var link = this.url + "api/buyCoin";
                // if (this.order_type == "market") {
                //     link = this.url + "api/buyCoin";
                // }
                var link = this.url + "api/manualBuy";
                if(this.order_type == 'limit'){
                    link = this.url + "api/openBuyOrder";
                }
              
              
              //console.log(link);

                axios
                    .post(link, {
                        quantity: this.coin_amount,
                        price: this.price,
                        coin: this.coin,
                        total: this.total,
                        token: localStorage.token,
                    })
                    .then((res) => {
                 // console.log(res);
                        // console.log(res.data);
                        this.buy_per = 1;
                        this.setBalance();
                        // this.usdtBalance();
                        this.getPrice();
                        this.openOrders();
                        this.closeOrders();
                        this.tradeHistory();
                    //    this.order_type == 'limit'? this.openOrders(): this.tradeHistory();
                        var message = res.data.message;
                        this.$toaster.success(message);
                        // this.message = message;

                        // this.text = "Success";
                        // this.toast="show bg-success text-white";
                        this.coin_amount = "";
                        this.total = "";
                        this.spin = true;
                        this.buy_disable = false;
                        // this.$bvToast.toast(message, {
                        //     variant: "success",
                        //     solid: true,
                        // });
                    })
                    .catch((err) => {
                  console.log("err");
                        console.log(err);
                        var message = err.response.data.message;

                        if(typeof (message) == 'object'){
                            Object.values(message).forEach(msg => {
                                this.$toaster.error(msg[0]);
                         });
                        }
                        else{
                            this.$toaster.error(message);
                        }
                        // this.message = message;
                        // this.text = "Error";
                        // this.toast="show bg-danger text-white";
                        this.coin_amount = "";
                        this.total = "";
                        this.buy_per = 1;
                        this.spin = true;
                        this.buy_disable = false;
                        // this.$bvToast.toast(message, {
                        //     variant: "danger",
                        //     solid: true,
                        // });
                    });
            }
        },

        //close order from open orders
        closeOrder(order_id) {
            if (confirm("Are you sure want close Order!")) {
                this.order_closing = true;
                axios
                    .post(this.url + "api/closeOrder", {
                        id: order_id,
                        token: localStorage.token,
                    })
                    .then((res) => {
                        // console.log(res);
                        this.setBalance();
                        this.getTotalBalance();
                        // this.usdtBalance();
                        this.getPrice();
                        this.openOrders();
                        this.closeOrders();
                        this.tradeHistory();
                        // this.order_type == 'limit'? this.openOrders(): this.tradeHistory();
                        var message = res.data.message;
                        this.$toaster.success(message);
                        this.order_closing = false;


                    })
                    .catch((err) => {
                        console.log(err);
                        var message = err.response.data.message;
                        if(typeof (message) == 'object'){
                            Object.values(message).forEach(msg => {
                                this.$toaster.error(msg[0]);
                         });
                        }
                        else{
                            this.$toaster.error(message);
                        }

                        this.sell_per = 1;

                        this.order_closing = false;


                    });
            }
        },

        // sell trade
           sellCoin() {
            if (confirm("Are you sure want to Sell!")) {
                this.sell_disable = false;
                this.spin = false;

                // var link = this.url + "api/sellCoin";
                // if (this.coin == "NPF") {
                //     link = this.url + "api/manualSell";
                // }
                // if (this.order_type  == "market") {
                //     link = this.url + "api/sellCoin";
                // }

                    var link = this.url + "api/manualSell";

                    if(this.order_type == 'limit'){
                        link = this.url + "api/openSellOrder";
                    }
                axios
                    .post(link, {
                        quantity: this.coin_sell,
                        price: this.price,
                        coin: this.coin,
                        total: this.total_sell,
                        token: localStorage.token,
                    })
                    .then((res) => {
                        // console.log(res);
                        this.setBalance();
                        this.getTotalBalance();
                        // this.usdtBalance();
                        this.getPrice();
                        this.order_type == 'limit'? this.openOrders(): this.tradeHistory();
                        var message = res.data.message;
                        this.$toaster.success(message);
                        // this.message = message;
                        // this.text = "Success";
                        // this.toast="show bg-success text-white";
                        this.coin_amount = "";
                        this.total_sell = "";
                        this.sell_per = 1;
                        this.spin = true;
                        this.sell_disable = false;
                    })
                    .catch((err) => {
                        console.log(err);
                        var message = err.response.data.message;
                        if(typeof (message) == 'object'){
                            Object.values(message).forEach(msg => {
                                this.$toaster.error(msg[0]);
                         });
                        }
                        else{
                            this.$toaster.error(message);
                        }
                        this.coin_amount = "";
                        this.total_sell = "";
                        this.sell_per = 1;
                        this.spin = true;
                        this.sell_disable = false;
                    });
            }
        },


        //create chart for npf
        createChart() {

        var options = {
        series: [{
        data: this.chartData
        }],
        chart: {
        type: 'candlestick',
        height: 350,
        },
        title: {
        text: '',
        align: 'left'
        },
        annotations: {
        xaxis: [
            {
            borderColor: '#00E396',
            label: {
                borderColor: '#00E396',
                style: {
                fontSize: '12px',
                color: '#fff',
                background: '#00E396'
                },
                orientation: 'horizontal',
                offsetY: 7,
            }
            }
        ]
        },
        xaxis: {
             type: 'datetime'
        },
        yaxis: {
        tooltip: {
            enabled: true,
            theme: 'dark', // You can use 'light' or 'dark' theme
                style: {
                background: '#333', // Change the background color here
                },
        }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

            // var options = {
            //     series: [{
            //         name: 'NPF EXCHANGE',
            //         data: this.data
            //     }],
            //     chart: {
            //         type: 'area',
            //         stacked: false,
            //         height: 350,
            //         zoom: {
            //             type: 'x',
            //             enabled: true,
            //             autoScaleYaxis: true
            //         },
            //         toolbar: {
            //             autoSelected: 'zoom'
            //         }
            //     },
            //     dataLabels: {
            //         enabled: false
            //     },
            //     markers: {
            //         size: 0,
            //     },
            //     title: {
            //         text: 'NPF Exchange Prices',
            //         align: 'left'
            //     },
            //     plotOptions: {
            //         candlestick: {
            //         colors: {
            //             upward: '#3C90EB',
            //             downward: '#DF7D46'
            //         },
            //         wick: {
            //             useFillColor: true,
            //             }
            //         }
            //     },
            //     fill: {
            //         type: 'gradient',
            //         gradient: {
            //             shadeIntensity: 1,
            //             inverseColors: false,
            //             opacityFrom: 0.5,
            //             opacityTo: 0,
            //             stops: [0, 90, 100]
            //         },
            //     },
            //     yaxis: {
            //         labels: {
            //             formatter: function (val) {
            //                 //   return (val / 1000000).toFixed(0);
            //                 return val;
            //             },
            //         },
            //         title: {
            //             text: 'Price'
            //         },
            //     },
            //     xaxis: {
            //         type: 'datetime',
            //         categories: this.date

            //     },
            //     tooltip: {
            //         shared: false,
            //         y: {
            //             formatter: function (val) {
            //                 //   return (val / 1000000).toFixed(0)
            //                 return val;
            //             }
            //         }
            //     }
            // };

            // var chart = new ApexCharts(document.querySelector("#chart"), options);
            // chart.render();
            // const ctx = this.$refs.chartCanvas.getContext('2d');
            // this.chart = new Chart(ctx, {
            //     type: 'line',
            //     data: {
            //         labels: this.date,
            //         datasets: [
            //             {
            //                 label: 'Live Rating',
            //                 borderColor: '#DD40FF',
            //                 borderWidth: 2,
            //                 data: this.data,
            //             },
            //         ],
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: false,
            //         aspectRatio: 1.3,
            //     },
            // });
        },
    },
    mounted(){


         var vm = this;
        // update price every 2 seconds
        // setInterval(()=>{
        //     vm.getPrice();
        // },2000);

         const rangeInputs = document.querySelectorAll('.custom-range');

         rangeInputs.forEach(rangeInput => {
            function updateBackground(value) {
                const percentage = ((value - 1) / 4) * 100;
                rangeInput.style.background = `linear-gradient(to right, #24d1e5 ${percentage}%, #e5e5e5 ${percentage}%)`;
        }

        updateBackground(rangeInput.value);

        rangeInput.addEventListener('input', (e) => {
            updateBackground(e.target.value);
        });
    });

    }
};
</script>

<style scoped>
.nav-link {
    color: white !important;
}

.table.custom-table-2 tbody tr {
    background-color: #052133;
    color: white !important;
}

.cstm_dropdown {
    background-color: #052133 !important;
    color: white !important;
}

table.table-bordered.dataTable tbody th,
table.table-bordered.dataTable tbody td {
    color: white !important;
    border-color: #092940 !important;
}

.lift {
    color: white !important;
}

.card {
    color: white !important;
    background-color: #052133;
    border-color: #092940 !important;
}

.card.card-body {
    background-color: #052133 !important;
    color: white !important;
}

.card.card-header {
    background-color: #052133;
    color: white !important;
}

.input-group> :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
    background-color: #052133;
    color: white !important;
    border-color: #092940 !important;
}

.input-group:not(.has-validation)> :not(:last-child):not(.dropdown-toggle):not(.dropdown-menu):not(.form-floating),
.input-group:not(.has-validation)>.dropdown-toggle:nth-last-child(n + 3),
.input-group:not(.has-validation)>.form-floating:not(:last-child)>.form-control,
.input-group:not(.has-validation)>.form-floating:not(:last-child)>.form-select {
    background-color: #052133;
    color: white !important;
    border-color: #092940 !important;
}

div.dataTables_wrapper div.dataTables_length select {
    background-color: #052133;
    color: white !important;
    border-color: #092940 !important;
}

.form-control {
    background-color: #052133;
    color: white !important;
    border-color: #092940 !important;
}

.form-control:focus {
    background-color: #052133;
    color: white !important;
    border-color: #092940 !important;
}

.main {
    background-color: #151a25 !important;
    color: white !important;
    border-color: #151a25 !important;
}

.layout-with-border-radius .layout__area--right:not(.no-border-top-left-radius) .widgetbar-pages,
.layout-with-border-radius .layout__area--right:not(.no-border-top-left-radius) .widgetbar-pages.hidden+.widgetbar-tabs,
.layout-with-border-radius .layout__area--right:not(.no-border-top-left-radius) .widgetbar-widget:first-child {
    background-color: 052133 !important;
    color: white !important;
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

thead,
tbody,
tfoot,
tr,
td,
th {
    color: white !important;
}

/* .widget-visible {
            display: none;
        } */

#x95lruu5mog1686812186080.widget-visible {
    display: none !important;
}


        @keyframes backgroundColorAnimation {
            0% {
              background-position: 100% 0;
            }
            100% {
              background-position: 0 0;
            }
          }

        .number-input {
            background-color: #536179 !important;
            animation: backgroundColorAnimation 1s forwards;

        }

        .custom_rounded{
            border-top-left-radius: 20px !important;
            border-bottom-left-radius: 20px !important;

        }

        .red_btn:hover {
            background-color: #fc5a69 !important;

        }

        .red_btn{
            background-color: #fc5a69 !important;

        }
        .mobile_btn{
            background-color: #666b66 !important;
            min-width: 65px;

            color: white !important;
        }



       .custom-range::-webkit-slider-runnable-track {
            height: 5;
        }

        .custom-range {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 5px;
            cursor: pointer;
            background: #e5e5e5;
            border-radius: 5px;
            outline: none;
        }

        .custom-range::-webkit-slider-runnable-track {
            height: 5px;
        }

        .custom-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 10px;
            height: 10px;
            background: #24d1e5;
            margin-top : -3px;
            border-radius: 10%;

            cursor: pointer;
            transform: rotate(45deg) !important;
            transform-origin: center;
        }

        .custom-range::-moz-range-thumb {
            width: 10px;
            height: 10px;
            background: #24d1e5;
            border-radius: 10%;
            cursor: pointer;
        }

        .custom-range::-ms-thumb {
            width: 10px;
            height: 10px;
            background: #24d1e5;
            border-radius: 10%;
            cursor: pointer;
        }
        ::-webkit-scrollbar {
            height: 5px;
            width: 5px;
          }

          /* Track */
          ::-webkit-scrollbar-track {
             border-radius: 10px;
          }

          /* Handle */
          ::-webkit-scrollbar-thumb {
            background: #686868;
            border-radius: 10px;
          }

          /* Handle on hover */
          ::-webkit-scrollbar-thumb:hover {
            background: #686868;
          }

</style>
