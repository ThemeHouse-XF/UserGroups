<?php

class ThemeHouse_UserGroups_Extend_XenForo_Model_UserGroup extends XFCP_ThemeHouse_UserGroups_Extend_XenForo_Model_UserGroup
{
    /**
     * Gets the XML representation of a user group, including permissions.
     *
     * @param array $userGroup
     *
     * @return DOMDocument
     */
    public function getUserGroupXml(array $userGroup)
    {
        $document = new DOMDocument('1.0', 'utf-8');
        $document->formatOutput = true;

        $rootNode = $document->createElement('user_group');
        $rootNode->setAttribute('user_title', $userGroup['user_title']);
        $rootNode->setAttribute('username_css', $userGroup['username_css']);
        $rootNode->setAttribute('display_style_priority', $userGroup['display_style_priority']);
        $rootNode->setAttribute('title', $userGroup['title']);
        $document->appendChild($rootNode);

        $permissionsNode = $document->createElement('permission_entries');
        $rootNode->appendChild($permissionsNode);
        $this->getModelFromCache('XenForo_Model_Permission')->appendUserGroupPermissionEntryXml($permissionsNode, $userGroup['user_group_id']);

        return $document;
    }

    /**
     * Imports a user group XML file.
     *
     * @param SimpleXMLElement $document
     * @param integer $overwriteUserGroupId
     */
    public function importUserGroupXml(SimpleXMLElement $document, $overwriteUserGroupId = 0)
    {
        if ($document->getName() != 'user_group')
        {
            throw new XenForo_Exception(new XenForo_Phrase('th_provided_file_is_not_valid_user_group_xml_usergroups'), true);
        }

        $title = (string)$document['title'];
        if ($title === '')
        {
            throw new XenForo_Exception(new XenForo_Phrase('th_provided_file_is_not_valid_user_group_xml_usergroups'), true);
        }

        $userGroupInfo = array(
            'title' => $title,
            'display_style_priority' => (integer)$document['display_style_priority'],
            'username_css' => (string)$document['username_css'],
            'user_title' => (string)$document['user_title'],
        );

        $permissions = array();
        $permissionEntries = $document->permission_entries;
        foreach ($permissionEntries->permission_entry AS $permissionEntry)
        {
            $value = (string)$permissionEntry['permission_value'];
            if ($value == 'use_int') {
                $value = (integer)$permissionEntry['permission_value_int'];
            }
            $permissions[(string)$permissionEntry['permission_group_id']][(string)$permissionEntry['permission_id']] = $value;
        }

        $userGroupId = $this->updateUserGroupAndPermissions($overwriteUserGroupId, $userGroupInfo, $permissions);
    }
}