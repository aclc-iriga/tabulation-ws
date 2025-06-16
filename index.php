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

    /** competitions */
    protected array $competitions = []; /* [
        'competition_1',
        'competition_2',
        ...the rest of competitions
    ] */

    /** clients */
    protected array $judge_clients = []; /* [
        'competition_1' => [
            SplObjectStorage,
            SplObjectStorage
            ...
        ]
        ...
    ] */
    protected array $dashboard_clients = []; /* [
        'competition_1' => [
            SplObjectStorage,
            SplObjectStorage
            ...
        ]
        ...
    ] */

    /** judge clients */
    protected array $judges = []; /* [
        'competition_1' => [
            'judge_1' => [resource_id_1, resource_id_2, ...],
            'judge_2' => [resource_id_1, resource_id_2, ...],
            ...the rest of judge clients
        ]
        ...
    ] */

    /** dashboard clients */
    protected array $dashboards = []; /* [
        'competition_1' => [
            'dash_1' => [resource_id_1, resource_id_2, ...],
            'dash_2' => [resource_id_1, resource_id_2, ...],
            ...the rest of dashboard clients
        ]
        ...
    ] */

    /** judge active event */
    protected array $judge_active_event = []; /* [
        'competition_1' => [
            'judge_1' => 'event_1',
            'judge_2' => 'event_1',
            ...
        ]
        ...
    ] */

    /** judge active team */
    protected array $judge_active_team = []; /* [
        'competition_1' => [
            'judge_1' => 'team_1',
            'judge_2' => 'team_1',
            ...
        ]
        ...
    ] */

    /** judge active column */
    protected array $judge_active_column = []; /* [
        'competition_1' => [
            'judge_1' => 0,
            'judge_2' => 1,
            ...
        ]
        ...
    ] */

    /** judges requesting for help */
    protected array $judges_requesting_help = []; /* [
        'competition_1' => [
            'judge_1',
            'judge_2'
            ...
        ]
        ...
    ] */

    protected array $judges_on_screensaver = []; /* [
        'competition_1' => [
            'judge_1',
            'judge_2',
            ...
        ]
        ...
    ] */


    /**
     * Constructor
     */
    public function __construct()
    {
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
        $competition = $params['competition'] ?? '';
        $entity      = $params['entity']      ?? '';
        $id          = $params['id']          ?? 0;

        // route open
        if (!empty($competition)) {
            if ($entity === self::ENTITY_JUDGE) {
                if (!isset($this->judge_clients[$competition])) {
                    $this->judge_clients[$competition] = new SplObjectStorage;
                }
                $this->judge_clients[$competition]->attach($conn);
                $this->openJudge($resource_id, $competition, $id);
            }
            else if ($entity === self::ENTITY_DASHBOARD) {
                if (!isset($this->dashboard_clients[$competition])) {
                    $this->dashboard_clients[$competition] = new SplObjectStorage;
                }
                $this->dashboard_clients[$competition]->attach($conn);
                $this->openDashboard($resource_id, $competition, $id);
            }
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
            $competition  = $arr_msg['competition'] ?? '';
            $entity       = $arr_msg['entity']      ?? '';
            $id           = $arr_msg['id']          ?? 0;
            $action       = $arr_msg['action']      ?? '';
            $payload      = $arr_msg['payload']     ?? [];

            // route message
            if (!empty($competition)) {
                if ($entity === self::ENTITY_JUDGE) {
                    $this->messageJudge($resource_id, $competition, $id, $action, $payload);
                }
                else if ($entity === self::ENTITY_DASHBOARD) {
                    $this->messageDashboard($resource_id, $competition, $id, $action, $payload);
                }
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

        // detach client (judge)
        $detached = false;
        foreach ($this->judges as $competition => $judges) {
            foreach ($judges as $judge_key => $resource_ids) {
                if (in_array($resource_id, $resource_ids)) {
                    if (isset($this->judge_clients[$competition])) {
                        $this->judge_clients[$competition]->detach($conn);
                    }
                    $this->closeJudge($resource_id, $competition, $this->getId($judge_key));
                    $detached = true;
                    break;
                }
            }
            if ($detached) {
                break;
            }
        }

        // detach client (dashboard)
        if (!$detached) {
            foreach ($this->dashboards as $competition => $dashboards) {
                foreach ($dashboards as $dashboard_key => $resource_ids) {
                    if (in_array($resource_id, $resource_ids)) {
                        if (isset($this->dashboard_clients[$competition])) {
                            $this->dashboard_clients[$competition]->detach($conn);
                        }
                        $this->closeDashboard($resource_id, $competition, $this->getId($dashboard_key));
                        $detached = true;
                        break;
                    }
                }
                if ($detached) {
                    break;
                }
            }
        }

        // detach client (possible judge or dashboard)
        if (!$detached) {
            // detach possible judge
            try {
                foreach ($this->judge_clients as $competition => $clients) {
                    if ($clients->contains($conn)) {
                        $this->judge_clients[$competition]->detach($conn);
                        $this->closeJudge($resource_id, $competition, 0);
                        break;
                    }
                }
            }
            catch (Exception $e) {}

            // detach possible dashboard
            try {
                foreach ($this->dashboard_clients as $competition => $clients) {
                    if ($clients->contains($conn)) {
                        $this->dashboard_clients[$competition]->detach($conn);
                        $this->closeJudge($resource_id, $competition, 0);
                        break;
                    }
                }
            }
            catch (Exception $e) {}
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