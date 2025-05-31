<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Partials\Helpers\HelperKeys;
use Partials\Helpers\HelperJudge;
use Partials\Helpers\HelperDashboard;
use Partials\Open\OpenJudge;
use Partials\Open\OpenDashboard;
use Partials\Message\MessageJudge;
use Partials\Message\MessageDashboard;
use Partials\Close\CloseJudge;
use Partials\Close\CloseDashboard;
use Partials\Senders\SenderJudge;
use Partials\Senders\SenderDashboard;

class TabulationServer implements MessageComponentInterface
{
    /** constants */
    protected const ENTITY_JUDGE     = 'judge';
    protected const ENTITY_DASHBOARD = 'dashboard';

    /** open */
    use OpenJudge;
    use OpenDashboard;

    /** message */
    use MessageJudge;
    use MessageDashboard;

    /** close */
    use CloseJudge;
    use CloseDashboard;

    /** helpers */
    use HelperKeys;
    use HelperJudge;
    use HelperDashboard;

    /** senders */
    use SenderJudge;
    use SenderDashboard;

    /** clients */
    protected SplObjectStorage $judge_clients;
    protected SplObjectStorage $dashboard_clients;

    /** judge clients */
    protected array $judges = []; /* [
        'judge_1' => [resource_id_1, resource_id_2, ...],
        'judge_2' => [resource_id_1, resource_id_2, ...],
        ...the rest of judge clients
    ] */

    /** dashboard clients */
    protected array $dashboards = []; /* [
        'dash_1' => [resource_id_1, resource_id_2, ...],
        'dash_2' => [resource_id_1, resource_id_2, ...],
        ...the rest of dashboard clients
    ] */

    /** judge active event */
    protected array $judge_active_event = []; /* [
        'judge_1' => 'event_1',
        'judge_2' => 'event_1',
        ...
    ] */

    /** judge active team */
    protected array $judge_active_team = []; /* [
        'judge_1' => 'team_1',
        'judge_2' => 'team_1',
        ...
    ] */

    /** judge active column */
    protected array $judge_active_column = []; /* [
        'judge_1' => 0,
        'judge_2' => 1,
        ...
    ] */

    /** judges requesting for help */
    protected array $judges_requesting_help = []; /* [
        'judge_1',
        'judge_2'
        ...
    ] */


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->judge_clients     = new SplObjectStorage;
        $this->dashboard_clients = new SplObjectStorage;
        echo ">> [START]\n";
    }


    /**
     * Open
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn): void
    {
        // resource id
        $resource_id = $conn->resourceId;

        // extract query parameters from the URI
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $params);

        // get entity and id
        $entity = $params['entity'] ?? '';
        $id     = $params['id']     ?? 0;

        // route open
        if ($entity === self::ENTITY_JUDGE) {
            $this->judge_clients->attach($conn);
            $this->openJudge($resource_id, $id);
        }
        else if ($entity === self::ENTITY_DASHBOARD) {
            $this->dashboard_clients->attach($conn);
            $this->openDashboard($resource_id, $id);
        }
    }


    /**
     * Message
     * @param ConnectionInterface $from
     * @param $msg
     * @return void
     */
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        // resource id
        $resource_id = $from->resourceId;

        // parse message
        $arr_msg = [];
        try { $arr_msg = json_decode($msg, true); } catch (Exception $e) {}
        if (sizeof($arr_msg) > 0) {
            // get entity, id, action, and payload
            $entity  = $arr_msg['entity']  ?? '';
            $id      = $arr_msg['id']      ?? 0;
            $action  = $arr_msg['action']  ?? '';
            $payload = $arr_msg['payload'] ?? [];

            // route message
            if ($entity === self::ENTITY_JUDGE) {
                $this->messageJudge($resource_id, $id, $action, $payload);
            }
            else if ($entity === self::ENTITY_DASHBOARD) {
                $this->messageDashboard($resource_id, $id, $action, $payload);
            }
        }
    }


    /**
     * Error
     * @param ConnectionInterface $conn
     * @param Exception $e
     * @return void
     */
    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        // TODO: Implement onError() method.
    }


    /**
     * Close
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onClose(ConnectionInterface $conn): void
    {
        // resource id
        $resource_id = $conn->resourceId;

        // detach client
        $detached = false;
        foreach ($this->judges as $judge_key => $resource_ids) {
            if (in_array($resource_id, $resource_ids)) {
                $this->judge_clients->detach($conn);
                $this->closeJudge($resource_id, $this->getId($judge_key));
                $detached = true;
                break;
            }
        }
        if (!$detached) {
            foreach ($this->dashboards as $dashboard_key => $resource_ids) {
                if (in_array($resource_id, $resource_ids)) {
                    $this->dashboard_clients->detach($conn);
                    $this->closeDashboard($resource_id, $this->getId($dashboard_key));
                    $detached = true;
                    break;
                }
            }
        }
        if (!$detached) {
            try { $this->judge_clients->detach($conn); $this->closeJudge($resource_id, 0); } catch (Exception $e) {}
            try { $this->dashboard_clients->detach($conn); $this->closeDashboard($resource_id, 0); } catch (Exception $e) {}
        }
    }
}


use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new TabulationServer()
        )
    ),
    8080
);

$server->run();