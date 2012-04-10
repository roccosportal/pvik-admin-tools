<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-base-field.php');
/**
 * Displays a textarea.
 */
class PvikAdminToolsTextareaField extends PvikAdminToolsBaseField {
    
    /**
     * Returns the html for the field.
     * @return type 
     */
    public function HtmlSingle(){
        $this->Html = '';
        $this->AddHtmlLabel();
        $this->Html .= '<textarea class="textarea-field" name="'. $this->GetLowerFieldName() .'" cols="50" rows="15" >'.htmlentities($this->GetPresetValue()) .'</textarea>';
        $this->AddHtmlValidationField();
        return $this->Html;
    }
    
    /**
     * Returns the html for the overview.
     * @return type 
     */
    public function HtmlOverview() {
        $this->Html = '';
        $FieldName = $this->FieldName;
        return  $this->Model->$FieldName;
    }
}
?>