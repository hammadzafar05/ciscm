<form id="modalForm"
      class="horizontal-form"
      method="POST"
      action="{{ route('admin.partner.invoice.update.single', ['id' => $invoice->id]) }}">
    <div id="modal_result"></div>
    <div id="modal_form">
    <table class="table table-striped">
        <tbody>
            <tr>
                <td>Invoice:</td>
                <td>{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <td>Course:</td>
                <td>{{ $invoice->course->name }}</td>
            </tr>
            <tr>
                <td>Students:</td>
                <td>{!! $invoice->students !!}</td>
            </tr>
            <tr>
                <td>Amount:</td>
                <td>{{formatCurrency($invoice->amount,$invoice->currency->country->currency_code)}}</td>
            </tr>
            <tr>
                <td>Invoice Date</td>
                <td>{{ showDate('d/m/Y',$invoice->created_at) }}</td>
             </tr>
            <tr>
                <td>
                    Receipt:
                </td>
                <td>
                    <input type="file"
                           id="upload_image"
                           class="upload_image required"
                           name="upload_image[]"
                           value=""
                           data-image_name="receipt_file"
                           data-image_width="500"
                           data-image_height="500"
                           data-thumbs_width="150"
                           data-thumbs_height="150"
                    />
                    <br>
                    {{--<span class="help-block">
                        <font color="#8b0000">Image size should be 500x500px</font>
                    </span>--}}
                    <label for="upload"
                           generated="true"
                           class="error"></label>
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td>
                    <div id="loader_image"
                         class="display-hide">
                        <img src="{{ asset('img/please_wait_animation.gif') }}"
                             alt="Loading..."
                             onerror="this.style.display='none'"
                             class="img-responsive col-sm-8"/>
                    </div>
                    <hr>
                    <img id="image_preview_receipt_file"
                         src=""
                         alt="Image"
                         onerror="this.style.display='none'"
                         class="img-responsive"/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="button"
                            id="idSubmitDataModal"
                            class="btn btn-primary btn-sm"
                            name="idSubmitDataModal"
                            value="SUBMIT"
                            onclick="modalPostReview()"
                            data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing">
                        <i class="fa fa-floppy-o"></i> SUBMIT
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".upload_image").on("change", function () {
        var id = $(this).prop('id');
        var image_name = $('#'+id).data('image_name');
        var image_width = $('#'+id).data('image_width');
        var image_height = $('#'+id).data('image_height');
        var thumbs_width = $('#'+id).data('thumbs_width');
        var thumbs_height = $('#'+id).data('thumbs_height');

        var form = $(this).closest('form')[0];

        var formData = new FormData($(this).closest('form')[0]);
        var file_data = $("#"+id).prop("files")[0];
        formData.append("image", file_data);
        formData.append("image_width", image_width);
        formData.append("image_height", image_height);
        formData.append("thumbs_width", thumbs_width);
        formData.append("thumbs_height", thumbs_height);

        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "{{ route('ajaxupload.image') }}",
            "method": "POST",
            "processData": false,
            "contentType": false,
            "mimeType": "multipart/form-data",
            "data": formData
        }

        $('#idSubmitDataModal').hide();
        jQuery('#loader_'+image_name).removeClass('display-hide');
        jQuery('#image_preview_'+image_name).hide();

        $.ajax(settings).done(function (response) {
            var response = $.parseJSON(response);

            alert(response.message);

            $('#idSubmitDataModal').show();
            jQuery('#loader_'+image_name).addClass('display-hide');

            jQuery('#image_preview_'+image_name).attr("src", response.data.path);
            jQuery('#image_preview_'+image_name).show();

            if(response.status == 'success') {
                $('<input>').attr('type','hidden')
                    .attr('id','uploaded_image_'+image_name)
                    .attr('class','form-control')
                    .attr('name',image_name)
                    .attr('value',response.data.name)
                    .appendTo('#'+form.id);
            }else{
                $('#'+id).val('');
            }
        });
    });
    var modalPostReview = function() {
        if($("#modalForm").valid()) {
            $('button[name=idSubmitDataModal]').button('loading');
            var dataString = $("#modalForm").serialize();
            $.ajax({
                type: $("#modalForm").attr('method'),
                url: $("#modalForm").attr('action'),
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: dataString,
                success: function (response) {
                    alert(response.message);
                    $('button[name=idSubmitDataModal]').button('reset');
                    if (response.status == 'success') {
                        location.reload();
                    } else if (response.status == 'error') {
                        alert("Unable to save into database. Please try again!");
                        $('button[name=idSubmitDataModal]').button('reset');
                    } else {
                        alert("Unable to save into database. Please try again!");
                        $('button[name=idSubmitDataModal]').button('reset');
                    }
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('Not connect. Verify Network.');
                    } else if (jqXHR.status == 404) {
                        alert('Requested page not found. [404].');
                    } else if (jqXHR.status == 500) {
                        alert('Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                        alert('Requested JSON parse failed.');
                    } else if (exception === 'timeout') {
                        alert('Time out error.');
                    } else if (exception === 'abort') {
                        alert('Ajax request aborted.');
                    } else {
                        //alert('Uncaught Error.' + jqXHR.responseText);
                        $.notify(jqXHR.responseText.message, 'error');
                    }
                    $('button[name=idSubmitDataModal]').button('reset');
                }
            });
        }
    }
</script>