<?php
$scriptName = "CloudBackup";
$configFile = "/boot/config/plugins/$scriptName/rclone.conf";


class Rclone {
    public static $rcloneConfig;
    private static $configFile;

    private static function sanitizeProvider( $provider ){
        
        switch( $provider ){
            case 'googledrive':
                $provider = 'drive';
                break;
            
            case 'drive':
                $provider = 'googledrive';
                break;

        }

        return $provider;
    }

    public static function load(){
        global $configFile;
        Rclone::$configFile = $configFile;
        Rclone::$rcloneConfig = @parse_ini_file( $configFile, true) ?: [];

        foreach(Rclone::$rcloneConfig as $key => $value){
            Rclone::$rcloneConfig[$key]['type'] = Rclone::sanitizeProvider($value['type']);
        }
    }

    public static function saveConfig(){
        $config = "";
        foreach(Rclone::$rcloneConfig as $name => $properties){
            $config .= "[" . $name . "]\n";
            foreach($properties as $key => $value){
                if ($key == "type")
                    $value = Rclone::sanitizeProvider($value);

                $config .= $key . " = " . $value . "\n";
            }
            $config .= "\n";
        }
        return file_put_contents(Rclone::$configFile, $config);
    }

    public static function updateProvider($name, $properties){
        Rclone::$rcloneConfig[$name] = array_merge(Rclone::$rcloneConfig[$name], $properties);
        return Rclone::saveConfig();
    }

    public static function addProvider($name, $properties){
        Rclone::$rcloneConfig[$name] = $properties;
        return Rclone::saveConfig();
    }

    public static function deleteProvider($name){
        unset(Rclone::$rcloneConfig[$name]);
        return Rclone::saveConfig();
    }

    public static function getProvider( $name ){
        return Rclone::$rcloneConfig[$name];
    }

    public static function getProviders(){
        return Rclone::$rcloneConfig;
    }

}


/*
Array
(
    [M2] => Array
        (
            [type] => dropbox
            [client_id] => oqe677eocfrspw6
            [client_secret] => tel2ugyyh9d3dj3
            [token] => {access_token:sl.BwGomth_n4l1D_dk7ULxFewQjgep3eXY784IePf52zIeHeJUuJQSNbj4NYuEqSFBulxM6YUjfxYSkEp55rtCjtKK5lRDW7gM4qZ3q4u5GDt-yGHY60vhuVO4AeGQBfPtS2Wd0r4alwT8zL0,token_type:bearer,refresh_token:bnBMTJZr_jcAAAAAAAAAAQrz61ekMONuurFaHf145M7ifKj7QTLRzvTZRl9Blhum,expiry:2024-02-21T22:32:31.921181-05:00}
        )

)
*/
?>