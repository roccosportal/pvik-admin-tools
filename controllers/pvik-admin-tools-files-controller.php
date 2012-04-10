<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'controllers/pvik-admin-tools-base-controller.php');
/**
 * Contains the logic for uploading a file.
 */
class PvikAdminToolsFilesController extends PvikAdminToolsBaseController{
    /**
     * Logic for uploading a file.
     */
    public function UploadFile(){
        if($this->CheckPermission()){
            $ValidationState = new ValidationState();
            $Uploaded = false;
            // post data send
            if(Core::IsPOST('submit')){
                $Folders = Core::$Config['PvikAdminTools']['FileFolders'];
                $SelectedFolder = Core::GetPOST('folder');
                $FolderValid = false;
                foreach($Folders as $Folder){
                    if($SelectedFolder==$Folder){
                        $FolderValid = true;
                        break;
                    }
                }
                if ($FolderValid&&isset($_FILES['file']) && $_FILES['file']['error'] == 0){
                    $FileName =  $_FILES['file']['name'];
                    if(Core::IsPOST('name')&&Core::GetPOST('name')!=''){
                        $FileName = Core::GetPOST('name');
                    }
                    $DiretoryName = dirname(Core::RealPath($SelectedFolder . $FileName));
                    if(!is_dir($DiretoryName)){
                        if (!mkdir($DiretoryName, 0777, true)){
                            $ValidationState->SetError('File', 'error creating folder');
                        }
                    }
                    if($ValidationState->IsValid()){
                        move_uploaded_file($_FILES['file']['tmp_name'],Core::RealPath($SelectedFolder . $FileName));
                        $Uploaded = true;
                    }
                    
                }
                else {
                    $ValidationState->SetError('File', 'error uploading');
                }
            }
            $this->ViewData->Set('ValidationState', $ValidationState);
            $this->ViewData->Set('Uploaded', $Uploaded);
            $this->ExecuteView();
        }
    }
}
?>