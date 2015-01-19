<?php
if (!function_exists('apache_request_headers')) {
    function apache_request_headers() {
        foreach($_SERVER as $key=>$value) {
            if (substr($key,0,5)=="HTTP_") {
                $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5)))));
                $out[$key]=$value;
            }
            else {
                $out[$key]=$value;
                $key = str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",$key))));
                $out[$key]=$value;
            }
        }
        return $out;
    }
}


// The set of elements in array1, but not in array2
// See http://en.wikipedia.org/wiki/Complement_%28set_theory%29#Relative_complement
function array_relative_complement($array1, $array2)
{
    $union = array_merge($array1, $array2);
    return array_diff($union, $array1);
}

function array_flatten($array)
{
  $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
  return iterator_to_array($it);
}

date_default_timezone_set('Europe/Madrid');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(__DIR__.'/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../../library'),
    get_include_path(),
    realpath(APPLICATION_PATH . '/../..')
)));
# Composer
require 'vendor/autoload.php';

# Zend_Application
require_once 'Zend/Application.php';

if (php_sapi_name() != 'cli' || !empty($_SERVER['REMOTE_ADDR'])) {
    // Create application, bootstrap, and run
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );

    $application->bootstrap()->run();
}


