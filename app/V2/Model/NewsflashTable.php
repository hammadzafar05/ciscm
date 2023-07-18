<?php

namespace App\V2\Model;

use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
class NewsflashTable extends BaseTable {

	protected $tableName = 'blog_posts';
	//protected $primary = 'newsflash_id';
    protected $accountId = true;

	public function getNews($limit=5)
	{
		$select = new Select($this->tableName);
		$select->limit($limit);
		$select->order($this->primary.' desc');
        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
        }
		$rowset = $this->tableGateway->selectWith($select);
		return $rowset;
	}

}

?>
