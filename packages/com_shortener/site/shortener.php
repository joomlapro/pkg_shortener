<?php
/**
 * @package     Shortener
 * @subpackage  com_shortener
 *
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Get the input.
$input = JFactory::getApplication()->input;

// Include dependancies.
require_once JPATH_COMPONENT . '/helpers/route.php';

// Execute the task.
$controller = JControllerLegacy::getInstance('Shortener');
$controller->execute($input->get('task'));
$controller->redirect();
