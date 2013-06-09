<?php
namespace PvikAdminTools\Library\Fields;
/**
 * Displays a normal text field for a file path.
 */
class File extends Normal{
    
    /**
     * Checks if the value is a file.
     * @return ValidationState 
     */
    public function validation() {
        parent::validation();
        if($this->validationState->getError($this->fieldName)==null && !is_file(\Pvik\Core\Path::realPath($this->getPOST()))){
              $this->validationState->setError($this->fieldName, 'Must be a valid file.');
        }
        return $this->validationState;
    }
    
}

