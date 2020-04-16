# Ajax load more posts button for WordPress

Loading more posts in WordPress template without reloading the page. For this project I'm using the WordPress REST API and a custom.js file to write down all of the ajax/js code.

## 1. Simple Loop in page template

Create simple loop(WP_Query) to show the first chunk of posts. In my case I am showing the latest 12 posts from the default post post_type: post, but you can modify it with custom post type.

```
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
   'post_type'       => 'post',
   'status'          => 'published',
   'posts_per_page'  => 12,
   'orderby'	     => 'post_date',
   'order'           => 'DESC',
   'paged'           => $paged
);

```

Make sure to add pagination to the query! This will give you the option on how many posts you want to display and when our ajax needs to load the next batch of posts.

```
$loop = new WP_Query( $args );

echo '<div class="post-container">';
while ( $loop->have_posts() ) : $loop->the_post();

    // Display here the post, the_title, post_thumbnail ect.

endwhile;
echo '</div>';
```
Note: the .post-container class is required.

## 2. The load more button

Show the load more button after the loop if there are more posts to show.

```
if($loop->post_count >= 12){
    echo '<div class="load-more-wrapper">
        <a id="ajax-load-more-news" class="more-btn" href="#!">Load More Posts</a>
    </div>';
}
```

## 3. Create a custom WP-API Endpoint

We need to register a custom REST API route for our JSON feed with the posts in functions.php.

```
add_action('rest_api_init', 'custom_api_get_news');
function custom_api_get_news(){
  register_rest_route( 'news', '/all-posts', array(
    'methods' => 'GET',
    'callback' => 'custom_api_get_news_callback'
  ));
}
```

## 4. Populate the JSON feed with data

In this step we create an empty array for each post and populate it with the desired post data. It is important to make the query with the exact same arguments as the

```

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
```

You can open the created custom endpoint in the browser and test if everything works as desired. The url should look like this: http://localhost/wp-json/news/all-posts

## The AJAX script

I am using jQuery to display the fetched post on button click. We get the JSON feed witch is generated with PHP and loop through all object instances with jQuery's iterator function $.each. Then we store the desired information in the string 'item_string'. Finally we append all strings to .post-container.

```
var pull_page = 1;

$('#ajax-load-more-news').on('click', function(){

    var jsonFlag = true;
    if(jsonFlag){

    jsonFlag = false;
    pull_page++;
    $.getJSON("http://localhost/wp-json/news/all-posts?page=" + pull_page, function(data){

    if(data.length){

        var items = [];
        $.each(data, function(key, val){
            var arr = $.map(val, function(el) { return el; });
            var format = arr[0];
            var permalink = arr[1];
            var title = arr[2];
            var thumbnail = arr[3];
            var post_date = arr[4];

            var item_string = '<div class="post-item item size2"><div class="artikel"><a href="'+ permalink +'" class="post-thumb default-thumb" style="background-image: url('+ thumbnail +')"></a><span class="date">'+ post_date +'</span><a href="'+ permalink +'" class="post-title"><h2>'+ title +'</h2></a></div></div>';

            items.push(item_string);
        });
        if(data.length >= 12){

            $('.post-container').append(items);

        } else {

            $('.post-container').append(items);
            $('.load-more-wrapper').hide();

        }

    } else {

        $('.load-more-wrapper').hide();

    }

    }).done(function(data){
        if(data.length){ jsonFlag = true; }
    });}
});

```
## Button style

Finally you have to style in CSS the button .more-button.
