<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Postlink_Classroom
 * @subpackage Postlink_Classroom/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="tabs">
  <ul id="tabs-nav">
    <li><a href="#tab1">Contours</a></li>
    <li disabled><a href="#tab2">An extra</a></li>
  </ul> <!-- END tabs-nav -->
  <div id="tabs-content">
<?php
    global $wpdb; //Define wpdb global variable
    $postlinkClassroom = $wpdb->prefix . 'postlinkClassroom_v1'; //Define postlinkClassroom table with wp prefix
    $posts;
    $user;
    $user_data= $wpdb->get_results("SELECT * FROM $postlinkClassroom");
    if($wpdb->num_rows>0){
      foreach($user_data as $data){
        $posts =  $data->post_per_page;
        $user = $data->user_role;
      }
    }
?>
    <div id="tab1" class="tab-content">
        <h2>Students page management</h2>
        <hr>
        <div class="form-group">
            <label class="lab" for="posts-display">How many posts will be shown per load?</label>
            <br>
            <select class="inp-des per-page-inp" id="posts-display">
            <?php if(isset($posts)){
              echo '<option selected="selected" disabled value="'.$posts.'">'.$posts.' Posts Selected</option>';
            }else{
              echo '<option selected="selected" disabled>Default</option>';
            }
            ?>
              <option value="2">2 Posts per load</option>
              <option value="5">5 Posts per load</option>
              <option value="10">10 Posts per load</option>
            </select>
        </div>
        <div class="form-group">
            <label class="lab" for="select-user">Which users can see their posts?</label>
            <br>
            <select class="inp-des user-selects" id="select-user">
            <?php if(isset($user)){
              echo '<option selected="selected" disabled value="'. $user.'">'.$user.' Selected</option>';
            }else{
              echo '<option selected="selected" disabled>Default</option>';
            }
            echo wp_dropdown_roles();
            ?>
            </select>
        </div>
        <button class="save-btn">Save</button>
        <span class="warning"></span>
    </div>
    <div id="tab2" class="tab-content">
      <!-- Empty for now -->
      <p>Nothing for now</p>
    </div>
  </div> <!-- END tabs-content -->
</div> <!-- END tabs -->