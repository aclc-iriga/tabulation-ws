<?php

namespace Partials\Close;

trait CloseJudge
{
    /**
     * Close judge client.
     * @param int $resource_id
     * @param string $competition
     * @param int $judge_id
     * @return void
     */
    public function closeJudge(int $resource_id, string $competition, int $judge_id): void
    {
        if (isset($this->judges[$competition])) {
            foreach ($this->judges[$competition] as $judge_key => $resource_ids) {
                for ($i = 0; $i < sizeof($resource_ids); $i++) {
                    if ($resource_ids[$i] == $resource_id) {
                        unset($this->judges[$competition][$judge_key][$i]);
                    }
                    $this->judges[$competition][$judge_key] = array_values($this->judges[$competition][$judge_key]);
                }
            }
        }

        // reset judge event, candidate, and column
        $this->setJudgeActiveEvent($competition, $judge_id, 0);
        $this->setJudgeActiveTeam($competition, $judge_id, 0);
        $this->setJudgeActiveColumn($competition, $judge_id, 0);

        echo ">> $competition: [CLOSE] Dashboard [$judge_id: $resource_id]\n";

        $this->sendDashboardAll($competition);
    }
}