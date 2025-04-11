<?php
namespace Interfaces\UploadRepository;

use Dtos\UploadDto;

interface IUploadRepository{
   function records();
   function by($uploadId);
   function created(UploadDto $file);
}