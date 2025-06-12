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
}