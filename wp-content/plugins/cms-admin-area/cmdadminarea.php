<?php
/**
Plugin Name: CMS admin area
Plugin URI: http://wordpress.org/extend/plugins/cms-admin-area/
Description: Simple plugin adds CMS-like features:  user-friendly horizontal menu and  dashboard metaboxes, completely remove blogging plarform widgets. It gives your clients a better experience of their new website.
Author: Piotr Bielecki
Version: 0.9
Author URI: http://netbiel.pl/
*/

function cms_main_function()
{


    remove_metabox_cms(); //remove metaboxes

    hide_welcome_panel(); //hide welcome panel

    load_plugin_textdomain('cms-admin-area', false, dirname(plugin_basename(__FILE__)) . '/languages/');

}


function remove_metabox_cms()
{
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // right now
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // recent comments
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); // plugins
    remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); // quick press
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); // recent drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // wordpress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'side'); // other wordpress news
}

/*
* hide welcome panel
*/
function hide_welcome_panel()
{
    global $wp_meta_boxes;

    $user_id = get_current_user_id();

    if (1 == get_user_meta($user_id, 'show_welcome_panel', true))
        update_user_meta($user_id, 'show_welcome_panel', 0);
}

//add new widget area
/* Adds a box to the main column on the Post and Page edit screens */
function cms_custom_box()
{
    add_meta_box(
        'cms_sectionid_left',
        __('Appearance', 'cms-admin-area'),
        'cms_inner_custom_box_left',
        'dashboard', 'side', 'high'
    );
    add_meta_box(
        'cms_sectionid_right',
        __('Content summary', 'cms-admin-area'),
        'cms_inner_custom_box_right',
        'dashboard', 'normal', 'high'
    );
}

//inner function for first widget area
function cms_inner_custom_box_left()
{


    dashboard_cms_widget_first();

}

//inner function for second widget area
function cms_inner_custom_box_right()
{


    dashboard_cms_widget_statistic();
}

add_action('wp_dashboard_setup', 'cms_custom_box');

//add new dashboard widget
function dashboard_cms_widget_first()
{
    // Display whatever it is you want to show
    ?>
<ul id="short-menu-list">
    <li id="short-menu-link"><a href="nav-menus.php"><?php echo __('Menus') ?></a></li>
    <li id="short-widget-link"><a href="widgets.php"><?php echo __('Widgets') ?></a></li>
    <li id="short-theme-link"><a href="themes.php"><?php echo __('Themes') ?></a></li>
</ul>
<div class="clear"></div>
<?php

}

function dashboard_cms_widget_statistic()
{
    $num_posts = wp_count_posts('post');
    $num_pages = wp_count_posts('page');

    $num_cats = wp_count_terms('category');

    $num_tags = wp_count_terms('post_tag');
    ?>
<ul class="statistic-list">
    <li>

        <h4><?php echo __('Pages', 'cms-admin-area') ?> :</h4>

        <p><?php echo $num_pages->publish; ?></p></li>
    <li><h4><?php echo __('Posts', 'cms-admin-area') ?> :</h4>

        <p><?php echo $num_posts->publish; ?></p></li>
</ul>
<?php
/*list posts*/
    $args = array('post_type' => 'page',


                  'posts_per_page' => 5,
    );
    $posts = new WP_Query($args);

    ?>
<div class="page-list-box">
    <h4><?php echo __('Post list', 'cms-admin-area') ?></h4>
    <ul class="post-list">
<?php

    while ($posts->have_posts()) : $posts->the_post();
        ?>
        <li>
            <h5><a href="<?php echo get_permalink();?>"><?php the_title(); ?></a></h5>
        </li>
        <?php
    endwhile;
    ?>
    </ul>
</div>
<ul class="menu-buttons">
    <li><a href="post-new.php?post_type=post"
           class="button-primary"><?php echo __('Add new post', 'cms-admin-area') ?></a></li>
    <li><a href="post-new.php?post_type=page"
           class="button-primary"><?php echo __('Add new page', 'cms-admin-area') ?></a></li>

</ul>


<?php

}
// We need some CSS to position the paragraph
function dashboard_cms_css()
{
    // This makes sure that the positioning is also good for right-to-left languages

    $url = get_bloginfo('wpurl');
    $dir = plugins_url( 'wp-admin.css' , __FILE__ );
    echo '<link rel="stylesheet" href="' . $dir . '" type="text/css" />';
}

function dashboard_cms_javascript()
{
    ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#adminmenu li.menu-top").hover(
                function () {
                    jQuery(this).addClass("wp-hover-menu");
                },
                function () {
                    setTimeout(function() {
                        jQuery(this).removeClass("wp-hover-menu");
                    }, 3000)

                }
        );
    });

</script>
<?php

}

add_action('admin_head', 'dashboard_cms_css');
add_action('admin_head', 'dashboard_cms_javascript');
add_action('wp_dashboard_setup', 'cms_main_function');
?>
