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
 * Unit tests for the courserating condition.
 *
 * @package   availability_courserating
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    Andreas Giesen <andreas@108design.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_courserating;

use availability_courserating\{condition, frontend};
use core_availability\{tree, info_module};

/**
 * Unit tests for the courserating condition.
 *
 * @package   availability_courserating
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    Andreas Giesen <andreas@108design.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class advanced_test extends \advanced_testcase {
    /** @var \stdClass course. */
    private $course;

    /** @var \stdClass cm. */
    private $cm;

    /** @var int userid. */
    private $userid;

    /** @var int ratedid. */
    private $ratedid;

    /** @var int teacherid. */
    private $teacherid;

    /**
     * Create course and page.
     */
    public function setUp(): void {
        global $CFG, $DB;
        parent::setUp();
        require_once($CFG->dirroot . '/availability/tests/fixtures/mock_info.php');
        require_once($CFG->dirroot . '/availability/tests/fixtures/mock_info_module.php');
        $this->resetAfterTest();
        $this->setAdminUser();
        $CFG->enableavailability = true;
        set_config('enableavailability', true);

        if (!$DB->get_manager()->table_exists('tool_courserating_rating')) {
            $this->markTestSkipped('tool_courserating_rating table not available in this test environment.');
        }

        $dg = $this->getDataGenerator();
        $this->course = $dg->create_course();
        $this->userid = $dg->create_user()->id;
        $this->ratedid = $dg->create_user()->id;
        $this->teacherid = $dg->create_user()->id;

        $role = $DB->get_field('role', 'id', ['shortname' => 'student']);
        $dg->enrol_user($this->userid, $this->course->id, $role);
        $dg->enrol_user($this->ratedid, $this->course->id, $role);
        $othercourse = $dg->create_course();
        $dg->enrol_user($this->userid, $othercourse->id, $role);
        $role = $DB->get_field('role', 'id', ['shortname' => 'editingteacher']);
        $dg->enrol_user($this->teacherid, $this->course->id, $role);

        $feedback = $dg->get_plugin_generator('mod_feedback')->create_instance(['course' => $this->course]);
        $this->cm = get_fast_modinfo($this->course)->get_cm($feedback->cmid);

        $DB->insert_record('tool_courserating_rating', (object) [
            'courseid' => $this->course->id,
            'userid' => $this->ratedid,
            'rating' => 5,
            'review' => '',
            'timecreated' => time(),
            'timemodified' => time(),
        ]);

        // Rating another course must not unlock this course or remove its users from feedback recipient lists.
        $DB->insert_record('tool_courserating_rating', (object) [
            'courseid' => $othercourse->id,
            'userid' => $this->userid,
            'rating' => 4,
            'review' => '',
            'timecreated' => time(),
            'timemodified' => time(),
        ]);

        rebuild_course_cache($this->course->id, true);
    }

    /**
     * Tests constructing and using courserating condition as part of tree.
     * @covers \availability_courserating\condition
     */
    public function test_tree(): void {
        $info1 = new \core_availability\mock_info($this->course, $this->userid);
        $info2 = new \core_availability\mock_info($this->course, $this->ratedid);

        $structure1 = (object)['op' => '|', 'show' => true, 'c' => [(object)['type' => 'courserating', 'id' => '1']]];
        $structure2 = (object)['op' => '|', 'show' => true, 'c' => [(object)['type' => 'courserating', 'id' => '0']]];
        $tree1 = new tree($structure1);
        $tree2 = new tree($structure2);

        $this->setUser($this->ratedid);
        $this->assertTrue($tree1->check_available(false, $info2, true, $this->ratedid)->is_available());
        $this->assertFalse($tree2->check_available(false, $info2, true, $this->ratedid)->is_available());

        $this->setUser($this->userid);
        $this->assertFalse($tree1->check_available(false, $info1, true, $this->userid)->is_available());
        $this->assertTrue($tree2->check_available(false, $info1, true, $this->userid)->is_available());
    }

    /**
     * Tests the get_description and get_standalone_description functions.
     * @covers \availability_courserating\condition
     * @covers \availability_courserating\frontend
     */
    public function test_get_description(): void {
        $nau = 'Not available unless: ';

        $frontend = new frontend();
        $name = 'availability_courserating\\frontend';
        $this->assertTrue(\phpunit_util::call_internal_method($frontend, 'allow_add', [$this->course], $name));

        $info = new \core_availability\mock_info_module($this->userid, $this->cm);
        $rated = new condition((object)['type' => 'courserating', 'id' => '1']);
        $information = $rated->get_description(true, false, $info);
        $this->assertEquals(get_string('getdescription', 'availability_courserating'), $information);
        $information = $rated->get_description(true, true, $info);
        $this->assertEquals(get_string('getdescriptionnot', 'availability_courserating'), $information);
        $information = $rated->get_standalone_description(true, false, $info);
        $this->assertEquals($nau . get_string('getdescription', 'availability_courserating'), $information);
        $information = $rated->get_standalone_description(true, true, $info);
        $this->assertEquals($nau . get_string('getdescriptionnot', 'availability_courserating'), $information);
    }

    /**
     * Tests is applied to user lists.
     * @covers \availability_courserating\condition
     */
    public function test_is_applied_to_user_lists(): void {
        $info = new \core_availability\mock_info_module($this->userid, $this->cm);
        $cond = new condition((object)['type' => 'courserating', 'id' => '1']);
        $this->assertTrue($cond->is_applied_to_user_lists());

        $checker = new \core_availability\capability_checker(\context_course::instance($this->course->id));
        $arr = [
            $this->userid => \core_user::get_user($this->userid),
            $this->ratedid => \core_user::get_user($this->ratedid),
            $this->teacherid => \core_user::get_user($this->teacherid),
        ];

        $result = $cond->filter_user_list([], true, $info, $checker);
        $this->assertEquals([], $result);

        $result = $cond->filter_user_list($arr, true, $info, $checker);
        $this->assertArrayHasKey($this->userid, $result);
        $this->assertArrayNotHasKey($this->ratedid, $result);
        $this->assertArrayHasKey($this->teacherid, $result);

        $result = $cond->filter_user_list($arr, false, $info, $checker);
        $this->assertArrayHasKey($this->teacherid, $result);
        $this->assertArrayHasKey($this->ratedid, $result);
        $this->assertArrayNotHasKey($this->userid, $result);
    }

    /**
     * Tests availability before and after adding a rating row.
     * @covers \availability_courserating\condition
     */
    public function test_page(): void {
        global $DB;
        $info = new info_module($this->cm);
        $cond = new condition((object)['type' => 'courserating', 'id' => '1']);
        $this->assertFalse($cond->is_available(false, $info, true, $this->userid));
        $this->assertTrue($cond->is_available(true, $info, true, $this->userid));

        $DB->insert_record('tool_courserating_rating', (object) [
            'courseid' => $this->course->id,
            'userid' => $this->userid,
            'rating' => 4,
            'review' => '',
            'timecreated' => time(),
            'timemodified' => time(),
        ]);

        $this->assertTrue($cond->is_available(false, $info, true, $this->userid));
        $this->assertFalse($cond->is_available(true, $info, true, $this->userid));
    }
}
