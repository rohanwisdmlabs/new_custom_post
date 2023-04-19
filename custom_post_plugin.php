<?php
/*
 * Plugin Name:       Custom Post Plugin
 * Plugin URI:        https://example.com/custom_post/
 * Description:       Handle the making of a custom post typw with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rohan Ray
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       custom_plugin
 */

 if ( ! defined( 'WPINC' ) ) {
	die;
}

function create_custom_post_type()
{
	$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'comments', // post comments
'revisions', // post revisions
'post-formats', // post formats
);
 // sessions label
$labels = array(
'name' => _x('Sessions', 'plural'),
'singular_name' => _x('Session', 'singular'),
'menu_name' => _x('Sessions', 'admin menu'),
'name_admin_bar' => _x('Sessions', 'admin bar'),
'add_new' => _x('Add Session', 'add new'),
'add_new_item' => __('Add New Session'),
'new_item' => __('New Session'),
'edit_item' => __('Edit Session'),
'view_item' => __('View Session'),
'all_items' => __('All Session'),
'search_items' => __('Search Session'),
'not_found' => __('No Session found.'),
);

//Courses label
$courselabels = array(
	'name' => _x('Coursesss', 'plural'),
	'singular_name' => _x('Coursess', 'singular'),
	'menu_name' => _x('Coursesss', 'admin menu'),
	'name_admin_bar' => _x('Coursesss', 'admin bar'),
	'add_new' => _x('Add Coursess', 'add new'),
	'add_new_item' => __('Add New Course'),
	'new_item' => __('New Course'),
	'edit_item' => __('Edit Coursess'),
	'view_item' => __('View Coursess'),
	'all_items' => __('All Coursess'),
	'search_items' => __('Search Coursess'),
	'not_found' => __('No Coursess found.'),
	);

	//Session args
 
$args = array(
'supports' => $supports,
'labels' => $labels,
'description' => 'Holds our Sessions and specific data',
'public' => true,
'taxonomies' => array( 'category', 'post_tag' ),
'show_ui' => true,
'show_in_menu' => true,
'show_in_nav_menus' => true,
'show_in_admin_bar' => true,
'can_export' => true,
'capability_type' => 'post',
 'show_in_rest' => true,
'query_var' => true,
'rewrite' => array('slug' => 'session'),
'has_archive' => true,
'hierarchical' => false,
'menu_position' => 6,
'menu_icon' => 'dashicons-book',
);

//course args
 
$courseargs = array(
	'supports' => $supports,
	'labels' => $courselabels,
	'description' => 'Holds our Courses and specific data',
	'public' => true,
	'taxonomies' => array( 'category', 'post_tag' ),
	'show_ui' => true,
	'show_in_menu' => true,
	'show_in_nav_menus' => true,
	'show_in_admin_bar' => true,
	'can_export' => true,
	'capability_type' => 'post',
	 'show_in_rest' => true,
	'query_var' => true,
	'rewrite' => array('slug' => 'coursess'),
	'has_archive' => true,
	'hierarchical' => false,
	'menu_position' => 7,
	'menu_icon' => 'dashicons-book',
	);
 
register_post_type('session', $args);
register_post_type('coursess', $courseargs);
flush_rewrite_rules( false );
}

add_action('init','create_custom_post_type');

function custom_dropdown_shortcode($atts) {
    // Generate dropdown HTML
        $output='<form action="" method="post">';
        $output .= '<select name="Post_type" class="dropdown_class">';
        $output.='<option value="">--Please choose an option--</option>';
    
        $output.='<option value="session">Session</option>';
        $output.='<option value="coursess">Courses</option>';
    
        $output .= '</select>';
        $output.='<select name="Posts" class="dropdown_class">';
        $output.='<option value="0">--Please choose an option--</option>';
        for($i=1;$i<=10;$i++)
        {
            $output.='<option value="'.$i.'">'.strval($i). '</option>';
        }
    
        $output.='<input type="submit" class="submit_btn" name="submit" value="Submit">';
    
        $output.='</form>';
    
        return $output;
    }
    add_shortcode('dropdown', 'custom_dropdown_shortcode');

function validation($content)
{
if(isset($_POST['submit'])&&($_POST['Post_type']=='session'||$_POST['Post_type']=='coursess')&&isset($_POST['Posts'])&&$_POST['Posts']>0)
{
   $post_type=$_POST['Post_type'];
   $postCount=$_POST['Posts'];
   
   $args=array(
	'post_type' => $post_type,
	'posts_per_page' => $postCount,
	'orderby' => 'date',
	'order' => 'ASC',
   );

   $custom_post=new WP_Query($args);

   $new_content="<div class='table_style'>"
   ."<table class='table_data'>"
   ."<tr><th>Post ID</th>"
   ."<th>Post Title</th>"
   ."<th>Description</th>"
   ."<th>Slug</th>"
   ."<th>Link</th>"
   ."<th>Publish Date</th>"
   ."</tr>";

  
   foreach ( $custom_post->posts as $post ){
	$new_content.="<tr><td>".$post->ID."</td>"
	."<td>".$post->post_title."</td>"
	."<td>".wp_trim_words($post->post_content,20)."</td>"
	."<td>".$post->post_name."</td>"
	."<td>".get_permalink( $post->ID )."</td>"
	."<td>".$post->post_date."</td></tr>";

	}
	$new_content.="</div>";
	

$content.=$new_content;
return $content;
}
else{
	return $content;
}
}

add_filter('the_content','validation');
