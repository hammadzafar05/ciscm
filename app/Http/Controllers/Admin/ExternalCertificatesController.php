<?php

namespace App\Http\Controllers\Admin;

use App\ExternalCertificate;
use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\V2\Model\CountryTable;
use Illuminate\Http\Request;

class ExternalCertificatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_external_certificates');
        $keyword = $request->get('filter');
        $perPage = 25;

        if (!empty($keyword)) {
            $external_certificates = ExternalCertificate::
                where('title',$keyword)
                ->orWhere('tracking_number',$keyword)
                ->paginate($perPage);
        }else {
            $external_certificates = ExternalCertificate::where('type',NULL)->paginate($perPage);
        }

        

        return view('admin.external_certificates.index', compact('external_certificates','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','add_external_certificate');
	    $countryTable = new CountryTable();
	    $countries = $countryTable->getRecords();
        //return view('admin.external_certificates.create',$countries);
	    $output['countries'] = $countries;
	    return view('admin.external_certificates.create',$output);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('access','add_external_certificate');
        $this->validate($request,[
            'title'=>'required'
        ]);

        $requestData = $request->all();
        $external_certificate = ExternalCertificate::create($requestData);

        return redirect('admin/external_certificates')->with('flash_message', __('default.changes-saved'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $this->authorize('access','view_external_certificates');
        $external_certificate = ExternalCertificate::findOrFail($id);
        //dd($external_certificate);

        return view('admin.external_certificates.show', compact('external_certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $this->authorize('access','edit_external_certificate');
        $external_certificate = ExternalCertificate::findOrFail($id);
	
	    $countryTable = new CountryTable();
	    $countries = $countryTable->getRecords();

		
        return view('admin.external_certificates.edit', compact('external_certificate','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->authorize('access','edit_external_certificate');
        $this->validate($request,[
            'title'=>'required'
        ]);

        $requestData = $request->all();

        $external_certificate = ExternalCertificate::findOrFail($id);
        $external_certificate->update($requestData);

        return redirect('admin/external_certificates')->with('flash_message', __('default.changes-saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
     
    //  kabir works========
    public function destroy($id)
    {
        $this->authorize('access','delete_external_certificate');
        ExternalCertificate::destroy($id);

        return redirect('admin/external_certificates')->with('flash_message', __('default.record-deleted'));
    }
    
    public function ambassadorIndex(){
        $this->authorize('access','verification_ambassador');
        $pageTitle="Page For the Ambassador View";
        $getAmbassador=ExternalCertificate::where('type','ambassador')->paginate(20);
        //dd($getAmbassador);
        
         return view('admin.external_certificates.ambassador',compact('pageTitle','getAmbassador'));
    }
    
    public function partnerIndex(){
        $this->authorize('access','verification_partner');
        $pageTitle="Page For the Partner View";
        $getPartner=ExternalCertificate::where('type','partner')->paginate(20);
        //dd('ok');
        return view('admin.external_certificates.partner',compact('pageTitle','getPartner'));
        
    }
    
    public function import_external_certificates(Request $request){
		set_time_limit(86400);

		$output = array();
		if($request->isMethod('post'))
		{
			$post = $request->all();
			$data = $_FILES['file'];
			$file = $data['tmp_name'];
			$file = fopen($file,"r");
			
			$all_rows = array();
			$header = null;
			while ($row = fgetcsv($file)) {
				if ($header === null) {
					$header = $row;
					continue;
				}
				$all_rows[] = array_combine($header, $row);
			}
			$total = 0;
			$failure = 0;

			//loop rows
			foreach($all_rows as $value){
				$dbData = array();
				$dbData['title'] = $value['partner_name'];
				$dbData['issue_date']=$value['valid_till'];
				$dbData['tracking_number']=$value['partner_id'];
				$dbData['country']=$value['country'];
				$dbData['website']=$value['website'];
				$dbData['type']='partner';
				$dbData['enabled']=1;

				try{
					ExternalCertificate::create($dbData);
					$total++;
				}
				catch(\Exception $ex){
					$failure++;
				}
				
			}
			$output['flash_message'] = __lang("import-success",['total'=>$total]);
			return redirect('admin/get_verification_partner');
			if(!empty($failure)){
				$output['flash_message'] .= " $failure ".__lang("records failed");
			}
			
		}
		
		$output['pageTitle']=('Import External Partner Verification');
		
		return view('admin.external_certificates.partnerexcel',$output);
	}
	
	public function import_external_ambassador(Request $request){
	   set_time_limit(86400);

		$output = array();
		if($request->isMethod('post'))
		{
			$post = $request->all();
			$data = $_FILES['file'];
			$file = $data['tmp_name'];
			$file = fopen($file,"r");
			
			$all_rows = array();
			$header = null;
			while ($row = fgetcsv($file)) {
				if ($header === null) {
					$header = $row;
					continue;
				}
				$all_rows[] = array_combine($header, $row);
			}
			$total = 0;
			$failure = 0;

			//loop rows
			foreach($all_rows as $value){
				$dbData = array();
				$dbData['title'] = $value['ambassador_name'];
				$dbData['issue_date']=$value['valid_till'];
				$dbData['tracking_number']=$value['ambassador_id'];
				$dbData['country']=$value['country'];
				$dbData['type']='ambassador';
				$dbData['enabled']=1;

				try{
					ExternalCertificate::create($dbData);
					$total++;
				}
				catch(\Exception $ex){
					$failure++;
				}
				
			}
			$output['flash_message'] = __lang("import-success",['total'=>$total]);
			return redirect('admin/get_verification_ambassador');
			if(!empty($failure)){
				$output['flash_message'] .= " $failure ".__lang("records failed");
			return redirect('admin/get_verification_ambassador');
			}
			
		}
		
		$output['pageTitle']=('Import External Ambassador Verification');
		
		return view('admin.external_certificates.ambassadorexcel',$output); 
	}
}
