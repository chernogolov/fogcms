<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data2 = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['ГВС', 60],
          ['ХВС', 60],
          ['Отопление', 80]
        ]);

        var options2 = {
          width: 400, height: 120,
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
          minorTicks: 5
        };

        var chart2 = new google.visualization.Gauge(document.getElementById('chart_div2'));

        chart2.draw(data2, options2);

        setInterval(function() {
          data.setValue(0, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 13000);
        setInterval(function() {
          data.setValue(1, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 5000);
        setInterval(function() {
          data.setValue(2, 1, 60 + Math.round(20 * Math.random()));
          chart.draw(data, options);
        }, 26000);
      }
    </script>
<div class="col-6 ">
    <div class="widget">
            <div id="chart_div2" style="width: 400px; height: 120px;"></div>
    </div>
</div>