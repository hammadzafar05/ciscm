@extends(TLAYOUT)

@section('page-title',$title)
@section('inline-title',$title)

@section('content')
    <section class="about-area them-2 pb-130 pt-50">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <div class="about-content them-2">
                        {!! $content !!}
                    </div>
                    <!-- about content -->
                </div>
            </div> <!-- row -->
        </div>
    </section>
@endsection
