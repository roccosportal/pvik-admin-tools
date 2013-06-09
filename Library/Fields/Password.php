<?php

namespace PvikAdminTools\Library\Fields;

/**
 * Displays a checkbox.
 */
class Password extends Base {

    protected function addHtmlSingleControl() {
        $disabled = '';
        if ($this->configurationHelper->fieldExists($this->fieldName)
                && $this->configurationHelper->isDisabled($this->fieldName)) {
            $disabled = 'disabled="disabled"';
        }
        $this->html .= '<input class="span8" name="' . $this->getLowerFieldName() . '" type="password" ' . $this->getPresetValue() . ' ' . $disabled . ' />';
    }

    /**
     * Returns the preset value for the checkbox
     * @return string 
     */
    public function getPresetValue() {
        if($this->isNewEntity()){
            return '';
        }
        return 'value="******"';
    }

    /**
     * Validates the checkbox.
     * @return ValidationState 
     */
    public function validation() {
        // ignore validation
        return $this->validationState;
    }

    /**
     * Returns the html for the overview.
     * @return string 
     */
    public function htmlOverview() {
        return '******';
    }

    /**
     * Updates the entity.
     */
    public function update() {
        $fieldName = $this->fieldName;
        $value = $this->getPost();
        if ($value != '******') {
            $random = md5(uniqid(mt_rand(), true));
            $salt = '$2a$07$' . $random . '$';
            $this->entity->$fieldName = crypt($value, $salt);
        }
    }

}
