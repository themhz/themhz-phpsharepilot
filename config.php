<?php
namespace SharePilotV2;
class Config
{
    static $confArray;

    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }
}

Config::write('db.host', 'localhost');
Config::write('db.port', '3306');
Config::write('db.basename', 'sharepilot');
Config::write('db.user', 'root');
Config::write('db.password', '526996');
Config::write('fbkey', 'EAADPBRDEIZBkBAKSwLhUOIM4QzCXjsz2PXSy7CiPCcHJDkqeM8uedJSsYsPS7JB9sE4ltiyMIzlfRUwZCI1bZCNKwoFoPjry1y6Xs3XquQd42UisQzsYvoJMep1kVfp74NSC3DVCf5OtHBikTKNNZCkm40UYZBpbV2Iswt39RlvzrqSlmlVCOHwHgX0A7Ughp5vZBmJvVVagZDZD');
Config::write('youtubeapiKey', 'AIzaSyAkxan2FVao0qFdTS3sZ8WiDCf7Ea6234k');