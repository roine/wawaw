<?php
use Orm\Model;

class Model_Ajax extends Model
{


	/* ==================================================
	 * Get the the columns name into a table
	 * ================================================== */
	public static function getColumns($table){
		// get all the column into the table
		$query = DB::select('column_name')->from('information_schema.columns')->where_open()
		->where('table_schema', 'fuel_dev')
		->and_where('table_name', $table)
		->where_close()
		->execute()->as_array();

		foreach($query as $k=>$v)
			$columns[$k] = $v['column_name'];
		return $columns;
	}


	/* ===================================================================================================================
	 * Display number of ppl register today(yesterday), this week(last week),this month(last month) in controller welcome
	 * =================================================================================================================== */
	public static function dashboard($tables, $languages){

		foreach($tables as $table){

			if($table == 'all') continue;
			foreach($languages as $lang){

				$language = ($table == 'small_registration') ? 'language' : 'lang';
				$created_at = ($table == 'demoaccount') ? 'regtime' : 'created_at';
				if($table != 'forexblog_ib_registration' && $table != 'fb_home' && $table != 'pay_order_info'){
					$query = DB::query("SELECT
						(SELECT COUNT(*) FROM $table WHERE $language='$lang' AND $created_at >= CURRENT_DATE AND $created_at < CURRENT_DATE + INTERVAL 1 DAY) as day,
						(SELECT COUNT(*) FROM $table WHERE $language='$lang' AND $created_at >= CURRENT_DATE - INTERVAL 1 DAY AND $created_at < CURRENT_DATE) as anteday,
						(SELECT COUNT(*)  FROM $table WHERE $language='$lang' AND WEEK($created_at) = WEEK(CURRENT_DATE)) AS week,
						(SELECT COUNT(*)  FROM $table WHERE $language='$lang' AND WEEK($created_at) = WEEK(CURRENT_DATE)-1) AS anteweek,
						(SELECT COUNT(*) as month FROM $table WHERE $language='$lang' AND MONTH($created_at) = MONTH(CURRENT_DATE)) AS month,
						(SELECT COUNT(*) as month FROM $table WHERE $language='$lang' AND MONTH($created_at) = MONTH(CURRENT_DATE)-1) AS antemonth");
				}
				else{
					$query = DB::query("SELECT
						(SELECT COUNT(*) FROM $table WHERE $created_at >= CURRENT_DATE AND $created_at < CURRENT_DATE + INTERVAL 1 DAY) as day,
						(SELECT COUNT(*) FROM $table WHERE $created_at >= CURRENT_DATE - INTERVAL 1 DAY AND $created_at < CURRENT_DATE) as anteday, 
						(SELECT COUNT(*)  FROM $table WHERE WEEK($created_at) = WEEK(CURRENT_DATE)) AS week,
						(SELECT COUNT(*)  FROM $table WHERE WEEK($created_at) = WEEK(CURRENT_DATE)-1) AS anteweek,
						(SELECT COUNT(*) as month FROM $table WHERE MONTH($created_at) = MONTH(CURRENT_DATE)) AS month,
						(SELECT COUNT(*) as month FROM $table WHERE MONTH($created_at) = MONTH(CURRENT_DATE)-1) AS antemonth");
				}
				// add cached(time in ms) when finished 
				$data[$lang][$table] = $query->execute()->as_array();
			}
		}
		return $data;
	}


	/* ==================================================
	 * display data from one table in customers controller
	 * ================================================== */
	public static function tables($table, $post){
		$sLimit = "";
		$languagesCan = explode(',', $post['language']);
		$languagesCannot = array_diff(array('en','ru','tw','cn'), $languagesCan);


		// fix for small registration which has a different column name
		$language = ($table == 'small_registration') ? 'language' : 'lang';
		$created_at = ($table == 'demoaccount') ? 'regtime' : 'created_at';
		
		$columns = self::getColumns($table);
		// Start fetching the data
		$query = DB::select(DB::expr('SQL_CALC_FOUND_ROWS *'))->from($table);

		// LIMIT
		if (isset($post['iDisplayStart'] ) && $post['iDisplayLength'] != '-1'){
			$query->limit($post['iDisplayLength'])->offset($post['iDisplayStart']);
		}	

		// ORDER
		if (isset($post['iSortCol_0'])){
			for ( $i=0 ; $i<intval( $post['iSortingCols'] ) ; $i++ )
			{
				if ( $post[ 'bSortable_'.intval($post['iSortCol_'.$i]) ] == "true" )
				{
					$query->order_by($columns[ intval( $post['iSortCol_'.$i] ) ], $post['sSortDir_'.$i]);
				}
			}
		}
		
		// Filters
		// global filter
		$filtering = 0;
		if ( isset($post['sSearch']) && $post['sSearch'] != "" )
		{		

			$search = '%'.$post['sSearch'].'%';
			$query->where_open();
			for ( $i=0 ; $i<count($columns) ; $i++ )
			{
				if($i == 0) 
					$query->where($columns[$i], ' LIKE ', $search);
				else 
					$query->or_where($columns[$i], ' LIKE ', $search);
			}
			$query->where_close();
			$filtering = 1;
		}


		// auto filter for languages
		$langTotal = count($languagesCan);
		$langfiltering = 0;
		$and = 0;
		// if the current form as a language filter
		if(isset($columns[$post['langPosition']])){
			foreach($languagesCan as $k => $language){
				$search = '%'.$language.'%';
				// not being filtered neither lang nor others
				if(!$filtering && !$langfiltering){
					$query->where_open();
				}
				// being filtered by others but not lang yet
				else if($filtering && !$langfiltering){
					$query->and_where_open();
					$and = 1;
				}
				// if first iteration
				if(!$k){
					$query->where($columns[$post['langPosition']], ' LIKE ', $search);
				}else{
					$query->or_where($columns[$post['langPosition']], ' LIKE ', $search);
				}
				// if last iteration
				if($langTotal == intval($k)+1){
					if($and){
						$query->and_where_close();
					}
					else
						$query->where_close();
				}
				$langfiltering = 1;
			}
		}
		

		

		// filter for each columns
		for ( $i=0 ; $i<count($columns) ; $i++ )
		{

			if ( isset($post['bSearchable_'.$i]) && $post['bSearchable_'.$i] == "true" && $post['sSearch_'.$i] != '' ){
				$search = '%'.$post['sSearch_'.$i].'%';
				if(in_array($post['sSearch_'.$post['langPosition']], $languagesCannot))
					continue;
				else if (!$filtering)
					$query->where($columns[$i], 'like', $search);
				else
					$query->and_where($columns[$i], 'like', $search);

			$filtering = 1;
			}
		}

		// filter for dates
		$dateFilter = 0;

		if( (isset($post['max']) || isset($post['min'])) && (!empty($post['max']) || !empty($post['min'])) && ($post['max'] !== 'undefined' || $post['min'] !== 'undefined')){
			// post
			if(!isset($post["max"]) || $post['max'] == ''){
				$extreme = $post['min'];
				$mesure = '>=';
			}
			// ante
			if(!isset($post["min"]) || $post['min'] == ''){
				$extreme = $post['max'];
				$mesure = '<=';
			}
			// echo $post['min'];
			// range
			if(isset($post['min']) && isset($post['max']) && $post['min'] != '' && $post['max'] != ''){
				$extreme = array($post['min'], $post['max']);
				$mesure = 'between';
			}

			if (!$filtering)
				$query->where($created_at, $mesure, $extreme);
			else
				$query->and_where($created_at, $mesure, $extreme);
			$filtering = 1;
		}

		// extract the rows
		// echo $query;
		$result = $query->execute();
		$row = array();
		foreach($result as $k=>$v)
			foreach($v as $key=>$value)
				$row[$k][] = $value;

		$response = array();
		$filtered = DB::select(DB::expr('FOUND_ROWS() as total'))->execute()->as_array();
		$total = DB::select(DB::expr('COUNT("id") as total'))->from($table)->execute()->as_array();
		$response['sEcho'] = intval($post['sEcho']);
		$response['iTotalRecords'] = $total[0]['total'];
		$response['iTotalDisplayRecords'] = $filtered[0]['total'];
		$response['sColumns'] = implode(', ', $columns);
		$response['aaData'] = $row;
		
		return $response;
	}


	/* =========================================================
	 * Display data from all tables in the controller customers
	 * ========================================================= */
	public static function allTables($post){
		// have to do the lang for all
		$sIndexColumn = "id";

		$languagesCan = explode(',', $post['language']);
		$languagesCannot = array_diff(array('en','ru','tw','cn'), $languagesCan);

		// to work each table must have same amount of columns
		$important_data[] = array('columns' => array( 'fullname', 'country', 'state', 'telephone', 'mobile', 'email', 'lang', 'null', '"Introducing Brokers"', 'created_at'), 'table' => 'ib');
		$important_data[] = array('columns' => array('fullname', 'country', 'state', 'telephone', 'mphone', 'email', 'lang', 'null', '"White Label"', 'created_at'), 'table' => 'whitelabel');
		$important_data[] = array('columns' => array('fullname', 'country', 'state', 'telephone', 'mphone', 'email', 'lang', 'null', '"Senior Partners"', 'created_at'), 'table' => 'seniorpartner');
		$important_data[] = array('columns' => array( 'fullname', 'country', 'state', 'telephone', 'mphone', 'email', 'lang', 'null', '"Franchise Scheme"', 'created_at'), 'table' => 'franchisescheme');
		$important_data[] = array('columns' => array( 'CONCAT_WS(", ",firstname, lastname)', 'country', 'city', 'telephone', 'mphone', 'email', 'lang', 'null', '"Callback"', 'created_at'), 'table' => 'callback');
		$important_data[] = array('columns' => array( 'name', 'country', 'city', 'phone', 'mphone', 'email', 'lang', 'null', '"Inquiry"', 'created_at'), 'table' => 'inquiry');
		$important_data[] = array('columns' => array('fullname', 'country', 'state', 'phone', 'mphone', 'email', 'language', 'null', '"Small Registration"', 'created_at'), 'table' => 'small_registration');
		$important_data[] = array('columns' => array('CONCAT_WS(", ",firstname, lastname)', 'null', 'null', 'phone', 'null', 'email', '"en"', 'null', '"Forex Blog"', 'created_at'), 'table' => 'forexblog_ib_registration');
		$important_data[] = array('columns' => array('CONCAT_WS(", ",firstname, lastname)', 'country', 'state', 'phone', 'null', 'email', 'lang', 'null','"Promotions"', 'created_at'), 'table' => 'promotions');
		$important_data[] = array('columns' => array('fullname', 'country', 'CONCAT_WS(", ",province, city)', 'phone', 'mphone', 'email', 'lang', 'null', '"Video Conference"', 'created_at'), 'table' => 'videoconference');
		$important_data[] = array('columns' => array('realName', 'Country', 'CONCAT_WS(", ",State, City)', 'Phone', 'null', 'Email', 'lang', 'PlatformShow', '"Demo Account"', 'regtime'), 'table' => 'demoaccount');
		$important_data[] = array('columns' => array('name', 'null', 'location', 'phone', 'null', 'email', '"en"', 'null', '"Facebook"', 'created_at'), 'table' => 'fb_home');
		$important_data[] = array('columns' => array('name', 'null', 'null', 'telephone', 'null', 'email', '"cn"', 'null', '"Pay Order"', 'created_at'), 'table' => 'pay_order_info');
		$important_data[] = array('columns' => array('name', 'null', 'null', 'null', 'mphone', 'email', 'lang', 'null', '"CMG"', 'created_at'), 'table' => 'cmginfo');

		$aColumns = array('id', 'fullName', 'country', 'city', 'telephone', 'mobile', 'email', 'language', 'platform', 'type', 'created_at');

		$query = 'SELECT SQL_CALC_FOUND_ROWS * FROM(';
		foreach($important_data as $k => $v){
			$query .= ' SELECT ';
			array_unshift($v['columns'], 'id');
				foreach($v['columns'] as $key => $value){
					$query .= $value.' as '.$aColumns[$key];
					if(count($v['columns']) - 1 != $key)
						$query .= ', ';
				}
					
				$query .= ' FROM '.$v['table'].'';
			if($k != count($important_data)-1)
				$query .= ' UNION';
		}
		$query .= ') AS t';
		// date filter
		$where = '';
		if( (isset($post['max']) || isset($post['min'])) && (!empty($post['max']) || !empty($post['min'])) && ($post['max'] !== 'undefined' || $post['min'] !== 'undefined')){
			// post
			if ($where != '')
				$where .= ' AND';
			else
				$where .= ' WHERE';
			if(!isset($post["max"]) || $post['max'] == ''){
				$where .= ' t.created_at >= "'.$post['min'].'"';
			}
			// ante
			if(!isset($post["min"]) || $post['min'] == ''){
				$where .= ' t.created_at <= "'.$post['max'].'"';
			}
			// range
			if(isset($post['min']) && isset($post['max']) && $post['min'] != '' && $post['max'] != ''){
				$where .= ' t.created_at BETWEEN "'.$post['min'].'" AND "'.$post['max'].'"';
			}
		}

		// filter for columns
		if ( isset($post['sSearch']) && $post['sSearch'] != "" ){
			if($where != ''){
				$where .= ' AND';
			}
			else{
				$where .= ' WHERE';
			}
			$search = '%'.$post['sSearch'].'%';
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				$where .= ' t.'.$aColumns[$i].' LIKE \''.$search.'\'';
				if($i != count($aColumns) - 1)
					$where .= ' OR';
			}
		}

		foreach($languagesCan as $language){
			if($where != '' && !$filtering){
					$where .= ' AND (';
			}
			else if($where != '' && $filtering){
				$where .= ' OR';
			}
			else{
				$where .= ' WHERE (';
			}
			$filtering = 1;
			$where .= ' t.'.$aColumns[$post['langPosition']].' LIKE \'%'.$language.'%\'';
		}
		if(count($languagesCan) > 0)
			$where .= ' )';

		// filter by columns
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($post['bSearchable_'.$i]) && $post['bSearchable_'.$i] == "true" && $post['sSearch_'.$i] != '' ){
				$search = '%'.$post['sSearch_'.$i].'%';
				if(count($languagesCannot) > 0 && in_array($post['sSearch_'.$post['langPosition']], $languagesCannot))
					continue;
				if($where != ''){
					$where .= ' AND';
				}
				else{
					$where .= ' WHERE';
				}
				$where .= ' t.'.$aColumns[$i].' LIKE \''.$search.'\'';
			}

		}

		// order
		$order = '';
		if (isset($post['iSortCol_0'])){
			for ( $i=0 ; $i<intval( $post['iSortingCols'] ) ; $i++ )
			{
				if ( $post[ 'bSortable_'.intval($post['iSortCol_'.$i]) ] == "true" )
				{
					$order .= ' ORDER BY t.'.$aColumns[intval($post['iSortCol_'.$i])].' '.$post['sSortDir_'.$i];
				}
			}
		}

		// LIMIT
		$limit = '';
		if (isset($post['iDisplayStart'] ) && $post['iDisplayLength'] != '-1'){
			$limit .= ' LIMIT '.$post['iDisplayLength'].' OFFSET '.$post['iDisplayStart'];
		}
		// get all data
		$row = array();
		$result = DB::query($query.$where.$order.$limit, \DB::SELECT)->execute()->as_array();
		foreach($result as $k=>$v)
			foreach($v as $key=>$value)
				$row[$k][] = $value;

		$filtered = DB::select(DB::expr('FOUND_ROWS() as total'))->execute()->as_array();


		foreach($important_data as $value){
			$aTables[] = $value['table'];
		}
		$query = 'SELECT '.implode('+', $aTables).' AS total FROM';

		foreach($aTables as $k => $value){
			$query .= " (SELECT COUNT(*) as $value FROM $value) AS ".substr($value, 0, 2).$k;
			if($k != count($aTables) - 1)
				$query .= ' ,';
		}
		// echo DB::query($query);
		$total = DB::query($query)->execute()->as_array();
		// echo $query;

		$response['sEcho'] = intval($post['sEcho']);
		$response['aaData'] = $row;
		$response['sColumns'] = implode(', ', $aColumns);
		$response['iTotalRecords'] = $total[0]['total'];
		$response['iTotalDisplayRecords'] = $filtered[0]['total'];
		return $response;
	}


	/* ==================================================
	 * Delete the data in the controller customers
	 * ================================================== */
	public static function deleteData($row){
	
		$result = DB::delete($row['table'])->where('id', $row['id'])->execute();
		return 1;

	}


	/* ==================================================
	 * upddte the data in the controller customers
	 * ================================================== */
	public static function updateData($details, $table){
		$columns = self::getColumns($table);
		$columnId = $details['columnId'];
		$columnName = $columns[$columnId];
		$value = $details['value'];
		$id = $details['id'];
		$query = DB::update($table)->set(array($columnName => $value))->where('id', $id)->execute();
		if($query)
			return $value;	
	}


	public static function messages($user){
		$query =  DB::select(DB::expr('messages.*, sentry_users.username'))->from('messages')
			->join('sentry_users')
			->on('sentry_users.id', '=', 'messages.from')
			->where_open()
			->where('to', intval($user->id))
			->and_where('read', 0)
			->and_where('to_delete', 0)
			->where_close()->execute()->as_array();
		return $query;
	}


	public static function chartsDaily($tables){

		foreach($tables as $k => $table){
			$date_name = ($table['table'] == "demoaccount") ? "regtime" : "created_at";

			$results = DB::select(DB::expr("YEAR($date_name) as year"), 
								DB::expr("MONTH($date_name) as month"),
								DB::expr("DAY($date_name) as day"),
								DB::expr("COUNT(*) as total"))
							->from($table['table'])
							// change to ->group_by('year', 'month', 'day') for daily
							->group_by('year', 'month')
							// ->cached(3600)
							->execute();

			foreach($results as $key => $result){
				// add 000 for javascript date format in milliseconds
				// change to $result['day'].'-'.$result['month'].'-'.$result['year'].' 00:00:00'; for daily
				$date = '1-'.$result['month'].'-'.$result['year'].' 00:00:00';
				date_default_timezone_set('UTC');
				$data[$k][$key][0] = (strtotime($date) * 1000) - (strtotime('02-01-1970 00:00:00') * 1000);
				$data[$k][$key][1] = intval($result['total']);

			}

			$series[$k]['data'] = $data[$k];
			$series[$k]['name'] = $table['CleanName'];

		}
		
		return $series;
	}
}