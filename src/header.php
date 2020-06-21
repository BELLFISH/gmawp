<!DOCTYPE html>
<html lang="ja">
<head prefix="og: http://ogp.me/ns#">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initialscale=1">
  <meta name="format-detection" content="telephone=no">
  <?php wp_head(); ?>
</head>

<body>
<div class="wrapper">
  <header class="js-animation-headerLoading"><?php
    include('template-parts/nav-logo.php');
    include('template-parts/nav-btn.php');
    include('template-parts/nav-menu.php'); ?>
  </header>

  <?php
    $class = '';
    $h1    = '';

    if(is_front_page()){
      $class = 'p-front-page';
      $h1    = get_bloginfo('name');
    }

    if(is_page('about')){
      $class = 'p-about';
      $h1    = 'About Us';
    }

    if(is_post_type_archive()){
      $post_type = get_query_var('post_type');
      $label     = get_post_type_object(get_post_type())->label;

      $class = 'p-archive-'.$post_type.'';
      $h1    = $label;
    }

    if(is_tax()){
      $term      = get_query_var('taxonomy');
      $tax       = get_taxonomy($term);
      $taxLabel  = $term->label;
      $post_type = $tax->object_type[0];
      $label     = get_post_type_object(get_post_type())->label;

      $class = 'p-archive-'.$post_type.'';
      $h1    = $label;
    }

    if(is_single()){
      $post_type = get_query_var('post_type');

      $class = 'p-single-'.$post_type.'';
    }

    if($class != ''){
      echo '<main class="container '.$class.'">';
    }else{
      echo '<main class="container">';
    }

    if($h1 != ''){
      echo '<h1><span class="screen-reader-text">'.$h1.'</span></h1>';
    }
    ?>