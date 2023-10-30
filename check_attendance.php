<?php include 'db_connect.php' ?>
<?php

if(isset($_GET['attendance_id'])){
	// echo "SELECT * FROM attendance_list where id = {$_GET['attendance_id']}";
$qry = $conn->query("SELECT * FROM attendance_list where id = {$_GET['attendance_id']}");
foreach($qry->fetch_array() as $k => $v){
	$$k = $v;
}
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header"><b>Check Attendance</b></div>
			<div class="card-body">
				<form action="attendance_report.php" id="manage-attendance">
					<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
					<div class="row justify-content-center">
						<label for="" class="mt-2">Class per Subjects</label>
						<div class="col-sm-4">
				            <select name="class_subject_id" id="class_subject_id" class="custom-select select2 input-sm">
				                <option value=""></option>
				                <?php
				                $class = $conn->query("SELECT cs.*,concat(co.course,' ',c.level,'-',c.section) as `class`,s.subject,f.name as fname FROM class_subject cs inner join `class` c on c.id = cs.class_id inner join courses co on co.id = c.course_id inner join faculty f on f.id = cs.faculty_id inner join subjects s on s.id = cs.subject_id ".($_SESSION['login_faculty_id'] ? " where f.id = {$_SESSION['login_faculty_id']} ":"")." order by concat(co.course,' ',c.level,'-',c.section) asc");
				                while($row=$class->fetch_assoc()):
				                ?>
				                <option value="<?php echo $row['id'] ?>" data-cid="<?php echo $row['id'] ?>" <?php echo isset($class_subject_id) && $class_subject_id == $row['id'] ? 'selected' : (isset($class_subject_id) && $class_subject_id == $row['id'] ? 'selected' :'') ?>><?php echo $row['class'].' '.$row['subject']. ' [ '.$row['fname'].' ]' ?></option>
				                <?php endwhile; ?>
				            </select>
						</div>
						<div class="col-sm-3">
							<input type="date" name="doc" value="<?php echo isset($doc) ? date('Y-m-d',strtotime($doc)) :date('Y-m-d') ?>" class="form-control">
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12" id='att-list'>
							<center><b><h4><i>Please Select Class First.</i></h4></b></center>
						</div>
						<div class="col-md-12"  style="display: none" id="submit-btn-field">
							<center>
								<button class="btn btn-primary btn-sm col-sm-5">Save</button>
							</center>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="table_clone" style="display: none">
	<table class='table table-bordered table-hover'>
		<thead>
			<tr>
				<th>#</th>
				<th>Student</th>
				<th>Attendance</th>
				<th>Grade</th>
                <th>Comments</th>
			</tr>
		</thead>
		<tbody>
		
		</tbody>
	</table>
</div>
<div id="chk_clone" style="display: none">
	<div class="d-flex justify-content-center chk-opts">
		<div class="form-check form-check-inline">
		  <input class="form-check-input present-inp" type="checkbox" value="1">
		  <label class="form-check-label present-lbl">Present</label>
		</div>
		<div class="form-check form-check-inline">
		  <input class="form-check-input absent-inp" type="checkbox" value="0">
		  <label class="form-check-label absent-lbl">Absent</label>
		</div>
		<div class="form-check form-check-inline">
		  <input class="form-check-input late-inp" type="checkbox" value="2">
		  <label class="form-check-label late-lbl">Late</label>
		</div>
		<!-- <select class="custom-select grade-select" name="grade[id]">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <textarea class="form-control comments-textarea" name="comments[id]" rows="4"></textarea>
     -->
	</div>
	
</div>
<style>
	.present-inp,.absent-inp,.late-inp,.present-lbl,.absent-lbl,.late-lbl{
		cursor: pointer;
	}
</style>
<script>
$(document).ready(function(){
    if ('<?php echo isset($class_subject_id) ? 1 : 0 ?>' == 1) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=get_class_list',
            method: 'POST',
            data: {
                class_subject_id: $('#class_subject_id').val(),
                doc: $('#doc').val(),
                att_id: '<?php echo isset($id) ? $id : '' ?>'
            },
            success: function(resp) {
                if (resp) {
                    resp = JSON.parse(resp);
                    var _table = $('#table_clone table').clone();
                    $('#att-list').html('');
                    $('#att-list').append(_table);
                    var _type = ['Absent', 'Present', 'Late'];
                    var data = resp.data;
                    var record = resp.record;
                    var attendance_id = !!resp.attendance_id ? resp.attendance_id : '';
                    if (Object.keys(data).length > 0) {
                        var i = 1;
                        Object.keys(data).map(function(k) {
                            var name = data[k].name;
                            var id = data[k].id;
                            var tr = $('<tr></tr>');
                            var opts = $('#chk_clone').clone();

                            opts.find('.present-inp').attr({'name': 'type[' + id + ']', 'id': 'present_' + id});
                            opts.find('.absent-inp').attr({'name': 'type[' + id + ']', 'id': 'absent_' + id});
                            opts.find('.late-inp').attr({'name': 'type[' + id + ']', 'id': 'late_' + id});

                            opts.find('.present-lbl').attr({'for': 'present_' + id});
                            opts.find('.absent-lbl').attr({'for': 'absent_' + id});
                            opts.find('.late-lbl').attr({'for': 'late_' + id});

                            tr.append('<td class="text-center">' + (i++) + '</td>');
                            tr.append('<td class="student-name">' + name + '</td>');
                            var td = '<td>';
                            td += '<input type="hidden" name="student_id[' + id + ']" value="' + id + '">';
                            td += opts.html();
                            td += '</td>';
                            tr.append(td);

                            // Add a <td> for Grade
                            var gradeSelect = '<td>';
                            gradeSelect += '<select class="custom-select grade-select" name="grade[' + id + ']">';
                            gradeSelect += '<option value="1">1</option>';
                            gradeSelect += '<option value="2">2</option>';
                            gradeSelect += '<option value="3">3</option>';
                            gradeSelect += '<option value="4">4</option>';
                            gradeSelect += '<option value="5">5</option>';
                            gradeSelect += '</select>';
                            gradeSelect += '</td>';
                            tr.append(gradeSelect);

                            // Add a <td> for Comments
                            var commentsTd = '<td>';
                            commentsTd += '<textarea class="form-control comments-textarea" name="comments[' + id + ']" rows="4"></textarea>';
                            commentsTd += '</td>';
                            tr.append(commentsTd);

                            _table.find('tbody').append(tr);
                        });
                        $('#submit-btn-field').show();
                        $('#edit_att').attr('data-id', attendance_id);
                    } else {
                        var tr = $('<tr></tr>');
                        tr.append('<td class="text-center" colspan="5">No data.</td>');
                        _table.find('tbody').append(tr);
                        $('#submit-btn-field').attr('data-id', '').hide();
                        $('#edit_att').attr('data-id', '');
                    }
                    $('#att-list').html('');
                    $('#att-list').append(_table);
                    if (Object.keys(record).length > 0) {
                        Object.keys(record).map(k => {
                            $('#att-list').find('.student-name').each(function () {
                                if ($(this).text() === record[k].name) {
                                    $(this).closest('tr').find('input:checkbox[name="type[' + record[k].student_id + ']"][value="' + record[k].type + '"]').prop('checked', true);
                                    $(this).closest('tr').find('select[name="grade[' + record[k].student_id + ']"]').val(record[k].grade);
                                    $(this).closest('tr').find('textarea[name="comments[' + record[k].student_id + ']"]').val(record[k].comments);
                                }
                            });
                        });
                    }
                }
            },
            complete: function () {
                $("input:checkbox").on('keyup keypress change', function () {
                    var group = "input:checkbox[name='" + $(this).attr("name") + "']";
                    $(group).prop("checked", false);
                    $(this).prop("checked", true);
                });
                $('#edit_att').click(function () {
                    location.href = 'index.php?page=check_attendance&attendance_id=' + $(this).attr('data-id');
                });
                end_load();
            }
        });
    }
});

$('#class_subject_id').change(function () {
    get_data($(this).val());
});

window.get_data = function (id) {
    start_load();
    $.ajax({
        url: 'ajax.php?action=get_class_list',
        method: 'POST',
        data: {class_subject_id: id},
        success: function (resp) {
            if (resp) {
                resp = JSON.parse(resp);
                var _table = $('#table_clone table').clone();
                $('#att-list').html('');
                $('#att-list').append(_table);
                if (Object.keys(resp).length > 0) {
                    var i = 1;
                    Object.keys(resp.data).map(function (k) {
                        var name = resp.data[k].name;
                        var id = resp.data[k].id;
                        var tr = $('<tr></tr>');
                        var opts = $('#chk_clone').clone();
                        opts.find('.present-inp').attr({'name': 'type[' + id + ']', 'id': 'present_' + id});
                        opts.find('.absent-inp').attr({'name': 'type[' + id + ']', 'id': 'absent_' + id});
                        opts.find('.late-inp').attr({'name': 'type[' + id + ']', 'id': 'late_' + id});

                        opts.find('.present-lbl').attr({'for': 'present_' + id});
                        opts.find('.absent-lbl').attr({'for': 'absent_' + id});
                        opts.find('.late-lbl').attr({'for': 'late_' + id});

                        tr.append('<td class="text-center">' + (i++) + '</td>');
                        tr.append('<td class="student-name">' + name + '</td>');
                        var td = '<td>';
                        td += '<input type="hidden" name="student_id[' + id + ']" value="' + id + '">';
                        td += opts.html();
                        td += '</td>';
                        tr.append(td);

                        // Add a <td> for Grade
                        var gradeSelect = '<td>';
                        gradeSelect += '<select class="custom-select grade-select" name="grade[' + id + ']">';
                        gradeSelect += '<option value="1">1</option>';
                        gradeSelect += '<option value="2">2</option>';
                        gradeSelect += '<option value="3">3</option>';
                        gradeSelect += '<option value="4">4</option>';
                        gradeSelect += '<option value="5">5</option>';
                        gradeSelect += '</select>';
                        gradeSelect += '</td>';
                        tr.append(gradeSelect);

                        // Add a <td> for Comments
                        var commentsTd = '<td>';
                        commentsTd += '<textarea class="form-control comments-textarea" name="comments[' + id + ']" rows="4"></textarea>';
                        commentsTd += '</td>';
                        tr.append(commentsTd);

                        _table.find('tbody').append(tr);
                    });
                    $('#submit-btn-field').show();
                } else {
                    var tr = $('<tr></tr>');
                    tr.append('<td class="text-center" colspan="5">No data.</td>');
                    _table.find('tbody').append(tr);
                    $('#submit-btn-field').hide();
                }
                $('#att-list').html('');
                $('#att-list').append(_table);
            }
        },
        complete: function () {
            $("input:checkbox").on('keyup keypress change', function () {
                var group = "input:checkbox[name='" + $(this).attr("name") + "']";
                $(group).prop("checked", false);
                $(this).prop("checked", true);
            });
            end_load();
        }
    });
}

$('#manage-attendance').submit(function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Check if the form is submitted with the "Absent" checkbox checked
    var isAbsentChecked = $('.absent-inp:checked').length > 0;

    if (isAbsentChecked) {
        // The form is submitted with the "Absent" checkbox checked
        // You can add your code here to call a page or perform an action
        // For example, you can use AJAX to call a page
        start_load(); // Start loading if needed

        $.ajax({
            url: 'your_page_to_call.php',
            method: 'POST', // Change the method as needed
            data: $(this).serialize(), // Serialize the form data
            success: function (resp) {
                // Handle the response from the page you called
                // Stop loading and handle the response here
                end_load(); // Stop loading if needed
            }
        });
    } else {
        // The form is submitted without the "Absent" checkbox checked
        // Proceed with the form submission as usual
        start_load(); // Start loading if needed

        $.ajax({
            url: 'ajax.php?action=save_attendance',
            method: 'POST',
            data: $(this).serialize(),
            success: function (resp) {
                // Handle the response from the default form submission
                if (resp == 1) {
                    alert_toast("Data successfully saved.", 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else if    (resp == 2) {
                    alert_toast("Class already has an attendance record with the selected subject and date.", 'danger');
                }
                end_load(); // Stop loading if needed
            }
        });
    }
});


</script>