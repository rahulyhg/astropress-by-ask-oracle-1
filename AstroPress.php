<?php
/*
Plugin Name: AstroPress by Ask Oracle
Plugin URI: http://www.aheadzen.com
Description: Hands-down, easiest way to embed horoscopes and astrology charts on your blog or website, we got a plugin for you!. New widget - "AstroPress Widget" added and short code eg.- "[astropress width=500 height=600]"
Version: 1.0.1
Author: Ask Oracle Team
Author URI: http://ask-oracle.com/

Copyright: © 2014-2015 ASK-ORACLE.COM
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/*******************************
Plugin Init Function
****************************/
add_action('init','astropress_init');
function astropress_init()
{
}

/**Widget Initialize**/
add_action( 'widgets_init','widget_astropress_init');

/*******************************
Widget Init Function
****************************/
function widget_astropress_init()
{
	register_widget('astropress_widget');
}

/*******************************
Widget Function
****************************/

if(!class_exists('astropress_widget')){
	class astropress_widget extends WP_Widget {
		function astropress_widget() {
		//Constructor
			$widget_ops = array('classname' => 'widget astropress_widget', 'description' => 'AstroPress Widget to embed horoscopes' );		
			$this->WP_Widget('astropress','AstroPress Widget', $widget_ops);
		}
		function widget($args, $instance) {
		// prints the widget
			extract($args, EXTR_SKIP);
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$w = empty($instance['w']) ? '600' : apply_filters('widget_w', intval($instance['w']));
			$h = empty($instance['h']) ? '600' : apply_filters('widget_h', intval($instance['h']));
			if(!$w){$w=600;}
			if(!$h){$h=600;}
			echo $before_widget;
			if($title){ echo $before_title.$title.$after_title; }
			echo astropress_iframe_code($w,$h);
			echo $after_widget;				
		}
			
		function update($new_instance, $old_instance) {
		//save the widget
			$instance = $new_instance;		
			return $instance;
		}
		
		function form($instance) {
		//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'w' => '600', 'h' => '600') );		
			$title = strip_tags($instance['title']);
			$w = strip_tags($instance['w']);
			$h = ($instance['h']);			
			?>
			<p><label for="<?php  echo $this->get_field_id('title'); ?>"><?php _e('Widget Title','aheadzen');?>: <input class="widefat" id="<?php  echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label>
			</p>  
			<p><label for="<?php  echo $this->get_field_id('w'); ?>"><?php _e('Display Width in pixels','aheadzen');?>: <input class="widefat" id="<?php  echo $this->get_field_id('w'); ?>" name="<?php echo $this->get_field_name('w'); ?>" type="text" value="<?php echo esc_attr($w); ?>" /></label>
			<small><?php _e('eg: default & maximum display screen size : 600 (in pixcels)','aheadzen');?></small>
			</p>     
			<p><label for="<?php echo $this->get_field_id('h'); ?>"><?php _e('Display Height in pixels','aheadzen');?>: 
			<input class="widefat" id="<?php  echo $this->get_field_id('h'); ?>" name="<?php echo $this->get_field_name('h'); ?>" type="text" value="<?php echo esc_attr($h); ?>" /></label>
			<small><?php _e('eg: default : 600, you can adjust as per you want.','aheadzen');?></small>
			</p>
			<?php
		}
	}
}


/*******************************
shotcode :: [astropress]
****************************/
function astropress_shortcode($atts) {
	$atts['shortcode']=1;
	$w = intval($atts['width']);
	$h = intval($atts['height']);
	$arg = array();
	
	if($atts['bgcolor']){$arg['bgcolor'] = $atts['bgcolor'];}
	if($atts['textcolor']){$arg['textcolor'] = $atts['textcolor'];}
	if($atts['linkcolor']){$arg['linkcolor'] = $atts['linkcolor'];}
	if(!$w){$w=650;}
	if(!$h){$h=750;}
	$content = astropress_iframe_code($w,$h,$arg);
	return $content;
}
add_shortcode('astropress', 'astropress_shortcode');

function astropress_iframe_code($w=600,$h=600,$arg=array())
{
	//return '<iframe src="http://www.ask-oracle.com/embed/" style="max-width:'.$w.'px;" width="'.$w.'px" height="'. $h.'px" frameborder="0" ></iframe>';
	$url = "http://localhost/arpit/ask-oracle-app-new/";
	if($arg){
		foreach($arg as $key=>$val)
		{
			$arg1[] = $key.'='.urlencode($val);
		}
		$url.= '?'.implode('&',$arg1);
	}
	return '<iframe src="'.$url.'" style="max-width:'.$w.'px;" width="'.$w.'px" height="'. $h.'px" frameborder="0" ></iframe>';
}