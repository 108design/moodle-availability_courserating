<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//
// Adapted from availability_coursecompleted in 2026 by Andreas Giesen.

/**
 * Front-end class.
 *
 * @package   availability_courserating
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    Andreas Giesen <andreas@108design.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_courserating;

use cm_info;
use section_info;
use stdClass;

/**
 * Front-end class.
 *
 * @package   availability_courserating
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    Andreas Giesen <andreas@108design.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class frontend extends \core_availability\frontend {
    /**
     * Language strings used by the restriction editor.
     *
     * @return array Required string identifiers
     */
    protected function get_javascript_strings() {
        return ['missing'];
    }

    /**
     * Decides whether this plugin should be available in a given course. The
     * plugin can do this depending on course or system settings.
     *
     * @param stdClass $course Course object
     * @param cm_info|null $cm Course-module currently being edited (null if none)
     * @param section_info|null $section Section currently being edited (null if Course object)
     * @return bool True if rating table is available
     */
    protected function allow_add($course, ?cm_info $cm = null, ?section_info $section = null) {
        global $DB;

        return $DB->get_manager()->table_exists('tool_courserating_rating');
    }
}
