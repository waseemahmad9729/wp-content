<?php
/**
 * Template Name: Projects Archive
 */

get_header();
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <div class="projects_container">

      <?php

      $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;


      $args = array(
        'post_type'      => 'projects', 
        'posts_per_page' => 2,
        'paged'          => $paged,
      );

      $projects_query = new WP_Query($args);

      // Start the loop.
      if ($projects_query->have_posts()) :
        while ($projects_query->have_posts()) : $projects_query->the_post();
          ?>
          <div class ="projects_profile">  
                      <div class="projects_info">
                      <h3><b><?php the_title();  ?></b></h3>  
                    <p>  <?php the_excerpt(); ?>  </p>
                      </div>
              </div>
  
          <?php
        endwhile;
     
                // Pagination links
            echo '<div class="pagination">';
            echo paginate_links(array(
                'total' => $projects_query->max_num_pages,
                'prev_text' => __('« Prev'),
                'next_text' => __('Next »'),
            ));
            echo '</div>';
   
        // Reset post data.
        wp_reset_postdata();

      else :

        echo '<p>No projects found.</p>';

      endif;
      ?>

    </div><!-- .project-archive -->
  </main><!-- #main -->
</div><!-- #primary -->
<?php
get_footer();