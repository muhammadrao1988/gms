<?php
/**
 * Developed by TGM.
 * Email: info@topgearmedia.co.uk
 * Autour: TGM TEAM
 * Date: 5/30/12
 * Time: 5:46 PM
 */

?>
<div class="form_module form-view" style=" min-height: 500px; margin: 10px;">
    <div class="panel_header"><?=ucwords(str_replace('_',' ',$this->table));?> Import File In Database</div>
    <?php
    $buttons = array('import_db', 'refresh','back');
    echo get_form_actions($buttons);
    echo validation_errors('<div class="error">', '</div>');
    ?>
    <div class="module_form"><!-- Must form put in div or any selctor-->
        <form name="module_form" action="<?=$formAction;?>/import" enctype="multipart/form-data" method="post">
            <input type="hidden" name="import" value="1">
            <table border="0" width="100%">
                <tr>
                    <td class="td_title"><label for="type">File Type :</label></td>
                    <td>
                        <select name="type" id="type" class="validate[required]">
                            <option value="csv">CSV</option>
                            <!--<option value="xml">XML</option>-->
                        </select>
                        &nbsp;<span class="req">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="td_title"><label for="file">File  :</label></td>
                    <td>
                        <input type="file" name="file" id="file">&nbsp;<span class="req">*</span>
                    </td>
                </tr>
                <?php
                if($total_records){
                    ?>
                    <tr>
                        <td colspan=""><p align="center" style="color: red; font-weight: bold;"><?=$total_records;?> record import.</p></td>
                    </tr>
                    <?
                }
                ?>
            </table>
        </form>
    </div>
</div>