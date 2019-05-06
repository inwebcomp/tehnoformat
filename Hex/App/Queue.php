<?php

namespace Hex\App;

use Database;

class Queue
{
    public static function add($from, $to, $subject, $message)
    {
        return Database::query("INSERT INTO `Queue` SET mail_from = '" . Database::escape($from) . "', mail_to = '" . Database::escape($to) . "', subject = '" . Database::escape($subject) . "', message = '" . Database::escape($message) . "', created = NOW()");
    }

    public static function get($ID)
    {
        return Database::value("SELECT mail_to, message FROM `Queue` WHERE ID = '" . (int) $ID . "'", true);
    }

    public static function touch($ID)
    {
        return Database::query("UPDATE `Queue` SET touched = 1 WHERE ID = '" . (int) $ID . "'");
    }

    public static function executeOne($ID)
    {
        if (\Mail::SendMailSMTPFromQueue($ID))
            Database::query("DELETE FROM `Queue` WHERE ID = '" . (int) $ID . "'");
    }

    public static function execute($count = 10)
    {
        $arr = Database::arrayValuesQ("SELECT ID FROM `Queue` WHERE touched = 0 ORDER BY created ASC LIMIT " . $count);

        foreach ($arr as $task) {
            self::executeOne($task['ID']);
        }
    }
}