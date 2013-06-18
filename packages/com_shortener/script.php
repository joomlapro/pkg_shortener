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
 * Script file of Shortener Component.
 *
 * @package     Shortener
 * @subpackage  com_shortener
 * @since       3.1
 */
class Com_ShortenerInstallerScript
{
	/**
	 * Called before any type of action.
	 *
	 * @param   string            $route    Which action is happening (install|uninstall|discover_install).
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.1
	 */
	public function preflight($route, JAdapterInstance $adapter)
	{
		// Get the application.
		$app         = JFactory::getApplication();
		$min_version = (string) $adapter->get('manifest')->attributes()->version;
		$jversion    = new JVersion;

		if (!$jversion->isCompatible($min_version))
		{
			$app->enqueueMessage(JText::sprintf('COM_SHORTENER_VERSION_UNSUPPORTED', $min_version), 'error');
			return false;
		}

		if (get_magic_quotes_gpc())
		{
			$app->enqueueMessage(JText::_('COM_SHORTENER_MAGIC_QUOTES'), 'error');
			return false;
		}

		// Storing old release number for process in postflight.
		if ($route == 'update')
		{
			$this->oldRelease = $this->getParam('version');

			// Check if update is allowed (only update from 1.0 and higher).
			if (version_compare($this->oldRelease, '1.0', '<'))
			{
				$app->enqueueMessage(JText::sprintf('COM_VIDEOS_UPDATE_UNSUPPORTED', $this->oldRelease), 'error');
				return false;
			}
		}
	}

	/**
	 * Called after any type of action.
	 *
	 * @param   string            $route    Which action is happening (install|uninstall|discover_install).
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.1
	 */
	public function postflight($route, JAdapterInstance $adapter)
	{

	}

	/**
	 * Called on installation.
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.1
	 */
	public function install(JAdapterInstance $adapter)
	{
		// Set the redirect location.
		$adapter->getParent()->setRedirectURL('index.php?option=com_shortener');
	}

	/**
	 * Called on update.
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.1
	 */
	public function update(JAdapterInstance $adapter)
	{

	}

	/**
	 * Called on uninstallation.
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.1
	 */
	public function uninstall(JAdapterInstance $adapter)
	{
		echo '<p>' . JText::_('COM_SHORTENER_UNINSTALL_TEXT') . '</p>';
	}
}
