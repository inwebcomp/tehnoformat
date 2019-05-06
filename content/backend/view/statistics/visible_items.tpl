<div id="visible_items" class="col-md-6">
	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          {%list items%}
			  ['{%.name%}',  {%.count%}],
		  {%end%}
        ]);

        var options = {
          titlePosition: "none",
		  chartArea: { height: "80%", width: "90%" },
		  fontSize: 14,
		  slices: {
			1: { color: '#EEEEEE' },
			0: { color: '#3366CC' },
          },
		  pieHole: 0.5,
		  pieSliceText: "none"
        };

        var chart = new google.visualization.PieChart(document.getElementById('visible_items_chart'));

        chart.draw(data, options);
      }
    </script>
    
    <div class="panel panel-default">
        <div class="page-header">
            <h3 class="row"><div class="col-md-12">{%lang Видимость%}</div></h3>
        </div>
        <div class="chart" id="visible_items_chart"></div>
    </div>
</div>