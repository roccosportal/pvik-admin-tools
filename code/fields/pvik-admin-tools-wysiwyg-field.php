<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-base-field.php');
/**
 * Displays a WYSIWYG field.
 */
class PvikAdminToolsWysiwygField extends PvikAdminToolsBaseField {
    

    /**
     * Returns the html for the field.
     * @return string 
     */
    public function HtmlSingle(){
        $this->Html = '';
        $this->AddHtmlLabel();
        
        $File = PvikAdminToolsHelp::FileRelativePath('tinymce/jscripts/tiny_mce/tiny_mce.js');
        // only add file if not already added
        if(!PvikAdminToolsFileRegister::IsFileRegisterd($File)){
            $this->Html .= '<script type="text/javascript" src="' . $File.'" ></script >';
        }
        $this->Html .= '
        <script type="text/javascript" >
        tinyMCE.init({
                 mode : "exact",
                 elements : "'. $this->GetLowerFieldName() .'-wysiwyg",
                theme : "advanced",
                
                // Theme options - button# indicated the row# only
                theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect",
                theme_advanced_buttons2 : "cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,|,code,preview,|,forecolor,backcolor",
                theme_advanced_buttons3 : "insertdate,inserttime,|,spellchecker,advhr,,removeformat,|,sub,sup,|,charmap,emotions",      
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,
                extended_valid_elements : "pre[class]",
                verify_html : false,
                entity_encoding : "raw",
                convert_urls : false
        });
        </script >';
        $this->Html .= '<textarea class="textarea-field" id="'. $this->GetLowerFieldName().'-wysiwyg" class="WYSIWYG" name="'. $this->GetLowerFieldName() .'" cols="50" rows="15" >'. htmlentities($this->GetPresetValue()) .'</textarea>';
        $this->AddHtmlValidationField();
        return $this->Html;
    }
    
    /**
     * Returns the html for the overview.
     * @return string 
     */
    public function HtmlOverview() {
        $this->Html = '';
        $FieldName = $this->FieldName;
        return  $Model->$FieldName;
    }
}
?>