<?php

namespace MPHB\Crons;

/**
 * @since 3.6.1
 */
class DeleteOldSyncLogsCron extends AbstractCron
{
    public function doCronJob()
    {
        global $wpdb;

        $period = MPHB()->settings()->main()->deleteSyncLogsOlderThan();

        if ($period == 'never') {
            return;
        }

        $date = null;

        switch ($period) {
            case 'day':       $date = new \DateTime('-1 day');    break;
            case 'week':      $date = new \DateTime('-1 week');   break;
            case 'month':     $date = new \DateTime('-1 month');  break;
            case 'quarter':   $date = new \DateTime('-3 months'); break;
            case 'half_year': $date = new \DateTime('-6 months'); break;
        }

        $timestamp = $date->getTimestamp();
        $pattern = "{$timestamp}";

        $queueTable = $wpdb->prefix . \MPHB\iCal\Queue::TABLE_NAME;
        $query = $wpdb->prepare("SELECT queue_id FROM {$queueTable} WHERE queue_name < %s LIMIT 0, 100", $pattern);

        $queueIds = $wpdb->get_col($query);

        if (!empty($queueIds)) {
            \MPHB\iCal\Logger::deleteQueues($queueIds);
            \MPHB\iCal\Stats::deleteQueues($queueIds);
            \MPHB\iCal\Queue::deleteItemsByIds($queueIds);
        }
    }
}
