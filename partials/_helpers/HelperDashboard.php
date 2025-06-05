<?php

namespace Partials\Helpers;

trait HelperDashboard
{
    /**
     * Get online dashboards.
     * @param string $competition
     * @return array
     */
    public function getOnlineDashboards(string $competition): array
    {
        $online_dashboards = [];
        if (isset($this->dashboards[$competition])) {
            foreach ($this->dashboards[$competition] as $dashboard_key => $resource_ids) {
                if (!empty($resource_ids)) {
                    $online_dashboards[] = $dashboard_key;
                }
            }
        }

        return $online_dashboards;
    }
}