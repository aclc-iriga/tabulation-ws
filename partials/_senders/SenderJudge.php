<?php

namespace Partials\Senders;

trait SenderJudge
{
    /**
     * Send all data to dashboard clients of given judge id.
     * @param string $competition
     * @param int $judge_id
     * @return void
     */
    public function sendJudgeAll(string $competition, int $judge_id): void
    {
        $this->sendJudgeHelpStatus($competition, $judge_id);
    }


    /**
     * Send help status of judge to its judge clients.
     * @param string $competition
     * @param int $judge_id
     * @return void
     */
    public function sendJudgeHelpStatus(string $competition, int $judge_id): void
    {
        if (isset($this->judge_clients[$competition]) && isset($this->judges[$competition])) {
            $judge_key = $this->judgeKey($judge_id);

            if (isset($this->judges[$competition][$judge_key])) {
                $message = json_encode([
                    'subject' => '__help_status__',
                    'body'    => $this->getJudgeHelpRequest($competition, $judge_id)
                ]);

                foreach ($this->judge_clients[$competition] as $judge_client) {
                    if (in_array($judge_client->resourceId, $this->judges[$competition][$judge_key])) {
                        $judge_client->send($message);
                    }
                }
            }
        }
    }


    /**
     * Send active event of judge to its judge clients.
     * @param string $competition
     * @param int $judge_id
     * @param string $event_slug
     * @return void
     */
    public function sendJudgeActiveEvent(string $competition, int $judge_id, string $event_slug): void
    {
        if (isset($this->judge_clients[$competition]) && isset($this->judges[$competition])) {
            $judge_key = $this->judgeKey($judge_id);

            if (isset($this->judges[$competition][$judge_key])) {
                $message = json_encode([
                    'subject' => '__active_event__',
                    'body'    => $event_slug
                ]);

                foreach ($this->judge_clients[$competition] as $judge_client) {
                    if (in_array($judge_client->resourceId, $this->judges[$competition][$judge_key])) {
                        $judge_client->send($message);
                    }
                }
            }
        }
    }


    /**
     * Send judge event to be refreshed to its judge clients.
     * @param string $competition
     * @param int $judge_id
     * @param string $event_slug
     * @return void
     */
    public function sendJudgeEventRefresh(string $competition, int $judge_id, string $event_slug): void
    {
        if (isset($this->judge_clients[$competition]) && isset($this->judges[$competition])) {
            $judge_key = $this->judgeKey($judge_id);

            if (isset($this->judges[$competition][$judge_key])) {
                $message = json_encode([
                    'subject' => '__refresh_event__',
                    'body'    => $event_slug
                ]);

                foreach ($this->judge_clients[$competition] as $judge_client) {
                    if (in_array($judge_client->resourceId, $this->judges[$competition][$judge_key])) {
                        $judge_client->send($message);
                    }
                }
            }
        }
    }


    /**
     * Send active event to all judge clients.
     * @param string $competition
     * @param string $event_slug
     * @param array $judge_keys
     * @return void
     */
    public function sendJudgeAllActiveEvent(string $competition, string $event_slug, array $judge_keys): void
    {
        if (isset($this->judge_clients[$competition]) && isset($this->judges[$competition])) {
            $message = json_encode([
                'subject' => '__all_active_event__',
                'body'    => $event_slug
            ]);

            $resource_ids = [];
            foreach ($judge_keys as $judge_key) {
                if (isset($this->judges[$competition][$judge_key])) {
                    $resource_ids = array_merge($resource_ids, $this->judges[$competition][$judge_key]);
                }
            }

            foreach ($this->judge_clients[$competition] as $judge_client) {
                if (in_array($judge_client->resourceId, $resource_ids)) {
                    $judge_client->send($message);
                }
            }
        }
    }
}