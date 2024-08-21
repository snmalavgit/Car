<?php
/*
 * Plugin Name: Car
 * Description: Car is a data management system plugin which includes the car entries and the list.
 * Plugin URI: #
 * Author: Sunil Malav
 * Author URI: #
 * Version: 1.0
 */

// Register Custom Post Type
function create_car_post_type() {
    register_post_type('car',
        array(
            'labels' => array(
                'name'               => __('Cars'),
                'singular_name'      => __('Car'),
                'add_new'            => __('Add New Car'),
                'add_new_item'       => __('Add New Car'),
            ),
            'public'      => true,
            'has_archive' => true,
            'supports'    => array('title', 'thumbnail'),
            'menu_icon'   => 'dashicons-car', // Optional: Sets a custom icon for the menu
        )
    );
}
add_action('init', 'create_car_post_type');

// Register Custom Taxonomies
function create_car_taxonomies() {
    // Make Taxonomy
    register_taxonomy('make', array('car'), array(
        'labels' => array(
            'name'              => __('Makes'),
            'singular_name'     => __('Make'),
            'search_items'      => __('Search Makes'),
            'all_items'         => __('All Makes'),
            'parent_item'       => __('Parent Make'),
            'parent_item_colon' => __('Parent Make:'),
            'edit_item'         => __('Edit Make'),
            'update_item'       => __('Update Make'),
            'add_new_item'      => __('Add New Make'),
            'new_item_name'     => __('New Make Name'),
            'menu_name'         => __('Makes'),
        ),
        'hierarchical' => true,
        'show_ui'      => true,
        'show_admin_column' => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => 'make'),
    ));

    // Model Taxonomy
    register_taxonomy('model', array('car'), array(
        'labels' => array(
            'name'              => __('Models'),
            'singular_name'     => __('Model'),
            'search_items'      => __('Search Models'),
            'all_items'         => __('All Models'),
            'parent_item'       => __('Parent Model'),
            'parent_item_colon' => __('Parent Model:'),
            'edit_item'         => __('Edit Model'),
            'update_item'       => __('Update Model'),
            'add_new_item'      => __('Add New Model'),
            'new_item_name'     => __('New Model Name'),
            'menu_name'         => __('Models'),
        ),
        'hierarchical' => true,
        'show_ui'      => true,
        'show_admin_column' => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => 'model'),
    ));

    // Year Taxonomy
    register_taxonomy('year', array('car'), array(
        'labels' => array(
            'name'              => __('Years'),
            'singular_name'     => __('Year'),
            'search_items'      => __('Search Years'),
            'all_items'         => __('All Years'),
            'parent_item'       => __('Parent Year'),
            'parent_item_colon' => __('Parent Year:'),
            'edit_item'         => __('Edit Year'),
            'update_item'       => __('Update Year'),
            'add_new_item'      => __('Add New Year'),
            'new_item_name'     => __('New Year Name'),
            'menu_name'         => __('Years'),
        ),
        'hierarchical' => false,
        'show_ui'      => true,
        'show_admin_column' => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => 'year'),
    ));

    // Fuel Type Taxonomy
    register_taxonomy('fuel_type', array('car'), array(
        'labels' => array(
            'name'              => __('Fuel Types'),
            'singular_name'     => __('Fuel Type'),
            'search_items'      => __('Search Fuel Types'),
            'all_items'         => __('All Fuel Types'),
            'parent_item'       => __('Parent Fuel Type'),
            'parent_item_colon' => __('Parent Fuel Type:'),
            'edit_item'         => __('Edit Fuel Type'),
            'update_item'       => __('Update Fuel Type'),
            'add_new_item'      => __('Add New Fuel Type'),
            'new_item_name'     => __('New Fuel Type Name'),
            'menu_name'         => __('Fuel Types'),
        ),
        'hierarchical' => false,
        'show_ui'      => true,
        'show_admin_column' => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => 'fuel-type'),
    ));
}
add_action('init', 'create_car_taxonomies');

function enqueue_custom_jquery() {
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', true);
    wp_enqueue_script('jquery');

    // Enqueue the custom script that contains AJAX handling
    wp_enqueue_script('car-form-script', get_template_directory_uri() . '/js/car-form.js', array('jquery'), null, true);

    // Passing Ajax URL to the script
    wp_localize_script('car-form-script', 'carForm', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_jquery');

// Shortcode for Car Entry Form
function car_entry_form() {
    ob_start(); ?>
    <form id="car-entry-form" method="post" enctype="multipart/form-data" style="display: inline-grid;">
    <input type="hidden" name="car_entry_nonce" value="<?php echo wp_create_nonce('car_entry_nonce'); ?>">
        <label>Car Name:</label>
        <input type="text" name="car_name" required />
        
        <label>Make:</label>
        <?php
        $makes = get_terms(array('taxonomy' => 'make', 'hide_empty' => false));
        echo '<select name="make" required>';
        echo '<option value="" disabled selected>Please select a make</option>';
        foreach ($makes as $make) {
            echo '<option value="' . esc_attr($make->term_id) . '">' . esc_html($make->name) . '</option>';
        }
        echo '</select>';
        ?>

        <label>Model:</label>
        <?php
        $models = get_terms(array('taxonomy' => 'model', 'hide_empty' => false));
        echo '<select name="model" required>';
        echo '<option value="" disabled selected>Please select a model</option>';
        foreach ($models as $model) {
            echo '<option value="' . esc_attr($model->term_id) . '">' . esc_html($model->name) . '</option>';
        }
        echo '</select>';
        ?>

        <label>Year:</label>
        <?php
        $years = get_terms(array('taxonomy' => 'year', 'hide_empty' => false));
        echo '<select name="year" required>';
        echo '<option value="" disabled selected>Please select a year</option>';
        foreach ($years as $year) {
            echo '<option value="' . esc_attr($year->term_id) . '">' . esc_html($year->name) . '</option>';
        }
        echo '</select>';
        ?>

        <label>Fuel Type:</label>
        <?php
        $fuel_types = get_terms(array('taxonomy' => 'fuel_type', 'hide_empty' => false));
        foreach ($fuel_types as $fuel_type) {
            echo '<label><input type="radio" name="fuel_type" value="' . esc_attr($fuel_type->term_id) . '" required />' . esc_html($fuel_type->name) . '</label><br>';
        }
        ?>

        <label>Image:</label>
        <input type="file" name="car_image" />
        <input type="submit" value="Submit" />
    </form>
    <style>
        input[type="text"], input[type="file"], select {margin-bottom: 10px; padding: 10px;}
        input[type="submit"], select {padding: 5px;}
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('car_entry', 'car_entry_form');


add_action('wp_ajax_handle_car_entry', 'handle_car_entry');
add_action('wp_ajax_nopriv_handle_car_entry', 'handle_car_entry');

function handle_car_entry() {
    // Check for nonce security
    if ( ! isset($_POST['car_entry_nonce']) || ! wp_verify_nonce($_POST['car_entry_nonce'], 'car_entry_nonce') ) {
        wp_send_json_error('Nonce verification failed.');
        return;
    }

    if ( ! isset($_POST['car_name']) || ! isset($_POST['make']) || ! isset($_POST['model']) || ! isset($_POST['year']) || ! isset($_POST['fuel_type']) ) {
        wp_send_json_error('Missing required fields.');
        return;
    }

    $car_name = sanitize_text_field($_POST['car_name']);
    $make = intval($_POST['make']);
    $model = intval($_POST['model']);
    $year = intval($_POST['year']);
    $fuel_type = intval($_POST['fuel_type']);

    // Create Post
    $post_id = wp_insert_post(array(
        'post_title'  => $car_name,
        'post_type'   => 'car',
        'post_status' => 'publish',
    ));

    if ($post_id) {
        // Set Taxonomies
        wp_set_post_terms($post_id, array($make), 'make');
        wp_set_post_terms($post_id, array($model), 'model');
        wp_set_post_terms($post_id, array($year), 'year');
        wp_set_post_terms($post_id, array($fuel_type), 'fuel_type');

        // Handle Image
        if (!empty($_FILES['car_image']['name'])) {
            $attachment_id = media_handle_upload('car_image', $post_id);
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($post_id, $attachment_id);
            } else {
                wp_send_json_error('Error uploading image.');
                return;
            }
        }

        wp_send_json_success('Car submitted successfully!');
    } else {
        wp_send_json_error('Error creating car post.');
    }
}

// Shortcode for Car List
function car_list_shortcode() {
    $args = array(
        'post_type' => 'car',
        'posts_per_page' => -1
    );
    $query = new WP_Query($args);
    ob_start();
    if ($query->have_posts()) {
        echo '<ul>';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li>';
            the_title();
            if (has_post_thumbnail()) {
                the_post_thumbnail();
            }
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo 'No cars found.';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('car_list', 'car_list_shortcode');

// Add submenu page under the Car custom post type menu
function car_plugin_submenu() {
    add_submenu_page(
        'edit.php?post_type=car',  // Parent menu slug
        'Shortcodes',     // Page title
        'Shortcodes',     // Menu title
        'manage_options',          // Capability
        'car-plugin-settings',     // Menu slug
        'car_plugin_settings_page' // Callback function
    );
}
add_action('admin_menu', 'car_plugin_submenu');

// Callback function to display the submenu page content/shortcode
function car_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Car Plugin Shortcodes</h1>
        <p>Use the following shortcodes to add car functionalities to your pages or posts:</p>
        <table class="form-table">
            <tr>
                <th scope="row">Car Entry Form</th>
                <td>
                    <code>[car_entry]</code><br />
                    Displays a form for submitting new cars. Include this shortcode on any page where you want the form to appear.
                </td>
            </tr>
            <tr>
                <th scope="row">Car List</th>
                <td>
                    <code>[car_list]</code><br />
                    Displays a list of all cars with their thumbnails. Use this shortcode on a page where you want to show the list of cars.
                </td>
            </tr>
        </table>
    </div>
    <?php
}
