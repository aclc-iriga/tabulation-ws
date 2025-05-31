<?php

namespace Partials\Close;

trait CloseJudge
{
    /**
     * Close judge client.
     * @param int $resource_id
     * @param int $judge_id
     * @return void
     */
    public function closeJudge(int $resource_id, int $judge_id): void
    {
        foreach ($this->judges as $judge_key => $resource_ids) {
            for ($i = 0; $i < sizeof($resource_ids); $i++) {
                if ($resource_ids[$i] == $resource_id) {
                    unset($this->judges[$judge_key][$i]);
                }
                $this->judges[$judge_key] = array_values($this->judges[$judge_key]);
            }
        }

        // reset judge event, candidate, and column
        $this->setJudgeActiveEvent($judge_id, 0);
        $this->setJudgeActiveTeam($judge_id, 0);
        $this->setJudgeActiveColumn($judge_id, 0);

        echo ">> [CLOSE] Dashboard [$judge_id: $resource_id]\n";

        $this->sendDashboardAll();
    }
}