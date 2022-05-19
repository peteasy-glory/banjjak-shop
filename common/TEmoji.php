<?php

class TEmoji
{
    function emojiDBToStr($arg){
        $arg = preg_replace_callback('/(\d+);/', function($v){
            if ($v[1] >= 0x10000 && $v[1] <= 0x10ffff) {
                return iconv('UCS-4', 'UTF-8', pack('N',$v[1]));
            }
            return $v[0];
        }, $arg);
        return  stripslashes($arg);
    }

    function emojiStrToDB($arg){
        $tmp =  preg_replace_callback('/[\x{010000}-\x{10ffff}]/u', function($v){
            return ''.current(unpack('N',iconv('UTF-8', 'UCS-4', $v[0]))).';';
        }, $arg);
        return  addslashes($tmp);
    }

}
