<?php

namespace  Dtos;

use Adapters\PhpModuleAdapter;


class EmailSend{
    private $formname=null;
    private $from=null;
    private $subject=null;
    private $body=null;
    private $address = [];
    public function __construct($formname,$subject,$body)
    {
        $this->formname=$formname;
        $this->subject=$subject;
        $this->body=$body;
    }


    public function addAdrress($nameSender,$emailSender)
    {
        $this->address [] = array("sender"=>$nameSender,"email"=>$emailSender);
    }


    public function to(){
        return array(
            "formname"=>PhpModuleAdapter::Utf8Dencode($this->formname),
            "from"=>PhpModuleAdapter::Utf8Dencode($this->from),
            "subject"=>PhpModuleAdapter::Utf8Dencode($this->subject),
            "body"=>PhpModuleAdapter::Utf8Dencode($this->body),
            "address"=>$this->address
        );
    }
    

}


 