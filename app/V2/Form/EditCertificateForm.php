<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 6/12/2017
 * Time: 1:15 PM
 */

namespace App\V2\Form;


use App\V2\Model\CertificateLessonTable;
use App\V2\Model\CertificateTable;
use App\V2\Model\SessionLessonTable;

class EditCertificateForm extends CertificateForm {

    public function __construct($name = null,$serviceLocator,$id)
    {
        parent::__construct($name,$serviceLocator);
        $certificateLessonTable = new CertificateLessonTable();
        $certificateTable = new CertificateTable();
        $sessionLessonTable = new SessionLessonTable();
        $row = $certificateTable->getRecord($id);
        $rowset = $sessionLessonTable->getSessionRecords($row->course_id);
/*
        foreach($rowset as $row){
            $this->createCheckbox('lesson_'.$row->lesson_id,$row->lesson_name,$row->lesson_id);
            if($certificateLessonTable->hasLesson($id,$row->lesson_id))
            {
                $this->get('lesson_'.$row->lesson_id)->setValue($row->lesson_id);
            }

        }
*/
        //
        $this->createCheckbox('any_session','search-all-sessions-text',1);
        $this->createHidden('html');
        $this->get('html')->setAttribute('id','html');
    }

}
