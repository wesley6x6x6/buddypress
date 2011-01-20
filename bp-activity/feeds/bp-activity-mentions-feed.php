<?php
/**
 * RSS2 Feed Template for displaying a member's group's activity
 *
 * @package BuddyPress
 */
header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
header('Status: 200 OK');
?>
<?php echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	<?php do_action('bp_activity_mentions_feed'); ?>
>

<channel>
	<title><?php bp_site_name() ?> | <?php echo $bp->displayed_user->fullname; ?> | <?php _e( 'Mentions', 'buddypress' ) ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php echo home_url( bp_get_activity_root_slug() . '/#mentions/' ) ?></link>
	<description><?php echo $bp->displayed_user->fullname; ?> - <?php _e( 'Mentions', 'buddypress' ) ?></description>
	<pubDate><?php echo mysql2date('D, d M Y H:i:s O', bp_activity_get_last_updated(), false); ?></pubDate>
	<generator>http://buddypress.org/?v=<?php echo BP_VERSION ?></generator>
	<language><?php echo get_option('rss_language'); ?></language>
	<?php do_action('bp_activity_mentions_feed_head'); ?>

	<?php if ( bp_has_activities( 'max=50&display_comments=stream&search_terms=@' . bp_core_get_username( $bp->displayed_user->id, $bp->displayed_user->userdata->user_nicename, $bp->displayed_user->userdata->user_login ) ) ) : ?>
		<?php while ( bp_activities() ) : bp_the_activity(); ?>
			<item>
				<guid><?php bp_activity_thread_permalink() ?></guid>
				<title><![CDATA[<?php bp_activity_feed_item_title() ?>]]></title>
				<link><?php echo bp_activity_thread_permalink() ?></link>
				<pubDate><?php echo mysql2date('D, d M Y H:i:s O', bp_get_activity_feed_item_date(), false); ?></pubDate>

				<description>
					<![CDATA[
					<?php bp_activity_feed_item_description() ?>

					<?php if ( bp_activity_can_comment() ) : ?>
						<p><?php printf( __( 'Comments: %s', 'buddypress' ), bp_activity_get_comment_count() ); ?></p>
					<?php endif; ?>

					<?php if ( 'activity_comment' == bp_get_activity_action_name() ) : ?>
						<br /><strong><?php _e( 'In reply to', 'buddypress' ) ?></strong> -
						<?php bp_activity_parent_content() ?>
					<?php endif; ?>
					]]>
				</description>
				<?php do_action('bp_activity_mentions_feed_item'); ?>
			</item>
		<?php endwhile; ?>

	<?php endif; ?>
</channel>
</rss>
