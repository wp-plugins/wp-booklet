<?php if ( $post->post_status == 'publish' ) : ?>
<p>Copy and paste the following shortcode to the page/post the booklet must appear on.</p>
<code class='wide'> [wp-booklet id=<?php echo $booklet->get_shortcode_id() ?>]</code>
<?php else : ?>
<p>Publish this post to use the shortcode.</code>
<?php endif ?>