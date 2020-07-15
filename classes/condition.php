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
 * Activity completion condition.
 *
 * @package    availability_courseprogress
 * @copyright  2020 onwards Willian Mano {@link http://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_courseprogress;

use core_availability\info;

defined('MOODLE_INTERNAL') || die();

/**
 * Activity completion condition.
 *
 * @package    availability_courseprogress
 * @copyright  2020 onwards Willian Mano {@link http://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class condition extends \core_availability\condition {
    /**
     * @var int $courseprogress - The desired course progress to enable the activity
     */
    protected $courseprogress;

    /**
     * Constructor.
     *
     * @param \stdClass $structure Data structure from JSON decode
     */
    public function __construct($structure) {
        $this->courseprogress = $structure->courseprogress;
    }

    /**
     * Determines whether a particular item is currently available
     * according to this availability condition.
     *
     * If implementations require a course or modinfo, they should use
     * the get methods in $info.
     *
     * The $not option is potentially confusing. This option always indicates
     * the 'real' value of NOT. For example, a condition inside a 'NOT AND'
     * group will get this called with $not = true, but if you put another
     * 'NOT OR' group inside the first group, then a condition inside that will
     * be called with $not = false. We need to use the real values, rather than
     * the more natural use of the current value at this point inside the tree,
     * so that the information displayed to users makes sense.
     *
     * @param bool $not Set true if we are inverting the condition
     * @param info $info Item we're checking
     * @param bool $grabthelot Performance hint: if true, caches information
     *   required for all course-modules, to make the front page and similar
     *   pages work more quickly (works only for current user)
     * @param int $userid User ID to check availability for
     *
     * @return bool True if available
     *
     * @throws \coding_exception
     */
    public function is_available($not, info $info, $grabthelot, $userid) {
        $modinfo = $info->get_modinfo();
        $course = $modinfo->get_course();

        $progresspercentage = $this->get_course_progress_percentage($course, $userid);

        if ($progresspercentage === false) {
            return false;
        }

        if ($not) {
            if ($progresspercentage == $this->courseprogress) {
                return false;
            }

            return true;
        }

        if ($progresspercentage >= $this->courseprogress) {
            return true;
        }

        return false;
    }

    /**
     * Obtains the course progress percentage
     *
     * @param $course
     * @param $userid
     *
     * @return bool|float|int|null
     */
    protected function get_course_progress_percentage($course, $userid) {
        $completion = new \completion_info($course);

        if ($completion->is_enabled()) {
            $percentage = \core_completion\progress::get_course_progress_percentage($course, $userid);

            if (!is_null($percentage)) {
                $percentage = floor($percentage);
            }

            if (is_null($percentage)) {
                $percentage = 0;
            }

            return $percentage;
        }

        return false;
    }

    /**
     * Obtains a string describing this restriction (whether or not
     * it actually applies). Used to obtain information that is displayed to
     * students if the activity is not available to them, and for staff to see
     * what conditions are.
     *
     * The $full parameter can be used to distinguish between 'staff' cases
     * (when displaying all information about the activity) and 'student' cases
     * (when displaying only conditions they don't meet).
     *
     * If implementations require a course or modinfo, they should use
     * the get methods in $info.
     *
     * The special string <AVAILABILITY_CMNAME_123/> can be returned, where
     * 123 is any number. It will be replaced with the correctly-formatted
     * name for that activity.
     *
     * @param bool $full Set true if this is the 'full information' view
     * @param bool $not Set true if we are inverting the condition
     * @param info $info Item we're checking
     *
     * @return string Information string (for admin) about all restrictions on
     *   this item
     *
     * @throws \coding_exception
     */
    public function get_description($full, $not, info $info) {
        if ($not) {
            return get_string('requires_notfinish', 'availability_courseprogress', $this->courseprogress);
        }

        return get_string('requires_finish', 'availability_courseprogress', $this->courseprogress);
    }

    /**
     * Obtains a representation of the options of this condition as a string,
     * for debugging.
     *
     * @return string Text representation of parameters
     */
    protected function get_debug_string() {
        return gmdate('Y-m-d H:i:s');
    }

    /**
     * Saves tree data back to a structure object.
     *
     * @return \stdClass Structure object (ready to be made into JSON format)
     */
    public function save() {
        return (object)['type' => 'courseprogress', 'courseprogress' => $this->courseprogress];
    }
}
