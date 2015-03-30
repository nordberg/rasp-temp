<html>

  <head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
  </head>

  <body>
  <?php
     $db = new SQLite3('templog.db');
     $results = $db->query('SELECT * FROM temps');
     while ($row = $results->fetchArray()) {
		$data[] = $row['temp'];
     }

	$avgtemp = $db->query('SELECT AVG(temp) AS avg FROM temps GROUP BY DATE(date)');
	while ($row = $avgtemp->fetchArray()) {
		$avgDay[] = $row['avg'];
	}

	$summerRes = $db->query('SELECT AVG(temp) AS avg FROM temps GROUP BY DATE(date)
		LIMIT 7');
	while ($row = $summerRes->fetchArray()) {
		$summerTemps[] = $row['avg'];
	}

	if (sizeof($summerTemps) < 7) {
		$summerYet = "Not enough data.";
	} else {
		$pos = 0;
		foreach ($summerTemps as $val) {
			if ($val > 10) {
				$pos += 1;
			}
		}
		if ($pos >= 7) {
			$summerYet = "Yes, it's summer!";
		} else {
			$summerYet = "Nope, not summer!";
		}
	}
  ?>
<script>
  $(function () {
     $('#container').highcharts({
            chart: {
               zoomType: 'x'
            },
            title: {
               text: 'Temperatur'
            },
            xAxis: {
  //categories: [<?php echo join($dates, ',') ?>]
               type: 'datetime'  
            },
            yAxis: {
               title: {
                  text: 'Temperatur'
               }
            },
  series: [{
  data: [<?php echo join($data, ',') ?>],
  type: 'area',
  pointStart: 0,
  }]
  });
  })
  $(function () {
     $('#container_avg_day').highcharts({
            chart: {
               zoomType: 'x'
            },
            title: {
               text: 'Daglig medeltemperatur'
            },
            xAxis: {
               categories: [<?php echo join($dates, ',') ?>] 
            },
            yAxis: {
               title: {
                  text: 'Temperatur'
               }
            },
  series: [{
  data: [<?php echo join($avgDay, ',') ?>],
  //data: [1,2,3,4],
  type: 'area',
  pointStart: 0,
  }]
  });
  })

	$(document).ready(function () {
		$('#summerYet').text("<?php echo $summerYet ?>");
	});
</script>

	<center><h2>Is it summer yet? </h2><h2 id="summerYet"></h2> </br></center>
    <div id="container" style="width:100%, height:400px;"></div>
	<br>
	<center>
		<div id="container_avg_day" style="width:50%, height:400px;"></div>
	</center>	
</body>

</html>
