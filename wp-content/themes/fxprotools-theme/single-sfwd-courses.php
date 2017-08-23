<?php
$course_id = get_the_ID();
$course = get_post( $course_id ); 
$lessons = get_lessons_by_course_id( $course_id );
$course_progress = get_user_progress();


?>

<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-products'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1><?php the_title();?></h1>
					<p><?php echo rwmb_meta('subtitle');?></p>
				</div>
			</div>
			<div class="col-md-8 col-md-offset-2">
				<div class="fx-video-container"></div>
				<br/>
				<a href="#" class="btn btn-success block">Start This Course</a>
				<br/>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default fx-course-outline">
					<div class="panel-body">
						<h3>Course Description</h3>
						
						<div class="content">
							<?php echo $course->post_content; ?>
						</div>
						<hr/>
						<h5 class="text-bold">Course Progress</h5>

						<?php get_template_part('inc/templates/product/progressbar'); ?>

						<hr/>
						<h5 class="text-bold">Course Lessons</h5>
						<table class="table table-bordered fx-table-lessons">
							<thead>
								<tr>
									<th style="width: 100px;">Lessons</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
							
							<?php if( $lessons ) : ?>

								<?php $count = 0;  foreach($lessons as $post): setup_postdata($post); $count++; ?>

									<?php get_template_part('inc/templates/product/list-lesson'); ?>

								<?php endforeach;?>

								<?php wp_reset_query(); ?>

							<?php endif;?>

							</tbody>
						</table>
					</div>
				</div>
			</div>	
		</div>
	</div>

	

<?php get_footer(); ?>