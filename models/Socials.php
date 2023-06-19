<?php
namespace SharePilotV2\Models;
use SharePilotV2\Components\Model;

class Socials extends model
{
    public function __construct()
    {
        parent::__construct('socials');
    }

    public int $id;
    public string $name;
}
