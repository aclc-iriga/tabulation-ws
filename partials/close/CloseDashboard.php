<?php

namespace Partials\Close;

trait CloseDashboard
{
    /**
     * Close dashboard client.
     * @param int $resource_id
     * @param $dashboard_id
     * @return void
     */
    public function closeDashboard(int $resource_id, $dashboard_id): void
    {
        foreach ($this->dashboards as $dashboard_key => $resource_ids) {
            for ($i = 0; $i < sizeof($resource_ids); $i++) {
                if ($resource_ids[$i] == $resource_id) {
                    unset($this->dashboards[$dashboard_key][$i]);
                }
                $this->dashboards[$dashboard_key] = array_values($this->dashboards[$dashboard_key]);
            }
        }

        echo ">> [CLOSE] Dashboard [$dashboard_id: $resource_id]\n";
    }
}