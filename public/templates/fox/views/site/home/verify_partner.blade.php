@extends(TLAYOUT)

@section('page-title','Verify Partner')
@section('inline-title','Verify Partner')

@section('content')
    <style type="text/css">
        body{
            background: #f2f2f2;
            font-family: 'Open Sans', sans-serif;
        }

        .search {
            width: 100%;
            position: relative;
            display: flex;
        }

        .searchTerm {
            width: 100%;
            border: 3px solid #dc3545;
            border-right: none;
            padding: 5px;
            height: 60px;
            border-radius: 5px 0 0 5px;
            outline: none;
            color: #fd7e14;
            transition:all 2s ease-in;
        }

        /*.searchTerm:focus{
          color: #dc3545;
          border:solid 3px #09f;
          outline:solid #fc0 2000px;
        }*/

        .searchButton {
            width: 40px;
            height: 60px;
            border: 1px solid #dc3545;
            background: #dc3545;
            text-align: center;
            color: #fff;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            font-size: 20px;
        }

        /*Resize the wrap to see the search bar change!*/
        .wrap{
            width: 30%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>

    <section class="ftco-section ftco-no-pt ftc-no-pb" style="padding:50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form class="search" action="{{ url('/partner') }}">
                        <input type="text" name="filter" class="searchTerm" placeholder="Please Enter verification Number" required="true">
                        <button type="submit" class="searchButton">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div> <!-- row -->
            @if($status==1)
                <div class="row" style="margin-top: 30px;">
                    <div class="col-md-3"></div>
                    <div class="col-md-6 ">
                        @if($is_exist==0)
                            <div class="alert alert-danger" role="alert">  Ops! {{ $filter }} tracking number is not available!</div>
                        @else
                            <div class="alert alert-success" role="alert">  Great! {{ $filter }} tracking number is available!</div>
                        @endif
                    </div>
                    <div class="col-md-3"></div>
                </div>
                @if($is_exist==1)
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                 <tr>
                                    <th width="30%">Certificate Holder Name</th>
                                    <th width="15%">Country</th>
                                    <th width="15%">Valid Till</th>
                                    <th width="40%">Web Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $student }}</td>
                                        <td>{{ $country }}</td>
                                        <td>{{ $issue_date }}</td>
                                        <td>{{ $website }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>
    <br clear="all"><br clear="all">


@endsection
