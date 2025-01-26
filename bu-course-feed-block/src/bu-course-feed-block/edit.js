import { useState, useEffect } from '@wordpress/element';
import { SelectControl, TextControl, ToggleControl, PanelBody } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
  const { include, exclude, period, showSections, showSchedules } = attributes;

  // State for API data
  const [courses, setCourses] = useState([]);
  const [loading, setLoading] = useState(true);

  // Fetch courses from API
  useEffect(() => {
    const fetchCourses = async () => {
      try {
        const response = await fetch('/wp-json/bu-course-feed/v1/courses');
        const data = await response.json();
        setCourses(data);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching courses:', error);
        setLoading(false);
      }
    };

    fetchCourses();
  }, []);

  // Dropdown options
  const courseOptions = loading
    ? [{ label: 'Loading...', value: '' }]
    : courses.length
    ? [
        { label: 'Select a Course', value: '' },
        ...courses.map((course) => ({
          label: course.course,
          value: course.course,
        })),
      ]
    : [{ label: 'No courses available', value: '' }];

  return (
    <>
      {/* Inspector Controls */}
      <InspectorControls>
        <PanelBody title="Course Feed Settings" initialOpen={true}>
          <SelectControl
            label="Include Courses"
            value={include}
            options={courseOptions}
            onChange={(value) => setAttributes({ include: value })}
            disabled={loading}
          />
          <TextControl
            label="Exclude Courses"
            value={exclude}
            onChange={(value) => setAttributes({ exclude: value })}
          />
          <TextControl
            label="Period"
            value={period}
            onChange={(value) => setAttributes({ period: value })}
          />
          <ToggleControl
            label="Show Sections"
            checked={showSections}
            onChange={(value) => setAttributes({ showSections: value })}
          />
          <ToggleControl
            label="Show Schedules"
            checked={showSchedules}
            onChange={(value) => setAttributes({ showSchedules: value })}
          />
        </PanelBody>
      </InspectorControls>

      {/* Block Content */}
      <div {...useBlockProps()}>
        <p>
          Selected Course: {include || 'None'} <br />
          Period: {period || 'Not Set'}
        </p>
        {showSections && <p>Sections are displayed.</p>}
        {showSchedules && <p>Schedules are displayed.</p>}
      </div>
    </>
  );
}
