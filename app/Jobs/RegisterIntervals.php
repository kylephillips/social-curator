<?php namespace SocialCurator\Jobs;
/**
* Add custom intervals for crons
*/
class RegisterIntervals {

	public function __construct()
	{
		add_filter('cron_schedules', array($this, 'addCronIntervals'));
	}

	/**
	* Add Custom Cron Interval
	*/
	public function addCronIntervals($schedules)
	{
		$schedules['everyminute'] = array(
			'interval' => 60,
			'display' => __('Once every minute', 'socialcurator')
		);
		$schedules['everyfiveminutes'] = array(
			'interval' => 5*60,
			'display' => __('Once every 5 minutes', 'socialcurator')
		);
		return $schedules;
	}

}