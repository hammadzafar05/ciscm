@extends('layouts.filemanager')
@section('page-title','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('admin.dashboard')=>__('default.dashboard'),
            '#'=>isset($pageTitle)?$pageTitle:''
        ]])
@endsection

@section('content')


 <script type="text/javascript" charset="utf-8">

	 function getUrlParam(paramName) {
	     var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
	     var match = window.location.search.match(reParam) ;

	     return (match && match.length > 1) ? match[1] : '' ;
	 }


    $().ready(function() {
    	var funcNum = getUrlParam('CKEditorFuncNum');
    	var token = getUrlParam('token');

        var elf = $('#elfinder').elfinder({
            // lang: 'ru',             // language (OPTIONAL)
            url : '{{ url('/admin/filemanager/connector') }}',  // connector URL (REQUIRED)
            commandsOptions : {
                edit : {
                    extraOptions : {
                        // set API key to enable Creative Cloud image editor
                        // see https://console.adobe.io/
                        //creativeCloudApiKey : '',
                        // browsing manager URL for CKEditor, TinyMCE
                        // uses self location with the empty value
                        //managerUrl : ''
                    }
                }
                ,quicklook : {
                    // to enable preview with Google Docs Viewer
                    googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.presentationml.presentation']
                }
            },
            height : '500',
            getFileCallback : function(file) {

                file = str_replace('%20','_',file.url);
                if(funcNum)
                {

              //  	file = str_replace('/storemantis/public','',file);
	                window.opener.CKEDITOR.tools.callFunction(funcNum,file);
	                window.close();
                }
                else if(token)
                {
                    file = str_replace('{{ $siteUrl }}/','',file);
                    file = str_replace('{{ $siteUrl }}','',file);
                  //  alert('Fist file:'+file);

                    if('{{ basePath()}}'.length != 0)
                    {
                        file = str_replace('{{ url('/') }}/','',file);
                    }



				//	file = urldecode(file);
				//	file = str_replace('/storemantis/public','',file);
               //        alert('seconf file'+file);

                    console.log('FInal: '+file);
                	parent.$('#{{ trim($field) }}').attr('value',file);
            		parent.$('#dialog').dialog('close');

            		parent.$('#dialog').remove();
                }

            }

        }).elfinder('instance');
    });





    function str_replace(search, replace, subject, count) {

  	  var i = 0,
  	    j = 0,
  	    temp = '',
  	    repl = '',
  	    sl = 0,
  	    fl = 0,
  	    f = [].concat(search),
  	    r = [].concat(replace),
  	    s = subject,
  	    ra = Object.prototype.toString.call(r) === '[object Array]',
  	    sa = Object.prototype.toString.call(s) === '[object Array]';
  	  s = [].concat(s);
  	  if (count) {
  	    this.window[count] = 0;
  	  }

  	  for (i = 0, sl = s.length; i < sl; i++) {
  	    if (s[i] === '') {
  	      continue;
  	    }
  	    for (j = 0, fl = f.length; j < fl; j++) {
  	      temp = s[i] + '';
  	      repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
  	      s[i] = (temp)
  	        .split(f[j])
  	        .join(repl);
  	      if (count && s[i] !== temp) {
  	        this.window[count] += (temp.length - s[i].length) / f[j].length;
  	      }
  	    }
  	  }
  	  return sa ? s : s[0];
  	}




</script>

<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('client/vendor/filemanager/css/elfinder.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/vendor/filemanager/css/theme.css') }}">
    <script type="text/javascript" src="{{ asset('client/vendor/filemanager/js/elfinder.min.js?wkejr') }}"></script>
    <script type="text/javascript" src="{{ asset('client/vendor/filemanager/js/extras/editors.default.min.js') }}"></script>
@endsection
