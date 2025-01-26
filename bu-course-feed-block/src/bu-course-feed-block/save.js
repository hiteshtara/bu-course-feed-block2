import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
  const { include, exclude, period, showSections, showSchedules } = attributes;

  return (
    <div {...useBlockProps.save()}>
      {`[bu-course-feed include="${include}" exclude="${exclude}" period="${period}" show_sections="${showSections}" show_schedules="${showSchedules}"]`}
    </div>
  );
}
