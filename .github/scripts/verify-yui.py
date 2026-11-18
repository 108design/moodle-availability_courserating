# This file is part of Moodle - https://moodle.org/
#
# Moodle is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Moodle is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Moodle. If not, see <https://www.gnu.org/licenses/>.
#
# @package    availability_courserating
# @copyright  2026 Andreas Giesen <andreas@108design.com>
# @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

"""Rebuild the three YUI variants and check their full licensed bytes."""

from pathlib import Path
import re
import subprocess
import sys


checkout, installed = (Path(argument).resolve(strict=True) for argument in sys.argv[1:])
assert checkout.name == 'this-plugin' and installed.name == 'courserating'
build = installed / 'yui/build'
expected_build = checkout / 'yui/build'
assert not build.is_symlink()
before = [entry for entry in build.rglob('*') if entry.is_file()]
for entry in before:
    assert entry.suffix == '.js' and not entry.is_symlink() and entry.resolve().is_relative_to(build)
    entry.unlink()

subprocess.run(['npx', 'grunt', 'yui'], cwd=installed / 'yui/src', check=True)

source = (checkout / 'yui/src/form/js/form.js').read_bytes()
notice = re.search(rb'/\*\n \* This file is part of Moodle[^*]*(?:\*(?!/)[^*]*)*\*/', source)
assert notice is not None
header = notice.group() + b'\n\n'
source_documentation = source[:notice.start()]
assert re.fullmatch(rb'/\*\*[^*]*(?:\*(?!/)[^*]*)*\*/\n', source_documentation)
expected = {p.relative_to(expected_build) for p in expected_build.rglob('*.js')}
generated = {p.relative_to(build) for p in build.rglob('*') if p.is_file()}
assert len(expected) == 3 and expected == generated, 'Unexpected generated YUI files'
for name in sorted(expected):
    source_file = expected_build / name
    built_file = build / name
    data = built_file.read_bytes()
    assert data.startswith(b'YUI.add('), name
    if not name.name.endswith('-min.js'):
        # Shifter copies the source file's own documentation into the
        # unminified wrapper. The release keeps that comment in the source.
        wrapper, separator, body = data.partition(b'\n\n')
        assert separator and body.startswith(source_documentation), name
        data = wrapper + separator + body.removeprefix(source_documentation)
    licensed = header + data
    assert source_file.read_bytes() == licensed, f'Stale generated YUI file: {name}'
    built_file.write_bytes(licensed)
    print(f'Verified Shifter output and Moodle licence for {name}')
