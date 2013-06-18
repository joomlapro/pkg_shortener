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
 * View class for a list of urls.
 *
 * @package     Shortener
 * @subpackage  com_shortener
 * @since       3.1
 */
class ShortenerViewUrls extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $authors;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   3.1
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');
		$this->authors    = $this->get('Authors');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			// Load the submenu.
			ShortenerHelper::addSubmenu('urls');

			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}

		// Load the parameters.
		$params = JComponentHelper::getParams('com_shortener');

		foreach ($this->items as $i => & $item)
		{
			$item->custom_url = $params->get('custom_url') ? $params->get('custom_url') . '/' : JUri::root();
			$item->custom_url .= $item->short_url;
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	protected function addToolbar()
	{
		// Initialise variables.
		$state = $this->get('State');
		$canDo = ShortenerHelper::getActions();
		$user  = JFactory::getUser();

		// Get the toolbar object instance.
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_SHORTENER_MANAGER_URLS_TITLE'), 'urls.png');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('url.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('url.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('urls.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('urls.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolbarHelper::archiveList('urls.archive');
			JToolbarHelper::checkin('urls.checkin');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'urls.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('urls.trash');
		}

		// Add a batch button.
		if ($user->authorise('core.create', 'com_shortener') && $user->authorise('core.edit', 'com_shortener') && $user->authorise('core.edit.state', 'com_shortener'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');
			$dhtml = "<button data-toggle=\"modal\" data-target=\"#collapseModal\" class=\"btn btn-small\">
						<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
						$title</button>";
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_shortener');
		}

		JToolBarHelper::help('urls', $com = true);

		JHtmlSidebar::setAction('index.php?option=com_shortener&view=urls');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);
	}

	/**
	 * Returns an array of fields the table can be sorted by.
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value.
	 *
	 * @since   3.1
	 */
	protected function getSortFields()
	{
		return array(
			'a.state' => JText::_('JSTATUS'),
			'a.short_url' => JText::_('COM_SHORTENER_HEADING_SHORT_URL'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.hits' => JText::_('JGLOBAL_HITS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
