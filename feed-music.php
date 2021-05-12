<?php
/**
 * Template Name: Custom RSS Template - Music
 */

use WPDM\Package;

$postCount = 5; // The number of posts to show in the feed
$posts = query_posts('showposts=' . $postCount);
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
echo '<?xml-stylesheet href="' . WPDM_ITUNES_FEED_PLUGIN_URL . 'feed-css.xsl" type="text/xsl"?>'; ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0"
     xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
     xmlns:podcast="https://github.com/Podcastindex-org/podcast-namespace/blob/main/docs/1.0.md">
    <channel>
        <atom:link rel="self" type="application/atom+xml" href="<?php self_link(); ?>" title="RiddimStream Feed"/>
        <atom:link rel="hub" href="https://pubsubhubbub.appspot.com/"/>
        <title>RiddimStream Feed</title>
        <generator>RiddimStream (https://riddimstream.com)</generator>
        <itunes:new-feed-url>https://www.riddimstream.com/feed/music</itunes:new-feed-url>
        <description>RiddimStream</description>
        <copyright>© 2021 RiddimStream</copyright>
        <podcast:locked owner="<?php echo get_option('admin_email'); ?>">yes</podcast:locked>
        <language>en</language>
        <pubDate>Tue, 04 May 2021 09:25:00 -0700</pubDate>
        <lastBuildDate>Sat, 08 May 2021 16:57:33 -0700</lastBuildDate>
        <link>
        https://www.riddimstream.com</link>
        <image>
            <url><?php echo WPDM_ITUNES_FEED_PLUGIN_URL . 'riddimstream.jpg' ?></url>
            <title>RiddimStream Feed</title>
            <link>
            https://www.riddimstream.com</link>
        </image>
        <itunes:category text="Business">
            <itunes:category text="Marketing"/>
        </itunes:category>
        <itunes:category text="Technology"/>
        <googleplay:author>RiddimStream</googleplay:author>
        <googleplay:image href="<?php echo WPDM_ITUNES_FEED_PLUGIN_URL . 'riddimstream.jpg' ?>"/>
        <googleplay:summary>RiddimStream</googleplay:summary>
        <googleplay:explicit>No</googleplay:explicit>
        <googleplay:block>No</googleplay:block>
        <itunes:type>episodic</itunes:type>
        <itunes:author>RiddimStream</itunes:author>
        <itunes:image href="<?php echo WPDM_ITUNES_FEED_PLUGIN_URL . 'riddimstream.jpg' ?>"/>
        <itunes:summary>RiddimStream</itunes:summary>
        <itunes:subtitle>RiddimStream</itunes:subtitle>
        <itunes:keywords>riddimstream, mixtapes
        </itunes:keywords>
        <itunes:owner>
            <itunes:name>RiddimStream</itunes:name>
            <itunes:email><?php echo get_option('admin_email'); ?></itunes:email>
        </itunes:owner>
        <!--        <itunes:complete>No</itunes:complete>-->
        <itunes:explicit>No</itunes:explicit>
        <itunes:block>No</itunes:block>

        <?php
        global $wpdb;
        $args = array(
            'post_type' => 'wpdmpro',
            'post_status' => 'publish',
            'posts_per_page' => 100,
            'orderby' => 'published',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'wpdmcategory',
                    'field' => 'slug',
                    'terms' => 'mixtapes',
                ),
            ),
        );
        $loop = new WP_Query($args);

        while ($loop->have_posts()) : $loop->the_post();
            global $post;

            $files = maybe_unserialize(get_post_meta($post->ID, '__wpdm_files', true));
            $ids = array_keys($files);
            $url = home_url("/wpdm-media/{$post->ID}/{$ids[0]}/play.mp3");
            //$file = UPLOAD_DIR.'/'.$files[];
            $file = WPDM()->package->locateFile($files[$ids[0]]);

            if (filter_var($files[$ids[0]], FILTER_VALIDATE_URL)) {
                $url = $files[$ids[0]];
            }
            ?>
            <item>
                <title><?php the_title_rss(); ?></title>
                <itunes:episode><?php echo $post->ID; ?></itunes:episode>
                <itunes:title><?php the_title_rss(); ?></itunes:title>
                <itunes:episodeType>full</itunes:episodeType>
                <itunes:block>No</itunes:block>
                <googleplay:block>No</googleplay:block>
                <guid isPermaLink="false"><?php echo uniqid(); ?></guid>
                <link><?php the_permalink_rss(); ?></link>
                <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
                <content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
                <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
                <author><?php echo get_option('admin_email'); ?></author>
                <?php echo "<enclosure url='{$url}' length='34872400' type='audio/mpeg'/>"; ?>
                <itunes:author>RiddimStream</itunes:author>
                <itunes:image href="<?php echo WPDM_ITUNES_FEED_PLUGIN_URL . 'riddimstream.jpg' ?>"/>
                <itunes:duration>2890</itunes:duration>
                <itunes:summary>RiddimStream</itunes:summary>
                <itunes:subtitle>RiddimStream</itunes:subtitle>
                <itunes:keywords>riddimstream, mixtapes
                </itunes:keywords>
                <itunes:explicit>No</itunes:explicit>
                <googleplay:explicit>No</googleplay:explicit>
            </item>


        <?php endwhile;
        wp_reset_postdata(); ?>
    </channel>
</rss>