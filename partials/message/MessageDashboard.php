<?php

namespace Partials\Message;

trait MessageDashboard
{
    /**
     * Handle message from dashboard client.
     * @param int $resource_id
     * @param int $dash_id
     * @param string $action
     * @param array $payload
     * @return void
     */
    public function messageDashboard(int $resource_id, int $dash_id, string $action, array $payload): void
    {
        $judge_key = $this->dashboardKey($dash_id);
    }
}
