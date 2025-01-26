BU Course Feed Block

A WordPress plugin that provides a custom Gutenberg block for displaying Boston University course feeds, complete with filtering options, a REST API for dynamic data, and shortcode support.

Features

Custom Gutenberg Block: Add a block to display courses with options to filter by included courses, excluded courses, and period.

Dynamic Data: Fetch courses dynamically via a custom REST API.

Shortcode Support: Use the [bu-course-feed] shortcode to display course feeds anywhere.

Customizable: Options to show/hide sections and schedules.

Installation

Download the plugin folder and place it in the wp-content/plugins/ directory of your WordPress installation.

Go to Plugins in the WordPress admin dashboard.

Find "BU Course Feed Block" in the list and click Activate.

Usage

Block Editor

Add a new page or edit an existing page/post.

Click the + button to add a new block.

Search for "BU Course Feed Block" and insert it.

Configure the block settings in the sidebar:

Include Courses: Select specific courses to include.

Exclude Courses: Enter course names to exclude (comma-separated).

Period: Filter by a specific period (e.g., Fall, Spring).

Show Sections: Toggle to show or hide sections.

Show Schedules: Toggle to show or hide schedules.

Save or publish the page and preview the frontend.

Shortcode

Use the [bu-course-feed] shortcode in a page, post, or widget. Available attributes:

[bu-course-feed include="Computer Science,Physics" exclude="Mathematics" period="Fall" showSections="true" showSchedules="false"]

include: Comma-separated list of courses to include.

exclude: Comma-separated list of courses to exclude.

period: Filter by period (e.g., Fall, Spring).

showSections: true or false to show/hide sections.

showSchedules: true or false to show/hide schedules.

REST API

A custom REST API endpoint is available for fetching course data dynamically.

Endpoint: /wp-json/bu-course-feed/v1/courses

Example Response:

[
    {
        "id": 1,
        "course": "Computer Science",
        "period": "Fall",
        "sections": ["101", "102", "103"]
    },
    {
        "id": 2,
        "course": "Mathematics",
        "period": "Spring",
        "sections": ["201", "202"]
    }
]

Testing

Block Editor

Add the block to a page.

Test the "Include Courses," "Exclude Courses," and "Period" fields.

Toggle "Show Sections" and "Show Schedules" and verify the preview updates dynamically.

Frontend

Save or publish the page.

Verify the output matches the block configuration.

REST API

Navigate to /wp-json/bu-course-feed/v1/courses in your browser or use a tool like Postman.

Verify the API returns the correct course data.

Shortcode

Add a shortcode to a page or post with different combinations of attributes.

Verify the output matches the specified filters.

