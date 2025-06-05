<?php

namespace Partials\Senders;

trait SenderDashboard
{
    /**
     * Send all data to dashboard clients.
     * @param string $competition
     * @return void
     */
    public function sendDashboardAll(string $competition): void
    {
        $this->sendDashboardOnlineJudges($competition);
        $this->sendDashboardJudgeActiveEvents($competition);
        $this->sendDashboardJudgeActiveTeamColumns($competition);
        $this->sendDashboardJudgesRequestingHelp($competition);
    }


    /**
     * Send online judges to dashboard clients.
     * @param string $competition
     * @return void
     */
    public function sendDashboardOnlineJudges(string $competition): void
    {
        $message = json_encode([
            'subject' => '__online_judges__',
            'body'    => $this->getOnlineJudges($competition)
        ]);

        if (isset($this->dashboard_clients[$competition])) {
            foreach ($this->dashboard_clients[$competition] as $dashboard_client) {
                $dashboard_client->send($message);
            }
        }
    }


    /**
     * Send judge active event to dashboard clients.
     * @param string $competition
     * @return void
     */
    public function sendDashboardJudgeActiveEvents(string $competition): void
    {
        $message = json_encode([
            'subject' => '__judges_active_event__',
            'body'    => $this->getActiveEventOfJudges($competition)
        ]);

        if (isset($this->dashboard_clients[$competition])) {
            foreach ($this->dashboard_clients[$competition] as $dashboard_client) {
                $dashboard_client->send($message);
            }
        }
    }


    /**
     * Send judge active team and column to dashboard clients.
     * @param string $competition
     * @return void
     */
    public function sendDashboardJudgeActiveTeamColumns(string $competition): void
    {
        $message = json_encode([
            'subject' => '__judges_active_team_column__',
            'body'    => $this->getActiveTeamColumnOfJudges($competition)
        ]);

        if (isset($this->dashboard_clients[$competition])) {
            foreach ($this->dashboard_clients[$competition] as $dashboard_client) {
                $dashboard_client->send($message);
            }
        }
    }


    /**
     * Send judges requesting for help.
     * @param string $competition
     * @return void
     */
    public function sendDashboardJudgesRequestingHelp(string $competition): void
    {
        $message = json_encode([
            'subject' => '__judges_requesting_help__',
            'body'    => $this->judges_requesting_help[$competition] ?? []
        ]);

        if (isset($this->dashboard_clients[$competition])) {
            foreach ($this->dashboard_clients[$competition] as $dashboard_client) {
                $dashboard_client->send($message);
            }
        }
    }
}