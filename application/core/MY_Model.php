<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (BASEPATH.'core/Model.php');
class MY_Model extends CI_Model
{
	protected $namespace = null;
	protected  $forcerefresh = FALSE;
	protected  $selfhack = FALSE;
	protected $db = NULL;
	var $CI;

//	private $redis;

//	function use_database($group) {
//		$this->db = $this->CI->load->database($group, TRUE);
//	}

	function __construct()
	{
//		log_message('debug', "DEE - MY_Model Constructor Begin: Calling parent::__construct");
		parent::__construct();
//		log_message('debug', "DEE - MY_Model Constructor Begin: Calling parent::__construct FINISHED");
		$this->CI =& get_instance();
//		$this->forcerefresh = $this->CI->forcerefresh;
//		$this->selfhack = $this->CI->selfhack;
		$this->CI->load->database();
		$this->db = $this->CI->db;
/*
		try
		{
			$this->redis = new Redis();
			$this->redis->open(REDIS_HOST, REDIS_PORT);
		}
		catch(Exception $e)
		{
			show_error($e->getMessage(), $e->getCode());
		}
// */
	}
	
	private function runQuery($sql)
	{
		$result = $this->db->query($sql);
		$this->namespace = null;
		return $result;
	}
	
	protected function ExecuteScalar($query, $params = array(), $type = null, $expire = 21600)
	{
		$sql = $this->db->compile_binds($query, $params);
//		if($this->selfhack) {
//			echo "\nSQL: \n{$sql}";
//		}
/*
		if(!$this->forcerefresh && ($result = $this->_get('query.'.md5($sql))) !== FALSE)
		{
			log_message('debug', "DEE - Using Cache: ".'query.'.md5($sql));
			return $result;
		}
		log_message('debug', "DEE - Running Query: ".$sql);
//*/
		
		$exec = $this->runQuery($sql);
		
		if($exec->num_rows() > 0)
		{
			
			$row = $exec->row_array(0);
			$result = array_shift($row);
/*
			if(!empty($type)) settype($result,$type);
				$this->_set('query.'.md5($sql), $result, $expire);
				//__MemSetter('query.'.md5($sql),$result, $expire);
//*/
			return $result;

		}
		return null;
	}
	
	protected function First($query, $params = array(), $expire = 21600)
	{
		$exec = $this->Fetch($query,$params, array('expire'=>$expire));
		if(count($exec) > 0)
		{
			return $exec[0];
		}
		return null;
	}	
	
	protected function FirstOrDefault($query, $params = array(), $default = null, $expire = 21600)
	{
		$exec = $this->First($query,$params, $expire);
		if($exec != null)
		{
			return $exec;
		}
		return $default;
	}

	protected function SingleOrDefault($query, $params = array(), $default = null, $expire = 21600)
	{
		$exec = $this->Single($query,$params, $expire);
		if($exec != null)
		{
			return $exec;
		}
		return $default;
	}

	protected function Single($query, $params = array(), $expire = 21600)
	{
		$exec = $this->Fetch($query, $params, array('expire'=>$expire,'row'=>1,'offset'=>0));
		if(count($exec) == 1)
		{
			$result = $exec[0];
			return $result;
		}
		return null;
	}

	protected function Fetch($query, $params = array(), $config = array())
	{

		$row = (int)$this->CI->input->get('row', TRUE, 250);
		$offset = (int)$this->CI->input->get('offset', TRUE, 0);
		
			if(isset($config['row']))
				$row = (int)$config['row'];
			if(isset($config['offset']))
				$offset = (int)$config['offset'];
//			if(isset($config['expire']))
//				$expire = $config['expire'];

		if((int) $row !== -1)
			$query .= " LIMIT {$offset}, {$row}";
//		log_message('debug', "DEE - FETCHing Queries");
		$sql = $this->db->compile_binds($query, $params);
//	echo $sql;
/*
		if(!$this->forcerefresh && ($result = $this->_get('query.'.md5($sql))) !== FALSE)
		{
			log_message('debug', "DEE - Using Cache: ".'query.'.md5($sql));
			return $result;
		}
		log_message('debug', "DEE - Running Query: ".$sql);
//*/
		$exec = $this->runQuery($sql);
		if($exec->num_rows() > 0)
		{
			$result = $exec->result();
//			$this->_set('query.'.md5($sql),$result, $expire);
			return $result;
		}
//		log_message('debug', "DEE - Fetching result: EMPTY");
		return array();
	}
/*
	private function _set($key, $value, $expire)
	{
		$value = json_encode(array('value'=>$value));
		if($expire != FALSE && is_numeric($expire))
			$this->redis->setex(REDIS_HASH.$key.'Expire', $expire, (time()+$expire));
		else
			$this->redis->set(REDIS_HASH.$key.'Expire', 'forever');
		return $this->redis->hSet(REDIS_HASH, $key, $value);
	}

	private function _get($key)
	{
		$checkExp = $this->redis->get(REDIS_HASH.$key.'Expire');
		if(!empty($checkExp) && ($checkExp >= time() || $checkExp == 'forever')) {
			$result = json_decode($this->redis->hGet(REDIS_HASH, $key));
			$result = !empty($result->value) ? $result->value : FALSE;
			return $result;
		} else {
			return FALSE;
		}
	}

	function _falsify($value) {
		if(empty($value))
		{
			if(is_array($value))
				return '<-FALSE_ARRAY->';
			elseif(is_object($value))
				return '<-FALSE_OBJECT->';
			elseif(is_null($value))
				return '<-FALSE_NULL->';
			elseif(is_bool($value))
				return '<-FALSE_BOOLEAN->';
			elseif(is_numeric($value))
			{
				if(is_int($value) || $value==0)
					return '<-FALSE_INTEGER->';
				elseif(is_long($value))
					return '<-FALSE_LONG->';
			}
			else
				return $value;
		}
		return $value;
	}
	function _defalsify($value) {
		if($value == '<-FALSE_ARRAY->') {
			return array();
		} else if($value == '<-FALSE_OBJECT->') {
			return new stdclass;
		} else if($value == '<-FALSE_BOOLEAN->') {
			return false;
		} else if($value == '<-FALSE_NULL->') {
			return null;
		} else if($value == '<-FALSE_INTEGER->') {
			return 0;
		} else if($value == '<-FALSE_LONG->') {
			return (float)0.0;
		} else {
			return $value;
		}
	}
// */
}

/*
class Mongo_Model extends MY_Model
{
	private $mongo;
	protected $db;
	protected $collection;
	protected $cursor;
	private $_db;
	private $_collection;
	private $_cursor;
	private $_where;
	private $_sort = array();
	private $_limit = 0;
	private $_skip = 0;
	private $_orwhere;
	private $_infields = array();
	private $_orinfields = array();
	private $_lastquery = array();

	public function __construct(){
		$this->Mongo_Model();
	}

	public function Mongo_Model(){
		parent::__construct();
		try {
			$this->load->config('mongodb');
			$host = config_item('mongo_host');
			$post = config_item('mongo_port');
			$this->mongo = new Mongo("mongodb://{$host}:{$post}");
		} catch( Exception $e) {
			show_error('MongoHost need to adjust: ' . $e->getMessage());
		}
	}

	protected function selectDB($db){
		if(empty($this->mongo)) show_error('MongoDB not connected correctly: #01');
		$this->_db = $db;
		$this->db = $this->mongo->{$db};
	}

	protected function selectCollection($collection){
		if(empty($this->mongo)) show_error('MongoConnection not connected correctly: #02');
		if(empty($this->db)) show_error('MongoDB not connected correctly: #03');
		$this->_collection = $collection;
		$this->collection = $this->db->{$collection};
	}

	protected function autoIncrement(){
		$_coll = $this->_collection;
		$this->selectCollection('auto_increment');
		$this->cursor = $this->collection->findAndModify(array(
			'query' => array('_id'=>$_coll),
			'update'=> array('$inc' => array('id'=>1)),
			'upsert'=> TRUE,
			'new'   => TRUE
		));
		$current = $this->cursor->current();
		$_id = $current['id'];
		$this->selectCollection($_coll);
		return $_id;
	}

	protected function autoDecrement(){
		$_coll = $this->_collection;
		$this->selectCollection('quantity_decrement');
		$this->cursor = $this->collection->findAndModify(array(
			'query' => array('_id'=>$_coll),
			'update'=> array('$inc' => array('qty'=>-1)),
			'new'   => TRUE
		));
		$current = $this->cursor->current();
		$qty = $current['qty'];
		$this->selectCollection($_coll);
		return $qty;
	}

	private function _insertIn($field, $value, $or = FALSE){
		$in = $or?'_orinfields':'_infields';
		if(array_search($field, $this->{$in}) === FALSE) {
			if(isset($this->{$this->_or($or)}[$field])) {
				$temp = $this->{$this->_or($or)}[$field];
				$this->{$this->_or($or)}[$field] = array(
					'$in' => array($temp, $value)
				);
				$this->{$in}[] = $field;
			} else {
				$this->{$this->_or($or)}[$field] = $value;
			}
		} else {
			$this->{$this->_or($or)}[$field]['$in'][] = $value;
		}
	}

	private function _or($or = FALSE){
		return $or?'_orwhere':'_where';
	}

	public function orwhere($where, $value = NULL){
		$this->where($where, $value, TRUE);
	}

	public function where($where, $value = NULL, $or = FALSE){
		if(is_array($where)) {
			foreach($where as $k => $v){
				if(is_string($k)) {
					$this->_insertIn($k, $v, $or);
					$this->{$this->_or($or)}[$k] = $v;
				}
			}
		} elseif(is_string($where) && ! is_null($value)) {
			$this->_insertIn($where, $value, $or);
		}
	}

	public function gt($field, $value, $or = FALSE){
		$this->_insertIn($field, array('$gt'=>$value), $or);
//		$this->{$this->_or($or)}[$field] = array('$gt'=>$value);
	}
	public function lt($field, $value, $or){
		$this->_insertIn($field, array('$lt'=>$value), $or);
//		$this->{$this->_or($or)}[$field] = array('$lt'=>$value);
	}
	public function gte($field, $value, $or){
		$this->_insertIn($field, array('$gte'=>$value), $or);
//		$this->{$this->_or($or)}[$field] = array('$gte'=>$value);
	}
	public function lte($field, $value, $or){
		$this->_insertIn($field, array('$lte'=>$value), $or);
//		$this->{$this->_or($or)}[$field] = array('$lte'=>$value);
	}

	private function _execQuery(){
		$collection = $this->collection;
		$where = $this->_where;
		$where['$or'] = $this->_orwhere;
		$this->cursor = $collection->find($where)
			->sort($this->_sort);
		$this->cursor
			->limit($this->_limit)
			->skip($this->_skip);
		$this->_cursor['queries'][] = $this->_cursor['lastquery'] = array(
			$this->_db => array(
				$this->_collection => array(
					'where' => $where,
					'sort'  => $this->_sort,
					'limit'  => $this->_limit,
					'skip'  => $this->_skip,
				)
			)
		);

	}
	public function sort($field, $ascending = 'ASC'){
		$order = $ascending == 'DESC' || empty($ascending)?MongoCollection::DESCENDING:MongoCollection::ASCENDING;
		$this->_sort[$field] = $order;
	}
	public function limit($limit){
		$this->_limit = $limit;
	}
	public function skip($skip){
		$this->_skip = $skip;
	}

	public function doQuery(){
		$this->_execQuery();
		return new MongoReturn($this->cursor);
	}

	public function get_last_query(){
		return $this->_cursor['lastquery'] ;
	}

	public function get_queries(){
		return $this->_cursor['queries'] ;
	}

	protected function auto_increment($db, $coll){
		$_db = $this->_db;
		$_coll = $this->_collection;
		$this->selectDB($db);
		$this->selectCollection('auto_increment');
		$cur = $this->collection->findAndModify(
			array(
				'query'=>array('_id'=>$coll),
				'update'=>array('$inc'=>array('id'=>1)),
				'upsert'=>TRUE,
				'new'=>TRUE,
				'fields'=>array('id'=>1, '_id'=>0)
			)
		);
		$current = $cur->current();
		$this->selectDB($_db);
		$this->selectCollection($_coll);

		return $current['id'];

	}
}

class MongoReturn
{
	private $cursor;
	private $_cursor;
	private $result = NULL;
	private $result_array = NULL;
	public function __construct($c){
		$this->MongoReturn($c);
	}

	public function MongoReturn($c){
		try {
			if(empty($c)) throw new Exception('Cursor is GONE!');
		} catch( Exception $e) {
			show_error(json_encode(array('error'=> array('id'=>0, 'message'=>$e, 'data'=>$c))), 501);
		}
		$this->cursor = $c;
		$this->_process();
		// copy
		$this->_cursor = $c;
		$this->cursor = $c;
	}

	public function count_result(){
		try {
			$count = $this->cursor->count();
		} catch (Exception $e){
			if($this->cursor !== $this->_cursor) {
				$this->cursor = $this->_cursor;
				$count = $this->count_result();
			} else {
				show_error(json_encode(array('id'=>0, 'message'=>'Error in executing to cursor')), 500);
				return;
			}
//			$this->cursor = $this->_cursor;
		}
		return $count;
	}

	private function _process(){
		try {
			$result = array();
			while($this->cursor->hasNext()){
				$_result = $this->cursor->current();
				if(!empty($_result))
					$result[] = $_result;
				$this->cursor->next();
				if( ! $this->cursor->hasNext()){
					$_result = $this->cursor->current();
					if(!empty($_result))
						$result[] = $_result;
				}
			}
			$this->result = $result;
			$this->result_array = json_decode(json_encode($result), TRUE);
		} catch(Exception $e) {
			if($this->cursor !== $this->_cursor) {
				$this->cursor = $this->_cursor;
				$this->_process();
			} else {
				show_error(json_encode(array('id'=>0, 'message'=>'Error in executing to cursor')), 500);
			}
		}
	}

	public function get($array = FALSE){
		return $array?$this->get_array():$this->get_object();
	}

	public function get_raw(){
		return $this->_cursor;
	}

	public function get_object(){
		if($this->result == NULL && $this->count() > 0){
			$this->_process();
		}
		return $this->result;
	}
	public function get_array(){
		if($this->result == NULL && $this->count() > 0){
			$this->_process();
		}
		return $this->result_array;
	}
}
//*/
