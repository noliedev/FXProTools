<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-marketing'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="fx-list-courses">
					<li class="list-item">
						<div class="left">
							<div class="box">
								<span class="sash">Active</span>
								<span class="number">01</span>
							</div>
						</div>
						<div class="right">
							<div class="row">
								<div class="col-md-12">
									<span class="title">Learn How To Use The Sales Funnels</span>
								</div>
								<div class="col-md-10">
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
									cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
									proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>	
								</div>
								<div class="col-md-2">
									<a href="<?php bloginfo('url');?>/product/course" class="btn btn-default block">Learn More</a>
								</div>
								<div class="col-md-12">
									<div class="progress">
									 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
											25%
									 	</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
				</ul>
				<br/>
				<div class="fx-header-title">
					<h1>Your Sales Funnels</h1>
					<p>Let us do most of the work for you</p>
				</div>
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								Funnel #1: Funel Page Title Goes Here
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<div class="fx-tabs-vertical marketing-funnels">
											<ul class="nav nav-tabs">
												<li class="active">
													<a href="#a" data-toggle="tab">
														<span class="block">Step 1</span>
														<span class="block">Capture</span>
														<small>Lead Gen</small>
													</a>
												</li>
												<li>
													<a href="#b" data-toggle="tab">
														<span class="block">Step 2</span>
														<span class="block">Landing</span>
														<small>Information</small>
													</a>
												</li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane tab-profile active" id="a">
													<div class="row">
														<div class="col-md-9">
															<div class="row">
																<div class="col-md-3">
																	<img src="http://via.placeholder.com/350x300" class="img-responsive">
																</div>
																<div class="col-md-9">
																	<div class="heading">
																		<h3 class="title">Capture Page</h3>
																		<p>Page Title Goes Here ...</p>
																	</div>
																	<ul class="social-media">
																		<li><a href="#" class="btn btn-default block text-center">Facebook</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Twitter</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Linkedin</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Google+</a></li>
																	</ul>
																	<div class="clearfix"></div>
																</div>
																<div class="clearfix"></div>
																<div class="col-md-12">
																	<hr/>
																	<div class="form-group url-group two">
																		<label>Share This URL:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Copy</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group one">
																		<label>Custom Video Embed:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Save</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group two">
																		<label>Custom Background Image:</label>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Upload</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Settings</label>
																	<hr class="m-xs"/>
																	<table>
																		<tr>
																			<td>Custom Video</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb1" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb1"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>IBO Exit Popup</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb2" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb2"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>Background</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb3" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb3"></label>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
															<br/>
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Views</label>
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<td>All</td>
																				<td>1000</td>
																			</tr>
																			<tr>
																				<td>Uniques</td>
																				<td>890</td>
																			</tr>
																		</tbody>
																	</table>
																	<a href="#" class="btn btn-default block text-center">View Stats</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="tab-pane tab-profile" id="b">
													<div class="row">
														<div class="col-md-9">
															<div class="row">
																<div class="col-md-3">
																	<img src="http://via.placeholder.com/350x300" class="img-responsive">
																</div>
																<div class="col-md-9">
																	<div class="heading">
																		<h3 class="title">Capture Page</h3>
																		<p>Page Title Goes Here ...</p>
																	</div>
																	<ul class="social-media">
																		<li><a href="#" class="btn btn-default block text-center">Facebook</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Twitter</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Linkedin</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Google+</a></li>
																	</ul>
																	<div class="clearfix"></div>
																</div>
																<div class="clearfix"></div>
																<div class="col-md-12">
																	<hr/>
																	<div class="form-group url-group two">
																		<label>Share This URL:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Copy</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group one">
																		<label>Custom Video Embed:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Save</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group two">
																		<label>Custom Background Image:</label>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Upload</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Settings</label>
																	<hr class="m-xs"/>
																	<table>
																		<tr>
																			<td>Custom Video</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb4" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb4"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>IBO Exit Popup</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb5" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb5"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>Background</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb6" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb6"></label>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
															<br/>
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Views</label>
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<td>All</td>
																				<td>1000</td>
																			</tr>
																			<tr>
																				<td>Uniques</td>
																				<td>890</td>
																			</tr>
																		</tbody>
																	</table>
																	<a href="#" class="btn btn-default block text-center">View Stats</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								Funnel #2: Funel Page Title Goes Here
								</a>
							</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<div class="fx-tabs-vertical marketing-funnels">
											<ul class="nav nav-tabs">
												<li class="active">
													<a href="#a" data-toggle="tab">
														<span class="block">Step 1</span>
														<span class="block">Capture</span>
														<small>Lead Gen</small>
													</a>
												</li>
												<li>
													<a href="#b" data-toggle="tab">
														<span class="block">Step 2</span>
														<span class="block">Landing</span>
														<small>Information</small>
													</a>
												</li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane tab-profile active" id="a">
													<div class="row">
														<div class="col-md-9">
															<div class="row">
																<div class="col-md-3">
																	<img src="http://via.placeholder.com/350x300" class="img-responsive">
																</div>
																<div class="col-md-9">
																	<div class="heading">
																		<h3 class="title">Capture Page</h3>
																		<p>Page Title Goes Here ...</p>
																	</div>
																	<ul class="social-media">
																		<li><a href="#" class="btn btn-default block text-center">Facebook</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Twitter</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Linkedin</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Google+</a></li>
																	</ul>
																	<div class="clearfix"></div>
																</div>
																<div class="clearfix"></div>
																<div class="col-md-12">
																	<hr/>
																	<div class="form-group url-group two">
																		<label>Share This URL:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Copy</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group one">
																		<label>Custom Video Embed:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Save</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group two">
																		<label>Custom Background Image:</label>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Upload</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Settings</label>
																	<hr class="m-xs"/>
																	<table>
																		<tr>
																			<td>Custom Video</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb7" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb7"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>IBO Exit Popup</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb8" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb8"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>Background</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb9" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb9"></label>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
															<br/>
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Views</label>
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<td>All</td>
																				<td>1000</td>
																			</tr>
																			<tr>
																				<td>Uniques</td>
																				<td>890</td>
																			</tr>
																		</tbody>
																	</table>
																	<a href="#" class="btn btn-default block text-center">View Stats</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="tab-pane tab-profile" id="b">
													<div class="row">
														<div class="col-md-9">
															<div class="row">
																<div class="col-md-3">
																	<img src="http://via.placeholder.com/350x300" class="img-responsive">
																</div>
																<div class="col-md-9">
																	<div class="heading">
																		<h3 class="title">Capture Page</h3>
																		<p>Page Title Goes Here ...</p>
																	</div>
																	<ul class="social-media">
																		<li><a href="#" class="btn btn-default block text-center">Facebook</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Twitter</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Linkedin</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Google+</a></li>
																	</ul>
																	<div class="clearfix"></div>
																</div>
																<div class="clearfix"></div>
																<div class="col-md-12">
																	<hr/>
																	<div class="form-group url-group two">
																		<label>Share This URL:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Copy</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group one">
																		<label>Custom Video Embed:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Save</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group two">
																		<label>Custom Background Image:</label>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Upload</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Settings</label>
																	<hr class="m-xs"/>
																	<table>
																		<tr>
																			<td>Custom Video</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb10" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb10"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>IBO Exit Popup</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb11" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb11"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>Background</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb12" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb12"></label>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
															<br/>
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Views</label>
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<td>All</td>
																				<td>1000</td>
																			</tr>
																			<tr>
																				<td>Uniques</td>
																				<td>890</td>
																			</tr>
																		</tbody>
																	</table>
																	<a href="#" class="btn btn-default block text-center">View Stats</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingThree">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
								Funnel #3: Funel Page Title Goes Here
								</a>
							</h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<div class="fx-tabs-vertical marketing-funnels">
											<ul class="nav nav-tabs">
												<li class="active">
													<a href="#a" data-toggle="tab">
														<span class="block">Step 1</span>
														<span class="block">Capture</span>
														<small>Lead Gen</small>
													</a>
												</li>
												<li>
													<a href="#b" data-toggle="tab">
														<span class="block">Step 2</span>
														<span class="block">Landing</span>
														<small>Information</small>
													</a>
												</li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane tab-profile active" id="a">
													<div class="row">
														<div class="col-md-9">
															<div class="row">
																<div class="col-md-3">
																	<img src="http://via.placeholder.com/350x300" class="img-responsive">
																</div>
																<div class="col-md-9">
																	<div class="heading">
																		<h3 class="title">Capture Page</h3>
																		<p>Page Title Goes Here ...</p>
																	</div>
																	<ul class="social-media">
																		<li><a href="#" class="btn btn-default block text-center">Facebook</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Twitter</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Linkedin</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Google+</a></li>
																	</ul>
																	<div class="clearfix"></div>
																</div>
																<div class="clearfix"></div>
																<div class="col-md-12">
																	<hr/>
																	<div class="form-group url-group two">
																		<label>Share This URL:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Copy</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group one">
																		<label>Custom Video Embed:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Save</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group two">
																		<label>Custom Background Image:</label>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Upload</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Settings</label>
																	<hr class="m-xs"/>
																	<table>
																		<tr>
																			<td>Custom Video</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb13" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb13"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>IBO Exit Popup</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb14" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb14"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>Background</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb15" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb15"></label>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
															<br/>
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Views</label>
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<td>All</td>
																				<td>1000</td>
																			</tr>
																			<tr>
																				<td>Uniques</td>
																				<td>890</td>
																			</tr>
																		</tbody>
																	</table>
																	<a href="#" class="btn btn-default block text-center">View Stats</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="tab-pane tab-profile" id="b">
													<div class="row">
														<div class="col-md-9">
															<div class="row">
																<div class="col-md-3">
																	<img src="http://via.placeholder.com/350x300" class="img-responsive">
																</div>
																<div class="col-md-9">
																	<div class="heading">
																		<h3 class="title">Capture Page</h3>
																		<p>Page Title Goes Here ...</p>
																	</div>
																	<ul class="social-media">
																		<li><a href="#" class="btn btn-default block text-center">Facebook</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Twitter</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Linkedin</a></li>
																		<li><a href="#" class="btn btn-default block text-center">Google+</a></li>
																	</ul>
																	<div class="clearfix"></div>
																</div>
																<div class="clearfix"></div>
																<div class="col-md-12">
																	<hr/>
																	<div class="form-group url-group two">
																		<label>Share This URL:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Copy</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group one">
																		<label>Custom Video Embed:</label>
																		<div class="clearfix"></div>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Save</a>
																		<div class="clearfix"></div>
																	</div>
																	<div class="form-group url-group two">
																		<label>Custom Background Image:</label>
																		<input type="text" class="form-control" value="">
																		<a href="#" class="btn btn-default">Upload</a>
																		<a href="#" class="btn btn-default">Preview</a>
																		<div class="clearfix"></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Settings</label>
																	<hr class="m-xs"/>
																	<table>
																		<tr>
																			<td>Custom Video</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb16" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb16"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>IBO Exit Popup</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb17" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb17"></label>
																			</td>
																		</tr>
																		<tr>
																			<td>Background</td>
																			<td class="toggle-action">
																				<input class="fx-slide-toggle" id="cb18" type="checkbox">
																				<label class="fx-slide-toggle-btn" for="cb18"></label>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
															<br/>
															<div class="panel panel-default">
																<div class="panel-body">
																	<label>Page Views</label>
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<td>All</td>
																				<td>1000</td>
																			</tr>
																			<tr>
																				<td>Uniques</td>
																				<td>890</td>
																			</tr>
																		</tbody>
																	</table>
																	<a href="#" class="btn btn-default block text-center">View Stats</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>