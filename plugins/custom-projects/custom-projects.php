<?php
/*
Plugin Name:  Projects CPT
Plugin URI:  
Description: 
Version:     1.0.0
Author:     Waseem Ahmad
*/


function custom_ip_redirection() {
  
      $user_ip = $_SERVER['REMOTE_ADDR'];
  
      if (strpos($user_ip, '77.29') === 0) {
          wp_redirect('https://example.com');
          exit;
      }
  }
  
add_action('template_redirect', 'custom_ip_redirection');


function custom_register_projects_post_type() {
      $labels = array(
          'name'               => _x('Projects', 'post type general name', 'textdomain'),
          'singular_name'      => _x('Project', 'post type singular name', 'textdomain'),
          'menu_name'          => _x('Projects', 'admin menu', 'textdomain'),
          'name_admin_bar'     => _x('Project', 'add new on admin bar', 'textdomain'),
          'add_new'            => _x('Add New', 'project', 'textdomain'),
          'add_new_item'       => __('Add New Project', 'textdomain'),
          'new_item'           => __('New Project', 'textdomain'),
          'edit_item'          => __('Edit Project', 'textdomain'),
          'view_item'          => __('View Project', 'textdomain'),
          'all_items'          => __('All Projects', 'textdomain'),
          'search_items'       => __('Search Projects', 'textdomain'),
          'parent_item_colon'  => __('Parent Projects:', 'textdomain'),
          'not_found'          => __('No projects found.', 'textdomain'),
          'not_found_in_trash' => __('No projects found in Trash.', 'textdomain')
      );
  
      $args = array(
          'labels'             => $labels,
          'public'             => true,
          'publicly_queryable' => true,
          'show_ui'            => true,
          'show_in_menu'       => true,
          'query_var'          => true,
          'rewrite'            => array('slug' => 'projects'), // Change the slug as desired
          'capability_type'    => 'post',
          'has_archive'        => true,
          'hierarchical'       => false,
          'menu_position'      => null,
          'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
          'menu_icon'          => 'dashicons-clipboard', // You can change the icon
      );
  
      register_post_type('projects', $args);
  }
  add_action('init', 'custom_register_projects_post_type');
  
  function custom_register_project_type_taxonomy() {
      $labels = array(
          'name'              => _x('Project Types', 'taxonomy general name', 'textdomain'),
          'singular_name'     => _x('Project Type', 'taxonomy singular name', 'textdomain'),
          'search_items'      => __('Search Project Types', 'textdomain'),
          'all_items'         => __('All Project Types', 'textdomain'),
          'parent_item'       => __('Parent Project Type', 'textdomain'),
          'parent_item_colon' => __('Parent Project Type:', 'textdomain'),
          'edit_item'         => __('Edit Project Type', 'textdomain'),
          'update_item'       => __('Update Project Type', 'textdomain'),
          'add_new_item'      => __('Add New Project Type', 'textdomain'),
          'new_item_name'     => __('New Project Type Name', 'textdomain'),
          'menu_name'         => __('Project Types', 'textdomain'),
      );
  
      $args = array(
          'hierarchical'      => true,
          'labels'            => $labels,
          'show_ui'           => true,
          'show_admin_column' => true,
          'query_var'         => true,
          'rewrite'           => array('slug' => 'project-type'), // Change the slug as desired
      );
  
      register_taxonomy('project_type', 'projects', $args);
  }
  add_action('init', 'custom_register_project_type_taxonomy');



      function custom_projects_ajax_handler() {
          
            $projects_per_page = is_user_logged_in() ? 6 : 3;
          
            $args = array(
              'post_type'      => 'projects', 
              'posts_per_page' => $projects_per_page,
              'tax_query'      => array(
                array(
                  'taxonomy' => 'project_type', 
                  'field'    => 'slug',
                  'terms'    => 'architecture',
                ),
              ),
            );
          
            $projects_query = new WP_Query($args);
          
            $projects = array();
            if ($projects_query->have_posts()) {
              while ($projects_query->have_posts()) {
                $projects_query->the_post();
          
                $project = array(
                  'id'    => get_the_ID(),
                  'title' => get_the_title(),
                  'link'  => get_permalink(),
                );
          
                $projects[] = $project;
              }
            }
          
            // Reset post data.
            wp_reset_postdata();
          
            // Return JSON response.
            $response = array(
              'success' => true,
              'data'    => $projects,
            );
          
            wp_send_json($response);
          }
      add_action('wp_ajax_custom_projects', 'custom_projects_ajax_handler');
      add_action('wp_ajax_nopriv_custom_projects', 'custom_projects_ajax_handler');
          

      function projects_script_enqueue() {
            wp_enqueue_style( 'style_css', plugins_url('assets/css/style.css', __FILE__), true, Null, 'all');    
            wp_enqueue_script( 'ajax-script', plugins_url('/assets/js/custom.js', __FILE__), array('jquery'), '1.0', true );
            wp_localize_script( 'ajax-script', 'custom_projects_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), '') );
      }      
      add_action( 'wp_enqueue_scripts', 'projects_script_enqueue' );



      function hs_give_me_coffee() {
           
            $api_url = 'https://random-coffee-api.com/api/coffee';
        
            $response = wp_remote_get($api_url);
        
            if (is_wp_error($response)) {
                return "Oops! Unable to fetch coffee data. Please try again later.";
            }
        
            $data = json_decode(wp_remote_retrieve_body($response), true);
        
            if (!$data || empty($data['coffee_url'])) {
                return "Sorry, the coffee data is unavailable at the moment.";
            }
            return $data['coffee_url'];
        }


      add_shortcode( 'hs_give_me_coffee', 'custom_hs_give_me_coffee_and_quote');


   function custom_hs_give_me_coffee_and_quote(){

      $html = "";
      $coffee_link = hs_give_me_coffee();
      if ($coffee_link) {
            $html = '<a href="' . esc_url($coffee_link) . '">Enjoy your cup of coffee!</a>';
      } else {
            $html = 'Unable to get coffee link at the moment. Please try again later.';
      }

            $url = 'https://api.kanye.rest/';
            $response = file_get_contents($url);

            if ($response !== false) {
            $quotes = json_decode($response, true);


            for ($i = 0; $i < 5; $i++) {
                  $quote = $quotes['quote'];
                  $html .=  "<li>".$quote."</li>";
            }
            } 
      
            return $html;
      }



     
