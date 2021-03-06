<?php

/**
 * Adds LatestPostsWidget widget.
 */

class LatestPostsWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'wcms18-latestposts-widget', // Base ID
			'WCMS18 Latest Posts', // Name
			[
				'description' => __('A Widget for displaying the latest posts', 'wcms18-latestposts-widget'),
			] // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		// start widget
		echo $before_widget;

		// title
		if (! empty($title)) {
			echo $before_title . $title . $after_title;
		}

		// content
		// this is the code from wcms18-latestposts shortcode plugin
		$posts = new WP_Query([
			'posts_per_page' => $instance['num_posts'],
		]);

		// $output = "<h2>" . esc_html($atts['title']) . "</h2>";
		if ($posts->have_posts()) {
			$output = "<ul>";
			while ($posts->have_posts()) {
				$posts->the_post();
				$output .= "<li>";
				$output .= "<a href='" . get_the_permalink() . "'>";
				$output .= get_the_title();
				$output .= "</a>";

				if ($instance['show_metadata']) {
					$output .= "<small>";
					$output .= " in ";
					$output .= get_the_category_list(', ');
					$output .= " by ";
					$output .= get_the_author();
					$output .= " ";
					$output .= human_time_diff(get_the_time('U')) . ' ago';
					$output .= "</small>";
				}

				$output .= "</li>";
			}
			wp_reset_postdata();
			$output .= "</ul>";
		} else {
			$output .= "No latest posts available.";
		}
		// end code from wcms18-latestposts shortcode plugin
		echo $output;

		// close widget
		echo $after_widget;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) {
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = __('Latest Posts', 'wcms18-latestposts-widget');
		}

		if (isset($instance['num_posts'])) {
			$num_posts = $instance['num_posts'];
		} else {
			$num_posts = 3;
		}

		$show_metadata = isset($instance['show_metadata'])
			? $instance['show_metadata']
			: false;

		?>

		<!-- title -->
		<p>
			<label
				for="<?php echo $this->get_field_name('title'); ?>"
			>
				<?php _e('Title:'); ?>
			</label>

			<input
				class="widefat"
				id="<?php echo $this->get_field_id('title'); ?>"
				name="<?php echo $this->get_field_name('title'); ?>"
				type="text"
				value="<?php echo esc_attr($title); ?>"
			/>
		 </p>
		 <!-- /title -->

		<!-- number of posts to show -->
		<p>
			<label
				for="<?php echo $this->get_field_name('num_posts'); ?>"
			>
				<?php _e('Number of posts to show:'); ?>
			</label>

			<input
				class="widefat"
				id="<?php echo $this->get_field_id('num_posts'); ?>"
				name="<?php echo $this->get_field_name('num_posts'); ?>"
				type="number"
				min="1"
				value="<?php echo $num_posts; ?>"
			/>
		 </p>
		 <!-- /number of posts to show -->

		<!-- show metadata about post -->
		<p>
			<label
				for="<?php echo $this->get_field_name('show_metadata'); ?>"
			>
				<?php _e('Show metadata?'); ?>
			</label>

			<input
				class="widefat"
				id="<?php echo $this->get_field_id('show_metadata'); ?>"
				name="<?php echo $this->get_field_name('show_metadata'); ?>"
				type="checkbox"
				value="1"
				<?php echo $show_metadata ? 'checked="checked"' : ''; ?>
			/>
		 </p>
		 <!-- /show metadata about post -->
	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) {
		$instance = [];

		$instance['title'] = (!empty($new_instance['title']))
			? strip_tags($new_instance['title'])
			: '';

		$instance['num_posts'] = (!empty($new_instance['num_posts']) && $new_instance['num_posts'] > 0)
			? intval($new_instance['num_posts'])
			: 3;

		$instance['show_metadata'] = (!empty($new_instance['show_metadata']));

		return $instance;
	}

} // class LatestPostsWidget
