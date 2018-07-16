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
 * Grade Now for cpassignment plugin
 *
 * @package    mod_cpassignment
 * @copyright  2018 Justin Hunt (poodllsupport@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 namespace mod_cpassignment;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot .'/lib/formslib.php');
use \mod_cpassignment\constants;


/**
 * Grade form for mod_cpassignment
 *
 * @package    mod_cpassignment
 * @copyright  2015 Justin Hunt (poodllsupport@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradenowform extends \moodleform{

    /**
     * Defines forms elements
     */
    public function definition() {
    	global $CFG;

        $mform = $this->_form;
        $context = $this->_customdata['context'];
        $audiorecorderhtml = $this->_customdata['audiorecorderhtml'];
        $videorecorderhtml = $this->_customdata['videorecorderhtml'];
        $shownext = $this->_customdata['shownext'];
        $maxgrade = $this->_customdata['maxgrade'];

		//$mform->addElement('header','General','');

        // adding the hidden fields which recorders write to and other bits we might/will use
		$mform->addElement('hidden', 'action');
		$mform->addElement('hidden', 'attemptid');
		$mform->addElement('hidden', 'n');
        $mform->addElement('hidden', 'sessiontime',null,
				array('class'=>constants::M_GRADING_FORM_SESSIONTIME,'id'=>constants::M_GRADING_FORM_SESSIONTIME));
		$mform->addElement('hidden', 'feedbackaudio',null,
				array('class'=>constants::M_GRADING_FORM_FEEDBACKAUDIO,'id'=>constants::M_GRADING_FORM_FEEDBACKAUDIO));
        $mform->addElement('hidden', 'feedbackvideo',null,
            array('class'=>constants::M_GRADING_FORM_FEEDBACKVIDEO,'id'=>constants::M_GRADING_FORM_FEEDBACKVIDEO));

		$mform->setType('action',PARAM_TEXT);
		$mform->setType('attemptid',PARAM_INT);
		$mform->setType('n',PARAM_INT);
		$mform->setType('sessiontime',PARAM_INT);
		$mform->setType('feedbackaudio',PARAM_TEXT);
        $mform->setType('feedbackvideo',PARAM_TEXT);

        $mform->addElement('header','feedbacklabel',
                get_string('feedback', constants::M_LANG),
                2);

        // session score
        $mform->addElement('text', 'sessionscore',
                get_string('grade',
                constants::M_LANG, $maxgrade), array('size'=>'12'));
        $mform->setType('sessionscore', PARAM_INT);

        // Feedback text.
        $edfileoptions = \mod_cpassignment\utils::
                editor_with_files_options($context);
        $opts = array('rows'=>'15', 'columns'=>'80');
        $mform->addElement('editor','feedbacktext_editor',
                get_string('feedbacktextlabel',
                constants::M_LANG),
                $opts, $edfileoptions);
        $mform->setDefault('feedbacktext_editor',
                array('text'=>'', 'format' => FORMAT_HTML));
        $mform->setType('feedbacktext_editor',PARAM_RAW);


        // Feedback audio.
        if ($audiorecorderhtml != '') {
            $mform->addElement('button', 'feedbackaudioholder',
                    get_string('feedbackaudiolabel', constants::M_LANG));
        }

        // Feedback video.
        if ($videorecorderhtml != '') {
            $mform->addElement('button', 'feedbackvideoholder',
                    get_string('feedbackvideolabel', constants::M_LANG));
        }


        // At the moment let's have buttons to select media feedback
        //  Those will go on the grading page.

        //-------------------------------------------------------------------------------
        // add out buttons for submitting and cancelling
        //-------------------------------------------------------------------------------
        $buttonarray=array();
        $buttonarray[] = &$mform->createElement('cancel');
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        if($shownext){
            $buttonarray[] = &$mform->createElement('submit', 'submitbutton2', get_string('saveandnext',constants::M_LANG));
        }
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        //	$mform->closeHeaderBefore('buttonar');
    }
}

