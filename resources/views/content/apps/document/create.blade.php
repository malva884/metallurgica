@extends('layouts/contentLayoutMaster')

@section('title', 'Nuova Specifica Tecnica')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/forms/select/select2.min.css')}}">


  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')) }}">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap"
        rel="stylesheet">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection
@section('page-style')
  <link rel="stylesheet" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
  <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice.css')}}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
  <link rel="stylesheet" href="{{asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.0-dev.3/quill.snow.min.css" rel="stylesheet">
  <link href="https://unpkg.com/quill-table-ui@1.0.5/dist/index.css" rel="stylesheet">
@endsection

@section('content')
  <section class="invoice-add-wrapper">
    <div class="row invoice-add">
      <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2"></div>
      <!-- Invoice Add Left starts -->
      <div class="col-xl-6 col-md-6 col-12">
        <div class="documents">
          <div class="card invoice-preview-card origin">
            <!-- Header starts -->
            <div class="card-body invoice-padding">
              <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                <div class="table-responsive">
                  <div class="row">
                    <div class="col-12" style="text-align: center">
                      <img
                              class="img-fluid rounded"
                              src="{{asset('/images/logo/stl.png')}}"
                              height="60"
                              width="60"
                      />
                      <p class="font-weight-bolder mt-0 py-0" style="font-family:'Palace Script MT', sans-serif; font-weight: bold; display: inline; font-size: 50px;">
                        &nbsp;Metallutgica Bresciana s.p.a.</p>
                    </div>
                  </div>

                  <table class="table table-bordered ">
                    <tbody>
                    <tr>
                      <td>
                        <div class="form-group row">
                          <label for="colFormLabel" class="col-sm-12 col-form-label">Technical
                            Dept.</label>
                        </div>
                      </td>
                      <td>
                        <div class="form-group row">
                          <label for="colFormLabel" class="col-sm-12 col-form-label">TECHNICAL
                            DATA SHEET</label>
                        </div>
                      </td>
                      <td>
                        <div class="form-group row">
                          <label for="colFormLabel" class="col-sm-5 col-form-label">Specification
                            N??</label>
                          <div class="col-sm-6">
                            <span ></span>
                          </div>
                        </div>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- Header ends -->


            <div class="row">
              <div class="col-sm-11" style="display: inline;margin-left: 5%; position: center;">
                <form enctype="multipart/form-data">
                  <textarea id="document_page_1" ></textarea>
                </form>

              </div>
            </div>
            <hr class="invoice-spacing mt-0"/>
            <div class="card-body invoice-padding py-0">
              <!-- Invoice Note starts -->
              <div class="row">
                <div class="col-12">
                </div>
              </div>
              <!-- Invoice Note ends -->
            </div>
          </div>
        </div>

      </div>
      <!-- Invoice Add Left ends -->

      <!-- Invoice Add Right starts -->
      <div class="col-xl-3 col-md-4 col-12">
        <div class="mt-2">
          <p class="mb-50">Specifica</p>
          <input type="text" class="form-control" id="specific_number"
                 name="specific_number"
                 class="form-control " placeholder="Specification n??"
          />
        </div>
        <div class="mt-2">
          <p class="mb-50">Category</p>
          <select class="form-control mb-1" id="category">
            <option selected>-- Seleziona Catagoria --</option>
            @foreach($categories as $category)
              <option value="{{$category->id}}">{{$category->category}}</option>
            @endforeach
          </select>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="form-group row">
              <label for="colFormLabel" class="col-sm-6 col-form-label col-form-label">Document number</label>
              <div class="col-sm-6">
                <div class="input-group disabled-touchspin">
                  <input type="number" class="touchspin" id="page_n" value="1" disabled />
                </div>
              </div>
            </div>

            <button type="button" class="btn btn-outline-primary btn-block" onclick="saveDocument()" >Save</button>
            <button type="button" class="btn btn-relief-success btn-block mb-75" id="btn-add-new">Add Page</button>
          </div>
        </div>
      </div>
      <!-- Invoice Add Right ends -->
    </div>
  </section>
@endsection

@section('vendor-script')
  <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
  <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>

  <script src="{{ asset(mix('vendors/js/editors/quill/katex.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/editors/quill/highlight.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
@endsection

@section('page-script')
  <script src="{{asset('js/scripts/pages/app-invoice.js')}}"></script>
  <script src="{{ asset(mix('js/scripts/forms/form-number-input.js')) }}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.0-dev.3/quill.min.js" type="text/javascript"></script>
  <script src="https://unpkg.com/quill-image-uploader@1.2.1/dist/quill.imageUploader.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.0-dev.3/quill.min.js" type="text/javascript"></script>
  <script src="https://unpkg.com/quill-table-ui@1.0.5/dist/umd/index.js" type="text/javascript"></script>
  <script src='https://cdn.tiny.cloud/1/14jzepvheitbqnd4u1a9qnq46m27xpn657dfpegsn5cvt5co/tinymce/5/tinymce.min.js' referrerpolicy="origin">
  </script>
  <script>
    $('#specific_number').change(function(){

      $('#num_specifica').text($('#specific_number').val());
    });
    tinymce.init({
      selector: 'textarea#document_page_1',
      height: {{$height_page}},

      relative_urls : false,
      remove_script_host : false,
      convert_urls : true,

      plugins: [
        'advlist autolink lists link image charmap anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table  code help wordcount',
        'insertdatetime media table  imagetools wordcount',
        'table wordcount image code',
        'insertdatetime media table powerpaste hr code'
      ],
      toolbar: 'undo redo | image code | formatselect | ' +
              'bold italic backcolor | alignleft aligncenter ' +
              'alignright alignjustify | bullist numlist outdent indent | ' +
              'removeformat ',
      content_style: '.left { text-align: left; } ' +
              'img.left { float: left; } ' +
              'table.left { float: left; } ' +
              '.right { text-align: right; } ' +
              'img.right { float: right; } ' +
              'table.right { float: right; } ' +
              '.center { text-align: center; } ' +
              //'img.center { display: block; margin: 0 auto; } ' +
              'table.center { display: block; margin: 0 auto; } ' +
              '.full { text-align: justify; } ' +
              //'img.full { display: block; margin: 0 auto; } ' +
              'table.full { display: block; margin: 0 auto; } ' +
              '.bold { font-weight: bold; } ' +
              '.italic { font-style: italic; } ' +
              '.underline { text-decoration: underline; } ' +
              '.example1 {} ' +
              'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }' +
              '.tablerow1 { background-color: #D3D3D3; }',
      formats: {
        alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'left' },
        aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'center' },
        alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'right' },
        alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'full' },

        bold: { inline: 'span', classes: 'bold' },
        italic: { inline: 'span', classes: 'italic' },
        underline: { inline: 'span', classes: 'underline', exact: true },
        strikethrough: { inline: 'del' },
        customformat: { inline: 'span', styles: { color: '#00ff00', fontSize: '20px' }, attributes: { title: 'My custom format'} , classes: 'example1'}
      },
      style_formats: [
        { title: 'Custom format', format: 'customformat' },
        { title: 'Align left', format: 'alignleft' },
        { title: 'Align center', format: 'aligncenter' },
        { title: 'Align right', format: 'alignright' },
        { title: 'Align full', format: 'alignfull' },
        { title: 'Bold text', inline: 'strong' },
        { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
        { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
        { title: 'Badge', inline: 'span', styles: { display: 'inline-block', border: '1px solid #2276d2', 'border-radius': '5px', padding: '2px 5px', margin: '0 2px', color: '#2276d2' } },
        { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' },
        { title: 'Image formats' },
        { title: 'Image Left', selector: 'img', styles: { 'float': 'left', 'margin': '0 10px 0 10px' } },
        { title: 'Image Right', selector: 'img', styles: { 'float': 'right', 'margin': '0 0 10px 10px' } },
      ],
      powerpaste_allow_local_images: true,
      powerpaste_word_import: 'prompt',
      images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '{{route('document.image')}}');
        var token = '{{ csrf_token() }}';
        xhr.setRequestHeader("X-CSRF-Token", token);
        xhr.onload = function() {
          var json;
          if (xhr.status != 200) {
            failure('HTTP Error: ' + xhr.status);
            return;
          }
          json = JSON.parse(xhr.responseText);

          if (!json || typeof json.location != 'string') {
            failure('Invalid JSON: ' + xhr.responseText);
            return;
          }
          success(json.location);
        };
        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);
      },
     // content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });



  </script>
  <script>

    $('#btn-add-new').click(function () {
      var pages = parseInt($('#page_n').val()) + 1;
      $('.documents').append(
              '<div class="card invoice-preview-card origin">'+
              '<div class="card-body invoice-padding  pb-0">'+
              '<div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0"><div>'+
              '<div class="table-responsive"'+
              '<div class="row" >'+
              '<div class="col-12" style="text-align: center">'+
              '<img class="img-fluid rounded" src="{{asset('/images/logo/stl.png')}}" height="60" width="60" alt="User avatar">'+
              '<p class="font-weight-bolder" style=" font-family: Palace Script MT , sans-serif; display: inline; font-size: 50px;">' +
              '&nbsp;Metallutgica Bresciana s.p.a.</p>'+
              '</div></div>'+
              '<table class="table table-bordered" style="width: 850px; ">'+
              '<tbody><tr><td>'+
              '<div class="form-group row">'+
              '<label for="colFormLabel" class="col-sm-12 col-form-label">s.p.aTechnical Dept.</label>'+
              '</div>'+
              '</td><td>'+
              '<div class="form-group row">'+
              '<label for="colFormLabel" class="col-sm-12 col-form-label">TECHNICAL DATA SHEET</label>'+
              '</div>'+
              '</td><td>'+
              '<div class="form-group row">'+
              '<label for="colFormLabel" class="col-sm-5 col-form-label">Specification N??</label>'+
              '<div class="col-sm-6">'+
              '<span id="num_specifica"></span>'+
              '</div>'+
              '</div>'+
              '</td></tr></tbody></div></table></div></div></div>'+
              '<div class="row">'+
              '<div class="col-sm-11" style="display: inline;margin-left: 5%;">'+
              '<textarea id="document_page_'+pages+'"></textarea>'+
              '</div>'+
              '<hr class="invoice-spacing mt-0"/>'+
              '<div class="card-body invoice-padding py-0">'+
              '<div class="row">'+
              '<div class="col-12"></div></div></div></div>'
      );
      $('#page_n').val(pages);
      tinymce.init({
        selector: 'textarea#document_page_'+pages,
        height: {{$height_page}},
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        plugins: [
          'advlist autolink lists link image charmap anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table  code help wordcount',
          'insertdatetime media table  imagetools wordcount',
          'table wordcount image code',
          'insertdatetime media table powerpaste hr code'
        ],
        toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | image code',
        content_style: '.left { text-align: left; } ' +
                'img.left { float: left; } ' +
                'table.left { float: left; } ' +
                '.right { text-align: right; } ' +
                'img.right { float: right; } ' +
                'table.right { float: right; } ' +
                '.center { text-align: center; } ' +
                //'img.center { display: block; margin: 0 auto; } ' +
                'table.center { display: block; margin: 0 auto; } ' +
                '.full { text-align: justify; } ' +
                //'img.full { display: block; margin: 0 auto; } ' +
                'table.full { display: block; margin: 0 auto; } ' +
                '.bold { font-weight: bold; } ' +
                '.italic { font-style: italic; } ' +
                '.underline { text-decoration: underline; } ' +
                '.example1 {} ' +
                'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }' +
                '.tablerow1 { background-color: #D3D3D3; }',
        formats: {
          alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'left' },
          aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'center' },
          alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'right' },
          alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'full' },
          bold: { inline: 'span', classes: 'bold' },
          italic: { inline: 'span', classes: 'italic' },
          underline: { inline: 'span', classes: 'underline', exact: true },
          strikethrough: { inline: 'del' },
          customformat: { inline: 'span', styles: { color: '#00ff00', fontSize: '20px' }, attributes: { title: 'My custom format'} , classes: 'example1'}
        },
        style_formats: [
          { title: 'Custom format', format: 'customformat' },
          { title: 'Align left', format: 'alignleft' },
          { title: 'Align center', format: 'aligncenter' },
          { title: 'Align right', format: 'alignright' },
          { title: 'Align full', format: 'alignfull' },
          { title: 'Bold text', inline: 'strong' },
          { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
          { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
          { title: 'Badge', inline: 'span', styles: { display: 'inline-block', border: '1px solid #2276d2', 'border-radius': '5px', padding: '2px 5px', margin: '0 2px', color: '#2276d2' } },
          { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' },
          { title: 'Image formats' },
          { title: 'Image Left', selector: 'img', styles: { 'float': 'left', 'margin': '0 10px 0 10px' } },
          { title: 'Image Right', selector: 'img', styles: { 'float': 'right', 'margin': '0 0 10px 10px' } },
        ],
        powerpaste_allow_local_images: true,
        powerpaste_word_import: 'prompt',
        powerpaste_html_import: 'prompt',
        images_upload_handler: function (blobInfo, success, failure) {
          var xhr, formData;
          xhr = new XMLHttpRequest();
          xhr.withCredentials = false;
          xhr.open('POST', '{{route('document.image')}}');
          var token = '{{ csrf_token() }}';
          xhr.setRequestHeader("X-CSRF-Token", token);
          xhr.onload = function() {
            var json;
            if (xhr.status != 200) {
              failure('HTTP Error: ' + xhr.status);
              return;
            }
            json = JSON.parse(xhr.responseText);

            if (!json || typeof json.location != 'string') {
              failure('Invalid JSON: ' + xhr.responseText);
              return;
            }
            success(json.location);
          };
          formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          xhr.send(formData);
        },
      });

    });


    function saveDocument(){
      var n = parseInt($('#page_n').val());
      var i = 1;
      var editor_value = [];
      while (n >= i) {
        editor_value[i] = tinymce.get("document_page_"+i).getContent();
        if(editor_value[i] === "")
          delete editor_value[i];
        i++;
      }

      $.ajax({
        type: "POST",
        url: '{{route('document.store')}}',
        data: {
          "editors": editor_value,
          "specification" : $('#specific_number').val(),
          "category" : $('#category').val(),
          'pages' : n,
          "_token": "{{ csrf_token() }}"
        },

        success: function(data)
        {
          window.location = "show/"+data;
        }
      });
    }



  </script>
  <style>


    .table-responsive {
      display: table;
    }

    .table tbody tr td {
      font-size: 8px;
      border: ridge #000 1px !important;
    }

    th {
      font-family: "Trebuchet MS", Arial, Verdana;
      font-size: 8px !important;
      padding: 5px;
      border: ridge #000 1px !important;
    }
  </style>
@endsection
