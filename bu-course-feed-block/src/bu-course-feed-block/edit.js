import { TextControl, ToggleControl, PanelBody } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
  const { include, exclude, period, showSections, showSchedules } = attributes;

  return (
    <>
      {/* Sidebar Controls */}
      <InspectorControls>
        <PanelBody title="Course Feed Settings" initialOpen={true}>
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
        </PanelBody>
      </InspectorControls>

      {/* Editor Preview */}
      <div {...useBlockProps()}>
        <p>
          BU Course Feed Block Preview:
          <br />
          Include: {include || "None"}
          <br />
          Exclude: {exclude || "None"}
          <br />
          Period: {period || "None"}
          <br />
          Show Sections: {showSections ? "Yes" : "No"}
          <br />
          Show Schedules: {showSchedules ? "Yes" : "No"}
        </p>
      </div>
    </>
  );
}
