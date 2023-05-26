<?php
namespace SharePilotV2\Models;
use SharePilotV2\Components\Model;

class Channels extends model
{
    public function __construct()
    {
        parent::__construct('channels');
    }

    public int $id;
    public string $name;
}
