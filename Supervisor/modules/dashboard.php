<?php
  include 'config1.php';
	
	$todayYMD = date("Y-m-d");
	$today = date("d/m/Y");
	$todayQuery = date("d-m-Y");
	$todayTimestamp = strtotime($today);
	$userId = $_SESSION['uid'];
?>

<div class="row">
	<div class="col-lg-5">
		<div class="panel panel-danger">
			<div class="panel-heading">
			  <h3 class="panel-title text-center"><b>Past</b></h3>
			</div>
			<div class="panel-body text-center">
				  <?php
					  
					  for($i = 1; $i < 8; $i++) {
						  $dateCurrentYMD = date('Y-m-d', strtotime($todayYMD ." -$i day"));
					  
						  $queryTimeStamp = strtotime($dateCurrentYMD);
						  $dateCurrent = date('d/m/Y', $queryTimeStamp);
										  
						  $query_subjectPending = "SELECT subject.name, subject.id from subject INNER JOIN user_subject WHERE user_subject.id = subject.id AND user_subject.uid = {$_SESSION['uid']}  ORDER BY subject.name";
						  $subPending=$conn->query($query_subjectPending);
						  $rsubPending=$subPending->fetchAll(PDO::FETCH_ASSOC);
						  $today = date("d/m/Y");
						  $todayQuery = date("d-m-Y");
						  $todayDBQuery = strtotime(date("Y-m-d"));
						  $noOfSubjectPending = count($rsubPending);
						  
						  $weekday= strtolower(date("l", strtotime($dateCurrentYMD)));
						  
						  if(($weekday!="saturday") && ($weekday!="sunday")) {
							  for($j = 0; $j<$noOfSubjectPending; $j++) {
								  $subIdP = $rsubPending[$j]['id'];
								  $sqlPending = "SELECT sid, ispresent FROM attendance WHERE id=$subIdP AND date=$queryTimeStamp AND uid=$userId";
								  $stmtP = $conn->prepare($sqlPending); 
								  $stmtP->execute();
								  $resultP = $stmtP->fetchAll(PDO::FETCH_ASSOC); 
								  if(!empty($resultP)){
									print "
									<div class='attendance-box'>
									<p><a href='index.php?subject=" . $subIdP . "&date=" . $dateCurrentYMD ."' style='text-decoration:none;'>Class: <strong style='color:#555;'>" . $rsubPending[$j]['name'] ."</strong> of <strong>" . $dateCurrent ."</strong></a></p>
									<span class='label label-success'>Attendance Recorded</span>
									</div>";
								} else {
									print "
									<div class='attendance-box'>
									<p><a href='index.php?subject=" . $subIdP . "&date=" . $dateCurrentYMD ."' style='text-decoration:none;'>Class: <strong style='color:#555;'>" . $rsubPending[$j]['name'] ."</strong> of <strong>" . $dateCurrent ."</strong></a></p>
									<span class='label label-danger'>Mark Attendance Now!</span>
									</div>";
								}
							
							  }
							  
							  if ($i !== 7) {
								  print "<hr>";
							  }
						  }
					  }
						  
				  ?>
				  
			</div>
		  </div>
	</div>

	<div class="col-md-5">
		<div class="panel panel-primary">
			<div class="panel-heading">
			  <h3 class="panel-title text-center"><b>Today's Attendance</b></h3>
			</div>
			<div class="panel-body text-center">
				  <?php
					  
				  
					  $query_subject = "SELECT subject.name, subject.id from subject INNER JOIN user_subject WHERE user_subject.id = subject.id AND user_subject.uid = {$_SESSION['uid']}  ORDER BY subject.name";
					  $sub=$conn->query($query_subject);
					  $rsub=$sub->fetchAll(PDO::FETCH_ASSOC);
					  $today = date("d/m/Y");
					  $todayQuery = date("d-m-Y");
					  $todayDBQuery = strtotime(date("Y-m-d"));
					  $noOfSubject = count($rsub);
					  
					  for($i = 0; $i<$noOfSubject; $i++) {
						  $subId = $rsub[$i]['id'];
						  $sql = "SELECT sid, ispresent FROM attendance WHERE id=$subId AND date=$todayDBQuery AND uid=$userId";
						  $stmt = $conn->prepare($sql); 
						  $stmt->execute();
						  $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
						  if(!empty($result)){
							  print "<p><a href='index.php?subject=" . $subId . "&date=" . $todayQuery ."' style='text-decoration:none;'>Class: <strong style='color:#555;'>" . $rsub[$i]['name'] ."</strong> of <strong>Today's</strong> (" . $today .")</a> <span class='label label-success' style='border-radius:0%;'>Attendance Recorded</span> </p>";
						  }
						  else {
							  print "<p><a href='index.php?subject=" . $subId . "&date=" . $todayQuery ."' style='text-decoration:none;'>Class: <strong style='color:#555;'>" . $rsub[$i]['name'] ."</strong> of <strong>Today's</strong> (" . $today .")</a> <span class='label label-danger' style='border-radius:0%;'>Mark Attendance Now!</span></p>";
						  }
					  }
				  ?>
			</div>
			
		  </div>
		  <form class="form-horizontal" action="index.php" method="post" id="studentForm" data-toggle="validator">
        <div class="form-group">
          <label for="rollno" class="control-label">SEARCH FOR STUDENT</label>
      
        <input type="number" class="form-control" id="rollno" maxlength="6" name="rollno" placeholder="Please Enter Student's Roll Number" required>
       
				
				<div class="form-group">
					<input type="submit" name="submit" class="btn" style="border-radius:0%" value="Search">
				</div>
				
				<input type="hidden" name="student" value="y" />
      </form>
		  
	</div>

					</div>
	
	</div>
	
</div>


<div class="row">
	<div class="col-lg-2"></div>

	<div class="col-lg-7">
		
	</div>

	<div class="col-lg-3">
		
	</div>
</div>



