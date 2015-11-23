<?php

class ThemeHouse_UserGroups_Listener_LoadClassModel extends ThemeHouse_Listener_LoadClass
{
	/**
	 * Gets the classes that are extended for this add-on. See parent for explanation.
	 *
	 * @return array
	 */
	protected function _getExtends()
	{
		return array(
			'XenForo_Model_Permission' => 'ThemeHouse_UserGroups_Extend_XenForo_Model_Permission', /* END 'XenForo_Model_Permission' */
		    'XenForo_Model_UserGroup' => 'ThemeHouse_UserGroups_Extend_XenForo_Model_UserGroup', /* END 'XenForo_Model_UserGroup' */
		);
	} /* END ThemeHouse_UserGroups_Listener_LoadClassModel::_getExtends */

	public static function loadClassModel($class, array &$extend)
	{
		$loadClassModel = new ThemeHouse_UserGroups_Listener_LoadClassModel($class, $extend);
		$extend = $loadClassModel->run();
	} /* END ThemeHouse_UserGroups_Listener_LoadClassModel::loadClassModel */
}