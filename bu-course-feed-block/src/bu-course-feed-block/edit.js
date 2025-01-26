import { useState, useEffect } from '@wordpress/element';
import { SelectControl, PanelBody } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
  const { include, exclude, period } = attributes;

  const [courses, setCourses] = useState([]);
  const [periods, setPeriods] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(false);

  useEffect(() => {
    const fetchCourses = async () => {
      try {
        const response = await fetch('/wp-json/bu-course-feed/v1/courses');
        if (!response.ok) throw new Error('Failed to fetch courses');
        const data = await response.json();

        console.log('Fetched courses:', data); // Debugging: log the data

        // Extract unique periods
        const uniquePeriods = Array.from(new Set(data.map((course) => course.period)));
        setCourses(data);
        setPeriods(uniquePeriods);
        setError(false);
      } catch (error) {
        console.error('Error fetching courses:', error);
        setError(true);
      } finally {
        setLoading(false);
      }
    };

    fetchCourses();
  }, []);

  // Generate dropdown options
  const courseOptions = loading
    ? [{ label: 'Loading...', value: '' }]
    : error
    ? [{ label: 'Error fetching courses', value: '' }]
    : courses.length
    ? [
        { label: 'Select a Course', value: '' },
        ...courses.map((course) => ({
          label: course.course,
          value: course.course,
        })),
      ]
    : [{ label: 'No courses available', value: '' }];

  const periodOptions = loading
    ? [{ label: 'Loading...', value: '' }]
    : error
    ? [{ label: 'Error fetching periods', value: '' }]
    : periods.length
    ? [
        { label: 'Select a Period', value: '' },
        ...periods.map((period) => ({
          label: period,
          value: period,
        })),
      ]
    : [{ label: 'No periods available', value: '' }];

  return (
    <>
      <InspectorControls>
        <PanelBody title="Course Feed Settings" initialOpen={true}>
          <SelectControl
            label="Include Courses"
            value={include}
            options={courseOptions}
            onChange={(value) => setAttributes({ include: value })}
            disabled={loading}
          />
          <SelectControl
            label="Exclude Courses"
            value={exclude}
            options={courseOptions}
            onChange={(value) => setAttributes({ exclude: value })}
            disabled={loading}
          />
          <SelectControl
            label="Period"
            value={period}
            options={periodOptions}
            onChange={(value) => setAttributes({ period: value })}
            disabled={loading}
          />
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps()}>
        {loading ? (
          <p>Loading courses...</p>
        ) : error ? (
          <p>Error loading courses. Please try again later.</p>
        ) : (
          <>
            <p>
              Selected Include: {include || 'None'} <br />
              Selected Exclude: {exclude || 'None'} <br />
              Selected Period: {period || 'Not Set'}
            </p>
            <ul>
              {courses
                .filter((course) => {
                  if (include && course.course !== include) return false;
                  if (exclude && course.course === exclude) return false;
                  if (period && course.period !== period) return false;
                  return true;
                })
                .map((course) => (
                  <li key={course.id}>
                    {course.course} - {course.period}
                  </li>
                ))}
            </ul>
          </>
        )}
      </div>
    </>
  );
}
