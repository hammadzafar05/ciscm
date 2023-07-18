@extends('layouts.student')
@section('pageTitle',$pageTitle)
@section('innerTitle',$pageTitle)
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            route('student.assignment.index')=>__lang('homework'),
            '#'=>__lang('submit')
        ]])
@endsection

@section('content')

<div class="card card-primary">
    <div class="card-header">{{  __lang('instructions')  }}</div>
    <div class="card-body">
        <p  class="readmorebox" >
            <article class="readmore">
                {!! clean($row->instruction)  !!}
            </article>
        </p>
    </div>
</div>

<div class="card card-success">
    <div class="card-header">{{  __lang('answer')  }}</div>
    <div class="card-body">
        <form enctype="multipart/form-data" class="form" action="{{  selfURL()  }}" method="post">
            @csrf
        @php  if($row->type=='t' || $row->type=='b'): @endphp
            <div class="form-group">
                {{  formLabel($form->get('content'))  }}


                {{  formElement($form->get('content')) }}
                <p class="help-block">{{  formElementErrors($form->get('content'))  }}</p>
            </div>

            @section('footer')
                @parent
                <link rel="stylesheet" href="{{ asset('client/vendor/summernote/summernote-bs4.css') }}">
                <script type="text/javascript" src="{{ asset('client/vendor/summernote/summernote-bs4.js') }}"></script>.
                <script>
                    $(function(){

                        $('.summernote').summernote({
                            height: 300
                        } );
                    });
                </script>
            @endsection


        @php  endif;  @endphp

        @php  if($row->type == 'f'  || $row->type=='b' ):  @endphp
            @php  if (isset($file)): @endphp
            <div>
                <strong>{{  __lang('current-file')  }}:</strong> {{  $file }} 
            </div>
                @php  endif;  @endphp
            <div class="form-group">
                {{  formLabel($form->get('file_path'))  }}  (You can submit all kinds of File like jpg,xls,csv,xlsx,txt,doc,docx,pptx,pdf,ppt,png,jpeg)

                {{  formElement($form->get('file_path')) }}
                <p class="help-block">{{  formElementErrors($form->get('file_path'))  }}</p>
            </div>

        @php  endif;  @endphp

         <div class="form-group">
             {{  formLabel($form->get('student_comment'))  }}

             {{  formElement($form->get('student_comment')) }}
             <p class="help-block">{{  formElementErrors($form->get('student_comment'))  }}</p>
         </div>

            <div class="form-group">
                {{  formLabel($form->get('submitted'))  }}

                {{  formElement($form->get('submitted')) }}
                <p class="help-block">{{  formElementErrors($form->get('submitted'))  }}</p>
            </div>


            <button class="btn btn-primary">{{  __lang('submit')  }}</button>
        </form>

    </div>
</div>






@endsection


@section('footer')

    <script type="text/javascript" src="{{ asset('client/vendor/readmore/readmore.min.js') }}"></script>
    <script>
        $(function(){
            $('article.readmore').readmore({
                collapsedHeight : 200
            });

        });
    </script>
@endsection
