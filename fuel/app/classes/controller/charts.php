<?php

class Controller_Charts extends Controller_Base
{



	public function action_index(){
		$data['id'] = '';
		
		$this->template->js = Asset::js(array(
			'plugins.js',
			'mylibs/jquery.ba-resize.js',
			'mylibs/jquery.easing.1.3.js',
			'mylibs/jquery.ui.touch-punch.js',
			'script.js',
			'mylibs/highcharts.js',
			'mylibs/HighCharts/exporting.js',
			'charts.js'
		));

		$this->template->css = Asset::css(array(
			'plugin.charts.css',
		));
		$this->template->title = 'Charts';
		$this->template->h2 = 'Daily Subscrition in all the form';
		$this->template->content = View::forge('charts/view');
	}
}