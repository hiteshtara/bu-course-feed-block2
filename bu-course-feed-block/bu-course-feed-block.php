<?php

/**
 * Plugin Name:       BU Course Feed Block
 * Description:       Example block scaffolded with Create Block tool, including a shortcode and custom API endpoint.
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

	// Fetch courses from the API.
	$response = wp_remote_get(home_url('/wp-json/bu-course-feed/v1/courses'));
	$courses = array();

	if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
		$courses = json_decode(wp_remote_retrieve_body($response), true);
	}

	// Filter courses based on attributes.
	if (!empty($attributes['include'])) {
		$included = explode(',', $attributes['include']);
		$courses = array_filter($courses, function ($course) use ($included) {
			return in_array($course['course'], $included, true);
		});
	}

	if (!empty($attributes['exclude'])) {
		$excluded = explode(',', $attributes['exclude']);
		$courses = array_filter($courses, function ($course) use ($excluded) {
			return !in_array($course['course'], $excluded, true);
		});
	}

	// Generate output.
	ob_start();
	echo '<div class="bu-course-feed-block">';
	if (!empty($courses)) {
		echo '<ul>';
		foreach ($courses as $course) {
			echo '<li>';
			echo '<strong>' . esc_html($course['course']) . '</strong> (' . esc_html($course['period']) . ')';
			if ($attributes['showSections']) {
				echo '<p>Sections: ' . implode(', ', array_map('esc_html', $course['sections'])) . '</p>';
			}
			echo '</li>';
		}
		echo '</ul>';
	} else {
		echo '<p>No courses found.</p>';
	}
	echo '</div>';

	return ob_get_clean();
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

/**
 * Registers a custom REST API endpoint for fetching course feed data.
 */
function bu_course_feed_register_api()
{
	register_rest_route(
		'bu-course-feed/v1', // Namespace and route
		'/courses',          // Endpoint path
		array(
			'methods'             => 'GET',
			'callback'            => 'bu_course_feed_get_courses',
			'permission_callback' => '__return_true', // No authentication required
		)
	);
}

/**
 * Callback function for the API endpoint.
 *
 * @return array Example course data.
 */
function bu_course_feed_get_courses()
{
	return array(
		array(
			'id'       => 1,
			'course'   => 'Computer Science',
			'period'   => 'Fall',
			'sections' => array('101', '102', '103'),
		),
		array(
			'id'       => 2,
			'course'   => 'Mathematics',
			'period'   => 'Spring',
			'sections' => array('201', '202'),
		),
		array(
			'id'       => 3,
			'course'   => 'Biology',
			'period'   => 'Summer',
			'sections' => array('301', '302', '303'),
		),
		array(
			'id'       => 4,
			'course'   => 'Physics',
			'period'   => 'Fall',
			'sections' => array('401', '402'),
		),
		array(
			'id'       => 5,
			'course'   => 'Chemistry',
			'period'   => 'Spring',
			'sections' => array('501', '502', '503'),
		),
		array(
			'id'       => 6,
			'course'   => 'English Literature',
			'period'   => 'Winter',
			'sections' => array('601'),
		),
		array(
			'id'       => 7,
			'course'   => 'History',
			'period'   => 'Fall',
			'sections' => array('701', '702'),
		),
		array(
			'id'       => 8,
			'course'   => 'Art',
			'period'   => 'Summer',
			'sections' => array('801', '802'),
		),
		array(
			'id'       => 9,
			'course'   => 'Philosophy',
			'period'   => 'Winter',
			'sections' => array('901'),
		),
		array(
			'id'       => 10,
			'course'   => 'Psychology',
			'period'   => 'Spring',
			'sections' => array('1001', '1002'),
		),
	);
}

add_action('rest_api_init', 'bu_course_feed_register_api');
