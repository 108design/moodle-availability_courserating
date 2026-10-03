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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Load Moodle's test autoloader for Infection's source reflection.
 *
 * @package availability_courserating
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.MoodleInternal.MoodleInternalGlobalState

// Source reflection needs class loading only; PHPUnit starts its own full test environment.
define('MOODLE_INTERNAL', true);
define('IGNORE_COMPONENT_CACHE', true);
global $CFG;
$CFG = new stdClass();
$CFG->dirroot = dirname(__DIR__, 4);
$CFG->libdir = $CFG->dirroot . '/lib';
$CFG->admin = 'admin';
$CFG->debug = 0;
require_once($CFG->libdir . '/classes/component.php');
\core\component::register_autoloader();
