<?php
/*
Plugin Name: Category and Tag Specific Widgets
Plugin URI: http://www.dynamick.it/category-and-tag-widgets-display-wordpress-widgets-on-specific-tag-or-category-4513.html
Description: Display widgets based on current category or tag. Based on the "Category Widget" of The HungryCoder
Version: 1.0.
Author: Dynamick
Author URI: http://www.dynamick.it
*/



class Category_And_Tag_Specific_Widgets extends WP_Widget {
	function Category_And_Tag_Specific_Widgets() {
		  parent::WP_Widget(false, $name = 'Category Tag Specific Widgets',array('description'=>'Display widgets based on current category or Tag.'));

	}

	function form($instance) {
		global $wpdb;
		 $title = esc_attr($instance['title']);
		 $content = esc_attr($instance['content']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

            <p><label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Content:'); ?><textarea class="widefat" id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>" rows="10"><?php echo $content; ?></textarea>
		</label></p>


			<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Show in:'); ?>
			<select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
			<option value="">[Select a category]</option>
			<?php
			$cats = get_terms('category',array('hide_empty'=>0,'parent'=>0,'count'=>1));
			//post_tag
			//get_the_tag_list('<ul><li>','</li><li>','</li></ul>');
			foreach ($cats as $cat){
				print_r($cat);
				if($instance['category']==$cat->term_id){
					echo '<option value="'.$cat->term_id.'" selected="selected">'.$cat->name.'</option>'.PHP_EOL;

				} else {
					echo '<option value="'.$cat->term_id.'">'.$cat->name.'</option>'.PHP_EOL;
				}
			}
			?>
			</select>
			</label></p>


			<p><label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e('Show in:'); ?>
			<select id="<?php echo $this->get_field_id('post_tag'); ?>" name="<?php echo $this->get_field_name('post_tag'); ?>">
			<option value="">[Select a tag]</option>
			<?php
			$tags = get_terms('post_tag',array('hide_empty'=>0,'parent'=>0,'count'=>1));
			//post_tag
			//get_the_tag_list('<ul><li>','</li><li>','</li></ul>');
			foreach ($tags as $tag){
				print_r($tag);
				if($instance['post_tag']==$tag->term_id){
					echo '<option value="'.$tag->term_id.'" selected="selected">'.$tag->name.'</option>'.PHP_EOL;

				} else {
					echo '<option value="'.$tag->term_id.'">'.$tag->name.'</option>'.PHP_EOL;
				}
			}
			?>
			</select>
			</label></p>



			<p><label for="<?php echo $this->get_field_id('hide_in_subcat'); ?>"><?php _e('Hide in child categories:'); ?>
			<input type="checkbox" name="<?php echo $this->get_field_name('hide_in_subcat'); ?>" id="<?php echo $this->get_field_id('hide_in_subcat')?>" value="1" <?php echo ($instance['hide_in_subcat']) ? 'checked="checked"' : '';?> />
			</label></p>
			<p><label for="<?php echo $this->get_field_id('hide_title'); ?>"><?php _e('Hide Title:'); ?>
			<input type="checkbox" name="<?php echo $this->get_field_name('hide_title'); ?>" id="<?php echo $this->get_field_id('hide_title')?>" value="1" <?php echo ($instance['hide_title']) ? 'checked="checked"' : '';?> />
			</label></p>

        <?php
    }


	function update($new_instance, $old_instance) {
		return $new_instance;

	}

	function widget($args, $instance) {
		 extract( $args );
		if(!$instance['hide_title']){
        	$title = apply_filters('widget_title', $instance['title']);
		}


        wp_reset_query();

        //check if hide_in_child category is on or off
        if(isset($instance['hide_in_subcat'])){
        	$hide_in_child = true;
        } else {
        	$hide_in_child = false;
        }
       $current_tags_id = $this->get_current_tags();
        
       $current_cat_id = $this->get_current_category();

       $parent_cat_id = $this->get_category_topparent($current_cat_id);

            //echo $parent_cat_id. " - ". $instance['category']."<br>";
            //echo $parent_cat_id. " - ". $current_cat_id."<br>";
            
       //we won't the widget is the $instance['category'] is not parent_cat_id
       if($instance['category']!="" AND $parent_cat_id != $instance['category']) return false;

       //don't show this widget if $hide_in_child is true and this is children category
       if($hide_in_child AND ($current_cat_id != $parent_cat_id)) return false;

       // match the tags
       if ($instance['post_tag']!="" AND $current_tags_id!=$instance['post_tag']) {return false;}



        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                  <?php echo $instance['content']; ?>
              <?php echo $after_widget; ?>
        <?php
    }

    function get_current_tags(){
    	global $post, $wp_query;;

    	return $tag = get_query_var('tag_id');
    }

    function get_current_category(){
    	global $post, $wp_query;;

    	return $cat = get_query_var('cat');
    }

	function get_category_parent($catid){
		global $wpdb;
		$parent = $wpdb->get_var("SELECT `parent` FROM {$wpdb->term_taxonomy} WHERE `term_id`='$catid'");
		return $parent;

	}

	function get_category_topparent($catid){
		$parent = $this->get_category_parent($catid);
		if($parent>0){
			 return $this->get_category_topparent($parent);
		}
		return $catid;

	}

}
add_action('widgets_init', create_function('', 'return register_widget("Category_And_Tag_Specific_Widgets");'));

?>