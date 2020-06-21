<?php

// テーマ内関数呼び出し ----------
require get_theme_file_path('/inc/template-functions.php');

// カスタム投稿タイプ定義呼び出し ----------
require get_theme_file_path('/inc/custom-post-type.php');

// --------------------------------------------------
function theme_setup(){
  // サムネイルを使用 ----------
  add_theme_support('post-thumbnails');
  // タイトルタグを出力 ----------
  add_theme_support( 'title-tag' );
}
add_action('after_setup_theme','theme_setup');



// 全ページ用CSS/JS
// --------------------------------------------------
function add_assets(){
  wp_enqueue_style('style',get_template_directory_uri().'/assets/css/style.css');
  wp_enqueue_script('jQuery',get_template_directory_uri().'/assets/lib/jquery-3.5.0.min.js',array(),'',true);
  wp_enqueue_script('ScrollMagic','//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.min.js',array(),'',true);
  wp_enqueue_script('script',get_template_directory_uri().'/assets/js/script.js',array(),'',true);
}
add_action('wp_enqueue_scripts','add_assets');

// 年表記の書き換え
// --------------------------------------------------
function my_gettext($translated_text,$original_text,$domain){
  if($original_text == '%1$s %2$d'){
    $translated_text = '%2$s.%1$02d';
  }
  return $translated_text;
}
add_filter('gettext','my_gettext',20,3);

// 不要なページを404扱い
// --------------------------------------------------
function handle_404(){
  $terms = is_search() || is_author() || is_attachment();
  if($terms){
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    nochache_headers();
  }
}
add_action('template_redirect','handle_404');

// mwform バリデーション
// --------------------------------------------------
function add_mwform_validation_rule($Validation,$data){
  $validation_message = '入力項目に未入力があります。';
  if(empty($data['name'])){
    $Validation->set_rule('name','noempty',array('message' => $validation_message));
  }elseif(empty($data['mail'])){
    $Validation->set_rule('mail','noempty',array('message' => $validation_message));
  }elseif(empty($data['message'])){
    $Validation->set_rule('message','noempty',array('message' => $validation_message));
  }
  return $Validation;
}
add_filter('mwform_validation_mw-wp-form-308','add_mwform_validation_rule',10,3);

// meta
// --------------------------------------------------
// タイトルタグをカスタマイズ ----------
function org_pre_get_document_title($title){
  $site_name = get_bloginfo('name');

  global $year;
  $post_month = get_the_date('n');
  $post_month = sprintf("%02d",$post_month);

  if(is_post_type_archive()){
    if(is_date()){
      $date  = sprintf($year);
      $date .= sprintf('.'.$post_month);
      $title = sprintf('%s | %s | %s',$date,post_type_archive_title('',false),$site_name);
    }else{
      $title = sprintf('%s | %s',post_type_archive_title('',false),$site_name);
    }
  }

  if(is_tax()){
    $current_term    = get_queried_object();
    $term            = get_query_var('taxonomy');
    $tax             = get_taxonomy($term);
    $post_type       = $tax->object_type[0];
    $post_type_label = get_post_type_object($post_type)->label;

    $title = sprintf('%s | %s | %s',$current_term->name,$post_type_label,$site_name);
  }

  if(is_page() && !(is_front_page())){
    $title = sprintf('%s | %s',single_post_title('',false),$site_name);
  }

  if(is_single()){
      $post_type = get_query_var('post_type');
      $post_type_label = get_post_type_object($post_type)->label;

      $title = sprintf('%s | %s | %s',single_post_title('',false),$post_type_label,$site_name);
  }

  if(is_404()){
      $title = sprintf('%s | %s','404 not found',$site_name);
  }

  if(is_front_page()){
      $title = $site_name;
  }

  return $title;
}
add_filter('pre_get_document_title', 'org_pre_get_document_title');

// OGPタグを<head>に出力 ----------
function ogp_setting(){
  global $post;
  $site['og_type'] = (is_front_page()) ? 'website' : 'article';
  $site['name']    = get_bloginfo('name');

  if(is_post_type_archive()){
    $current_post_type = get_post_type();
    $archives_meta = [
      'works' => [
        'description' => 'worksのdescription',
      ],
      'blog' => [
        'description' => 'blogのdescription',
      ]
    ];
    $current_page['description'] = $archives_meta[$current_post_type]['description'];
  }

  if(is_tax()){
    $current_obj = get_queried_object();
    $taxonomy = $current_obj->taxonomy;
    $name = $current_obj->name;
    $taxonomy_meta = [
      'blog_category' => [
        'description' => 'blogのdescription',
      ]
    ];
    $current_page['description'] = $taxonomy_meta[$taxonomy]['description'];
  }

  if(is_date()){
    $current_post_type = get_post_type();
    $archives_meta = [
      'blog' => [
        'description' => 'blogのdescription',
      ]
    ];
    $current_page['description'] = $archives_meta[$current_post_type]['description'];
  }

  if(is_single()){
    $current_data = [];
    if(get_post_type() === 'works'){
      $image   = SCF::get('image');
      $image   = wp_get_attachment_image_url($image[0],'large');
      $content = get_post_meta($post->ID,'works_text',true);;
      $current_data = [
        'description' => mb_substr($content, 0, 140),
        'image'       => $image
      ];
    }
    if(get_post_type() === 'blog'){
      $thumbnail = get_the_post_thumbnail_url($post->ID, 'full');
      $content   = wp_strip_all_tags(get_post($post)->post_content);
      $current_data = [
        'description' => mb_substr($content, 0, 140),
        'image'       => $thumbnail
      ];
    }
    $current_page = [
      'description' => (!empty($current_single['description'])) ? $current_single['description'] : $current_data['description'],
      'image'       => (!empty($current_single['image'])) ? $current_single['image'] : $current_data['image']
    ];
  }

  if(!empty($current_page)){
    $page['description'] = (!empty($current_page['description'])) ? $current_page['description'] : $site['description'];
    $page['image']       = (!empty($current_page['image'])) ? $current_page['image'] : $site['image'];
  }

  $page = [
    'description' => (!empty($page['description'])) ? $page['description'] : $site['description'],
    'image'       => (!empty($page['image'])) ? $page['image'] : $site['image'],
    'url'         => esc_url((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]),
    'title'       => wp_get_document_title()
  ];

  ob_start();

  ?>
  <meta name="description" content="<?php echo $page['description']; ?>">
  <meta property="og:description" content="<?php echo $page['description']; ?>">
  <meta property="og:site_name" content="<?php echo $site['name']; ?>">
  <meta property="og:locale" content="ja_JP">
  <meta property="og:type" content="<?php echo $site['og_type']; ?>">
  <meta property="og:image" content="<?php echo $page['image']; ?>">
  <meta property="og:url" content="<?php echo $page['url']; ?>">
  <meta property="og:title" content="<?php echo $page['title']; ?>">
  <?php

  $html = ob_get_contents();
  ob_end_clean();

  echo $html;
}
add_action('wp_head', 'ogp_setting', 1);



// 管理画面
// --------------------------------------------------

// サイドメニューカスタマイズ ----------
function remove_menus(){
  // remove_menu_page( 'index.php' );                // ダッシュボード
  remove_menu_page( 'edit.php' );                 // 投稿
  // remove_menu_page( 'upload.php' );               // メディア
  remove_menu_page( 'edit.php?post_type=page' );  // 固定ページ
  remove_menu_page( 'edit-comments.php' );        // コメント
  remove_menu_page( 'themes.php' );               // 外観
  // remove_menu_page( 'plugins.php' );              // プラグイン
  // remove_menu_page( 'users.php' );                // ユーザー
  // remove_menu_page( 'tools.php' );                // ツール
  // remove_menu_page( 'options-general.php' );      // 設定
}
add_action('admin_menu','remove_menus');

// 管理画面CSS適用 ----------
function my_admin_style() {
  wp_enqueue_style('my_admin_style',get_template_directory_uri().'/assets/css/admin.css');
}
add_action('admin_print_styles','my_admin_style');

// 投稿画面CSS適用 ----------
function add_editor_styles(){
  add_editor_style(get_template_directory_uri().'/assets/css/editor-style.css');
}
add_action('admin_init','add_editor_styles');

// Gutenbergを無効化 ----------
add_filter('use_block_editor_for_post', '__return_false', 10);

function my_remove_post_editor_support() {
 remove_post_type_support('works','editor');
}
add_action('init','my_remove_post_editor_support');
