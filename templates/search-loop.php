<div id="primary" class="content-area">
	<main id="main" class="site-main tk-ud-ajax-search-results" role="main">
		<?php foreach ( $posts as $post ){ ?>
			<div>
			<article id="post-<?php echo $post->ID ?>" class="post-<?php echo $post->ID ?> ultimate_directory type-ultimate_directory status-publish hentry">

				<header class="entry-header">
					<h2 class="entry-title">
						<a href="<?php echo get_permalink( $post->ID ); ?>" rel="bookmark"><?php echo esc_html( $post->post_title ) ?></a>
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