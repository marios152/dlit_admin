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
 * Create the menu.
 *
 * @package    local
 * @subpackage dlit_admin
 * @copyright  DLIT
 * @license    
 */

defined('MOODLE_INTERNAL') || die();

$ADMIN->add('root', new admin_category('local_dlit_admin',get_string('pluginname','local_dlit_admin')));

$ADMIN->add('local_dlit_admin',new admin_externalpage('coursenotes', get_string('menuoption','local_dlit_admin'),$CFG->wwwroot."/local/dlit_admin/main.php",'local/dlit_admin:add'));

/*$settings->add(new admin_setting_heading('sampleheader',
                                         get_string('headerconfig', 'block_newblock'),
                                         get_string('descconfig', 'block_newblock')));

$settings->add(new admin_setting_configcheckbox('newblock/foo',
                                                get_string('labelfoo', 'block_newblock'),
                                                get_string('descfoo', 'block_newblock'),
                                                '0'));
*/