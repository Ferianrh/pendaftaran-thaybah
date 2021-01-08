<?php 

function checkValue($var){
    if(isset($var)){
        return null;
    }else{
        return $var;
    }
}

?>