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
?>
<script language="javascript" type="text/javascript">
    $(document).ready(function() {

        $('#searchAdvanced').button().click(function() {
            if ($('#formSearchAdvanced').validationEngine('validate')) {
                document.formSearchAdvanced.task.value = 'searchAdvanced';
                document.formSearchAdvanced.submit();
            }
            return false;
        }); 

        $('*').keypress(function(e) {
            //funciona cuando se preciona la tecla enter
            if (e.keyCode == 13) {
                var contenido = $('#contenido').val();
                if (contenido != '') {
                    document.formSearchAdvanced.task.value = 'searchAdvanced';
                    document.formSearchAdvanced.submit();
                }
                else {
                    $('#contenido').validationEngine('showPrompt', '<?php echo JText::_('COM_LOGICALDOC_SOME_OF_THESE_FIELDS_ARE_REQUIRED');?>', 'error', true);       
                    return false;
                }
            }
        });

        $("#searchReturn").button().click(function() {
            document.formSearchAdvanced.task.value = 'returnDesktop';
            document.formSearchAdvanced.submit();
        });

        $("#tab").tabs();

        $('#formSearchAdvanced').validationEngine();
    });


</script>
<form action= "" method="post" name="formSearchAdvanced"
      id="formSearchAdvanced" enctype="multipart/form-data">
    <div id="tab">
        <ul>
            <li><a href="#dato"><?php echo JText::_('COM_LOGICALDOC_ADVANCED_SEARCH') ?></a></li>                
        </ul>
        <div id="dato">
            <table id="datoTable" align="center">
                <tr>
                    <td align="right"><?php echo JText::_('COM_LOGICALDOC_CONTENT') ?>:</td>
                    <td>
                        <input type="text" name="contenido" id="contenido" value="<?php echo $this->sessionSearch->get('contenido'); ?>" class="validate[required] text-input" />
                    </td>
                </tr>
                <tr>
                    <td align="right">Search Fields:</td>
                    <td>
                        <input type="checkbox" name="sfields[]" id="sfieldsc" value="content" <?php if(isset($_POST['sfields']) && is_array($_POST['sfields']) && in_array('content', $_POST['sfields'])) echo 'checked="checked"'; ?> />Content
                        <input type="checkbox" name="sfields[]" id="sfieldst" value="title" <?php if(isset($_POST['sfields']) && is_array($_POST['sfields']) && in_array('title', $_POST['sfields'])) echo 'checked="checked"'; ?> />Title
						<input type="checkbox" name="sfields[]" id="sfieldsk" value="tags" <?php if(isset($_POST['sfields']) && is_array($_POST['sfields']) && in_array('tags', $_POST['sfields'])) echo 'checked="checked"'; ?> />Tags                        
                    </td>
                </tr>
                <tr>
                    <td><?php echo JText::_('COM_LOGICALDOC_DOCUMENT_TYPE') ?>:</td>
                    <td>
                        <select id="tipoDocumento" name="tipoDocumento">
                            <option value=""></option>
                            <option value="xls,xlsx" <?php if ($this->sessionSearch->get('tipoDocumento') == 'xls,xlsx') echo "selected='selected'"; ?>>MS Excel</option>
                            <option value="ppt,pptx" <?php if ($this->sessionSearch->get('tipoDocumento') == 'ppt,pptx') echo "selected='selected'"; ?>>MS PowerPoint</option>
                            <option value="doc,docx" <?php if ($this->sessionSearch->get('tipoDocumento') == 'doc,docx') echo "selected='selected'"; ?>>MS Word</option>
                            <option value="odp" <?php if ($this->sessionSearch->get('tipoDocumento') == 'odp') echo "selected='selected'"; ?>>ODF Presentation</option>
                            <option value="ods" <?php if ($this->sessionSearch->get('tipoDocumento') == 'ods') echo "selected='selected'"; ?>>ODF Spreadsheet</option>
                            <option value="odt" <?php if ($this->sessionSearch->get('tipoDocumento') == 'odt') echo "selected='selected'"; ?>>ODF Text Document</option>
                            <option value="pdf" <?php if ($this->sessionSearch->get('tipoDocumento') == 'pdf') echo "selected='selected'"; ?>>PDF</option>
                            <option value="rtf" <?php if ($this->sessionSearch->get('tipoDocumento') == 'rtf') echo "selected='selected'"; ?>>RTF</option>
                            <option value="txt" <?php if ($this->sessionSearch->get('tipoDocumento') == 'txt') echo "selected='selected'"; ?>>TXT</option>
                            <option value="html,htm" <?php if ($this->sessionSearch->get('tipoDocumento') == 'html,htm') echo "selected='selected'"; ?>>HTML</option>
                            <option value="xml" <?php if ($this->sessionSearch->get('tipoDocumento') == 'xml') echo "selected='selected'"; ?>>XML</option>                                
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <table width="100%" id="buttonTable">
            <tr>
                <td align="right" colspan="2">
                    <button id="searchReturn">
                        <?php echo JText::_('COM_LOGICALDOC_GO_BACK') ?>
                    </button>
                    <button id="searchAdvanced">
                        <?php echo Jtext::_('COM_LOGICALDOC_SEARCH') ?>
                    </button>                        
                </td>
            </tr>
        </table>            
        <input type="hidden" name="option" value="com_logicaldoc" />
        <input type="hidden" name="view" value="search"/>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="id" value="<?php echo $this->rowConfiguration->idConfiguration; ?>"/>
		<input type="hidden" name="documento" id="documento" value="1" />
    </div><!--fin del tabs-->
</form>
