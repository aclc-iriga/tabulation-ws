<?php

namespace Partials\Helpers;

trait HelperJudge
{
    /**
     * Get online judges.
     * @return array
     */
    public function getOnlineJudges(): array
    {
        $online_judges = [];
        foreach ($this->judges as $judge_key => $resource_ids) {
            if (!empty($resource_ids)) {
                $online_judges[] = $judge_key;
            }
        }

        return $online_judges;
    }


    /**
     * Get active event of judges
     * @return array
     */
    public function getActiveEventOfJudges(): array
    {
        $active_events = [];
        foreach ($this->judges as $judge_key => $resource_ids) {
            $judge_id = $this->getId($judge_key);
            $active_events[$judge_key] = $this->getJudgeActiveEvent($judge_id);
        }

        return $active_events;
    }


    /**
     * Get active team and column of judges.
     * @return array
     */
    public function getActiveTeamColumnOfJudges(): array
    {
        $active_team_column = [];
        foreach ($this->judges as $judge_key => $resource_ids) {
            $judge_id = $this->getId($judge_key);
            $active_team_column[$judge_key] = [
                'team'   => $this->getJudgeActiveTeam($judge_id),
                'column' => $this->getJudgeActiveColumn($judge_id)
            ];
        }

        return $active_team_column;
    }


    /**
     * Get judge's active event.
     * @param int $judge_id
     * @return string
     */
    public function getJudgeActiveEvent(int $judge_id): string
    {
        $judge_key = $this->judgeKey($judge_id);
        return $this->judge_active_event[$judge_key] ?? '';
    }


    /**
     * Set judge's active event.
     * @param int $judge_id
     * @param int $event_id
     * @return void
     */
    public function setJudgeActiveEvent(int $judge_id, int $event_id): void
    {
        $judge_key = $this->judgeKey($judge_id);
        $event_key = $this->eventKey($event_id);

        $this->judge_active_event[$judge_key] = $event_key;

        // reset team and column
        $this->setJudgeActiveTeam($judge_id, 0);
        $this->setJudgeActiveColumn($judge_id, 0);
    }


    /**
     * Get judge's active team.
     * @param int $judge_id
     * @return string
     */
    public function getJudgeActiveTeam(int $judge_id): string
    {
        $judge_key = $this->judgeKey($judge_id);
        return $this->judge_active_team[$judge_key] ?? '';
    }


    /**
     * Set judge's active team.
     * @param int $judge_id
     * @param int $team_id
     * @return void
     */
    public function setJudgeActiveTeam(int $judge_id, int $team_id): void
    {
        $judge_key = $this->judgeKey($judge_id);
        $team_key  = $this->teamKey($team_id);

        $this->judge_active_team[$judge_key] = $team_key;
    }


    /**
     * Get judge's active column.
     * @param int $judge_id
     * @return int
     */
    public function getJudgeActiveColumn(int $judge_id): int
    {
        $judge_key = $this->judgeKey($judge_id);
        return $this->judge_active_column[$judge_key] ?? 0;
    }


    /**
     * Set judge's active column.
     * @param int $judge_id
     * @param int $column
     * @return void
     */
    public function setJudgeActiveColumn(int $judge_id, int $column): void
    {
        $judge_key = $this->judgeKey($judge_id);

        $this->judge_active_column[$judge_key] = $column;
    }


    /**
     * Get judge's help request status.
     * @param int $judge_id
     * @return bool
     */
    public function getJudgeHelpRequest(int $judge_id): bool
    {
        $judge_key = $this->judgeKey($judge_id);

        return in_array($judge_key, $this->judges_requesting_help);
    }


    /**
     * Set judge's help request status.
     * @param int $judge_id
     * @param bool $help
     * @return void
     */
    public function setJudgeHelpRequest(int $judge_id, bool $help = true): void
    {
        $judge_key = $this->judgeKey($judge_id);

        if ($help) {
            if (!in_array($judge_key, $this->judges_requesting_help)) {
                $this->judges_requesting_help[] = $judge_key;
            }
        }
        else {
            if (in_array($judge_key, $this->judges_requesting_help)) {
                unset($this->judges_requesting_help[array_search($judge_key, $this->judges_requesting_help)]);
                $this->judges_requesting_help = array_values($this->judges_requesting_help);
            }
        }
    }
}