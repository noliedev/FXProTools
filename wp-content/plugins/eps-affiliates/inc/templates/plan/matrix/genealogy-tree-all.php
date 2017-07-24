<?php 
   /* get all the downlines of this user */
   $uid = get_current_user_id();
   $query = array();
   $query['#select'] = 'wp_afl_user_downlines';
   $query['#join']  = array(
      'wp_users' => array(
        '#condition' => '`wp_users`.`ID`=`wp_afl_user_downlines`.`downline_user_id`'
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

    $tree = array();
    //get the downlines levels
    $levels = array();
    foreach ($downlines as $key => $row) {
      $tree[$row->downline_user_id] = $row;
      $level[$row->relative_position] = $row->downline_user_id;
    }
    $parent = afl_genealogy_node($uid);
    // pr($tree);
    $plan_width = afl_variable_get('matrix_plan_width',3);
if (!empty($parent)) :
  ?>
<section class="genealogy-hierarchy">
        <div class="hv-container">
            <div class="hv-wrapper">

                
                <div class="hv-item">

                    <div class="hv-item-parent">
                        <div class="person">
                            <img src="https://pbs.twimg.com/profile_images/762654833455366144/QqQhkuK5.jpg" alt="">
                            <p class="name">
                                <?= $parent->display_name; ?>
                            </p>
                        </div>
                    </div>
                    <?php if (!empty($tree)) : ?>
                    <div class="hv-item-children">
                    <?php 
                      foreach ($tree as $key => $row) { ?>
                        <div class="hv-item-child">

                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <div class="person">
                                        <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                        <p class="name">
                                                 Wilner <b>/ Creative Director</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>

                                        <!-- <div class="person">
                                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="">
                                            <p class="name">
                                                Anne Potts <b>/ UI Designer</b>
                                            </p>
                                        </div> -->
                                    </div>


                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>

                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                     <?php } ?>
                        <div class="hv-item-child">

                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <div class="person">
                                        <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                        <p class="name">
                                                 Wilner <b>/ Creative Director</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>

                                        <!-- <div class="person">
                                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="">
                                            <p class="name">
                                                Anne Potts <b>/ UI Designer</b>
                                            </p>
                                        </div> -->
                                    </div>


                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>

                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="hv-item-child">
                            <!-- Key component -->
                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <div class="person">
                                        <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="">
                                        <p class="name">
                                            Gordon Clark <b>/ Senior Developer</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                       <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>


                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>

                                    <div class="hv-item-child">
                                       <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="hv-item-child">
                            <!-- Key component -->
                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <div class="person">
                                        <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="">
                                        <p class="name">
                                            Gordon Clark <b>/ Senior Developer</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>

                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>

                                    <div class="hv-item-child">
                                        <div class="hv-item-parent">
                                            <div class="person">
                                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                                <p class="name">
                                                         Wilner <b>/ Creative Director</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hv-item-children">
                                           <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                          <div class="hv-item-child">
                                              <div class="person">
                                                  <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="">
                                                  <p class="name">
                                                      Dan Butler <b>/ UI Designer</b>
                                                  </p>
                                              </div>
                                          </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                  <?php endif; ?>

                </div>

            </div>
        </div>
    </section>
<?php endif; ?>
