<?php 
   /* get all the downlines of this user */

    if (isset($_POST['uid']))  {
     $uid = $_POST['uid'];
     $query = array();
     $query['#select'] = _table_name('afl_unilevel_user_downlines');
     $query['#join']  = array(
        _table_name('users') => array(
          '#condition' => '`wp_users`.`ID`=`'._table_name('afl_unilevel_user_downlines').'`.`downline_user_id`'
        )
      );
     $query['#where'] = array(
        '`'._table_name('afl_unilevel_user_downlines').'`.`uid`='.$uid.'',
        '`'._table_name('afl_unilevel_user_downlines').'`.`level`=1',
      );
     $query['#order_by'] = array(
        '`level`' => 'ASC'
      );
      $downlines = db_select($query, 'get_results');

      $tree = array();
      //get the downlines levels
      $levels = array();
      foreach ($downlines as $key => $row) {
        $tree[$row->downline_user_id] = $row;
        $level[$row->relative_position] = $row->downline_user_id;
      }
      $parent = afl_genealogy_node($uid,'unilevel');

      $plan_width = afl_variable_get('matrix_plan_width',3);
  if (!empty($parent)) :
  ?>

  <div class="hv-item-children">
  <?php 
  for ($i = 1; $i <= $plan_width; $i++) : 

    if (isset($level[$i])) : ?>
      <div class="hv-item-child">

          <div class="hv-item">
                <div class="">
                  <div class="person">
                      <?php 
                        $user_roles = afl_user_roles($tree[$level[$i]]->ID); 
                        if ( array_key_exists('afl_customer', $user_roles)) {
                          echo '<img src="'.EPSAFFILIATE_PLUGIN_ASSETS.'images/customer.png'.'" alt="">';
                        } else 
                          echo '<img src="'.EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'.'" alt="">';
                      ?>
                      <!-- <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'; ?>" alt=""> -->
                      <p class="name">
                        <?= $tree[$level[$i]]->user_login.' ('.$tree[$level[$i]]->ID.')'; ?>
                      </p>
                    <span class="expand-tree" data-user-id ="<?= $level[$i];?>" onclick="expandUnilevelTree(this)">
                      <i class="fa fa-plus-circle fa-2x"></i>
                    </span>
                  </div>
                </div>
              <!-- check he has downlines -->
              <div class="append-child-<?= $level[$i];?>">
                
              </div>
              

          </div>
      </div>
    <?php else : ?>
        <div class="hv-item-child">

          <div class="hv-item">
                <div class="">
                  <div class="person">
                      <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/no-user.png'; ?>" alt="">
                      <p class="name">
                        No user
                      </p>
                  </div>
                </div>
          </div>
      </div>
    <?php endif; ?>
  <?php endfor;  ?>
  </div>

<?php
endif;
 } 
 die();
