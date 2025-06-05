<?php

namespace Partials\Open;

trait OpenDashboard
{
    /**
     * Open dashboard client.
     * @param int $resource_id
     * @param string $competition
     * @param int $dashboard_id
     * @return void
     */
    public function openDashboard(int $resource_id, string $competition, int $dashboard_id): void
    {
        $dashboard_key = $this->dashboardKey($dashboard_id);

        if (!isset($this->dashboards[$competition])) {
            $this->dashboards[$competition] = [];
        }
        if (!isset($this->dashboards[$competition][$dashboard_key])) {
            $this->dashboards[$competition][$dashboard_key] = [];
        }
        if (!in_array($resource_id, $this->dashboards[$competition][$dashboard_key])) {
            $this->dashboards[$competition][$dashboard_key][] = $resource_id;
        }

        // send data to dashboard
        $this->sendDashboardAll($competition);

        echo ">> $competition: [OPEN] Dashboard [$dashboard_id: $resource_id]\n";
    }
}