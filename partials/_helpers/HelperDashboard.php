<?php

namespace Partials\Helpers;

trait HelperDashboard
{
    /**
     * Get online dashboards.
     * @return array
     */
    public function getOnlineDashboards(): array
    {
        $online_dashboards = [];
        foreach ($this->dashboards as $dashboard_key => $resource_ids) {
            if (!empty($resource_ids)) {
                $online_dashboards[] = $dashboard_key;
            }
        }

        return $online_dashboards;
    }
}