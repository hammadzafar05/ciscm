{{--MARUF START--}}
@extends(TLAYOUT)

@section('page-title',$page->name)
@section('inline-title',$page->name)

@section('content')

    <section class="ftco-section ftco-no-pt ftc-no-pb" style="padding:50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    @if(isset($page)){!! $page->description !!}@endif

                </div>
            </div> <!-- row -->
        </div>
    </section>


@endsection
{{--MARUF END--}}
