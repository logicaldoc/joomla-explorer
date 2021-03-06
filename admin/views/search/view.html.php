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

class LogicalDOCViewSearch extends JViewLegacy {

    public function display($tpl = null) {

        $layout = JRequest::getVar('layout');
        $error = 0;
        require_once(JPATH_COMPONENT . DS . 'tables' . DS . 'configuration.php');

        $rowConfiguration = JTable::getInstance('Configuration', 'Table');
        $id = JRequest::getVar('id');
        $rowConfiguration->load($id);
        $url = $rowConfiguration->url;
        $user = $rowConfiguration->username;
        $password = $rowConfiguration->password;
        $this->assignRef('rowConfiguration', $rowConfiguration);
        if ($layout == 'result') {
            try {
                $contenido = JRequest::getVar('contenido');
                $nombre = JRequest::getVar('nombre');
                $palabraClave = JRequest::getVar('palabraClave');
                $documento = JRequest::getVar('documento');
                $carpeta = JRequest::getVar('carpeta');
                $tipoDocumento = JRequest::getVar('tipoDocumento');
                $sessionSearch = JFactory::getSession();
                $sessionSearch->set('contenido', $contenido);
                $sessionSearch->set('nombre', $nombre);
                $sessionSearch->set('palabraClave', $palabraClave);
                $sessionSearch->set('documento', $documento);
                $sessionSearch->set('carpeta', $carpeta);
                $sessionSearch->set('tipoDocumento', $tipoDocumento);
                $this->assignRef('sessionSearch', $sessionSearch);

                // Register WSDL
				$LDAuth = new SoapClient($url . '/services/Auth?wsdl');

                // Login
				$loginResp = $LDAuth->login(array ('username' => $user, 'password' => $password ));
                $token = $loginResp->return;

                // Register WSDL
				$LDSearch = new SoapClient($url . '/services/Search?wsdl');

				$soptions = new SearchOptions();
				
				$scontent = JRequest::getVar('contenido');
				$soptions->expression = $scontent;

                // Setup the startfolder (limit the search to just the sub-tree starting from the configured folder)
				$startFolder = $this->rowConfiguration->ldFolderID;

				$sfields = JRequest::getVar('sfields');

				if (isset($_POST['sfields'])) {
					$soptions->fields = array ();
					$fArray = $_POST['sfields'];
					for ($i=0; $i<count($fArray); $i++) {
						array_push($soptions->fields, $fArray[$i]);	
					}
				} else {
                    // user searched from the simple search (just filling the field contenido)
					$soptions->fields = array ('content', 'title', 'tags');
					if (!isset($_POST['sfields'])) {
						$_POST['sfields'] = array('content', 'title', 'tags');
					} 
				}

				$findResp = $LDSearch->find(array('sid' => $token, 'options' => $soptions));

                if (!empty($findResp->searchResult->hits)) {
                    $resultArray = $findResp->searchResult->hits;

                    //filter the result in the array based on Document Type        
                    if (!empty($tipoDocumento)) {
						$resultArray = array();
						$fdocs = $findResp->searchResult->hits;

                        if (!is_array($fdocs)) {
							$fdocs = array();
							$fdocs[] = $findResp->searchResult->hits;
						} 

                        for ($i=0; $i<count($fdocs); $i++) {
						   $docext = $fdocs[$i]->type;
						   if (!empty($docext)) {
							   $docext = strtolower($docext);					   		
							   if (strpos($tipoDocumento, $docext) !== false) {
									$resultArray[] = $fdocs[$i];
							   }
						   }	
						}
					}
                }

                $LDAuth->logout(array('sid' => $token));
                $this->assignRef('resultArray', $resultArray);
                $this->assignRef('error', $error);
            } catch (Exception $e) {
                $error = 1;
                $this->assignRef('error', $error);
            }
        }
        parent::display($tpl);
    }

}

class SearchOptions {

	var $type = 0; // 0 is full-text query
    var $expression = '';
	var $expressionLanguage = 'en';
    var $maxHits = 50;
    //var $language = 'en'; // search documents in english
	var $retrieveAliases = 0; // requested but not used
	var $caseSensitive = 0; // requested but not used	
	//var $fields = array ('title','tags','content');
	var $fields = array ();
    var $searchInSubPath = true;
	var $folderId = 4; // Default workspace ID
}


?>
