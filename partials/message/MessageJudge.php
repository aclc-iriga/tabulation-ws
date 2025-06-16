<?php

namespace Partials\Message;

trait MessageJudge
{
    /**
     * Handle message from judge client.
     * @param int $resource_id
     * @param string $competition
     * @param int $judge_id
     * @param string $action
     * @param array $payload
     * @return void
     */
    public function messageJudge(int $resource_id, string $competition, int $judge_id, string $action, array $payload): void
    {
        // judge active team and column
        if ($action === '__active_team_column__') {
            $this->setJudgeActiveTeam($competition, $judge_id, ($payload['team_id'] ?? 0));
            $this->setJudgeActiveColumn($competition, $judge_id, ($payload['column'] ?? 0));

            $this->sendDashboardJudgeActiveTeamColumns($competition);
        }

        // judge active event
        else if ($action === '__active_event__') {
            $this->setJudgeActiveEvent($competition, $judge_id, ($payload['event_id'] ?? 0));

            $this->sendDashboardJudgeActiveEvents($competition);
            $this->sendDashboardJudgeActiveTeamColumns($competition);
        }

        // judge help request
        else if ($action === '__call_for_help__') {
            $this->setJudgeHelpRequest($competition, $judge_id, ($payload['status'] ?? false));

            $this->sendDashboardJudgesRequestingHelp($competition);
        }

        // judge screensaver
        else if ($action === '__screensaver__') {
            $this->setJudgeScreensaverStatus($competition, $judge_id, ($payload['status'] ?? false));

            $this->sendDashboardJudgesOnScreensaver($competition);
        }

        // judge sign out
        else if ($action === '__sign_out__') {
            $this->closeJudge($resource_id, $competition, $judge_id);
        }
    }
}
