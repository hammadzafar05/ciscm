<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 8/3/2018
 * Time: 5:31 PM
 */

namespace App\V2\Model;


use App\StudentCertificateDownload;
use App\Certificate;
use App\StudentCertificate;
use App\Lib\BaseTable;
use Carbon\Carbon;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class StudentCertificateTable extends BaseTable
{
	protected $tableName = 'student_certificates';
	//protected $primary = 'student_certificate_id';
	
	public function addStudentEntry($studentId,$certificateId){
		StudentCertificateDownload::create([
			'student_id'=>$studentId,
			'certificate_id'=>$certificateId
		]);
		
		$certificate = Certificate::find($certificateId);
		$student = \App\Student::find($studentId);
		
		//check if student has record
		$row = StudentCertificate::where('student_id',$studentId)->where('certificate_id',$certificateId)->first();
		if($row){
			return $row;
		}
		
		$trackingNo = Carbon::parse($certificate->created_at)->format('y').'-'.$student->id.'-'.$certificate->id;
		
		$row  = StudentCertificate::where('tracking_number',$trackingNo)->first();
		if($row){
			return $row;
		}
		
		$studentCertificate  = StudentCertificate::create([
			'student_id' => $studentId,
			'certificate_id' => $certificateId,
			'tracking_number' => $trackingNo
		]);
		
		return $studentCertificate;
	}
	
	
	public function searchStudents($filter)
	{
		$select = new Select($this->tableName);
		
		$select->join($this->getPrefix().'students',$this->tableName.'.student_id='.$this->getPrefix().'students.id',['mobile_number']);
		$select->join($this->getPrefix().'users',$this->getPrefix().'students.user_id='.$this->getPrefix().'users.id',['name','email','picture','last_name','enabled','last_seen', 'billing_country_id']);
		
		$filter = addslashes(trim($filter));
		//$select->where('(student.first_name LIKE \'%'.$filter.'%\' OR student.last_name LIKE \'%'.$filter.'%\' OR student.email LIKE \'%'.$filter.'%\')');
		$select->where("MATCH ({$this->getPrefix()}users.name,{$this->getPrefix()}users.last_name,{$this->getPrefix()}users.email) AGAINST ('$filter' IN NATURAL LANGUAGE MODE) OR tracking_number='$filter'");
		
		
		$paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
		$paginator = new Paginator($paginatorAdapter);
		return $paginator;
		
	}
	
}
