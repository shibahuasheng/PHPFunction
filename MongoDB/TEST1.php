<?php
/**
 * Date: 2015/7/30
 * Time: 12:59
 */
//27017
try{
    $mongo = new Mongo(); //create a connection to MongoDB
    $databases = $mongo->listDBs(); //List all databases
    echo '<pre>';
    print_r($databases);
    $mongo->close();
} catch(MongoConnectionException $e) {
//handle connection error
    die($e->getMessage());
}
