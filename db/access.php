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
 * dlit_admin  
 *
 * @package   local
 * @subpackage dlit_admin
 * @copyright DLIT
 * @license   
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    'local/dlit_admin:add' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
	    'student' => CAP_PREVENT
	    'teacher' => CAP_PREVENT
	    'editingteacher' =>CAP_PREVENT		
        ),
    ),
);
