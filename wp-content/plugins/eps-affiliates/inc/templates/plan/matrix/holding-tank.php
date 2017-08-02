<?php 
   /* get all the downlines of this user */
    $uid = get_current_user_id();
    if (current_user_can('administrator')) {
      $uid = afl_root_user();
    }
    $query = array();
    $query['#select'] = 'wp_afl_user_holding_tank';
    $query['#join']  = array(
      'wp_users' => array(
        '#condition' => '`wp_users`.`ID`=`wp_afl_user_holding_tank`.`uid`'
      ),
    );
    $query['#fields']  = array(
      'wp_users' => array(
        'display_name'
      ),
      'wp_afl_user_holding_tank' => array(
        'parent_uid','uid','created','day_remains'
      )
    );
   	$query['#where'] = array(
      '`wp_afl_user_holding_tank`.`referrer_uid`='.$uid.'',
    );
   	$query['#order_by'] = array(
      '`level`' => 'ASC'
    );
    
    $tank_users = db_select($query, 'get_results');
    
if ( $tank_users ) : ?>
  
	<section class="holding-tank-warpper">
		<div class="holding-tank-wrapper">
			<div class="holding-tank-profiles">
				<ul class="row">
					<?php foreach ($tank_users as $key => $value) : ?>
							<li class="col-md-2 col-sm-3" data-user-id = "<?=$value->uid;?>">
					      <div class="person">
	                <img src="http://woocommerce-plugin/wp-content/plugins/eps-affiliates/assets/images/avathar.png" alt="">
		              <p class="name"><?= $value->display_name; ?></p>
		              <span class=""><?= $value->day_remains;?> Day remains</span>
	              </div>
					    </li>
					<?php endforeach; ?>
				    
				</ul>
			 </div>
		</div>
	</section>
	
	<div class="modal fade" id="holding-tank-change-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">User Placement</h5>
      </div>
      <div class="modal-body">
        <input type="hidden" name="" id="current-user-id" value="<?= $uid;?>">
        <input type="hidden" name="" id="seleted-user-id" value="">

  			<div class="form-group row">
  				<label for="choose-parent" class="form-label">Choose Parent</label>
  				<input name="choose_parent" id="choose-parent" data-path="users_auto_complete" class="auto_complete form-control " value="" type="text">
  			</div>
        
        <div class="form-group row" id="available-free-spaces">

        </div>
  			

        <div class="progress-outer"><div class="progress"></div></div>
        
        <div class="form-group row">
          <span class="notification"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="place-user">Place user</button>
      </div>
    </div>
  </div>
</div>



<?php  else : ?>
	<div class="panel panel-default">
		<div class="panel-body">
			No users currently in your Holding Tank
		</div>
	</div>

<?php endif; ?>