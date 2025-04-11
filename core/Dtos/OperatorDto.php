<?php

namespace Dtos;

use Commons\Uteis;
use PhpParser\Node\Expr\List_;

class OperatorDto
{

    private $inputs = ["id","name","state", "created_at", "deleted_at", "email", "password", "groups_id"];
    public  $id;
    public  $name;
    public  $state;
    public  $email;
    public  $password;
    public  $created_at;
    public  $deleted_at;
    public  $groups_id;
    public function __construct($data = null)
    {
        if (!is_null($data) && is_array($data)) {
            foreach ($data as $key => $row) {
                if (in_array($key, $this->inputs)) {
                    $this->$key = $row;
                }
            }
        }
    }

    public function  toArray(){
        $list=[];

        foreach($this->inputs as $key =>$row ){
           $list[$row]=$this->$row;
        }

        return   $list;
    }

}
