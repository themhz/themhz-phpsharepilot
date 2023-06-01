<?php
namespace SharePilotV2;
require_once 'CronConfig.php';

class CronConfig
{
    public static $config = [
        'db' => [
            'host' => 'localhost',
            'port' => '3306',
            'basename' => 'sharepilot',
            'user' => 'root',
            'password' => '526996'
        ],
        'consumer_key' => 'cpBXk3DhyCANvdQtjpvxmLB3C',
        'consumer_secret' => 'Ar57VS71PxMY6xwiEMdg5dpV7cyb6rX74LjckK8KVYHXgF4ZdY',
        'access_token' => '78294985-68h5YCG7HNs1L9tDwdq4Vv9dkpzU6vzTht02MjSgb',
        'access_token_secret' => 'lZ1o4ik0OuPmEw9GC1wUPZJXFZ8grw5UyIMoutKN5w4WL',
    ];
}
