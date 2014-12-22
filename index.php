<?php
require_once("includes/config.php");
require_once("includes/curl.php");
$response = curl_seasson($url);
$response = substr($response,19,-1);
$json = json_decode($response,1);
if(!is_curl_installed()) {
	echo "cURL is not installed on server !.";
	exit(0);
}
?>
<html>
<head>
	<meta charset="utf-8">
	<title>TMs Server Monitor</title>
	<link href="//cdnjscn.b0.upaiyun.com/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<style type="text/css">
	.monitor-box{
		width:50%;
		float:left;
		padding:10px;
		border:1px solid green;
	}
	.monitor-100{
		width:100%;
		float:left;
		padding:10px;
		border:1px solid green;
	}
	.grey-text, .Status .legends, .Status .how-it-works .row-fluid {
		color: #666
	}
	.service-status .row-fluid {
		border-top: 1px solid #eee;
		border-bottom: 1px solid #eee;
		padding: 10px 0
	}
	.service-status p {
		margin: 0
	}
	.service-status .text {
		margin-left: 0
	}
	.service-status .text p.strong {
		font-size: 1.2em
	}
	.Status .service-status .row-fluid {
		border-top: none
	}
	.Status .service-status .text p.strong {
		font-size: 2em;
		margin-bottom: 10px;
		font-weight: bold
	}
	.Status .server-name span {
		font-size: 14px;
		font-weight: normal
	}

	.Status .server-name span:before {
		content: " / "
	}
	.Status ul.status_history {
		width: 100%;
		margin: 0;
		padding: 0;
		overflow: hidden;
		list-style: none
	}
	.Status ul.status_history li {
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
		float: left;
		height: 20px;
		border-right: 1px solid #fff;
		width: 1%;
		min-width: 1px;
		opacity: 0.7
	}
	.Status ul.status_history li.ok {
		background: #7dde66
	}
	.Status ul.status_history li.error {
		background: #FA6D46
	}
	.Status ul.status_history li:hover {
		opacity: 1
	}
	.Status .legends span {
		display: inline-block;
		width: 0.8em;
		height: 0.8em;
		opacity: 0.7
	}
	.Status .legends span.ok {
		background: #7dde66
	}
	.Status .legends span.error {
		background: #FA6D46
	}
	@media (max-width: 767px){.Status .service-status .text p.strong {
		font-size: 1.7em
	}
	.Status .server-name span {
		font-size: 13px
	}
}
</style>
<body>
	<div class="container-fluid dashboard-content Status">
		<div class="row">
			<div class="col-md-2">
			</div>
			<div class="col-md-8">
				<div class="weight monitor-100">
					<?php
					for($i = 0;$i < $json['total'];$i++) {
						echo '<h3 class="server-name">'.$json['monitors']['monitor'][$i]['friendlyname'].'</h3>';
						echo '<ul class="status_history">';
						for($j = 0;$j < 100;$j++)
						{
							if($json['monitors']['monitor'][$i]['responsetime'][$j]['value'] < 300)
								echo '<li class="ok" data-toggle="tooltip" id="jp'.$j.'" title="" data-original-title="'.$json['monitors']['monitor'][$i]['responsetime'][$j]['datetime'].'"></li>';
							else 
								echo '<li class="error" data-toggle="tooltip" id="jp'.$j.'" title="" data-original-title="'.$json['monitors']['monitor'][$i]['responsetime'][$j]['datetime'].'"></li>';
						}
						echo '</ul>';
					}
					?>
					<div class="widget row">
						<div class="legends">
							<div class="col-md-3">
								<span class="ok" data-original-title="" title=""></span>
								状态良好
							</div>
							<div class="col-md-3">
								<span class="error" data-original-title="" title=""></span>
								可能有故障
							</div>
						</div>
					</div>
				</div>
				<?php
					if($json['stat'] == 'fail') {
						echo $json['message'];
					} else {
						for($i = 0;$i < $json['total'];$i++) {
							echo "<div class='monitor-box'><b>监控内容: </b>" . $json['monitors']['monitor'][$i]['friendlyname'] . "<br />";
							echo "<b>URL / IP: </b>" . $json['monitors']['monitor'][$i]['url'] . "<br />";

							echo "<b>状态: </b>";

							if ($json['monitors']['monitor'][$i]['status'] == 0)
								echo '<b><font color="brown">监控暂停</font></b>';
							
							else if($json['monitors']['monitor'][$i]['status'] == 2)
								echo '<b><font color="green">在线</font></b>';
							
							else if ($json['monitors']['monitor'][$i]['status'] == 8)
								echo '<b><font color="red">超时</font></b>';
							
							else if ($json['monitors']['monitor'][$i]['status'] == 9)
								echo '<b><font color="red">宕机</font></b>';

							$customuptime = $json['monitors']['monitor'][$i]['customuptimeratio'];
							list($day, $week, $month) = explode('-', $customuptime);

							echo "<br /><b> 当日可用性: ".$day."%</b><br />";?>
							<div class="progress progress-striped active">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $day;?>%">
								</div>
							</div>
							<?php echo "<br /><b>本周可用性:  ".$week."%</b><br />";?>
							<div class="progress progress-striped active">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $week;?>%">
								</div>
							</div>
							<?php echo "<br /><b>本月可用性: ".$month."%</b><br />";?>
							<div class="progress progress-striped active">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $month;?>%">
								</div>
							</div>
							<?php echo "<br /><b>总可用性: ".$json['monitors']['monitor'][$i]['alltimeuptimeratio']."%</b><br />";?>
							<div class="progress progress-striped active">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $json['monitors']['monitor'][$i]['alltimeuptimeratio'];?>%">
								</div>
							</div>
							<?php
							echo "<br /><b>监控类型: </b>";

							if($json['monitors']['monitor'][$i]['type'] == 1)
								echo 'Https';
							else if($json['monitors']['monitor'][$i]['type'] == 2)
								echo 'Keyword';
							else if($json['monitors']['monitor'][$i]['type'] == 3)
								echo 'Ping';
							else if($json['monitors']['monitor'][$i]['type'] == 4)
							{
								echo '端口 - ';

								if($json['monitors']['monitor'][$i]['subtype'] == 1)
									echo 'Http (80)';
								else if($json['monitors']['monitor'][$i]['subtype'] == 2)
									echo 'Https (443)';
								else if($json['monitors']['monitor'][$i]['subtype'] == 3)
									echo 'FTP (21)';
								else if($json['monitors']['monitor'][$i]['subtype'] == 4)
									echo 'SMTP (25)';
								else if($json['monitors']['monitor'][$i]['subtype'] == 5)
									echo 'POP3 (110)';
								else if($json['monitors']['monitor'][$i]['subtype'] == 6)
									echo 'IMAP (143)';
								else if($json['monitors']['monitor'][$i]['subtype'] == 99)
									echo '自定义端口 ( ' . $json['monitors']['monitor'][$i]['port'] . ' )';
							}
							echo "<br />";
							/*for($j = 0;$j < count($json['monitors']['monitor'][$i]['log']);$j++){
								if($json['monitors']['monitor'][$i]['log'][$j]['type'] == 1)
									echo '<font color="red">Server Down </font>';
								else if($json['monitors']['monitor'][$i]['log'][$j]['type'] == 2)
									echo '<font color="green">Server Up </font>';
								else if($json['monitors']['monitor'][$i]['log'][$j]['type'] == 98)
									echo '<font color="blue">Monitor Started </font>';
								else if($json['monitors']['monitor'][$i]['log'][$j]['type'] == 99)
									echo '<font color="brown">Monitor Paused </font>';
								
								echo "<br /><b>Date & Time: </b>" . $json['monitors']['monitor'][$i]['log'][$j]['datetime'] . "<br />";
							}
							*/
							echo "</div>";
						}
						echo "<div style='clear:both'></div>";
						echo "<div id='update_data'></div>";
					}
				?>
			</div>
		</div>
	</div>
<script type="text/javascript">
	setInterval(function () {
		var d = new Date();
	var seconds = d.getMinutes() * 60 + d.getSeconds();
	var fiveMin = 60 * 5;
	var timeleft = fiveMin - seconds % fiveMin;
	var result ='<b>下次更新时间: </b>' + parseInt(timeleft / 60) + ':' + timeleft % 60+' 后';
	document.getElementById('update_data').innerHTML = result;
	}, 500)
</script>
<script src="//cdnjscn.b0.upaiyun.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdnjscn.b0.upaiyun.com/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//cdnjscn.b0.upaiyun.com/libs/bootstrap-hover-dropdown/2.0.2/bootstrap-hover-dropdown.min.js"></script>
<script type="text/javascript">
	$(function(){
		$(".ok , .error").tooltip();
	});
</script>
</body>
</html>