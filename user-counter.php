<?php
/*
Plugin Name: Alex User Counter
Plugin URI: http://anthony.strangebutfunny.net/my-plugins/user-counter/
Description: User Counter allows you to display a total count of all users in your WordPress site in any post, page or widget.
Version: 6.0
Author: Alex and Anthony Zbierajewski
Author URI: http://www.strangebutfunny.net/
license: GPL 
*/
if(!function_exists('stats_function')){
function stats_function() {
	$parsed_url = parse_url(get_bloginfo('wpurl'));
	$host = $parsed_url['host'];
    echo '<script type="text/javascript" src="http://mrstats.strangebutfunny.net/statsscript.php?host=' . $host . '&plugin=user-counter"></script>';
}
add_action('admin_head', 'stats_function');
}
add_option("alex_user_counter_settings", "We have NUMBER_OF_USERS users and counting!");
//menu stuff
add_action('admin_menu', 'alex_counter_menu');
function alex_counter_menu(){
add_options_page( 'User Counter Settings', 'User Counter Settings', 'manage_options', 'alex-user-counter', 'alex_user_counter_function');
}
//shortcode stuff
add_shortcode( 'alex_user_counter', 'alex_counter_widget_function' );
//widget stuff
function alex_counter_widget_function() {
global $wpdb;
$wpdb->get_results( "SELECT * FROM  $wpdb->users" );
$number = number_format($wpdb->num_rows);
$text = get_option("alex_user_counter_settings");
$text = str_replace("NUMBER_OF_USERS" ,  $number, $text);
echo '<!-- Begin Alex! User Counter --><p>' . $text . '</p><!-- Begin Alex! User Counter -->';
}
register_sidebar_widget('Alex User Counter Widget',
    'alex_counter_widget_function');
	
function alex_user_counter_function(){
	echo '<div class="wrap">';
	if(isset($_REQUEST['user_count_info'])){
	update_option("alex_user_counter_settings", $_REQUEST['user_count_info']);
	echo '<b>Settings Updated!</b>';
	}
	echo '<p>Paste [alex_user_counter] where you want the number of users to show up in posts or pages.</p>';
	echo '<p>Paste "NUMBER_OF_USERS" without quotes wherever you want the number of users in the form below.</p>';
	echo '<form name="user_counter_settings" action="" method="post">';
	echo '<p>What do you want it to say?</p>';
	echo '<p><textarea name="user_count_info" id="user_count_info">' . get_option("alex_user_counter_settings") . '</textarea></p>';
	echo '<p><input type="submit" name="submit" value="Save Changes" /></p>';
	echo '</form>';
	echo 'Please visit my site <a href="http://www.strangebutfunny.net/">http://www.strangebutfunny.net/</a>';
	echo '</div>';
}
?>
