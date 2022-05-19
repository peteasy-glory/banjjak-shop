<?php
class TBridge
{
    public function __construct(){
    }
}


class TFileDownBridge extends TBridge{

    protected $fileName;

    public function __construct($fileName){
        
        $this->fileName = $fileName;
    }

}