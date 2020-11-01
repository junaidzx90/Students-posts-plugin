<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Postlink_Classroom
 * @subpackage Postlink_Classroom/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Postlink_Classroom
 * @subpackage Postlink_Classroom/admin
 * @author     Postlink <demo@gmail.com>
 */
class Postlink_Classroom_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

			// 4.6 and older
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);

		} else {

			// Add a filter to the wp 4.7 version attributes metabox
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);

		}
		// Add a filter to the save post to inject out template into the page cache
		add_filter(
			'wp_insert_post_data', 
			array( $this, 'register_project_templates' ) 
		);

		// Add a filter to the template include to determine if the page has our 
		// template assigned and return it's path
		add_filter(
			'template_include', 
			array( $this, 'view_project_template') 
		);

		// Add your templates to this array.
		$this->templates = array(
			'postlink-classroom-public-display.php' =>'Students page',
		);

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Postlink_Classroom_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Postlink_Classroom_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/postlink-classroom-admin.css', array(), $this->version, 'all' );
		

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Postlink_Classroom_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Postlink_Classroom_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'jquery', plugin_dir_url( __FILE__ ) . 'js/jquery.min.js', array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/postlink-classroom-admin.js', array( 'jquery' ), $this->version, true );
		wp_localize_script($this->plugin_name, '_ajax_url', array(
			'ajax_url' => admin_url('admin-ajax.php')
		));
	}


	// Make postlink_classroom options
	function postlink_classroom_opt()
	{
		add_menu_page( //Main menu register
			"Postlink Classroom", //page_title
			"Postlink Classroom", //menu title
			"manage_options", //capability
			"postlink-classroom-opt", //menu_slug
			array($this, "postlink_classroom_view"), //callback function
			'dashicons-schedule',
			65
		);
		add_submenu_page( //sub menu register
			"postlink-classroom-opt", //parent_slug
			"Students", //page title
			"Students", //menu title
			"manage_options", //capability
			"postlink-classroom-opt",  //menu-slug
			array($this, "postlink_classroom_view") //Callback function same with parent
		);
	}

	function postlink_classroom_view(){
		require_once MY_PLUGIN_PATH. 'admin/partials/postlink-classroom-admin-display.php';
	}
		/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
	
		// Retrieve the cache list. 
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		} 
	
		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');
	
		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );
	
		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );
	
		return $atts;
	
	}

	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {
		
		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta( 
			$post->ID, '_wp_page_template', true 
		)] ) ) {
			return $template;
		} 

		// Just changing the page template path
		// WordPress will now look for page templates in the subfolder 'templates',
		// instead of the root
		$file = MY_PLUGIN_PATH.'public/partials/'. get_post_meta( 
			$post->ID, '_wp_page_template', true 
		);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}
		// Return template
		return $template;
	}
// Configered students settings page
	function configured_students_page(){
		$perpage_post = $_POST['post_shows'];
		$user = $_POST['users'];

		global $wpdb; //Define wpdb global variable
		$postlinkClassroom = $wpdb->prefix . 'postlinkClassroom_v1'; //Define postlinkClassroom table with wp prefix

		$user_data= $wpdb->get_var("SELECT student_id FROM $postlinkClassroom");
		if($wpdb->num_rows>0){

			$wpdb->update( 
				$postlinkClassroom, 
				array(
					"post_per_page" => $perpage_post,
					"user_role" => "$user"
				),
				array("student_id" => $user_data), 
				array( "%d", "%s" ),
				array("%d")
			);
			die();
		}
		$wpdb->insert(
			$postlinkClassroom,
			array(
				"post_per_page" => $perpage_post,
				"user_role" => "$user",
			),
			array(
				"%d",
				"%s"
			)
		);
		die();
	}
}
