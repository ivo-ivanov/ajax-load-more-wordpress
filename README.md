# Ajax load more posts button for WordPress

Loading more posts in WordPress template without reloading the page. For this project I'm using the WordPress REST API and a custom.js file to write down all of the ajax/js code.

### The Setup

1. Create simple loop(WP_Query) to show the first chunk of posts. In my case I am showing the latest 12 posts from the default post post_type: post, but you can modify it with custom post type.

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
