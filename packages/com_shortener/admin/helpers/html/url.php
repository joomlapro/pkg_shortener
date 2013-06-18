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

/**
 * Utility class working with url.
 *
 * @package     Shortener
 * @subpackage  com_shortener
 * @since       3.1
 */
abstract class JHtmlUrl
{
	/**
	 * Displays a batch widget for moving or copying items.
	 *
	 * @param   string  $extension  The extension.
	 *
	 * @return  string  The necessary HTML for the widget.
	 *
	 * @since   3.1
	 */
	public static function item($extension)
	{
		// Create the copy/move options.
		$options = array(JHtml::_('select.option', 'c', JText::_('JLIB_HTML_BATCH_COPY')),
			JHtml::_('select.option', 'm', JText::_('JLIB_HTML_BATCH_MOVE')));

		// Create the batch selector to move or copy.
		$lines = array('<div id="batch-move-copy" class="control-group radio">',
			JHtml::_('select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'), '</div><hr />');

		return implode("\n", $lines);
	}
}
