<?php
use think\Exception;

class Conversation{

	private $SendRequest;
	
	public function __construct($SendRequest) {
       		$this->SendRequest = $SendRequest;
    }


    public function setNotification($conversationType,$requestId,$targetId,$isMuted){
        try{
            if (empty($conversationType))
                throw new Exception('Paramer "conversationType" is required');

            if (empty($requestId))
                throw new Exception('Paramer "requestId" is required');

            if (empty($targetId))
                throw new Exception('Paramer "targetId" is required');

            if (empty($isMuted))
                throw new Exception('Paramer "isMuted" is required');


            $params = array (
                'conversationType' => $conversationType,
                'requestId' => $requestId,
                'targetId' => $targetId,
                'isMuted' => $isMuted
            );

            $ret = $this->SendRequest->curl('/conversation/notification/set.json',$params,'urlencoded','im','POST');
            if(empty($ret))
                throw new Exception('bad request');
            return $ret;

        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function getNotification($conversationType,$requestId,$targetId){
        try{
            if (empty($conversationType))
                throw new Exception('Paramer "conversationType" is required');

            if (empty($requestId))
                throw new Exception('Paramer "requestId" is required');

            if (empty($targetId))
                throw new Exception('Paramer "targetId" is required');


            $params = array (
                'conversationType' => $conversationType,
                'requestId' => $requestId,
                'targetId' => $targetId
            );

            $ret = $this->SendRequest->curl('/conversation/notification/get.json',$params,'urlencoded','im','POST');
            if(empty($ret))
                throw new Exception('bad request');
            return $ret;

        }catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    
}
?>