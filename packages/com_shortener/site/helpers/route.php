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
 * Shortener Component Route Helper.
 *
 * @static
 * @package     Shortener
 * @subpackage  com_shortener
 * @since       3.1
 */
abstract class ShortenerHelperRoute
{
	protected static $lookup = array();

	/**
	 * Method to get a route configuration for the url view.
	 *
	 * @param   integer  $id        The route of the url item.
	 * @param   string   $language  The language code, default value of * means all.
	 *
	 * @return  string
	 *
	 * @since   3.1
	 */
	public static function getUrlRoute($id, $language = 0)
	{
		// Initialiase variables.
		$needles = array(
			'url'  => array((int) $id)
		);

		// Create the link.
		$link = 'index.php?option=com_shortener&view=url&id=' . $id;

		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			// Initialiase variables.
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('a.sef AS sef')
				->select('a.lang_code AS lang_code')
				->from('#__languages AS a');

			// Set the query and load the result.
			$db->setQuery($query);
			$langs = $db->loadObjectList();

			foreach ($langs as $lang)
			{
				if ($language == $lang->lang_code)
				{
					$link .= '&lang=' . $lang->sef;
					$needles['language'] = $language;
				}
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}
		elseif ($item = self::_findItem())
		{
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 * Method to get a route configuration for the form view.
	 *
	 * @param   integer  $id      The id of the url.
	 * @param   string   $return  The return page variable.
	 *
	 * @return  string
	 *
	 * @since   3.1
	 */
	public static function getFormRoute($id, $return = null)
	{
		// Create the link.
		if ($id)
		{
			$link = 'index.php?option=com_shortener&task=url.edit&s_id=' . $id;
		}
		else
		{
			$link = 'index.php?option=com_shortener&task=url.add&s_id=0';
		}

		if ($return)
		{
			$link .= '&return=' . $return;
		}

		return $link;
	}

	/**
	 * Method to find the item.
	 *
	 * @param   array  $needles  The needles to find.
	 *
	 * @return  null
	 *
	 * @since   3.1
	 */
	protected static function _findItem($needles = null)
	{
		// Initialiase variables.
		$app      = JFactory::getApplication();
		$menus    = $app->getMenu('site');
		$language = isset($needles['language']) ? $needles['language'] : '*';

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language]))
		{
			self::$lookup[$language] = array();

			$component  = JComponentHelper::getComponent('com_shortener');
			$attributes = array('component_id');
			$values     = array($component->id);

			if ($language != '*')
			{
				$attributes[] = 'language';
				$values[] = array($needles['language'], '*');
			}

			$items = $menus->getItems($attributes, $values);

			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];

					if (!isset(self::$lookup[$language][$view]))
					{
						self::$lookup[$language][$view] = array();
					}

					if (isset($item->query['id']))
					{
						/*
						Here it will become a bit tricky.
						language != * can override existing entries.
						language == * cannot override existing entries.
						 */
						if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*')
						{
							self::$lookup[$language][$view][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$language][$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$language][$view][(int) $id]))
						{
							return self::$lookup[$language][$view][(int) $id];
						}
					}
				}
			}
		}

		$active = $menus->getActive();

		if ($active && $active->component == 'com_shortener' && ($active->language == '*' || !JLanguageMultilang::isEnabled()))
		{
			return $active->id;
		}

		// If not found, return language specific home link.
		$default = $menus->getDefault($language);

		return !empty($default->id) ? $default->id : null;
	}
}
