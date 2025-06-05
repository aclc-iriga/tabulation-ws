<?php

namespace Partials\Message;

trait MessageDashboard
{
    /**
     * Handle message from dashboard client.
     * @param int $resource_id
     * @param string $competition
     * @param int $dash_id
     * @param string $action
     * @param array $payload
     * @return void
     */
    public function messageDashboard(int $resource_id, string $competition, int $dash_id, string $action, array $payload): void
    {
        $dashboard_key = $this->dashboardKey($dash_id);
    }
}
