<?php

include_once '../src/Weavora/QueryProfiler/Connection.php';
include_once '../src/Weavora/QueryProfiler/Extension/Extension.php';
include_once '../src/Weavora/QueryProfiler/Extension/ExplainExtension.php';
include_once '../src/Weavora/QueryProfiler/Extension/StatusExtension.php';
include_once '../src/Weavora/QueryProfiler/Extension/ProfileExtension.php';
include_once '../src/Weavora/QueryProfiler/Extension/ExecutionTimeExtension.php';
include_once '../src/Weavora/QueryProfiler/Profile.php';
include_once '../src/Weavora/QueryProfiler/Profiler.php';

$queries = include 'queries.php';

if (empty($_REQUEST['q'])) {
    die('please specify query: q=<query_name>');
}

$query = $_REQUEST['q'];

if (!array_key_exists($query, $queries)) {
    die('unknown query ' . $query);
}

set_time_limit(0);

$connection = new \Weavora\QueryProfiler\Connection('weavora-2','sems_at_ser', 'dev', 'dev');
$connection->connect();

$profiler = new \Weavora\QueryProfiler\Profiler();
$profiler->setConnection($connection);

$profile = $profiler->profile($queries[$query]);

?><!DOCTYPE html>
<html>
<head>
    <title>Profiler</title>

    <style>

        * {
            font-family: Arial;
        }

        .btn {
            font-size: 12px;
            text-decoration: none;
            border: 1px solid #ccc;
            padding: 3px 10px;
            color: #000;
            background: #eee;
        }

        .container {

        }

        .container .connections {
            float: left;
            width: 200px;
            padding: 10px;
            border: 1px dashed #ccc;
        }

        .container .connections ul {
            list-style: none;
            padding: 0;
        }

        .container .connections ul a {
            font-size: 13px;
            text-decoration: none;
            color: #525c66;
        }

        .container .connections h3 {
            text-transform: uppercase;
            font-size: 12px;
            color: #555;
        }

        .container .queries {
            margin-left: 230px;
            padding: 10px;
            border: 1px dashed #ccc;
        }

        .container .queries h1, .container .queries h2 {
            margin: 0 0 10px 0;
            border-bottom: 1px dashed #ccc;
            font-size: 21px;
            padding-bottom: 5px;
            color: #555;
            font-weight: normal;
        }

        .container .queries .query {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
            margin: 0 0 10px 0;
        }

        .container .queries .query label {
            display: block;
            font-size: 13px;
            color: #333;
        }

        .container .queries .query textarea {
            width: 100%;
            height: 150px;
            font-family: Consolas;
            box-sizing: border-box;
            margin: 5px 0;
        }

        .container .queries .profile table {
            border-collapse: collapse;
            border: 1px solid #ccc;
            min-width: 300px;
        }

        .container .queries .profile table thead th {
            background-color: #eee;
            color: #333;
            font-size: 13px;
            border: 1px solid #ccc;
            text-align: left;
            padding: 5px;
        }

        .container .queries .profile table tbody td {
            color: #333;
            font-size: 13px;
            border: 1px solid #ccc;
            text-align: left;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="connections">
            <h3>Connections</h3>

            <a href="#connection/new" class="btn connection-add">Add New Connection</a>


            <ul class="connections-list">
                <li class="selected"><a href="#">localhost/sems_at_ser</a></li>
                <li><a href="#">localhost/sems_v41_ser</a></li>
                <li><a href="#">localhost/sems_v23_ser</a></li>
                <li><a href="#">weavora-2</a></li>
            </ul>

            <h3>Favorite Queries</h3>

            <ul>
                <li><a href="#">keywords</a></li>
                <li><a href="#">keywords metrics</a></li>
                <li><a href="#">adtesting bulk profile</a></li>
            </ul>

            <h3>Recent Queries</h3>

            <ul>
                <li><a href="#"><small>today 20:01</small> keywords</a></li>
                <li><a href="#"><small>today 18:47</small> ads</a></li>
            </ul>
        </div>
        <div class="queries">
            <div class="query">
                <h1>keywords</h1>

                <label for="query">Query:</label>
                <textarea name="query" id="query"><?php echo $profile->query;?></textarea>

                <button id="profile">Profile Query</button>
            </div>
            <div class="profiles">
                <div class="profile">
                    <h2>Execution time: <b><?php echo round($profile->profiles['execution_time']*1000, 2);?> ms</b></h2>
                </div>
                <div class="profile">
                    <h2>Status</h2>

                    <table>
                        <thead>
                            <tr>
                                <th>Variable</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($profile->profiles['status'] as $var => $status):?>
                                <tr>
                                    <td><?php echo $var;?></td>
                                    <td><?php echo $status;?></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="profile">
                    <h2>Profile</h2>

                    <table>
                        <thead>
                            <tr>
                                <th>Step</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($profile->profiles['profile'] as $step => $duration):?>
                                <tr>
                                    <td><?php echo $step;?></td>
                                    <td><?php echo $duration;?></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>

                <div class="profile">
                    <h2>Explain</h2>

                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($profile->profiles['explain'] as $row):?>
                                <tr>
                                    <td><?php echo $row['brief'];?></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>