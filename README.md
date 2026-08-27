# Availability courserating

Restrict module and section access based on whether a user has rated the current course.

## Idea

This availability condition makes it easy to show modules or sections only when a user has
rated (or has not rated) a course in `tool_courserating_rating`.

## Conditional availability conditions

Check the global documentation about conditional availability conditions: https://docs.moodle.org/en/Conditional_activities_settings

## Compatibility note

This plugin has not been tested in Moodle Workplace, Totara, or other Moodle-derived systems.

## Installation:

 1. Unpack the zip file into the availability/condition/ directory. A new directory will be created called courserating.
 2. Go to Site administration > Notifications to complete the plugin installation.

## Requirements

This plugin requires Moodle 4.5+

## Troubleshooting

 1. Ensure plugin `tool_courserating` is installed and the table `tool_courserating_rating` exists.
 2. Add the restriction "Course rated" in activity or section availability settings.
 3. Choose **Yes** to require a rating row for the current user/course, or **No** to require no rating row.

## Theme support

This plugin is developed and tested on Moodle Core's Boost theme and Boost child themes, including Moodle Core's Classic theme.

## Plugin repositories

This plugin can be maintained in your own repository for your Moodle deployment.

## Bug and problem reports / Support requests

This plugin is carefully developed and thoroughly tested, but bugs and problems can always appear.
Please report bugs and problems in your internal issue tracker or repository.

## Feature proposals

Please issue feature proposals in your internal issue tracker or repository.

## Moodle release support

This plugin is maintained for the latest major releases of Moodle.

## Status

Maintained by Andreas Giesen.

## Origin and attribution

This plugin was adapted in 2026 from the GPL-licensed
[`availability_coursecompleted`](https://github.com/ewallah/moodle-availability_coursecompleted)
plugin. The original work is copyright iplusacademy (www.iplusacademy.org) and
was authored and maintained by Renaat Debleu. The adaptation and subsequent
changes are copyright 2026 Andreas Giesen.

## License

GNU General Public License version 3 or later. See [LICENSE](LICENSE) for the
complete license text.
