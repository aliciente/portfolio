<?php 
/* Don't remove these lines. */
add_filter('comment_text', 'popuplinks');
foreach ($posts as $post) { start_wp();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title><?php echo get_settings('blogname'); ?> - Comentarios de <?php the_title(); ?></title>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_settings('blog_charset'); ?>" />
<style type="text/css" media="screen">
@import url( <?php bloginfo('stylesheet_url'); ?> );
</style>

</head>

<body id="commentspopup">

<div id="comments">

<div id="comments-header"><div id="comments-header-inner">
<h1><a href="" title="<?php echo get_settings('blogname'); ?>"><?php echo get_settings('blogname'); ?></a></h1>
<h2>Comentarios acerca del trabajo '<?php the_title(); ?>'</h2>
<?php } ?>

</div>
</div>

<?php
// this line is WordPress' motor, do not delete it.
$comment_author = (isset($_COOKIE['comment_author_' . COOKIEHASH])) ? trim($_COOKIE['comment_author_'. COOKIEHASH]) : '';
$comment_author_email = (isset($_COOKIE['comment_author_email_'. COOKIEHASH])) ? trim($_COOKIE['comment_author_email_'. COOKIEHASH]) : '';
$comment_author_url = (isset($_COOKIE['comment_author_url_'. COOKIEHASH])) ? trim($_COOKIE['comment_author_url_'. COOKIEHASH]) : '';
$comments = get_approved_comments($id);
$post = get_post($id);
if (!empty($post->post_password) && $_COOKIE['wp-postpass_'. COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
	echo(get_the_password_form());
} else { ?>

<?php if ($comments) { ?>
<?php foreach ($comments as $comment) { ?>
<div class="comment-main" id="comment-<?php comment_ID() ?>">
<div class="gravatar"><?php  echo get_avatar( $comment, 35); ?></div>

<div class="comment-meta">
<span class="comment-meta-author"><?php comment_author_link(); ?></span>
<?php comment_date('M jS, Y') ?> at <?php comment_time() ?>
</div>
<div class="comment-body">
<?php comment_text() ?> 
</div>
</div>
<?php } // end for each comment ?>


<?php } else { // this is displayed if there are no comments so far ?>
<p>No comments yet.</p>
<?php } ?>

<div id="comment-form"><div id="comment-form-inner">

<?php if ('open' == $post->comment_status) { ?>
<h2>Leave a comment</h2>

<div class="comment-form-info">
<p>Line and paragraph breaks automatic, e-mail address never displayed, <acronym title="Hypertext Markup Language">HTML</acronym> allowed: <code><?php echo allowed_tags(); ?></code></p>
</div>

<form action="<?php echo get_settings('siteurl'); ?>/wp-comments-post.php" method="post" id="comments-form">
<p>
<label for="author">Name</label>
<input type="text" name="author" id="author" class="textarea" value="<?php echo $comment_author; ?>" size="28" tabindex="1" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
<input type="hidden" name="redirect_to" value="<?php echo wp_specialchars($_SERVER["REQUEST_URI"]); ?>" />
</p>
<p>
<label for="email">E-mail</label>
<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="28" tabindex="2" />
</p>
<p>
<label for="url">URL</label>
<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="28" tabindex="3" />
</p>
<p>
<label for="comment">Your Comment</label>
<textarea name="comment" id="comment" cols="40" rows="4" tabindex="4"></textarea>
</p>
<p>
<input name="submit" id="comment-submit" type="submit" tabindex="5" value="Submit" />
</p>
<?php do_action('comment_form', $post->ID); ?>
</form>

<?php } else { // comments are closed ?>
<p>Sorry, the comment form is closed at this time.</p>
<?php }
} // end password check
?>

<div id="comments-info">
<p><a href="<?php echo get_settings('siteurl'); ?>/wp-commentsrss2.php?p=<?php echo $post->ID; ?>">RSS feed for comments on this post.</a></p>
<?php if ('open' == $post->ping_status) { ?>
<p class="trackback">The URL to TrackBack this entry is: <br /><em><?php trackback_url() ?></em></p>
</div>

</div></div>

<?php // if you delete this the sky will fall on your head
}
?>

<!-- // this is just the end of the motor - don't touch that line either :) -->
<?php //} ?> 

</div>

</body>
</html>
