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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_shortener'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Register dependent classes.
JLoader::register('ShortenerHelper', __DIR__ . '/helpers/shortener.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Shortener');
$controller->execute($input->get('task'));
$controller->redirect();
