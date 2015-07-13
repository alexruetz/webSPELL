<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

$_language->readModule('news');

$ergebnis = safe_query(
    "SELECT
        newsID
    FROM
        " . PREFIX . "news
    WHERE
        newsID='" . $topnewsID . "' AND
        intern<=" . (int)isclanmember($userID) . " AND
        published='1'
    LIMIT 0,1"
);
$anz = mysqli_num_rows($ergebnis);
if ($anz) {
    $dn = mysqli_fetch_array($ergebnis);

    $message_array = array();
    $query = safe_query("SELECT * FROM " . PREFIX . "news_contents WHERE newsID='" . $dn[ 'newsID' ] . "'");
    while ($qs = mysqli_fetch_array($query)) {
        $message_array[ ] =
            array('lang' => $qs[ 'language' ], 'headline' => $qs[ 'headline' ], 'message' => $qs[ 'content' ]);
    }
    $showlang = select_language($message_array);

    $headline = clearfromtags($message_array[ $showlang ][ 'headline' ]);
    $content = $message_array[ $showlang ][ 'message' ];
    $content = nl2br(strip_tags($content));

    if (mb_strlen($content) > $maxtopnewschars) {
        $content = mb_substr($content, 0, $maxtopnewschars);
        $content .= '...';
    }
    $content = htmloutput($content);

    $data_array = array();
    $data_array['$topnewsID'] = $topnewsID;
    $data_array['$headline'] = $headline;
    $data_array['$content'] = $content;
    $sc_topnews = $GLOBALS["_template"]->replaceTemplate("sc_topnews", $data_array);
    echo $sc_topnews;
} else {
    echo $_language->module[ 'no_topnews' ];
}
