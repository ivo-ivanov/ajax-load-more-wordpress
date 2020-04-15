<?php

// ajax custom laoding NEWS
add_action('rest_api_init', 'custom_api_get_news');
function custom_api_get_news(){
  register_rest_route( 'news', '/all-posts', array(
    'methods' => 'GET',
    'callback' => 'custom_api_get_news_callback'
  ));
}

function custom_api_get_news_callback($request){
    $posts_data = array();
    $paged = $request->get_param('page');
    $paged = (isset($paged) || !(empty($paged))) ? $paged : 1;
    $posts = get_posts( array(
      'post_type'       => 'post',
      'status'          => 'published',
      'posts_per_page'  => 12,
      'orderby'         => 'post_date',
      'order'           => 'DESC',
      'paged'           => $paged
    ));
    foreach($posts as $post){
      $id = $post->ID;
      $post_thumbnail = (has_post_thumbnail($id)) ? wp_get_attachment_image_src(get_post_thumbnail_id( $id ), 'large') : '';
      $post_date = get_the_time('j. F y', $id);

      $posts_data[] = (object)array(
        'format' => esc_attr(get_post_format($id)),
        'permalink' => esc_url(get_the_permalink($id)),
        'title' => esc_html($post->post_title),
        'thumbnail' => esc_url($post_thumbnail['0']),
        'date' => esc_html($post_date)
      );
    }
    return $posts_data;
}

?>
