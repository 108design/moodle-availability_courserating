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
 * Version info.
 *
 * @package   availability_courserating
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    Andreas Giesen <andreas@108design.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'availability_courserating';
$plugin->requires = 2024100700;
$plugin->maturity = MATURITY_BETA;
$plugin->supported = [405, 500];
$plugin->release = '0.1';
$plugin->version = 2026022401;
