<?php

// ぱんくず
// --------------------------------------------------
function get_crumbs(){
  global $post;
  global $post_type;
  global $year;
  $post_month = get_the_date('n');
  $post_month = sprintf("%02d",$post_month);

  $link_format = '<li><a href="%s">%s</a></li>';
  $current_format = '<li><span>%s</span></li>';

  $li = null;

  if(is_post_type_archive()){
    if(is_date()){
      $li .= sprintf($link_format,get_post_type_archive_link($post_type),post_type_archive_title('',false));
      $date = sprintf($year);
      $date .= sprintf('.'.$post_month);
      $li .= sprintf($current_format, $date);
    }else{
      $li .= sprintf($current_format,post_type_archive_title('',false));
    }
  }

  if (is_tax()) {
    $current_term = get_queried_object();
    $term = get_query_var('taxonomy');
    $tax = get_taxonomy($term);
    $post_type = $tax->object_type[0];
    $post_type_label = get_post_type_object($post_type)->label;
    $post_type_link = get_post_type_archive_link($post_type);

    $li .= sprintf($link_format, $post_type_link, $post_type_label);

    $li .= sprintf($current_format, $current_term->name);
  }

  if (is_page() && !(is_front_page())) {
    $parend_id = $post->post_parent;
    if ($parend_id !== 0) {
      $li .= sprintf($link_format, get_permalink($parend_id), get_post($parend_id)->post_title);
    }
    $li .= sprintf($current_format, single_post_title('', false));
  }

  if(is_single()){
    $post_type = get_query_var('post_type');
    $post_type_label = get_post_type_object($post_type)->label;
    $post_type_link = get_post_type_archive_link($post_type);
    $li .= sprintf($link_format,$post_type_link,$post_type_label);
    $li .= sprintf($current_format,single_post_title('',false));
  }

  if(is_404()){
    $li .= sprintf($current_format,'404 not found');
  }

  ob_start();
  ?>
  <nav class="crumbs">
    <ul>
      <li><a href="/">Home</a></li>
      <?php echo $li; ?>

    </ul>
  </nav>
  <?php
  $str = ob_get_contents();
  ob_end_clean();

  return $str;
}

// ページネーション
// --------------------------------------------------
function wp_pagenation(){
  global $wp_query;
  $big = 999999999;

  $total = $wp_query->max_num_pages;
  $total = (!is_paged()) ? ceil ( ( $wp_query->found_posts - 1 ) / 4 ) : $total;

  $links = paginate_links([
    'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format'    => '?paged=%#%',
    'current'   => max(1, get_query_var('paged')),
    'total'     => $total,
    'mid_size'  => 1,
    'prev_text' => '→',
    'next_text' => '→'
  ]);

  if (empty($links)) {
    return false;
  }

  printf('<nav class="pagenation">%s</nav>', $links);
}

// カスタム投稿の絞り込み（タクソノミー）
// --------------------------------------------------
function filtering_box($args){
  $wrap = null;
  foreach($args as $arg){
    $categories = get_categories([
      'taxonomy' => $arg['taxonomy']
    ]);
    if(empty($categories)){
      continue;
    }
    $content = sprintf('<p>%s</p>',$arg['name']);
    foreach($categories as $category){
      $item .= sprintf('<li><a href="%s">%s</a></li>',get_term_link($category),$category->name);
    }
    $content .= sprintf('<ul>%s</ul>',$item);
    $wrap .= sprintf('<div>%s</div>',$content);
  }
  return $wrap;
}





















