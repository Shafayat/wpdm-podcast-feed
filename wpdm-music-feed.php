<?php
/**
 * Plugin Name: WPDM - Itunes Feed
 * Plugin URI: https://www.wpdownloadmanager.com/download/wpdm-elementor/
 * Description: Download Manger modules for Elementor
 * Version: 1.0.4
 * Author: WordPress Download Manager
 * Text Domain: wpdm-elementor
 * Author URI: https://www.wpdownloadmanager.com/
 */

define('WPDM_ITUNES_FEED_PLUGIN_URL',plugin_dir_url(__FILE__));
add_action('init', 'customRSS');
add_action("init", 'playMedia', 8);
function customRSS()
{
    add_feed('music', 'customRSSFunc');
}

function customRSSFunc()
{
    include 'feed-music.php';
}
function enclosure()
{
    global $post;
    $files = maybe_unserialize(get_post_meta($post->ID, '__wpdm_files', true));
    $ids = array_keys($files);
    $url = \WPDM\Package::expirableDownloadLink($post->ID, 100, 9999999);
    $url .= "&ind={$ids[0]}";
    echo "<enclosure url='{$url}' />";
}

function playMedia()
{

    if (strstr("!{$_SERVER['REQUEST_URI']}", "/wpdm-media/")) {
        $media = explode("wpdm-media/", $_SERVER['REQUEST_URI']);
        $media = explode("/", $media[1]);
        list($ID, $file, $name) = $media;
        $key = wpdm_query_var('_wpdmkey');

        if (isset($_SERVER['HTTP_RANGE'])) {
            $partialContent = true;
            preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
            $offset = intval($matches[1]);
            $length = intval($matches[2]) - $offset;
        } else {
            $partialContent = false;
        }

        $keyValid = is_wpdmkey_valid($ID, $key, true);


        $files = \WPDM\Package::getFiles($ID);
        $file = $files[$file];
        $file = \WPDM\libs\FileSystem::fullPath($file, $ID);
        $stream = new \WPDM\libs\StreamMedia($file);
        $stream->start();
        die();
    }
}