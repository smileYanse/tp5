<?php
namespace RongCloud;
include 'SendRequest.php';
include 'methods/User.php';
include 'methods/Message.php';
include 'methods/Wordfilter.php';
include 'methods/Push.php';
include 'methods/SMS.php';
include 'methods/Conversation.php';
class RongCloud
{
    /**
     * 参数初始化
     * @param $appKey
     * @param $appSecret
     * @param string $format
     */
    public function __construct($format = 'json') {
        $this->SendRequest = new \SendRequest($format);
    }
    
    public function User() {
        $User = new \User($this->SendRequest);
        return $User;
    }
    
    public function Message() {
        $Message = new \Message($this->SendRequest);
        return $Message;
    }
    
    public function Wordfilter() {
        $Wordfilter = new \Wordfilter($this->SendRequest);
        return $Wordfilter;
    }
    
    public function Push() {
        $Push = new \Push($this->SendRequest);
        return $Push;
    }
    
    public function SMS() {
        $SMS = new \SMS($this->SendRequest);
        return $SMS;
    }
    public function Conversation(){
        $conver=new \Conversation($this->SendRequest);
        return $conver;
    }
    
}