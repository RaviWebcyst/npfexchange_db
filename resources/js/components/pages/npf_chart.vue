<template>
    <div id="chart">
      <apexchart
        type="area"
        height="350"
        :options="chartOptions"
        :series="series"
      ></apexchart>
    </div>
  </template>

  <script>
  import VueApexCharts from "vue-apexcharts";

  export default {
    name: "npf_chart",
    components: {
      apexchart: VueApexCharts,
    },
    data() {
      return {
        series: [
          {
            name: "",
            data: this.npfCoinPrice(),
          },
        ],
        chartOptions: {
          chart: {
            type: "area",
            stacked: false,
            height: 350,
            zoom: {
              type: "x",
              enabled: true,
              autoScaleYaxis: true,
            },
            toolbar: {
              autoSelected: "zoom",
            },
          },
          theme: {
            mode: "dark",
          },
          grid: {
            show: false,
          },
          dataLabels: {
            enabled: false,
          },
          markers: {
            size: 0,
          },
          title: {
            text: "NPF Exchange",
            align: "left",
            style: {
              color: "#ffffff",
            },
          },
          fill: {
            type: "gradient",
            gradient: {
              shadeIntensity: 1,
              inverseColors: false,
              opacityFrom: 0.5,
              opacityTo: 0,
            },
          },
          yaxis: {
            labels: {
              style: {
                colors: "#ffffff",
              },
              formatter: function (val) {
                return val.toFixed(3); // Format as a decimal with 3 places
              },
            },
          },
          xaxis: {
            type: "datetime",
            labels: {
              style: {
                colors: "#ffffff",
              },
            },
            crosshairs: {
              show: false,
            },
          },
          tooltip: {
            shared: false,
            y: {
              formatter: function (val) {
                return val.toFixed(3); // Show the exact price in tooltip
              },
            },
          },
        },
      };
    },
    methods: {
      npfCoinPrice() {
        // Generate and return price data as needed
        const dates = [];
            let ts2 = new Date().setHours(0, 0, 0, 0);

            for (let i = 0; i < 6; i++) {
                // console.log("data is ====    " + res.data.prices);
                // const coinPrice = res.data.prices;
                const coinPrice = Math.floor(Math.random(2) * 10 + 2);
                dates.push([ts2, coinPrice]);
                ts2 -= 86400000; // Decrease by one day in milliseconds
            }
            return dates;
      },
    },
  };
  </script>

  <style scoped>
  #chart {
    max-width: 1080px;
    margin: 35px auto;
  }
  </style>
