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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the behavior script.
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');

// Create shortcut to parameters.
$params = $this->state->get('params');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'url.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task);
		} else {
			alert('<?php echo $this->escape(JText::_("JGLOBAL_VALIDATION_FORM_FAILED")); ?>');
		}
	}
</script>
<div class="shortener edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($params->get('show_page_heading', 1)): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>
	<form action="<?php echo JRoute::_('index.php?option=com_shortener&s_id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
		<fieldset>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('short_url'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('short_url'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('url'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('url'); ?>
				</div>
			</div>
			<div class="form-actions">
				<div class="btn-toolbar">
					<div class="btn-group">
						<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('url.save')">
							<i class="icon-ok"></i> <?php echo JText::_('JSAVE'); ?>
						</button>
					</div>
					<div class="btn-group">
						<button type="button" class="btn" onclick="Joomla.submitbutton('url.cancel')">
							<i class="icon-cancel"></i> <?php echo JText::_('JCANCEL'); ?>
						</button>
					</div>
				</div>
			</div>
			<div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</fieldset>
	</form>
</div>
