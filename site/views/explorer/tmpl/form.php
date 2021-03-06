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
defined( '_JEXEC' ) or die; 
?>
<script language="javascript" type="text/javascript">
    $(document).ready(function(){

        $("#searchAdvanced").button().click(function() {
            var contenido = $('#contenido').val();
            var nombre = $('#nombre').val();
            var palabraClave = $('#palabraClave').val();
            if (contenido != '' || nombre != '' || palabraClave != ''){
                document.formSearchAdvanced.task.value = 'searchAdvanced';
                document.formSearchAdvanced.submit();
            }
            else{
                $('#nombre').validationEngine('showPrompt', '<?php echo JText::_('COM_LOGICALDOC_SOME_OF_THESE_FIELDS_ARE_REQUIRED');?>', 'error', true);                     
                return false;
            }
        });
        
        $('*').keypress(function(e){
            //funciona cuando se preciona la tecla enter
            if (e.keyCode == 13){
                var contenido = $('#contenido').val();
                var nombre = $('#nombre').val();
                var palabraClave = $('#palabraClave').val();
                if (contenido != '' || nombre != '' || palabraClave != ''){
                    document.formSearchAdvanced.task.value = 'searchAdvanced';
                    document.formSearchAdvanced.submit();
                }
                else{
                    $('#nombre').validationEngine('showPrompt', '<?php echo JText::_('COM_LOGICALDOC_SOME_OF_THESE_FIELDS_ARE_REQUIRED');?>', 'error', true);                     
                    return false;
                }
            }
        });
        
        $("#searchReturn").button().click(function() {
            document.formSearch.task.value = 'returnDesktop';
            document.formSearch.submit();
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
                        <input type="text" name="contenido" id="contenido" value="" />
                    </td>
                </tr>
                <tr>
                    <td align="right">Search Fields:</td>
                    <td>
                        <input type="checkbox" name="sfields[]" id="sfieldsc" value="content" checked="checked" />Content
                        <input type="checkbox" name="sfields[]" id="sfieldst" value="title" checked="checked" />Name
			<input type="checkbox" name="sfields[]" id="sfieldsk" value="tags" checked="checked" />Tags                        
                    </td>
                </tr>
                <tr>
                    <td><?php echo JText::_('COM_LOGICALDOC_DOCUMENT_TYPE') ?>:</td>
                    <td>
                        <select id="tipoDocumento" name="tipoDocumento">
                            <option value=""></option>
                            <option value="xls,xlsx">MS Excel</option>
                            <option value="ppt,pptx">MS PowerPoint</option>
                            <option value="doc,docx">MS Word</option>
                            <option value="odp">ODF Presentation</option>
                            <option value="ods">ODF Spreadsheet</option>
                            <option value="odt">ODF Text Document</option>
                            <option value="pdf">PDF</option>
                            <option value="rtf">RTF</option>
                            <option value="txt">TXT</option>
                            <option value="html,htm">HTML</option>
                            <option value="xml">XML</option>                                
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
