<?php
ob_start();
/**
 * Template Name: Students page
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Postlink_Classroom
 * @subpackage Postlink_Classroom/public/partials
 */
wp_head();
if(!is_user_logged_in()){
    wp_redirect( home_url('/login'));
}
get_header();
?>
<!-- Main student posts wrapper start from here -->
<div id="main_wrapper">
<?php the_content(); ?>
    <div id="post_contents">
        <div class="loaddata">
        <h1 class="published_post"></h1>
            <!-- Dtat will be load here -->
        </div>
    </div>
<?php  get_sidebar(); ?>
</div>
<!-- Main student posts wrapper end -->
<?php
get_footer();
wp_footer();
?>