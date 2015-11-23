<?php

/**
 * Exports user groups as XML.
 */
class ThemeHouse_UserGroups_ViewAdmin_UserGroup_Export extends XenForo_ViewAdmin_Base
{
	public function renderXml()
	{
		$title = str_replace(' ', '-', utf8_romanize(utf8_deaccent($this->_params['userGroup']['title'])));

		$this->setDownloadFileName('user_group-' . $title . '.xml');
		return $this->_params['xml']->saveXml();
	}
}