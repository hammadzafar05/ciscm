
<div class="box mb-5" >
    <div class="box-head">
        <header><h4>{{ __lang('slide') }} <strong>[num]</strong>  </h4></header>
    </div>
    <div class="box-body " class="slideroptions">


        <div class="form-group"  >

            <label for="slideshow_image[num]" class="control-label">{{ __lang('image') }}</label><br />


            <div class="image"><img data-name="slideshow_image[num]" src="[no_image]" alt="" id="slideshow_thumb[num]" /><br />
                <input class="form-control" type="hidden" name="slideshow_image[num]" value="" id="slideshow_image[num]" />
                <a class="pointer" onclick="image_upload('slideshow_image[num]', 'slideshow_thumb[num]');">{{ __lang('Browse') }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#slideshow_thumb[num]').attr('src', '[no_image]'); $('#slideshow_image[num]').attr('value', '');">{{ __lang('Clear') }}</a></div>

        </div>




        <div class="row" >
            <div class="col-md-12">
                <div class="form-group form-group-sm form-group-md">
                    <div class="col-md-2 col-sm-3">
                        <label class="control-label">{{ __lang('url') }}:</label>
                    </div>

                    <div class="col-md-5 col-sm-5">
                        <input name="url[num]"   class="form-control" placeholder="{{ __lang('full-url') }} e.g. http://..."  type="text">



                    </div>
                </div>
            </div>

        </div>









    </div>





</div><!--end .box -->





<hr>

