<?php

$taxonomy = get_query_var('taxonomy');
$post_type = get_taxonomy($taxonomy)->object_type[0];

get_template_part('archive', $post_type);