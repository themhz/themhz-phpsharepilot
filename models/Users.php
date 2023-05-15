<?php
namespace SharePilotV2\Models;
use SharePilotV2\Components\Model;

class Users extends model
{
    public function __construct()
    {
        parent::__construct('users');
    }

    public int $id;
    public string $name;
    public string $lastname;
    public string $email;
    public string $password;
    public int $role;
    public string $mobilephone;
    public string $address;
    public Date $birthdate;






    
}
