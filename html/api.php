<?php

include_once '../src/Weavora/QueryProfiler/Connection.php';
include_once '../src/Weavora/QueryProfiler/Extension/Extension.php';
include_once '../src/Weavora/QueryProfiler/Extension/ExplainExtension.php';
include_once '../src/Weavora/QueryProfiler/Extension/StatusExtension.php';
include_once '../src/Weavora/QueryProfiler/Extension/ProfileExtension.php';
include_once '../src/Weavora/QueryProfiler/Extension/ExecutionTimeExtension.php';
include_once '../src/Weavora/QueryProfiler/Profile.php';
include_once '../src/Weavora/QueryProfiler/Profiler.php';


$connection = new \Weavora\QueryProfiler\Connection('localhost','sems_at_ser', 'root', 'root');
$connection->connect();

$profiler = new \Weavora\QueryProfiler\Profiler();
$profiler->setConnection($connection);

$profile = $profiler->profile("SELECT SQL_NO_CACHE * FROM keyword LIMIT 10");

echo json_encode($profile);
