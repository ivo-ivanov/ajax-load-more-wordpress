		<section id="news">

		<?php
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array(
		   'post_type'     => 'post',
		   'status'        => 'published',
		   'posts_per_page'=> 12,
		   'orderby'	=> 'post_date',
		   'order'         => 'DESC',
		   'paged'         => $paged
		);

		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();

			$id = $post->ID;
			$permalink = get_the_permalink();
			$postFormat = get_post_format();
			$post_thumbnail = (has_post_thumbnail($id)) ? wp_get_attachment_image_src(get_post_thumbnail_id( $id ), 'large')['0'] : '';

			echo '<div class="post-item '. esc_attr($postFormat) .'">
				<div class="artikel">';

					echo '<a href="'. esc_url($permalink) .'" class="post-thumb default-thumb" style="background-image: url('. esc_url($post_thumbnail) .')"></a>

					<span class="date">'. get_the_time('j. F y') .'</span>

					<a href="'. esc_url($permalink) .'" class="post-title">
					   <h2>'. get_the_title() . '</h2>
					</a>';

				echo '</div>
			</div>';

		endwhile;

		?>

	</section>
	
	<?php if($loop->post_count >= 12){ ?>
		<div class="load-more-wrapper">
			<a id="ajax-load-more-news" class="more-btn" href="#!">Mehr anzeigen</a>
		</div>
   	<?php } ?>
