<?php
  get_header();
  $works_posts = get_posts('post_type=works&posts_per_page=5');
  $blog_posts = get_posts('post_type=blog&posts_per_page=3');
  if(!empty($works_posts)) { ?>

    <section class="p-front-page_section archiveWorks">
      <h2><span class="screen-reader-text">Works</span></h2>
      <?php foreach($works_posts as $post) { setup_postdata($post); ?>

      <article class="archiveWorks_item js-animation-slideUp">
        <a href="<?php the_permalink(); ?>" class="c-singleLink -onlyImage">
          <div class="c-aspectRatio">
            <?php
              $image = SCF::get('image');
              if($image) {
                echo '<div class="js-aspectRatio">'.wp_get_attachment_image($image[0],'large').'</div>';
                echo '<div class="js-aspectRatio">'.wp_get_attachment_image($image[1],'large').'</div>';
                echo '<div class="js-aspectRatio">'.wp_get_attachment_image($image[2],'large').'</div>';
              }else{
                echo '<div class="c-thumbnailDummy"></div>';
              }
            ?>
          </div>
        </a>
      </article>
      <?php } ?>

      <div class="p-front-page_archiveLink"><a href="/works/"><span class="screen-reader-text">Works</span></a></div>
    </section>
<?php
  wp_reset_postdata(); }
  if(!empty($works_posts)) { ?>

    <section class="p-front-page_section archiveBlog">
      <h2 class="p-front-page_title">Blog</h2>
      <div class="p-front-page_l-LG-blog">
      <?php foreach($blog_posts as $post) { setup_postdata($post);
        $thumbnail = get_the_post_thumbnail($post->ID,'full');
        if(empty($thumbnail)){
          $thumbnail = '<div class="c-thumbnailDummy"></div>';
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
          </div>
        </a>
      </article>
      <?php } ?>

      </div>
      <div class="p-front-page_archiveLink"><a href="/blog/"><span class="screen-reader-text">Blog</span></a></div>
    </section>
<?php
  wp_reset_postdata(); } ?>

    <section class="p-front-page_section contact" id="contact">
      <div>
        <h2 class="p-front-page_title">Contact</h2>
      </div>
      <div>
        <p>お仕事のご依頼、求人、取材など各種お問い合わせは下記フォームよりお願い致します。<br>内容を確認のうえ、ご連絡致します。</p>
        <p class="contact_heading">フォームからお問い合わせ</p>
        <?php echo do_shortcode('[mwform_formkey key="308"]'); ?>
      </div>
    </section>
<?php
  get_footer();