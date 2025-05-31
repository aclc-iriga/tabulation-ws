<?php

namespace Partials\Helpers;

trait HelperKeys
{
    /**
     * Generate dashboard key.
     * @param int $id
     * @return string
     */
    public function dashboardKey(int $id): string
    {
        return 'dash_' . $id;
    }


    /**
     * Generate judge key.
     * @param int $id
     * @return string
     */
    public function judgeKey(int $id): string
    {
        return 'judge_' . $id;
    }


    /**
     * Generate team key.
     * @param int $id
     * @return string
     */
    public function teamKey(int $id): string
    {
        return 'team_' . $id;
    }


    /**
     * Generate event key.
     * @param int $id
     * @return string
     */
    public function eventKey(int $id): string
    {
        return 'event_' . $id;
    }


    /**
     * Generate criterion key.
     * @param int $id
     * @return string
     */
    public function criterionKey(int $id): string
    {
        return 'criterion_' . $id;
    }


    /**
     * Get id from a given key.
     * @param string $key
     * @return int
     */
    public function getId(string $key): int
    {
        $id = 0;
        $arr = explode('_', $key);
        if (sizeof($arr) >= 2) {
            $id = intval($arr[1]);
        }

        return $id;
    }
}
