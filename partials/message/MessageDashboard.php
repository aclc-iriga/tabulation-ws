<?php

namespace Partials\Message;

trait MessageDashboard
{
    /**
     * Handle message from dashboard client.
     * @param int $resource_id
     * @param string $competition
     * @param int $dash_id
     * @param string $action
     * @param array $payload
     * @return void
     */
    public function messageDashboard(int $resource_id, string $competition, int $dash_id, string $action, array $payload): void
    {
        // terminate call for help of judge
        if ($action === '__terminate_help__') {
            $this->setJudgeHelpRequest($competition, ($payload['judge_id'] ?? 0), false);

            $this->sendJudgeHelpStatus($competition, ($payload['judge_id'] ?? 0));
            $this->sendDashboardJudgesRequestingHelp($competition);
        }

        // switch judge active event (portion)
        else if ($action === '__switch_judge_event__') {
            $this->sendJudgeActiveEvent($competition, ($payload['judge_id'] ?? 0), ($payload['event_slug'] ?? ''));
        }

        // refresh judge active event (portion)
        else if ($action === '__refresh_judge_event__') {
            $this->sendJudgeEventRefresh($competition, ($payload['judge_id'] ?? 0), ($payload['event_slug'] ?? ''));
        }
    }
}
