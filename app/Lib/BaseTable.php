<?php

namespace App\Lib;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Adapter\Adapter;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Db\Metadata\Metadata;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilter;

use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use Laminas\Db\Sql\Select;

abstract class BaseTable implements InputFilterAwareInterface{
	protected  $tableName = '';
	public $tableGateway ;
	protected $serviceLocator;
	protected  $primary = 'id';
	protected $adapter;
	protected  $inputFilter;
	public $db;
	public $config;
	public $cache;
    protected $accountId = false;
    protected $prefix;
    protected $timeStamp = true;


	/**
	 * Constructor sets service locator and prepares tableGateway
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	function __construct() {


        $serviceLocator = $GLOBALS['serviceManager'];


		$this->setServiceLocator($serviceLocator);

		$dbAdapter =  $this->serviceLocator->get(Adapter::class);

		//add prefix to table name
        $prefix = \Illuminate\Support\Facades\DB::getTablePrefix();
        $this->tableName = $prefix.$this->tableName;
        $this->prefix = $prefix;

		$this->adapter = $dbAdapter;
		$gateWay = new TableGateway($this->tableName, $dbAdapter);
		$this->tableGateway = $gateWay;

        try {
            $this->tableGateway->getAdapter()->driver->getConnection()->execute("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        }
        catch (\Exception $ex){
      //      dd($ex->getMessage());
        }


        //add default primary

        $this->primary= 'id';


/*        if(Auth::user() && Auth::user()->can('access','global_resource_access'))
        {
            define('GLOBAL_ACCESS',true);
        }
        else{
            define('GLOBAL_ACCESS',false);
        }*/

	}

	public function getTableName(){
	    return $this->tableName;
    }

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator= $serviceLocator;
	}

	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}

	public function getPrefix(){
	    return $this->prefix;
    }

	public function detectPrimary()
	{
		if (null === $this->primary) {

			$metadata = new Metadata($this->adapter);
			$constraints = $metadata->getTable($this->tableName)->getConstraints();

			foreach ($constraints AS $constraint) {
				if ($constraint->isPrimaryKey()) {
					$primaryColumns = $constraint->getColumns();
					$this->primary = $primaryColumns[0];
				}
			}


		}


	}

	public function getPrimary()
	{
		if (!isset($this->primary)) {
			$this->detectPrimary();
		}

		return $this->primary;
	}

	public function setPrimary($primary)
	{
		$this->primary = $primary;
	}

	public function getRecord($id)
	{
		$primary = $this->getPrimary();
        if($this->accountId && !GLOBAL_ACCESS){
            $row= $this->tableGateway->select(array($primary=>$id,'admin_id'=>ADMIN_ID))->current();
        }
        else{
            $row= $this->tableGateway->select(array($primary=>$id))->current();
        }


		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function deleteRecord($id)
	{
		$primary = $this->getPrimary();
        if($this->accountId && !GLOBAL_ACCESS){
            $total = $this->tableGateway->delete(array($primary=>$id,'admin_id'=>ADMIN_ID));
        }
        else{
            $total = $this->tableGateway->delete(array($primary=>$id));
        }

		return $total;
	}

	public function getRecords()
	{
        if($this->accountId && !GLOBAL_ACCESS){
            return $this->tableGateway->select(['admin_id'=>ADMIN_ID]);
        }
        else{
            return $this->tableGateway->select();
        }


	}

  public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
    	if (!$this->inputFilter)
    	{
    		$inputFilter = new InputFilter();
    		$factory = new Factory();




    	}
    }

    public function addRecord($data)
    {
        if($this->accountId){
            $data['admin_id']= ADMIN_ID;
        }

        if ($this->timeStamp){
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
        }

    	$data = $this->clearInvalid($data);
    	$this->tableGateway->insert($data);
    	$id = $this->tableGateway->getLastInsertValue();
    	return $id;
    }

    public function update($data,$id){
        if ($this->timeStamp){
            $data['updated_at'] = Carbon::now()->toDateTimeString();
        }
        if($this->accountId && !GLOBAL_ACCESS){
              $this->tableGateway->update($data,array($this->primary=>$id,'admin_id'=>ADMIN_ID));
        }
        else{
            $this->tableGateway->update($data,array($this->primary=>$id));
        }


    }

    public function saveRecord($data)
    {

    	$data = $this->clearInvalid($data);
    	$id = (int)$data[$this->primary];
    	if ($id == 0) {
    		//add record
    		$id = $this->addRecord($data);
    	}
    	else {
    		//update record
    		if ($this->getRecord($id)) {
                if ($this->timeStamp){
                    $data['updated_at'] = Carbon::now()->toDateTimeString();
                }
    			$this->tableGateway->update($data, array($this->primary => $id));
    		} else {
    			throw new \Exception('Record id does not exist');
    		}
    	}

    	return $id;
    }

    /**
     * Clears the submit button from form fields
     * @param array $data
     * @return array
     */
    public function clearInvalid($data)
    {
    	unset($data['submit']);
    	return $data;
    }



    public function getPaginatedRecords($paginated=false,$id=null)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');

        if($this->accountId && !GLOBAL_ACCESS){
            $select->where(['admin_id'=>ADMIN_ID]);
        }

        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
	
    public function getTotal()
    {
        if($this->accountId && !GLOBAL_ACCESS){
            $rowset = $this->tableGateway->select(['admin_id'=>ADMIN_ID]);
        }
        else{
            $rowset = $this->tableGateway->select();
        }

    	$total = $rowset->count();
    	return $total;
    }

	public function getLanguage($language_id=null)
	{
		return 1;
	}

	public function setRegistry($registry)
	{
		$this->registry = $registry;
		foreach ($this->registry->getData() as $key=>$value)
		{

			$this->$key = $value;
		}
	}

    public function recordExists($id){
        $total = $this->tableGateway->select([$this->primary=>$id])->count();
        if(empty($total)){
            return false;
        }
        else{
            return true;
        }
    }

    public function getLimitedRecords($limit)
    {
        $select = new Select($this->tableName);

        $select->limit($limit);
        if($this->accountId && !GLOBAL_ACCESS){
            $select->where(['admin_id'=>ADMIN_ID]);
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getGateway(){
        return $this->tableGateway;
    }


}

?>
