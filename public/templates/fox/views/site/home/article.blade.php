@extends(TLAYOUT)

@section('page-title',(!empty($article->meta_title))? $article->meta_title:$article->title)
@section('meta-description',$article->meta_description)
@section('inline-title',$article->title)

@section('content')




    <section class="ftco-section">
        <div class="container px-4">
            <div class="row">

                <div class="col-md-12">
                    <div class="about-content them-2">
                        {!!  $article->content !!}
                    </div>
                    <!-- about content -->
                </div>
            </div> <!-- row -->
        </div>
    </section>





@endsection
