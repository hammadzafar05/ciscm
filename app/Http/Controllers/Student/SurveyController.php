<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;

use Dompdf\Dompdf;
use App\Lib\UtilityFunctions;
use Illuminate\Support\Facades\Auth;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use App\Course;
use App\Student;
use App\SurveyResponse;
use App\Survey;
use App\V2\Model\AttendanceTable;
use App\V2\Model\SessionLessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\SurveyResponseOptionTable;
use App\V2\Model\SurveyResponseTable;
use App\V2\Model\SurveyOptionTable;
use App\V2\Model\SurveyQuestionTable;
use App\V2\Model\SurveyTable;

class SurveyController  extends Controller
{


    use HelperTrait;



    private function getLayout(){
         if (Auth::check() && Auth::user()->role_id==2){
             return 'layouts.student';
         }
         else{
             return 'layouts.survey';
         }
    }

    public function survey(Request $request,$hash){

        $output = [];
        $hash = trim($hash);
        $survey = Survey::where('hash',$hash)->where('enabled',1)->first();
        $surveyTable = new SurveyTable();

        if(!$survey){
            return $this->showMessage(__lang('invalid-survey'));

        }

        //check if survey is private
        if($survey->private==1){
            if(!$this->studentIsLoggedIn()){
                flashMessage(__lang('private-survey-error'));
                return redirect()->route('login');
            }

            //check if student is enrolled into any session of the survey
            $rowset = $surveyTable->getStudentSurveyRecords($this->getId(),$survey->id);
            $total = $rowset->count();


            if(empty($total)){
                return $this->showMessage(__lang('no-survey-permission'));
            }

        }


        if(request()->isMethod('post')){

            $data = [];

            if($this->studentIsLoggedIn()){
                $data['student_id'] = $this->getId();
            }

            $surveyResponse = $survey->surveyResponses()->create($data);

            $data = request()->all();

            foreach($data as $key=>$value){
                    if(preg_match('#question_#',$key)){
                        $surveyResponse->surveyOptions()->attach($value);
                    }

            }

            return redirect()->route('survey.complete');

        }

        $output['pageTitle'] = __lang('survey').': '.$survey->name;
        $output['survey'] = $survey;
        $output['loggedIn'] = $this->studentIsLoggedIn();
        $output['totalQuestions'] = $survey->surveyQuestions()->count();
        $output['layout'] = $this->getLayout();
        return view('student.survey.survey',$output);

    }

    public function complete(Request $request){

        return view('student.survey.complete',['pageTitle'=>__lang('survey-submitted'),'layout'=>$this->getLayout()]);
    }


    public function showMessage($message){
        $viewModel = viewModel('student',__CLASS__,'message',['message'=>$message,'pageTitle'=>__lang('survey')]);
        return $viewModel;
    }


}
