<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Shortener
 *
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Joomla Shortener plugin.
 *
 * @package     Joomla.Plugin
 * @subpackage  System.Shortener
 * @since       3.1
 */
class PlgContentShortener extends JPlugin
{
	/**
	 * Method is called by the view.
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   object   &$article  The content object.  Note $article->text is also available.
	 * @param   object   &$params   The content params.
	 * @param   integer  $page      The 'page' number.
	 *
	 * @return  string
	 *
	 * @since   3.1
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		if (!in_array($article->catid, $this->params->get('categories')) || $context != 'com_content.article')
		{
			return;
		}

		// Include dependancies.
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_shortener/models', 'ShortenerModel');
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_shortener/tables');

		// Get an instance of the url table.
		$url = JTable::getInstance('Url', 'ShortenerTable');

		$link = JUri::getInstance()->toString();

		if ($url->load(array('url' => $link)))
		{
			return true;
		}

		// Load the backend helper.
		require_once JPATH_ADMINISTRATOR . '/components/com_shortener/helpers/shortener.php';

		// Get an instance of the generic url model.
		$model = JModelLegacy::getInstance('Url', 'ShortenerModel', array('ignore_request' => true));

		// Load the parameters.
		$params = JComponentHelper::getParams('com_shortener');

		$date = JDate::getInstance();

		// Attempt to save the url.
		$data  = array(
			'id'        => 0,
			'asset_id'  => 0,
			'short_url' => ShortenerHelper::rand($params->get('size_url')),
			'url'       => $link,
			'state'     => 1,
			'language'  => '*',
		);

		// Save the data.
		$model->save($data);

		return true;
	}
}
