<?php
  get_header();
  $archives = wp_get_archives([
    'post_type' => 'blog',
    'type' => 'monthly',
    'echo' => 0,
    'order' => 'DESC',
  ]);
  $args = [
    [
      'name' => 'Category',
      'taxonomy' => 'blog_category'
    ]
  ];
  if(have_posts()):
?>
    <div class="p-single-blog_wrapper">
    <section class="archiveBlog">
      <div class="p-single-blog_inner">
<?php
    while(have_posts()):
      the_post();
?>

    <article class="singleBlog">
      <div class="singleBlog_data c-slashBlock js-animation-slideUp">
        <span><?php the_date(); ?></span>
        <span>
          <?php
          if ($terms = get_the_terms($post->ID,'blog_category')){
            foreach ($terms as $term){
              echo esc_html($term->name);
            }
          }
          ?>
        </span>
      </div>
      <h1 class="singleBlog_title js-animation-slideUp"><span><?php the_title(); ?></span></h1>
      <div class="singleBlog_content js-animation-slideUp"><?php the_content(); ?></div>
    </article>

<?php
    endwhile;
?>
      </div>
    </section>
<?php
  if(!empty($archives)){
    $title = '<p>Archives</p>';
    $archives = sprintf('<ul>%s</ul>',$archives);
    $archives = $title . $archives;
    $archives = sprintf('<div>%s</div>',$archives);
  }
  echo sprintf('<nav class="filteringBox js-animation-slideUp">%s%s</nav>',filtering_box($args),$archives); ?>
    </div>
<?php
  endif;
  get_footer();