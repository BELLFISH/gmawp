      <nav class="blogNav">
        <p class="blogNav_title">Category</p>
        <ul class="blogNav_archives">
          <?php
            $args = array(
              'orderby' => 'name'
            );
            $categories = get_categories($args);
            foreach($categories as $category){
              echo '<li><a href="/blog/'.$category->slug.'">'.$category->name.'</a></li>';
            }
          ?>

        </ul>
        <p class="blogNav_title">Archives</p>
        <ul class="blogNav_archives">
          <?php
            $year = NULL;
            $args = array(
              'orderby' => 'date',
              'order' => 'DESC',
              'posts_per_page' => -1
            );
            $the_query = new WP_Query($args);
            while($the_query->have_posts()): $the_query->the_post();
              if($year != get_the_date('Y')){
                $year = get_the_date('Y');
                $yearandmonth = get_the_date('Y.n');
                echo '<li><a href="/blog/'.$year.'/">'.$yearandmonth.'</a></li>';
              }
            endwhile;
            wp_reset_postdata();
          ?>

        </ul>
      </nav>
