<?php

namespace Partials\Close;

trait CloseDashboard
{
    /**
     * Close dashboard client.
     * @param int $resource_id
     * @param string $competition
     * @param $dashboard_id
     * @return void
     */
    public function closeDashboard(int $resource_id, string $competition, $dashboard_id): void
    {
        if (isset($this->dashboards[$competition])) {
            foreach ($this->dashboards[$competition] as $dashboard_key => $resource_ids) {
                for ($i = 0; $i < sizeof($resource_ids); $i++) {
                    if ($resource_ids[$i] == $resource_id) {
                        unset($this->dashboards[$competition][$dashboard_key][$i]);
                    }
                    $this->dashboards[$competition][$dashboard_key] = array_values($this->dashboards[$competition][$dashboard_key]);
                }
            }
        }

        echo ">> $competition: [CLOSE] Dashboard [$dashboard_id: $resource_id]\n";
    }
}