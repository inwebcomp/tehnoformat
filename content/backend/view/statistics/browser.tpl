<div id="browser" class="col-md-6">
	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['', ''],
          {%list items%}
			  ['{%.name%}',  {%.count%}],
		  {%end%}
        ]);

        var options = {
          titlePosition: "none",
		  chartArea: { height: "80%", width: "90%" },
		  fontSize: 14,
		  pieHole: 0.5,
		  pieSliceText: "none"
        };

        var chart = new google.visualization.PieChart(document.getElementById('browser_chart'));

        chart.draw(data, options);
      }
    </script>
    
    <div class="panel panel-default">
        <div class="page-header">
            <h3 class="row"><div class="col-md-12">{%lang Браузер посетителей%}</div></h3>
        </div>
        <div class="chart" id="browser_chart"></div>
    </div>
</div>