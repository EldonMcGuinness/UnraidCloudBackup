<?

class Cron {

    private static $cronFile = "";
    private static $jobs = [];

    private static $header = "# Generated CloudBackup Schedules";


    public static function load( $cronFile ){

        Cron::$cronFile = $cronFile;
        $cron = file_get_contents( Cron::$cronFile );

        $parsedCron = Cron::parseCron( $cron );
        Cron::$jobs = $parsedCron['jobs'];
        Cron::$header = $parsedCron['header'];
    }

    private static function jobExists( $job ){
        foreach( Cron::$jobs as $index => $existingJob ){
            if( $job["schedule"] == $existingJob["schedule"] &&
                $job["command"] == $existingJob["command"] ){
                return $index;
            }
        }

        return -1;
    }


    private static function parseCron( $cron ){
        $lines = explode("\n", $cron);
        $header = Cron::$header;

        if ( str_starts_with($lines[0], "#") ){
            $header = array_shift($lines);
        }

        $jobs = [];

        foreach( $lines as $line ){
            $job = explode(" ", $line);
            $jobTemp = [
                "schedule" => implode(" ", array_slice($job, 0, 5)),
                "command" => implode(" ", array_slice($job, 5))
            ];

            if( !Cron::isEmpty( $jobTemp ) ){
                $jobs[] = $jobTemp;
            }
        }

        return [ "jobs" => $jobs, "header" => $header ];
    }

    public static function save(){
        $cron = Cron::$header . "\n";

        foreach( Cron::$jobs as $job ){
            $cron .= implode(" ", $job) . "\n";

        }

        if ( file_put_contents( Cron::$cronFile, $cron ) ){
            Cron::updateUnraidCron();
            return true;

        }else{
            return false;
        }
    }

    private static function updateUnraidCron(){
        exec("update_cron");
    }

    private static function isEmpty( $job){
        return $job["schedule"] == "" &&
               $job["command"] == "";
    }

    public static function addJob( $job ){
        $cronIndex = Cron::jobExists( $job );

        if( $cronIndex != -1 ){
            Cron::updateJob( $cronIndex, $job );
        }else{
            Cron::$jobs[] = $job;
        }
        
        return Cron::save();
    }

    public static function deleteJobById( $index ){
        unset( Cron::$jobs[$index] );
        return Cron::save();
    }

    public static function deleteJob( $job ){
        $cronIndex = Cron::jobExists( $job );

        if( $cronIndex != -1 ){
            return Cron::deleteJobById( $cronIndex );
        }

        return false;
    }

    public static function updateJob( $index, $job ){
        Cron::$jobs[$index] = $job;
        return Cron::save();
    }

    public static function getJobs(){
        return Cron::$jobs;
    }

    public static function getJob( $index ){
        return Cron::$jobs[$index];
    }   

    public static function getHeader(){
        return Cron::$header;   
    }
}

//Cron::load( "/boot/config/plugins/CloudBackup/monitor.cron" );
//Cron::addJob( [ "schedule" => "* * * * *", "command" => "ccccc"] );
//echo "Header:" . Cron::getHeader() . "<br/>";
//echo print_r(Cron::getJobs(), true);
?>