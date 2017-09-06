<?php
$progress = learndash_course_progress(array( 'course_id' => get_the_ID(), 'array' => true) ); 
?>

<div class="progress">
 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress['percentage'];?>%">
		<?php echo $progress['percentage'];?>%
 	</div>
</div>