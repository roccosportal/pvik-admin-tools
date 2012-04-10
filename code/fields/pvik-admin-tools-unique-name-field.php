<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-base-field.php');
/**
 * Displays a text field that creates a unique name from a other field.
 */
class PvikAdminToolsUniqueNameField extends PvikAdminToolsBaseField{
    /**
     * Returns the html for the field.
     * @return type 
     */
    public function HtmlSingle(){
        $this->Html = '';
        $this->AddHtmlLabel();
        
       
        $Disabled = 'disabled="disabled"';
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
     * Returns the  html for the overview.
     * @return type 
     */
    public function HtmlOverview() {
        $this->Html = '';
        $FieldName = $this->FieldName;
        return  $Model->$FieldName;
    }
    
    /**
     * Checks if the field value is valid.
     * @return ValidationState. 
     */
    public function Validation() {
        // ignore 
        return $this->ValidationState;
    }
    
    public function Update(){
        $Field = $this->PvikAdminToolsTablesConfigurationHelper->GetField($this->FieldName);
        // wrong 'UseField' configuration
        if(!isset($Field['UseField'])||!$this->FieldDefinitionHelper->FieldExists($Field['UseField'])){
            throw new Exception('PvikAdminTools: UseField for '. $FieldName . ' is not set up correctly. UseField is missing or it the stated field does not exists.');
        }
        $UseField = $Field['UseField'];
        $FieldName = $this->FieldName;
        $this->Model->$FieldName = $this->CreateUniqueName( $UseField);
    }
    
    protected function CreateUniqueName($UseField){
        $Name = $this->GetPOST($UseField);
        $IsValid = false;
        $UrlSafeName = PvikAdminToolsHelp::MakeUrlSafe($Name);
        $UniqueName = $UrlSafeName;
        if($this->CheckIfUniqueName($UniqueName,  $UseField)){
            $IsValid = true;;
        }
        // add numbers till name is unique
        $i = 1;
        while(!$IsValid){
           $UniqueName = $UrlSafeName . '-' . $i;
           if($this->CheckIfUniqueName($UniqueName, $UseField)){
            $IsValid = true;;
           }
           $i++;
         }
        return $UniqueName;
    }
    
    protected function CheckIfUniqueName($Name, $UseField){
        $PrimaryKey = $this->Model->GetPrimaryKey();
        $ModelArray = $this->Model->GetModelTable()->LoadAll()
            ->FilterEquals($UseField, $Name);

        if($PrimaryKey!=null&&$PrimaryKey!=''){         
            $ModelArray = $ModelArray->FilterNotEquals($this->Model->GetModelTable()->GetPrimaryKeyName(), $PrimaryKey);
        }
            
        // if query return an empty object, than no other entry has this name
        if($ModelArray->IsEmpty()){
            return true;
        }
        else {
            return false;
        }

    }
    
    
}
?>