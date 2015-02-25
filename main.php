<?php
/**
 * local plugin for admins.
 *
 * @package    local
 * @subpackage dlit_admin
 * @copyright  DLIT
 * @license  
 */
global $DB, $PAGE, $USER, $CFG;
require_once('../../config.php');
require_once($CFG->libdir.'/formslib.php');
// require_once($CFG->diroot.'/local/dlit_admin/main.php');


$PAGE->set_context(context_system::instance()); 
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Admin');
$PAGE->set_heading('Admin');

$PAGE->set_url($CFG->wwwroot.'/local/dlit_admin/main.php');
?>
<?php

require_login();

echo $OUTPUT->header();


$context    = context_system::instance(); 
if (has_capability('local/dlit_admin:add', $context)): //only allowed person to view the page is admin and manager



    $getMoodleCategories = $DB->get_records("course_categories", null, 'name');
?>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF'];?>">
<?php echo"<h1>Check lecturers and Students</h1>";?>
    <select name="catId">
        <?php foreach($getMoodleCategories as $category): ?>
			<?php if(@$_GET['catId'] == $category->id):?>
				<option value="<?php echo $category->id;?>" selected><?php echo $category->name;?></option>
			<?php else:?>
				<option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
			<?php endif;?>
        <?php endforeach;?>
    </select>
	<input type='submit' name='action'  value='Lecturer no Students'></input>
	<input type='submit' name='action'  value='Students no Lecturer'></input>
	<input type='submit' name='action'  value='Check Enrollments'></input>
</form>
<?php
    if(isset($_GET['action']) && ($_GET['action'] == "Lecturer no Students")){
        $categoryId = $_GET['catId'];
        $categoryName = $DB->get_record("course_categories", array('id'=>$categoryId),'name,path,depth');

        $resultsCourses = $DB->get_records("course",array('category'=>$categoryId));

        echo "<h1>Enrollments in Category $categoryName->name</h1>";
		$isempty = 0; /* 0 when course has no one enrolled*/
					/*1 when there is a lecturer or student enrolled */
        foreach ($resultsCourses as $course){
            $coursecontext = context_course::instance($course->id);
            $students = get_role_users(5, $coursecontext);
			$lecturers = get_role_users(3, $coursecontext);
			$non_editing_teach = get_role_users(4, $coursecontext);
			$course_leader = get_role_users(9, $coursecontext);
			$stud_count = 0;
			$lec_count = 0;
			$non_ed_teach_count = 0;
			$course_leader_count = 0;
			
			/*search for lecturer*/
			foreach($lecturers as $lecturer){
				$lec_count++; 
            }
			/*search for student*/
			foreach($students as $student){ 
				$stud_count++;
            }
			foreach($non_editing_teach as $non_ed_t){ 
				$non_ed_teach_count++;
            }
			foreach($course_leader as $course_lead){ 
				$course_leader_count++;
            }
			
			if ((($lec_count >= 1) or ($non_ed_teach_count >= 1)) and ($stud_count == 0))
			{	
				echo "<h2><a href='".$CFG->wwwroot."/course/view.php?id=$course->id' target='_blank'>$course->fullname</a></h2>";
				echo "<table>";
				$lec_count = 0;
				/*search for lecturers*/
				foreach($lecturers as $lecturer){
					echo "<tr>";
					echo "<td><a href='".$CFG->wwwroot."/user/profile.php?id=$lecturer->id' target='_blank'>$lecturer->username</a></td><td><font color='red'>$lecturer->firstname</font></td><td><font color='red'>$lecturer->lastname</font></td>";
					echo "</tr>"; 
				}
				foreach($non_editing_teach as $non_ed_t){ 
					echo "<tr>";
					echo "<td><a href='".$CFG->wwwroot."/user/profile.php?id=$non_ed_t->id' target='_blank'>$non_ed_t->username</a></td><td><font color='red'>$non_ed_t->firstname</font></td><td><font color='red'>$non_ed_t->lastname</font></td>";
					echo "</tr>"; 
				}
				foreach($course_leader as $course_lead){ 
					echo "<tr>";
					echo "<td><a href='".$CFG->wwwroot."/user/profile.php?id=$course_lead->id' target='_blank'>$course_lead->username</a></td><td><font color='red'>$course_lead->firstname</font></td><td><font color='red'>$course_lead->lastname</font></td>";
					echo "</tr>"; 
				}							
				echo "</table>";
			}
        }
    }	
	
	if(isset($_GET['action']) && ($_GET['action'] == "Students no Lecturer")){
        $categoryId = $_GET['catId'];
        $categoryName = $DB->get_record("course_categories", array('id'=>$categoryId),'name,path,depth');
        $resultsCourses = $DB->get_records("course",array('category'=>$categoryId));
        echo "<h1>Enrollments in Category $categoryName->name</h1>";
		$isempty = 0; /* 0 when course has no one enrolled*/
					  /* 1 when there is a lecturer or student enrolled*/	
        foreach ($resultsCourses as $course){
            $coursecontext = context_course::instance($course->id);
            $students = get_role_users(5, $coursecontext);
			$lecturers = get_role_users(3, $coursecontext);
			$non_editing_teach = get_role_users(4, $coursecontext);
			$course_leader = get_role_users(9, $coursecontext);
			
			$stud_count = 0;
			$lec_count = 0;
			$non_ed_teach_count = 0;
			$course_leader_count = 0;
			
			/*search for lecturer*/
			foreach($lecturers as $lecturer){
				$lec_count++; 
            }
			/*search for student*/
			foreach($students as $student){ 
				$stud_count++;
            }
			foreach($non_editing_teach as $non_ed_t){ 
				$non_ed_teach_count++;
            }
			
			if ((($lec_count == 0) and ($non_ed_teach_count == 0)) and ($stud_count >= 1))
			{	
				echo "<h2><a href='".$CFG->wwwroot."/course/view.php?id=$course->id' target='_blank'>$course->fullname</a></h2>";
			}
        }		
	}
	
    if(isset($_GET['action']) && ($_GET['action'] == "Check Enrollments")){
        $categoryId = $_GET['catId'];
        $categoryName = $DB->get_record("course_categories", array('id'=>$categoryId),'name,path,depth');

        $resultsCourses = $DB->get_records("course",array('category'=>$categoryId));

        echo "<h1>Enrollments in Category $categoryName->name</h1>";

        foreach ($resultsCourses as $course){
            $coursecontext = context_course::instance($course->id);
            $students = get_role_users(5 , $coursecontext);
            $lecturers = get_role_users(3 , $coursecontext);
			$non_editing_teach = get_role_users(4 , $coursecontext);
			
            echo "<h2><a href='".$CFG->wwwroot."/course/view.php?id=$course->id' target='_blank'><font color='blue'>$course->fullname</font></a></h2>";
            echo "<table>";
			if (!((empty($students)) && (empty($lecturers)) && (empty($non_editing_teach)))) // check if the course is empty
			{

				if (((empty($lecturers))&&(empty($non_editing_teach)))){ 
					echo "<tr>";
					echo "<td><font color='#8B008B'>No lecturer(s) enrolled</font></td>";
					echo "</tr>";						
				}else{				
					echo "<th> Lecturer(s)</th>";
					foreach($lecturers as $lecturer){
						echo "<tr>";
						echo "<td><a href='".$CFG->wwwroot."/user/profile.php?id=$lecturer->id' target='_blank'>$lecturer->username</a></td><td><font color='red'>$lecturer->firstname</font></td><td><font color='red'>$lecturer->lastname</font></td>";
						echo "</tr>"; 
					}
					foreach($non_editing_teach as $non_ed_t){ 
						echo "<tr>";
						echo "<td><a href='".$CFG->wwwroot."/user/profile.php?id=$non_ed_t->id' target='_blank'>$non_ed_t->username</a></td><td><font color='red'>$non_ed_t->firstname</font></td><td><font color='red'>$non_ed_t->lastname</font></td>";
						echo "</tr>"; 
					}
				}
					if (empty($students)){
						echo "<tr>";
						echo "<td><font color='#8B008B'>No student(s) enrolled</font></td>";
						echo "</tr>";						
					}else{	
						echo "<th>Student(s)</th>";				
						foreach($students as $student){
							echo "<tr>";
							echo "<td><a href='".$CFG->wwwroot."/user/profile.php?id=$student->id' target='_blank'>$student->username</a></td><td>$student->firstname</td><td>$student->lastname</td>";
							echo "</tr>";
						}
					}
			}else{
				echo "<tr>";
				echo "<td><font color='#8B008B'>The course is empty</font></td>";
				echo "</tr>";
			}	
            echo "</table>";
        }
    }	

?>
<?php
	class simplehtml_form extends moodleform {
		public function definition() {
			global $CFG;
			$mform = $this->_form; // Don't forget the underscore! 
			$mform->addElement('textarea', 'id', 'id', 'wrap="virtual" rows="20" cols="50"');
			$this->add_action_buttons($cancel = true, $submitlabel=null);
		}
		
	}
		
?>
		<?php
			$mform = new simplehtml_form();
			echo "<h1>Search user(s)</h1>";
			if ($mform->is_cancelled()) {
				//Handle form cancel operation, if cancel button is present on form
				$mform->display();
			}else if ($fromform = $mform->get_data()) {

				//In this case you process validated data. $mform->get_data() returns data posted in form.
				$mform->display();
				$users = explode(",",$fromform->id);
				foreach($users as $user){

					$array = array('username'=>$user);//there is a space in pics
					//var_dump($array);
					$result = $DB->get_record('user', $array);
					//var_dump($result);
					if($result){
						echo "<a href='".$CFG->wwwroot."/user/profile.php?id=".$result->id."'>".$result->username."</a>";
						echo  " ".$result->firstname." ".$result->lastname." ".$result->email;
						if ($mycourses = enrol_get_all_users_courses($result->id, true, NULL, 'visible DESC,sortorder ASC')) {  	
							//var_dump($mycourses);
							echo "<ol>";
							foreach ($mycourses as $course){
								echo "<li><a href='".$CFG->wwwroot."/course/view.php?id=".$course->id."' class='myLink' target='_blank'>".$course->shortname."</a></li>";
							}
							echo "</ol>";
							echo "<br/>";
						}
						echo "<br/>";
						echo "<br/>";
						//var_dump($users);
					}else{
						echo "This user does not exist";
					}
				}
			} else {
			  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
			  // or on the first display of the form.
			 
			  echo "Insert the Student ID seperated by a comma and press save changes";
			  //displays the form
			  $mform->display();
			}
			
		
		?>
<?php endif; //end of capability?>			
		
<?php echo $OUTPUT->footer();?>                                     