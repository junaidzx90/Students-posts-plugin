<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Postlink_Classroom
 * @subpackage Postlink_Classroom/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Postlink_Classroom
 * @subpackage Postlink_Classroom/public
 * @author     Postlink <demo@gmail.com>
 */
class Postlink_Classroom_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		if (is_page_template('postlink-classroom-public-display.php' )) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/postlink-classroom-public.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		if (is_page_template('postlink-classroom-public-display.php' )) {
			wp_enqueue_script( 'jquery', plugin_dir_url( __FILE__ ) . 'js/jquery.min.js', array(), $this->version, true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/postlink-classroom-public.js', array( 'jquery' ), $this->version, true );
			wp_localize_script($this->plugin_name, '_ajax_url', array(
				'ajax_url' => admin_url('admin-ajax.php')
			));
		}
	}


	function get_post_perpage(){//This function for checking per-page posts
		global $wpdb; //Define wpdb global variable
		$postlinkClassroom = $wpdb->prefix . 'postlinkClassroom_v1'; //Define postlinkClassroom table with wp prefix

		$post_per = $wpdb->get_var("SELECT post_per_page FROM $postlinkClassroom");
		if ($wpdb->num_rows > 0) {
			return $post_per;
			wp_die();
		}else{
			return 5;
			wp_die();
		}
	}

	function get_current_user(){//This function for checking user role
		global $wpdb; //Define wpdb global variable
		$postlinkClassroom = $wpdb->prefix . 'postlinkClassroom_v1'; //Define postlinkClassroom table with wp prefix

		$user_role = $wpdb->get_var("SELECT user_role FROM $postlinkClassroom");
		if ($wpdb->num_rows > 0) {
			return $user_role;
			wp_die();
		}else{
			return "student";
			wp_die();
		}
	}

	// Loadmore data function
	function loadmoredata(){
		global $current_user;      
		wp_get_current_user();

		$default = $this->get_post_perpage();
		$default_role = $this->get_current_user();
		$paged = $_GET['paged'];

		if ( current_user_can( $default_role ))//check user level by level ID
		{
			$args = array(
				'posts_per_page' => $default ,
				'paged' => $paged ,
				'post_status' => 'publish',
				'author' => $current_user->ID,
				'orderby' => 'date',
				'order' => 'DESC',
			);
		
			$students_posts = new WP_Query($args);
			$totalpost = $students_posts->found_posts; 
			$output="";
			if ($students_posts->have_posts()) :
				while ($students_posts->have_posts()) : $students_posts->the_post();
					$output .= '<div class="publish-content">';
					$output .= '<div class="content_top">';
					$output .= get_the_post_thumbnail();
					$output .= '<span class="student_name">'.get_the_author_meta( 'display_name' ).'</span>';
					$output .= '<span class="create_date">Created: '.get_the_date( 'D M j' ).'</span>';
					$output .= '</div>';
					$output .= '<hr>';
					$output .= '<h3 class="title"><a href="'.get_the_permalink().'" rel="nofollow">'.get_the_title().'</a></h3>';

					$output .= substr(get_the_excerpt(),0,300);
					
					$output .= '<a class="seemore" href="'.get_the_permalink().'" rel="nofollow">Read More...</a>';
					$output .= '</div>';
				endwhile;
				$output .= '<button  class="pst-loadmore">See more</button>';
				echo $output;
				die();
			else :
				wp_reset_postdata();
				die();
			endif;
		}else{
			echo $output = '<span class="warn">Please <a href="/login">login</a> to view your posts</span>';
			echo $output = '<style> .published_post{ display: none !important; } </style>';
			die();
		}
	}
}