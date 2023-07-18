@extends(TLAYOUT)

@section('page-title',$title)
@section('inline-title',$title)

@section('content')
    <section class="ftco-section">
        <div class="container px-4">
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
