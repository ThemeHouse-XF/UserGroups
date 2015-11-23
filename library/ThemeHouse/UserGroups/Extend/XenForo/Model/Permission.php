<?php

class ThemeHouse_UserGroups_Extend_XenForo_Model_Permission extends XFCP_ThemeHouse_UserGroups_Extend_XenForo_Model_Permission
{
    /**
     * Gets all global-level permission entries for a user collection.
     *
     * @param integer $userGroupId
     * @param integer $userId
     *
     * @return array Format: [permission_entry_id] => permission_info
     */
    public function getAllGlobalPermissionEntriesForUserCollection($userGroupId = 0, $userId = 0)
    {
        $this->_sanitizeUserIdAndUserGroupForQuery($userGroupId, $userId);

        return $this->fetchAllKeyed('
			SELECT *
			FROM xf_permission_entry
			WHERE user_group_id = ? AND user_id = ?
		', 'permission_entry_id', array($userGroupId, $userId));
    }

    /**
     * Appends the permission entry list to an XML document.
     *
     * @param DOMElement $rootNode Node to append to
     * @param integer $userGroupId User group to read values/definitions from
     */
    public function appendUserGroupPermissionEntryXml(DOMElement $rootNode, $userGroupId)
    {
        $document = $rootNode->ownerDocument;

        $permissionEntries = $this->getAllGlobalPermissionEntriesForUserCollection($userGroupId);

        foreach ($permissionEntries AS $permissionEntry)
        {
            $node = $document->createElement('permission_entry');
            $node->setAttribute('permission_value_int', $permissionEntry['permission_value_int']);
            $node->setAttribute('permission_value', $permissionEntry['permission_value']);
            $node->setAttribute('permission_id', $permissionEntry['permission_id']);
            $node->setAttribute('permission_group_id', $permissionEntry['permission_group_id']);
            $rootNode->appendChild($node);
        }
    }
}