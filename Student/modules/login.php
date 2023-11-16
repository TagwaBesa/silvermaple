
<!-- 
<header>

    <h1>Student Attendance Management System</h1>
  
</header> -->

<div class="container">
  <div class="row">
    <div class="col-lg-6">
      <h3 class="text-center">Student Login</h3>
		<?php if(isset($_GET['invalid'])) : ?>
				<div class="form-group alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>Sorry!</strong> Invalid Username Or Password.
				</div>
			
		<?php endif; ?>
	
      <form class="form-horizontal" id="loginForm" action="modules/verify.php" method="post" data-toggle="validator">
				<div class="form-group">
          <label for="inputEmail3" class="control-label">Username</label>
          <input type="text" class="form-control" id="inputEmail3" name="name" maxlength="16" placeholder="Enter Username" required>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="control-label">Password</label>
          <input type="password" class="form-control" id="inputPassword3"  name="password" maxlength="16" placeholder="Enter Password" required>
        </div>
        <div class="form-group">
          <input type="submit" name="submit" class="btn btn-success btn-block" style="border-radius:0%" value="Login">
        </div>
      </form>
   
    </div>


  <hr  />

</div>


