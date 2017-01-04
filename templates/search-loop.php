<div id="primary" class="content-area">
	<main id="main" class="site-main tk-ud-ajax-search-results" role="main">

		<?php
		// The Loop
		if ( $tk_ud_search_query->have_posts() ) {
			echo '<ul>';
			while ( $tk_ud_search_query->have_posts() ) {
				$tk_ud_search_query->the_post() ?>
				<article id="post-<?php echo $post->ID ?>"
				         class="post-<?php echo $post->ID ?> ultimate_directory type-ultimate_directory status-publish hentry">

					<header class="entry-header">
						<h2 class="entry-title">
							<a href="<?php the_permalink(); ?>"
							   rel="bookmark"><?php the_title(); ?></a>
						</h2>
					</header> <!-- .entry-header -->

					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div> <!-- .entry-content -->

				</article>
				<!-- #<?php echo $post->ID ?> -->

				<?php
			}
			echo '</ul>';
			/* Restore original Post Data */
			wp_reset_postdata($tk_ud_search_query);
		} else {
			// no posts found
		}
		?>

	</main>
</div>