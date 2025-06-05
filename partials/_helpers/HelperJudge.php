<?php

namespace Partials\Helpers;

trait HelperJudge
{
    /**
     * Get online judges.
     * @param string $competition
     * @return array
     */
    public function getOnlineJudges(string $competition): array
    {
        $online_judges = [];
        if (isset($this->judges[$competition])) {
            foreach ($this->judges[$competition] as $judge_key => $resource_ids) {
                if (!empty($resource_ids)) {
                    $online_judges[] = $judge_key;
                }
            }
        }

        return $online_judges;
    }


    /**
     * Get active event of judges
     * @param string $competition
     * @return array
     */
    public function getActiveEventOfJudges(string $competition): array
    {
        $active_events = [];
        if (isset($this->judges[$competition])) {
            foreach ($this->judges[$competition] as $judge_key => $resource_ids) {
                $judge_id = $this->getId($judge_key);
                $active_events[$judge_key] = $this->getJudgeActiveEvent($competition, $judge_id);
            }
        }

        return $active_events;
    }


    /**
     * Get active team and column of judges.
     * @param string $competition
     * @return array
     */
    public function getActiveTeamColumnOfJudges(string $competition): array
    {
        $active_team_column = [];
        if (isset($this->judges[$competition])) {
            foreach ($this->judges[$competition] as $judge_key => $resource_ids) {
                $judge_id = $this->getId($judge_key);
                $active_team_column[$judge_key] = [
                    'team'   => $this->getJudgeActiveTeam($competition, $judge_id),
                    'column' => $this->getJudgeActiveColumn($competition, $judge_id)
                ];
            }
        }

        return $active_team_column;
    }


    /**
     * Get judge's active event.
     * @param string $competition
     * @param int $judge_id
     * @return string
     */
    public function getJudgeActiveEvent(string $competition, int $judge_id): string
    {
        $judge_key = $this->judgeKey($judge_id);
        return isset($this->judge_active_event[$competition]) ? ($this->judge_active_event[$competition][$judge_key] ?? '') : '';
    }


    /**
     * Set judge's active event.
     * @param string $competition
     * @param int $judge_id
     * @param int $event_id
     * @return void
     */
    public function setJudgeActiveEvent(string $competition, int $judge_id, int $event_id): void
    {
        $judge_key = $this->judgeKey($judge_id);
        $event_key = $this->eventKey($event_id);

        if (!isset($this->judge_active_event[$competition])) {
            $this->judge_active_event[$competition] = [];
        }
        $this->judge_active_event[$competition][$judge_key] = $event_key;

        // reset team and column
        $this->setJudgeActiveTeam($competition, $judge_id, 0);
        $this->setJudgeActiveColumn($competition, $judge_id, 0);
    }


    /**
     * Get judge's active team.
     * @param string $competition
     * @param int $judge_id
     * @return string
     */
    public function getJudgeActiveTeam(string $competition, int $judge_id): string
    {
        $judge_key = $this->judgeKey($judge_id);

        return isset($this->judge_active_team[$competition]) ? ($this->judge_active_team[$competition][$judge_key] ?? '') : '';
    }


    /**
     * Set judge's active team.
     * @param string $competition
     * @param int $judge_id
     * @param int $team_id
     * @return void
     */
    public function setJudgeActiveTeam(string $competition, int $judge_id, int $team_id): void
    {
        $judge_key = $this->judgeKey($judge_id);
        $team_key  = $this->teamKey($team_id);

        if (!isset($this->judge_active_team[$competition])) {
            $this->judge_active_team[$competition] = [];
        }
        $this->judge_active_team[$competition][$judge_key] = $team_key;
    }


    /**
     * Get judge's active column.
     * @param string $competition
     * @param int $judge_id
     * @return int
     */
    public function getJudgeActiveColumn(string $competition, int $judge_id): int
    {
        $judge_key = $this->judgeKey($judge_id);
        return isset($this->judge_active_column[$competition]) ? ($this->judge_active_column[$competition][$judge_key] ?? 0) : 0;
    }


    /**
     * Set judge's active column.
     * @param string $competition
     * @param int $judge_id
     * @param int $column
     * @return void
     */
    public function setJudgeActiveColumn(string $competition, int $judge_id, int $column): void
    {
        $judge_key = $this->judgeKey($judge_id);

        if (!isset($this->judge_active_column[$competition])) {
            $this->judge_active_column[$competition] = [];
        }
        $this->judge_active_column[$competition][$judge_key] = $column;
    }


    /**
     * Get judge's help request status.
     * @param string $competition
     * @param int $judge_id
     * @return bool
     */
    public function getJudgeHelpRequest(string $competition, int $judge_id): bool
    {
        $judge_key = $this->judgeKey($judge_id);

        return isset($this->judges_requesting_help[$competition]) && in_array($judge_key, $this->judges_requesting_help[$competition]);
    }


    /**
     * Set judge's help request status.
     * @param string $competition
     * @param int $judge_id
     * @param bool $help
     * @return void
     */
    public function setJudgeHelpRequest(string $competition, int $judge_id, bool $help = true): void
    {
        $judge_key = $this->judgeKey($judge_id);

        if (!isset($this->judges_requesting_help[$competition])) {
            $this->judges_requesting_help[$competition] = [];
        }

        if ($help) {
            if (!in_array($judge_key, $this->judges_requesting_help[$competition])) {
                $this->judges_requesting_help[$competition][] = $judge_key;
            }
        }
        else {
            if (in_array($judge_key, $this->judges_requesting_help[$competition])) {
                unset($this->judges_requesting_help[$competition][array_search($judge_key, $this->judges_requesting_help[$competition])]);
                $this->judges_requesting_help[$competition] = array_values($this->judges_requesting_help[$competition]);
            }
        }
    }
}