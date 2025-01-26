import { TextControl, ToggleControl } from '@wordpress/components';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
  const { include, exclude, period, showSections, showSchedules } = attributes;

  return (
    <div {...useBlockProps()}>
      <TextControl
        label="Include Courses"
        value={include}
        onChange={(value) => setAttributes({ include: value })}
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
    </div>
  );
}