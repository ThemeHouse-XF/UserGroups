<?php

class ThemeHouse_UserGroups_Extend_XenForo_ControllerAdmin_UserGroup extends XFCP_ThemeHouse_UserGroups_Extend_XenForo_ControllerAdmin_UserGroup
{
    public function actionExport()
    {
        $userGroupId = $this->_input->filterSingle('user_group_id', XenForo_Input::UINT);
		$userGroup = $this->_getUserGroupOrError($userGroupId);

        $this->_routeMatch->setResponseType('xml');

        $viewParams = array(
            'userGroup' => $userGroup,
            'xml' => $this->_getUserGroupModel()->getUserGroupXml($userGroup)
        );

        return $this->responseView('ThemeHouse_UserGroups_ViewAdmin_UserGroup_Export', '', $viewParams);
    }

    public function actionImport()
    {
        $userGroupModel = $this->_getUserGroupModel();

        if ($this->isConfirmedPost())
        {
            $input = $this->_input->filter(array(
                'target' => XenForo_Input::STRING,
                'overwrite_user_group_id' => XenForo_Input::UINT
            ));

            $upload = XenForo_Upload::getUploadedFile('upload');
            if (!$upload)
            {
                return $this->responseError(new XenForo_Phrase('th_please_upload_valid_user_group_xml_file_usergroups'));
            }

            if ($input['target'] == 'overwrite')
            {
                $this->_getUserGroupOrError($input['overwrite_user_group_id']);
            }
            else
            {
                $input['overwrite_user_group_id'] = 0;
            }

            $document = $this->getHelper('Xml')->getXmlFromFile($upload);
            $caches = $userGroupModel->importUserGroupXml($document, $input['overwrite_user_group_id']);

            return XenForo_CacheRebuilder_Abstract::getRebuilderResponse($this, $caches, XenForo_Link::buildAdminLink('user-groups'));
        }
        else
        {
            $viewParams = array(
                'userGroups' => $userGroupModel->getAllUserGroups()
            );

            return $this->responseView('ThemeHouse_UserGroups_ViewAdmin_UserGroup_Import', 'th_user_group_import_usergroups', $viewParams);
        }
    }
}