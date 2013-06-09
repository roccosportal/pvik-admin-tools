<?php
namespace PvikAdminTools\Library;
/**
 * A helper class for the PvikAminTools configuration.
 */
class ConfigurationHelper {
    /**
     * Contains the configuration for the tables.
     * @var array 
     */
    protected $tables;
    /**
     * Contains the configuration for the current set up table.
     * @var array 
     */
    protected $currentTable;
    
    /**
     * 
     */
    public function __construct(){
        $this->tables = \Pvik\Core\Config::$config['PvikAdminTools']['Tables'];
       
        // set current table to first table
        foreach($this->tables as $tableName => $configuration){
            $this->setCurrentTable($tableName);
            break;
        }
    }
    
    /**
     * Sets the current used table.
     * @param string $tableName 
     */
    public function setCurrentTable($tableName){
        if($this->tableExists($tableName)){
            $this->currentTable = $this->tables[$tableName];
        }
        else {
            throw new \Exception('PvikAdminTools: table ' . $tableName  . ' does not exists in configuration');
        }
    }
    /**
     * Checks if a table exists in the configuration.
     * @param string $tableName
     * @return bool 
     */
    public function tableExists($tableName){
        return isset($this->tables[$tableName]);
    }
    
    /**
     * Returns the configuration for all tables.
     * @return type 
     */
    public function getTables(){
        return $this->tables;
    }
    
    /**
     * Returns the configuration for currently used table.
     * @return array 
     */
    public function getCurrentTable(){
        return $this->currentTable;
    }
    
    /**
     * Checks if a field exists in the currently used table.
     * @param string $fieldName
     * @return bool 
     */
    public function fieldExists($fieldName){
        if(isset($this->currentTable['Fields'][$fieldName])){
            return true;
        }
        return false;
    }
    
    /**
     * Checks if a field is nullable in the currently used table.
     * @param string $fieldName
     * @return bool 
     */
    public function isNullable($fieldName){
        $field = $this->getField($fieldName);
        if(isset($field['Nullable'])){
            return $field['Nullable'];
        }
        return false;
    }
    
    /**
     * Returns the configuration for a field in the currently used table.
     * @param string $fieldName
     * @return array 
     */
     public function getField($fieldName){
        if($this->fieldExists($fieldName)){
            return $this->currentTable['Fields'][$fieldName];
        }
        return null;
    }
    
    /**
     * Returns the type for a field in the currently used table.
     * @param string $fieldName
     * @return string 
     */
    public function getFieldType($fieldName){
        $field = $this->getField($fieldName);
        if($field!=null&&isset($field['Type'])){
            return $field['Type'];
        }
        return null;
    }
    
    /**
     * Checks if a field is from type "Ignore" in the currently used table.
     * @param string $fieldName
     * @return bool 
     */
    public function isTypeIgnore($fieldName){
        $fieldType = $this->getFieldType($fieldName);
        if($fieldType=='Ignore'){
            return true;
        }
        return false;
    }
    
    /**
     * Returns the field value for 'ShowInOverview' or false.
     * @param string $fieldName
     * @return bool 
     */
    public function showInOverview($fieldName){
        if($this->hasValueField($fieldName, 'ShowInOverview')){
            return $this->getValue($fieldName, 'ShowInOverview');
        }
        return false;
    }
    
    /**
     * Returns the field value for 'Disabled' or false.
     * @param string $fieldName
     * @return bool 
     */
    public function isDisabled($fieldName){
        if($this->hasValueField($fieldName, 'Disabled')){
            return $this->getValue($fieldName, 'Disabled');
        }
        return false;
    }
    
    /**
     * Checks if a field is set up for a field configuration
     * @param string $fieldName
     * @param string $valueField
     * @return bool 
     */
    public function hasValueField($fieldName, $valueField){
        $field = $this->getField($fieldName);
        if($field!=null&&isset($field[$valueField])){
            return true;
        }
        return false;
    }
    
    
    /**
     * Sets the value of a field configuration field.
     * @param string $fieldName
     * @param string $valueField
     * @return mixed 
     */
    public function setValue($fieldName, $valueField, $value){
        if($this->fieldExists($fieldName)){
            $this->currentTable['Fields'][$fieldName][$valueField] = $value;
            
        }
        else {
            throw new \Exception('PvikAdminTools: ' . $fieldName .' does not exists');
        } 
    }
    
    
    /**
     * Returns the value of a field configuration field.
     * @param string $fieldName
     * @param string $valueField
     * @return mixed 
     */
    public function getValue($fieldName, $valueField){
        
        if($this->hasValueField($fieldName, $valueField)){
            $field = $this->getField($fieldName);
            return $field[$valueField];
        }
        else {
            return null;
        }
       
         
    }
    /**
     * Returns a list of field in the currently used table.
     * @return ArrayObject 
     */
    public function getFieldList(){
        $fieldList = new \ArrayObject();
        foreach($this->currentTable['Fields'] as $fieldName => $definition){
            $fieldList->append($fieldName);
        }
        return $fieldList;
    }
    
    /**
     * Checks if currently used table has foreign tables.
     * @return bool 
     */
    public function hasForeignTables(){
        if(isset($this->currentTable['ForeignTables'])){
            return true;
        }
        return false;
    }
    
    /**
     * Returns the configuration for foreign tables for the currently used table.
     * @return array 
     */
    public function getForeignTables(){
        if($this->hasForeignTables()){
            return $this->currentTable['ForeignTables'];
        }
        return null;
    }
}
