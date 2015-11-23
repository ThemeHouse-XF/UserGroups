<?php

class ThemeHouse_UserGroups_Listener_FrontControllerPostView extends ThemeHouse_Listener_FrontControllerPostView
{
	/**
	 * @see ThemeHouse_Listener_Template::run()
	 */
	public function run()
	{
		switch ($this->_routePath)
		{
			case 'user-groups':
				$this->_userGroups();
				break;
			default:
				if (preg_match("#^user-groups/(?:([a-z\-_]*)\.)?([0-9]*)/edit#", $this->_routePath, $matches))
				{
					$this->_userGroupsEdit($matches[2], $matches[1]);
				}
		}
		return parent::run();
	}

	public static function frontControllerPostView(XenForo_FrontController $fc, &$output)
	{
		$frontControllerPostView = new ThemeHouse_UserGroups_Listener_FrontControllerPostView($fc, $output);
		$output = $frontControllerPostView->_run();
	}

	protected function _userGroups()
	{
		$this->_appendTemplateAfterTopCtrl('th_user_groups_topctrl_import_usergroups');
	}

	/**
	 * @param string $fieldId
	 */
	protected function _userGroupsEdit($userGroupId, $title = '')
	{
	    if (XenForo_Application::debugMode()) {
    		$this->_assertResponseCode(200);
    		$viewParams['userGroup'] = array('user_group_id' => $userGroupId, 'title' => $title);
    		$this->_appendTemplateAfterTopCtrl('th_user_groups_topctrl_export_usergroups', $viewParams);
	    }
	}
}