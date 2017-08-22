<nav class="navbar fx-navbar-sub">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="fx-nav-options">
					<li class="dashboard"><a href="<?php bloginfo('url');?>/wallet" title="Marketing Icon"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
					
					<?php $customer = false; ?>
					<?php if ($customer): ?>
						<li><a href="<?php bloginfo('url');?>/wallet/referred-members">Referred Members</a></li>
					<?php else: ?>
						<li><a href="<?php bloginfo('url');?>/wallet">E-Wallet Setup</a></li>
					<?php endif; ?>
					
					<li><a href="<?php bloginfo('url');?>/wallet/summary">E-Wallet Summary</a></li>
					<li><a href="<?php bloginfo('url');?>/wallet/bonuses">Bonuses</a></li>
				</ul>
			</div>
		</div>
	</div>
</nav>