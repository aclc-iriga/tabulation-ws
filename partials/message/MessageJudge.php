<?php

namespace Partials\Message;

trait MessageJudge
{
    /**
     * Handle message from judge client.
     * @param int $resource_id
     * @param int $judge_id
     * @param string $action
     * @param array $payload
     * @return void
     */
    public function messageJudge(int $resource_id, int $judge_id, string $action, array $payload): void
    {
        // judge active team and column
        if ($action === '__active_team_column__') {
            $this->setJudgeActiveTeam($judge_id, $payload['team_id'] ?? 0);
            $this->setJudgeActiveColumn($judge_id, $payload['column'] ?? 0);

            $this->sendDashboardJudgeActiveTeamColumns();
        }

        // judge active event
        else if ($action === '__active_event__') {
            $this->setJudgeActiveEvent($judge_id, $payload['event_id'] ?? 0);

            $this->sendDashboardJudgeActiveEvents();
            $this->sendDashboardJudgeActiveTeamColumns();
        }

        // judge help request
        else if ($action === '__call_for_help__') {
            $this->setJudgeHelpRequest($judge_id, $payload['status'] ?? false);

            $this->sendDashboardJudgesRequestingHelp();
        }

        // judge sign out
        else if ($action === '__sign_out__') {
            $this->closeJudge($resource_id, $judge_id);
        }
    }
}
