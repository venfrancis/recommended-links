<?php
/*
Plugin Name: Recommended Links
Plugin URI: http://ven.revereasia.com/wordpress/plugins/recommended-links
Description: A simple plugin for your recommended links. Best for users that likes to show where they have positively reviewed, featured, and awarded from other sites. The plugin adds a rel="nofollow" attribute allows you to tell the search engines that you do not want the link to pass any link value (a.k.a. "PageRank"), that it should not be counted as an endorsement of the target page. This eliminates the value of the link for spammers, which is why Google invented it in the first place.
Author: Ven Francis Dano-og, Revere Asia
Version: 1.0
Author URI: http://ven.revereasia.com
*/

// Register Custom Post Type
function recommendedLinks() {
    $labels = array(
        'name'                => 'Recommended Links',
        'singular_name'       => 'Recommended Link',
        'menu_name'           => 'Recommended Links',
        'parent_item_colon'   => 'Parent Link:',
        'all_items'           => 'All Links',
        'view_item'           => 'View Link',
        'add_new_item'        => 'Add Recommended Link',
        'add_new'             => 'New Recommended Link',
        'edit_item'           => 'Edit Recommended Link',
        'update_item'         => 'Update Recommended Link',
        'search_items'        => 'Search Recommended Link',
        'not_found'           => 'No recommended link found',
        'not_found_in_trash'  => 'No recommended link found in Trash',
    );
    $rewrite = array(
        // 'slug'                => 'recommended-links/%recommend%',
        'with_front'          => true,
        'pages'               => true,
        'feeds'               => true,
    );
    $args = array(
        'label'               => 'rlinks',
        'description'         => 'Recommended Links',
        'labels'              => $labels,
        'supports'            => array( 'title', 'page-attributes'),
        'taxonomies'          => array( 'rlinks' ),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 100,
        'menu_icon'           => '',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'query_var'           => 'recommendedlinks',
        'rewrite'             => $rewrite,
        'capability_type'     => 'page',
    );
    register_post_type( 'rlinks', $args );

}

// Hook into the 'init' action
add_action( 'init', 'recommendedLinks', 0 );

function filter_post_type_link($link, $post)
{
    if ($post->post_type != 'rlinks')
        return $link;

    if ($cats = get_the_terms($post->ID, 'recommend'))
        $link = str_replace('%recommend%', array_pop($cats)->slug, $link);
    return $link;
}
add_filter('post_type_link', 'filter_post_type_link', 10, 2);

function add_custom_taxonomies() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy('recommend', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name'                       => 'Link Category',
            'singular_name'              => 'Recommended Link',
            'menu_name'                  => 'Recommended Links',
            'all_items'                  => 'All Link Categories',
            'parent_item'                => 'Parent Link Category',
            'parent_item_colon'          => 'Parent Link Category:',
            'new_item_name'              => 'New Recommended Link',
            'add_new_item'               => 'Add Recommended Link',
            'edit_item'                  => 'Edit Recommended Link',
            'update_item'                => 'Update Recommended Link',
            'separate_items_with_commas' => 'Separate recommended links with commas',
            'search_items'               => 'Search recommended links',
            'add_or_remove_items'        => 'Add or remove recommended links',
            'choose_from_most_used'      => 'Choose from the most used recommended links',
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'recommended-links', // This controls the base slug that will display before each term
            'with_front' => true, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}
add_action( 'init', 'add_custom_taxonomies', 0 );

//We create an array called $meta_box and set the array key to the relevant post type
$meta_box['rlinks'] = array(

    //This is the id applied to the meta box
    'id' => 'post-format-meta',

    //This is the title that appears on the meta box container
    'title' => 'Recommended Link Details',

    //This defines the part of the page where the edit screen section should be shown
    'context' => 'normal',

    //This sets the priority within the context where the boxes should show
    'priority' => 'high',

    //Here we define all the fields we want in the meta box
    'fields' => array(
        array(
            'name' => 'Article Name',
            'desc' => '',
            'id' => 'rc_url_name',
            'type' => 'text',
            'default' => ''
        ),
        array(
            'name' => 'URL',
            'desc' => 'Link to the external site (ie. <em>http://www.bbc.co.uk/news/world-us-canada-24362141</em>)',
            'id' => 'rc_url',
            'type' => 'text',
            'default' => ''
        ),
        array(
            'name' => 'Root URL',
            'desc' => 'The site\'s root link (ie. <em>www.bbc.co.uk</em>)',
            'id' => 'rc_root_url',
            'type' => 'text',
            'default' => ''
        )
    )
);

//Format meta boxes
function recommended_format_box() {
  global $meta_box, $post;

  // Use nonce for verification
  echo '<input type="hidden" name="rlinks_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
  echo '<table class="form-table">';

  foreach ($meta_box[$post->post_type]['fields'] as $field) {
      // get current post meta data
      $meta = get_post_meta($post->ID, $field['id'], true);

      echo '<tr>'.
              '<th style="width:20%"><label for="'. $field['id'] .'">'. $field['name']. '</label></th>'.
              '<td>';
      switch ($field['type']) {
          case 'text':
              echo '<input type="text" name="'. $field['id']. '" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['default']) . '" size="30" style="width:97%" />'. '<br />'. $field['desc'];
              break;
      }
      echo     '<td>'.'</tr>';
  }
  echo '</table>';
}

//Add meta boxes to post types
function add_recommended_fields() {
    global $meta_box;

    foreach($meta_box as $post_type => $value) {
        add_meta_box($value['id'], $value['title'], 'recommended_format_box', $post_type, $value['context'], $value['priority']);
    }
}

add_action('admin_menu', 'add_recommended_fields');

// Save data from meta box
function recommended_links_save_data($post_id) {
    global $meta_box,  $post;

    //Verify nonce
    if (!wp_verify_nonce($_POST['rlinks_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    //Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    //Check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    foreach ($meta_box[$post->post_type]['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

add_action('save_post', 'recommended_links_save_data');


class recommended_links_home_widget extends WP_Widget {

    // constructor
    function recommended_links_home_widget() {
        // parent::WP_Widget(false, $name = __('Recommended Links', 'recommended_links_home_widget') );
        $widget_ops = array(
                'classname' => 'widg-reco-links', //recent classes: span3 no-margin-right
                'description' => __('Display Recommended Links', 'recommended_links_home_widget')
            );
        $this->WP_Widget( 'recommended_links_home_widget', 'Recommended Links',  $widget_ops );
    }
    // widget form creation
    function form($instance) {
        // Check values
        if( $instance) {
             $title = esc_attr($instance['title']);

        } else {
             $title = '';
        }
    ?>

    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <?php
    }

    // update widget
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    // display widget
    function widget($args, $instance) {
        extract( $args );
        // these are the widget options
        $title = apply_filters('widget_title', $instance['title']);
        $before_title = "<h3 class='recommended-links-title'>";
        $after_title = "</h3>";
        // Display the widget

        echo $before_widget;

        // Check if title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        echo '<div>';
        //prepare the loop and columns

        $args = array (
            'post_type'      =>     'rlinks',
            'posts_per_page' =>     10
        );

        // The Query
        $query = new WP_Query( $args );
        // The Loop
        $colnum = 1;

        global $meta_box, $post;

        $counterfive = 10;
        $counter2 = 5;

        if ( $query->have_posts() ) {
            echo "<ul class='col1'>";
            while ( $query->have_posts() )
            {
                $query->the_post();
                //$getTheUrl = get_post_meta($post->ID, $meta, true);

                $arr = array();

                foreach ($meta_box[$post->post_type]['fields'] as $key => $field) {
                    $meta = get_post_meta($post->ID, $field['id'], true);
                    $arr[$field['id']] = $meta;
                }

                if($counterfive == 5)
                {
                    echo "</ul>";
                    echo "<ul class='col2'>";
                    $secondColumn = true;
                }

                $counterfive--;

            if($counterfive >= 0 )
                {
                    echo "
                    <li>
                    <a href='".$arr['rc_url']."' target='_blank' rel='nofollow'>".$arr['rc_url_name']."</a><br>
                    <em>".$arr['rc_root_url']."</em>
                    </li>
                    ";
                }
            }
        } else {
            echo "There's no recommended links as of the moment, please come back later.";
        }
        echo "</ul>";
        // Restore original Post Data
        wp_reset_postdata();

        echo '</div>';
        echo $after_widget;
    }
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("recommended_links_home_widget");'));

// the icon
function registerRecoLinksIcon() {
    $purl = plugins_url();
    echo '<style type="text/css">
    #menu-posts-rlinks a .wp-menu-image{background: url("'.$purl.'/recommended-links/admin/img/icon.png") center 5px no-repeat;background-size:70%;}
    </style>';

}

add_action('admin_head', 'registerRecoLinksIcon');

function rc_styles(){
?>
  <style type="text/css">
  .recommended-links-title{border-top: 1px solid #000;  border-bottom: 1px solid #ddd;  margin: 0 0 20px; line-height: 38px;  font-family: Arial,sans-serif; text-transform: uppercase;  font-size: 11px; }
  .widg-reco-links{min-height: 200px;}
  .widg-reco-links div{overflow: hidden;}
  .widg-reco-links ul.col1{float: left;width: 47%;margin:0 1% 0 15px;list-style: square;}
  .widg-reco-links ul.col2{float: left;width: 47%;margin:0 0 0 15px;list-style: square;}
  .widg-reco-links ul li {padding: 0 0 5px;}
  .widg-reco-links ul li a{font-size: 14px;line-height: 22px;margin: 0 0 10px;font-family: Arial,sans-serif;}

  </style>
<?php
}
add_action('wp_head', 'rc_styles');
?>