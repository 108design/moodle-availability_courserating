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
 * Basic unit tests for the courserating condition.
 *
 * @package   availability_courserating
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    Andreas Giesen <andreas@108design.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_courserating;


use availability_courserating\condition;

/**
 * Bare tests for the courserating condition.
 *
 * @package   availability_courserating
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    Andreas Giesen <andreas@108design.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class basic_test extends \basic_testcase {
    /**
     * Tests the constructor including error conditions.
     * @covers \availability_courserating\condition
     */
    public function test_constructor(): void {
        $cond = new condition((object)[]);
        $this->assertNotEmpty($cond);

        $cond = new condition((object)['id' => '1']);
        $this->assertNotEmpty($cond);

        $cond = new condition((object)['id' => '0']);
        $this->assertNotEmpty($cond);

        $this->expectException(\coding_exception::class);
        $this->expectExceptionMessage('Invalid value for course rating condition');
        new condition((object)['id' => 1]);
    }

    /**
     * Tests the save() function.
     * @covers \availability_courserating\condition
     */
    public function test_save(): void {
        $structure = (object)['id' => '1'];
        $cond = new condition($structure);
        $structure->type = 'courserating';
        $this->assertEquals($structure, $cond->save());
    }

    /**
     * Tests json.
     * @covers \availability_courserating\condition
     */
    public function test_json(): void {
        $this->assertEqualsCanonicalizing((object)['type' => 'courserating', 'id' => '3'], condition::get_json('3'));
        $this->assertEqualsCanonicalizing((object)['type' => 'courserating', 'id' => '0'], condition::get_json('0'));
    }
}
