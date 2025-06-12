<?php

namespace Partials\Open;

trait OpenJudge
{
    /**
     * Open judge client.
     * @param int $resource_id
     * @param string $competition
     * @param int $judge_id
     * @return void
     */
    public function openJudge(int $resource_id, string $competition, int $judge_id): void
    {
        $judge_key = $this->judgeKey($judge_id);

        if (!isset($this->judges[$competition])) {
            $this->judges[$competition] = [];
        }
        if (!isset($this->judges[$competition][$judge_key])) {
            $this->judges[$competition][$judge_key] = [];
        }
        if (!in_array($resource_id, $this->judges[$competition][$judge_key])) {
            $this->judges[$competition][$judge_key][] = $resource_id;
        }

        $this->sendDashboardAll($competition);
        $this->sendJudgeAll($competition, $judge_id);

        echo ">> $competition: [OPEN] Judge [$judge_id: $resource_id]\n";
    }
}