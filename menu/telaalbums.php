<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function telaalbums_step_1_create_project() {

	$site_url = admin_url();
	$settings_url = $site_url . "/admin.php?page=telaalbums";
	$site = $_SERVER['SERVER_NAME'];
        $port = ($_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : '';
        $self  = $_SERVER['PHP_SELF'];
        $js_origins = "http://" . $site . $port;
	echo "<h2>Install Step 1: Create Project</h2>";
	echo "<p>As of April 20th, 2015, Google no longer allows access to Picasa Web Albums using AuthSub authentication. Now we must use OAuth2, which requires you to create a project in the Google Developer Console.  <br /><br />IF YOU SEE THIS PAGE AFTER YOU HAVE ALREADY SET UP THE PLUGIN, RELOAD THIS PAGE.  <br /><br />If you still see this page after you have set up the plugin and reloaded a few times, then you have done something wrong with the setup or the plugin is broken.  Go to <a href='https://telaaedifex.com/utils/support/open.php'>Telaaedifex's Helpdesk</a> and open a ticket if you are still having issues.<p>";
	echo "<p>To create the project,<ol>";
	echo "<li>Head to the <a target='_BLANK' href='https://console.developers.google.com/project'>Google Developer Console</a> and click 'Create Project'";
	echo "<li>Give the project a name (i.e. telaalbums) and a project id (i.e. telaalbums). Click Create. Wait a few minutes.</li>";
	echo "<li>After it's done, click 'APIs & Auth' in the left sidebar, the underneath that, click 'Consent screen'.</li>";
	echo "<li>Select your email address from the dropdown and enter a Product Name, i.e. telaalbums. Click 'Save'.</li>";
	echo "<li>Above 'Consent Screen' in the sidebar, click 'Credentials'.</li>";
	echo "<li>On the page that comes up, click the 'Create new Client ID' button";
	echo "<li>For Application Type, select 'Web Application'.</li>";
	echo "<li>In the Authorized Javascript Origins box, enter: $js_origins </li>";
	echo "<li>In the Authorized Redirect URIs box, enter: $settings_url </li>";
	echo "<li>Click 'Create Client ID'</li>";
	echo "<li>Copy the 'Client ID' and 'Client Secret' or leave the window open</li>";
	echo "<li>Go to <a href='$settings_url&loc=telaalbums_step_2_project_creds'>Step 2</a>...";
	echo "</ol></p>";

}

function telaalbums_step_2_project_creds() {

		$site_url = admin_url();
        $settings_url = $site_url . "/admin.php?page=telaalbums";
		$next  = $settings_url . "&loc=start_oauth";
		$client_id = get_option("telaalbums_client_id");
        $client_secret = get_option("telaalbums_client_secret");
		$TELAALBUMS_GOOGLE_USER	 		= get_option("telaalbums_google_username");
	echo "<h2>Install Step 2: Project Credentials</h2>";
	echo "<p>Now we need to enter this info for Telaaedifex's Albums to exchange it for an OAuth2 token.<br />
			 Your google username is your email (example@gmail.com) without the @gmail.com so ...(example)
	</p>";
	echo "<form id='project_creds' action='$next' method='GET'><table>";
		echo "<tr><td>Google Username</td><td><input style='width:400px;' name='GOOGLE_USER' id='GOOGLE_USER' value='$TELAALBUMS_GOOGLE_USER'/></td></tr>";
	echo "<tr><td>Client ID</td><td><input style='width:400px;' name='client_id' id='client_id' value='$client_id'/></td></tr>";
	echo "<tr><td>Client Secret</td><td><input style='width:400px;' name='client_secret' id='client_secret' value='$client_secret'/></td></tr>";
	echo "</table>";
	echo "<input type='hidden' name='loc' value='telaalbums_step_3_start_oauth' />";
	echo "<input type='hidden' name='page' value='telaalbums' />";
	echo "<input type='submit' value='Go to Step 3' />";
	echo "</form>";

}


function telaalbums_step_3_start_oauth() {
	$site_url = admin_url();
    $settings_url = $site_url . "/admin.php?page=telaalbums";
	$TELAALBUMS_GOOGLE_USER = $_GET['GOOGLE_USER'];
	$client_id = $_GET['client_id'];
	$client_secret = $_GET['client_secret'];
	//if ((!isset($client_id)) || (!isset($client_secret)) || (!isset($TELAALBUMS_GOOGLE_USER)) ) {
	//	$TELAALBUMS_GOOGLE_USER	 = get_option("telaalbums_google_username");
	//	$client_id = get_option("telaalbums_client_id");
	//	$client_secret = get_option("telaalbums_client_secret");
	//}
		update_option("telaalbums_google_username",$TELAALBUMS_GOOGLE_USER);       # save your username
		update_option("telaalbums_client_id",$client_id);          # save the access token
        update_option("telaalbums_client_secret",$client_secret);       # save the refresh token
        echo "<h2>Install Step 3: Token Generation</h2>";
        echo "<p>Generating this Google OAuth2 token is a one-time step that allows Telaaedifex's Albums to access to your private (unlisted) Picasa albums.</p>";
	echo "<p><strong>Verify the info below before clicking 'Request The Token'</strong></p>";
	echo "<table><tr><td>REDIRECT URIS:</td><td>$settings_url</td></tr>";
	echo "<tr><td>Username:</td><td>$TELAALBUMS_GOOGLE_USER</td></tr>";
	echo "<tr><td>CLIENT ID:</td><td>$client_id</td></tr>";
	echo "<tr><td>CLIENT SECRET:</td><td>$client_secret</td></tr></table>";
	$settings_url = urlencode($settings_url);
	$next = "https://accounts.google.com/o/oauth2/auth?scope=https://picasaweb.google.com/data/&response_type=code&access_type=offline&redirect_uri=$settings_url&approval_prompt=force&client_id=$client_id";
        echo "<p>If this is correct, <a href='$next'>";
        echo "Request The Token</a>, then click 'Accept' on the page that comes up.</p>";
        echo "</body>\n</html>";
}

function telaalbums_step_4_set_token() {

    $site_url = admin_url();
    $settings_url = $site_url . "/admin.php?page=telaalbums";

    # THESE 2 COME FROM DB
    $client_id = get_option("telaalbums_client_id");
    $client_secret = get_option("telaalbums_client_secret");

    $TELAALBUMS_now = date("U");
    
    $postBody = 'code='.urlencode($_GET['code'])
              .'&grant_type=authorization_code'
              .'&redirect_uri='.urlencode($settings_url)
              .'&client_id='.urlencode($client_id)
              .'&client_secret='.urlencode($client_secret);

    $curl = curl_init();
    curl_setopt_array( $curl,
                array( CURLOPT_CUSTOMREQUEST => 'POST'
                           , CURLOPT_URL => 'https://accounts.google.com/o/oauth2/token'
                           , CURLOPT_HTTPHEADER => array( "Content-Type: application/x-www-form-urlencoded"
                                                         , "Content-Length: ".strlen($postBody)
                                                         , "User-Agent: Telaaedifex's Albums/0.2 +https://telaaedifex.com/albums"
                                                         )
                           , CURLOPT_POSTFIELDS => $postBody                              
                           , CURLOPT_REFERER => $settings_url
                           , CURLOPT_RETURNTRANSFER => 1 // means output will be a return value from curl_exec() instead of simply echoed
                           , CURLOPT_TIMEOUT => 12 // max seconds to wait
                           , CURLOPT_FOLLOWLOCATION => 0 // don't follow any Location headers, use only the CURLOPT_URL, this is for security
                           , CURLOPT_FAILONERROR => 0 // do not fail verbosely fi the http_code is an error, this is for security
                           , CURLOPT_SSL_VERIFYPEER => 1 // do verify the SSL of CURLOPT_URL, this is for security
                           , CURLOPT_VERBOSE => 0 // don't output verbosely to stderr, this is for security
                ) );
    $orig_response = curl_exec($curl);
    $response = json_decode($orig_response, true); // convert returned objects into associative arrays
    $TELAALBUMS_TOKEN_EXPIRES = $TELAALBUMS_now + $response['expires_in'];
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($response['access_token']) {
		update_option("telaalbums_oauth_token",$response['access_token']);          # save the access token
        update_option("telaalbums_refresh_token",$response['refresh_token']);       # save the refresh token
        update_option("telaalbums_token_expires",$TELAALBUMS_TOKEN_EXPIRES);                   # save the epoch when the token expires
	echo "<h2>Install Step 4: Complete!</h2>";
        echo "Token retrieved and saved in WordPress configuration database.<br />";
		$uri = $_SERVER["REQUEST_URI"];
        list($back_link, $uri_tail) = explode('&', $uri);
        echo "Continue to <a href='$back_link'>the final step: Settings</a>...\n";
    } else {
	echo "<h2>Install Step 4: Failed!</h2>";
	echo "Got the following response:<br />";
	echo $orig_response;
	
	
    }

}


function update_options(){

    $site_url = admin_url();
    $settings_url = $site_url . "/admin.php?page=telaalbums";

    # THESE 2 COME FROM DB
    $client_id = get_option("telaalbums_client_id");
    $client_secret = get_option("telaalbums_client_secret");

    $TELAALBUMS_now = date("U");
    
    $postBody = 'code='.urlencode($_GET['code'])
              .'&grant_type=authorization_code'
              .'&redirect_uri='.urlencode($settings_url)
              .'&client_id='.urlencode($client_id)
              .'&client_secret='.urlencode($client_secret);

    $curl = curl_init();
    curl_setopt_array( $curl,
                array( CURLOPT_CUSTOMREQUEST => 'POST'
                           , CURLOPT_URL => 'https://accounts.google.com/o/oauth2/token'
                           , CURLOPT_HTTPHEADER => array( 'Content-Type: application/x-www-form-urlencoded'
                                                         , 'Content-Length: '.strlen($postBody)
                                                         , "User-Agent: Telaaedifex's Albums/0.2 +https://telaaedifex.com/telaalbums"
                                                         )
                           , CURLOPT_POSTFIELDS => $postBody                              
                           , CURLOPT_REFERER => $settings_url
                           , CURLOPT_RETURNTRANSFER => 1 // means output will be a return value from curl_exec() instead of simply echoed
                           , CURLOPT_TIMEOUT => 12 // max seconds to wait
                           , CURLOPT_FOLLOWLOCATION => 0 // don't follow any Location headers, use only the CURLOPT_URL, this is for security
                           , CURLOPT_FAILONERROR => 0 // do not fail verbosely fi the http_code is an error, this is for security
                           , CURLOPT_SSL_VERIFYPEER => 1 // do verify the SSL of CURLOPT_URL, this is for security
                           , CURLOPT_VERBOSE => 0 // don't output verbosely to stderr, this is for security
                ) );
    $orig_response = curl_exec($curl);
    $response = json_decode($orig_response, true); // convert returned objects into associative arrays
    $TELAALBUMS_TOKEN_EXPIRES = $TELAALBUMS_now + $response['expires_in'];
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($response['access_token']) {
		update_option("telaalbums_oauth_token",$response['access_token']);          # save the access token
        update_option("telaalbums_refresh_token",$response['refresh_token']);       # save the refresh token
        update_option("telaalbums_token_expires",$TELAALBUMS_TOKEN_EXPIRES);                   # save the epoch when the token expires
	echo "<h2>Install Step 4: Complete!</h2>";
        echo "Token retrieved and saved in WordPress configuration database.<br />";
		$uri = $_SERVER["REQUEST_URI"];
        list($back_link, $uri_tail) = explode('&', $uri);
        echo "Continue to <a href='$back_link'>the final step: Settings</a>...\n";
    } else {
	echo "<h2>Install Step 4: Failed!</h2>";
	echo "Got the following response:<br />";
	echo $orig_response;
    }

}


function telaalbums_main_page_content() {
	
echo "
	
<style>
/* Tab Content - menucool.com */
ul.telaalbums_tabs
{
    padding: 14px 0 8px;
    margin:0;
    font-size: 0;
    list-style-type: none;
    text-align: center; /*set to left, center, or right to align the tabs as desired*/
    background: #DDDDE3;
    border:1px solid #CCC;
    border-bottom:none;
    border-radius: 2px 2px 0 0;
}
        
ul.telaalbums_tabs li
{
    display: inline;
    margin: 0;
    margin-right: 2px;/*distance between tabs*/
    font: normal 12px Verdana;
}
        
ul.telaalbums_tabs li a
{
    text-decoration: none;
    position: relative;
    padding: 8px 10px;
    color: #000;
    border-radius: 3px;
    outline:none;
}
  
        
ul.telaalbums_tabs li a:hover
{
    text-decoration: underline;
    color: #000;
}
        
ul.telaalbums_tabs li.selected a
{
    position: relative;
    top: 0px;
    font-weight:bold;
    background: #FFF;
    border: 1px solid #AAA;
    color: #000;
}
        
        
ul.telaalbums_tabs li.selected a:hover, ul.telaalbums_tabs li.selected a:hover
{
    text-decoration: none;
}

div.tabcontents
{
    border: 1px solid #CCC; padding: 30px;
    border-top-color:#AAA;
    background-color:#FFF;
    border-radius: 0 0 4px 4px;
}
div.all{width:50%;}
</style>
";
echo'
<script>
/* http://www.menucool.com/tabbed-content Free to use. v2013.7.6 */
(function(){var g=function(a){if(a&&a.stopPropagation)a.stopPropagation();else window.event.cancelBubble=true;var b=a?a:window.event;b.preventDefault&&b.preventDefault()},d=function(a,c,b){if(a.addEventListener)a.addEventListener(c,b,false);else a.attachEvent&&a.attachEvent("on"+c,b)},a=function(c,a){var b=new RegExp("(^| )"+a+"( |$)");return b.test(c.className)?true:false},j=function(b,c,d){if(!a(b,c))if(b.className=="")b.className=c;else if(d)b.className=c+" "+b.className;else b.className+=" "+c},h=function(a,b){var c=new RegExp("(^| )"+b+"( |$)");a.className=a.className.replace(c,"$1");a.className=a.className.replace(/ $/,"")},e=function(){var b=window.location.pathname;if(b.indexOf("/")!=-1)b=b.split("/");var a=b[b.length-1]||"root";if(a.indexOf(".")!=-1)a=a.substring(0,a.indexOf("."));if(a>20)a=a.substring(a.length-19);return a},c="mi"+e(),b=function(b,a){this.g(b,a)};b.prototype={h:function(){var b=new RegExp(c+this.a+"=(\\d+)"),a=document.cookie.match(b);return a?a[1]:this.i()},i:function(){for(var b=0,c=this.b.length;b<c;b++)if(a(this.b[b].parentNode,"selected"))return b;return 0},j:function(b,d){var c=document.getElementById(b.TargetId);if(!c)return;this.l(c);for(var a=0;a<this.b.length;a++)if(this.b[a]==b){j(b.parentNode,"selected");d&&this.d&&this.k(this.a,a)}else h(this.b[a].parentNode,"selected")},k:function(a,b){document.cookie=c+a+"="+b+"; path=/"},l:function(b){for(var a=0;a<this.c.length;a++)this.c[a].style.display=this.c[a].id==b.id?"block":"none"},m:function(){this.c=[];for(var c=this,a=0;a<this.b.length;a++){var b=document.getElementById(this.b[a].TargetId);if(b){this.c.push(b);d(this.b[a],"click",function(b){var a=this;if(a===window)a=window.event.srcElement;c.j(a,1);g(b);return false})}}},g:function(f,h){this.a=h;this.b=[];for(var e=f.getElementsByTagName("a"),i=/#([^?]+)/,a,b,c=0;c<e.length;c++){b=e[c];a=b.getAttribute("href");if(a.indexOf("#")==-1)continue;else{var d=a.match(i);if(d){a=d[1];b.TargetId=a;this.b.push(b)}else continue}}var g=f.getAttribute("data-persist")||"";this.d=g.toLowerCase()=="true"?1:0;this.m();this.n()},n:function(){var a=this.d?parseInt(this.h()):this.i();if(a>=this.b.length)a=0;this.j(this.b[a],0)}};var k=[],i=function(e){var b=false;function a(){if(b)return;b=true;setTimeout(e,4)}if(document.addEventListener)document.addEventListener("DOMContentLoaded",a,false);else if(document.attachEvent){try{var f=window.frameElement!=null}catch(g){}if(document.documentElement.doScroll&&!f){function c(){if(b)return;try{document.documentElement.doScroll("left");a()}catch(d){setTimeout(c,10)}}c()}document.attachEvent("onreadystatechange",function(){document.readyState==="complete"&&a()})}d(window,"load",a)},f=function(){for(var d=document.getElementsByTagName("ul"),c=0,e=d.length;c<e;c++)a(d[c],"telaalbums_tabs")&&k.push(new b(d[c],c))};i(f);return{}})()
</script>
';
    
echo "		<h2>Telaaedifex's Albums</h2>
  
		<div class='all'>
		<ul class='telaalbums_tabs'>
    		<li><a href='#Announcements'>Announcements</a></li>
			<li><a href='#Help_Links'>Help Links</a></li>
    		<li><a href='#Version_Information'>Version Information</a></li>
		</ul>
		<div class='tabcontents'>
		
			<div id='Announcements'>
				<table class='widefat' width='100%'>
				<thead><tr><th valign=top colspan=3>Announcements</th></tr></thead>
                <tr><td align=''>";  
                    // Get RSS Feed(s)
                    include_once( ABSPATH . WPINC . '/feed.php' );
                    
                    // Get a SimplePie feed object from the specified feed source.
                    $rss = fetch_feed( 'https://telaaedifex.com/albums/feed/' );
                    
                    $maxitems = 5;
                    
                    if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly
                    
                        // Figure out how many total items there are, but limit it to 5. 
                        $maxitems = $rss->get_item_quantity( 5 ); 
                    
                        // Build an array of all the items, starting with element 0 (first element).
                        $rss_items = $rss->get_items( 0, $maxitems );
                    
                    endif;
                    
                    echo "<ul>";
                         if ( $maxitems == 0 ) :
                             echo "<li>"; _e( 'No items', 'my-text-domain' ); echo "</li>";
                        else :
                             // Loop through each feed item and display each item as a hyperlink.
                             foreach ( $rss_items as $item ) :
                               echo "<li>";
                                     echo"<a href=".esc_url( $item->get_permalink() )."";
                                        echo "title=".printf( __( 'Posted %s', 'my-text-domain' ), $item->get_date('j F Y | g:i a') ).">";
                                        echo esc_html( $item->get_title() );
                                     echo "</a>
                                </li>";
                           endforeach;
                       endif;
                    echo  "</ul>
                    </tr></td>
				<tfoot><tr><th valign=top colspan=3></th></tr></tfoot>
				</table><br />
				";
			echo "</div>
			
			
			
			<div id='Help_Links'>
				<table class='widefat' width='100%'>
				<thead><tr><th valign=top colspan=3>Help & Support</th></tr></thead>
				<tr><td>
                If you encounter any issues, head to the <strong><a href='https://telaaedifex.com/utils/support/kb/faq.php?cid=2' target='_BLANK'>support site</a></strong>
                </tr></td>
				<tfoot><tr><th valign=top colspan=3></th></tr></tfoot>
				</table><br />
			</div>
			
			
			
			<div id='Version_Information'>
            
                <table class='widefat' width='100%'>
                <thead><tr><th valign=top colspan=3>Server Information</th></tr></thead>
                <tr><td>
                <table cellspacing=0 width='100%'>
                <tr><th>Version:</th><td>";global $THIS_VERSION;echo $THIS_VERSION;echo"</td></tr>
                <tr><th>Hostname:</th><td>";echo $_SERVER['SERVER_NAME']; echo"</td></tr>";
                list($ws,$os) = array_pad(explode(" ", $_SERVER['SERVER_SOFTWARE'], 2), 2, null);
                $curlver = curl_version();
                echo "<tr><th valign=top>Webserver:</th><td>";echo $ws.$os; echo"</td></tr>
                <tr><th valign=top>PHP/cURL:</th><td>v";echo "".phpversion()."/".$curlver['version']."";echo"</td></tr>
                </table>
                <td></tr>
                <tfoot><tr><th valign=top colspan=3></th></tr></tfoot>
                </table>
                </td></tr></table>
            
			</div>
		</div>
      </div>";
	
}



function telaalbums_main_page() { 


$TELAALBUMS_OAUTH_TOKEN = get_option("telaalbums_oauth_token");
//if ($THIS_VERSION > $Telaaedifex's Albums_VERSION || $Telaaedifex's Albums_VERSION == '' || $Telaaedifex's Albums_VERSION == 'FALSE'){upgrade_options();}
if(isset($_GET['page'])){global $p;$p = $_GET['page'];}
if(isset($_GET['loc'])){global $loc;$loc = $_GET['loc'];}else{$loc = '';}
if (isset($_GET['code'])) {
        telaalbums_step_4_set_token();
} else if (isset($w3)) {
	echo "<a href='admin.php?page=telaalbums'>CLICK HERE!!!</a>";
} else if ($loc == 'telaalbums_step_3_start_oauth') {
	telaalbums_step_3_start_oauth();
} else if ($loc == 'telaalbums_step_2_project_creds') {
        telaalbums_step_2_project_creds();
} else if (($TELAALBUMS_OAUTH_TOKEN == "FALSE" || $TELAALBUMS_OAUTH_TOKEN == "" || $TELAALBUMS_OAUTH_TOKEN == "NULL" ) && (!isset($_GET['code'])) ) {
	//upgrade_options();
	telaalbums_step_1_create_project();
} else if ($loc == 'reset') {
	delete_option("telaalbums_oauth_token");
	delete_option("telaalbums_token_expires");	
	delete_option("telaalbums_refresh_token");
	telaalbums_step_3_start_oauth();
} else if (!$TELAALBUMS_OAUTH_TOKEN == "FALSE" || !$TELAALBUMS_OAUTH_TOKEN == "" || !$TELAALBUMS_OAUTH_TOKEN == "NULL" && $loc == ''){
	telaalbums_main_page_content();
} else {echo "ERROR!!!";}

}

?>