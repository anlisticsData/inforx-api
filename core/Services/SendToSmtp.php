<?php 

namespace Services;

use Commons\Uteis;
use Exception;
use Interfaces\ISend;
use PHPMailer\PHPMailer\PHPMailer;
use Resources\APPLICATION;
use Resources\Strings;
use Resources\HttpStatus;
use Dtos\EmailSend;

class SendToSmtp implements ISend{

    private $mailer = null;
    private $email;

    public function __construct(EmailSend $emailDto,$IsHTML=true)
    {

        $this->mailer  = new PHPMailer();
        $this->mailer->IsSMTP();
        $this->mailer->SMTPDebug    =  $_SERVER["SMTP_DEBUG"];
        $this->mailer->Port         =  $_SERVER["SMTP_PORT"];
        $this->mailer->Host         =  $_SERVER["SMTP_HOST"];
        $this->mailer->SMTPAuth     =  $_SERVER["SMTP_AUTH"];
        $this->mailer->Username     =  $_SERVER["SMTP_USER"];
        $this->mailer->Password     =  $_SERVER["SMTP_USER_PASSWORD"];
        $this->mailer->IsHTML($IsHTML); 
        $this->email =  $emailDto->to();
    }
    public  function send(){
       try{
            $this->mailer->FromName = $this->email["formname"]; 
            $this->mailer->From =$this->mailer->Username; 
            foreach($this->email["address"] as $index => $row){       
           
                $this->mailer->AddAddress($row['email'], $row['sender']);
            }
            $this->mailer->Subject = $this->email["subject"];
            $this->mailer->Body = $this->email["body"];
            if (!$this->mailer->Send()) {
                throw new Exception(Strings::$STR_CONNECTION_FAILED_SMTP, HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
            }
       }catch(Exception $e){
           throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
       }

    }

}