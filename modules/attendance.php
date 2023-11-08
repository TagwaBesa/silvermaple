<?php 
require 'sendmail.php';
	include 'config1.php';
	$updateFlag = 0;
?>

<div class="container">
  <div class="row ">
    <div class="col-md-12 col-lg-12">
			<h1 class="page-header">Take Attendance</h1>  
		</div>
	</div>
	<div class="row text-center">
		<div class="col-md-12 col-lg-12">
			<form action="index.php" method="get" class="form-inline" id="subjectForm" data-toggle="validator">
				<div class="form-group">
					<label for="select" class="control-label">Subject:</label>
					<?php
											
						$query_subject = "SELECT subject.name, subject.id from subject 
						INNER JOIN user_subject WHERE user_subject.id = subject.id AND user_subject.uid = {$_SESSION['uid']}  ORDER BY subject.name";
						$sub=$conn->query($query_subject);
						$rsub=$sub->fetchAll(PDO::FETCH_ASSOC);
						echo "<select name='subject' class='form-control' required='required'>";
						for($i = 0; $i<count($rsub); $i++)
						{
							if ($_GET['subject'] == $rsub[$i]['id']) {
								echo"<option value='". $rsub[$i]['id']."' selected='selected'>".$rsub[$i]['name']."</option>";
							}
							else {
								echo"<option value='". $rsub[$i]['id']."'>".$rsub[$i]['name']."</option>";
							}
						}
						echo"</select>";
					?>									
				</div>

				<div class="form-group" data-provide="datepicker">
					<label for="select" class="control-label">Date:</label>
					<input type="date" class="form-control" name="date" value="<?php print isset($_GET['date']) ? $_GET['date'] : ''; ?>" required>
				</div>

				<button type="submit" class="btn btn-danger" style='border-radius:0%;' name="sbt_stn"><i class="glyphicon glyphicon-filter"></i> Load</button>
			</form>
				


			<?php
				if(isset($_GET['date']) && isset($_GET['subject'])) :
			?>
			
			<?php 
				$todayTime = time();
				$submittedDate = strtotime($_GET['date']);
				if ($submittedDate <= $todayTime) :
			?>
			<form action="index.php" method="post">
			
			<div class="margin-top-bottom-medium">
				<button type="submit" class="btn btn-success btn-block" style='border-radius:0%;' name="sbt_top"><i class="glyphicon glyphicon-ok-sign"></i> Save Attendance</button>
			</div>
			
			<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th class="text-center">Roll</th>
            <th class="text-center">Student's Name</th>
            <th class="text-center"><input type="checkbox" class="chk-head" /> All Present</th>
            <th class="text-center">Grade</th>
            <th class="text-center">Comment</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $dat = $_GET['date'];
        $ddate = strtotime($dat);
        $sub = $_GET['subject'];
        $que = "SELECT sid, aid, ispresent  from attendance  WHERE date  =$ddate
                AND id=$sub ORDER BY sid";
        $ret = $conn->query($que);
        $attData = $ret->fetchAll(PDO::FETCH_ASSOC);

        if (count($attData)) {
            $updateFlag = 1;
        } else {
            $updateFlag = 0;
        }

        $qu = "SELECT student.sid, student.name, student.rollno from student INNER JOIN student_subject WHERE student.sid = student_subject.sid AND student_subject.id  = {$_GET['subject']}  ORDER BY student.sid";
        $stu = $conn->query($qu);
        $rstu = $stu->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($rstu); $i++) {
            echo "<tr>";
            if ($updateFlag) {
                echo "<td>" . $rstu[$i]['rollno'] . "<input type='hidden' name='st_sid[]' value='" . $rstu[$i]['sid'] . "'>" . "<input type='hidden' name='att_id[]' value='" . $attData[$i]['aid'] . "'></td>";
                echo "<td>" . $rstu[$i]['name'] . "</td>";
                if (($rstu[$i]['sid'] ==  $attData[$i]['sid']) && ($attData[$i]['ispresent'])) {
                    echo "<td><input class='chk-present' checked type='checkbox' name='chbox[]' value='" . $rstu[$i]['sid'] . "'></td>";
                } else {
                    echo "<td><input class='chk-present' type='checkbox' name='chbox[]' value='" . $rstu[$i]['sid'] . "'></td>";
                }
            } else {
                echo "<td>" . $rstu[$i]['rollno'] . "<input type='hidden' name='st_sid[]' value='" . $rstu[$i]['sid'] . "'></td>";
                echo "<td>" . $rstu[$i]['name'] . "</td>";
                echo "<td><input class='chk-present' type='checkbox' name='chbox[]' value='" . $rstu[$i]['sid'] . "'></td>";
            }
           
            echo "<td>";
            echo "<select name='grade[]' class='form-control'>";
            echo "<option value='1'>1</option>";
            echo "<option value='2'>2</option>";
            echo "<option value='3'>3</option>";
            echo "<option value='4'>4</option>";
            echo "<option value='5'>5</option>";
            echo "</select>";
            echo "</td>";
            
            echo "<td>";
            echo "<textarea name='comment[]' class='form-control' rows='4'></textarea>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>




			<?php if($updateFlag) : ?>
				<input type="hidden" name="updateData" value="1">
			<?php else: ?>
				<input type="hidden" name="updateData" value="0">
			<?php endif; ?>
             
            
			<input type="hidden" name="date" value="<?php print isset($_GET['date']) ? $_GET['date'] : ''; ?>">
			<input type="hidden" name="subject" value="<?php print isset($_GET['subject']) ? $_GET['subject'] : ''; ?>">
			<button type="submit" class="btn btn-success btn-block" style='border-radius:0%;' name="sbt_top"><i class="glyphicon glyphicon-ok-sign"></i> Save Attendance</button>
		
            
    

			</form>
			
			<?php
				else :
			?>
			
			<p>&nbsp;</p>
			<div class="alert alert-dismissible alert-danger">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>Sorry!</strong> Attendance cannot be recorded for future dates!.
			</div>	
			
			<?php
				endif;
			?>
			
			<?php endif;?>
			
			<?php
if (isset($_POST['sbt_top'])) {
    if (isset($_POST['updateData']) && ($_POST['updateData'] == 1)) {
        // Update existing attendance data
        $id = $_POST['subject'];
        $uid = $_SESSION['uid'];
        $p = 0;
        $st_sid = $_POST['st_sid'];
        $attt_aid = $_POST['att_id'];
        $ispresent = array();

        // Check if the grade and comment fields are set in the POST data
        $grade = $_POST['grade'];
        $comment = $_POST['comment'];
        checkAttendanceAndNotify($conn);
        if (isset($_POST['chbox'])) {
            $ispresent = $_POST['chbox'];
        }

        for ($j = 0; $j < count($st_sid); $j++) {
            $stmtUpdate = $conn->prepare("UPDATE attendance SET ispresent = :isMarked, grade = :grade, comment = :comment WHERE aid = :aid");
            if (count($ispresent)) {
                $p = (in_array($st_sid[$j], $ispresent)) ? 1 : 0;
            }
            $stmtUpdate->bindParam(':isMarked', $p);
            $stmtUpdate->bindParam(':grade', $grade[$j]);
            $stmtUpdate->bindParam(':comment', $comment[$j]);
            $stmtUpdate->bindParam(':aid', $attt_aid[$j]);
            $stmtUpdate->execute();
        }
        echo '<p>&nbsp;</p><div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Well done!</strong> Attendance Recorded Successfully!.
              </div>';
    } else {
        // Insert new attendance data
        $date = $_POST['date'];
        $tstamp = strtotime($date);
        $id = $_POST['subject'];
        $uid = $_SESSION['uid'];
        $p = 0;
        $st_sid = $_POST['st_sid'];

        // Check if the grade and comment fields are set in the POST data
        $grade = $_POST['grade'];
        $comment = $_POST['comment'];

        $ispresent = array();
        if (isset($_POST['chbox'])) {
            $ispresent = $_POST['chbox'];
        }

        for ($j = 0; $j < count($st_sid); $j++) {
            $stmtInsert = $conn->prepare("INSERT INTO attendance (sid, date, ispresent, uid, id, grade, comment) 
                            VALUES (:sid, :date, :ispresent, :uid, :id, :grade, :comment)");
            if (count($ispresent)) {
                $p = (in_array($st_sid[$j], $ispresent)) ? 1 : 0;
            }
            $stmtInsert->bindParam(':sid', $st_sid[$j]);
            $stmtInsert->bindParam(':date', $tstamp);
            $stmtInsert->bindParam(':ispresent', $p);
            $stmtInsert->bindParam(':uid', $uid);
            $stmtInsert->bindParam(':id', $id);
            $stmtInsert->bindParam(':grade', $grade[$j]);
            $stmtInsert->bindParam(':comment', $comment[$j]);
            $stmtInsert->execute();
        }
        echo '<p>&nbsp;</p><div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Well done!</strong> Attendance Recorded Successfully!.
              </div>';
    }
}       
			
			?>
		</div>
	</div>
</div>

<script>
	$('#subjectForm').validator();	
</script>