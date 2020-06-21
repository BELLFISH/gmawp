<?php
  get_header();
  $archives = wp_get_archives([
    'post_type' => 'works',
    'type' => 'postbypost',
    'format' => 'option',
    'echo' => 0,
    'order' => 'DESC',
  ]);
  if(have_posts()):
?>
    <section class="archiveWorks">
<?php
    while(have_posts()):
      the_post();
        $thumbnail = get_the_post_thumbnail($post->ID,'full');
        if(empty($thumbnail)){
          $thumbnail = '<div class="c-thumbnailDummy"></div>';
        }
?>
      <article class="archiveWorks_item js-animation-slideUp">
        <a href="<?php the_permalink(); ?>" class="c-singleLink">
          <div class="c-singleLink_hoverEffect">
            <?php
              $image = SCF::get('image');
              if($image) {
                echo wp_get_attachment_image($image[0],'large');
              }else{
                echo $thumbnail;
              }
            ?>
          </div>
          <div class="archiveWorks_textArea">
            <p class="archiveWorks_term"><?php echo get_post_meta($post->ID,'works_termEnd',true); ?></p>
            <h2 class="archiveWorks_title c-slashBlock"><span><?php the_title(); ?></span><span><?php echo get_post_meta($post->ID,'works_title',true); ?></span></h2>
          </div>
        </a>
      </article>

<?php
    endwhile;
?>
    </section>
<?php
  endif;
  get_footer();