<?php

namespace Partials\Open;

trait OpenDashboard
{
    /**
     * Open dashboard client.
     * @param int $resource_id
     * @param int $dashboard_id
     * @return void
     */
    public function openDashboard(int $resource_id, int $dashboard_id): void
    {
        $dashboard_key = $this->dashboardKey($dashboard_id);

        if (!isset($this->dashboards[$dashboard_key])) {
            $this->dashboards[$dashboard_key] = [];
        }
        if (!in_array($resource_id, $this->dashboards[$dashboard_key])) {
            $this->dashboards[$dashboard_key][] = $resource_id;
        }

        // send data to dashboard
        $this->sendDashboardAll();

        echo ">> [OPEN] Dashboard [$dashboard_id: $resource_id]\n";
    }
}