<?php

/**
 * Plugin Name:       BU Course Feed Block
 * Description:       Example block scaffolded with Create Block tool, including a shortcode.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bu-course-feed-block
 *
 * @package BU_Course_Feed_Block
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function bu_course_feed_block_init()
{
	$block_path = __DIR__ . '/build/bu-course-feed-block';

	// Check if the block.json exists to prevent errors.
	if (file_exists($block_path . '/block.json')) {
		register_block_type($block_path, array(
			'render_callback' => 'render_bu_course_feed_block',
		));
	} else {
		error_log('BU Course Feed Block: block.json not found in ' . $block_path);
	}
}
add_action('init', 'bu_course_feed_block_init');

/**
 * Render callback for the BU Course Feed Block.
 *
 * @param array $attributes The block attributes.
 * @return string The rendered output.
 */
function render_bu_course_feed_block($attributes)
{
	// Parse and sanitize attributes with default values.
	$attributes = shortcode_atts(array(
		'include'        => '',
		'exclude'        => '',
		'period'         => '',
		'showSections'   => false,
		'showSchedules'  => false,
	), $attributes, 'bu-course-feed');

	$include = esc_attr($attributes['include']);
	$exclude = esc_attr($attributes['exclude']);
	$period = esc_attr($attributes['period']);
	$show_sections = $attributes['showSections'] ? 'true' : 'false';
	$show_schedules = $attributes['showSchedules'] ? 'true' : 'false';

	// Generate the output.
	return sprintf(
		'<div class="bu-course-feed-block" data-include="%s" data-exclude="%s" data-period="%s" data-show-sections="%s" data-show-schedules="%s">Course feed content goes here.</div>',
		$include,
		$exclude,
		$period,
		$show_sections,
		$show_schedules
	);
}

/**
 * Register the shortcode for BU Course Feed.
 *
 * @param array $atts Shortcode attributes.
 * @return string Rendered content.
 */
function bu_course_feed_shortcode($atts)
{
	$atts = shortcode_atts(array(
		'include'        => '',
		'exclude'        => '',
		'period'         => '',
		'showSections'   => false,
		'showSchedules'  => false,
	), $atts, 'bu-course-feed');

	// Use the same logic as the block's render callback.
	return render_bu_course_feed_block($atts);
}
add_shortcode('bu-course-feed', 'bu_course_feed_shortcode');
