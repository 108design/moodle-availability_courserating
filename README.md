# Course Rating Availability for Moodle

Make an activity or course section available according to whether a learner has
rated the current course. Use the condition to offer a follow-up after a rating,
or to show a rating reminder to learners who have not yet submitted one.

## Requirements and installation

- Moodle 4.5 or later.
- The [Course Rating plugin](https://github.com/marinaglancy/moodle-tool_courserating)
  (`tool_courserating`) must be installed.

1. Install this plugin as `availability/condition/courserating`.
2. Complete installation through **Site administration → Notifications**.

## Adding a restriction

1. Edit the activity or section and open **Restrict access**.
2. Add **Course rated**.
3. Choose **Yes** to require a rating, or **No** to require that no rating exists.
4. Combine the condition with other restrictions if needed, then save.

The condition checks the current learner's rating for this course. Ratings in
other courses do not satisfy it. Moodle's normal restriction visibility controls
determine whether unavailable content is hidden or displayed with an explanation.

## Troubleshooting

If the condition cannot find ratings, check that Course Rating is installed and
working in the course. Verify the restriction using learners with and without a
rating, and review any other access restrictions on the same activity or section.

The condition uses Moodle's normal availability editor. Moodle Workplace, Totara
and other derived platforms are not confirmed compatibility targets.

## Maintainer and origin

Adapted in 2026 from the GPL-licensed
[Course Completed availability condition](https://github.com/ewallah/moodle-availability_coursecompleted).
Original work: iplusacademy, authored by Renaat Debleu.
Adaptation and subsequent changes: © 2026 Andreas Giesen.
Maintained by Andreas Giesen <andreas@108design.com> (108design).
Original authorship and copyright notices are retained.

## License

GNU General Public License version 3 or later. See [LICENSE](LICENSE) for the full terms.
