<?php
// $client = new SoapClient('http://localhost/php/soap/math.wsdl');
$client = new SoapClient("http://localhost/PHP/Soap/soap_server.php");
try{
$result = $client->div(8, 2); // will cause a Soap Fault if divide by zero
print "The answer is: $result";
}catch(SoapFault $e){
print "Sorry an error was caught executing your request: {$e->getMessage()}";
}
?>