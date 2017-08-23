<?php 

global $count; 
global $course_id;

$is_complete = get_course_lesson_progress($course_id, get_the_ID());

?>
<tr>
	<td class="text-center number"><?php echo $count; ?></td>
	<td>
		<a href="<?php the_permalink();?>"><?php the_title();?></a>
		<div class="status pull-right">
			<i class="fa <?php echo  $is_complete ?  'fa-check text-success' : '';?>"></i>
		</div>
	</td>
</tr>