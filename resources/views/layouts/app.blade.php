<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('logo.png')}}" type="image/x-icon">

    <title>NPF Exchange</title>
    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('user/assets/css/theme-style.css')}}" />
    <link rel="stylesheet" href="{{ asset('user/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('user/assets/css/responsive.css')}}">
    <!-- bootstrap cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
      crossorigin="anonymous">
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet">

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <style>

    .tabcontent {
      display: none !important;
    }
    .cTypeTabActive {
       background-color: rgba(216,216,216, 0.8) !important;
    }
    .tabperiods {
        font-family: 'Raleway', sans-serif;
        border-radius: 7px;
        padding: 2px 1px;
      background-color: rgba(0,0,0, 0.03);
    }
    .tabperiods:hover {
       background-color: rgb(231, 240, 249);
    }
    .tabperiods_active{
      background-color: rgba(10,115,167, 0.1);
    }

    .tablinks {
      border-radius: 7px;
        border-width: 1px;
        padding: 5px 10px;
        border-style: solid;
        background-color: #ffffff;
        border-color: rgb(255, 255, 255)!important;
    }
    .tablinks:hover {
        background-color: #f2f2f2;
        border-color: rgba(127,211,179, 0.8);
    }
    .tablinks.active {
        border-color: rgba(255, 255, 255, 0.01) !important;
        background-color: #e6e6e6 !important;
    }
    .amcharts-graph-line
         {
          fill: rgba(255, 255, 255, 0.9);
    }
    .cccCustomRadioContainer {
      display: none;
    }
    .tabcontent {
        display:none;
    }

    .ccc-widget, .ccc-chart-v3 {
      border: 0px solid; !important
    }
    tspan, textPath {
        font-size: 0.8rem;
    }
    rect.amcharts-cursor-div {
      background-color: #000000;
    }

    .chartTypeTabLinks:hover {
       background-color: #e5e5e5 !important;
       color: rgba(0, 0, 0, 0.6) !important;
    }

    .chartTypeTabLinks {
        padding-top: 1px;
      padding-bottom: 1px;
    }
    .amcharts-graph-fill {
        fill: rgb(139, 146, 167); !important;
        fill-opacity: 0.2;
    }
    .amcharts-graph-stroke {
        fill-opacity: 0.8;
        stroke-width: 2;
        stroke-opacity: 0.8;
        stroke: rgb(125, 149, 182);
    }
    .amcharts-category-axis {
      fill: rgba(0,0,0, 0.8); !important;
    }

    .amcharts-cursor-fill {
     fill: rgba(10,115,167, 0.8);
      stroke:  rgb(4, 0, 0) !important;
      display: none !important;
    }

    .amcharts-plot-area {
          fill: rgb(25, 225, 225) !important;
    }


    .amcharts-legend-marker {
      fill: rgba(10,115,167, 0.3) !important;
      stroke: rgba(10,115,167, 0.6) !important;
    }

    .amcharts-balloon-div {
      fill:  rgba(10,115,167, 0.9) !important;
      position:absolute !important;
      border:1px !important;
    }

    #loaderccc {
       display: none !important;
    }
    .amount-box textarea {
    width: 100%;
    padding: 10px;
    background-color: rgb(247, 247, 247, 1);
    border-radius: 5px;
    height: 60px;
    border: 0px;
}
.mt-cstm{
  margin-top:7rem;
}
.left-sidebar{
  overflow-y: scroll;
}

        .alert_msg {
            top: 10px !important;
            right: 10px ! important;
            position: absolute;
            z-index: 11111;
            position: fixed;
        }

  </style>

</head>
<body>
    <div id="main-wrapper">
        @include('user.sidebar')
        <div class="page-wrapper">
          @include('user.header')
          {{-- @if (session('success'))
          <div class="alert alert-success  alert-dismissible fade show alert_msg">
              {{ session('success') }}
              <a class=" text-decoration-none text-dark text-end ms-3" data-bs-dismiss="alert">X</a>
          </div>
      @endif
      @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show alert_msg">
              {{ session('error') }}
              <a class=" text-decoration-none text-dark text-end ms-3" data-bs-dismiss="alert">X</a>
          </div>
      @endif --}}
          @yield('content')
        </div>

        {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}




    </div>

    <script src="{{asset('user/assets/js/simplebar.js')}}"></script>
      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
      <script src="{{asset('user/assets/js/app.init.js')}}"></script>
      <script src="{{asset('user/assets/js/app.min.js')}}"></script>
      <script src="{{asset('user/assets/js/sidebarmenu.js')}}"></script>

      <!-- solar icons -->
      <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>


      <script>
        var options = {
          chart: {
            type: 'line',
            height: 350,
            animations: {
              enabled: true,
              easing: 'linear',
              dynamicAnimation: {
                speed: 1000
              }
            },
            toolbar: {
              show: false // Hide toolbar icons
            },
            zoom: {
              enabled: false // Disable zooming
            }
          },
          series: [
            {
              name: 'Profit',
              data: [15000, 20000, 18000, 25000, 24000, 30000, 35000, 45000, 60000, 65000],
              color: 'rgba(170, 231, 148, 1)', // Profit line color
              stroke: {
                width: 1 // Adjusted thickness for Profit line
              }
            },
            {
              name: 'Total Amount',
              data: [30000, 40000, 35000, 50000, 49000, 60000, 70000, 91000, 125000, 130000],
              color: 'rgba(37, 57, 146, 1)', // Total Amount line color
              stroke: {
                width: 1 // Adjusted thickness for Profit line
              }
            }
          ],
          xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            axisBorder: {
              show: true,
              color: 'rgba(235, 235, 235, 1)' // X-axis border color
            },
            labels: {
              style: {
                colors: 'rgba(163, 163, 163, 1)',
                fontFamily: 'Work Sans, sans-serif'
              }
            }
          },
          yaxis: {
            min: 0,
            max: 100000, // Adjusted maximum value for better visibility
            tickAmount: 5,
            labels: {
              formatter: function (value) {
                return "$ " + (value / 1000) + "k";
              },
              style: {
                colors: 'rgba(163, 163, 163, 1)',
                fontFamily: 'Work Sans, sans-serif'
              }
            },
            axisBorder: {
              show: true,
              color: 'rgba(235, 235, 235, 1)' // Y-axis border color
            }
          },
          tooltip: {
            shared: true,
            intersect: false,
            y: {
              formatter: function (value) {
                return "$" + (value / 1000) + "k";
              }
            }
          },
          stroke: {
            curve: 'smooth' // Make lines curved
          },
          grid: {
            borderColor: 'rgba(235, 235, 235, 1)', // Grid border color
            strokeDashArray: 10 // Dotted grid lines
          },
          legend: {
            show: false // Hide legend
          }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();



        var monthlyChartOptions = {
          chart: {
            type: 'line',
            height: 350,
            animations: {
              enabled: true,
              easing: 'linear',
              dynamicAnimation: {
                speed: 1000
              }
            },
            toolbar: {
              show: false // Hide toolbar icons
            },
            zoom: {
              enabled: false // Disable zooming
            }
          },
          series: [
            {
              name: 'Profit',
              data: [15000, 20000, 18000, 25000, 24000, 30000], // Data for 6 months
              color: 'rgba(170, 231, 148, 1)', // Profit line color
              stroke: {
                width: 2 // Adjusted thickness for Profit line
              }
            },
            {
              name: 'Total Amount',
              data: [30000, 40000, 35000, 50000, 49000, 60000], // Data for 6 months
              color: 'rgba(37, 57, 146, 1)', // Total Amount line color
              stroke: {
                width: 2 // Adjusted thickness for Total Amount line
              }
            }
          ],
          xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], // Adjusted for 6 months
            axisBorder: {
              show: true,
              color: 'rgba(235, 235, 235, 1)' // X-axis border color
            },
            labels: {
              style: {
                colors: 'rgba(163, 163, 163, 1)',
                fontFamily: 'Work Sans, sans-serif'
              }
            }
          },
          yaxis: {
            min: 0,
            max: 100000, // Adjusted maximum value for better visibility
            tickAmount: 5,
            labels: {
              formatter: function (value) {
                return "$" + (value / 1000) + "k";
              },
              style: {
                colors: 'rgba(163, 163, 163, 1)',
                fontFamily: 'Work Sans, sans-serif'
              }
            },
            axisBorder: {
              show: true,
              color: 'rgba(235, 235, 235, 1)' // Y-axis border color
            }
          },
          tooltip: {
            shared: true,
            intersect: false,
            y: {
              formatter: function (value) {
                return "$" + (value / 1000) + "k";
              }
            }
          },
          stroke: {
            curve: 'smooth' // Make lines curved
          },
          grid: {
            borderColor: 'rgba(235, 235, 235, 1)', // Grid border color
            strokeDashArray: 10 // Dotted grid lines
          },
          legend: {
            show: false // Hide legend
          }
        };

        var monthlyChart = new ApexCharts(document.querySelector("#monthly-chart"), monthlyChartOptions);
        monthlyChart.render();

        var januaryChartOptions = {
          chart: {
            type: 'line',
            height: 350,
            animations: {
              enabled: true,
              easing: 'linear',
              dynamicAnimation: {
                speed: 1000
              }
            },
            toolbar: {
              show: false // Hide toolbar icons
            },
            zoom: {
              enabled: false // Disable zooming
            }
          },
          series: [
            {
              name: 'Profit',
              data: [15000], // Data for January
              color: 'rgba(170, 231, 148, 1)', // Profit line color
              stroke: {
                width: 2 // Adjusted thickness for Profit line
              }
            },
            {
              name: 'Total Amount',
              data: [30000], // Data for January
              color: 'rgba(37, 57, 146, 1)', // Total Amount line color
              stroke: {
                width: 2 // Adjusted thickness for Total Amount line
              }
            }
          ],
          xaxis: {
            categories: ['Jan'], // Only January
            axisBorder: {
              show: true,
              color: 'rgba(235, 235, 235, 1)' // X-axis border color
            },
            labels: {
              style: {
                colors: 'rgba(163, 163, 163, 1)',
                fontFamily: 'Work Sans, sans-serif'
              }
            }
          },
          yaxis: {
            min: 0,
            max: 40000, // Adjusted maximum value for better visibility
            tickAmount: 5,
            labels: {
              formatter: function (value) {
                return "$" + (value / 1000) + "k";
              },
              style: {
                colors: 'rgba(163, 163, 163, 1)',
                fontFamily: 'Work Sans, sans-serif'
              }
            },
            axisBorder: {
              show: true,
              color: 'rgba(235, 235, 235, 1)' // Y-axis border color
            }
          },
          tooltip: {
            shared: true,
            intersect: false,
            y: {
              formatter: function (value) {
                return "$" + (value / 1000) + "k";
              }
            }
          },
          stroke: {
            curve: 'smooth' // Make lines curved
          },
          grid: {
            borderColor: 'rgba(235, 235, 235, 1)', // Grid border color
            strokeDashArray: 10 // Dotted grid lines
          },
          legend: {
            show: false // Hide legend
          }
        };

        var januaryChart = new ApexCharts(document.querySelector("#january-chart"), januaryChartOptions);
        januaryChart.render();



        var weeklyChartOptions = {
          chart: {
            type: 'line',
            height: 350,
            animations: {
              enabled: true,
              easing: 'linear',
              dynamicAnimation: {
                speed: 1000
              }
            },
            toolbar: {
              show: false // Hide toolbar icons
            },
            zoom: {
              enabled: false // Disable zooming
            }
          },
          series: [
            {
              name: 'Profit',
              data: [5000, 6000, 5500, 7000, 6500], // Sample data for 5 weeks
              color: 'rgba(170, 231, 148, 1)', // Profit line color
              stroke: {
                width: 2 // Adjusted thickness for Profit line
              }
            },
            {
              name: 'Total Amount',
              data: [8000, 9000, 8500, 10000, 9500], // Sample data for 5 weeks
              color: 'rgba(37, 57, 146, 1)', // Total Amount line color
              stroke: {
                width: 2 // Adjusted thickness for Total Amount line
              }
            }
          ],
          xaxis: {
            categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'], // Adjusted for 5 weeks
            axisBorder: {
              show: true,
              color: 'rgba(235, 235, 235, 1)' // X-axis border color
            },
            labels: {
              style: {
                colors: 'rgba(163, 163, 163, 1)',
                fontFamily: 'Work Sans, sans-serif'
              }
            }
          },
          yaxis: {
            min: 0,
            max: 12000, // Adjusted maximum value for better visibility
            tickAmount: 5,
            labels: {
              formatter: function (value) {
                return "$" + (value / 1000) + "k";
              },
              style: {
                colors: 'rgba(163, 163, 163, 1)',
                fontFamily: 'Work Sans, sans-serif'
              }
            },
            axisBorder: {
              show: true,
              color: 'rgba(235, 235, 235, 1)' // Y-axis border color
            }
          },
          tooltip: {
            shared: true,
            intersect: false,
            y: {
              formatter: function (value) {
                return "$" + (value / 1000) + "k";
              }
            }
          },
          stroke: {
            curve: 'smooth' // Make lines curved
          },
          grid: {
            borderColor: 'rgba(235, 235, 235, 1)', // Grid border color
            strokeDashArray: 10 // Dotted grid lines
          },
          legend: {
            show: false // Hide legend
          }
        };

        var weeklyChart = new ApexCharts(document.querySelector("#weekly-chart"), weeklyChartOptions);
        weeklyChart.render();
      </script>

</body>
</html>
