<?php

use Requests\UploadRequest;
use Services\UploadServices;
use Dtos\UploadDto;
use Commons\ResponseJson;
use Commons\Uteis;
use Middleware\Authorization;
use Resources\APPLICATION;
use Resources\HttpStatus;
use Resources\Strings;
use Services\UserServices;

require_once __DIR__."./../../core/Settings.php";
try{

   
    Authorization::Init();
    $request =  new UploadRequest();
    $UploadServices =  new UploadServices();
    $fileTemp = $request->file["file"]['tmp_name'];
	$name = $request->file["file"]['name'];
 	$extension = strrchr($name, '.');
    $extension = strtolower($extension);
    if(strstr(APPLICATION::$APP_UPLOAD_DIR_FILES_PERMISIONS, $extension))
	{
        
		$newname = md5(microtime()) . '' . $extension;
        $filesrc =APPLICATION::$APP_UPLOAD_DIR_FILES . $newname;
        if(move_uploaded_file( $fileTemp, $filesrc  )){
            $fileNew =  new UploadDto();
            $fileNew->description = $request->description;
            $fileNew->path = $newname;
            $fileNew->ext=$extension;
            $response = [
                "id" =>$UploadServices->save($fileNew),
                "url"=>APPLICATION::$APP_UPLOAD_DIR_FILES_RESPONSE."".$newname,
                "namefile"=>$newname,
                "extension"=>$extension,
            ];
            
            if($request->user_code > 0){
                $UserSercices =  new UserServices();
                $updateAvatar =   $UserSercices->updateAvatar($request->user_code,$response["id"]);
            }
            new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,$response);
        } 
        unlink($filesrc);
        new ResponseJson(HttpStatus::$HTTP_CODE_NOT_FOUND,Strings::$STR_UPLOAD_CANCELED);
    }
    throw new Exception(Strings::$STR_UPLOAD_CANCELED_TYPE_NOT_PERMITION."(".APPLICATION::$APP_UPLOAD_DIR_FILES_PERMISIONS.")",HttpStatus::$HTTP_CODE_BAD_REQUEST);
}catch(Exception $e){
    new ResponseJson($e->getCode(),$e->getMessage());
}

