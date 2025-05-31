<?php

namespace Partials\Open;

trait OpenJudge
{
    /**
     * Open judge client.
     * @param int $resource_id
     * @param int $judge_id
     * @return void
     */
    public function openJudge(int $resource_id, int $judge_id): void
    {
        $judge_key = $this->judgeKey($judge_id);

        if (!isset($this->judges[$judge_key])) {
            $this->judges[$judge_key] = [];
        }
        if (!in_array($resource_id, $this->judges[$judge_key])) {
            $this->judges[$judge_key][] = $resource_id;
        }

        // send data to dashboard
        $this->sendDashboardAll();

        echo ">> [OPEN] Judge [$judge_id: $resource_id]\n";
    }
}