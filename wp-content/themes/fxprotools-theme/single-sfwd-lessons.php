<?php
global $post;
$lesson_id = get_the_ID();
$lesson = get_post($lesson_id);
$course = get_lesson_parent_course( $lesson_id );
$course_id = $course->ID;
$lessons = get_lessons_by_course_id( $course->ID );
$course_progress = get_user_progress();

$course_video = Learndash_Course_Video::get_instance();
$lesson_settings = learndash_get_setting( $post );
$video = $course_video->add_video_to_content( '', $post, $lesson_settings );

?>

<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-products'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-body">
						<h5 class="text-bold">Course Progress</h5>
						<?php get_template_part('inc/templates/course/progressbar'); ?>
					</div>
				</div>
				<div class="panel panel-default fx-course-navigation">
					<div class="panel-body">
						<h5 class="text-bold">Lesson Navigation</h5>
						<?php if( $lessons ) : ?>
							<ul>
							<?php $count = 0;  foreach($lessons as $post): setup_postdata($post); $count++; ?>
								<?php $is_complete = get_course_lesson_progress($course_id, get_the_ID());?>
								<li class="<?php echo  $is_complete ?  'completed' : '';?>"><a href="<?php the_permalink();?>"><?php the_title();?></a></li>
							<?php endforeach;  wp_reset_query(); ?>
							</ul>
						<?php endif;?>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="row">
					<div class="col-md-12">
						<div class="fx-header-title">
							<h3>Lesson #<?php echo $lesson->menu_order;?></h3>
							<h1><?php the_title();?></h1>
						</div>
					</div>
					<div class="col-md-12">
						<div class="fx-video-container">
							<?php echo $video; ?>
						</div>
						<br/>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-12">
						<div class="panel panel-default fx-course-outline">
							<div class="panel-body">
								<div class="content">
									<?php echo wpautop($lesson->post_content); ?>
								</div>
								<br>
								<?php if( get_course_lesson_progress($course_id, $lesson_id) != 1 ): ?>
									<div class="mark-complete">
										<form id="sfwd-mark-complete" method="post" action="">
											<input type="hidden" value="<?php echo $lesson_id;?>" name="post" />
											<input type="hidden" value="<?php echo wp_create_nonce( 'sfwd_mark_complete_'. get_current_user_id() .'_'. $lesson_id );?>" name="sfwd_mark_complete" />
											<input type="submit" value="Mark Complete" class="btn btn-success block" style="width:100%;" id="learndash_mark_complete_button"/>

										</form>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<div class="fx-adjacent-lessons">
							<?php echo learndash_next_post_link(); ?>
							<?php echo learndash_previous_post_link(); ?>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>

	

<?php get_footer(); ?>