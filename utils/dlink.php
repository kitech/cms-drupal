<?php
/* <iframe src="/utils/dlink.php?p=nullfxp" width=500px height=415px frameborder=0> */

$pkg = $_GET['p'];
$a_url = "http://sourceforge.net/project/showfiles.php?group_id=204368&package_id=243873";
$a_url = "http://sourceforge.net/projects/nullfxp/files/";
$a_url = "http://sourceforge.net/projects/nullfxp/files/nullfxp/";
$a_url = "http://sourceforge.net/projects/nullfxp/files/{$pkg}/";
$pkg_name = "nullfxp";
$pkg_name = $pkg;
$versions = array('2.1.0', '2.0.2', '2.0.1', '2.0.0');
//echo '<a href="http://sourceforge.net/projects/nullfxp/files/" target="_blank">SourceForge.net Nullfxp Download page</a>';

$a_c = file($a_url);

// print_r($a_c);

foreach ($versions as $idx => $ver) {

}

$blno = -1;
$elno = 0;
if(!$a_c || count($a_c) == 0 ) {
        //echo "Page error. Refresh please.";
        echo "<a href=$a_url taget=_blank>SourceForge.net Nullfxp Download page</a>";
}else{
        foreach($a_c as $lno => $lv) {
                //if(trim($lv) == '<a name="downloads"></a>') {
                //      $blno = $lno;
                //}
                if(strstr(trim($lv), "files_list") != null) {
                        $blno = $lno;
                }

                //if(trim($lv) == '<div id="fadbtmp">&nbsp;</div>') {
                //      $elno = $lno;
                //}
        $section_end = false;
                if($blno > 0) {
                        if(trim($lv) == "</table>") {
                                $elno = $lno+1;
                $section_end = true;
                        }
                }

                if($blno > 0 ) {
                        if($lno == $blno+1 ) {
                                $lv = str_replace("border=\"0\"", "border=\"1\"",  $lv);
                        }

                        if($elno == 0 || $lno < $elno) {
                                if(strstr($lv, "href=\"/") != null) {
                                        $lv = str_replace("href=\"/",  "href=\"http://sf.net/", $lv);
                                }

                                if(strstr($lv, "href=\"show") != null) {
                                        $lv = str_replace("href=\"show", "href=\"http://sf.net/project/show",  $lv);
                                }

                                if(strstr($lv, "href=\"mirror") != null) {
                                        $lv = str_replace("href=\"mirror", "target=\"_blank\" href=\"http://sf.net/project/mirror",$lv);
                                }
                                if(strstr($lv, "onClick=\"window.location") != null) {
                                        $lv = str_replace("window.location='", "window.location='http://sf.net",$lv);
                                }
                                echo $lv;
                        }
                }
        if ($section_end) {
            break;
        }
        }
    echo "\n";
}

?>