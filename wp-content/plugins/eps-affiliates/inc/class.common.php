<?php 
/*
 * ----------------------------------------------------------------
 * Include common functionalities
 * ----------------------------------------------------------------
 *
*/
class Eps_affiliates_common {
	/**
	 * -------------------------------------------------------------------------
	 * Eps_affiliates instance.
	 * -------------------------------------------------------------------------
	 *
	 * @access private
	 * @since  1.0
	 * @var    Eps_plan The one true Eps_plan
	 *
	*/
		private static $instance;
		public function __construct(){
			// $this = new Eps_affiliates_common;
			$this->_load_common_scritps();
			$this->_load_common_styles();
		}
	/*
 	 * ----------------------------------------------------------------
	 * Load Common scripts for plugin
 	 * ----------------------------------------------------------------
	*/
		public function _load_common_scritps() {
			wp_enqueue_script( 'jquery-js', EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js');
			wp_enqueue_script( 'bootstrap-js', EPSAFFILIATE_PLUGIN_ASSETS.'js/bootstrap.min.js');
			wp_enqueue_script( 'jquery-ui', EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js');
			wp_enqueue_script( 'common-js', EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js');

		} 
	/*
 	 * ----------------------------------------------------------------
	 * Load Common styles for plugin
 	 * ----------------------------------------------------------------
	*/
		public function _load_common_styles() {
			wp_enqueue_style( 'bootstrap-css', EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css');
			wp_enqueue_style( 'jquery-ui', EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.css');
			wp_enqueue_style( 'developer', EPSAFFILIATE_PLUGIN_ASSETS.'css/developer.css');
		} 
}

$common_include = new Eps_affiliates_common();

/*
 * -------------------------------------------------------------------
 * Generate form based on the input array gives to it
 * -------------------------------------------------------------------
*/
	function afl_render_form($form = array()){
		$html 			= '';
		$elements 	= $form;
		$action 		= isset($elements['#action']) ? $elements['#action'] : '#';
		$method 		= isset($elements['#method']) ? $elements['#method'] : 'post';
		$name 			= isset($elements['#name']) 	? $elements['#name'] : '';
		$prefix 		= isset($elements['#prefix']) ? $elements['#prefix'] : '' ;
		$suffix 		= isset($elements['#suffix']) ? $elements['#suffix'] : '' ;
		$attributes = isset($elements['#attributes']) ? $elements['#attributes'] : array() ;
		$classes 		=	''; 
		
		if (isset($attributes['class'])) {
			foreach ($attributes['class'] as $attr_class) {
				$classes .= $attr_class;
			}
		}

		unset($elements['#action']);
		unset($elements['#method']);
		unset($elements['#name']);
		unset($elements['#prefix']);
		unset($elements['#suffix']);
		unset($elements['#attributes']);

		if (!empty($prefix))
			$html .= $prefix;
		if ($suffix)
			$html .= $suffix;

		$html .= '<form action="' .$action. '"" method="'.$name.'" id="" accept-charset="UTF-8" class="'.$classes.'"> ';
		foreach ($elements as $key => $element) {
			if (isset($element['#prefix'])) {
				$html .= $element['#prefix'];
			}
			
			$html .= html_input_render($element,$key,$attributes);

			if (isset($element['#suffix'])) {
				$html .= $element['#suffix'];
			}
		}

		return $html;
	}
/*
 * -------------------------------------------------------------------
 * Render Table
 * -------------------------------------------------------------------
*/
	function afl_render_table($table = array()) {
		$table_data = $table;
		$table_html = '';

		$name 			= isset($table_data['#name']) 			? $table_data['#name'] : '';
		$title 			= isset($table_data['#title']) 			? $table_data['#title'] : '';
		$prefix 		= isset($table_data['#prefix']) 		? $table_data['#prefix'] : '' ;
		$suffix 		= isset($table_data['#suffix']) 		? $table_data['#suffix'] : '' ;
		$attributes = isset($table_data['#attributes']) ? $table_data['#attributes'] : array() ;
		$header 		= isset($table_data['#header']) 		? $table_data['#header'] : array() ;
		$rows 			= isset($table_data['#rows']) 			? $table_data['#rows'] : array() ;
		$classes 		=	''; 


		//add class if exist
		if (isset($attributes['class'])) {
			foreach ($attributes['class'] as $attr_class) {
				$classes .= ' '.$attr_class;
			}
		}
		//add prefix 
		if (!empty($prefix))
			$table_html .= $prefix;
		//add title
		if ($title) 
			$table_html .= $title;
		//table starts here
		$table_html 	.= '<table class="'.$classes.'">';

		/* -----------table head :  Begin	----------*/
		$table_html 	.= '<thead>';
			if (!empty($header)) {	
				$table_html .= '<tr>';
				foreach ($header as $head) {
					$table_html .= '<th>'.$head.'</th>';				
				}
				$table_html .= '<tr>';
			}
		$table_html .= '</thead>';
		/* -----------table head :  End	----------*/

		/* -----------rendering rows :  Begin	----------*/
			if (!empty($rows)) {
				foreach ($rows as $key => $row) {
					$table_html .= '<tr>';
					if (is_array($row)) {
						foreach ($row as $key1 =>$element) {
							$table_html .= '<td>';
							$table_html .= html_input_render($element, $key1);
							$table_html .= '</td>';
						}
					}
					$table_html .= '<tr>';
				}
			}
		/* -----------rendering rows :  End	----------*/
		$table_html .= '</table>';

		//append suffix
		if (isset($table['#suffix'])) {
			$table_html .= $table['#suffix'];
		}

		return $table_html;
	}
/*
 * -------------------------------------------------------------------
 * Render input elements 
 * -------------------------------------------------------------------
*/
	function html_input_render($element, $key, $attributes = array()) {
		$element_type = '';
		$class 				= '';
		$attributes 	= isset($element['#attributes'])  ? $element['#attributes']: array();
		if (!empty($attributes['class'])) {
			foreach ($attributes['class'] as $attr_class) {
				$class .= ' '.$attr_class;
			}
		}
		
		$html 	= '';
		if (is_array($element)) {
			if (isset($element['#type'])) {
				$element_type = $element['#type'];
			}
		}
		//append prefix
		if (isset($element['#preffix'])) {
			$html .= $element['#preffix'];
		}

		switch ($element_type) {
			case 'text':
				$deflt = isset($element['#default_value']) ? $element['#default_value'] : '';
				$html .= '<label for="'.str_replace('_','-',$key).'">';
				$html .= isset($element['#title']) ? $element['#title'] : '';
				$html .= '</label>';
				$html .= '<input type = "text" name = "'.$key.'" id="'.str_replace('_','-',$key).'" class="'.$class.'" value="'.$deflt.'">';	
			break;
			case 'checkbox':
				$checked = (!empty($element['#default_value']) && isset($element['#default_value'])) ? TRUE : FALSE ;
				$html .= '<label for="'.str_replace('_','-',$key).'">';
				$html .= isset($element['#title']) ? $element['#title'] : '';
				$html .= '</label>';
				if ($checked) {
					$html .= '<input type = "checkbox" name = "'.$key.'" id="'.str_replace('_','-',$key).'" class="'.$class.'" checked="'.$checked.'" >';	
				}else {
					$html .= '<input type = "checkbox" name = "'.$key.'" id="'.str_replace('_','-',$key).'" class="'.$class.'">';	
				}
			break;
			case 'markup':
				$html = '<div class="'.$class.'">'.$element['#markup'].'</div>';	
			break;
			case 'submit':
				$html = '<input type="submit" class="'.$class.'" value="'.$element['#value'].'">';	
			break;
			case 'label':
				$html = '<label class="'.$class.'">'.$element['#title'].'</label>';	
			break;
			case 'date_time':
				$deflt = isset($element['#default_value']) ? $element['#default_value'] : '';
				$html .= '<label for="'.str_replace('_','-',$key).'">';
				$html .= isset($element['#title']) ? $element['#title'] : '';
				$html .= '</label>';
				$html .= '<input type = "text" name = "'.$key.'" id="'.str_replace('_','-',$key).'" class=" date_time_picker '.$class.'" value="'.$deflt.'">';	
			break;
		}

		//append suffix
		if (isset($element['#suffix'])) {
			$html .= $element['#suffix'];
		}

		return $html;
	}
/*
 * ----------------------------------------------------------------------------------
 * Open a form tag
 * ---------------------------------------------------------------------------------- 
*/
function afl_form_open($action = '',$method='GET', $attributes = array()){
	$html_tag_form = '';
	$cls 			=  isset($attributes['class']) ? $attributes['class'] : '';
	$id 			=  isset($attributes['id']) ? $attributes['id'] : '';

	$html_tag_form .= '<form action="'.$action.'" class="'.$cls.'" id="'.$id.'" method="'.$method.'">';

	return $html_tag_form;
}
/*
 * ----------------------------------------------------------------------------------
 * Close a form tag
 * ---------------------------------------------------------------------------------- 
*/
function afl_form_close(){
	return '</form>';
}
/*
 * ----------------------------------------------------------------------------------
 * Create submit button
 * ---------------------------------------------------------------------------------- 
*/
function afl_input_button($type = '',$value = 'Submit',$name = 'submit',$attributes = array()){
	$html_tag_button = '';
	$cls 			=  isset($attributes['class']) ? $attributes['class'] : '';
	$id 			=  isset($attributes['id']) ? $attributes['id'] : '';

	$html_tag_button = '<button type="'.$type.'" class="'.$cls.'" id="'.$id.'">'.$value.'</button>';
	if ($type= 'submit') {
		$html_tag_button = '<input type="submit" class="'.$cls.'" name="submit" value="'.$value.'" id="'.$id.'">';	
	}

	return $html_tag_button;

}
/*
 * ----------------------------------------------------------------------------------
 * AFL variable set
 * ---------------------------------------------------------------------------------- 
*/
if(!function_exists('afl_variable_set')){
	function afl_variable_set($name = '', $value = '',$afl_merchant_id ='', $afl_project_name = '' ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'afl_variable';
	  $query 		= 'SELECT * FROM '.$table_name.' WHERE name = %s';
	  $row 			= $wpdb->get_row( 
                    $wpdb->prepare($query,$name) 
                 );
	  //if name already exist update, else insert
		if (!empty($row)){
			$wpdb->update( 
					$table_name, 
					array( 
						'value' => maybe_serialize($value) 
					),
					array('name' => $name)				
				);	
		} else {
			$wpdb->insert( 
					$table_name, 
					array( 
						'name'	=> $name, 
						'value' => maybe_serialize($value) 
					)					
				);	
		}
  }
}
/*
 * ----------------------------------------------------------------------------------
 * AFL variable get
 * ---------------------------------------------------------------------------------- 
*/
if(!function_exists('afl_variable_get')){
	function afl_variable_get($name = '', $default = '',$afl_merchant_id ='', $afl_project_name = '') {
		global $wpdb;
		$table_name = $wpdb->prefix . 'afl_variable';
	  $query 		= 'SELECT * FROM '.$table_name.' WHERE name = %s';
	  $row 			= $wpdb->get_row( 
                    $wpdb->prepare($query,$name) 
                 );
		if (!empty($row))
			return maybe_unserialize($row->value);
		else
			return $default;
	}
}
/*
 * -----------------------------------------------------------
 * Wordpress set messages 
 * -----------------------------------------------------------
*/
function wp_set_message($msg = '', $action = 'success'){
	$alert = '';
	$alert .= '<div class="alert alert-'.$action.'" role="alert">';
  $alert .= $msg;
	$alert .='</div>';

	return $alert;
}
/**
 * -----------------------------------------------------------
 * Get user data
 * -----------------------------------------------------------
 * @param $uid : user id
 *
*/
function afl_user_data($uid = ''){
	if ($uid == '') {
		$uid = get_current_user_id();
	}

	$user = get_userdata($uid);

	return $user->data;
}

/**
 * -----------------------------------------------------------
 * Get user roles
 * -----------------------------------------------------------
 * @param $uid : user id
 *
*/
function afl_user_roles($uid = ''){
	if ($uid == '') {
		$uid = get_current_user_id();
	}

	$user = get_userdata($uid);

	return $user->roles;
}


/**
 * ------------------------------------------------------------------------------
 * Locate template.
 * ------------------------------------------------------------------------------
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/woocommerce-plugin-templates/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/woocommerce-plugin-templates/templates/$template_name.
 *
 * ------------------------------------------------------------------------------
 * @since 1.0
 *
 * @param 	string 	$template_name			Template to load.
 * @param 	string 	$string $template_path	Path to templates.
 * @param 	string	$default_path			Default path to template files.
 * @return 	string 							Path to the template file.
 * ------------------------------------------------------------------------------
 */
function afl_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	// Set variable to search in woocommerce-plugin-templates folder of theme.
	if ( ! $template_path ) :
		$template_path = 'woocommerce-plugin-templates/';
	endif;
	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = EPSAFFILIATE_PLUGIN_DIR . 'inc/templates/'; // Path to the template folder
	endif;
	// Search template file in theme folder.
	$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );
	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;
	return apply_filters( 'afl_locate_template', $template, $template_name, $template_path, $default_path );
}

/**
 * ------------------------------------------------------------------------------
 * Get template.
 * ------------------------------------------------------------------------------
 *
 * Search for the template and include the file.
 * ------------------------------------------------------------------------------
 *
 * @since 1.0
 *
 * @see afl_locate_template()
 *
 * @param string 	$template_name			Template to load.
 * @param array 	$args					Args passed for the template file.
 * @param string 	$string $template_path	Path to templates.
 * @param string	$default_path			Default path to template files.
 * ------------------------------------------------------------------------------
 */
function afl_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;
	$template_file = afl_locate_template( $template_name, $tempate_path, $default_path );
	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;
	include $template_file;
}
/**
 * ------------------------------------------------------------------------------
 * Render the admin dashboard 
 * ------------------------------------------------------------------------------
*/
function afl_eps_afiliate_dashboard_shortcode(){
		return afl_get_template( 'eps-affiliate-dashboard.php' );
}
	add_shortcode('afl_affiliates', 'afl_eps_afiliate_dashboard_shortcode');
/**
 * ------------------------------------------------------------------------------
 * Create new widget
 * ------------------------------------------------------------------------------
*/
	function eps_affiliates_dashboard_menu_widget() {
		register_sidebar( array(
			'name'          => __( 'AFL Dashboard widget', 'AFL Dashboard widget' ),
			'id'            => 'afl-dashboard-menus',
			'description'   => __( 'Add the widget here that will display in the admin', 'AFL Dashboard widget' ),
		) );
	}
	add_action( 'widgets_init', 'eps_affiliates_dashboard_menu_widget' );

/**
 * ------------------------------------------------------------------------------
 * Render left side dropdown menu
 * ------------------------------------------------------------------------------
*/
	function render_dropdown_menu ($menu = array()) {
		$html_tag 	= '';
		$prefix 	 	= isset($menu['#prefix']) 		? $menu['#prefix'] : '';
		$suffix 	 	= isset($menu['#suffix']) 		? $menu['#suffix'] : '';
		$attributes	= isset($menu['#attributes']) ? $menu['#attributes'] : '';
		$class 			=	'';

		if (!empty($attributes['class'])) {
			foreach ($attributes['class'] as $attr_class) {
				$class .= ' '.$attr_class;
			}
		}
		
		unset($menu['#prefix']);
		unset($menu['#suffix']);

		$html_tag .= '<nav class="navbar navbar-default " role="navigation">';
		$html_tag .= '<ul class="nav navbar-nav menu-dropdown-ul '.$class.'">';

		if (!empty($menu)) {

			foreach ($menu as $key => $element) {
				if (!empty($element['#childrens']) ){
					$html_tag .= '<li class="panel panel-default" id="dropdown">';
					$html_tag .= '<a data-toggle="collapse" href="#dropdown-lvl1">';
					$html_tag .= isset($element['#icon']) ? $element['#icon'] : '';
					$html_tag .= isset($element['#title']) ? $element['#title'] : '';
					$html_tag .= '<span class="caret"></span>';
					$html_tag .= '</a>';
					$html_tag .= '<div id="dropdown-lvl1" class="panel-collapse collapse">';
					$html_tag .= '<div class="panel-body">';
					$html_tag .= '<ul class="nav navbar-nav">';
					
					foreach ($element['#childrens'] as $submenu_key => $submenu) : 
						$subattributes	= isset($submenu['#attributes']) ? $submenu['#attributes'] : '';
						$submenu_cls 		= '';
						if (!empty($subattributes['class'])) {
							foreach ($subattributes['class'] as $attr_class) {
								$submenu_cls .= ' '.$attr_class;
							}
						}
						$link 		 = '#';
						if ($submenu['#link'])
							$link = $submenu['#link'];
						$html_tag .= '<li class="'.$submenu_cls.'">';
						$html_tag .= '<a href="'.$link.'">';
						$html_tag .= isset($submenu['#icon']) ? $submenu['#icon'] : '';
						$html_tag .= isset($submenu['#title']) ? $submenu['#title'] : '';
						$html_tag .= '</a>';
						$html_tag .= '</li>';
					endforeach;

					$html_tag .= '</ul>';
					$html_tag .= '</div>';
					$html_tag .= '</div>';
					$html_tag .= '</li>';
				}else{
					$link 		 = '#';
					if ($submenu['#link'])
						$link = $submenu['#link'];

					$html_tag .= '<li>';
					$html_tag .= '<a href="'.$link.'">';
					$html_tag .= isset($element['#icon']) ? $element['#icon'] : '';
					$html_tag .= isset($element['#title']) ? $element['#title'] : '';
					$html_tag .= '</a>';
					$html_tag .= '</li>';
				}

			}
		}
		$html_tag .= '</ui>';
		$html_tag .= '</nav>';
		return $html_tag;
	}