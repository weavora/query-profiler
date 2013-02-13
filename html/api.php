<?php

include_once '../src/Weavora/QueryProfiler/Connection.php';
include_once '../src/Weavora/QueryProfiler/Extension/Extension.php';
include_once '../src/Weavora/QueryProfiler/Extension/ExplainExtension.php';
include_once '../src/Weavora/QueryProfiler/Extension/StatusExtension.php';
include_once '../src/Weavora/QueryProfiler/Extension/ProfileExtension.php';
include_once '../src/Weavora/QueryProfiler/Extension/ExecutionTimeExtension.php';
include_once '../src/Weavora/QueryProfiler/Profile.php';
include_once '../src/Weavora/QueryProfiler/Profiler.php';

try {

    if (empty($_REQUEST['host']) || empty($_REQUEST['database'])) {
        throw new Exception('No database connection selected');
    }

    if (empty($_REQUEST['query'])) {
        throw new Exception('Empty query');
    }

    $connection = new \Weavora\QueryProfiler\Connection($_REQUEST['host'],$_REQUEST['database'],$_REQUEST['user'],$_REQUEST['password']);
    $connection->connect();

    $profiler = new \Weavora\QueryProfiler\Profiler();
    $profiler->setConnection($connection);

    $profile = $profiler->profile($_REQUEST['query']);

    echo json_encode($profile);

} catch (Exception $e) {
    echo json_encode(array(
        'error' => $e->getCode(),
        'error_message' => $e->getMessage(),
    ));
}