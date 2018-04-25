<?php

require_once __DIR__ . '/../fns/all_fns.php';
require_once __DIR__ . '/../queries/servers/servers_select.php';

// tell the command line
$time = date('r');
output("Mandatory server reboot CRON starting at $time...");

// load all servers
$pdo = pdo_connect();
$servers = servers_select($pdo);

// shut down all active servers
foreach ($servers as $server) {
    output("Shutting down $server->server_name (ID: #$server->server_id)...");
    try {
        $reply = talk_to_server($server->address, $server->port, $server->salt, 'shut_down`', true);
        output("Reply: $reply");
        output("$server->server_name (ID #$server->server_id) shut down successful.");
    } catch (Exception $e) {
        output($e->getMessage());
    }
}

// tell the command line
output('Mandatory reboot finished.');
