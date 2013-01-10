<?php

class Controller_Charts extends Controller_Base
{



	public function action_index(){
		if(!Sentry::user()->has_access('charts_monthly')){
			Session::set_flash('error', 'You don\'t have access to the charts');
			Response::redirect('');
		}
		$data['id'] = '';
		
		$this->template->js = Asset::js(array(
			'plugins.js',
			'mylibs/jquery.ba-resize.js',
			'mylibs/jquery.easing.1.3.js',
			'mylibs/jquery.ui.touch-punch.js',
			'libs/date.js',
			'script.js',
			'mylibs/highcharts.js',
			'mylibs/HighCharts/exporting.js',
			'charts.js'
		));

		$this->template->css = Asset::css(array(
			'plugin.charts.css',
		));
		$this->template->title = 'Charts';
		$this->template->h2 = 'Monthly Subscription in all the forms';
		$this->template->content = View::forge('charts/view');
	}
}