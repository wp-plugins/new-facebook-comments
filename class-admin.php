<?php
define("FBC_NAME","New Facebook Comments");
define("FBC_TAGLINE","Adds Facebook Comments");
define("FBC_URL","https://www.google.com/+tayyabismail0o1");
define("FBC_EXTEND_URL","#");
define("FBC_AUTHOR_TWITTER","#");
define("FBC_DONATE_LINK","#");

add_action('admin_init', 'fbcomments_init' );
function fbcomments_init(){
	register_setting( 'fbcomments_options', 'fbcomments' );
	$new_options = array(
		'fbml' => 'on',
		'old_sdk' => '',
		'opengraph' => 'off',
		'fbns' => 'off',
		'html5' => 'on',
		'posts' => 'on',
		'pages' => 'off',
		'homepage' => 'off',
		'appID' => '',
		'mods' => '',
		'num' => '5',
		'count' => 'on',
		'countmsg' => 'comments',
		'title' => 'Comments',
		'titleclass' => '',
		'width' => '100%',
		'countstyle' => '',
		'linklove' => 'off',
		'scheme' => 'light',
		'language' => 'en_US'
	);

	// if old options exist, update to array
	foreach( $new_options as $key => $value ) {
		if( $existing = get_option( 'fbcomments_' . $key ) ) {
			$new_options[$key] = $existing;
			delete_option( 'fbcomments_' . $key );
		}

	}


	add_option( 'fbcomments', $new_options );
}


add_action('admin_menu', 'show_fbcomments_options');
function show_fbcomments_options() {
	add_options_page('Facebook Comments Settings', 'FB Comments', 'manage_options', 'fbcomments', 'fbcomments_options');
}


function fbcomments_fetch_rss_feed() {
    include_once(ABSPATH . WPINC . '#');
	$rss = fetch_feed("#");	
	if ( is_wp_error($rss) ) { return false; }	
	$rss_items = $rss->get_items(0, 3);
    return $rss_items;
}   

function fbcomments_admin_notice(){
$options = get_option('fbcomments');
if ($options['appID']=="") {
	$fbadminurl = get_admin_url()."options-general.php?page=fbcomments";
    echo '<div class="error">
       <p>Please enter your Facebook App ID for Facebook Comments to work properly. <a href="'.$fbadminurl.'"><input type="submit" value="Enter App ID" class="button-secondary" /></a></p>
    </div>';
}
}
add_action('admin_notices', 'fbcomments_admin_notice');

// ADMIN PAGE
function fbcomments_options() {
$domain = get_option('siteurl');
$domain = str_replace('http://', '', $domain);
$domain = str_replace('www.', '', $domain);
?>
    <link href="<?php echo plugins_url( 'admin.css' , __FILE__ ); ?>" rel="stylesheet" type="text/css">
    <div class="pea_admin_wrap">
        <div class="pea_admin_top">
            <h1><?php echo FBC_NAME?> <small> - <?php echo FBC_TAGLINE?></small></h1>
        </div>

        <div class="pea_admin_main_wrap">
            <div class="pea_admin_main_left">      

		<form method="post" action="options.php" id="options">
			<?php settings_fields('fbcomments_options'); ?>
			<?php $options = get_option('fbcomments'); 
				if (!isset($options['fbml'])) {$options['fbml'] = "";}
				if (!isset($options['old_sdk'])) {$options['old_sdk'] = "";}
				if (!isset($options['fbns'])) {$options['fbns'] = "";}
				if (!isset($options['opengraph'])) {$options['opengraph'] = "";}
				if (!isset($options['html5'])) {$options['html5'] = "";}
				if (!isset($options['linklove'])) {$options['linklove'] = "";}
				if (!isset($options['posts'])) {$options['posts'] = "";}
				if (!isset($options['pages'])) {$options['pages'] = "";}
				if (!isset($options['homepage'])) {$options['homepage'] = "";}
				if (!isset($options['count'])) {$options['count'] = "";}
				if (!isset($options['jquery'])) {$options['jquery'] = "";}
			?>
			<?php if ($options['appID']=="") { ?>
			<div class="error">
			<h3 class="title">You Need to Set Up your Facebook App ID!</h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><a href="https://developers.facebook.com/apps" style="text-decoration:none" target="_blank">Create an App to handle your comments</a></th>
					<td><small style="font-size:12px;">Click <strong>+ Create New App</strong> to the top right of the page. Name the App something memorable e.g. "Comments". <small style="font-size:12px;">Ensure you enter <strong><?php echo $domain; ?></strong> in both "App Domains" and in the url fields.</small> Once you have done it enter app id here and press enter:</small><br><strong>APP ID: </strong><input id="appID" type="text" name="fbcomments[appID]" value="<?php echo $options['appID']; ?>" /><br><br>
</td>
				</tr>
			</table>
</div>
<?php } else { ?>
			<h3 class="title">Facebook Setup</h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><a href="https://developers.facebook.com/apps<?php if ($options['appID'] != "") { echo "/".$options['appID']."/summary"; } ?>" style="text-decoration:none" target="_blank">App Setup</a></th>
					<td><small style="font-size:12px;">To set up, choose your App and click <strong>Edit Settings</strong>. Ensure you enter <strong><?php echo $domain; ?></strong> in both "App Domains" and in the url fields.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><a href="https://developers.facebook.com/apps" style="text-decoration:none" target="_blank">Create a New App</a></th>
					<td><small style="font-size:12px;">You have already entered your App ID, but if you want to set up a new one click <strong>+ Create New App</strong> to the top right of the page. Name the App something memorable e.g. "Comments".</small><small style="font-size:12px;">Ensure you enter <strong><?php echo $domain; ?></strong> in both "App Domains" and in the url fields.</small></td>
				</tr>
			</table>
<?php } ?>

			<h3 class="title">Moderation</h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><a href="https://developers.facebook.com/tools/comments<?php if ($options['appID'] != "") { echo "?id=".$options['appID']."&view=queue"; } ?>" style="text-decoration:none" target="_blank">Comment Moderation Area</a></th>
					<td><small style="font-size:12px;">When you're a moderator of comments you will see notifications in facebook.com. If you want to see all comments in one area, use the link to the left.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="appID">Moderators</label></th>
					<td><input id="mods" type="text" name="fbcomments[mods]" value="<?php echo $options['mods']; ?>" size="50" /><br><small style="font-size:12px;">By default, all admins to the App ID can moderate comments. To add moderators, enter each Facebook Profile ID by a comma <strong>without spaces</strong>. To find your Facebook User ID, click <a href="https://developers.facebook.com/tools/explorer/?method=GET&path=me" target="blank">here</a> where you will see your own. To view someone else's, replace "me" with their username in the input provided or select your app from above.If not working use <a href="http://ipics32.blogspot.com/p/get-your-facebook-id.html" target="blank">this</a>.</small></td>
				</tr>
			</table>


			<h3 class="title">Main Settings</h3>
			<table class="form-table">
<?php if ($options['appID']!="") { ?>
				<tr valign="top"><th scope="row"><label for="appID">Facebook App ID</label></th>
					<td><input id="appID" type="text" name="fbcomments[appID]" value="<?php echo $options['appID']; ?>" /></td>
				</tr>
<?php } ?>
				<tr valign="top"><th scope="row"><label for="fbml">Enable FBML</label></th>
					<td><input id="fbml" name="fbcomments[fbml]" type="checkbox" value="on" <?php checked('on', $options['fbml']); ?> /> <small style="font-size:12px;">Only disable this if you already have XFBML enabled elsewhere in your theme/plugins.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="old_sdk">Use old SDK</label></th>
					<td><input id="old_sdk" name="fbcomments[old_sdk]" type="checkbox" value="on" <?php checked('on', $options['old_sdk']); ?> /> <small style="font-size:12px;">V2.3 of Facebook's SDK has some interface changes. If you want to use v2.0 of the SDK check this box.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="fbns">Use Facebook NameServer</label></th>
					<td><input id="fbns" name="fbcomments[fbns]" type="checkbox" value="on" <?php checked('on', $options['fbml']); ?> /> <small style="font-size:12px;">Only enable this if Facebook Comments do not appear.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="opengraph">Use Open Graph NameServer</label></th>
					<td><input id="opengraph" name="fbcomments[opengraph]" type="checkbox" value="on" <?php checked('on', $options['opengraph']); ?> /> <small style="font-size:12px;">Only enable this if Facebook comments are not appearing, not all information is being passed to Facebook or if you have not enabled Open Graph elsewhere within you website.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="html5">Use HTML5</label></th>
					<td><input id="html5" name="fbcomments[html5]" type="checkbox" value="on" <?php checked('on', $options['html5']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="linklove">Credit</label></th>
					<td><input id="credit" name="fbcomments[linklove]" type="checkbox" value="on" <?php checked('on', $options['linklove']); ?> /></td>
				</tr>
			</table>

			<h3 class="title">Display Settings</h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><label for="posts">Singular Posts</label></th>
					<td><input id="posts" name="fbcomments[posts]" type="checkbox" value="on" <?php checked('on', $options['posts']); ?> /> <small style="font-size:12px;">This includes all posts, custom post types and attacments. Note that you can disable facebook comments on a post using the Facebook Comments checkbox present on the post editor page.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="pages">Pages</label></th>
					<td><input id="pages" name="fbcomments[pages]" type="checkbox" value="on" <?php checked('on', $options['pages']); ?> /> <small style="font-size:12px;">Note that you can disable facebook comments on a page using the Facebook Comments checkbox present on the page editor page.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="homepage">Homepage</label></th>
					<td><input id="home" name="fbcomments[homepage]" type="checkbox" value="on" <?php checked('on', $options['homepage']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="language">Language</label></th>
					<td>
						<select name="fbcomments[language]">
							<option value="af_ZA" <?php selected( $options['language'], 'af_ZA' ); ?>>Afrikaans</option>
							<option value="ar_AR" <?php selected( $options['language'], 'ar_AR' ); ?>>Arabic</option>
							<option value="az_AZ" <?php selected( $options['language'], 'az_AZ' ); ?>>Azerbaijani</option>
							<option value="be_BY" <?php selected( $options['language'], 'be_BY' ); ?>>Belarusian</option>
							<option value="bg_BG" <?php selected( $options['language'], 'bg_BG' ); ?>>Bulgarian</option>
							<option value="bn_IN" <?php selected( $options['language'], 'bn_IN' ); ?>>Bengali</option>
							<option value="bs_BA" <?php selected( $options['language'], 'bs_BA' ); ?>>Bosnian</option>
							<option value="ca_ES" <?php selected( $options['language'], 'ca_ES' ); ?>>Catalan</option>
							<option value="cs_CZ" <?php selected( $options['language'], 'cs_CZ' ); ?>>Czech</option>
							<option value="cy_GB" <?php selected( $options['language'], 'cy_GB' ); ?>>Welsh</option>
							<option value="da_DK" <?php selected( $options['language'], 'da_DK' ); ?>>Danish</option>
							<option value="de_DE" <?php selected( $options['language'], 'de_DE' ); ?>>German</option>
							<option value="el_GR" <?php selected( $options['language'], 'el_GR' ); ?>>Greek</option>
							<option value="en_GB" <?php selected( $options['language'], 'en_GB' ); ?>>English (UK)</option>
							<option value="en_PI" <?php selected( $options['language'], 'en_PI' ); ?>>English (Pirate)</option>
							<option value="en_UD" <?php selected( $options['language'], 'en_UD' ); ?>>English (Upside Down)</option>
							<option value="en_US" <?php selected( $options['language'], 'en_US' ); ?>>English (US)</option>
							<option value="eo_EO" <?php selected( $options['language'], 'eo_EO' ); ?>>Esperanto</option>
							<option value="es_ES" <?php selected( $options['language'], 'es_ES' ); ?>>Spanish (Spain)</option>
							<option value="es_LA" <?php selected( $options['language'], 'es_LA' ); ?>>Spanish</option>
							<option value="et_EE" <?php selected( $options['language'], 'et_EE' ); ?>>Estonian</option>
							<option value="eu_ES" <?php selected( $options['language'], 'eu_ES' ); ?>>Basque</option>
							<option value="fa_IR" <?php selected( $options['language'], 'fa_IR' ); ?>>Persian</option>
							<option value="fb_LT" <?php selected( $options['language'], 'fb_LT' ); ?>>Leet Speak</option>
							<option value="fi_FI" <?php selected( $options['language'], 'fi_FI' ); ?>>Finnish</option>
							<option value="fo_FO" <?php selected( $options['language'], 'fo_FO' ); ?>>Faroese</option>
							<option value="fr_CA" <?php selected( $options['language'], 'fr_CA' ); ?>>French (Canada)</option>
							<option value="fr_FR" <?php selected( $options['language'], 'fr_FR' ); ?>>French (France)</option>
							<option value="fy_NL" <?php selected( $options['language'], 'fy_NL' ); ?>>Frisian</option>
							<option value="ga_IE" <?php selected( $options['language'], 'ga_IE' ); ?>>Irish</option>
							<option value="gl_ES" <?php selected( $options['language'], 'gl_ES' ); ?>>Galician</option>
							<option value="he_IL" <?php selected( $options['language'], 'he_IL' ); ?>>Hebrew</option>
							<option value="hi_IN" <?php selected( $options['language'], 'hi_IN' ); ?>>Hindi</option>
							<option value="hr_HR" <?php selected( $options['language'], 'hr_HR' ); ?>>Croatian</option>
							<option value="hu_HU" <?php selected( $options['language'], 'hu_HU' ); ?>>Hungarian</option>
							<option value="hy_AM" <?php selected( $options['language'], 'hy_AM' ); ?>>Armenian</option>
							<option value="id_ID" <?php selected( $options['language'], 'id_ID' ); ?>>Indonesian</option>
							<option value="is_IS" <?php selected( $options['language'], 'is_IS' ); ?>>Icelandic</option>
							<option value="it_IT" <?php selected( $options['language'], 'it_IT' ); ?>>Italian</option>
							<option value="ja_JP" <?php selected( $options['language'], 'ja_JP' ); ?>>Japanese</option>
							<option value="ka_GE" <?php selected( $options['language'], 'ka_GE' ); ?>>Georgian</option>
							<option value="km_KH" <?php selected( $options['language'], 'km_KH' ); ?>>Khmer</option>
							<option value="ko_KR" <?php selected( $options['language'], 'ko_KR' ); ?>>Korean</option>
							<option value="ku_TR" <?php selected( $options['language'], 'ku_TR' ); ?>>Kurdish</option>
							<option value="la_VA" <?php selected( $options['language'], 'la_VA' ); ?>>Latin</option>
							<option value="lt_LT" <?php selected( $options['language'], 'lt_LT' ); ?>>Lithuanian</option>
							<option value="lv_LV" <?php selected( $options['language'], 'lv_LV' ); ?>>Latvian</option>
							<option value="mk_MK" <?php selected( $options['language'], 'mk_MK' ); ?>>Macedonian</option>
							<option value="ml_IN" <?php selected( $options['language'], 'ml_IN' ); ?>>Malayalam</option>
							<option value="ms_MY" <?php selected( $options['language'], 'ms_MY' ); ?>>Malay</option>
							<option value="nb_NO" <?php selected( $options['language'], 'nb_NO' ); ?>>Norwegian (bokmal)</option>
							<option value="ne_NP" <?php selected( $options['language'], 'ne_NP' ); ?>>Nepali</option>
							<option value="nl_NL" <?php selected( $options['language'], 'nl_NL' ); ?>>Dutch</option>
							<option value="nn_NO" <?php selected( $options['language'], 'nn_NO' ); ?>>Norwegian (nynorsk)</option>
							<option value="pa_IN" <?php selected( $options['language'], 'pa_IN' ); ?>>Punjabi</option>
							<option value="pl_PL" <?php selected( $options['language'], 'pl_PL' ); ?>>Polish</option>
							<option value="ps_AF" <?php selected( $options['language'], 'ps_AF' ); ?>>Pashto</option>
							<option value="pt_BR" <?php selected( $options['language'], 'pt_BR' ); ?>>Portuguese (Brazil)</option>
							<option value="pt_PT" <?php selected( $options['language'], 'pt_PT' ); ?>>Portuguese (Portugal)</option>
							<option value="ro_RO" <?php selected( $options['language'], 'ro_RO' ); ?>>Romanian</option>
							<option value="ru_RU" <?php selected( $options['language'], 'ru_RU' ); ?>>Russian</option>
							<option value="sk_SK" <?php selected( $options['language'], 'sk_SK' ); ?>>Slovak</option>
							<option value="sl_SI" <?php selected( $options['language'], 'sl_SI' ); ?>>Slovenian</option>
							<option value="sq_AL" <?php selected( $options['language'], 'sq_AL' ); ?>>Albanian</option>
							<option value="sr_RS" <?php selected( $options['language'], 'sr_RS' ); ?>>Serbian</option>
							<option value="sv_SE" <?php selected( $options['language'], 'sv_SE' ); ?>>Swedish</option>
							<option value="sw_KE" <?php selected( $options['language'], 'sw_KE' ); ?>>Swahili</option>
							<option value="ta_IN" <?php selected( $options['language'], 'ta_IN' ); ?>>Tamil</option>
							<option value="te_IN" <?php selected( $options['language'], 'te_IN' ); ?>>Telugu</option>
							<option value="th_TH" <?php selected( $options['language'], 'th_TH' ); ?>>Thai</option>
							<option value="tl_PH" <?php selected( $options['language'], 'tl_PH' ); ?>>Filipino</option>
							<option value="tr_TR" <?php selected( $options['language'], 'tr_TR' ); ?>>Turkish</option>
							<option value="uk_UA" <?php selected( $options['language'], 'uk_UA' ); ?>>Ukrainian</option>
							<option value="vi_VN" <?php selected( $options['language'], 'vi_VN' ); ?>>Vietnamese</option>
							<option value="zh_CN" <?php selected( $options['language'], 'zh_CN' ); ?>>Simplified Chinese (China)</option>
							<option value="zh_HK" <?php selected( $options['language'], 'zh_HK' ); ?>>Traditional Chinese (Hong Kong)</option>
							<option value="zh_TW" <?php selected( $options['language'], 'zh_TW' ); ?>>Traditional Chinese (Taiwan)</option>
						</select>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><label for="scheme">Colour Scheme</label></th>
					<td>
						<select name="fbcomments[scheme]">
							  <option value="light"<?php if ($options['scheme'] == 'light') { echo ' selected="selected"'; } ?>>Light</option>
							  <option value="dark"<?php if ($options['scheme'] == 'dark') { echo ' selected="selected"'; } ?>>Dark</option>
						</select>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><label for="num">Number of Comments</label></th>
					<td><input id="num" type="text" name="fbcomments[num]" value="<?php echo $options['num']; ?>" /> <small style="font-size:12px;">Default is <strong>5</strong>.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="width">Width</label></th>
					<td><input id="width" type="text" name="fbcomments[width]" value="<?php echo $options['width']; ?>" /> <small style="font-size:12px;">Default is <strong>100%</strong>. Keep this to ensure that the comment box is responsive.</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="title">Title</label></th>
					<td><input id="title" type="text" name="fbcomments[title]" value="<?php echo $options['title']; ?>" />  <input type="text" name="fbcomments[titleclass]" value="<?php echo $options['titleclass']; ?>" /> <small style="font-size:12px;">Add a CSS class here to give style according to it.</small></td>
				</tr>
				
			</table>

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

               <div class="pea_admin_box">
			<h3 class="title">Using the Shortcode</h3>
              <table class="form-table">
				<tr valign="top"><td>
<small style="font-size:12px;"><p>Above settings are for automatic insertion of the Facebook Comments box.</p>
<p>To insert the comment box manually in any page or post or template. 
<p>You can add using the shortcode. Enter the given shortcode 
<p><input style="width:104px;" type="text" value="[fbcomments]" onClick="this.setSelectionRange(0, this.value.length);"> 
<p>To enter the shortcode directly into templates using PHP, enter folowing code
<p><input style="width:250px;" type="text"  onClick="this.setSelectionRange(0, this.value.length);" name="code_snippet" value="<?php echo("<?php echo do_shortcode('[fbcomments]'); ?>"); ?>"></p>
</p></small>
				</td>
				</tr>
			</table>
</div>

</div>
      
<div class="fbc_col">
                            <div class="fbc_where">
                                <h2 style="text-align:left;">   
                                    Please support us!
                                </h2>
                                
                              <hr>
                                <div>
                  
              
                        
<div style="font-family: Georgia,&quot;Times New Roman&quot;,serif;font-style:italic;font-size: 18px; margin-top: 10px;">
                                        Our Plugins:
                                    </div>
                                    <div style="margin-top:10px; margin-bottom: 8px;">
 <a href="http://wordpress.org/plugin/facebook-page-like-popup-box/" target="_blank" style="width:90%; margin-top:5px; margin-bottom: 5px; "><strong>Facebook Page Like Popup Box</strong></a>
 Allows you to add facebook page like in popup box easily.
<div style="text-align: center;">
                                        <a class="fbc_btn fbc_btn-success" href="http://wordpress.org/plugin/facebook-page-like-popup-box/" target="_blank" style="width:90%; margin-top:5px; margin-bottom: 5px; ">Download</a>
                                    </div>
                                    </div>
                                    
                                    <div style="margin-top:10px; margin-bottom: 8px;">
 <a href="https://wordpress.org/plugins/new-facebook-comments/" target="_blank" style="width:90%; margin-top:5px; margin-bottom: 5px; "><strong>Facebook Comments</strong></a>
 Allows you to add facebook comments system in your website easily.
<div style="text-align: center;">
                                        <a class="fbc_btn fbc_btn-success" href="https://wordpress.org/plugins/new-facebook-comments/" target="_blank" style="width:90%; margin-top:5px; margin-bottom: 5px; ">Download</a>
                                    </div>
                                    </div>
                                </div>
                                <hr>  
                                
                                
                                <hr>
                                <div>
                                    <div style="font-family: Georgia,&quot;Times New Roman&quot;,serif;font-style:italic;font-size: 18px; margin-top: 10px;">
                                        Please make a donation:
                                    </div>
                                    <div style="margin-top:0px; margin-bottom: 8px;">
<div style="text-align:center;display:block;font-style:italic;font-family: Verdana,sans-serif;color: #FF0066;font-size: 18px;">
<div style="text-align:center;display:block;font-size: 11px;font-style:italic;font-family: Verdana,sans-serif;color: #FF0066;">
Your donation will help us in providing other useful plugins and <strong>support</strong>.</div>  
  <div style="margin-top:5px;">   
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>


                                 
</div>

                                </div>
                                </div>
                                <hr>
                                <div>
                  
              
                        
<div style="font-family: Georgia,&quot;Times New Roman&quot;,serif;font-style:italic;font-size: 18px; margin-top: 10px;">
                                        Visit support for any issues:
                                    </div>
                                    <div style="margin-top:10px; margin-bottom: 8px;">
<div style="text-align: center;">
                                        <a class="fbc_btn fbc_btn-success" href="http://wordpress.org/support/plugin/new-facebook-comments/" target="_blank" style="width:90%; margin-top:5px; margin-bottom: 5px; ">Support!</a>
                                    </div>
                                    </div>
                                    
                                </div>
                                <hr>
                                <div>                  
                                    
                                </div>
                            </div>
                        </div>    

<?php
}

function fbc_add_custom_box() {
    $post_types = get_post_types( '', 'names' );
    $options = get_option('fbcomments');
    if (!isset($options['posts'])) {$options['posts'] = "";}
	if (!isset($options['pages'])) {$options['pages'] = "";}
    foreach ( $post_types as $post_type ) {
        if ( "post" == $post_type ) {
        	if ($options['posts']=='on') {
	            add_meta_box(
	                'fbc_sectionid',
	                __( 'Facebook Comments', 'fbc_singlemeta' ),
	                'fbc_metabox',
	                $post_type,
	                'advanced',
	                'core'
	                );
	        }
        } elseif ( "page" == $post_type) {
        	if ($options['pages']=='on') {
	            add_meta_box(
	                'fbc_sectionid',
	                __( 'Facebook Comments', 'fbc_singlemeta' ),
	                'fbc_metabox',
	                $post_type,
	                'advanced',
	                'core'
	                );
       		}
        } else {
        	if ($options['posts']=='on') {
	            add_meta_box(
	                'fbc_sectionid',
	                __( 'Facebook Comments', 'fbc_singlemeta' ),
	                'fbc_metabox',
	                $post_type,
	                'advanced',
	                'high'
	                );
        	}
        }
    }
}
add_action( 'add_meta_boxes', 'fbc_add_custom_box' );

function fbc_save_postdata( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
        return;
    }
    if ( !isset( $_POST['fbc_noncename'] ) ) {
        return;
    }
    if ( isset( $_POST['fbc_noncename'] ) && !wp_verify_nonce( $_POST['fbc_noncename'], plugin_basename( __FILE__ ) ) ){
        return;
    }
    if ( 'page' == $_POST['post_type'] ){
        if ( !current_user_can( 'edit_page', $post_id ) ){
            return;
        }
    } else {

        if ( !current_user_can( 'edit_post', $post_id ) ){
            return;
        }
    }

	$_disable_fbc_data = sanitize_text_field( $_POST['_disable_fbc'] );
    add_post_meta($post_id, '_disable_fbc', $_disable_fbc_data, true) or
    update_post_meta($post_id, '_disable_fbc', $_disable_fbc_data);

}


add_action( 'save_post', 'fbc_save_postdata' );

function fbc_metabox( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'fbc_noncename' );
  $_disable_fbc = get_post_meta( get_the_ID(), $key = '_disable_fbc', $single = true );
?>
    <input id="_disable_fbc" name="_disable_fbc" type="checkbox" value="on" <?php checked('on', $_disable_fbc); ?> /> <label for="_disable_fbc"><strong>DISABLE</strong> Facebook Comments</label></td>
<?php
}

?>
