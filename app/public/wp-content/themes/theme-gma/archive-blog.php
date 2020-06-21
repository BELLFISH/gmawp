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
    <div class="p-archive-blog_wrapper">
    <section class="archiveBlog">
      <div class="p-archive-blog_inner">
<?php
    while(have_posts()):
      the_post();

      $thumbnail = get_the_post_thumbnail($post->ID,'full');
      if(empty($thumbnail)){
        $thumbnail = '<div class="c-thumbnailDummy"></div>';
      }
      $content = get_the_content();
      if(!empty($content)){
        $content = mb_substr($content,0,36) . '...';
      }
?>

      <article class="archiveBlog_item js-animation-slideUp">
        <a href="<?php the_permalink(); ?>" class="c-singleLink">
          <div class="c-singleLink_hoverEffect"><?php echo $thumbnail; ?></div>
          <div class="archiveBlog_textArea">
            <div class="archiveBlog_data c-slashBlock">
              <span><?php the_time('Y-m-d'); ?></span>
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
            <h2 class="archiveBlog_title"><span><?php the_title(); ?></span></h2>
            <div class="archiveBlog_content"><?php echo $content ?></div>
          </div>
        </a>
      </article>

<?php
    endwhile;
?>
      </div>
<?php
    wp_pagenation();
?>
    </section>
<?php
  if(!empty($archives)){
    $title = '<p>Archives</p>';
    $archives = sprintf('<ul>%s</ul>',$archives);
    $archives = $title . $archives;
    $archives = sprintf('<div>%s</div>',$archives);
  }
  echo sprintf('<nav class="filteringBox">%s%s</nav>',filtering_box($args),$archives); ?>
    </div>
<?php
  endif;
  get_footer();