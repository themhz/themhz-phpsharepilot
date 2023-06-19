<?php
namespace SharePilotV2\Models;
use SharePilotV2\Components\Model;

class Channel_social_keys extends model
{
    public function __construct()
    {
        parent::__construct('channel_social_keys');
    }

    public int $id;
    public int $channel_id;
    public int $social_id;
    public string $name;
    public string $value;
}
