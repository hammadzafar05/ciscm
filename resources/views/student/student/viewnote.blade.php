@extends('layouts.student')
@section('pageTitle',__lang('revision-note').': '.$row->title)
@section('innerTitle',$row->title)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
             route('student.student.notes')=>__lang('revision-notes'),
            route('student.student.sessionnotes',['id'=>$row->course->id])=>__lang('course-notes'),
            '#'=>__lang('view-note')
        ]])
@endsection

@section('content')


            <!--container starts-->
           <div class="container"  >
           	<!--primary starts-->
           <div class="card">
               <div class="card-body">
                   {!! $row->content !!}
               </div>
           </div>


               @php  $shortName = setting('general_disqus'); if(!empty($shortName)): @endphp
                   <script type="text/javascript">
                       /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                       var disqus_shortname = '{{  trim($shortName) }}'; // required: replace example with your forum shortname

                       /* * * DON'T EDIT BELOW THIS LINE * * */
                       (function () {
                           var s = document.createElement('script'); s.async = true;
                           s.type = 'text/javascript';
                           s.src = '//' + disqus_shortname + '.disqus.com/count.js';
                           (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
                       }());
                   </script>
               @php  endif;  @endphp
           </div>




             <!--container ends-->

@endsection
