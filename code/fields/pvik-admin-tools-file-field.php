<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-normal-field.php');
/**
 * Displays a normal text field for a file path.
 */
class PvikAdminToolsFileField extends PvikAdminToolsNormalField{
    
    /**
     * Checks if the value is a file.
     * @return ValidationState 
     */
    public function Validation() {
        parent::Validation();
        if($this->ValidationState->GetError($this->FieldName)==null && !is_file(Core::RealPath($this->GetPOST()))){
              $this->ValidationState->SetError($this->FieldName, 'Must be a valid file.');
        }
        return $this->ValidationState;
    }
    
}
?>
