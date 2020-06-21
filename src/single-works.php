<?php
  get_header();
  if(have_posts()):
    while(have_posts()):
      the_post();
?>

    <article class="singleWorks">
      <h1 class="singleWorks_title c-slashBlock js-animation-slideUp"><span><?php the_title(); ?></span><span><?php echo get_post_meta($post->ID,'works_title',true); ?></span></h1>
      <div class="singleWorks_images c-aspectRatio">
        <?php
          $image_group = SCF::get('image_group');
          foreach ($image_group as $field_name => $field_value) {
            $image = wp_get_attachment_image($field_value['image'],'full');
            echo '<div class="js-aspectRatio js-animation-slideUp">'.$image.'</div>';
          }
        ?>
      </div>
      <div class="singleWorks_inner">
        <div class="singleWorks_text js-animation-slideUp">
          <?php echo get_post_meta($post->ID,'works_text',true); ?>
        </div>
        <dl class="singleWorks_list js-animation-slideUp">
          <?php
            $works_place_value = get_post_meta($post->ID,'works_place',true);
            if(!empty($works_place_value)){
              echo '<dt><p>所在地</p></dt>';
              echo '<dd><p>'.$works_place_value.'</p></dd>';
            }
            $works_use_value = get_post_meta($post->ID,'works_use',true);
            if(!empty($works_use_value)){
              echo '<dt><p>用途</p></dt>';
              echo '<dd><p>'.$works_use_value.'</p></dd>';
            }
            $works_scale_value = get_post_meta($post->ID,'works_scale',true);
            if(!empty($works_scale_value)){
              echo '<dt><p>規模</p></dt>';
              echo '<dd><p>'.$works_scale_value.'</p></dd>';
            }
            $works_structure_value = get_post_meta($post->ID,'works_structure',true);
            if(!empty($works_structure_value)){
              echo '<dt><p>構造</p></dt>';
              echo '<dd><p>'.$works_structure_value.'</p></dd>';
            }
            $works_space_value = get_post_meta($post->ID,'works_space',true);
            if(!empty($works_space_value)){
              echo '<dt><p>床面積</p></dt>';
              echo '<dd><p>'.$works_space_value.'</p></dd>';
            }
            $works_designer_value = get_post_meta($post->ID,'works_designer',true);
            if(!empty($works_designer_value)){
              echo '<dt><p>構造設計</p></dt>';
              echo '<dd><p>'.$works_designer_value.'</p></dd>';
            }
            $works_photo_value = get_post_meta($post->ID,'works_photo',true);
            if(!empty($works_photo_value)){
              echo '<dt><p>写真</p></dt>';
              echo '<dd><p>'.$works_photo_value.'</p></dd>';
            }
            $works_termStart_value = get_post_meta($post->ID,'works_termStart',true);
            $works_termEnd_value = get_post_meta($post->ID,'works_termEnd',true);
            if(!empty($works_termStart_value) || !empty($works_termEnd_value)){
              echo '<dt><p>期間</p></dt>';
              if(!empty($works_termStart_value) && !empty($works_termEnd_value)){
                echo '<dd><p>'.$works_termStart_value.'-'.$works_termEnd_value.'</p></dd>';
              }else if(!empty($works_termStart_value) && empty($works_termEnd_value)){
                echo '<dd><p>'.$works_termStart_value.'</p></dd>';
              }else if(empty($works_termStart_value) && !empty($works_termEnd_value)){
                echo '<dd><p>'.$works_termEnd_value.'</p></dd>';
              }
            }
          ?>
        </dl>
      </div>
    </article>

<?php
    endwhile;
  endif;
  get_footer();