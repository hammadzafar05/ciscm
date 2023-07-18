@extends('layouts.student')
@section('pageTitle','')
@section('innerTitle','')
@section('breadcrumb')
    @include('admin.partials.crumb',[
    'crumbs'=>[
            route('student.dashboard')=>__lang('dashboard'),
            '#'=>$pageTitle
        ]])
@endsection

@section('content')
@php  $this->headTitle($pageTitle);  @endphp
<div class="container box">
    <table class="table table-stripped">
        <thead>
        <tr>
            <th>{{  setting('label_session_course','Session/Course') }}</th>
            <th>{{  __lang('Fee')  }}</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td>{{  $row->session_name }}</td>
            <td>{{  price($row->amount)  }}</td>
        </tr>

        </tbody>

    </table>

    <div class="row">
        <div class="col-md-4 col-md-offset-6">
            <h4>{{  __lang('select-payment-method')  }}</h4>
            <form class="form" action="{{  $this->url('application/default',['controller'=>'payment','action'=>'method']) }}" method="post">
            <table class="table">
                @php  $count = 0;  @endphp
                @php  foreach($methods as $method): @endphp
                <tr>
                    <td><input  @php  if($count==0): @endphp  checked="checked" @php  endif;  @endphp required="required" type="radio" name="code" value="{{  $method->code  }}"/> </td>
                    <td>{{  $method->method_label  }}</td>
                </tr>
                @php  $count++;  @endphp
                @php  endforeach;  @endphp
            </table>
                <button class="btn btn-primary btn-lg" type="submit">{{  __lang('Make Payment')  }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
