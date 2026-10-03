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
 * Step definitions related to adding a course rating.
 *
 * @package    availability_courserating
 * @copyright  iplusacademy (www.iplusacademy.org)
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @author     Renaat Debleu <info@eWallah.net>
 * @author     Andreas Giesen <andreas@108design.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.
// For that reason, we can't even rely on $CFG->admin being available here.

// @codeCoverageIgnoreStart
require_once(__DIR__ . '/../../../../../lib/behat/behat_base.php');
// @codeCoverageIgnoreEnd

/**
 * Step definitions related to adding a course rating.
 *
 * @package    availability_courserating
 * @copyright  iplusacademy (www.iplusacademy.org)
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @author     Renaat Debleu <info@eWallah.net>
 * @author     Andreas Giesen <andreas@108design.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_availability_courserating extends behat_base {
    /**
     * Add rating for user in a course.
     * @Then /^I add a rating for course "(?P<course>[^"]*)" by user "(?P<user>[^"]*)"$/
     * @param string $course
     * @param string $user
     */
    public function i_add_a_rating_for_course_by_user($course, $user) {
        global $DB;

        $courseid = $this->get_course_id($course);
        $userid = $this->get_user_id($user);

        if (!$DB->record_exists('tool_courserating_rating', ['courseid' => $courseid, 'userid' => $userid])) {
            $DB->insert_record('tool_courserating_rating', (object) [
                'courseid' => $courseid,
                'userid' => $userid,
                'rating' => 5,
                'review' => '',
                'timecreated' => time(),
                'timemodified' => time(),
            ]);
        }
    }

    /**
     * Fetch user ID from its username.
     *
     * @param string $username The username.
     * @return int The user ID.
     * @throws Exception
     */
    protected function get_user_id($username) {
        global $DB;
        if (!$userid = $DB->get_field('user', 'id', ['username' => $username])) {
            throw new Exception("A user with username '{$username}' does not exist");
        }
        return $userid;
    }
}
