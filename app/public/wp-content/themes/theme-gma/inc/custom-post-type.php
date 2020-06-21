<?php

// カスタム投稿タイプをセット
// --------------------------------------------------
function add_custom_post_types(){
  // blog ----------
  $post_types[] = [
    'post_type' => 'blog',
    'args' => [
      'public' => true,
      'has_archives' => true,
      'supports' => [
        'title',
        'editor',
        'thumbnail'
      ],
      'menu_position' => 5,
      'menu_ison' => 'dashicons-screenoptions',
      'rewrite' => [
        'with_front' => false
      ],
    ]
  ];
  // works ----------
  $post_types[] = [
    'post_type' => 'works',
    'args' => [
      'public' => true,
      'has_archives' => true,
      'supports' => [
        'title',
        'editor',
        'thumbnail'
      ],
      'menu_position' => 5,
      'menu_ison' => 'dashicons-screenoptions',
      'rewrite' => [
        'with_front' => false
      ],
    ]
  ];

  return $post_types;
}

function create_custom_post_type(){
  $types = add_custom_post_types();
  foreach($types as $type){
    register_post_type($type['post_type'],$type['args']);
  }
}
add_action('init','create_custom_post_type');

// アーカイブを作成
// --------------------------------------------------
function post_has_archive_blog($args,$post_type){
  if('blog' == $post_type){
    $args['rewrite'] = true;
    $args['has_archive'] = 'blog';
    $args['label'] = 'Blog';
  }
  return $args;
}
add_filter('register_post_type_args','post_has_archive_blog',10,2);

function post_has_archive_works($args,$post_type){
  if('works' == $post_type){
    $args['rewrite'] = true;
    $args['has_archive'] = 'works';
    $args['label'] = 'Works';
  }
  return $args;
}
add_filter('register_post_type_args','post_has_archive_works',10,2);

// アーカイブ表示件数
// --------------------------------------------------
function change_posts_per_page($query){
  if(is_admin() || !$query->is_main_query()){
    return;
  }else if($query->is_post_type_archive('blog')){
    $query->set('posts_per_page','6');
  }
}
add_action('pre_get_posts','change_posts_per_page');

// カスタムタクソノミーをセット
// --------------------------------------------------
function add_custom_taxonomy(){
  // blog ----------
  $taxonomies[] = [
    'taxonomy' => 'blog_category',
    'post_type' => 'blog',
    'args' => [
      'label' => 'カテゴリー',
      'show_admin_columns' => true,
      'hierarchical' => true,
      'rewrite' => [
        'slug' => 'blog/category',
        'with_front' => false,
        'hierarchical' => true
      ]
    ]
  ];

  return $taxonomies;
}

function create_custom_taxonomy(){
  $taxonomies = add_custom_taxonomy();
  foreach($taxonomies as $taxonomy){
    register_taxonomy($taxonomy['taxonomy'],$taxonomy['post_type'],$taxonomy['args']);
  }
}
add_action('init','create_custom_taxonomy');

function register_taxonomy_post_process(){
  $taxonomies = add_custom_taxonomy();
  foreach($taxonomies as $taxonomy){
    $regex = $taxonomy['args']['rewrite']['slug'].'/([^/]+)/?$';
    $query = 'index.php?'.$taxonomy['taxonomy'].'=$matches[1]';
    add_rewrite_rule($regex,$query,'top');
  }
}
add_action('init','register_taxonomy_post_process');

// カスタムフィールド
// --------------------------------------------------
function add_custom_fields(){
  // works ----------
  add_meta_box(
    'works_custom_field_title',
    '英語タイトル',
    'insert_works_custom_field_title',
    'works',
    'normal'
  );
  add_meta_box(
    'works_custom_field_text',
    'テキスト',
    'insert_works_custom_field_text',
    'works',
    'normal'
  );
  add_meta_box(
    'works_custom_field_info',
    '情報',
    'insert_works_custom_field_info',
    'works',
    'normal'
  );
}
add_action('admin_menu','add_custom_fields');

// works ----------
// 英語タイトル
function insert_works_custom_field_title(){
  global $post;
  echo '<div class="works_custom_field_title_wrap"><textarea name="works_title">'.get_post_meta($post->ID,'works_title',true).'</textarea></div>';
}
// テキスト
function insert_works_custom_field_text(){
  global $post;
  echo '<div class="works_custom_field_text_wrap"><textarea name="works_text">'.get_post_meta($post->ID,'works_text',true).'</textarea></div>';
}
// 情報
function insert_works_custom_field_info(){
  global $post;
  echo '<div class="works_custom_field_info_note"><p>すべて埋まっていなくても問題ありません</p></div>';
  echo '<div class="works_custom_field_info_wrap"><p>所在地</p><div><textarea name="works_place">'.get_post_meta($post->ID,'works_place',true).'</textarea></div></div>';
  echo '<div class="works_custom_field_info_wrap"><p>用途</p><div><textarea name="works_use">'.get_post_meta($post->ID,'works_use',true).'</textarea></div></div>';
  echo '<div class="works_custom_field_info_wrap"><p>規模</p><div><textarea name="works_scale">'.get_post_meta($post->ID,'works_scale',true).'</textarea></div></div>';
  echo '<div class="works_custom_field_info_wrap"><p>構造</p><div><textarea name="works_structure">'.get_post_meta($post->ID,'works_structure',true).'</textarea></div></div>';
  echo '<div class="works_custom_field_info_wrap"><p>床面積</p><div><textarea name="works_space">'.get_post_meta($post->ID,'works_space',true).'</textarea></div></div>';
  echo '<div class="works_custom_field_info_wrap"><p>構造設計</p><div><textarea name="works_designer">'.get_post_meta($post->ID,'works_designer',true).'</textarea></div></div>';
  echo '<div class="works_custom_field_info_wrap"><p>写真</p><div><textarea name="works_photo">'.get_post_meta($post->ID,'works_photo',true).'</textarea></div></div>';
  echo '<div class="works_custom_field_info_wrap"><p>期間 - はじまり</p><div><textarea name="works_termStart">'.get_post_meta($post->ID,'works_termStart',true).'</textarea></div></div>';
  echo '<div class="works_custom_field_info_wrap"><p>期間 - おわり</p><div><textarea name="works_termEnd">'.get_post_meta($post->ID,'works_termEnd',true).'</textarea></div></div>';
}

function save_custom_fields_of_works($post_id){
  // 英語タイトル
  if(!empty($_POST['works_title'])) { update_post_meta($post_id,'works_title',$_POST['works_title']); } else { delete_post_meta($post_id,'works_title'); }
  // テキスト
  if(!empty($_POST['works_text'])) { update_post_meta($post_id,'works_text',$_POST['works_text']); } else { delete_post_meta($post_id,'works_text'); }
  // 情報
  if(!empty($_POST['works_place'])) { update_post_meta($post_id,'works_place',$_POST['works_place']); } else { delete_post_meta($post_id,'works_place'); }
  if(!empty($_POST['works_use'])) { update_post_meta($post_id,'works_use',$_POST['works_use']); } else { delete_post_meta($post_id,'works_use'); }
  if(!empty($_POST['works_scale'])) { update_post_meta($post_id,'works_scale',$_POST['works_scale']); } else { delete_post_meta($post_id,'works_scale'); }
  if(!empty($_POST['works_structure'])) { update_post_meta($post_id,'works_structure',$_POST['works_structure']); } else { delete_post_meta($post_id,'works_structure'); }
  if(!empty($_POST['works_space'])) { update_post_meta($post_id,'works_space',$_POST['works_space']); } else { delete_post_meta($post_id,'works_space'); }
  if(!empty($_POST['works_designer'])) { update_post_meta($post_id,'works_designer',$_POST['works_designer']); } else { delete_post_meta($post_id,'works_designer'); }
  if(!empty($_POST['works_photo'])) { update_post_meta($post_id,'works_photo',$_POST['works_photo']); } else { delete_post_meta($post_id,'works_photo'); }
  if(!empty($_POST['works_termStart'])) { update_post_meta($post_id,'works_termStart',$_POST['works_termStart']); } else { delete_post_meta($post_id,'works_termStart'); }
  if(!empty($_POST['works_termEnd'])) { update_post_meta($post_id,'works_termEnd',$_POST['works_termEnd']); } else { delete_post_meta($post_id,'works_termEnd'); }
}
add_action('save_post','save_custom_fields_of_works');