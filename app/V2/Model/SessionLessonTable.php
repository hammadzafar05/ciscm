<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/10/2017
 * Time: 4:15 PM
 */

namespace App\V2\Model;


use App\Course;
use App\Lib\BaseTable;
use Illuminate\Support\Carbon;
use Laminas\Db\Sql\Select;

class SessionLessonTable extends BaseTable {
	
	protected $tableName = 'course_lesson';
	//protected $primary = 'course_lesson_id';
	protected $timeStamp = FALSE;
	
	
	public function clearSessionLessons($id) {
		$this->tableGateway->delete(['course_id' => $id]);
	}
	
	public function getSessionRecords($id, $type = null) {
		$select = new Select($this->tableName);
		$select->where(['course_id' => $id])
			->join($this->getPrefix() . 'lessons', $this->tableName . '.lesson_id=' . $this->getPrefix() . 'lessons.id', ['name', 'type', 'picture', 'description', 'test_required', 'test_id'])
			->order($this->tableName . '.sort_order');
		if ($type) {
			$select->where(['type' => $type]);
		}
		$rowset = $this->tableGateway->selectWith($select);
		$rowset->buffer();
		return $rowset;
	}
	public function getClassRecords($course_id, $class_id, $type = null) {
		$select = new Select($this->tableName);
		$select->where(['course_id' => $course_id,'lesson_id' => $class_id])
			->join($this->getPrefix() . 'lessons', $this->tableName . '.lesson_id=' . $this->getPrefix() . 'lessons.id', ['name', 'type', 'picture', 'description', 'test_required', 'test_id'])
			->order($this->tableName . '.sort_order');
		if ($type) {
			$select->where(['type' => $type]);
		}
		$rowset = $this->tableGateway->selectWith($select);
		$rowset->buffer();
		return $rowset->get();
	}
	
	public function getSessionRecordsDateSorted($id) {
		$select = new Select($this->tableName);
		$select->where(['course_id' => $id])
			->join($this->getPrefix() . 'lessons', 'course_lesson.lesson_id=' . $this->getPrefix() . 'lessons.id', ['name', 'type', 'picture', 'content'])
			->order($this->tableName . '.lesson_date asc');
		$rowset = $this->tableGateway->selectWith($select);
		$rowset->buffer();
		return $rowset;
	}
	
	
	public function getLessonDate($sessionId, $lessonId) {
		
		$row = $this->tableGateway->select(['course_id' => $sessionId, 'lesson_id' => $lessonId])->current();
		return $row->lesson_date;
	}
	
	public function getSessionLessonRecord($sessionId, $lessonId) {
		$row = $this->tableGateway->select(['course_id' => $sessionId, 'lesson_id' => $lessonId])->current();
		return $row;
	}
	
	public function getSessionLessonWithSortOrder($sessionId, $sortOrder) {
	
	}
	
	public function lessonExists($sessionId, $lessonId) {
		$total = $this->tableGateway->select(['course_id' => $sessionId, 'lesson_id' => $lessonId])->count();
		if (empty($total)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function getUpcomingLessons($days) {
		$upperLimit = strtotime('tomorrow midnight') - 1;
		
		$timestamp = strtotime("+$days day");
		
		$timeLimit = mktime(23, 59, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
		
		$upperLimit = Carbon::createFromTimestamp($upperLimit)->toDateTimeString();
		$timeLimit = Carbon::createFromTimestamp($timeLimit)->toDateTimeString();
		$upperLimit = "'{$upperLimit}'";
		$timeLimit = "'{$timeLimit}'";
		$select = new Select($this->tableName);
		$select->where(['lesson_date < ' . $timeLimit, 'lesson_date > ' . $upperLimit])
			->join($this->getPrefix() . 'lessons', $this->getPrefix() . 'course_lesson.lesson_id=' . $this->getPrefix() . 'lessons.id', ['name', 'type'])
			->join($this->getPrefix() . 'courses', $this->tableName . '.course_id=' . $this->getPrefix() . 'courses.id', ['venue', 'course_name' => 'name'])
			->order('lesson_date');
		$rowset = $this->tableGateway->selectWith($select);
		$rowset->buffer();
		return $rowset;
		
	}
	
	public function getStartedLessons($type = 'c') {
		$timestamp = time();
		$upperLimit = strtotime('yesterday midnight');
		
		$timeLimit = mktime(23, 59, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
		
		$timeLimit = Carbon::createFromTimestamp($timeLimit)->toDateTimeString();
		$upperLimit = Carbon::createFromTimestamp($upperLimit)->toDateTimeString();
		$upperLimit = "'{$upperLimit}'";
		$timeLimit = "'{$timeLimit}'";
		$select = new Select($this->tableName);
		$select->where(['lesson_date < ' . $timeLimit, 'lesson_date > ' . $upperLimit])
			->join($this->getPrefix() . 'lessons', $this->getPrefix() . 'course_lesson.lesson_id=' . $this->getPrefix() . 'lessons.id', ['name', 'type'])
			->join($this->getPrefix() . 'courses', $this->tableName . '.course_id=' . $this->getPrefix() . 'courses.id', ['course_name' => 'name'])
			->order('lesson_date');
		if ($type) {
			$select->where([$this->getPrefix() . 'lessons.type' => $type]);
		}
		$rowset = $this->tableGateway->selectWith($select);
		$rowset->buffer();
		return $rowset;
	}
	
	public function arrangeSortOrdersDateSorted($id) {
		$rowset = $this->getSessionRecordsDateSorted($id);
		$count = 1;
		foreach ($rowset as $row) {
			$this->update(['sort_order' => $count], $row->course_lesson_id);
			$count++;
		}
	}
	
	
	public function arrangeSortOrders($id) {
		$rowset = $this->getSessionRecords($id);
		$count = 1;
		
		foreach (Course::find($id)->lessons()->orderBy('pivot_sort_order')->get() as $row) {
			$row->pivot->sort_order = $count;
			$row->pivot->save();
			///  $this->update(['sort_order'=>$count],$row->id);
			$count++;
		}
	}
	
	public function getPreviousLessonInSession($sessionId, $id, $type = null) {
		$row = $this->getSessionLessonRecord($sessionId, $id);
		$sortOrder = $row->sort_order;
		$select = new Select($this->tableName);
		$select->where(['course_id' => $sessionId])
			->where("course_lesson.sort_order < '$sortOrder'")
			->order('sort_order desc')
			->join($this->getPrefix() . 'lessons', $this->tableName . '.lesson_id=' . $this->getPrefix() . 'lessons.id', ['name', 'type'])
			->limit(1);
		if ($type) {
			$select->where(['type' => $type]);
		}
		
		$rowset = $this->tableGateway->selectWith($select);
		if ($rowset->count() == 0) {
			return FALSE;
		} else {
			return $rowset->current();
			
		}
	}
	
	public function getNextLessonInSession($sessionId, $id, $type = null) {
		$row = $this->getSessionLessonRecord($sessionId, $id);
		$sortOrder = $row->sort_order;
		$select = new Select($this->tableName);
		$select->where(['course_id' => $sessionId])
			->where("course_lesson.sort_order > '$sortOrder'")
			->order('sort_order')
			->join($this->getPrefix() . 'lessons', $this->tableName . '.lesson_id=' . $this->getPrefix() . 'lessons.id', ['name', 'type'])
			->limit(1);
		if ($type) {
			$select->where(['type' => $type]);
		}
		
		$rowset = $this->tableGateway->selectWith($select);
		if ($rowset->count() == 0) {
			return FALSE;
		} else {
			return $rowset->current();
			
		}
	}
	
	public function getLastSortOrder($courseId) {
		$select = new Select($this->tableName);
		$select->order('sort_order desc');
		$select->limit(1);
		$row = $this->tableGateway->selectWith($select)->current();
		return $row;
		
	}
}
