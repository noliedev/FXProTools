<?php 
   /* get all the downlines of this user */

   $uid = get_current_user_id();
   if ( eps_is_admin() ){
    $uid = afl_root_user();
   }

   if ( isset($_GET['uid'])){
    $uid = $_GET['uid'];
   }
   // pr($uid);
   $query = array();
   $query['#select'] = 'wp_afl_user_downlines';
   $query['#join']  = array(
      'wp_users' => array(
        '#condition' => '`wp_users`.`ID`=`wp_afl_user_downlines`.`downline_user_id`'
      ),
      'wp_afl_user_genealogy' => array(
        '#condition' => '`wp_afl_user_genealogy`.`uid`=`wp_afl_user_downlines`.`downline_user_id`'
      ),
    );
   $query['#fields']  = array(
      'wp_users' => array(
        'display_name',
        'user_login',
        'ID'
      ),
      'wp_afl_user_downlines' => array(
        'downline_user_id',
        'uid',
        'relative_position',
        'level'
      ),
      'wp_afl_user_genealogy' => array(
        'parent_uid'
      )
    );
   $query['#where'] = array(
      '`wp_afl_user_downlines`.`uid`='.$uid.'',
      '`wp_afl_user_downlines`.`level`=1',
    );
   $query['#order_by'] = array(
      '`level`' => 'ASC'
    );
    $downlines = db_select($query, 'get_results');
    // pr($downlines);
    $tree = array();
    //get the downlines levels
    $levels = array();
    $positions = array();
    foreach ($downlines as $key => $row) {
      $tree[$row->downline_user_id] = $row;
      $level[$row->relative_position] = $row->downline_user_id;
      $positions[$row->parent_uid][$row->relative_position] = $row->downline_user_id;
    }
    $parent = afl_genealogy_node($uid);
    
    $this_user_downlines =  isset($positions[$uid])  ? $positions[$uid] : array();
    ksort($this_user_downlines);
    
    $plan_width = afl_variable_get('matrix_plan_width',3);

if (!empty($parent)) :
  ?>
<section class="genealogy-hierarchy">
        <div class="hv-container">
            <div class="hv-wrapper">
                <div class="hv-item">
                    <div class="hv-item-parent">
                        <div class="person">
                            <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'; ?>" alt="">
                            <p class="name">
                                <?= $parent->user_login.' ('.$parent->ID.')'; ?>
                            </p>
                        </div>
                    </div>
                    <!-- Check the users occure in all levels -->
                    <div class="hv-item-children">
                    <?php 
                    for ($i = 1; $i <= $plan_width; $i++) : 

                      if (isset($level[$i])) : ?>
                        <div class="hv-item-child">

                            <div class="hv-item">
                                  <div class="">
                                    <div class="person">
                                        <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'; ?>" alt="">
                                        <p class="name">
                                          <?= $tree[$level[$i]]->user_login.' ('.$tree[$level[$i]]->ID.')'; ?>
                                        </p>
                                      <span class="expand-tree" data-user-id ="<?= $level[$i];?>" onclick="expandToggleMatrixTree(this)">
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
                                  
                                  <div class="col-md-12">
                                    <div class="holding-user">
                                      <div class="">
                                         <div class="person">
                                            <span class="toggle-left-arrow"><i class="fa fa-caret-left fa-5x"></i></span>
                                              <div class="holding-toggle-user-image">
                                                <img src="http://woocommerce-plugin/wp-content/plugins/eps-affiliates/assets/images/no-user.png" alt="">
                                              </div>
                                            <span class="toggle-right-arrow"><i class="fa fa-caret-right fa-5x"></i></span>
                                          </div>
                                          <div>
                                            <button class="toggle-save-placement-button" onclick="_toggle_holding_node_place(this)">Save Placement</button>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                      <?php endif; ?>
                    <?php endfor;  ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style type="text/css">
      .toggle-left-arrow{float: left;cursor: pointer;}
      .toggle-right-arrow{float: right;cursor: pointer;}
      .holding-toggle-user-image{display: inline-block;padding: 2px;}
      .holding-toggle-user-image > img{height: 80px;
    border: 5px solid #ccc;
    border-radius: 50%;
    overflow: hidden;
    background-color: #ccc;}
    </style>
<?php else : ?>
    <div class="panel panel-default">
      <div class="panel-body">
        Unable to view genealogy.
      </div>
    </div>
<?php endif;
