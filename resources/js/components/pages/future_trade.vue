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
                            <coinImg :coin="coin" />   {{ coin }}USDT
                        </a><small class="my-auto">Prep</small>
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
                                getPrice(),
                                getLeverages()
                                " data-bs-dismiss="offcanvas" aria-label="Close">
                                <coinImg :coin="symbol.name" />   {{ symbol.name }}USDT</a>
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
                                        <div id="chart" class="d-flex jusify-content-center"></div>
                                        <!-- <canvas ref="chartCanvas"></canvas> -->
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
                                                        Ubuntu, sans-serif;
                                                ">
                                                <div style="
                                                        width: 100%;
                                                        height: 100%;
                                                        background: transparent;
                                                        padding: 0 !important;
                                                    ">
                                                    <!-- <iframe id="tradingview_319a7" src="https://s.tradingview.com/widgetembed/?frameElementId=tradingview_319a7&amp;symbol=BITSTAMP%3ABTCUSD&amp;interval=D&amp;hidesidetoolbar=0&amp;symboledit=1&amp;saveimage=1&amp;toolbarbg=f1f3f6&amp;details=1&amp;calendar=1&amp;hotlist=1&amp;studies=%5B%5D&amp;style=1&amp;timezone=Etc%2FUTC&amp;withdateranges=1&amp;studies_overrides=%7B%7D&amp;overrides=%7B%7D&amp;enabled_features=%5B%5D&amp;disabled_features=%5B%5D&amp;locale=in&amp;utm_source=&amp;utm_medium=widget&amp;utm_campaign=chart&amp;utm_term=BITSTAMP%3ABTCUSD#%7B%22page-uri%22%3A%22__NHTTP__%22%7D" style="width: 100%; height: 100%; margin: 0 !important; padding: 0 !important;" frameborder="0" allowtransparency="true" scrolling="no" allowfullscreen="">
    </iframe> -->
                                                    <iframe id="tradingview_319a7" :src="'https://s.tradingview.com/widgetembed/?frameElementId=tradingview_319a7&amp;symbol=' +coin +'USDTPERP&amp;interval=D&amp;hidesidetoolbar=0&theme=dark&amp;symboledit=1&amp;saveimage=1&amp;toolbarbg=f1f3f6&amp;details=1&amp;calendar=1&amp;hotlist=1&amp;studies=%5B%5D&amp;style=1&amp;timezone=Etc%2FUTC&amp;withdateranges=1&amp;studies_overrides=%7B%7D&amp;overrides=%7B%7D&amp;enabled_features=%5B%5D&amp;disabled_features=%5B%5D&amp;locale=in&amp;utm_source=&amp;utm_medium=widget&amp;utm_campaign=chart&amp;utm_term=BITSTAMP%3ABTCUSD#%7B%22page-uri%22%3A%22__NHTTP__%22%7D'" style="
                                                            width: 100%;
                                                            height: 100%;
                                                            margin: 0 !important;
                                                            padding: 0 !important;
                                                        " frameborder="0" allowtransparency="true" scrolling="no"
                                                        allowfullscreen="">
                                                    </iframe>
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
                                <div class="card mb-3" style="max-height: 535px; overflow: scroll;">
                                    <div
                                        class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0 pt-5">
                                        <h6 class="mb-0 fw-bold text-light">Future</h6>
                                    </div>
                                    <div class="card-body">

                                        <!-- leverage option start  -->
                                        <div class="mb-3 mt-1">
                                            <button type="button" class="bg-black border-0 text-white px-4 py-1" data-bs-toggle="modal" data-bs-target="#leverageModal" @click="getLeverages" :class="{'d-none':!auth}"> {{final_leverage}}x</button>
                                            <button type="button" class="bg-black border-0 text-white px-4 py-1" data-bs-toggle="modal" data-bs-target="#crossModal" @click="getLeverages" :class="{'d-none':!auth}"> {{ final_margin}}</button>

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
                                                                    <button :class="['w-50','bg-transparent', 'p-2', 'text-white',   'border-1', { 'border-white': selectedMargin === 'cross' }]" @click="selectButton('cross')" >
                                                                          Cross </button>
                                                                    <button  :class="['w-50','bg-transparent', 'p-2', 'text-white',  'border-1', 'ms-3', { 'border-white': selectedMargin === 'Isolated' }]"  @click="selectButton('Isolated')" >
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
                                             <!-- leverage modal start  -->
                                            <div class="modal fade " id="leverageModal" data-bs-backdrop="static"
                                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="leverageModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog centered " style="margin-top:150px">
                                                    <div class="modal-content bg-dark">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="transfer_usdt">Adjust Leverage
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close" ></button>
                                                        </div>
                                                        <div class="modal-body" >
                                                            <label for="">Leverage</label>
                                                            <div class="input-group mb-3" >
                                                                <button class="border-0 px-4 bg-black" type="button" @click="subLev">-</button>
                                                                <input type="text" id="leverage" class="form-control text-center bg-black" :value="leverage+'x'" readonly>
                                                                <button class="border-0 px-4 bg-black" type="button" @click="addLev">+</button>
                                                            </div>

                                                            <div class="input-group mb-3 px-3">
                                                                    <div class="mb-2 d-flex justify-content-between align-items-center w-100">
                                                                    <span class="text-muted">1x</span>
                                                                    <span class="text-muted px-2">25x</span>
                                                                    <span class="text-muted px-1">50x</span>
                                                                    <span class="text-muted px-1">75x</span>
                                                                    <span class="text-muted">100x</span>
                                                                    <span class="text-muted">125x</span>
                                                                </div>
                                                                <input type="range" class="custom-range" id="rangeInput" min="1" max="125"  step="1" v-model="leverage"  />

                                                            </div>
                                                                <ul style="list-style-type: disc;">
                                                                    <li> <small> Maximum position at current leverage: 20,000,000 USDT  </small></li>
                                                                    <li> <small> Please note that leverage changing will also apply for open positions and open orders.</small></li>
                                                                </ul>
                                                                <ul class="mt-2 warning-list">
                                                                    <li class="text-danger"><small> Selecting higher leverage such as [10x] increases your liquidation risk. Always manage your risk levels. See our help article for more information. </small></li>
                                                                </ul>
                                                            <div class="d-flex justify-content-center">
                                                                <button class="btn butn rounded text-dark mb-4 mt-3 " type="button" :disabled="leveraging"  @click="setLeverage">Confirm
                                                                    <span class="spinner-grow spinner-grow-sm pl-2" role="status" aria-hidden="true" v-if="leveraging"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- leverage modal end  -->
                                        </div>
                                        <!-- leverage option end  -->


                                        <ul class="nav nav-tabs tab-body-header   border-0 nav-fill" role="tablist">
                                            <!-- <li class="nav-item" role="presentation">
                                                <a class="nav-link" href="#">Market</a>
                                            </li> -->
                                            <li class="nav-item" role="presentation"><a class="nav-link future-link active" data-bs-toggle="tab" href="#Market" role="tab" aria-selected="false" tabindex="-1"  @click="EnableInput('market')">Market</a></li>
                                            <li class="nav-item" role="presentation"><a class="nav-link future-link " data-bs-toggle="tab" href="#Market" role="tab" aria-selected="true"  @click="EnableInput('limit')">Limit</a></li>
                                            <!-- <li class="nav-item" role="presentation"><a class="nav-link" data-bs-toggle="tab" href="#Stoplimit" role="tab" aria-selected="false" tabindex="-1">Stop Limit</a></li> -->
                                        </ul>
                                        <div class="tab-content">

                                            <!-- Trade card start -->

                                            <div class="tab-pane fade show active" id="Market" role="tabpanel">
                                                <!-- <ul class="nav nav-tabs tab-body-header rounded d-flex mt-3 mx-auto col-8 justify-content-center"
                                                    role="tablist">
                                                    <li class="nav-item col  p-0" role="presentation">
                                                        <a class="nav-link active text-center" data-bs-toggle="tab"
                                                            href="#open" role="tab" aria-selected="true">Open</a>
                                                    </li>
                                                    <li class="nav-item col p-0" role="presentation">
                                                        <a class="nav-link text-center" data-bs-toggle="tab"
                                                            href="#close" role="tab" aria-selected="false"
                                                            tabindex="-1">Close</a>
                                                    </li>
                                                </ul> -->
                                                <div class="row g-3 tab-content">

                                                    <!-- Market open start -->

                                                    <!-- <div class="col-lg-12 tab-pane fade show active" id="open" role="tabpanel"> -->
                                                        <div class="col-lg-12 tab-pane fade show active  " :class="{' py-2': disable_input, 'py-1': !disable_input}"  id="open" role="tabpanel">

                                                        <div
                                                            class="d-flex align-items-center justify-content-between my-3">
                                                            <span class="small text-muted">Avbl</span>
                                                            <span class="">{{usd }} USDT</span>
                                                        </div>
                                                        <form class="pb-4">
                                                            <div class="input-group mb-3" :class="{'d-none':disable_input}" >
                                                                <span class="input-group-text custom_rounded" >Price</span>
                                                                <input type="number" class="form-control border"  v-model="price" :disabled="disable_input" @input="setBuyPrices" v-if="disable_input" />
                                                                <input type="number" class="form-control border"  v-model="limit_price"  @input="setBuyPrices" v-else />
                                                                <span class="input-group-text" >USDT</span>
                                                            </div>
                                                            <div class="input-group mb-3"  :class="{'mt-4':disable_input}">
                                                                <span class="input-group-text custom_rounded" :class="{'number-input': disable_input}">{{ coin }}</span>
                                                                <input type="number" min="0" :step="formattedNumber" class="form-control border" v-model="coin_amount" @input="calUsdt" :class="{'number-input': disable_input}" :placeholder="'Enter '+coin+' Amount'" @click="updateRangeUi();" />
                                                                <!-- <span class="input-group-text" :class="{'number-input': disable_input}">{{coin}}</span> -->
                                                            </div>

                                                            <!-- <div class="input-group mb-3"  :class="{'mt-4':disable_input}">
                                                                <span class="input-group-text custom_rounded" :class="{'number-input': disable_input}">USDT</span>
                                                                <input type="number" min="0" class="form-control border" v-model="total"  :class="{'number-input': disable_input}" placeholder="USDT Amount" readonly>
                                                            </div>
                                                            <small class="text-danger" v-if="error">{{error}}</small> -->

                                                            <div class="input-group mb-4 px-3 mt-4">
                                                                    <div class="mb-2 d-flex justify-content-between align-items-center w-100">
                                                                    <span class="text-muted">0%</span>
                                                                    <span class="text-muted px-2">25%</span>
                                                                    <span class="text-muted px-1">50%</span>
                                                                    <span class="text-muted px-1">75%</span>
                                                                    <span class="text-muted">100%</span>
                                                                </div>
                                                                <!-- <input type="range" class="form-range custom-range custom-range-color" min="1" max="5" value="1" step="1" v-model="buy_per" @change="buyPer" /> -->
                                                                <input type="range" class="custom-range custom-range-color" :class="{'my-2':disable_input}" min="0" max="100"  step="1" v-model="buy_per" @change="buyPer" />
                                                                <!-- <input type="range" :class="{'my-4':disable_input}"  class="custom-range custom-range-color" min="1" max="5" value="1" step="1" v-model="buy_per" @change="buyPer" /> -->
                                                            </div>
                                                            <div><input type="checkbox" class="me-1 tp_sl" v-model="isChecked"> TP/SL</div>


                                                            <div class="mt-3" v-if="isChecked">
                                                                <label class="small text-muted">Take Profit</label>
                                                                <div class="input-group mb-3 border rounded-5 px-3 border-0 number-input">
                                                                    <input type="number" class="form-control number-input" aria-label="Text input with dropdown button" v-model="take_profit">
                                                                    <button class="bg-transparent border-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false"> {{ profit_type }}</button>
                                                                    <!-- <button class="bg-transparent border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> {{ profit_type }}</button> -->
                                                                    <!-- <ul class="dropdown-menu dropdown-menu-end  number-input border shadow-lg">
                                                                      <li><a class="dropdown-item text-white" href="#" @click="selectTakeProfit('Mark')" >Mark</a></li>
                                                                      <li><a class="dropdown-item text-white" href="#" @click="selectTakeProfit('Last')">Last</a></li>
                                                                       </ul> -->
                                                                  </div>
                                                                <!-- <div class="input-group mb-3"  >
                                                                    <input type="number" class="form-control border"    />
                                                                    <span class="input-group-text" >Mark</span>
                                                                </div> -->
                                                                <label class="small text-muted">Stop Loss</label>

                                                                <div class="input-group mb-3 border rounded-5 px-3 border-0 number-input">
                                                                    <input type="number" class="form-control number-input" aria-label="Text input with dropdown button" v-model="stop_loss">
                                                                    <button class="bg-transparent border-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false"> {{ loss_type }}</button>
                                                                    <!-- <button class="bg-transparent border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> {{ loss_type }}</button> -->
                                                                    <!-- <ul class="dropdown-menu dropdown-menu-end  number-input border shadow-lg">
                                                                      <li><a class="dropdown-item text-white" href="#" @click="selectStopLoss('Mark')" >Mark</a></li>
                                                                      <li><a class="dropdown-item text-white" href="#" @click="selectStopLoss('Last')">Last</a></li>
                                                                       </ul> -->
                                                                  </div>
                                                                <!-- <div class="input-group mb-3"   >
                                                                     <input type="number" class="form-control  border"   />
                                                                     <span class="input-group-text" >LAST</span>
                                                                </div> -->
                                                            </div>
                                                            <div class="d-flex gap-3 mt-4" :class="{'my-3':disable_input}" v-if="auth">
                                                                <button type="button" class="flex-fill bg-success w-100  text-white border-0 rounded-pill py-2"  @click="buyCoin" :disabled="buy_disable">
                                                                    Buy/Long
                                                                    <div class="spinner-grow spinner-grow-sm text-light" role="status" v-if="buy_disable"></div>
                                                                </button>
                                                                <button type="button" class="flex-fill bg-danger w-100  text-white border-0 rounded-pill py-2" @click="sellCoin"  :disabled="sell_disable">
                                                                    Sell/Short
                                                                    <div class="spinner-grow spinner-grow-sm text-light" role="status" v-if="sell_disable"></div>
                                                                </button>
                                                            </div>
                                                            <div class="row px-2"  v-if="auth">
                                                                    <div class="col "><small>Cost <strong>{{ total != '' ? total: 0 }} USDT</strong></small></div>
                                                                    <div class="col text-end"><small>Cost <strong>{{ total_sell != '' ? total_sell: 0 }} USDT</strong></small></div>
                                                            </div>
                                                            <div class="row px-2"  v-if="auth">
                                                                <div class="col"><small>Max <strong>{{ max_coin == "Infinity" ? 'Loading...':max_coin }} {{ coin }}</strong></small></div>
                                                                <div class="col text-end"><small>Max <strong>{{ max_sell == "Infinity" ? 'Loading...':max_sell }} {{ coin }}</strong></small></div>
                                                            </div>
                                                            <div class=" d-flex gap-3 mt-4" v-if="!auth">
                                                                <router-link  class="flex-fill  bg-white text-dark w-100   border-0 rounded-pill py-2 text-center" :to="{ name : 'Login'}">
                                                                    Login
                                                                 </router-link>
                                                                <router-link class="flex-fill bg-info w-100  text-white border-0 rounded-pill py-2 text-center " :to="{ name : 'Register'}">
                                                                   Register
                                                                 </router-link>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <!-- Market open end -->

                                                     <!-- Market close start -->



                                                      <!-- Market sell end -->

                                                </div>
                                            </div>

                                              <!-- Trade Card end -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- Row End -->
                    <div class="my-5" v-if="trade_on == true">
                        <h3 class="text-center">Trading will be Coming Soon</h3>
                    </div>
                    <div :class="{ 'd-none': trade_on }">
                        <div class="row g-3 mb-3" :class="{ 'd-none d-md-block': trade }">

                            <div class="col-md-9">
                                <div class="card">
                                    <div
                                        class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
                                        <h6 class="mb-0 fw-bold text-light">
                                            Future trade Status
                                        </h6>
                                    </div>
                                    <ul class="d-sm-none d-flex overflow-scroll nav-tabs tab-body-header rounded d-inline-flex mb-3 col-12 border-end-0 border-start-0 border-top-0"
                                    role="tablist">
                                    <li class="nav-item col p-0" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#Positions" role="tab"
                                            aria-selected="true" @click="openOrders" style="font-size:11px !important; width:110px !important">Positions</a>
                                    </li>


                                    <li class="nav-item col p-0" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#OpenOrder" role="tab"
                                            aria-selected="true" @click="openOrders" style="font-size:11px !important; width:110px !important">Open Order</a>
                                    </li>
                                    <!-- <li class="nav-item col p-0" role="presentation">

                                            <a class="nav-link" data-bs-toggle="tab" href="#closeOrder" role="tab"
                                            aria-selected="true" @click="closeOrders" style="font-size:11px !important; width:110px !important">Order History</a>
                                    </li> -->
                                    <li class="nav-item col p-0" role="presentation">
                                        <a class="nav-link " data-bs-toggle="tab" href="#OrderHistory" role="tab" aria-selected="false" tabindex="-1" @click="orderHistory" style="font-size:11px !important; width:110px !important">Order History</a>
                                    </li>
                                    <li class="nav-item  p-0 text-center" role="presentation">
                                        <a class="nav-link " data-bs-toggle="tab" href="#positionHistory" role="tab" aria-selected="false" tabindex="-1" @click="positionHistory" style="font-size:11px !important; width:115px !important">Position History</a>
                                    </li>
                                </ul>
                                    <div class="card-body ">
                                        <ul class="d-none d-sm-flex nav nav-tabs tab-body-header rounded d-inline-flex mb-3 col-12 border-end-0 border-start-0 border-top-0"
                                            role="tablist">
                                            <li class="nav-item  p-0 text-center" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#Positions" role="tab" aria-selected="true" @click="positions">Positions</a>
                                            </li>
                                            <li class="nav-item  p-0 text-center" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#OpenOrder" role="tab" aria-selected="true" @click="openOrders">Open Order</a>
                                            </li>
                                            <!-- <li class="nav-item  p-0 text-center" role="presentation">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#closeOrder" role="tab" aria-selected="true" @click="closeOrders">Order History</a>
                                            </li> -->
                                            <li class="nav-item  p-0 text-center" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#OrderHistory" role="tab" aria-selected="false" tabindex="-1" @click="orderHistory">Order History</a>
                                            </li>
                                            <li class="nav-item  p-0 text-center" role="presentation">
                                                <a class="nav-link " data-bs-toggle="tab" href="#positionHistory" role="tab" aria-selected="false" tabindex="-1" @click="positionHistory">Position History</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                        <!-- Positions Order start -->
                                        <div class="tab-pane fade show active" id="Positions" role="tabpanel">
                                            <div id="ordertabone_wrapper"
                                                class="dataTables_wrapper dt-bootstrap5 no-footer">
                                                <div class="row">
                                                    <div class="col-sm-12">

                                                        <div class="row d-block d-sm-none" v-if="open_position_loading" >
                                                            <div>
                                                                <div colspan="9" class="text-center">
                                                                    <div class="spinner-grow text-info" role="status"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <div class="row d-block d-sm-none" v-if="open_positions.length > 0 && auth" >
                                                        <div class="col-md-4" v-for="(trade, i) in open_positions" :key="i">
                                                            <div class="card mb-3 d-flex">

                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between pb-2">
                                                                        <div class=" "> {{ trade.symbol }} <span class="badge bg-gradient"   style="font-size: 10px; font-weight: normal;">Perp  </span> <span class="badge bg-gradient"   style="font-size: 10px; font-weight: normal;">Isolated {{ leverage }}  </span> </div>
                                                                        <div class="my-auto"><i class="fas fa-share-alt-square"></i></div>

                                                                    </div>
                                                                    <div class="row">

                                                                        <div class="row">
                                                                            <div class="col-6">
                                                                                <div style="font-size: 14px"  >
                                                                                    <div class="text-muted small"> PNL ({{ coin }})  </div>
                                                                                    <div  :class="markPrice(trade.symbol) != 'Loading'  && (Number((markPrice(trade.symbol) - trade.price) * trade.quantity) > 0) ? 'text-success' : 'text-danger'">
                                                                                            {{ markPrice(trade.symbol) != 'Loading'  ? Number((markPrice(trade.symbol) - trade.price) * trade.quantity).toFixed(4) : 'Loading...' }}</div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 text-end">
                                                                                <div style="font-size: 14px" >
                                                                                    <div class="text-muted small"> ROI </div>
                                                                                    <div  :class="markPrice(trade.symbol) != 'Loading'  && (Number((markPrice(trade.symbol) - trade.price) * trade.quantity) > 0) ? 'text-success' : 'text-danger'">   {{ markPrice(trade.symbol) != 'Loading' ? '(' + Number((markPrice(trade.symbol) - trade.price) / trade.price).toFixed(4) + "%)" : ''}} </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-3">
                                                                            <div class="col-4">
                                                                                <div style="font-size: 10px">
                                                                                    <div class="text-muted small">Size ( {{ coin }})</div>
                                                                                    <div>  {{ trade.quantity }}</div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <div style="font-size: 10px">
                                                                                    <div class="text-muted small">Margin {{ coin}}</div>
                                                                                    <div> {{ trade.amount }}</div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <div style="font-size: 10px">
                                                                                    <div class="text-muted small">Margin Ratio</div>
                                                                                    <div> {{toFixed(trade.amount,5)}} USDT</div>
                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                        <div class="row mt-3">

                                                                            <div class="col-4">
                                                                                <div style="font-size: 10px">
                                                                                    <div class="text-muted small">Entry Price</div>
                                                                                    <div>  {{ trade.price  }}</div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <div style="font-size: 10px">
                                                                                    <div class="text-muted small">Mark Price {{coin}}</div>
                                                                                    <div> {{ markPrice(trade.symbol) }}</div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <div style="font-size: 10px">
                                                                                    <div class="text-muted small">Liq. Price {{coin}}</div>
                                                                                    <div> {{ trade.amount}}</div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <button class=" bg-dark text-white border-0  bg-gradient px-2 " style="font-size:12px !important" v-if="trade.order_type == 'Market'" @click="closePosition(trade.id)" :disabled="trade.order_closing"  >Close
                                                                                <div class="spinner-grow spinner-grow-sm text-danger" role="status" v-if="trade.order_closing"></div>
                                                                            </button>
                                                                            <button type="button" data-bs-toggle="modal"  :data-bs-target="'#mobiletpslmodal-' + trade.id" style="font-size:12px !important"   class=" bg-dark text-white border-0  bg-gradient px-2 "  :class="{'d-none':!auth}"  >  TP/SL	 </button>



                                                                        </div>

                                                                        <div class="modal fade "  :id="'mobiletpslmodal-' + trade.id" data-bs-backdrop="static"
                                                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="tpslmodalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog centered " style="margin-top:150px">
                                                                            <div class="modal-content bg-dark">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="transfer_usdt">TP/SL for entire position
                                                                                    </h5>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                        aria-label="Close" ></button>
                                                                                </div>
                                                                                <div class="modal-body" >
                                                                                  <div class="d-flex   justify-content-between small mt-2">
                                                                                      <span class="  text-muted">Symbol </span>
                                                                                      <span> {{trade.coin}}USDT Perpetual / {{ trade.type == "Buy" ? 'Long' : 'Short' }} {{ trade.leverage }}x</span>
                                                                                  </div>
                                                                                  <div class="d-flex   justify-content-between small mt-2">
                                                                                      <span class="  text-muted">Entry Price </span>
                                                                                      <span> {{Number(trade.price)}} USDT</span>
                                                                                  </div>
                                                                                  <div class="d-flex border-bottom pb-3  justify-content-between small mt-2">
                                                                                      <span class="  text-muted">Mark Price </span>
                                                                                      <span>  {{ markPrice(trade.symbol) }} USDT</span>
                                                                                  </div>
                                                                                  <div class="mb-3  ">
                                                                                      <label for="takeProfit" class="form-label   small mt-4 ">Take Profit</label>
                                                                                      <div class="input-group">
                                                                                        <input type="number" class="form-control border w-75 number-input" id="takeProfit"  v-model="take_profit">
                                                                                        <!-- <select class="form-control border number-input"  >
                                                                                          <option selected>Mark</option>
                                                                                          <option value="1">PnL</option>
                                                                                        </select> -->
                                                                                      </div>
                                                                                      <div class="small text-muted mt-2">
                                                                                          When Mark Price reaches {{ take_profit != "" ? take_profit :'0.0000'}}, it will trigger Take Profit Market 
<!-- <br> order to close this position. -->
                                                                                           <br> order to close this position. Estimated PNL will be {{take_profit != "" ?calPnl(trade.symbol,trade.price,trade.quantity,trade.type): "--"}} USDT 
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="mb-3">
                                                                                        <label for="stopLoss" class="form-label   small  ">Stop Loss</label>
                                                                                        <div class="input-group ">
                                                                                        <input type="number" class="form-control border w-75 number-input" id="stopLoss" v-model="stop_loss">
                                                                                        <!-- <select class="form-control border number-input"  >
                                                                                          <option selected>Mark</option>
                                                                                          <option value="1">PnL</option>
                                                                                        </select> -->
                                                                                      </div>
                                                                                      <div class="small text-muted mt-2">
                                                                                          When Mark Price reaches {{ stop_loss != "" ? stop_loss :'0.0000' }}, it will trigger Stop Market order to 
                                                                                        <!-- <br> close this position. -->
                                                                                            <br> close this position. Estimated PNL will be {{stop_loss != "" ?calPnl(trade.symbol,trade.price,trade.quantity,trade.type): "--"}} USDT 
                                                                                      </div>
                                                                                    </div>
                                                                                    <!-- <div class="mt-3">
                                                                                       <label class="form-check-label small" for="hideSymbols"> <i class="fas fa-info-circle"></i> What is position TP/SL</label>
                                                                                    </div> -->

                                                                                    <div class="d-flex justify-content-center">
                                                                                        <button class="btn butn rounded text-dark mb-4 mt-3 w-75" type="button" :disabled="updatepl"  @click="submitPl(trade.id)">Confirm
                                                                                            <span class="spinner-grow spinner-grow-sm pl-2" role="status" aria-hidden="true" v-if="updatepl"></span>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=" card card-body mt-2  d-block d-sm-none " v-if="!open_position_loading && open_positions.length == 0 && auth">
                                                        <div colspan="8" class="text-center text-white">
                                                                <svg style="width: 60px;" width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                               <div class="fs-6">You have no Position Orders  </div>
                                                            </div>
                                                    </div>
                                                    <div class="row d-block d-sm-none py-5 text-center" v-if="!auth">
                                                        <div>
                                                            <router-link :to="{name : 'Login'}" class="text-info">Login</router-link> or <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link> to trade
                                                        </div>
                                                    </div>

                                                          <table id="ordertabone" class=" overflow-scroll d-none d-sm-block priceTable table table-hover custom-table-2 align-middle mb-0 nowrap table-borderless no-footer dtr-inline" style=" width: 100%; " aria-describedby="ordertabone_info">
                                                            <thead>
                                                                <tr role="row">
                                                                   
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
                                                                        Leverage
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
                                                                       Order Type
                                                                    </th>

                                                                    <th class="sorting_asc" tabindex="0"
                                                                        aria-controls="ordertabone" rowspan="1"
                                                                        colspan="1" style="
                                                                            width: 0px;
                                                                        " aria-sort="ascending"
                                                                        aria-label="Date: activate to sort column descending">
                                                                        Entry Price
                                                                    </th>
                                                                    <th class="sorting_asc" tabindex="0"
                                                                        aria-controls="ordertabone" rowspan="1"
                                                                        colspan="1" style="
                                                                            width: 0px;
                                                                        " aria-sort="ascending"
                                                                        aria-label="Date: activate to sort column descending">
                                                                        Mark Price
                                                                    </th>
                                                                    <th class="sorting_asc" tabindex="0"
                                                                        aria-controls="ordertabone" rowspan="1"
                                                                        colspan="1" style="
                                                                            width: 0px;
                                                                        " aria-sort="ascending"
                                                                        aria-label="Date: activate to sort column descending">
                                                                        Size
                                                                    </th>
                                                                    <th class="sorting_asc" tabindex="0"
                                                                        aria-controls="ordertabone" rowspan="1"
                                                                        colspan="1" style="
                                                                            width: 0px;
                                                                        " aria-sort="ascending"
                                                                        aria-label="Date: activate to sort column descending">
                                                                        Amount
                                                                    </th>
                                                                    <th class="sorting_asc" tabindex="0"
                                                                        aria-controls="ordertabone" rowspan="1"
                                                                        colspan="1" style="
                                                                            width: 0px;
                                                                        " aria-sort="ascending"
                                                                        aria-label="Date: activate to sort column descending">
                                                                        Margin
                                                                    </th>
                                                                       <th class="sorting" tabindex="0"
                                                                        aria-controls="ordertabtwo" rowspan="1"
                                                                        colspan="1" style="
                                                                            width: 0px;
                                                                        "
                                                                        aria-label="Pnl: profit and loss">
                                                                        PNL(ROE%)
                                                                    </th>
                                                                       <th class="sorting" tabindex="0"
                                                                        aria-controls="ordertabtwo" rowspan="1"
                                                                        colspan="1" style="
                                                                            width: 0px;
                                                                        "
                                                                        aria-label="TP/SL">
                                                                        TP/SL
                                                                    </th>
                                                                    <th class="sorting_asc" tabindex="0"
                                                                        aria-controls="ordertabone" rowspan="1"
                                                                        colspan="1" style="
                                                                            width: 0px;
                                                                        " aria-sort="ascending"
                                                                        aria-label="Date: activate to sort column descending">
                                                                        Close Positions
                                                                    </th>
                                                                    <th class="sorting_asc" tabindex="0" aria-controls="ordertabone" rowspan="1"
                                                                    colspan="1" style=" width: 0px; " aria-sort="ascending" aria-label="Date: activate to sort column descending"> Date </th>
                                                                </tr>
                                                            </thead>

                                                            <tbody v-if="open_position_loading">
                                                                <tr>
                                                                    <td colspan="10" class="text-center">
                                                                        <div class="spinner-grow text-info" role="status"></div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tbody v-if="open_positions.length >0 && auth">
                                                                <tr role="row" class="odd" v-for="(trade,i) in open_positions">
                                                                    
                                                                    <td>
                                                                        <img :src="'user/assets/images/coin/' + trade.symbol.slice(0,-4) +'.png'"
                                                                            alt="coin" class="img-fluid avatar rounded-circle mx-1"
                                                                            v-if="trade.symbol !== 'NPFUSDT'" >
                                                                        <img src="user/assets/images/coin/NPF.png" alt="coin" class="img-fluid avatar rounded-circle mx-1" v-else > {{trade.symbol }}
                                                                    </td>
                                                                    <td>{{ trade.leverage }}x</td>
                                                                    <td> {{ trade.order_type }}</td>
                                                                    <td> {{ trade.type == "Buy" ? 'Long' : 'Short' }}</td>

                                                                    <td>{{Number(trade.price)}}</td>
                                                                    <td>{{ current_price[trade.symbol] }}</td>
                                                                    <td>  <span :class="trade.type == 'Buy'? 'text-success': 'text-danger'"> {{trade.type == 'Sell' ?  "-" : ""}}{{ toFixed(trade.quantity,5)}} {{ trade.symbol.replace("USDT","") }} </span></td>
                                                                    <td class="dt-body-right">
                                                                        {{toFixed((trade.amount*trade.leverage),5)}} USDT
                                                                    </td>
                                                                    <td class="dt-body-right">
                                                                        {{toFixed(trade.amount,5)}} USDT
                                                                    </td>
                                                                     <td>
                                                                        <div >
                                                                            <span :class='markPrice(trade.symbol) != "Loading"  && (calPnl(trade.symbol,trade.price,trade.quantity,trade.type) > 0) ? "text-success":"text-danger"' >
                                                                                {{ markPrice(trade.symbol) != "Loading"  ? calPnl(trade.symbol,trade.price,trade.quantity,trade.type) : "Loading......."  }}
                                                                                 {{ markPrice(trade.symbol) != "Loading" ? '('+calPnlPer(trade.symbol,trade.price,trade.quantity,trade.leverage,trade.type)+"%)":''}}
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                       <span> {{ trade.take_profit != null ? trade.take_profit : "--" }} / {{ trade.stop_loss != null ? trade.stop_loss : "--" }}</span>  <button type="button" data-bs-toggle="modal" :data-bs-target="'#tpslmodal-' + trade.id" class="border-0 bg-transparent text-white"  :class="{'d-none':!auth}"> <i class="fas fa-edit small ms-2"></i> </button>
                                                                       <!-- <button type="button" class="bg-black border-0 text-white px-4 py-1" data-bs-toggle="modal" data-bs-target="#leverageModal" @click="getLeverages" :class="{'d-none':!auth}"> {{final_leverage}}x</button> -->

                                                                       <!-- leverage modal start  -->
                                                                      <div class="modal fade "  :id="'tpslmodal-' + trade.id" data-bs-backdrop="static"
                                                                          data-bs-keyboard="false" tabindex="-1" aria-labelledby="tpslmodalLabel"
                                                                          aria-hidden="true">
                                                                          <div class="modal-dialog centered " style="margin-top:150px">
                                                                              <div class="modal-content bg-dark">
                                                                                  <div class="modal-header">
                                                                                      <h5 class="modal-title" id="transfer_usdt">TP/SL for entire position
                                                                                      </h5>
                                                                                      <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                          aria-label="Close" ></button>
                                                                                  </div>
                                                                                  <div class="modal-body" >
                                                                                    <div class="d-flex   justify-content-between small mt-2">
                                                                                        <span class="  text-muted">Symbol </span>
                                                                                        <span> {{trade.coin}}USDT Perpetual / {{ trade.type == "Buy" ? 'Long' : 'Short' }} {{ trade.leverage }}x</span>
                                                                                    </div>
                                                                                    <div class="d-flex   justify-content-between small mt-2">
                                                                                        <span class="  text-muted">Entry Price </span>
                                                                                        <span> {{Number(trade.price)}} USDT</span>
                                                                                    </div>
                                                                                    <div class="d-flex border-bottom pb-3  justify-content-between small mt-2">
                                                                                        <span class="  text-muted">Mark Price </span>
                                                                                        <span>  {{ markPrice(trade.symbol) }} USDT</span>
                                                                                    </div>
                                                                                    <div class="mb-3  ">
                                                                                        <label for="takeProfit" class="form-label   small mt-4 ">Take Profit</label>
                                                                                        <div class="input-group">
                                                                                          <input type="number" class="form-control border w-75 number-input" id="takeProfit"  v-model="take_profit">
                                                                                          <!-- <select class="form-control border number-input"  >
                                                                                            <option selected>Mark</option>
                                                                                            <option value="1">PnL</option>
                                                                                          </select> -->
                                                                                        </div>
                                                                                        <div class="small text-muted mt-2">
                                                                                            When Mark Price reaches {{ take_profit != "" ? take_profit :'0.0000'}}, it will trigger Take Profit Market <br> order to close this position.
                                                                                            <!-- <br> order to close this position. Estimated PNL will be -- USDT -->
                                                                                          </div>
                                                                                      </div>

                                                                                      <div class="mb-3">
                                                                                          <label for="stopLoss" class="form-label   small  ">Stop Loss</label>
                                                                                          <div class="input-group ">
                                                                                          <input type="number" class="form-control border w-75 number-input" id="stopLoss" v-model="stop_loss">
                                                                                          <!-- <select class="form-control border number-input"  >
                                                                                            <option selected>Mark</option>
                                                                                            <option value="1">PnL</option>
                                                                                          </select> -->
                                                                                        </div>
                                                                                        <div class="small text-muted mt-2">
                                                                                            When Mark Price reaches {{ stop_loss != "" ? stop_loss :'0.0000' }}, it will trigger Stop Market order to <br> close this position.
                                                                                             <!-- <br> close this position. Estimated PNL will be -- USDT -->
                                                                                        </div>
                                                                                      </div>
                                                                                      <!-- <div class="mt-3">
                                                                                         <label class="form-check-label small" for="hideSymbols"> <i class="fas fa-info-circle"></i> What is position TP/SL</label>
                                                                                      </div> -->

                                                                                      <div class="d-flex justify-content-center">
                                                                                          <button class="btn butn rounded text-dark mb-4 mt-3 w-75" type="button" :disabled="updatepl"  @click="submitPl(trade.id)">Confirm
                                                                                              <span class="spinner-grow spinner-grow-sm pl-2" role="status" aria-hidden="true" v-if="updatepl"></span>
                                                                                          </button>
                                                                                      </div>
                                                                                  </div>
                                                                              </div>
                                                                          </div>
                                                                      </div>
                                                                    </td>
                                                                        <td>
                                                                            <button type="button" class="bg-transparent text-info border-0"  @click="closePosition(trade.id)"  :disabled="trade.order_closing">Market
                                                                            <div class=" spinner-grow  spinner-grow-sm text-info" role="status" v-if="trade.order_closing"></div>
                                                                        </button>
                                                                        </td>
                                                                        <td tabindex="0" class="sorting_1">
                                                                            {{moment(trade.created_at).format("DD-MM-YYYY,hh:mm:ss A")}}
                                                                         </td>
                                                                </tr>

                                                            </tbody>
                                                            <tbody v-if="!open_position_loading && open_positions.length == 0 && auth">
                                                                <td colspan="10" class="text-center text-white">
                                                                        <svg  width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                       <div>You have no Position Orders </div>
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
                                                <div class="row mt-5">
                                                    <div class="col-sm-12 col-md-5">
                                                        <div class="dataTables_info" id="ordertabtwo_info"
                                                            role="status" aria-live="polite">

                                                        </div>
                                                    </div>
                                                     <!-- <div class="col-sm-12 col-md-7" v-if="auth && open_positions.length > 0">
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
                                                    </div> -->
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Positions Order end -->



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

                                                        <div class="row d-block d-sm-none" v-if="open_orders.length > 0 && auth">
                                                            <div class="col-md-4" v-for="(trade, i) in open_orders" :key="i">
                                                                <div class="card mb-3 d-flex">
                                                                    <div class="card-header">
                                                                        {{ moment(trade.created_at).format("DD-MM-YYYY, hh:mm:ss A") }}
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-3">
                                                                                <p class="card-text1 m-0">  <span :class="markPrice(trade.symbol) != 'Loading'  && (Number((markPrice(trade.symbol) - trade.price) * trade.quantity) > 0) ? 'text-success' : 'text-danger'">
                                                                                    {{ markPrice(trade.symbol) != 'Loading'  ? Number((markPrice(trade.symbol) - trade.price) * trade.quantity).toFixed(4) : 'Loading...' }}
                                                                                    {{ markPrice(trade.symbol) != 'Loading' ? '(' + Number((markPrice(trade.symbol) - trade.price) / trade.price).toFixed(4) + "%)" : ''}}
                                                                                </span></p>


                                                                            </div>
                                                                            <div class="col-6">
                                                                                <h5 class="card-title">{{ trade.symbol }}</h5>
                                                                                <div style="font-size: 12px">Order Type: {{ trade.order_type }}</div>
                                                                                <div style="font-size: 12px">Side: <span :class="trade.type == 'Buy' ? 'text-success' : 'text-danger'">{{ trade.type == "Buy" ? 'Long' : 'Short' }}</span></div>
                                                                                <div style="font-size: 12px">Price: {{ Number(trade.price).toFixed(5) }}</div>
                                                                                <div style="font-size: 12px">Quantity: {{ toFixed(trade.quantity, 5) }}</div>
                                                                                <div style="font-size: 12px">Amount: {{ toFixed(trade.amount, 5) }}</div>

                                                                            </div>
                                                                            <div class="col-3">
                                                                                <button class=" bg-transparent text-danger border-0  mobile_btn " v-if="trade.order_type == 'Market'" @click="closePosition(trade.id)" :disabled="order_closing"  >Close
                                                                                    <div class="spinner-grow spinner-grow-sm text-danger" role="status" v-if="order_closing"></div>
                                                                                </button>
                                                                                <!-- <button class="  bg-transparent text-danger mt-5 border-0 mobile_btn" @click="cancelOrder(trade.id)" :disabled="cancel_coin"  >Cancel</button> -->

                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class=" card card-body mt-2  d-block d-sm-none " v-if="!open_order_loading && open_orders.length == 0 && auth">
                                                            <div colspan="8" class="text-center text-white">
                                                                    <svg style="width:60px;" width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                   <div class="fs-6"> You have no Open Orders </div>
                                                                </div>
                                                        </div>

                                                        <div class="row d-block d-sm-none py-5 text-center" v-if="!auth">
                                                            <div>
                                                                <router-link :to="{name : 'Login'}" class="text-info">Login</router-link> or <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link> to trade
                                                            </div>
                                                        </div>

                                                            <table   id="ordertabone1"
                                                                class="table-responsive d-none d-sm-table priceTable table table-hover custom-table-2 align-middle mb-0 nowrap table-borderless no-footer dtr-inline"
                                                                style="
                                                                    width: 100%;
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
                                                                        <!-- <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabone" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="Date: activate to sort column descending">
                                                                            Close Orders
                                                                        </th> -->
                                                                    </tr>
                                                                </thead>

                                                                <tbody v-if="open_order_loading && auth" >
                                                                    <tr>
                                                                        <td colspan="10" class="text-center">
                                                                            <div class="spinner-grow text-info" role="status"></div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tbody v-if="open_orders.length > 0  && auth">
                                                                    <tr role="row" class="odd" v-for="(trade,i) in open_orders">
                                                                        <td tabindex="0" class="sorting_1"> {{moment(trade.created_at).format("DD-MM-YYYY,hh:mm:ss A")}} </td>
                                                                        <td>
                                                                            <img :src="'user/assets/images/coin/' + trade.symbol.slice(0,-4) +'.png'" alt="coin" class="img-fluid avatar rounded-circle mx-1" v-if="trade.symbol !== 'NPFUSDT'" >
                                                                            <img src="user/assets/images/coin/NPF.png" alt="coin" class="img-fluid avatar rounded-circle mx-1" v-else > {{trade.symbol }}
                                                                        </td>
                                                                        <td> {{ trade.order_type }}</td>
                                                                        <td> <span :class="trade.type == 'Buy'? 'color-price-up': 'color-price-down'">{{ trade.type == "Buy" ? 'Long' : 'Short' }}</span> </td>
                                                                        <td>{{Number(trade.price).toFixed(5)}}</td>
                                                                        <td>  {{ toFixed(trade.quantity,5)}}</td>
                                                                        <td class="dt-body-right"> {{ toFixed(trade.amount,5) }} </td>
                                                                        <!-- <td><span :class="trade.pnl_per > 0 ? 'text-success' : 'text-danger'">{{trade.pnl}}({{trade.pnl_per > 0 ? +trade.pnl_per : trade.pnl_per }}%)</span></td> -->
                                                                        <!-- <td>
                                                                            <div>
                                                                                <span :class='markPrice(trade.symbol) != "Loading"  && (calPnl(trade.symbol,trade.price,trade.quantity) > 0) ? "text-success":"text-danger"' >
                                                                                    {{ markPrice(trade.symbol) != "Loading"  ? calPnl(trade.symbol,trade.price,trade.quantity) : "Loading......."  }}
                                                                                    {{ markPrice(trade.symbol) != "Loading" ? '('+calPnlPer(trade.symbol,trade.price,trade.quantity,trade.leverage)+"%)":''}}
                                                                                </span>
                                                                            </div>
                                                                        </td> -->
                                                                        <td>
                                                                            <button class="bg-transparent text-danger border-0" type="button"   @click="cancelOrder(trade.id)" :disabled="cancel_coin" >Cancel </button>
                                                                        </td>
                                                                        <!-- <td>
                                                                            <button type="button" class="bg-transparent text-danger border-0"  @click="closePosition(trade.id)" v-if="trade.order_type == 'Market'" :disabled="order_closing">Close
                                                                                <div class=" spinner-grow  spinner-grow-sm text-danger" role="status" v-if="order_closing"></div>
                                                                            </button>
                                                                        </td> -->
                                                                    </tr>
                                                                </tbody>
                                                                <tbody v-if="!open_order_loading && open_orders.length == 0 && auth">
                                                                    <td colspan="10" class="text-center text-white">
                                                                            <svg  width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                           <div>You have no open orders </div>
                                                                        </td>
                                                                </tbody>
                                                                <tbody v-if="!auth">
                                                                    <td colspan="10" class="text-center text-white py-5">
                                                                        <div> <router-link :to="{name : 'Login'}" class="text-info">Login</router-link> or <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link> to trade </div>
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
                                                    <div class="row mt-5">
                                                        <div class="col-sm-12 col-md-5">
                                                            <div class="dataTables_info" id="ordertabtwo_info"
                                                                role="status" aria-live="polite">

                                                            </div>
                                                        </div>
                                                         <div class="col-sm-12 col-md-7" v-if="auth && open_orders.length > 0">
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

                                                            <div class="row  d-block d-sm-none " v-if="close_orders.length > 0 && auth">
                                                                <div class="col-md-4" v-for="(close_order,i) in close_orders">
                                                                    <div class="card mb-3 d-flex">
                                                                        <div class="card-header">
                                                                            {{ moment(close_order.created_at).format("DD-MM-YYYY, hh:mm:ss A") }}
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-3">
                                                                                    <span :class="close_order.pnl_per > 0 ? 'text-success' : 'text-danger'">{{close_order.pnl}}({{close_order.pnl_per > 0 ? +close_order.pnl_per : close_order.pnl_per }}%)</span>
                                                                                </div>
                                                                                <div class="col-9">
                                                                                    <!-- <h5 class="card-title">{{ close_order.symbol }}</h5> -->
                                                                                    <!-- <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span> Type: </span>
                                                                                         <span :class="close_order.type == 'Buy'? 'color-price-up': 'color-price-down'">{{close_order.type }}</span></div>
                                                                                     <div style="font-size: 12px" >
                                                                                        <span>Price:</span>
                                                                                        <span>{{Number(close_order.price).toFixed(5)}}</span>
                                                                                    </div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span>Close Price	: </span>
                                                                                        <span> {{ close_order.close_price}}</span>
                                                                                    </div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span>Executed: </span>
                                                                                        <span>{{close_order.close_quantity}}</span>
                                                                                    </div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span> Total:  </span>
                                                                                        <span>{{close_order.close_amount}}</span>
                                                                                    </div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span> Close Time	: </span>
                                                                                        <span> {{new Date(close_order.updated_at).toLocaleString()}}</span>
                                                                                    </div> -->
                                                                                    <div  class="d-flex justify-content-between">
                                                                                        <h5 class="card-title">{{ close_order.symbol }}  <span class="badge bg-gradient">Perp</span>
                                                                                        </h5>
                                                                                        <span>{{new Date(close_order.updated_at).toLocaleString()}}</span>
                                                                                    </div>
                                                                                    <div> <span :class="close_order.type == 'Buy'? 'color-price-up': 'color-price-down'">{{close_order.type }}</span></div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span> Order No : </span>
                                                                                        <span>{{close_order.order_id}}</span>
                                                                                    </div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span> Price : </span>
                                                                                        <span> {{Number(close_order.price).toFixed(5)}} </span>
                                                                                    </div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span> Filled ({{ coin }}) : </span>
                                                                                        <span> {{Number(close_order.close_quantity)}} </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                                 <div class=" card card-body mt-2  d-block d-sm-none " v-if="!close_order_loading && close_orders.length == 0 && auth">
                                                                <div colspan="8" class="text-center text-white">
                                                                        <svg style="width: 60px;" width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                       <div class="fs-6">You have no Order History </div>
                                                                    </div>
                                                            </div>
                                                            <div class="row d-block d-sm-none py-5 text-center" v-if="!auth">
                                                                <div>
                                                                    <router-link :to="{name : 'Login'}" class="text-info">Login</router-link> or <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link> to trade
                                                                </div>
                                                            </div>

                                                            <table id="ordertabtwo"
                                                                class=" table-responsive d-none d-sm-table priceTable table table-hover custom-table-2 align-middle mb-0 nowrap table-borderless no-footer dtr-inline"
                                                                style="
                                                                    width: 100%;
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
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Pnl: profit and loss">
                                                                            PNL(ROE%)
                                                                        </th>
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
                                                                        <td>
                                                                            <div>
                                                                                <span :class="close_order.pnl_per > 0 ? 'text-success' : 'text-danger'">{{close_order.pnl}}({{close_order.pnl_per > 0 ? +close_order.pnl_per : close_order.pnl_per }}%)</span>
                                                                            </div>
                                                                        </td>
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

                                                                <tbody v-if="!close_order_loading && close_orders.length == 0 && auth ">
                                                                    <td colspan="10" class="text-center text-white">
                                                                            <svg  width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                           <div >You have no Order History </div>
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
                                                    <div class="row mt-5">
                                                        <div class="col-sm-12 col-md-5">
                                                            <div class="dataTables_info" id="ordertabtwo_info"
                                                                role="status" aria-live="polite">

                                                            </div>
                                                        </div>
                                                         <div class="col-sm-12 col-md-7" v-if="auth && close_orders.length > 0">
                                                            <div class="dataTables_paginate paging_simple_numbers"
                                                                id="ordertabtwo_paginate">
                                                                <ul class="pagination gap-2">
                                                                    <li class="paginate_button page-item previous"
                                                                        :class="close_order_page == 1 ? 'disabled' : ''"
                                                                        id="ordertabtwo_previous">
                                                                        <a href="#" aria-controls="ordertabtwo"
                                                                            data-dt-idx="0" tabindex="0"
                                                                            class="page-link" @click="close_prev">Previous</a>
                                                                    </li>
                                                                    <li class="paginate_button page-item next"
                                                                        :class="close_order_page == close_order_last_page ? 'disabled' : ''"
                                                                        id="ordertabtwo_next">
                                                                        <a href="#" aria-controls="ordertabtwo"
                                                                            data-dt-idx="2" tabindex="0"
                                                                            class="page-link" @click="close_next">Next</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <pagination v-model="page" :records="records" @paginate="trades" :per-page="per_page" class="mt-4"  /> -->
                                                </div>
                                            </div>
                                            <!-- closeOrder End -->


                                            <!-- Trade History start -->
                                            <div class="tab-pane fade" id="OrderHistory" role="tabpanel">
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

                                                            <div class="row d-block d-sm-none" v-if="order_loading && auth">
                                                                <div>
                                                                    <div  class="text-center">
                                                                        <div class="spinner-grow text-info" role="status"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row  d-block d-sm-none " v-if="trades.length >0 && auth">
                                                                <div class="col-md-4"  v-for="(trade,i) in trades">
                                                                    <div class="card mb-3 d-flex">
                                                                        <div class="card-header">
                                                                                                                                            </div>
                                                                        <div class="card-body">
                                                                            <div class="row">

                                                                                <div class="col-12">
                                                                                    <!-- <h5 class="card-title"> {{trade.symbol  }}</h5>
                                                                                    <div style="font-size: 12px">Type: {{ trade.order_type }}</div>
                                                                                     <div style="font-size: 12px">Side: <span
                                                                                        :class="trade.type == 'Buy'? 'color-price-up': 'color-price-down'"> {{trade.type }}  </span></div>
                                                                                    <div style="font-size: 12px">Price	:   {{Number(trade.price).toFixed(5)}}</div>
                                                                                    <div style="font-size: 12px">Executed:  {{ toFixed(trade.quantity,5)}}</div>
                                                                                    <div style="font-size: 12px">Amount:  {{ toFixed(trade.quantity,5)}}</div>
                                                                                    <div style="font-size: 12px">Total	:  {{trade.amount}}</div> -->


                                                                                    <div  class="d-flex justify-content-between">
                                                                                        <h5 class="card-title">{{ trade.symbol }}  <span class="badge bg-gradient"   style="font-size: 10px; font-weight: normal;">Perp  </span> </h5>
                                                                                        <span style="font-size: 12px">{{new Date(trade.created_at).toLocaleString()}}</span>
                                                                                    </div>
                                                                                    <div style="font-size: 12px"> <span :class="trade.type == 'Buy'? 'color-price-up': 'color-price-down'">{{trade.type }}</span></div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span> Order No  </span>
                                                                                        <span>{{trade.order_id}}</span>
                                                                                    </div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span> Price  </span>
                                                                                        <span> {{Number(trade.price).toFixed(5)}} </span>
                                                                                    </div>
                                                                                    <div style="font-size: 12px" class="d-flex justify-content-between">
                                                                                        <span> Filled ({{ coin }})  </span>
                                                                                        <span> {{Number(trade.close_quantity)}} </span>
                                                                                    </div>

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
                                                                class="table-responsive d-none d-sm-table priceTable table table-hover custom-table-2 align-middle mb-0 nowrap table-borderless no-footer dtr-inline"
                                                                style="
                                                                    width: 100%;
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
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Total: activate to sort column ascending">
                                                                            Total
                                                                        </th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody v-if="order_loading ">
                                                                    <tr>
                                                                        <td colspan="8" class="text-center">
                                                                            <div class="spinner-grow text-info" role="status"></div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tbody v-if="trades.length >0 && auth ">
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
                                                                                :class="trade.type == 'Buy'? 'color-price-up': 'color-price-down'">{{ trade.type == "Buy" ? 'Long' : 'Short' }}</span>
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
                                                                        <!-- <td><span :class="trade.pnl_per > 0 ? 'text-success' : 'text-danger'">{{trade.pnl}}({{trade.pnl_per > 0 ? +trade.pnl_per : trade.pnl_per }}%)</span></td> -->
                                                                    </tr>

                                                                </tbody>

                                                                <tbody v-if="!order_loading && trades.length == 0 && auth ">
                                                                    <td colspan="8" class="text-center text-white">
                                                                            <svg width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                           <div>You have no orders </div>
                                                                        </td>
                                                                </tbody>
                                                                <tbody v-if="!auth">
                                                                    <td colspan="10" class="text-center text-white py-5">
                                                                            <div> <router-link :to="{name : 'Login'}" class="text-info">Login</router-link> or <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link> to trade </div>
                                                                        </td>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                    <!-- <pagination v-model="page" :records="records" @paginate="trades" :per-page="per_page" class="mt-4"  /> -->
                                                    <div class="row mt-5">
                                                        <div class="col-sm-12 col-md-5">
                                                            <div class="dataTables_info" id="ordertabtwo_info"
                                                                role="status" aria-live="polite">
                                                                <!-- Showing 1 to 4 of 4
                                                            entries -->
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-7" v-if="auth && trades.length > 0">
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
                                            <!-- Trade History tab End -->

                                            <!-- Position History start -->
                                            <div class="tab-pane fade " id="PositionHistory" role="tabpanel">
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
                                                        <div class="col-sm-12 table-responsive" style="max-height: 550px;">
                                                            <div class="row d-block d-sm-none" v-if="position_loading">
                                                                <div>
                                                                    <div class="text-center">
                                                                        <div class="spinner-grow text-info" role="status"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="row  d-block d-sm-none " v-if="position_history.length >0">
                                                                <div class="col-md-4"  v-for="(trade,i) in position_history">
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
                                                            </div> -->
                                                            <div v-if="position_history.length > 0 && auth"  v-for="(trade,i) in position_history" class=" card card-body mt-2"  id="ordertabtwo"  style=" width: 100%; ">
                                                                <div class=" d-none d-sm-flex justify-content-between align-items-center mb-3 mt-2">
                                                                    <div>
                                                                        <span class="me-2"><strong><img :src="'user/assets/images/coin/' + trade.symbol.slice(0,-4) +'.png'"
                                                                            alt="coin" class="img-fluid avatar rounded-circle mx-1" v-if="trade.symbol !== 'NPFUSDT'" >

                                                                        <img src="user/assets/images/coin/NPF.png" alt="coin" class="img-fluid avatar rounded-circle mx-1" v-else >
                                                                            {{ trade.symbol }}</strong></span>
                                                                        <span class="badge bg-gradient">Perp</span>
                                                                        <!-- <span class="badge bg-gradient text-success">Cross Long</span> -->
                                                                        <span :class="trade.type == 'Buy'? 'text-success': 'text-danger'" class="badge bg-gradient"> Isolated {{ trade.type == 'Buy'? 'Long': 'Short' }}</span>
                                                                        <span class="badge bg-gradient">  {{trade.status == '0' ? 'Open' : (trade.status == 1 ? 'Closed' : 'Cancel')}}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row d-none d-sm-flex">
                                                                    <div class="col-md-3 col-12">
                                                                        <div class="d-flex d-sm-block justify-content-between"><span class="small text-muted">Closing PNL</span>  <br class="d-none d-sm-block"> <span :class="Number(trade.pnl) < 0 ? 'text-danger':'text-success'">{{ Number(trade.pnl).toFixed(4) }} </span> </div>
                                                                        <div class="mt-3 d-flex d-sm-block justify-content-between"> <span class="small text-muted"> Opened </span>  <br class="d-none d-sm-block"> {{moment(trade.created_at).format("DD-MM-YYYY,hh:mm:ss A")}}</div>
                                                                    </div>
                                                                    <div class="col-md-3 col-12">
                                                                        <div class="d-flex d-sm-block justify-content-between"> <span class="small text-muted"> Entry Price</span>   <br class="d-none d-sm-block">{{Number(trade.price)}}  </div>
                                                                         <div class="mt-3 d-flex d-sm-block justify-content-between"> <span class="small text-muted">  {{trade.status == '0' ? 'Open' : (trade.status == 1 ? 'Closed' : 'Cancel')}} </span>    <br class="d-none d-sm-block"> {{moment(trade.updated_at).format("DD-MM-YYYY,hh:mm:ss A")}}</div>
                                                                    </div>
                                                                    <div class="col-md-3 col-12">
                                                                        <div class="d-flex d-sm-block justify-content-between"> <span class="small text-muted"> Avg. Close Price</span>   <br class="d-none d-sm-block">{{Number(trade.close_price)}}</div>
                                                                     </div>
                                                                    <div class="col-md-3 col-12">
                                                                        <div class="d-flex d-sm-block justify-content-between"> <span class="small text-muted"> Max Open Interest</span>    <br class="d-none d-sm-block"> {{Number(trade.quantity)}} {{trade.coin}}</div>
                                                                        <div class="mt-3 d-flex d-sm-block justify-content-between"> <span class="small text-muted"> Closed Volume</span>   <br class="d-none d-sm-block"> {{Number(trade.close_quantity)}} {{trade.coin}}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="row d-block d-sm-none">
                                                                    <div class="col-md-3 col-12">
                                                                        <div class="d-flex d-sm-block justify-content-between small pt-1"><div class=" h3 "> {{trade.symbol }}</div>   <span class="small text-muted"> {{moment(trade.created_at).format("DD-MM-YYYY,hh:mm:ss A")}}</span> </div>
                                                                        <div class="d-flex d-sm-block justify-content-between small"><div class=" small  " :class="trade.type == 'Buy'? 'text-success': 'text-danger'">{{trade.type}} / {{trade.order_type}}</div>   </div>
                                                                        <div class="d-flex d-sm-block justify-content-between small pt-1"><span class="small text-muted">Amount ({{trade.coin}})</span><span> {{Number(trade.quantity)}} / {{Number(trade.quantity)}}</span> </div>
                                                                        <div class=" d-flex d-sm-block justify-content-between small pt-1"> <span class="small text-muted"> Price </span><span>  {{Number(trade.price)}} / 0.0000</span></div>
                                                                        <div class=" d-flex d-sm-block justify-content-between small pt-1"> <span class="small text-muted"> Status </span><span class="text-success" :class="trade.status == 0 ? 'text-success': 'text-danger'">{{trade.status == '0' ? 'Open' : (trade.status == 1 ? 'Closed' : 'Cancel')}}</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  class=" card card-body mt-2"  id="ordertabtwo" style="width:100%;" v-if="!position_loading && position_history.length == 0 && auth">
                                                                <div colspan="8" class="text-center text-white">
                                                                        <svg style="width:60px" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                       <div class="fs-6">You have no position history </div>
                                                                    </div>
                                                            </div>
                                                            <div class="card py-5 text-center" id="ordertabtwo" style=" width: 100%; " v-if="!auth">
                                                                <div> <router-link :to="{name : 'Login'}" class="text-info">Login</router-link> or <router-link :to="{name : 'Register'}" class="text-info"> Register now </router-link> to trade </div>

                                                            </div>

                                                            <!-- <table id="ordertabtwo"
                                                                class="table-responsive d-none d-sm-table priceTable table table-hover custom-table-2 align-middle mb-0 nowrap table-borderless no-footer dtr-inline"
                                                                style="
                                                                    width: 100%;
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
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            " aria-sort="ascending"
                                                                            aria-label="closing_pnl">

                                                                        </th>
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
                                                                           Open Price
                                                                        </th>

                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="ordertabtwo" rowspan="1"
                                                                            colspan="1" style="
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="close_price">
                                                                            Close Price
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
                                                                                width: 0px;
                                                                            "
                                                                            aria-label="Total: activate to sort column ascending">
                                                                            Total
                                                                        </th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody v-if="position_loading">
                                                                    <tr>
                                                                        <td colspan="8" class="text-center">
                                                                            <div class="spinner-grow text-info" role="status"></div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tbody v-if="position_history.length >0">
                                                                    <tr role="row" class="odd" v-for="(trade,i) in position_history">
                                                                        <td tabindex="0" class="sorting_1">
                                                                            {{moment(trade.created_at).format("DD-MM-YYYY,hh:mm:ss A")}}
                                                                        </td>
                                                                        <td><span :class="Number((trade.close_price - trade.price) * trade.amount) < 0 ? 'text-danger':'text-success'">{{ Number((trade.close_price - trade.price) * trade.quantity).toFixed(2) }} </span></td>
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
                                                                                :class="trade.type == 'Buy'? 'color-price-up': 'color-price-down'"> {{ trade.type == 'Buy'? 'Long': 'Short' }}</span>
                                                                        </td>
                                                                        <td>{{Number(trade.price).toFixed(5)}}</td>

                                                                        <td class="dt-body-right">
                                                                            {{ trade.close_price}}
                                                                        </td>
                                                                        <td>
                                                                            {{ toFixed(trade.quantity,5)}}
                                                                        </td>
                                                                        <td class="dt-body-right">
                                                                            {{trade.amount}}
                                                                        </td>

                                                                    </tr>
                                                                </tbody>
                                                                <tbody v-if="!position_loading && position_history.length == 0 ">
                                                                    <td colspan="8" class="text-center text-white">
                                                                            <svg  width="100" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path><path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path></svg>
                                                                           <div>You have no position history </div>
                                                                        </td>
                                                                </tbody>
                                                            </table> -->

                                                        </div>
                                                    </div>
                                                    <!-- <pagination v-model="page" :records="records" @paginate="trades" :per-page="per_page" class="mt-4"  /> -->
                                                    <div class="row mt-5">
                                                        <div class="col-sm-12 col-md-5">
                                                            <div class="dataTables_info" id="ordertabtwo_info"
                                                                role="status" aria-live="polite">
                                                                <!-- Showing 1 to 4 of 4
                                                            entries -->
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-sm-12 col-md-7" v-if="auth && position_history.length > 0">
                                                            <div class="dataTables_paginate paging_simple_numbers"
                                                                id="ordertabtwo_paginate">
                                                                <ul class="pagination gap-2">
                                                                    <li class="paginate_button page-item previous" :class="page == 1 ? 'disabled' : ''" id="ordertabtwo_previous">
                                                                        <a href="#" aria-controls="ordertabtwo" data-dt-idx="0" tabindex="0" class="page-link" @click="prev">Previous</a>
                                                                    </li>
                                                                    <li class="paginate_button page-item next" :class="page ==last_page ? 'disabled' : ''" id="ordertabtwo_next">
                                                                        <a href="#" aria-controls="ordertabtwo" data-dt-idx="2" tabindex="0" class="page-link" @click="next">Next</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- position history End -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-3 mt-3">
                                <div class="card border-dark text-white border-light card-dark" >
                                    <div class="card-body bg-dark ">
                                        <div class="d-grid gap-3 ">
                                            <div class="fs-5">Margin Ratio</div>
                                            <div class="card-body p-0" style="max-height:225px;overflow:scroll">
                                                <div class="d-grid  gap-2 pb-2">
                                                    <div class="d-flex gap-2   w-100">
                                                        <div class="lh-1">
                                                            <div class="text-white text-muted small">{{coin}}USDT Perpetual</div>
                                                         </div>
                                                        <div class="ms-auto small">0.0</div>
                                                    </div>
                                                    <div class="d-flex gap-2   w-100">
                                                        <div class="lh-1">
                                                            <div class="text-white text-muted small">Maintenance Margin</div>
                                                         </div>
                                                        <div class="ms-auto small">0.0</div>
                                                    </div>
                                                    <div class="d-flex gap-2 w-100">
                                                        <div class="lh-1">
                                                            <div class="text-white text-muted small">Margin Balance</div>
                                                         </div>
                                                        <div class="ms-auto small">0.0</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-dark text-white border-light card-dark mt-3">
                                    <div class="card-body bg-dark ">
                                        <div class="d-grid gap-3 ">
                                            <div class="fs-5">USDT </div>
                                            <div class="card-body p-0" style="max-height:225px;overflow:scroll">
                                                <div class="d-grid gap-2   ">
                                                    <div class="d-flex gap-2  w-100">
                                                        <div class="lh-1">
                                                            <div class="text-white text-muted small">Balance</div>
                                                         </div>
                                                        <div class="ms-auto small">{{ Number(total_balance).toFixed(4) }}</div>
                                                    </div>
                                                    <div class="d-flex gap-2 w-100">
                                                        <div class="lh-1">
                                                            <div class="text-white text-muted small">Unrealized pnl</div>
                                                         </div>
                                                        <div class="ms-auto small">{{ totalUnrealizedPnL }}</div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
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
                <div class="toast-body">
                    {{ message }}
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
// import Chart from "chart.js/auto";
import pagination from "vue-pagination-2";
import moment from "moment";

// import { createChart } from 'lightweight-charts';
import coinImg from "../ui/coinImg.vue";


export default {
    name: "future_trade",
    components: {
        pagination,
        coinImg
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
            limit_price:"",
            coin_amount: "",
            total: "",
            coin_sell: "",
            total_sell: "",
            buy_per: 0,
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
            usd:0,
            total_balance:0,
            total_pnl:0,
            total_pnl_per:0,
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

            current_pnl:{},
            current_pnlPer:{},


            leverage:"1",
            final_leverage:"1",
            disable:false,
            leveraging:false,
            formattedNumber:"0.0",
            coin_type : "",
            max_coin:0,
            max_sell:0,

            open_positions:[],
            position_records: 1,
            position_page: 1,
            position_per_page: 1,
            position_last_page: 1,
            open_position_loading:true,

            position_history:[],
            position_history_records: 1,
            position_history_page: 1,
            position_history_per_page: 1,
            position_history_last_page: 1,
            position_loading:true,

            wsConnection:{},
            maintenance_margin: 0,
            totalUnrealizedPnL:0,
            margin_balance:0,
            profit_type : 'Mark',
            loss_type : 'Mark',
            isChecked: false,

            take_profit:"",
            stop_loss:"",
            updatepl:false,
            final_margin: 'isolated',
            marginMode:false,
            selectedMargin: 'isolated',




        };
    },

    created() {
        this.getPrice();
        // this.setBalance();
        // this.usdtBalance();
        var vm = this;
        if(!this.disable_input){
            setInterval(()=>{
                vm.getPrice();
            },1000);
        }

        this.coinDetails();
        this.setBalance();
        this.orderHistory();
        this.getSymbols();
        // this.npfPrices();
        this.getTotalBalance();
        // this.closeOrders();
        this.getLeverages();

        this.positions();
        this.positionHistory();
        this.calTotalPnl();

       this.openOrders();
       this.orderHistory();

    },
    beforeMount() {
        // set all values to false
        // this.items.forEach((item, index) => this.$set(this.close_order, index, false))
  },

  watch: {
    // Watch for changes in current prices for each asset
    open_positions: {
      handler(newAssets) {
        newAssets.forEach((asset) => {
            console.log("sssssssssssssssssssss");
            console.log(asset);

        //   if (asset.currentPrice >= asset.targetPrice) {
        //     this.positions();
        //   }
        });
      },
      deep: true,
    },
  },

    methods: {
        selectButton(button) {
                this.selectedMargin = button;
         },
        selectTakeProfit(item) {
             this.take_profit = item;
        },
        selectStopLoss(item) {
            this.stop_loss = item;
        },
        setMarginMode(){
                this.marginMode=true;
                axios.post(this.url+ "api/setMargin",{
                    token:localStorage.token,
                    coin:this.coin,
                    margin:this.selectedMargin
                }).then(res=>{
                    // console.log(res);
                    this.getLeverages();
                    this.$toaster.success(res.data.message);
                    this.marginMode=false;
                    $(".btn-close").click();
                }).catch(err=>{
                    this.$toaster.error(err.response.data.message);
                    this.marginMode=false;
                });
            },
                selectTakeProfit(item) {
                this.profit_type = item;
        },
        selectStopLoss(item) {
            this.loss_type = item;
        },
        calTotalPnl(){
            var vm  = this;
            // positions.forEach(order => {
            //         const unrealizedPnL = (vm.markPrice(order.symbol) - order.price) * order.quantity;
            //         this.totalUnrealizedPnL += unrealizedPnL;
            // });
        },

        calPnl(asset,price,quantity,side){
           var pnl =  Number((this.markPrice(asset) - price) * quantity).toFixed(2);
           if(side == 'Sell'){
                pnl = Number((price - this.markPrice(asset))*quantity).toFixed(2);
           }
           this.$set(this.current_pnl,asset,pnl);
           return this.current_pnl[asset];
        },
        calPnlPer(asset,price,quantity,leverage,side){
            var pnl =  Number((this.markPrice(asset) - price) * quantity).toFixed(2);
            if(side == 'Sell'){
                pnl = Number((price - this.markPrice(asset))*quantity).toFixed(2);
           }
            var margin = (price*quantity)/leverage;
            var pnl_per = Number((pnl/margin) * 100).toFixed(2);

            this.$set(this.current_pnlPer,asset,pnl_per);

            return this.current_pnlPer[asset];

        },
        fix(value, decimals) {
            const factor = Math.pow(10, decimals);
            return (Math.floor(value * factor) / factor).toFixed(decimals);
        },
        subLev(){
            if(this.leverage > 0){
                this.leverage = Number(this.leverage) - 1;

                const rangeInput_id = document.getElementById('rangeInput');
                const percentage = (this.leverage / rangeInput_id.max) * 100;
                rangeInput_id.style.background = `linear-gradient(to right, #24d1e5 ${percentage}%, #e5e5e5 ${percentage}%)`;
            }
        },
        addLev(){
            if(this.leverage < 125){
                this.leverage = Number(this.leverage) + 1;

                const rangeInput_id = document.getElementById('rangeInput');
                const percentage = (this.leverage / rangeInput_id.max) * 100;
                rangeInput_id.style.background = `linear-gradient(to right, #24d1e5 ${percentage}%, #e5e5e5 ${percentage}%)`;
            }
        },

        getLeverages(){
            axios
                .get(this.url + "api/getLeverage",{
                    params:{
                        token:localStorage.token,
                        coin:this.coin
                    }
                })
                .then((res) => {
                    console.log(res);
                    this.leverage = res.data.leverage;
                    this.final_leverage = res.data.leverage;
                    this.final_margin = res.data.margin;

                })
                .catch((err) => {
                    console.log(err);
                });
        },
        setLeverage(){
            this.leveraging=true;
            axios.post(this.url+ "api/setLeverage",{
                token:localStorage.token,
                coin:this.coin,
                leverage:this.leverage
            }).then(res=>{
                // console.log(res);
                this.getLeverages();
                this.$toaster.success(res.data.message);
                this.leveraging=false;
                $(".btn-close").click();
            }).catch(err=>{
                this.$toaster.error(err.response.data.message);
                this.leveraging=false;
            });
        },

        //hide show input type of basis of limit and market order
        EnableInput(type){
            type == 'limit' ? this.disable_input = false : this.disable_input = true;
            // this.disable_input = !this.disable_input;
            this.coin_sell = "";
            this.total  = "";
            this.total_sell  = "";
            this.buy_per = 1;
            this.sell_per = 1;
            this.order_type = type;
            // type == 'limit' ? this.orderHistory():this.openOrders();
            this.getPrice();
            console.log(this.limit_price);

            this.updateRangeUi();
        },

        //set value after decimal
        toFixed(n, fixed) {
            return ~~(Math.pow(10, fixed) * n) / Math.pow(10, fixed);
        },

        //onchange asset set coin and store in localstorage
        coins(val) {
            this.coin = val;
            // localStorage.setItem("coin",val);
            this.show = false;
        },

        //manual paginations
        prev() {
            if (this.page > 1) {
                this.page = this.page - 1;
                this.orderHistory();
            }
        },
        next() {
            if (this.page < this.last_page) {
                this.page = this.page + 1;
                this.orderHistory();
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
                // this.closeOrders();
            }
        },

        close_prev() {
            if (this.close_order_page > 1) {
                this.close_order_page = this.close_order_page - 1;
                // this.closeOrders();
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

            if(this.wsConnection[asset]){
                // console.log(`WebSocket for ${symbol} already exists.`);
                return this.current_price[asset] || "Loading"; // Reuse the existing connection
            }

            // let ws = this.wsConnection[asset];
            // if(!this.wsConnection[asset]){
            //   ws =  new WebSocket(
            //        `wss://fstream.binance.com/ws/${symbol}@ticker`
            //     );
            // }

            if(asset != "NPFUSDT"){
                const symbol = asset.toLowerCase();

                const ws = new WebSocket(
                    `wss://fstream.binance.com/ws/${symbol}@ticker`
                        );

                    // Handle incoming WebSocket messages (real-time data)
                    ws.onmessage = (event) => {
                        const data = JSON.parse(event.data);
                            this.$set(this.current_price,asset,data.c);
                            // return data.c;
                    };

                  //set new connection acc. to symbol
                this.$set(this.wsConnection, asset, ws);

                // this.ws.onclose = (event) => {
                //     if (!event.wasClean) {
                //         console.warn('WebSocket closed unexpectedly, retrying...');
                //         setTimeout(connect, 5000);
                //     }
                // };
            }
            else{
                this.$set(this.current_price,asset,this.price);
            }

            return this.current_price[asset] || "Loading";
        },

        // get coins data from coins.json
        coinDetails(){
            axios
                .get(this.url + "api/future_coin_details",{
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
                .get(this.url + "api/future_total_balance",{
                    params:{
                        token:localStorage.token
                    }
                })
                .then((res) => {

                    // console.log("total_balance res "+res);

                    this.total_balance = res.data.total_balance;
                })
                .catch((err) => {
                    console.log(err);
                    if(err.response.data.message.token){
                        this.auth = false;
                    }
                });
        },

        //get and set all coins balance
       async  setBalance(){
         this.usdt = await this.getBalance("epin");
         this.usd = await this.getBalance("usd");
         this.bnb = await this.getBalance("BNB");
         this.btc = await this.getBalance("BTC");
         this.trx = await this.getBalance("TRX");
         this.eth = await this.getBalance("ETH");
         this.npf = await this.getBalance("NPF");
         this.balance = await this.getBalance(this.coin);
         this.max_coin = Number(((this.usd*this.final_leverage)/this.price).toFixed(4));

        // this.max_sell = this.balance > 0 ? Number((this.balance/this.final_leverage)/this.price).toFixed(4):this.max_coin;
         this.max_sell = this.max_coin;
        },

        async getBalance(coin) {
            // this.coin_amount = "";
            // this.total = "";
            // this.coin_sell = "";
            // this.total_sell = "";
          var res =  await axios
                .post(this.url + "api/coinFutureBalance", {
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
                // console.log("herrer");

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



        //get price and set graph only for npf coin
        npfPrices() {
            axios
                .post(this.url + "api/npfPrices", {
                    coin: this.coin,
                        })
                    .then((res) => {
                    this.data = res.data.prices;
                    this.date = res.data.date;
                    this.price = res.data.price.price;
                    this.limit_price = res.data.price.price;

                    res.data.date.forEach((ress,i)=>{
                    // console.log(ress);

                    // console.log(res.data.prices[i]);
                    var data = {
                        x:ress,
                        y:[res.data.data[i].open,res.data.data[i].high,res.data.data[i].low,res.data.data[i].close]
                    }
                        this.chartData.push(data);

                    });

                    // this.createChart();
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        //get and set asset price of current selected coin
        getPrice() {

            // console.log("coin "+this.coin);

            if (this.coin != 'NPF') {
                axios
                    .post(this.url + "api/getFuturePrice", {
                        coin: this.coin,
                    })
                    .then((res) => {
                        var size = this.coinInfo[0].tickSize;
                        this.price = Number(res.data).toFixed(size);
                        this.limit_price =  Number(res.data).toFixed(size);
                        if(this.coin_amount >0 && this.buy_per == 0){
                            this.calUsdt();
                        }


                        // this.price = res.data.price;
                        // console.log('====================================');
                        // console.log(res.data.price);
                        // console.log('====================================');
                        this.getSymbols();
                    })
                    .catch((err) => {
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
                .post(this.url + "api/getFutureSymbols")
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
            var size = this.coinInfo[0].stepSize;
            this.total = ((Number(this.price) * Number(this.coin_amount))/Number(this.final_leverage)).toFixed(size);
            this.total_sell = this.total;

            // this.total_sell = Number((this.coin_amount*this.price)/this.final_leverage).toFixed(2);

            // console.log('total_balance '+ this.total);
            // console.log('usdt_balan '+ this.usdt);
            if (this.total > Number(this.usd)) {
                this.error = "Insufficient Balance";
            }
        },



        // set value acc to percentage when buy
        buyPer() {
            this.coin_type = "per";
            this.error = false;
               // var size = stepSize[this.coin];
            var size = this.coinInfo[0].stepSize;

            var val = this.buy_per;
            var amount = this.usd * (val / 100);
            this.total = (amount).toFixed(size);

            this.coin_amount = this.fix(((amount / this.price)*this.final_leverage),size);

            // this.total_sell = Number(((this.balance*(val/100))*this.price)*this.final_leverage).toFixed(2);
            // if(this.balance == 0){
                this.total_sell = this.total;
            // }


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


        // trade buy/long
        buyCoin() {
            if (confirm("Are you sure want to Buy!")) {

                var link = this.url + "api/manualFutureBuy";
                var price= this.price;
                if(this.order_type == 'limit'){
                    link = this.url + "api/limitBuyOrder";
                    price = this.limit_price;
                    // this.$toaster.info("coming soon");
                    // return false;
                }

                this.buy_disable = true;

                axios
                    .post(link, {
                        quantity: this.coin_amount,
                        coin: this.coin,
                        leverage:this.final_leverage,
                        token: localStorage.token,
                        coin_type:this.coin_type,
                        price:price,
                        stop_loss:this.stop_loss,
                        take_profit:this.take_profit,
                        margin_mode:this.final_margin
                    })
                    .then((res) => {
                        console.log(res);

                        this.coin_amount = "";
                        this.total = "";
                        this.total_sell = "";
                        this.stop_loss="";
                        this.take_profit="";
                        // console.log(res.data);
                        this.buy_per = 1;
                        this.setBalance();
                        this.positions();
                        this.markPrice(this.coin+"USDT");
                        this.positionHistory();
                        this.updateRangeUi();
                        this.getTotalBalance();
                        this.openOrders();
                        this.orderHistory();
                        console.log(res);

                    //    this.order_type == 'limit'? this.openOrders(): this.orderHistory();
                        var message = res.data.message;
                        this.$toaster.success(message);
                        this.buy_disable = false;
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
                        this.total = "";
                        this.total_sell = "";
                        this.buy_per = 0;
                        this.buy_disable = false;
                        this.updateRangeUi();

                    });
            }
        },


         // sell trade
         sellCoin() {
            if (confirm("Are you sure want to Sell!")) {

                var link = this.url + "api/manualFutureSell";
                var price= this.price;

                if(this.order_type == 'limit'){
                    link = this.url + "api/limitSellOrder";
                    price = this.limit_price;
                    // this.$toaster.info("coming soon");
                    // return false;
                }
                this.sell_disable = true;

                axios
                    .post(link, {
                        quantity: this.coin_amount,
                        coin: this.coin,
                        token: localStorage.token,
                        leverage:this.leverage,
                        coin_type:this.coin_type,
                        price:price,
                        stop_loss:this.stop_loss,
                        take_profit:this.take_profit,
                        margin_mode:this.final_margin
                    })
                    .then((res) => {
                        this.coin_amount = "";
                        this.stop_loss="";
                        this.take_profit="";
                        // console.log(res);
                        this.setBalance();
                        this.getTotalBalance();
                        this.positions();
                        this.markPrice(this.coin+"USDT");
                        this.positionHistory();
                        this.updateRangeUi();
                        this.getTotalBalance();
                        this.openOrders();
                        this.orderHistory();
                        var message = res.data.message;
                        this.$toaster.success(message);

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
                        this.sell_disable = false;
                        this.updateRangeUi();

                    });
            }
        },

        positions(){
            axios
                .get(this.url + "api/openPositions?page=" + this.position_page, {
                    params:{
                         token: localStorage.token
                    }
                })
                .then((res) => {

                    // this.open_positions = res.data.history.data;
                    this.open_positions = res.data.history.data.map(order => ({
                            ...order,
                            order_closing: false  // Default to false
                         }));
                    this.position_page = res.data.history.current_page;
                    this.position_records = res.data.history.total;
                    this.position_per_page = res.data.history.per_page;
                    this.position_last_page = res.data.history.last_page;
                    this.open_position_loading = false;
                })
                .catch((err) => {
                    console.log(err);
                    this.open_position_loading = false;
                });
        },
        positionHistory(){
            axios
                .get(this.url + "api/positionHistory?page=" + this.order_page, {
                    params:{
                         token: localStorage.token
                    }
                })
                .then((res) => {
                    this.position_history = res.data.history.data;
                    this.position_history_page = res.data.history.current_page;
                    this.position_history_records = res.data.history.total;
                    this.position_history_per_page = res.data.history.per_page;
                    this.position_history_last_page = res.data.history.last_page;
                    this.position_loading = false;
                })
                .catch((err) => {
                    console.log(err);
                    this.position_loading = false;
                });
        },


         //close position from open orders
         closePosition(order_id) {
            if (confirm("Are you sure want close position!")) {
                // this.order_closing = true;

                const order = this.open_positions.find(order => order.id === order_id);
                order.order_closing = true;

                axios
                    .post(this.url + "api/closePosition", {
                        id: order_id,
                        token: localStorage.token,
                    })
                    .then((res) => {
                        // console.log(res);
                        this.setBalance();
                        this.positions();
                        this.positionHistory();
                        this.getTotalBalance();
                        // this.order_type == 'limit'? this.openOrders(): this.orderHistory();
                        var message = res.data.message;
                        this.$toaster.success(message);
                        // this.order_closing = false;
                        order.order_closing = false;
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

                        // this.order_closing = false;
                        order.order_closing = false;


                    });
            }
        },

        submitPl(id){
            if(confirm('Are you sure want to update')){
                this.updatepl = true;
                axios
                    .post(this.url+"api/updatePl", {
                        token:localStorage.token,
                        stop_loss:this.stop_loss,
                        take_profit:this.take_profit,
                        id:id
                    })
                    .then((res) => {
                        this.stop_loss = "";
                        this.take_profit = "";
                        this.positions();
                        $(".btn-close").click();
                        var message = res.data.message;
                        this.$toaster.success(message);
                        this.updatepl = false;
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
                        this.updatepl = false;
                    });
            }
        },


        //get and set open orders history
        openOrders() {
            if(this.order_type == 'limit'){
                this.setBalance();
            }
            axios
                .post(this.url + "api/openFutureOrders?page=" + this.order_page, {
                    token: localStorage.token,
                })
                .then((res) => {
                    console.log("res");
                    console.log(res);

                    this.open_orders = res.data.history.data;
                    this.order_page = res.data.history.current_page;
                    this.order_records = res.data.history.total;
                    this.order_per_page = res.data.history.per_page;
                    this.order_last_page = res.data.history.last_page;
                    this.open_order_loading = false;
                })
                .catch((err) => {
                    console.log(err);
                    this.open_order_loading = false;
                });
        },

        //get and set close orders history
        // closeOrders() {
        //     if(this.order_type == 'limit'){
        //         this.setBalance();
        //     }
        //     axios
        //         .post(this.url + "api/closeOrders?page=" + this.close_order_page, {
        //             token: localStorage.token,
        //         })
        //         .then((res) => {
        //             // this.close_orders = res.data.orders.data;
        //             // this.close_page = res.data.orders.current_page;
        //             // this.close_order_records = res.data.orders.total;
        //             // this.closeorder_per_page = res.data.orders.per_page;
        //             // this.close_order_last_page = res.data.orders.last_page;
        //             this.close_order_loading = false;
        //         })
        //         .catch((err) => {
        //             console.log(err);
        //             this.close_order_loading = false;
        //         });
        // },

        //get and set trades orders history
        orderHistory() {
            if(this.order_type == 'limit'){
                this.setBalance();
            }
            axios
                .post(this.url + "api/futureOrderHistory?page=" + this.page, {
                    token: localStorage.token,
                })
                .then((res) => {
                    console.log('====================================');
                    console.log(res.data);
                    console.log('====================================');
                    this.trades = res.data.trades.data;
                    // this.total_pnl = res.data.totalpnl;
                    // this.total_pnl_per = res.data.total_pnl_per;
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
                    .post(this.url + "api/cancelFutureOrder", {
                        token: localStorage.token,
                        id:  id,
                    })
                    .then((res) => {
                        console.log(res);
                        var message = res.data.message;
                        this.$toaster.success(message);
                        this.openOrders();
                        // this.closeOrders();
                        this.setBalance();
                        this.getTotalBalance();
                        // this.usdtBalance();
                        this.getPrice();
                        this.positions();
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
        text: 'NPF Exchange Prices',
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
                        //     borderWidth: 2,
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
        updateRangeUi(){
            this.coin_type = "";
        this.formattedNumber = 10/Math.pow(10,this.coinInfo[0].stepSize);

           this.coin_amount='';this.total='';this.buy_per=0;
            const rangeInputs = document.querySelectorAll('.custom-range-color');
                rangeInputs.forEach(rangeInput => {
                    const percentage = 0;
                    rangeInput.style.background = `linear-gradient(to right, #24d1e5 ${percentage}%, #e5e5e5 ${percentage}%)`;
            });
        }

    },
    mounted(){
         var vm = this;
        // update price every 2 seconds
        // setInterval(()=>{
        //     vm.getPrice();
        // },2000);



         const rangeInputs = document.querySelectorAll('.custom-range-color');

         rangeInputs.forEach(rangeInput => {
            function updateBackground(value) {
                const percentage = (value / rangeInput.max) * 100;
                rangeInput.style.background = `linear-gradient(to right, #24d1e5 ${percentage}%, #e5e5e5 ${percentage}%)`;
        }

        updateBackground(rangeInput.value);

        rangeInput.addEventListener('input', (e) => {
            updateBackground(e.target.value);
        });
    });




    const rangeInput_id = document.getElementById('rangeInput');

// Function to update the background based on the current value
function updateBackgroundId(value) {
    const percentage = (value / rangeInput_id.max) * 100;
    rangeInput_id.style.background = `linear-gradient(to right, #24d1e5 ${percentage}%, #e5e5e5 ${percentage}%)`;
}

// Initialize background on page load
updateBackgroundId(rangeInput_id.value);

// Update background on input change
rangeInput_id.addEventListener('input', (e) => {
    updateBackgroundId(e.target.value);
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
       /*  border-color: #092940 !important; */
    border: 1px solid Var(--primary-color) !important

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
            height:5px;
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

          ::-webkit-scrollbar-track {
             border-radius: 10px;
          }

          ::-webkit-scrollbar-thumb {
            background: #686868;
            border-radius: 10px;
          }

          ::-webkit-scrollbar-thumb:hover {
            background: #686868;
          }
          input[type='checkbox'] {
            accent-color: #ffffff !important;
        }

</style>
