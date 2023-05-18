<?php
namespace SharePilotV2\Models;
use SharePilotV2\Components\Model;

class Urls extends Model
{

    public function __construct()
    {
        parent::__construct('urls');
    }

    public int $id;
    public string $url;
    public string $title;
    public datetime $datePosted;
    public int $source;
    public int $type;
    public string $thumbnailUrl;

}