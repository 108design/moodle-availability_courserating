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

YUI.add("moodle-availability_courserating-form",function(o,e){M.availability_courserating=M.availability_courserating||{},M.availability_courserating.form=o.Object(M.core_availability.plugin),M.availability_courserating.form.rated=null,M.availability_courserating.form.initInner=function(e){this.rated=e},M.availability_courserating.form.getNode=function(e){var i=M.util.get_string("title","availability_courserating"),l='<label class="form-group"><span class="p-r-1">'+i+"</span>",l=(l=(l=(l+='<span class="availability-courserating"><select class="custom-select" name="id" title='+i+">")+('<option value="choose">'+M.util.get_string("choosedots","moodle")+"</option>"))+('<option value="1">'+M.util.get_string("yes","moodle")+"</option>"))+('<option value="0">'+M.util.get_string("no","moodle")+"</option>"),i=o.Node.create('<span class="form-inline">'+(l+="</select></span></label>")+"</span>");return e.creating===undefined&&(e.id!==undefined&&i.one("select[name=id] > option[value="+e.id+"]")?i.one("select[name=id]").set("value",""+e.id):e.id===undefined&&i.one("select[name=id]").set("value","choose")),M.availability_courserating.form.addedEvents||(M.availability_courserating.form.addedEvents=!0,o.one(".availability-field").delegate("change",function(){M.core_availability.form.update()},".availability_courserating select")),i},M.availability_courserating.form.fillValue=function(e,i){i=i.one("select[name=id]").get("value");e.id="choose"===i?"":i},M.availability_courserating.form.fillErrors=function(e,i){"choose"===i.one("select[name=id]").get("value")&&e.push("availability_courserating:missing")}},"@VERSION@",{requires:["base","node","event","moodle-core_availability-form"]});
