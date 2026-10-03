YUI.add('moodle-availability_courserating-form', function (Y, NAME) {

/*
 * This file is part of Moodle - http://moodle.org/
 *
 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * Adapted from availability_coursecompleted in 2026 by Andreas Giesen.
 *
 * @copyright iplusacademy (www.iplusacademy.org)
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @author Renaat Debleu <info@eWallah.net>
 * @author Andreas Giesen <andreas@108design.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * JavaScript for form editing course rated condition.
 *
 * @module moodle-availability_courserating-form
 */

M.availability_courserating = M.availability_courserating || {};

// Class M.availability_courserating.form @extends M.core_availability.plugin.
M.availability_courserating.form = Y.Object(M.core_availability.plugin);

// Options available for selection.
M.availability_courserating.form.rated = null;

/**
 * Initialises this plugin.
 *
 * @method initInner
 * @param {boolean} rated Is course rated or not rated
 */
M.availability_courserating.form.initInner = function(rated) {
    this.rated = rated;
};

M.availability_courserating.form.getNode = function(json) {
    // Create HTML structure.
    var tit = M.util.get_string('title', 'availability_courserating');
    var html = '<label class="form-group"><span class="p-r-1">' + tit + '</span>';
    html += '<span class="availability-courserating"><select class="custom-select" name="id" title="' + tit + '">';
    html += '<option value="choose">' + M.util.get_string('choosedots', 'moodle') + '</option>';
    html += '<option value="1">' + M.util.get_string('yes', 'moodle') + '</option>';
    html += '<option value="0">' + M.util.get_string('no', 'moodle') + '</option>';
    html += '</select></span></label>';
    var node = Y.Node.create('<span class="form-inline">' + html + '</span>');

    // Set initial values (leave default 'choose' if creating afresh).
    if (json.creating === undefined) {
        if (json.id !== undefined && node.one('select[name=id] > option[value=' + json.id + ']')) {
            node.one('select[name=id]').set('value', '' + json.id);
        } else if (json.id === undefined) {
            node.one('select[name=id]').set('value', 'choose');
        }
    }

    // Add event handlers (first time only).
    if (!M.availability_courserating.form.addedEvents) {
        M.availability_courserating.form.addedEvents = true;
        var root = Y.one('.availability-field');
        root.delegate('change', function() {
            // Just update the form fields.
            M.core_availability.form.update();
        }, '.availability_courserating select');
    }

    return node;
};

M.availability_courserating.form.fillValue = function(value, node) {
    var selected = node.one('select[name=id]').get('value');
    if (selected === 'choose') {
        value.id = '';
    } else {
        value.id = selected;
    }
};

M.availability_courserating.form.fillErrors = function(errors, node) {
    var selected = node.one('select[name=id]').get('value');
    if (selected === 'choose') {
        errors.push('availability_courserating:missing');
    }
};


}, '@VERSION@', {"requires": ["base", "node", "event", "moodle-core_availability-form"]});
