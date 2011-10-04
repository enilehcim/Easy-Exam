
<div id = "add-exam-panel">
	<span class = "panel-title">Create New Exam</span>
	<form method = "post" action = "admin.php" id = "add-exam-form">
		<input type = "hidden" name = "action" value = "addExam" />
		<table>
			<tr>
				<td>Exam Name</td>
				<td><input type = "text" name = "name" /></td>
			</tr>
			<tr>
				<td>Get Questions From</td>
				<td>
				<?php 
				$attributes = array('name' => 'questions_category');
				echo questionCategorySelectHTML($attributes);
				?>
				</td>
			</tr>
			<tr>
				<td>Exam Availability Start</td>
				<td>
					Date <input style="width:5em;text-align:center" type="text" name="start_date"/>
					Time <input style="width:3em;text-align:center" type="text" name="start_time"/>
				</td>
			</tr>
			<tr>
				<td>Exam Availability End</td>
				<td>
					Date <input style="width:5em;text-align:center" type="text" name="end_date"/>
					Time <input style="width:3em;text-align:center" type="text" name="end_time"/>
				</td>
			</tr>
			<tr>
				<td>Time Limit In Hours</td>
				<td>
					<input style="width:2em;text-align:center" type="text" name="time_limit"/>
				</td>
			</tr>
			<tr>
				<td>Passing Score</td>
				<td><input type="text" name="passing_score" style="width:2em"/> %
				</td>
			</tr>
			
			<tr>
				<td></td>
				<td><input type = "submit" value = "Add"/></td>
			</tr>
			<tr>
				
				<td colspan="2" style="color:GREEN;font-size:80%">
					<p>
					The date of exam expects the following format for entry: YYYY-MM-DD.<br/>
					YYYY is a 4 digit year, MM is a 2 digit month and DD is the 2 digit day.<br/>
					Example: For May 8, 2012 the entry should be 2012-05-08 <br/><br/>
					For the time of exam, the following format is expected: HH:MM<br/>
					HH is the time in hour (0 to 24) and MM is in minutes (0 to 60)<br/>
					Example: 6:30PM should be specified ad 18:30
					</p>
				</td>
			</tr>
			
		</table>
		
	</form>
</div>
		