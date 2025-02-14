<?php
/*
Template Name: Tabbed Page
*/
get_header(); ?>

<div class="container">
   

        <div class="tabs">
            <ul class="tab-links">
                <li class="active"><a href="#tab1">Description</a></li>
                <li><a href="#tab2">YouTube Playlist</a></li>
                <li><a href="#tab3">Featured Image</a></li>
            </ul>
			
	<?php 
	$args = array(
        'post_type' => 'post',
        'posts_per_page' => -1, // Change this to the number of posts you want to display
    );
    $query = new WP_Query($args);
	
	if ($query->have_posts()) : 
		while ($query->have_posts()) : $query->the_post(); ?>
            <div class="tab-content">
                <!-- First Tab: Post Content -->
                <div id="tab1" class="tab active">
                    <h2><?php the_title(); ?></h2>
                    <p><?php the_content(); ?></p>
                </div>

                <!-- Second Tab: YouTube Playlist -->
                <div id="tab2" class="tab">
                    <div class="lazy-load" data-url="<?php echo esc_url(get_post_meta(get_the_ID(), 'youtube_playlist', true)); ?>">
                        <p>Loading YouTube Playlist...</p>
                    </div>
                </div>

                <!-- Third Tab: Post Featured Image -->
                <div id="tab3" class="tab">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="lazy-load" data-url="<?php echo esc_url(get_the_post_thumbnail_url()); ?>">
                            <p>Loading Featured Image...</p>
                        </div>
                    <?php else : ?>
                        <p>No featured image available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php endwhile; else : ?>
        <p>No content found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>