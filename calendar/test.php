<?php 
	// GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
	$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
	// GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
	$month = isset($_GET['month']) ? $_GET['month'] : date('m');

	$date = "$year-$month-01"; // 현재 날짜의 1일
	$time = strtotime($date); // 현재 날짜의 타임스탬프
	$start_week = date('w', $time); // 1. 시작 요일
	$total_day = date('t', $time); // 2. 현재 달의 총 날짜
	$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차 (현재 요일부터 요일수를 구한뒤 7로 나눔 ($start_week = 일 = 0 월 = 1 ... 토 = 6))
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>calendar</title>
    <link rel="stylesheet" href="./test.css">
</head>
<body>
	<div class="calendar">
		<div class="calendar-header"></div>
		<div class="calendar-body">
			<!-- 현재가 1월이라 이전 달이 작년 12월인경우 -->
			<?php if ($month == 1){ ?>
				<!-- 작년 12월 -->
				<a class="calendar-day" href="./test.php?year=<?php echo $year-1 ?>&month=12"><img src="./img/left.png" alt=""></a>
			<?php }else{ ?>
				<!-- 이번 년 이전 월 -->
				<a class="calendar-day" href="./test.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><img src="./img/left.png" alt=""></a>
			<?php }; ?>
			<div class="calendar-year"><?php echo "$year 년 $month 월" ?></div>
			<!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
			<?php if ($month == 12){ ?>
				<!-- 내년 1월 -->
				<a class="calendar-day" href="./test.php?year=<?php echo $year+1 ?>&month=1"><img src="./img/right.png" alt=""></a>
			<?php }else{ ?>
				<!-- 이번 년 다음 월 -->
				<a class="calendar-day" href="./test.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><img src="./img/right.png" alt=""></a>
			<?php }; ?>


			<table>
				<tr>
					<th>일</th> 
					<th>월</th> 
					<th>화</th> 
					<th>수</th> 
					<th>목</th> 
					<th>금</th> 
					<th>토</th>
				</tr> 

				<!-- 총 주차를 반복합니다. -->
				<?php for ($n = 1, $i = 0; $i < $total_week; $i++){ ?> 
					<tr> 
						<!-- 1일부터 7일 (한 주) -->
						<?php for ($k = 0; $k < 7; $k++){ ?> 
							<td> 
								<!-- 시작 요일부터 마지막 날짜까지만 날짜를 보여주도록 -->
								<?php if ( ($n > 1 || $k >= $start_week) && ($total_day >= $n) ){ ?>
									<!-- 현재 날짜를 보여주고 1씩 더해줌 -->
									<a href="" class="day-button"><?php echo $n++ ?></a>
								<?php }?>
							</td> 
						<?php }; ?> 
					</tr> 
				<?php }; ?> 
			</table>
		</div>
	</div>
</body>
</html>