<?php
/*
Plugin Name: Let's Mix Shortcode
Plugin URI: http://www.installedforyou.com/letsmix-shortcode
Description: Let's Mix Shortcode. Usage in your posts: [letsmix]http://www.letsmix.com/mix/MIXID/MIX_NAME[/letsmix] .
Version: 1.0
Author: Jeff Rose (jeff@installedforyou.com) & Ralph van Troost
Author URI: http://www.installedforyou.com
*/

/*
Let's Mix Shortcode (Wordpress Plugin)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

add_filter('mce_external_plugins', "lms_register");
add_filter('mce_buttons', 'lms_add_button', 0);

add_shortcode( "letsmix", "lms_letsmix_shortcode" );

/**
 * Handle the Let's Mix shortcode
 *
 * @param string $args Optional. The arguments from the shortcode
 * @param string $content Optional. The content from between the shortcode tags.
 *
 * @return string The <object> for the Let's Mix object tags
 */
function lms_letsmix_shortcode( $args = '', $content = '' ) {

    /*
    * If $args and $content are empty just return
    */
    if ( empty( $args ) && empty( $content ) ){
        return;
    } else {

        /*
        * If args is empty, grab $content instead
        */
        if ( empty( $args ) ){
            $args = html_entity_decode( $content, ENT_QUOTES );
        } else {
            /*
            * $args isn't empty, so let's process it
            */
            extract( shortcode_atts( array(
                                            'url' => '',
                                            'mix_id' => '',
                                            'size' => '',
                                            'autostart' => '',
                                            'width' => '',
                                            'height' => '',
                                            'tracklist' => ''
                                            ),
                                    $args )
                    );
        }

        /*
        * If we don't have a $url yet, but $content is not a number
        * we assume that $content is the url
        */
        if ( $url == "" and ( !is_numeric( $content ) ) ){
            $url = $content;
        } elseif ( is_numeric( $content ) ){
            /*
            * If $content is a number, it should be the mix_id
            */
            $mix_id = absint( $content );
        }

        /*
        * Still nothing? Give up and head back
        */
        if ( $url == "" && $mix_id == "" ){
            return;
        }

        /*
        * We have a URL, so let's parse it and grab the mix_id
        * but do a little checking along the way.
        * Element 1 has to be "mix"
        * Element 2 has to be a number
        */
        if ( $url != "" ){
            $url = parse_url( $url );
            if ( $url ){
                $split_url = explode( "/", $url['path'] );

                if ( !$split_url[1] == "mix" ){
                    return;
                } else {
                    if ( is_numeric( $split_url[2] ) ){
                        $mix_id = absint( $split_url[2] );
                    } else {
                        return;
                    }
                } // $url seems valid
            }
        } // $url is not empty
        $final_embed = lms_build_embed( $mix_id, $size, $autostart, $width, $height, $tracklist );
    } // $args & $content are not empty

    return $final_embed;
}

/**
 * Build the actual embed string
 *
 * @param int $mix_id The ID of the mix, extracted from the URL or the content
 * @param string $size The content from between the shortcode tags.
 * @param int $autostart Either a 0 (no) or 1 (yes) for the AutoPlay param
 *
 * @return string The <object> for the Let's Mix object tags
 */
function lms_build_embed( $mix_id, $size, $autostart, $width, $height, $tracklist ){

    $get_url = "http://www.letsmix.com/mix/$mix_id?format=xml";

    libxml_use_internal_errors(true);

    if( !$xml = simpleXML_load_file( $get_url, "SimpleXMLElement", LIBXML_NOCDATA ) ){
        return "The mix could not be found. Please verify the mix ID or mix URL.";
    }

    $size = ucfirst( $size );

    if ( $size . $height . $width == "" ){
        $size = 'Big';
    }

    if ( $height != "" && $width == "" ) {
		$width = '420';
	}

    if ( $height == "" && $width != "" ) {
		$height = '320';
	}

    switch ( $size ) {
        case "Wide":
            $width = 420;
            $height = 140;
            break;
        case "Tall":
            $width = 175;
            $height = 140;
            break;
        case "Big":
            $width = 420;
            $height = 320;
            break;
    }

    if ( !empty( $size ) ){
        $format = "&format=$size";
    }

    if ( ( $autostart == 'yes' ) || ( $autostart == 'on' ) || ( $autostart == '1' ) || ( $autostart === 'true' ) ){
        $autostart = 1;
    } else {
        $autostart = 0;
    }

    if ( $tracklist == "" ){
        $tracklist = 'yes';
    }

    $embed = '
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' . $width . '" height="' . $height . '">
    <param name="wmode" value="transparent"></param>
    <param name="movie" value="http://www.letsmix.com/content/flash/player/letsmixplayer.1.6.swf" />
    <param name="flashvars" value="mixID=' . $mix_id . $format . '&autoStart=' . $autostart . '" />
    <object type="application/x-shockwave-flash" data="http://www.letsmix.com/content/flash/player/letsmixplayer.1.6.swf" width="' . $width . '" height="' . $height . '">
        <param name="wmode" value="transparent"></param>
        <param name="flashvars" value="mixID=' . $mix_id . $format . '&autoStart=' . $autostart . '" />
        <a href="http://www.letsmix.com/mix/' . $mix_id . '/' . $mix_name . 'source=embed" target="_blank">
                <img src="http://www.letsmix.com/mix/artwork/' . $mix_id . '" alt="' . $xml->metadata->name . ' from ' . $xml->owner->username . ' at Letsmix.com">
        </a>
    </object>
</object>
<p>
	<a href="' . $xml->url . '?source=embed" target="_blank">' . $xml->metadata->name . '</a> from <a href="' . $xml->owner->url . '" target="_blank">' . $xml->owner->username . '</a> at <a href="http://www.letsmix.com?source=embed" target="_blank">Letsmix.com</a>.
</p>';
    $tracklist = strtolower( $tracklist );
    if ( ( $tracklist == 'yes' ) || ( $tracklist == 'on' ) || ( $tracklist == '1' ) || ( $tracklist === 'true' ) ){
        $embed .= "\n<p>Tracklist:</p>\n";
        foreach ( $xml->metadata->tracks->track as $track ){
            $embed .= $track["order"] . ".&nbsp;" . $track["artist"] . " - " . $track["title"] . "<br />\n";
        }
    }
    return $embed;
}

function lms_add_button($buttons){
    array_push($buttons, "separator", "lms_shortcode");
    return $buttons;
}

function lms_register($plugin_array){
    $url .= plugins_url( 'lms_shortcode.js', __FILE__ );

    $plugin_array["lms_shortcode"] = $url;
    return $plugin_array;
}

add_action('admin_print_scripts', 'lms_add_quicktag');
function lms_add_quicktag() {
	wp_enqueue_script(
		'lms_quicktag',
		plugin_dir_url(__FILE__) . 'lms_quicktag.js',
		array('quicktags')
	);
}
?>
