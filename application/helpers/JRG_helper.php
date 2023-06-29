<?php
/*****************************************************************************************************************************************************************************************************************************************************************************************************
---------------------------------------------------------------------------------------------------------------------------------------------------
               Editing the source code below may cause major disturbance on your website. Please DO NOT Modify things beyond this line.
---------------------------------------------------------------------------------------------------------------------------------------------------
                   
                                .______    ___________    ____  ______   .__   __.  _______          
                                |   _  \  |   ____\   \  /   / /  __  \  |  \ |  | |       \         
                                |  |_)  | |  |__   \   \/   / |  |  |  | |   \|  | |  .--.  |           
                                |   _  <  |   __|   \_    _/  |  |  |  | |  . `  | |  |  |  |                 
                                |  |_)  | |  |____    |  |    |  `--'  | |  |\   | |  '--'  |              
                                |______/  |_______|   |__|     \______/  |__| \__| |_______/              

                                        .___________. __    __   __       _______.
                                        |           ||  |  |  | |  |     /       | 
                                        `---|  |----`|  |__|  | |  |    |   (----`
                                            |  |     |   __   | |  |     \   \ 
                                            |  |     |  |  |  | |  | .----)   |
                                            |__|     |__|  |__| |__| |_______/ 
                                            .__.     .__. .__. .__. ._______.  
                                            |  |     |  | |  \ |  | |   ____|
                                            |  |     |  | |   \|  | |  |__
                                            |  |     |  | |  . `  | |   __| 
                                            |  `----.|  | |  |\   | |  |____
                                            |_______||__| |__| \__| |_______|

                                ._______   ______      .__   __.   ______   .___________.   
                                |       \ /  __  \     |  \ |  |  /  __  \  |           |   
                                |  .--.  |  |  |  |    |   \|  | |  |  |  | `---|  |----`        
                                |  |  |  |  |  |  |    |  . `  | |  |  |  |     |  |             
                                |  '--'  |  `--'  |    |  |\   | |  `--'  |     |  |              
                                |_______/ \______/     |__| \__|  \______/      |__|                  
                                                                                                                                     
                                .___  ___.   .____.   ._______  .__. ._______. ____    ____
                                |   \/   |  /  __  \  |       \ |  | |   ____| \   \  /   /
                                |  \  /  | |  |  |  | |  .--.  ||  | |  |__     \   \/   /
                                |  |\/|  | |  |  |  | |  |  |  ||  | |   __|     \_    _/
                                |  |  |  | |  `--'  | |  '--'  ||  | |  |          |  |
                                |__|  |__|  \______/  |_______/ |__| |__|          |__|

---------------------------------------------------------------------------------------------------------------------------------------------------
            Editing the source code below may cause major disturbance on your website. Please DO NOT Modify things beyond this line.
---------------------------------------------------------------------------------------------------------------------------------------------------
                                                    YOU HAVE BEEN WARNED.
                                                        BY: JRGDev
***************************************************************************************************************************************************
**************************************************************************************************************************************************/


if (! function_exists('currentday')) {
    
    function currentday() {
      //$differencetolocaltime=-8;
      $differencetolocaltime=-7;
      $new_U=date("U")-$differencetolocaltime*3600;
      $today = date("l", $new_U);
      return $today;
    }
}

if (! function_exists('currentdate')) {
    
    function currentdate() {
      //$differencetolocaltime=-8;
      $differencetolocaltime=-7;
      $new_U=date("U")-$differencetolocaltime*3600;
      $today = date("M d, Y", $new_U);
      return $today;
    }
}

if (! function_exists('currentdate1')) {
    
    function currentdate1() {
      //$differencetolocaltime=-8;
      $differencetolocaltime=-7;
      $new_U=date("U")-$differencetolocaltime*3600;
      $today = date("Y-m-d", $new_U);
      return $today;
    }
}

if (! function_exists('currenttime')) {
    
    function currenttime() {
      //$differencetolocaltime=-8;
      $differencetolocaltime=-24;
      $new_U=date("U")-$differencetolocaltime*3600;
      $today = date("H:i A", $new_U);
      return $today;
    }
}


if (! function_exists('currentdatetime')) {
    
    function currentdatetime() {
      //$differencetolocaltime=-8;
      $differencetolocaltime=-12;
      $new_U=date("U")-$differencetolocaltime*3600;
      $today = date("Y-m-d h:i:s", $new_U);
      return $today;
    }
}

if (! function_exists('currentdatetime0')) {
    
    function currentdatetime0() {
      //$differencetolocaltime=-8;
      $differencetolocaltime=-6;
      $new_U=date("U")-$differencetolocaltime*3600;
      $today = date("Y-m-d H:i", $new_U);
      return $today;
    }
}

if (! function_exists('currentdatetime1')) {
    
    function currentdatetime1() {
      //$differencetolocaltime=-8;
      $differencetolocaltime=-6;
      $new_U=date("U")-$differencetolocaltime*3600;
      $today = date("H:i:s", $new_U);
      return $today;
    }
}


if ( ! function_exists('redirect_back')) {
    function redirect_back() {
        if(isset($_SERVER['HTTP_REFERER'])) {   header('Location: '.$_SERVER['HTTP_REFERER']);          }
        else                                {   header('Location: http://'.$_SERVER['SERVER_NAME']);    }
        exit;
    }
}

if (! function_exists('formatdatefix')) {
    function formatdatefix($date)
    {
        $newDate = date("mdY", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdatecapsule')) {
    function formatdatecapsule($date)
    {
		$newDate = substr(date("l"),0,3)." ".date("M d, Y", strtotime($date));
		return $newDate;
    }
}

if (! function_exists('formatdate')) {
    function formatdate($date)
    {
        $newDate = date("m-d-y", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate01')) {
    function formatdate01($date)
    {
        $newDate = date("n", strtotime($date));
        return $newDate;
    }
}


if (! function_exists('formatdate0')) {
    function formatdate0($date)
    {
        $newDate = date("n-j", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate1')) {
    function formatdate1($date)
    {
        $newDate = date("M d.Y", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate3')) {
    function formatdate3($date) {
        $newDate = date("n/j/Y h:s", strtotime($date));
        return $newDate;
    }
}
if (! function_exists('formatdate31')) {
    function formatdate31($date) {
        $newDate = date("H:i:s", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate4')) {
    function formatdate4($date) {
        $newDate = date("Y-m-d", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate5')) {
    function formatdate5($date) {
        $newDate = date("m-d-Y", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate6')) {
    function formatdate6($date) {
        $newDate = date("m/d/Y", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate51')) {
    function formatdate51($date) {
        $newDate = date("Y-m-d", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate52')) {
    function formatdate52($date) {
        $newDate = date("M-d-y", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate61')) {
    function formatdate61($date) {
        $newDate = date("j", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate62')) {
    function formatdate62($date) {
        $newDate = date("S", strtotime($date));
        return $newDate;
    }
}
if (! function_exists('formatdate63')) {
    function formatdate63($date) {
        $newDate = date("F, Y", strtotime($date));
        return $newDate;
    }
}
if (! function_exists('formatdate64')) {
    function formatdate64($date) {
        $newDate = date("l", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate65')) {
    function formatdate65($date) {
        $newDate = date("F d, Y", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate2')) {
		function formatdate2($date) {
		$newDate = date("n/j/Y", strtotime($date));
		return $newDate;
    }
}
if (! function_exists('formatdate32')) {
    function formatdate32($date) {
        $newDate = date("g:i:s A", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdatetime')) {
    function formatdatetime($date)
    {
        $newDate = date("Y-m-d h:i", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatdate032')) {
    function formatdate032($date) {
        $newDate = date("Y-m-d h:i", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('DateExpiration')) {
    function DateExpiration($date) {
        $newDate = date("Y-m-d h:i", strtotime ( '30 days' , strtotime($date)));
        return $newDate;
    }
}

// if (! function_exists('DateExpiration')) {
//     function DateExpiration() {
//       $differencetolocaltime=-6;
//       $new_U=date("U")-$differencetolocaltime*3600;
//       $newdate = strtotime ( '30 days' , strtotime ($new_U)) ;
//       //$today = date("Y-m-d h:i:s", $date);
//       $today = date ( 'Y-m-j' , $newdate );
//       return $today;
//     }
// }


if (! function_exists('formattime1')) {
    function formattime1($date) {
        $newDate = date("H:i:s", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formattime2')) {
    function formattime2($date) {
        $newDate = date("h:i:s A", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formattime3')) {
    function formattime3($date) {
        $newDate = date("H:i A", strtotime($date));
        return $newDate;
    }
}

if (! function_exists('formatnumber')) {
    function formatnumber($number) {
        $number = number_format($number, 0, '.', ',');
        return $number;
    }
}

if (! function_exists('formatnumber1')) {   
    function formatnumber1($number) {
        $number = number_format($number, 4, '.',',');
        return $number;
    }
}

if (! function_exists('formatnumber2')) {   
    function formatnumber2($number) {
        $number = number_format($number, 2, '.',',');
        return $number;
    }
}
if (! function_exists('formatnumber3')) {   
    function formatnumber3($number) {
        $number = number_format($number, 0, '', ',');
        return $number;
    }
}

if (! function_exists('cShadow')) {   
    function cShadow($type) {
        if($type == 'Administrator'){
            $output = 'style="color:#2e8b57;"';
        } else {
            $output = 'style="color:black;"';
        }

        return $output;
    }
}


if (! function_exists('getOS')) {   
    function getOS($user_agent = null)
	{

	    // https://stackoverflow.com/questions/18070154/get-operating-system-info-with-php
	    $os_array = [
	        'windows nt 10'                              =>  'Windows 10',
	        'windows nt 6.3'                             =>  'Windows 8.1',
	        'windows nt 6.2'                             =>  'Windows 8',
	        'windows nt 6.1|windows nt 7.0'              =>  'Windows 7',
	        'windows nt 6.0'                             =>  'Windows Vista',
	        'windows nt 5.2'                             =>  'Windows Server 2003/XP x64',
	        'windows nt 5.1'                             =>  'Windows XP',
	        'windows xp'                                 =>  'Windows XP',
	        'windows nt 5.0|windows nt5.1|windows 2000'  =>  'Windows 2000',
	        'windows me'                                 =>  'Windows ME',
	        'windows nt 4.0|winnt4.0'                    =>  'Windows NT',
	        'windows ce'                                 =>  'Windows CE',
	        'windows 98|win98'                           =>  'Windows 98',
	        'windows 95|win95'                           =>  'Windows 95',
	        'win16'                                      =>  'Windows 3.11',
	        'mac os x 10.1[^0-9]'                        =>  'Mac OS X Puma',
	        'macintosh|mac os x'                         =>  'Mac OS X',
	        'mac_powerpc'                                =>  'Mac OS 9',
	        'linux'                                      =>  'Linux',
	        'ubuntu'                                     =>  'Linux - Ubuntu',
	        'iphone'                                     =>  'iPhone',
	        'ipod'                                       =>  'iPod',
	        'ipad'                                       =>  'iPad',
	        'android'                                    =>  'Android',
	        'blackberry'                                 =>  'BlackBerry',
	        'webos'                                      =>  'Mobile',

	        '(media center pc).([0-9]{1,2}\.[0-9]{1,2})'=>'Windows Media Center',
	        '(win)([0-9]{1,2}\.[0-9x]{1,2})'=>'Windows',
	        '(win)([0-9]{2})'=>'Windows',
	        '(windows)([0-9x]{2})'=>'Windows',

	        // Doesn't seem like these are necessary...not totally sure though..
	        //'(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'Windows NT',
	        //'(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})'=>'Windows NT', // fix by bg

	        'Win 9x 4.90'=>'Windows ME',
	        '(windows)([0-9]{1,2}\.[0-9]{1,2})'=>'Windows',
	        'win32'=>'Windows',
	        '(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})'=>'Java',
	        '(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}'=>'Solaris',
	        'dos x86'=>'DOS',
	        'Mac OS X'=>'Mac OS X',
	        'Mac_PowerPC'=>'Macintosh PowerPC',
	        '(mac|Macintosh)'=>'Mac OS',
	        '(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'SunOS',
	        '(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'BeOS',
	        '(risc os)([0-9]{1,2}\.[0-9]{1,2})'=>'RISC OS',
	        'unix'=>'Unix',
	        'os/2'=>'OS/2',
	        'freebsd'=>'FreeBSD',
	        'openbsd'=>'OpenBSD',
	        'netbsd'=>'NetBSD',
	        'irix'=>'IRIX',
	        'plan9'=>'Plan9',
	        'osf'=>'OSF',
	        'aix'=>'AIX',
	        'GNU Hurd'=>'GNU Hurd',
	        '(fedora)'=>'Linux - Fedora',
	        '(kubuntu)'=>'Linux - Kubuntu',
	        '(ubuntu)'=>'Linux - Ubuntu',
	        '(debian)'=>'Linux - Debian',
	        '(CentOS)'=>'Linux - CentOS',
	        '(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - Mandriva',
	        '(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - SUSE',
	        '(Dropline)'=>'Linux - Slackware (Dropline GNOME)',
	        '(ASPLinux)'=>'Linux - ASPLinux',
	        '(Red Hat)'=>'Linux - Red Hat',
	        // Loads of Linux machines will be detected as unix.
	        // Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
	        //'X11'=>'Unix',
	        '(linux)'=>'Linux',
	        '(amigaos)([0-9]{1,2}\.[0-9]{1,2})'=>'AmigaOS',
	        'amiga-aweb'=>'AmigaOS',
	        'amiga'=>'Amiga',
	        'AvantGo'=>'PalmOS',
	        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1}-([0-9]{1,2}) i([0-9]{1})86){1}'=>'Linux',
	        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1} i([0-9]{1}86)){1}'=>'Linux',
	        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1})'=>'Linux',
	        '[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})'=>'Linux',
	        '(webtv)/([0-9]{1,2}\.[0-9]{1,2})'=>'WebTV',
	        'Dreamcast'=>'Dreamcast OS',
	        'GetRight'=>'Windows',
	        'go!zilla'=>'Windows',
	        'gozilla'=>'Windows',
	        'gulliver'=>'Windows',
	        'ia archiver'=>'Windows',
	        'NetPositive'=>'Windows',
	        'mass downloader'=>'Windows',
	        'microsoft'=>'Windows',
	        'offline explorer'=>'Windows',
	        'teleport'=>'Windows',
	        'web downloader'=>'Windows',
	        'webcapture'=>'Windows',
	        'webcollage'=>'Windows',
	        'webcopier'=>'Windows',
	        'webstripper'=>'Windows',
	        'webzip'=>'Windows',
	        'wget'=>'Windows',
	        'Java'=>'Unknown',
	        'flashget'=>'Windows',

	        // delete next line if the script show not the right OS
	        //'(PHP)/([0-9]{1,2}.[0-9]{1,2})'=>'PHP',
	        'MS FrontPage'=>'Windows',
	        '(msproxy)/([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
	        '(msie)([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
	        'libwww-perl'=>'Unix',
	        'UP.Browser'=>'Windows CE',
	        'NetAnts'=>'Windows',
	    ];

	    // https://github.com/ahmad-sa3d/php-useragent/blob/master/core/user_agent.php
	    $arch_regex = '/\b(x86_64|x86-64|Win64|WOW64|x64|ia64|amd64|ppc64|sparc64|IRIX64)\b/ix';
	    $arch = preg_match($arch_regex, $user_agent) ? '64' : '32';

	    foreach ($os_array as $regex => $value) {
	        if (preg_match('{\b('.$regex.')\b}i', $user_agent)) {
	            return $value.' x'.$arch;
	        }
	    }

	    return 'Unknown';
	}
}

if(! function_exists('superadmin')){
    function superadmin(){
        $superadmin = array('jrgadmin','jrgd3v');
        return $superadmin;
    }
}


if (! function_exists('time_ago')) {   
    function time_ago($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}
}

if (! function_exists('CJob')) {
    function CJob(){
        $CJob = array(
            '<img src="'.base_url().'Images/Icon/brawler_emblem_icon.gif" style="width:16px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Brawler">',
            '<img src="'.base_url().'Images/Icon/sword_emblem_icon.gif" style="width:16px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Swordsman">',
            '<img src="'.base_url().'Images/Icon/archer_emblem_icon.gif" style="width:16px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Archer">',
            '<img src="'.base_url().'Images/Icon/shamen_emblem_icon.gif" style="width:16px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Shaman">',
            '<img src="'.base_url().'Images/Icon/extreme_emblem_icon.gif" style="width:16px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Extreme">',
            '<img src="'.base_url().'Images/Icon/scientist_emblem_icon.gif" style="width:16px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Scientist">',
            '<img src="'.base_url().'Images/Icon/assassin_emblem_icon.gif" style="width:16px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Assasin">',
            '<img src="'.base_url().'Images/Icon/magician_emblem_icon.gif" style="width:16px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Magician">',
            '<img src="'.base_url().'Images/Icon/shaper_emblem_icon.gif" style="width:16px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Shaper">'
        );
        return $CJob;   
    }
}

//eto yung nasa combobox
if (! function_exists('getselectschool')){
    function getselectschool($getselectschool){
            if($getselectschool == 0) { $getselectschool = ''.base_url().'assets/icons/00.gif'; }
            elseif($getselectschool == 1) { $getselectschool = ''.base_url().'assets/icons/01.gif'; }
            elseif($getselectschool == 2) { $getselectschool =''.base_url().'assets/icons/02.gif'; }
        
        return $getselectschool;
    }
}

if(! function_exists('Schools')){
    function Schools(){
        $Schools = array('0','1','2');
        return $Schools;
    }
}

if (! function_exists('school')){
    function school($school){
            if($school == 0) { $schimg = '<img src="'.base_url().'Images/Icon/00.gif" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sacred Gate">&nbsp; <b>SG</b>'; }
            elseif($school == 1) { $schimg = '<img src="'.base_url().'Images/Icon/01.gif" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mystic Peak">&nbsp; <b>MP</b>'; }
            elseif($school == 2) { $schimg ='<img src="'.base_url().'Images/Icon/02.gif" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pheonix">&nbsp; <b>PH</b>'; }
        
        return $schimg;
    }
}

if (! function_exists('school1')){
    function school1($school){
            if($school == 0) { $schimg = '<img src="'.base_url().'Images/Icon/00.gif" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sacred Gate">&nbsp; <b>Sacred Gate</b>'; }
            elseif($school == 1) { $schimg = '<img src="'.base_url().'Images/Icon/01.gif" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mystic Peak">&nbsp; <b">Mystic Peak</b>'; }
            elseif($school == 2) { $schimg ='<img src="'.base_url().'Images/Icon/02.gif" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pheonix">&nbsp; <b>Pheonix</b>'; }
        
        return $schimg;
    }
}

if (! function_exists('ClassIMG')){
    function ClassIMG($ClassIMG){
    if($ClassIMG == 1) { $ClassIMG = '<img src="'.base_url().'Images/icon/brawler_emblem_icon.gif" style="width:16px;" title="Brawler[M]">';} //BRA-B
    elseif($ClassIMG == 64) { $ClassIMG = '<img src="'.base_url().'Images/icon/brawler_emblem_icon.gif" style="width:16px;" title="Brawler[F]">';} //BRA-F
    elseif($ClassIMG == 2) { $ClassIMG = '<img src="'.base_url().'Images/icon/sword_emblem_icon.gif" style="width:16px;" title="Swordsman[M]">';} //SWO-B
    elseif($ClassIMG == 128) { $ClassIMG = '<img src="'.base_url().'Images/icon/sword_emblem_icon.gif" style="width:16px;"  title="Swordsman[F]">';} //SWO-F
    elseif($ClassIMG == 256) { $ClassIMG = '<img src="'.base_url().'Images/icon/archer_emblem_icon.gif" style="width:16px;"  title="Archer[M]">';} //ARC-B
    elseif($ClassIMG == 4) { $ClassIMG = '<img src="'.base_url().'Images/icon/archer_emblem_icon.gif" style="width:16px;" title="Archer[F]">';} //ARC-F
    elseif($ClassIMG == 512) { $ClassIMG = '<img src="'.base_url().'Images/icon/shamen_emblem_icon.gif" style="width:16px;" title="Shaman[M]">';} //SHA-B
    elseif($ClassIMG == 8) { $ClassIMG = '<img src="'.base_url().'Images/icon/shamen_emblem_icon.gif" style="width:16px;" title="Shaman[F]">';} //SHA-G
    elseif($ClassIMG == 16) { $ClassIMG = '<img src="'.base_url().'Images/icon/extreme_emblem_icon.gif" style="width:16px;" title="Extreme[M]">';} //XTR-B
    elseif($ClassIMG == 32) { $ClassIMG = '<img src="'.base_url().'Images/icon/extreme_emblem_icon.gif" style="width:16px;" title="Extreme[F]">';} //XTR-G
    elseif($ClassIMG == 1024) { $ClassIMG = '<img src="'.base_url().'Images/icon/scientist_emblem_icon.gif" style="width:16px;" title="Gunner[M]">';} //GUN-B
    elseif($ClassIMG == 2048) { $ClassIMG = '<img src="'.base_url().'Images/icon/scientist_emblem_icon.gif" style="width:16px;" title="Gunner[F]">';} //GUN-G
    elseif($ClassIMG == 4096) { $ClassIMG = '<img src="'.base_url().'Images/icon/assassin_emblem_icon.gif" style="width:16px;" title="Assasin[M]">';} //ASS-B
    elseif($ClassIMG == 8192) { $ClassIMG = '<img src="'.base_url().'Images/icon/assassin_emblem_icon.gif" style="width:16px;" title="Assasin[F]">';} //ASS-G
    elseif($ClassIMG == 16384) { $ClassIMG = '<img src="'.base_url().'Images/icon/magician_emblem_icon.gif" style="width:16px;" title="Magician[M]">';} //MAG-B
    elseif($ClassIMG == 32768) { $ClassIMG = '<img src="'.base_url().'Images/icon/magician_emblem_icon.gif" style="width:16px;" title="Magician[F]">';} //MAG-G
    elseif($ClassIMG == 262144) { $ClassIMG = '<img src="'.base_url().'Images/icon/shaper_emblem_icon.gif" style="width:16px;" title="Shaper[M]">';} //SHAP-B
    elseif($ClassIMG == 524288) { $ClassIMG = '<img src="'.base_url().'Images/icon/shaper_emblem_icon.gif" style="width:16px;" title="Shaper[F">';} //SHAP-G
    return $ClassIMG;   
    }

}

if (! function_exists('ChaClass')){
    function ChaClass($ChaClass){
    if($ChaClass == 1) { $CharClass = '<img src="'.base_url().'Images/icon/brawler_emblem_icon.gif" style="width:16px;"> <b>Male</b>';} //BRA-B
    elseif($ChaClass == 64) { $CharClass = '<img src="'.base_url().'Images/icon/brawler_emblem_icon.gif" style="width:16px;"> <b>Female</b>';} //BRA-F
    elseif($ChaClass == 2) { $CharClass = '<img src="'.base_url().'Images/icon/sword_emblem_icon.gif" style="width:16px;"> <b>Male</b>';} //SWO-B
    elseif($ChaClass == 128) { $CharClass = '<img src="'.base_url().'Images/icon/sword_emblem_icon.gif" style="width:16px;"> <b>Female</b>';} //SWO-F
    elseif($ChaClass == 256) { $CharClass = '<img src="'.base_url().'Images/icon/archer_emblem_icon.gif" style="width:16px;"> <b>Male</b>';} //ARC-B
    elseif($ChaClass == 4) { $CharClass = '<img src="'.base_url().'Images/icon/archer_emblem_icon.gif" style="width:16px;"> <b>Female</b>';} //ARC-F
    elseif($ChaClass == 512) { $CharClass = '<img src="'.base_url().'Images/icon/shamen_emblem_icon.gif" style="width:16px;"> <b>Male</b>';} //SHA-B
    elseif($ChaClass == 8) { $CharClass = '<img src="'.base_url().'Images/icon/shamen_emblem_icon.gif" style="width:16px;"> <b>Female</b>';} //SHA-G
    elseif($ChaClass == 16) { $CharClass = '<img src="'.base_url().'Images/icon/extreme_emblem_icon.gif" style="width:16px;"> <b>Male</b>';} //XTR-B
    elseif($ChaClass == 32) { $CharClass = '<img src="'.base_url().'Images/icon/extreme_emblem_icon.gif" style="width:16px;"> <b>Female</b>';} //XTR-G
    elseif($ChaClass == 1024) { $CharClass = '<img src="'.base_url().'Images/icon/scientist_emblem_icon.gif" style="width:16px;"> <b>male</b>';} //SCI-B
    elseif($ChaClass == 2048) { $CharClass = '<img src="'.base_url().'Images/icon/scientist_emblem_icon.gif" style="width:16px;"> <b>Female</b>';} //SCI-G
    elseif($ChaClass == 4096) { $CharClass = '<img src="'.base_url().'Images/icon/assassin_emblem_icon.gif" style="width:16px;"> <b>Male</b>';} //ASS-B
    elseif($ChaClass == 8192) { $CharClass = '<img src="'.base_url().'Images/icon/assassin_emblem_icon.gif" style="width:16px;"> <b>Female</b>';} //ASS-G
    elseif($ChaClass == 16384) { $CharClass = '<img src="'.base_url().'Images/icon/magician_emblem_icon.gif" style="width:16px;"> <b>Male</b>';} //MAG-B
    elseif($ChaClass == 32768) { $CharClass = '<img src="'.base_url().'Images/icon/magician_emblem_icon.gif" style="width:16px;"> <b>Female</b>';} //MAG-G
    elseif($ChaClass == 262144) { $CharClass = '<img src="'.base_url().'Images/icon/shaper_emblem_icon.gif" style="width:16px;"> <b>Male</b>';} //SHAP-B
    elseif($ChaClass == 524288) { $CharClass = '<img src="'.base_url().'Images/icon/shaper_emblem_icon.gif" style="width:16px;"> <b>Female</b>';} //SHAP-G
    
    return $CharClass;  
    }

}


//eto yung club leaders
if (! function_exists('Club')) {
    function Club(){
        $Club = array(
            '<img src="'.base_url().'Images/Icon/000.gif" alt="SG">',
            '<img src="'.base_url().'Images/Icon/001.gif" alt="MP">',
            '<img src="'.base_url().'Images/Icon/002.gif" alt="PH">',
            '<img src="'.base_url().'Images/Icon/003.gif" alt="TH">',
        );
        return $Club;   
    }
}

function datebetween($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}    

/*
if (! function_exists('build_calendar')) {
    function build_calendar($month,$year,$dateArray) {
        // Create array containing abbreviations of days of week.
     $daysOfWeek = array('S','M','T','W','T','F','S');
     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);
     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);
     // What is the name of the month in question?
     $monthName = $dateComponents['month'];
     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];
     // Create the table tag opener and day headers
     $calendar = "<section class='calendar'>
                    <table id='calendar'>";
     $calendar .= '<caption class="display-block">
                                <figure>
                                    <div class="yearmonth">
                                        <div class="left"><img src="'.base_url().'Images/Calendar/calendar_title.gif" alt="이벤트달력"></div>
                                        <div class="right">
                                            <img src="'.base_url().'Images/Calendar/month_prev.gif" id="prevcalendar" class="pointer" alt="다음달">
                                            &nbsp;<span id="yearmonth">'. date('Y').'.'.date('n').'</span>&nbsp;
                                            <img src="'.base_url().'Images/Calendar/month_next.gif" id="nextcalendar" class="pointer" alt="이전달">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </figure>
                            </caption>';
     $calendar .= '<thead><tr class="blankline2">
                                <td class="blankcell" colspan="13"></td>
                            </tr><tr>';
     // Create the calendar headers
     foreach($daysOfWeek as $day) {
          $calendar .= '<th class="blank"></th><th>'.$day.'</th>';
     } 
     // Create the rest of the calendar
     // Initiate the day counter, starting with the 1st.
     $currentDay = 1;
     $calendar .= '</tr></thead><tbody><tr><tr class="blankline">
                                <td class="blankcell" colspan="13"></td>
                                </tr>';
     // The variable $dayOfWeek is used to
     // ensure that the calendar
     // display consists of exactly 7 columns.
     if ($dayOfWeek > 0) { 
          $calendar .= '<td class="blank"></td><td class="">&nbsp;</td>'; 
     }
     
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
     while ($currentDay <= $numberDays) {
          // Seventh column (Saturday) reached. Start a new row.
          if ($dayOfWeek == 7) {
               $dayOfWeek = 0;
               $calendar .= '<tr class="blankline">
                                <td class="blankcell" colspan="13"></td>
                                </tr></tr><tr>';
          }
          
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          
          $date = "$year-$month-$currentDayRel";
          if(date('d') == $currentDay){
               $calendar .= '<td class="blank"></td><td class="event-now" isevent="True" style="cursor: pointer;">'.$currentDay.'</td>';
          } else {
               $calendar .= '<td class="blank"></td><td class="event" isevent="True" style="cursor: pointer;">'.$currentDay.'</td>';
          }
          // Increment counters

          $currentDay++;
          $dayOfWeek++;
     }
     
     
     // Complete the row of the last week in month, if necessary
     if ($dayOfWeek != 7) { 
     
          $remainingDays = 7 - $dayOfWeek;
          $calendar .= '<td class="blank"><td class="">&nbsp;</td>'; 
     }
     
     $calendar .= '</tr><tr class="blankline">
                                <td class="blankcell" colspan="13"></td>
                                </tr></tbody>';
     $calendar .= '<tfoot>
                            <tr class="blankline2">
                                <td class="blankcell" colspan="13"></td>
                            </tr>
                            </tfoot>';
     $calendar .= "</table></section>";
     return $calendar;
    }
}
*/

if(! function_exists('GenerateBadge')){
    function GenerateBadge( $binary )
                        {
                            if($binary!="00"){
                            $line = 0;
                            echo "<table border='0' style='style:none;' cellpadding='0' cellspacing='0' width='16' height='11'>";
                            for( $m = 0 ; $m < 11 ; $m ++ )
                            {
                                echo "<tr class='badge'>";
                                for( $n = 0 ; $n < 16 ; $n ++ )
                                {
                                    $offset = $line*8*16 + $n*8;
                                    $color = substr( $binary , $offset + 4 , 2 ) . substr( $binary , $offset + 2 , 2 ) . substr( $binary , $offset , 2 );
                                    echo "<td class='badge' style='width:1px;height:1px;background-color:#$color'></td>";
                                }
                                echo "</tr>";
                                $line++;
                            }
                            echo "</table>";
                            }
                        else echo "";
                        }
}


if(! function_exists('GenerateBadge1')){
    function GenerateBadge1( $binary )
                        {
                            if($binary!="00"){
                            $line = 0;
                            echo "<div style='position: relative;' class='rTable' border='0' cellpadding='0' cellspacing='0' width='16' height='11'>";
                            for( $m = 0 ; $m < 11 ; $m ++ )
                            {
                                echo '<div class="rTableRow">';
                                for( $n = 0 ; $n < 16 ; $n ++ )
                                {
                                    $offset = $line*8*16 + $n*8;
                                    $color = substr( $binary , $offset + 4 , 2 ) . substr( $binary , $offset + 2 , 2 ) . substr( $binary , $offset , 2 );
                                    echo "<div class='rTableCell' style='width:1px;height:1px;background-color:#$color'></div>";
                                }
                                echo "</div>";
                                $line++;
                            }
                            echo "</div>";
                            }
                        else echo "";
                        }
}

if(! function_exists('GenerateBadge2')){
    function GenerateBadge2( $binary )
    {
        if($binary!="00"){
            $line = 0;
            echo "<div class='rTable' border='0' cellpadding='0' cellspacing='0' width='16' height='11'>";
                for( $m = 0 ; $m < 11 ; $m ++ ){
                    echo '<div class="rTableRow">';
                        for( $n = 0 ; $n < 16 ; $n ++ ){
                            $offset = $line*8*16 + $n*8;
                            $color = substr( $binary , $offset + 4 , 2 ) . substr( $binary , $offset + 2 , 2 ) . substr( $binary , $offset , 2 );
                            echo "<div class='rTableCell' style='width:1px;height:1px;background-color:#$color'></div>";
                        }
                    echo "</div>";
                    $line++;
                }
                echo "</div>";
        }
        else echo "";
    }
}

// eto yung nasa accountinfo
if (! function_exists('Fullschool')){
    function Fullschool($Fullschool){
            if($Fullschool == 0) { $schimg = '<img src="'.base_url().'assets/icons/00.gif">&nbsp; <label><small>Sacred Gate</small></label>'; }
            elseif($Fullschool == 1) { $schimg = '<img src="'.base_url().'assets/icons/01.gif">&nbsp; <label><small>Mystic Peak</small></label>'; }
            elseif($Fullschool == 2) { $schimg ='<img src="'.base_url().'assets/icons/02.gif">&nbsp; <label><small>Phoenix</small></label>'; }
        
        return $schimg;
    }
}


if(! function_exists('generatecaptcha')){
    function generatecaptcha($length = 5){
        return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}

if (! function_exists('usertype')) {
    function usertype($UserType) {
        if ($UserType == 1) { $TypeName = '<span class="badge" style="color:white; background-color:#3c8dbc;">Normal</span>'; }
        elseif ($UserType == 28) { $TypeName = '<span class="badge" style="color:white; background-color:skyblue;">GMC</span>'; }
        elseif ($UserType == 30) { $TypeName = '<span class="badge" style="color:white; background-color:#D81B60;">Staff</span>'; }
        elseif ($UserType == 32) { $TypeName = '<span class="badge" style="color:white; background-color:#001F3F;">Game Master</span>'; }
        else{
            $TypeName = '<span class="badge" style="color:white; background-color:#a9a9a9;">Others</span>';
        }
        
        return $TypeName;   
    }
}

if (! function_exists('CharSchool')){
    function CharSchool($CharSchool){
            if($CharSchool == 0) { $CharSchool = '<img src="'.base_url().'assets/icons/A0.png" style="width:50px;float:right;"  title="Sacred Gate">'; }
            elseif($CharSchool == 1) { $CharSchool = '<img src="'.base_url().'assets/icons/A1.png" style="width:50px;float:right;" Title="Mystic Peak">'; }
            elseif($CharSchool == 2) { $CharSchool ='<img src="'.base_url().'assets/icons/A2.png" style="width:50px;float:right;" Title="Phoenix">'; }
        
        return $CharSchool;
    }
}

if(! function_exists('getRemoteFilesize')){
    function getRemoteFilesize($url, $formatSize = true, $useHead = true)
        {
            if (false !== $useHead) {
                stream_context_set_default(array('http' => array('method' => 'HEAD')));
            }
            $head = array_change_key_case(get_headers($url, 1));
            // content-length of download (in bytes), read from Content-Length: field
            $clen = isset($head['content-length']) ? $head['content-length'] : 0;

            // cannot retrieve file size, return "-1"
            if (!$clen) {
                return -1;
            }

            if (!$formatSize) {
                return $clen; // return size in bytes
            }

            $size = $clen;
            switch ($clen) {
                case $clen < 1024:
                    $size = $clen .' B'; break;
                case $clen < 1048576:
                    $size = round($clen / 1024, 2) .' KB'; break;
                case $clen < 1073741824:
                    $size = round($clen / 1048576, 2) . ' MB'; break;
                case $clen < 1099511627776:
                    $size = round($clen / 1073741824, 2) . ' GB'; break;
            }

            return $size; // return formatted size
        }
}



if(! function_exists('transaction')){
	function transaction($length = 4){
	    return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)) )),1,$length);
	}
}


if(! function_exists('generatecode')){
	function generatecode($length = 11){
	    return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}

if(! function_exists('generatepincode')){
	function generatepincode($length = 6){
	    return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}

if(! function_exists('generateverification')){
	function generateverification($length = 7){
	    return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}

if(! function_exists('generatereferralcode')){
	function generatereferralcode($length = 7){
	    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyz', ceil($length/strlen($x)) )),1,$length);
	}
}


if(! function_exists('doCheckCaptchaResult')){
	function doCheckCaptchaResult($captcha,$ip,$secret){
		$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$ip);
		return $response;
	}
}




if(! function_exists('GenTicketNum')){
	function GenTicketNum() {
	 
	    $ID = substr(str_shuffle(str_repeat($x='123456789', ceil(1/strlen($x)) )),1,1);
	    $sub = substr(str_shuffle(str_repeat($x='0123456789', ceil(9/strlen($x)) )),1,9);
        $key_string = $ID.'-'.$sub;
	    return $key_string;
	 
	}
}


if(! function_exists('license')){
	function license() {
	 
	    $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $segment_chars = 4;
	    $num_segments = 4;
	    $key_string = '';
	 
	    for ($i = 0; $i < $num_segments; $i++) {
	 
	        $segment = '';
	 
	        for ($j = 0; $j < $segment_chars; $j++) {
	                $segment .= $tokens[rand(0, 35)];
	        }
	 
	        $key_string .= $segment;
	 
	        if ($i < ($num_segments - 1)) {
	                $key_string .= '-';
	        }
	 
	    }
	 
	    return $key_string;
	 
	}
}

if( !function_exists('TicketStatus')){
    function TicketStatus($val) {
        /*
        0 = Pending // <i class="fad fa-history"></i>
        1 = On-Going // <i class="fad fa-sync-alt"></i>
        2 = Complete // <i class="fad fa-file-check"></i>
        
        */
        ?>
        <style>
            .badge-pending {
                color: #212529;
                background-color: #ffd700;
            }

            .badge-dodger {
                color: #212529;
                background-color: #1e90ff;
            }

            .badge-lime {
                color: #212529;
                background-color: #32cd32;
            }
            .badge {
                display: inline-block;
                padding: .25em .4em;
                font-size: 90%;
                font-weight: 700;
                line-height: 1;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: .25rem;
            }
        </style>
        <?php

        if($val == 0){
            $Output = '<span class="gold" style="font-size:16px;"><i class="fad fa-history" title="Pending"></i> </span>';
        }

        if($val == 1){
            $Output = '<span class="dodgerblue" style="font-size:16px;"><i class="fad fa-sync-alt" title="On-Going"></i></span>';
        }

        if($val == 2){
            $Output = '<span class="mediumseagreen" style="font-size:16px;"><i class="fad fa-check-circle" title="Complete"></i> </span>';
        }

        return $Output;
    }
}

// config_set('inventory.ini','INVENTORY','ChaInven',bin2hex($ChaInfo[0]['ChaInven']));
if( !function_exists('config_set')){
    function config_set($config_file, $section, $key, $value) {
        $config_data = @parse_ini_file($config_file, true);
        $config_data[$section][$key] = $value;
        $new_content = '';
        foreach ($config_data as $section => $section_content) {
            $section_content = array_map(function($value, $key) {
                return "$key=$value";
            }, array_values($section_content), array_keys($section_content));
            $section_content = implode("\n", $section_content);
            $new_content .= "[$section]\n$section_content\n";
        }
        return file_put_contents($config_file, $new_content);
    }
}


if(! function_exists('VoidEncrypter2')){
	function VoidEncrypter2($val){
		$CI = &get_instance();
		return $CI->my_encrypt->encode($val,'VoidMainDevelopment');
	}
}

if(! function_exists('VoidDecrypter2')){
	function VoidDecrypter2($val){
		$CI = &get_instance();
		return $CI->my_encrypt->decode($val,'VoidMainDevelopment');
	}
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////
//LOAD INVENTORY
//LOAD INVENTORY
//LOAD INVENTORY
//LOAD INVENTORY
//LOAD INVENTORY
///////////////////////////////////////////////////////////////////////////////////////////////////////////
// public $CI;
// $CI = &get_instance();
// $CI = &get_instance();
// define("ChaInvenData",$CI->session->userdata('ChaInventory'));

define("delimiter", ",");
if( !function_exists('ChaInven')){
    function ChaInven(){
        $CI = &get_instance();
        $ChaInvenData = $CI->session->userdata('ChaInven');
        return $ChaInvenData;
    }
}

// if( !function_exists('GenItem')){
//     function GenItem($loc,$head){
//         $csv = new item_csv;
//         $csv->read();
//         $Binary = ChaInven();
        
//         $chainven = $Binary;
//         $item=array();
//         $INVEN_HEAD = substr($Binary,0,24);    //INVENTORY HEADER
//         $INVEN_SIZE = str_ireplace($INVEN_HEAD,"",$Binary); // REMOVE INVENTORY HEADER
//         $FILE_SIZE = strlen($INVEN_SIZE);
//         $MEM_SIZE= 160;
//         $ITEM_LIST = str_split($INVEN_SIZE,$MEM_SIZE);

//         if($ITEM_LIST!=0){
//             foreach($ITEM_LIST as $em_Buffer){
//                 $MID=str_pad(trimID($em_Buffer,16,4),3,0,STR_PAD_LEFT);
//                 $SID=str_pad(trimID($em_Buffer,20,4),3,0,STR_PAD_LEFT);
//                 $address="IN_".$MID."_".$SID;
//                 $item[] = array(  
//                     'pos' => "x".getData($em_Buffer,0,2)."y".getData($em_Buffer,4,2),
//                     'address' => "IN_".$MID."_".$SID,
//                     'strName' => $csv->item_offset[$address]['strName'],
//                     'InventoryFile' => $csv->item_offset[$address]['strInventoryFile'],
//                     'ICONX' => $csv->item_offset[$address]['sICONID wMainID'],
//                     'ICONY' => $csv->item_offset[$address]['sICONID wSubID']
//                 );
//             }
//         }

//         phpFastCache::$storage = "auto";
//         $cache = phpFastCache();
        
//         $ITEM_BUFFER=array();
//         for($a=0;$a<count($ITEM_LIST);$a++){
//             $data=$item[$a];
//             $ITEM_BUFFER[$data['pos']]['pos'] = $data['pos'];
//             $ITEM_BUFFER[$data['pos']]['strName'] = $data['strName'];
//             $ITEM_BUFFER[$data['pos']]['InventoryFile'] = $data['InventoryFile'];
//             $ITEM_BUFFER[$data['pos']]['ICONX'] = $data['ICONX'];
//             $ITEM_BUFFER[$data['pos']]['ICONY'] = $data['ICONY'];
//         }

//         return @$ITEM_BUFFER[$loc][$head];
//     }
// }

if( !function_exists('FindItem')){
    function FindItem($Location){
        $CI = &get_instance();

        if(!empty($CI->inventory->GenItem($Location,'pos'))){
            return '<div class="item-icon-holder lfloat"><div title="'.$CI->inventory->GenItem($Location,'strName').'" class="sprite-icon" style="background:url('.base_url().'slot/'.str_ireplace(".dds","",$CI->inventory->GenItem($Location,'InventoryFile')).'_'.$CI->inventory->GenItem($Location,'ICONY').'_'.$CI->inventory->GenItem($Location,'ICONX').'.png) -1px -1px;"></div></div>';
        } else {
            return '<div class="item-icon-holder lfloat"><div class="item-image"></div></div>';
        }
    }
}



if( !function_exists('getHeaderSize')){
    function getHeaderSize($memory){
		$LAST_KEY = key( array_slice( $memory, -1, 1, TRUE ) );
		return $LAST_KEY;
    }
}
    


if( !function_exists('ExtractMemAddress')){
    function ExtractMemAddress($ADDR){
        return explode(delimiter,$ADDR);
    }
}


if(! function_exists('getMemory')){
    function getMemory($a,$b,$c){
		$d=substr($a,$b,$c);
		return $d;
    }
}

if(! function_exists('getData')){
    function getData($a,$b,$c){
		return hexdec(substr($a,$b,$c));	
    }
}

if(! function_exists('trimID')){
    function trimID($a,$b,$c){
		$b=substr($a,$b,$c);
		$c=str_split($b,2);
		@$d=$c[1].$c[0];
		return hexdec($d);
    }
}

if(! function_exists('getOffset')){
    function getOffset($a,$b,$c){
		$f=substr($a,$b,2)."".substr($a,$c,2);
		return hexdec($f);
	}
}

