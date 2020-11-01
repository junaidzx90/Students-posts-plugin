<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Postlink_Classroom
 * @subpackage Postlink_Classroom/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Postlink_Classroom
 * @subpackage Postlink_Classroom/includes
 * @author     Postlink <demo@gmail.com>
 */
class Postlink_Classroom_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb; //Define wpdb global variable
		$postlinkClassroom = $wpdb->prefix . 'postlinkClassroom_v1'; //Define postlinkClassroom table with wp prefix
		$postlinkClassroomSql = "CREATE TABLE IF NOT EXISTS `$postlinkClassroom` ( 
			`student_id` INT NOT NULL AUTO_INCREMENT,
			`post_per_page` INT NOT NULL,
			`user_role` VARCHAR(20) NOT NULL,
			PRIMARY KEY  (`student_id`)) ENGINE = InnoDB";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($postlinkClassroomSql); //Action for create table postlinkClassroom
	}

}
