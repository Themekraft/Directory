<div id="primary" class="content-area">
	<main id="main" class="site-main tk-ud-ajax-search-result" role="main">
		<?php
		// The Loop
		if ( $tk_ud_search_query->have_posts() ) {
			echo '<ul>';
			while ( $tk_ud_search_query->have_posts() ) {
				$tk_ud_search_query->the_post() ?>
				<article id="post-<?php echo $post->ID ?>" class="post-<?php echo $post->ID ?> ultimate_directory type-ultimate_directory status-publish hentry">

					<header class="entry-header">
						<h2 class="entry-title">
							<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
						</h2>
					</header> <!-- .entry-header -->

					<div class="entry-summary">
						<?php the_excerpt(); ?>
						<?php tk_ud_display_meta( 'loop' ) ?>
					</div> <!-- .entry-content -->

				</article>
				<!-- #<?php echo $post->ID ?> -->

				<?php
			}
			echo '</ul>';
			echo '<input type="hidden" id="tk-ud-paged" value="' . empty( $paged ) ? 1 : $paged .'">';
			the_posts_pagination();
			/* Restore original Post Data */
			wp_reset_postdata($tk_ud_search_query);
		} else {
			echo '<p>' . __('No Posts Found', 'dav') . '</p>';
		}
		?>

	</main>
</div>