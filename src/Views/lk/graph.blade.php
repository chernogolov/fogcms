<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Месяц', 'ГВС', 'ХВС', 'ЭЭД', 'ЭЭН'],
          ['Январь',  13, 15, 250, 550],
          ['Февраль',  30, 40, 130, 112],
          ['Март',  50, 44, 334, 421],
          ['Апрель',  11, 21, 233, 222]
        ]);

        var options = {
          title: 'Потребление коммунальных ресурсов',
          hAxis: {title: 'Месяц',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
<div class="col-12">
    <div class="widget">
        <div id="chart_div" style="width: 90%; height: 200px;"></div>
    </div>
</div>