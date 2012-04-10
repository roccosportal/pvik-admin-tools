<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-base-field.php');
/**
 * Displays a text field.
 */
class PvikAdminToolsNormalField extends PvikAdminToolsBaseField{
    /**
     * Returns the html.
     * @return string 
     */
    public function HtmlSingle(){
        $this->Html = '';
        $this->AddHtmlLabel();
        
        $Disabled = '';
        if($this->PvikAdminToolsTablesConfigurationHelper->FieldExists($this->FieldName)
                && $this->PvikAdminToolsTablesConfigurationHelper->IsDisabled($this->FieldName)){
            $Disabled = 'disabled="disabled"';
        }
        
        if($this->IsNewModel){
            $this->Html .= '<input class="input-field" name="'. $this->GetLowerFieldName() .'" type="text" value="'. $this->GetPresetValue() .'" ' . $Disabled . ' />';
        }
        else {
            $this->Html .= '<input class="input-field" name="'. $this->GetLowerFieldName() .'" type="text" value="'. $this->GetPresetValue() .'" ' . $Disabled . ' />';
        }
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