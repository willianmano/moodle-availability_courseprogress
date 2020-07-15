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

/**
 * Front-end class.
 *
 * @package    availability_courseprogress
 * @copyright  2020 onwards Willian Mano {@link http://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_courseprogress;

defined('MOODLE_INTERNAL') || die();

/**
 * Front-end class.
 *
 * @package    availability_courseprogress
 * @copyright  2020 onwards Willian Mano {@link http://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class frontend extends \core_availability\frontend {
    /**
     * Gets a list of string identifiers (in the plugin's language file) that
     * are required in JavaScript for this plugin. The default returns nothing.
     *
     * You do not need to include the 'title' string (which is used by core) as
     * this is automatically added.
     *
     * @return array Array of required string identifiers
     */
    protected function get_javascript_strings() {
        return ['fieldlabel'];
    }
}
