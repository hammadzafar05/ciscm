<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Student;
use App\User;
use App\V2\Model\LessonTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentSessionTable;
use App\V2\Model\TestTable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{

    public function index(Request $request){
	
	    $admin_role = getAdminRole(Auth::user()->id);
	
	    $enrolled_users[] = 0;
	    $SessionTable = new SessionTable();
	    $StudentSessionTable = new StudentSessionTable();
	    $courses = $SessionTable->getPaginatedRecords(false);
	    foreach ($courses as $course) {
		    $students = $StudentSessionTable->getSessionRecords(FALSE, $course->id, TRUE);
		    foreach ($students as $student) {
			    //dd($student);
			    $enrolled_users[] = $student->user_id;
		    }
	    }
		
        $output =[];
        //get total students
        $studentsTable = new StudentSessionTable();
	    if ($admin_role == 'Partner'){
		    $output['totalStudents'] = User::whereIn('id',$enrolled_users)->count();
		    $output['invoices'] = array();
		    $output['latestUsers'] = array();
	    }else {
		    $output['totalStudents'] = User::where('role_id',2)->whereHas('student')->count();
		    $output['invoices'] = Invoice::latest()->limit(10)->get();
		    $output['latestUsers'] = User::latest()->limit(6)->get();
	    }
        

        $sessionTable = new SessionTable();
        $output['totalSessions'] = $sessionTable->getPaginatedRecords(false,null,true,null,null,null,['s','b'],true)->count();
        $output['totalCourses'] = $sessionTable->getPaginatedRecords(false,null,true,null,null,null,'c')->count();

        $lessonTable = new LessonTable();
        $output['totalClasses'] = $lessonTable->getTotal();

        $testTable = new TestTable();
        $output['totalTests'] = $testTable->getActivePaginatedRecords()->count();

        $viewModel = app(StudentController::class)->index($request);
        $output['student'] = $viewModel->getData();
        $output['student']['paginator']->setItemCountPerPage(5);

        $viewModel = app(StudentController::class)->sessions($request);
        $output['session'] = $viewModel->getData();
        $output['session']['paginator']->setItemCountPerPage(5);

        $_GET['replied'] = 0;
        $viewModel = app(DiscussController::class)->index($request);
        $output['discuss'] = $viewModel->getData();
        $output['discuss']['paginator']->setItemCountPerPage(5);




        $months = array_map('getMonthStr', range(-7,0));

        $monthlySales = [];
        $monthlyCount = [];
        $monthlyCompCount = [];
        $monthlyCanCount = [];
        
	
	    if ($admin_role == 'Partner') {
		    $output['todaySales'] = 0;
		    $output['todaySum'] = array();
		
		    $output['weekSales'] = 0;
		    $output['weekSum'] = array();
		
		    $output['monthSales'] = 0;
		    $output['monthSum'] = array();
		
		    $output['yearSales'] = 0;
		    $output['yearSum'] = array();
			
		    $output['monthSaleData'] = json_encode($monthlySales);
		    $output['monthSaleCount'] = json_encode($monthlyCount);
		    $output['monthSaleCompCount'] = json_encode($monthlyCompCount);
		    $output['monthSaleCanCount'] = json_encode($monthlyCanCount);
		    $output['monthList'] = json_encode($months);
	    }else{
		    $output['todaySales'] = Invoice::where('paid',1)->whereDay('created_at', now()->day)->count();
		    $output['todaySum'] = $this->getTotal(Invoice::where('paid',1)->whereDay('created_at', now()->day)->get());
		
		    $output['weekSales'] = Invoice::where('paid',1)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
		    $output['weekSum'] = $this->getTotal(Invoice::where('paid',1)->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get());
		
		    $output['monthSales'] = Invoice::where('paid',1)->where('created_at','>=', Carbon::now()->firstOfMonth()->toDateTimeString())->count();
		    $output['monthSum'] = $this->getTotal(Invoice::where('paid',1)->where('created_at','>=', Carbon::now()->firstOfMonth()->toDateTimeString())->get());
		
		    $output['yearSales'] = Invoice::where('paid',1)->whereYear('created_at', date('Y'))->count();
		    $output['yearSum'] = $this->getTotal(Invoice::where('paid',1)->whereYear('created_at', date('Y'))->get());
			
			
		    foreach(range(-7,0) as $offset){
			    //get the
			    $start= date("Y-m-d", strtotime("$offset months first day of this month"));
			    $end = date("Y-m-d", strtotime("$offset months last day of this month"));
			    $monthlySales[] = Invoice::where('paid',1)->whereDate('created_at','>=', $start)->whereDate('created_at','<=', $end)->sum('amount');
			    $monthlyCount[] = Invoice::where('paid',1)->whereDate('created_at','>=', $start)->whereDate('created_at','<=', $end)->count();
			    $monthlyCompCount[] = Invoice::where('paid',1)->whereDate('created_at','>=', $start)->whereDate('created_at','<=', $end)->count();
			    $monthlyCanCount[] = Invoice::where('paid',1)->whereDate('created_at','>=', $start)->whereDate('created_at','<=', $end)->count();
		    }
			
		    $output['monthSaleData'] = json_encode($monthlySales);
		    $output['monthSaleCount'] = json_encode($monthlyCount);
		    $output['monthSaleCompCount'] = json_encode($monthlyCompCount);
		    $output['monthSaleCanCount'] = json_encode($monthlyCanCount);
		    $output['monthList'] = json_encode($months);
	    }

       

       

        return view('admin.home.index',$output);
    }


    private function getTotal($rowset){
        $total = 0;
        $currency = currentCurrency();
        foreach ($rowset as $row){
            $amount = price($row->amount,$currency->id,true);
            $total += $amount;
        }
        return $total;
    }
}
