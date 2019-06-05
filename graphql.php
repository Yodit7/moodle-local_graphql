<?php
namespace local_qlapi;

use local_qlapi\data\local_qlapi_datasource;
use local_qlapi\type\local_qlapi_types;
use \GraphQL\Type\Schema;
use \GraphQL\GraphQL;
use \GraphQL\Error\FormattedError;
use \GraphQL\Error\Debug;

require_once(__DIR__ . '/../../config.php');
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/json_header.php';


// Disable default PHP error reporting - we have better one for debug mode (see bellow)
ini_set('display_errors', 0);
$output = [];
$debug = false;
if (!empty($_GET['debug'])) {
    set_error_handler(function($severity, $message, $file, $line) use (&$phpErrors) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    });
    $debug = Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE;
}

try {
    // Parse incoming query and variables
    if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $raw = file_get_contents('php://input') ?: '';
        $data = json_decode($raw, true) ?: [];
    } else {
        $data = $_REQUEST;
    }

    $data += ['query' => null, 'variables' => null];

    if (null === $data['query']) {
        $data['query'] = '{categories {name}}';
    }


    $schema = new Schema([
        'query' => local_qlapi_types::query()
    ]);
    $result = GraphQL::executeQuery(
        $schema,
        $data['query'],
        null,
        null,
        (array)$data['variables']
    );
    $output = $result->toArray($debug);

    $httpStatus = 200;
} catch (\Exception $error) {
    $httpStatus = 500;
}
echo json_encode($output);