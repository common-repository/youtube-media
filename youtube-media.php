<?php
/**
 * @package Youtube Media
 * @author Aaron Trank (99bots)
 * @version 1.0
 */
/*
Plugin Name: Youtube Media
Plugin URI: http://99bots.com/products/plugins/youtube-media/
Description: This plugin adds a "YouTube Media" tab to your video upload options.  This allows you to view your YouTube video options and insert the selected video into your posts or pages without having to leave your blog to find the "id" of your video on youtube.  After enabling this plugin visit <a href="options-general.php?page=youtube-media-tab.php">the settings page</a> and enter your YouTube user id.
Author: Aaron Trank (99bots)
Version: 1.0
Author URI: http://99bots.com/
*/

$youtube_media_username = '99bots';

function check_for_zend_gdata_interfaces() {
  // if the Zend Gdata Interfaces plugin is successfully loaded this constant is set to true
  if (defined('WP_ZEND_GDATA_INTERFACES') && constant('WP_ZEND_GDATA_INTERFACES')) {
    return true;
  }
  // you can also check if the Zend Gdata Interfaces are available on the system
  $paths = explode(PATH_SEPARATOR, get_include_path());
  foreach ($paths as $path) {
    if (file_exists("$path/Zend/Loader.php")) {
	  require_once '$path/Zend/Loader.php';
      define('WP_ZEND_GDATA_INTERFACES', true);
      return true;
    }
  }
  // nothing found, you may advice the user to install the ZF plugin
  define('WP_ZEND_GDATA_INTERFACES', false);
  add_action('admin_notices', 'youtubegallery_need_zend');
}

add_action('plugins_loaded', 'check_for_zend_gdata_interfaces');

function youtubemedia_wp_upload_tabs ($tabs) {

	$newtab = array('youtubemedia' => __('Youtube Media'));
 
    return array_merge($tabs,$newtab);
}
	
add_filter('media_upload_tabs', 'youtubemedia_wp_upload_tabs');

function media_upload_youtubemedia() {
	
	if ( isset($_POST['send']) ) {
		$keys = array_keys($_POST['send']);
		$vid_id = array_shift($keys);
		//$vid_align = $_POST['vid_align'];
		$vid_size_id = "vid_size_$vid_id";
		$vid_size = $_POST[$vid_size_id];
		$vid_width = 212;
		$vid_height = 172;
		if ($vid_size == "thumbnail"){
			// stick with the default
		} else if ($vid_size == "small"){
			$vid_width = 425;
			$vid_height = 344;
		} else if 	($vid_size == "medium"){
			$vid_width = 480;
			$vid_height = 385;
		} 
		$html = '<object width="' . $vid_width . '" height="' . $vid_height . '"><param name="movie" value="http://www.youtube.com/v/' . $vid_id . '&hl=en_US&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $vid_id . '&hl=en_US&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $vid_width . '" height="' . $vid_height . '"></embed></object>';
		
		// Build output
		//if ($image['size'] == "thumbnail") 
		//	$html = "<img src='{$image['thumb']}' alt='$alttext' class='$class' />";
		// Wrap the link to the fullsize image around	
		//$html = "<a $thumbcode href='{$image['url']}' title='$clean_description'>$html</a>";

		//if ($image['size'] == "full") 
		//	$html = "<img src='{$image['url']}' alt='$alttext' class='$class' />";
		
		//if ($image['size'] == "singlepic") 
		//	$html = "[singlepic id=$send_id w=320 h=240 float={$image['align']}]";
			
		//media_upload_nextgen_save_image();
		
		// Return it to TinyMCE
		return media_send_to_editor($html);
	}
	
	return wp_iframe( 'media_upload_youtubemedia_form');
}

add_action('media_upload_youtubemedia', 'media_upload_youtubemedia');

function printVideoEntry($videoEntry) 
{
	$vid_id = $videoEntry->getVideoId();
	$vid_entry = '<object width="212" height="172"><param name="movie" value="http://www.youtube.com/v/' . $vid_id . '&hl=en_US&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $vid_id . '&hl=en_US&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="212" height="172"></embed></object>';
	echo "<td>";
	echo $vid_entry;
	echo "</td>";
	echo '<td><table><tr>';
	//<td class="label"><label for="image[<php echo $picid >][align]"><php esc_attr_e("Alignment"); ></label></td>
	//<td>
	//<input name="image[<php echo $picid >][align]" id="image-align-none-<php echo $picid >" checked="checked" value="none" type="radio" />
	//<label for="image-align-none-<php echo $picid >" class="align image-align-none-label"><php esc_attr_e("None") ;></label>
	//<input name="image[<php echo $picid >][align]" id="image-align-left-<php echo $picid >" value="left" type="radio" />
	//<label for="image-align-left-<php echo $picid >" class="align image-align-left-label"><php esc_attr_e("Left") ;></label>
	//<input name="image[<php echo $picid >][align]" id="image-align-center-<php echo $picid >" value="center" type="radio" />
	//<label for="image-align-center-<php echo $picid >" class="align image-align-center-label"><php esc_attr_e("Center") ;></label>
	//<input name="image[<php echo $picid >][align]" id="image-align-right-<php echo $picid >" value="right" type="radio" />
	//<label for="image-align-right-<php echo $picid >" class="align image-align-right-label"><php esc_attr_e("Right") ;></label>
	//</td>
	//</tr><tr>
		?>
		<th class="label"><label for="vid[<?php echo $vid_id ?>][size]"><span class="alignleft"><?php esc_attr_e("Size") ; ?></span></label>
		</th>
		<td class="field">
			<input name="vid_size_<?php echo $vid_id ?>" id="vid-size-thumb-<?php echo $vid_id ?>" type="radio" checked="checked" value="thumbnail" />
			<label for="vid-size-thumb-<?php echo $vid_id ?>"><?php esc_attr_e("Thumbnail (212x172)") ; ?></label><br>
			<input name="vid_size_<?php echo $vid_id ?>" id="vid-size-small-<?php echo $vid_id ?>" type="radio" value="small" />
			<label for="vid-size-small-<?php echo $vid_id ?>"><?php esc_attr_e("Small (425x344)") ; ?></label><br>
			<input name="vid_size_<?php echo $vid_id ?>" id="vid-size-medium-<?php echo $vid_id ?>" type="radio" value="medium" />
			<label for="vid-size-medium-<?php echo $vid_id ?>"><?php esc_attr_e("Medium (480x385)") ; ?></label>
		</td>
	</tr><tr>	
	<?php
	echo '<td align="right" colspan="2"><input type="submit" value="Insert into Post" name="send[';
	echo $vid_id;
	echo ']" class="button"></td>';
	echo '</tr></table></td>';
  // the videoEntry object contains many helper functions
  // that access the underlying mediaGroup object
  //echo 'Video: ' . $videoEntry->getVideoTitle() . "<br>";
  //echo 'Video ID: ' . $videoEntry->getVideoId() . "<br>";
  //echo 'Updated: ' . $videoEntry->getUpdated() . "<br>";
  //echo 'Description: ' . $videoEntry->getVideoDescription() . "<br>";
  //echo 'Category: ' . $videoEntry->getVideoCategory() . "<br>";
  //echo 'Tags: ' . implode(", ", $videoEntry->getVideoTags()) . "<br>";
  //echo 'Watch page: ' . $videoEntry->getVideoWatchPageUrl() . "<br>";
  //echo 'Flash Player Url: ' . $videoEntry->getFlashPlayerUrl() . "<br>";
  //echo 'Duration: ' . $videoEntry->getVideoDuration() . "<br>";
  //echo 'View count: ' . $videoEntry->getVideoViewCount() . "<br>";
  //echo 'Rating: ' . $videoEntry->getVideoRatingInfo() . "<br>";
  //echo 'Geo Location: ' . $videoEntry->getVideoGeoLocation() . "<br>";
  //echo 'Recorded on: ' . $videoEntry->getVideoRecorded() . "<br>";
  
  // see the paragraph above this function for more information on the 
  // 'mediaGroup' object. in the following code, we use the mediaGroup
  // object directly to retrieve its 'Mobile RSTP link' child
  //foreach ($videoEntry->mediaGroup->content as $content) {
  //  if ($content->type === "video/3gpp") {
  //    echo 'Mobile RTSP link: ' . $content->url . "<br>";
  //  }
  //}
  
  //echo "Thumbnails:<br>";
  //$videoThumbnails = $videoEntry->getVideoThumbnails();

  //foreach($videoThumbnails as $videoThumbnail) {
  //  echo $videoThumbnail['time'] . ' - ' . $videoThumbnail['url'];
  //  echo ' height=' . $videoThumbnail['height'];
  //  echo ' width=' . $videoThumbnail['width'] . "<br>";
  //}

}

function printVideoFeed($videoFeed)
{
  $count = 1;
  foreach ($videoFeed as $videoEntry) {
    echo "<tr>";
    printVideoEntry($videoEntry);
    echo "</tr>";
    $count++;
  }
}

//Boilerplate Code 
function getAndPrintUserUploads($userName)                    
{     
  $yt = new Zend_Gdata_YouTube();
  $yt->setMajorProtocolVersion(2);
  printVideoFeed($yt->getuserUploads($userName));
}

function media_upload_youtubemedia_form() {
	
	//Get the Zend_Gdata_YouTube object
	Zend_Loader::loadClass('Zend_Gdata_YouTube');
	
	//get the navigation header for the media_uploader
	media_upload_header();
$post_id 	= intval($_REQUEST['post_id']);

$form_action_url = site_url( "wp-admin/media-upload.php?type={$GLOBALS['type']}&tab=youtubemedia&post_id=$post_id", 'admin');	

	// Build navigation
	$_GET['paged'] = isset($_GET['paged']) ? intval($_GET['paged']) : 0;
	if ( $_GET['paged'] < 1 )
		$_GET['paged'] = 1;
	$start = ( $_GET['paged'] - 1 ) * 10;
	if ( $start < 1 )
		$start = 0;
		
	?> 
	<form id="youtube-media-form" enctype="multipart/form-data" action="<?php echo esc_attr($form_action_url); ?>" class="media-upload-form type-form validate" action="" method="post">
	<input type="hidden" name="type" value="<?php echo esc_attr( $GLOBALS['type'] ); ?>" />
	<input type="hidden" name="tab" value="<?php echo esc_attr( $GLOBALS['tab'] ); ?>" />
	<input type="hidden" name="post_id" value="<?php echo (int) $post_id; ?>" />

	<table class="tablenav">		
		<?php
		if(get_option("youtube_media_username")) {
			$youtube_media_username = get_option("youtube_media_username");
		} 
		
		$page_links = paginate_links( array(
			'base' => add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'total' => ceil($total / 10),
			'current' => $_GET['paged']
		));
		
		getAndPrintUserUploads($youtube_media_username);
		?>
	</table>
	</form>
	<?php
}

/**
 * Prints an error message in case the Zend GData plugin isn't installed.
 */
function youtubegallery_need_zend() {
  ?>
  <div class='error'>
  <p><strong>
  YouTube Gallery Tab Plugin: The Zend Gdata Framework needs to be
  installed and activated.
  </strong></p>
  </div>
	<?php
}

// Options submenu
add_action('admin_menu', 'youtube_media_options');

// Title of page, Name of option in menu bar, Which function prints out the html
function youtube_media_options() {
	add_options_page(__('YouTube Media Options'), __('YouTube Media'), 5, basename(__FILE__), 'youtube_media_options_page');
}

// HTML Options Page
function youtube_media_options_page() {

	// Default username if none is specified
	global $youtube_media_username;

	// did the user enter a new/changed location?
	if (isset($_POST['youtube_media_username'])) {
		$youtube_media_username = $_POST['youtube_media_username'];
		update_option('youtube_media_username', $youtube_media_username);
		// and remember to note the update to user
		$updated = true;
	}

	// Grab the latest value for the users YouTube Username
	if(get_option('youtube_media_username')) {
		$youtube_media_username = get_option('youtube_media_username');
	} else {
		add_option('youtube_media_username', $youtube_media_username, "My YouTube Username", "yes");
	}

	if ($updated) {
		echo '<div class="updated"><p><strong>Options saved.</strong></p></div>';
	}

	// Print the Options Page w/ form
	?>
	<div class="wrap">
		<h2>YouTube Media Username</h2>
		<form name="form1" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<fieldset class="options">
                    <input id="youtube_media_username" name="youtube_media_username" value="<? echo get_option('youtube_media_username'); ?>" />
			</fieldset>
			<p class="submit">
				<input type="submit" name="update_youtube_media_username" value="Update Options &raquo;" />
			</p>
	  	</form>
  	</div>

<?php

}
?>