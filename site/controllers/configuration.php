<?php

/**
 * Copyright (c) 2006-2022 LogicalDOC
 * WebSites: www.logicaldoc.com
 *
 * No bytes were intentionally harmed during the development of this application.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class LogicalDOCControllerConfiguration extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = false)
    {
        parent::display();
    }

    public function buscar()
    {
        require_once(JPATH_COMPONENT . DS . 'tables' . DS . 'configuration.php');
        $row = &JTable::getInstance('Configuration', 'Table');
        if ($row->idConfiguration != '') {
            $row->load($row->idConfiguration);
            $password = $row->password;
            $accessPassword = $row->accessPassword;
        }
        if (!$row->bind(JRequest::get('post'))) {
            JError::raiseError(500, $row->getError());
        }
        if ($row->idConfiguration != '') {
            $passwordBind = $row->password;
            if ($passwordBind == '') {
                $row->password = $password;
            }
            $accessPasswordBind = $row->accessPassword;
            if ($accessPasswordBind == '') {
                $row->accessPassword = $accessPassword;
            }
        }
        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
        }
        $this->setRedirect('index.php?option=com_logicaldoc&view=configuration&layout=listconfiguration', JText::_('COM_LOGICALDOC_GUARDED'));
    }

    public function delete()
    {
        require_once(JPATH_COMPONENT . DS . 'tables' . DS . 'configuration.php');
        $row = &JTable::getInstance('Configuration', 'Table');
        $row->idConfiguration = JRequest::getVar('id');
        if (!$row->delete()) {
            JError::raiseError(500, $row->getError());
        }
        $this->setRedirect('index.php?option=com_logicaldoc&view=configuration&layout=listconfiguration', JText::_('COM_LOGICALDOC_DELETED'));
    }

    public function edit()
    {
        JRequest::setVar('view', 'configuration');
        JRequest::setVar('layout', 'formconfiguration');
        $this->display();
    }

    public function test()
    {
        require_once(JPATH_COMPONENT . DS . 'tables' . DS . 'configuration.php');
        $row = &JTable::getInstance('Configuration', 'Table');
        $id = JRequest::getVar('id');
        $row->load($id);
        $user = $row->username;
        $password = $row->password;
        $url = $row->url;
        $message = "";
        try {

            $LDAuth = new SoapClient($url . '/services/Auth?wsdl');
            // Login
            $loginResp = $LDAuth->login(array('username' => $user, 'password' => $password));
            $token = $loginResp->return;
            $LDAuth->logout(array('sid' => $token));
            $message = JText::_('COM_LOGICALDOC_CONNECTION_SUCCEEDED');
        } catch (Exception $e) {
            $message = JText::_('COM_LOGICALDOC_CANNOT_ESTABLISH_A_CONNECTION');
        }
        $this->setRedirect('index.php?option=com_logicaldoc&view=configuration&layout=listconfiguration', $message);
    }

    public function accessLevel()
    {
        require_once(JPATH_COMPONENT . DS . 'tables' . DS . 'configuration.php');
        $row = &JTable::getInstance('Configuration', 'Table');
        $id = JRequest::getVar('id');
        $row->load($id);
        if ($row->accessLevel == 'Private') {
            $row->accessLevel = 'Public';
        } else {
            $row->accessLevel = 'Private';
        }
        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
        }
        $this->setRedirect('index.php?option=com_logicaldoc&view=configuration&layout=listconfiguration', JText::_('COM_LOGICALDOC_UPDATED'));
    }

    public function cancel()
    {
        $this->setRedirect('index.php?option=com_logicaldoc&view=configuration&layout=listconfiguration');
    }

}

?>
