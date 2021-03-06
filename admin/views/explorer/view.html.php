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

jimport('joomla.application.component.view');

class LogicalDOCViewExplorer extends JViewLegacy {

    public function display($tpl = null) {

        $layout = JRequest::getVar('layout');
        $app = JFactory::getApplication();
        $params = $app->getParams();
        $name = $params->get('name');

        require_once(JPATH_COMPONENT . DS . 'models' . DS . 'configuration.php');
        $modelConfiguration = new LogicalDOCModelConfiguration();
        $rowsCongiguracion = $modelConfiguration->getObject($name);
        $rowConfiguration = $rowsCongiguracion[0];
        $url = $rowConfiguration->url;
        $user = $rowConfiguration->username;
        $password = $rowConfiguration->password;
        $accessLevel = $rowConfiguration->accessLevel;
        $ldFolderID = $rowConfiguration->ldFolderID;
        $this->assignRef('rowConfiguration', $rowConfiguration);
        $error = 0;
        $entrar = 0;

        if ($layout == 'view') {
            if ($accessLevel == 'Private') {
                $entrar = JRequest::getVar("entrar");
                if ($entrar == null) {
                    $entrar = 0;
                }
            } else {
                $entrar = 1;
            }
            if ($entrar == 1) {
                try {
                    // Register WSDL
					$LDAuth = new SoapClient($url . '/services/Auth?wsdl');

                    // Login
                    $loginResp = $LDAuth->login(array ('username' => $user, 'password' => $password ));
                    $token = $loginResp->return;
                    $this->assignRef('token', $token);

					$LDDocument = new SoapClient($url . '/services/Document?wsdl');
					$LDFolder = new SoapClient($url . '/services/Folder?wsdl');

                    $folderID = JRequest::getVar('folderID');
					if ($folderID == '' || $folderID == $ldFolderID) {
                        $folderID = 4;
                    } 

                    $session = JFactory::getSession();
                    $session->set('folderID', $folderID);

                    $this->assignRef('LDAuth', $LDAuth);
                    $this->assignRef('LDDocument', $LDDocument);
                    $this->assignRef('LDFolder', $LDFolder);

					$this->assignRef('folderID', $folderID);
                    $this->assignRef('error', $error);
                    $this->assignRef('session', $session);
                } catch (Exception $e) {
                    $error = 1;
                    $this->assignRef('error', $error);
                }
            }//fin if entrar
            $mensaje = JRequest::getVar('mensaje');
            $this->assignRef('mensaje', $mensaje);
            $this->assignRef('entrar', $entrar);
        } else if ($layout == 'download') {
            try {
				$docID = JRequest::getVar('documentID');
                // Register WSDL
				$LDAuth = new SoapClient($url . '/services/Auth?wsdl');
                // Login
				$loginResp = $LDAuth->login(array ('username' => $user, 'password' => $password ));
                $token = $loginResp->return;
                // Register WSDL
				$LDDocument = new SoapClient($url . '/services/Document?wsdl');

                $getPropertiesResp = $LDDocument->getProperties(array('sid' => $token, 'documentID' => $docID));
                $properties = $getPropertiesResp->return;

                $getContent = $LDDocument->getContent(array('sid' => $token, 'documentID' => $docID));
                $content = $getContent->return;
				$LDAuth->logout(array('sid' => $token));
                $this->assignRef('path', $docID);
                $this->assignRef('properties', $properties);
                $this->assignRef('content', $content);
            } catch (Exception $e) {
                $error = 1;
                echo 'Caught exception: ',  $e->getMessage(), "\n";
				var_dump($e->getMessage());
                echo $e->getTraceAsString();
            }
        }
        parent::display($tpl);
    }

}

?>
