<div id="orders" class="col-md-12">
	<script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart(){
            var data = google.visualization.arrayToDataTable([
                ['{%lang Дата%}', '{%lang Количество заказов%}'],
                {%list items%}
                    ['{%.date%}',  {%.count%}],
                {%end%}
            ]);
        
            var options = {
                legend: { position: "none" },
                hAxis: {title: '{%lang Дата%}', titleTextStyle: {color: '#333'}, slantedText: false, ticks: [5,10,15,20], textStyle: { fontSize: 14 }},
                vAxis: {title: '{%lang Количество заказов%}', minValue: 0, gridlines: { color: "#EFEFEF", count: -1 }},
                axisTitlesPosition: "none",
                height: 300,
                titlePosition: "none",
                backgroundColor: { stroke: "#FF0000" },
                chartArea: { height: "80%", width: "90%" },
            };
        
            var chart = new google.visualization.AreaChart(document.getElementById('orders_chart'));
            chart.draw(data, options);
        }
    </script>
    
    <div class="panel panel-default">
        <div class="page-header">
            <h3 class="row"><div class="col-md-6">{%lang Заказы%}</div><div class="col-md-6 action"><a num="21" interval="1"{%if interval == 1%} class="selected"{%endif%}>{%lang День%}</a><a num="8" interval="7"{%if interval == 7%} class="selected"{%endif%}>{%lang Неделя%}</a><a num="6" interval="31"{%if interval == 31%} class="selected"{%endif%}>{%lang Месяц%}</a></div></h3>
        </div>
        <div class="chart" id="orders_chart"></div>
    </div>
    
    <script type="text/javascript">
        $("#orders .page-header a:not(.selected)").bind("click", function(e){
            e.preventDefault();
            $("#orders").Request({ controller: "statistics", action: "orders", data: "num="+$(this).attr("num")+"&interval="+$(this).attr("interval"), complete: function(){ drawChart(); }, loader: "global" });
        });
    </script>
</div>