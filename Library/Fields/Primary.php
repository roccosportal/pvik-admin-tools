<?php
namespace PvikAdminTools\Library\Fields;
/**
 * Displays a text field.
 */
class Primary extends Normal{
    
    
    public function __construct($fieldName, \Pvik\Database\ORM\Entity $entity, \Pvik\Web\Request $request, \Pvik\Utils\ValidationState $validationState) {
        parent::__construct($fieldName, $entity, $request, $validationState);
        $this->configurationHelper->setValue($fieldName, 'Disabled', true);
        $this->configurationHelper->setValue($fieldName, 'Nullable', true);
    }
 
    
    
    
    public function update() {
       // do nothing
    }
    
}
