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
 * Shortener Component Controller.
 *
 * @package     Shortener
 * @subpackage  com_shortener
 * @since       3.1
 */
class ShortenerController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  This object to support chaining.
	 *
	 * @since   3.1
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Initialise variables.
		$cachable = true;
		$user     = JFactory::getUser();

		// Set the default view name and format from the Request.
		// Note we are using s_id to avoid collisions with the router and the return page.
		$id       = $this->input->getInt('s_id');
		$vName    = $this->input->get('view', 'form');
		$this->input->set('view', $vName);

		if ($user->get('id') || ($this->input->getMethod() == 'POST' && $vName = 'form'))
		{
			$cachable = false;
		}

		$safeurlparams = array(
			'catid'            => 'INT',
			'id'               => 'INT',
			'cid'              => 'ARRAY',
			'limit'            => 'UINT',
			'limitstart'       => 'UINT',
			'return'           => 'BASE64',
			'filter'           => 'STRING',
			'filter_order'     => 'CMD',
			'filter_order_Dir' => 'CMD',
			'filter-search'    => 'STRING',
			'print'            => 'BOOLEAN',
			'lang'             => 'CMD',
			'Itemid'           => 'INT',
		);

		// Check for edit form.
		if ($vName == 'form' && !$this->checkEditId('com_shortener.edit.url', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

		return parent::display($cachable, $safeurlparams);
	}
}
