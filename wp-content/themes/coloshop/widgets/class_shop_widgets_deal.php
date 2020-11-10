<?php 
class Widgets_Deal extends WP_Widget{

	public $wpb_id = 'wpb_waiting';
	public $wpb_name = 'Waiting';
	public $wpb_description = 'One-click countdowns';
	
	function __construct(){
		parent::__construct(
			$this->wpb_id,
			__($this->wpb_name, 'waiting'),
			array('description' => __($this->wpb_description, 'waiting'))
		);
	}
	
	public function widget($args, $instance){
		$link = $instance['link'] ? $instance['link'] : '';
		
		echo $args['before_widget']; ?>
		<?php echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title']; ?>
		<div class="pbc-wrapper">
			<?php echo WPB_Waiting::output( $instance['key'] ); ?>
		</div>
		<div class="ofthe_week_button"><a class="red_button deal_ofthe_week_button" href="<?php echo  $instance['link']; ?>">shop now</a></div>
		
		<?php echo $args['after_widget'];
		//var_dump($instance['link']);
	} 
	
	public function form($instance){
		$title = empty($instance['title']) ? '' : $instance['title'];
		$key   = empty($instance['key']) ? '' : $instance['key'];
		$link  = empty($instance['link']) ? '' : $instance['link'];

		?>
		
		<div>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'waiting'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</div>
		
		<div>
			<label for="<?php echo $this->get_field_id('key'); ?>"><?php _e('Name', 'waiting'); ?>:</label>
			<select class="widefat" id="<?php echo $this->get_field_id('key'); ?>" name="<?php echo $this->get_field_name('key'); ?>">
				<?php foreach(WPB_Waiting::downs() as $d): ?>
					<option value="<?php echo $d->name; ?>" <?php echo $d->name == $key ? 'selected' : ''; ?>><?php echo $d->name; ?></option>
				<?php endforeach; ?>
			<select/>
		</div>
		
		<div>
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link', 'waiting'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" value="<?php echo $link; ?>" />
		</div>

		<?php
	}
	
	public function update($n, $o){
		$instance = array();
		$instance['title'] = $n['title'] ? $n['title'] : '';
		$instance['key'] = $n['key'] ? $n['key'] : 'Please select one';
		$instance['link'] = $n['link'] ? $n['link'] : '';
		//var_dump($instance);
		return $instance;
	}
	
}