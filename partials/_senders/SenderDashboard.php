<?php

namespace Partials\Senders;

trait SenderDashboard
{
    /**
     * Send all data to dashboard clients.
     * @return void
     */
    public function sendDashboardAll(): void
    {
        $this->sendDashboardOnlineJudges();
        $this->sendDashboardJudgeActiveEvents();
        $this->sendDashboardJudgeActiveTeamColumns();
        $this->sendDashboardJudgesRequestingHelp();
    }


    /**
     * Send online judges to dashboard clients.
     * @return void
     */
    public function sendDashboardOnlineJudges(): void
    {
        $message = json_encode([
            'subject' => '__online_judges__',
            'body'    => $this->getOnlineJudges()
        ]);

        foreach ($this->dashboard_clients as $dashboard_client) {
            $dashboard_client->send($message);
        }
    }


    /**
     * Send judge active event to dashboard clients.
     * @return void
     */
    public function sendDashboardJudgeActiveEvents(): void
    {
        $message = json_encode([
            'subject' => '__judges_active_event__',
            'body'    => $this->getActiveEventOfJudges()
        ]);

        foreach ($this->dashboard_clients as $dashboard_client) {
            $dashboard_client->send($message);
        }
    }


    /**
     * Send judge active team and column to dashboard clients.
     * @return void
     */
    public function sendDashboardJudgeActiveTeamColumns(): void
    {
        $message = json_encode([
            'subject' => '__judges_active_team_column__',
            'body'    => $this->getActiveTeamColumnOfJudges()
        ]);

        foreach ($this->dashboard_clients as $dashboard_client) {
            $dashboard_client->send($message);
        }
    }


    /**
     * Send judges requesting for help.
     * @return void
     */
    public function sendDashboardJudgesRequestingHelp(): void
    {
        $message = json_encode([
            'subject' => '__judges_requesting_help__',
            'body'    => $this->judges_requesting_help
        ]);

        foreach ($this->dashboard_clients as $dashboard_client) {
            $dashboard_client->send($message);
        }
    }
}