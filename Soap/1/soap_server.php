<?php
/**
 * Date: 2015/8/3
 * Time: 11:00
 */
/**
 * A simple math utility class
 */
class math{
    /**
     * Add two integers together
     *
     * @param integer $a The first integer of the addition
     * @param integer $b The second integer of the addition
     * @return integer The sum of the provided integers
     */
    public function add($a, $b){
        return $a + $b;
    }
    /**
     * Subtract two integers from each other
     *
     * @param integer $a The first integer of the subtraction
     * @param integer $b The second integer of the subtraction
     * @return integer The difference of the provided integers
     */
    public function sub($a, $b){
        return $a - $b;
    }
    /**
     * Div two integers from each other
     *
     * @param integer $a The first integer of the subtraction
     * @param integer $b The second integer of the subtraction
     * @return double The difference of the provided integers
     */
    public function div($a, $b){
        if($b == 0){
            throw new SoapFault(-1, "Cannot divide by zero!");
        }
        return $a / $b;
    }
}
$server = new SoapServer('math.wsdl', array('soap_version'=>SOAP_1_2));
$server->setClass("math");
$server->handle();
?>