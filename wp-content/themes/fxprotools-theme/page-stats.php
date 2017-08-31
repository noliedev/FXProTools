<?php
$category_slug = 'stats';
$category = get_term_by('slug', $category_slug, 'ld_course_category' );
$courses = get_courses_by_category_id($category->term_id);
$funnels = get_funnels();
?>
<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-marketing'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="fx-list-courses">
					<?php if( $courses ) : ?>
						<?php $count = 0; foreach($courses as $post): setup_postdata($post); $count++; ?>
							<?php get_template_part('inc/templates/product/list-course'); ?>
						<?php endforeach;?>
						<?php wp_reset_query(); ?>
					<?php endif;?>
				</ul>
				<br/>
				<div class="fx-header-title">
					<h1>Your Sales Funnels Stats</h1>
					<p>Let us do most of the work for you</p>
				</div>
				<div class="form-inline m-b-md">
					<div class="form-group">
						<input data-provide="datepicker" data-date-format="mm/dd/yyyy" type="text" class="form-control" value="" placeholder="Starting: MM/DD/YYYY">
					</div>
					<div class="form-group">
						<input data-provide="datepicker" data-date-format="mm/dd/yyyy" type="text" class="form-control" value="" placeholder="Ending: MM/DD/YYYY">
					</div>
				</div>
				<?php foreach ($funnels as $key => $post): setup_postdata($post); $stats = get_funnel_stats($post->ID); ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="small text-center">Funnel #<?php echo $key + 1;?></th>
							<th class="small text-center" colspan="2">Page View</th>
							<th class="small text-center" colspan="2">Opt Ins</th>
							<th class="small text-center" colspan="2">Sales</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>&nbsp;</td>
							<td class="small text-center">All</td>
							<td class="small text-center">Uniques</td>
							<td class="small text-center">All</td>
							<td class="small text-center">Rate %</td>
							<td class="small text-center">Count</td>
							<td class="small text-center">Rate %</td>
						</tr>
						<tr>
							<td>Step 1: Capture Page</td>
							<td class="text-center"><?php echo $stats['capture']['page_views']['all'];?></td>
							<td class="text-center"><?php echo $stats['capture']['page_views']['unique'];?></td>
							<td class="text-center"><?php echo $stats['capture']['opt_ins']['all'];?></td>
							<td class="text-center"><?php echo $stats['capture']['opt_ins']['rate'];?>%</td>
							<td class="text-center"><?php echo $stats['capture']['sales']['count'];?></td>
							<td class="text-center"><?php echo $stats['capture']['page_views']['rate'];?>%</td>
						</tr>
						<tr>
							<td>Step 2: Landing Page</td>
							<td class="text-center"><?php echo $stats['landing']['page_views']['all'];?></td>
							<td class="text-center"><?php echo $stats['landing']['page_views']['unique'];?></td>
							<td class="text-center"><?php echo $stats['landing']['opt_ins']['all'];?></td>
							<td class="text-center"><?php echo $stats['landing']['opt_ins']['rate'];?></td>
							<td class="text-center"><?php echo $stats['landing']['sales']['count'];?></td>
							<td class="text-center"><?php echo $stats['landing']['page_views']['rate'];?></td>
						</tr>
					</tbody>
				</table>
				<div class="row m-b-md">
					<div class="col-md-6">
						<span class="total-count funnel-stats">Total Customer Sales: <?php echo $stats['totals']['customer_sales'];?></span>
					</div>
					<div class="col-md-6">
						<span class="total-count funnel-stats">Total Distributor Sales: <?php echo $stats['totals']['distributor_sales'];?></span>
					</div>
				</div>
				<?php endforeach;?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>