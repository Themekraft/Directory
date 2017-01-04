<div id="primary" class="content-area">
	<main id="main" class="site-main tk-ud-ajax-search-results" role="main">

		<?php
		// The Loop
		if ( $tk_ud_search_query->have_posts() ) {
			echo '<ul>';
			while ( $tk_ud_search_query->have_posts() ) {
				$tk_ud_search_query->the_post();
				echo '<li>' . get_the_title() . '</li>';
			}
			echo '</ul>';
			/* Restore original Post Data */
			wp_reset_postdata($tk_ud_search_query);
		} else {
			// no posts found
		}
		?>



		<?php foreach ( $tk_ud_search_query as $post ){ ?>
		<div>
			<article id="post-<?php echo $post->ID ?>"
			         class="post-<?php echo $post->ID ?> ultimate_directory type-ultimate_directory status-publish hentry">

				<header class="entry-header">
					<h2 class="entry-title">
						<a href="<?php echo get_permalink( $post->ID ); ?>"
						   rel="bookmark"><?php echo esc_html( $post->post_title ) ?></a>
					</h2>
				</header> <!-- .entry-header -->

				<div class="entry-summary">
					<?php echo $post->post_content; ?>
				</div> <!-- .entry-content -->

			</article>
			<!-- #<?php echo $post->ID ?> -->
			<?php } ?>
	</main>
</div>