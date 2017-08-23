<?php global $count; ?>
<li class="list-item">
	<div class="left">
		<div class="box">
			<span class="sash">Active</span>
			<span class="number"><?php echo $count;?></span>
		</div>
	</div>
	<div class="right">
		<div class="row">
			<div class="col-md-12">
				<span class="title"><?php the_title();?></span>
			</div>
			<div class="col-md-10">
				<p><?php echo rwmb_meta('short_description');?></p>	
			</div>
			<div class="col-md-2">
				<a href="<?php the_permalink(); ?>" class="btn btn-default block">Learn More</a>
			</div>
			<div class="col-md-12">
				<?php get_template_part('inc/templates/product/progressbar'); ?>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</li>