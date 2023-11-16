<?php
  include 'config1.php';
	
	$todayYMD = date("Y-m-d");
	$today = date("d/m/Y");
	$todayQuery = date("d-m-Y");
	$todayTimestamp = strtotime($today);
	$userId = $_SESSION['id'];
?>

<div class="row">
	<div class="col-lg-5">


		  <form class="form-horizontal" action="index.php" method="post" id="studentForm" data-toggle="validator">
        <div class="form-group">
          <label for="rollno" class="control-label">SEARCH FOR STUDENT</label>
      
        <input type="number" class="form-control" id="rollno" maxlength="6" name="rollno" placeholder="Please Enter Student's Roll Number" required>
       
				
				<div class="form-group">
					<input type="submit" name="submit" class="btn " style="border-radius:0%" value="Search">
				</div>
				
				<input type="hidden" name="student" value="y" />
      </form>
		  
	</div>

					</div>
	
		  <div class="col-md-8 col-md-offset-3 col-lg-8">
     
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



