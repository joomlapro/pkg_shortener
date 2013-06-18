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
 * Shortener helper.
 *
 * @package     Shortener
 * @subpackage  com_shortener
 * @since       3.1
 */
class ShortenerHelper
{
	/**
	 * The extension name.
	 *
	 * @var     string
	 * @since   3.1
	 */
	public static $extension = 'com_shortener';

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public static function addSubmenu($vName = 'cpanel')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_SHORTENER_SUBMENU_CPANEL'),
			'index.php?option=com_shortener&view=cpanel',
			$vName == 'cpanel'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_SHORTENER_SUBMENU_URLS'),
			'index.php?option=com_shortener&view=urls',
			$vName == 'urls'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  $urlId  The url ID.
	 *
	 * @return  JObject  A JObject containing the allowed actions.
	 *
	 * @since   3.1
	 */
	public static function getActions($urlId = 0)
	{
		// Initialiase variables.
		$user    = JFactory::getUser();
		$result  = new JObject;

		if (empty($urlId))
		{
			$assetName = self::$extension;
		}
		else
		{
			$assetName = self::$extension . '.url.' . (int) $urlId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * Method to generate rand alias.
	 *
	 * @param   integer  $size  The size number.
	 *
	 * @return  string
	 */
	public static function rand($size = 5)
	{
		// Make sure somebody doesn't put in a too large alias size value:
		if ($size > 10)
		{
			$size = 10;
		}

		// Create the random alias:
		$alias    = '';
		$chars     = range('a', 'z');
		$numbers   = range(0, 9);

		// We want the fist character to be a random letter:
		shuffle($chars);
		$alias .= $chars[0];

		// Next we combine the numbers and characters to get the other characters:
		$symbols = array_merge($numbers, $chars);
		shuffle($symbols);

		for ($i = 0, $j = $size - 1; $i < $j; ++$i)
		{
			$alias .= $symbols[$i];
		}

		return $alias;
	}

	/**
	 * Method to get short url.
	 *
	 * @param   url  $url  The current url.
	 *
	 * @return  object
	 *
	 * @since   3.1
	 */
	public static function getShortUrl($url)
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('a.*')
			->from($db->quoteName('#__shortener') . ' AS a')
			->where($db->quoteName('a.url') . ' = ' . $db->quote($url));

		// Set the query and load the result.
		$db->setQuery($query);
		$result = $db->loadObject();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return null;
		}

		return $result;
	}
}
