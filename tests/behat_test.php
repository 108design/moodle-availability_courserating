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
 * Unit tests for the behat courserating condition.
 *
 * @package   availability_courserating
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    Andreas Giesen <andreas@108design.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_courserating;

/**
 * Unit tests for the behat courserating condition.
 *
 * @package   availability_courserating
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    Andreas Giesen <andreas@108design.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class behat_test extends \advanced_testcase {
    /**
     * Test behat funcs
     * @covers \behat_availability_courserating
     */
    public function test_behat(): void {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/availability/condition/courserating/tests/behat/behat_availability_courserating.php');
        $class = new \behat_availability_courserating();
        $this->resetAfterTest();
        $this->setAdminUser();
        $CFG->enableavailability = true;
        set_config('enableavailability', true);

        if (!$DB->get_manager()->table_exists('tool_courserating_rating')) {
            $this->markTestSkipped('tool_courserating_rating table not available in this test environment.');
        }

        $dg = $this->getDataGenerator();
        $course = $dg->create_course();
        $user = $dg->create_user();
        $class->i_add_a_rating_for_course_by_user($course->fullname, $user->username);
        $this->expectExceptionMessage("A user with username 'otheruser' does not exist");
        $class->i_add_a_rating_for_course_by_user($course->fullname, 'otheruser');
    }
}
