<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <link id="theme-style" rel="stylesheet" href="{{ asset('/assets/css/portal.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/style.css') }}">

        <link href='https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
        <script defer src="{{ asset('/assets/plugins/fontawesome/js/all.min.js') }}"></script>
        <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        {{-- @livewireStyles --}}

        
    </head>
    <body class="app">
        {{-- <x-jet-banner /> --}}
        @include('layouts.header')
        
        <div class="app-wrapper">
	    
            <div class="app-content pt-3 p-md-3 p-lg-4">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>

            <footer class="app-footer">
                <div class="card card-body">
                    <div class="container text-center">
                        <small class="copyright">XTRHA System by <a href="http://arcelbularon.epizy.com" target="_blank">Arcel Bularon Jr</a></small>
                    </div>
                </div>
            </footer><!--//app-footer-->

        
            <div class="modal fade" id="show_in_popup_event" tabindex="-1" role="dialog" aria-labelledby="showInPopUpModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-body">
                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="toast-container position-absolute">
                <div id="toast" class="toast fixed-bottom" style="z-index: 9999;" role="alert" aria-live="assertive" aria-atomic="true"></div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('assets/plugins/popper.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>  
        <!-- Charts JS -->
        <script src="{{ asset('assets/plugins/chart.js/chart.min.js') }}"></script> 
        <script src="{{ asset('assets/js/index-charts.js') }}"></script> 
        <script src="{{ asset('assets/js/app.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var table_id    =   $('.datatable').attr('id');
                var table_url   =   $('.datatable').attr('data-url');
    
                $('#'+table_id).DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "ajax": table_url
                } );

                $(document.body).off('click', '.show_in_popup');
                $(document.body).on('click', '.show_in_popup', function(event) {
                    var data_keyid  =   $(this).attr('data-keyid');
                    var data_url    =   $(this).attr('data-url');
    
                    $.ajax({
                        beforeSend:function() {
                            $('#show_in_popup_event').find('.modal-body').html('<h1 class="text-center"><i class="fas fa-circle-notch fa-spin"></i></h1>');
                            showInPopupEvent('1');
                        },
                        type: "GET",
                        data: { keyid: data_keyid },
                        url: data_url,
                        success:function(response) {
                            $('#show_in_popup_event').find('.modal-body').html(response);
                            showInPopupEvent('1');
                        }
                    })
                });

                $(document.body).off('click', '.btn_close');
                $(document.body).on('click', '.btn_close', function(event) {
                    var tableid =   $(this).attr('data-tableid');
                    $('#'+tableid).DataTable().ajax.reload();
                    showInPopupEvent('0');
                });

                $(document.body).off('click', '.btn_delete');
                $(document.body).on('click', '.btn_delete', function(event) {
                    var id          =   $(this).attr('data-id');
                    var url         =   $(this).attr('data-url');
                    
                    if (confirm('Are you sure you want to delete this record?')) {
                        $.ajax({
                            type: "POST",
                            data: { id: id },
                            url: url,
                            success:function(response)
                            {
                                $('#delete_row_'+id).parents(':eq(1)').hide();
                                $('#toast').html(response);
                                show_toast();
                            }
                        });
                    }
                });

                $(document.body).off('click', '.btn_print');
                $(document.body).on('click', '.btn_print', function(event) {
                    var keyid = $(this).attr('data-id');
                    $.ajax({
                        type: "GET",
                        url: '/general_info/edit/'+keyid,
                        dataType: 'html',
                        success:function(response) {
                            window.open(window.location.href,"_blank");
                            window.document.open();
                            window.document.write(response);
                            window.document.close();
                            window.print();
                        }
                    })
                });

                $(".select2").select2({
                    matcher: matchCustom
                });

                $(document.body).off('change', '.autosave');
                $(document.body).on('change', '.autosave', function(event) {
                    var form_serialized = $(this).closest('form').serializeArray();
                    var form_url = $(this).closest('form').attr('action');
                    var form_data = new FormData();
                    form_serialized.forEach(element => {
                        form_data.append(element.name, element.value);
                    });

                    $.ajax({
                        type: "POST",
                        data: form_data,
                        url: form_url,
                        dataType: 'JSON',
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSubmit() {
                            $('.progress-bar').css('width', '0%');
                            $('.progress-bar').attr('aria-valuenow', '0');
                        },
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = (evt.loaded / evt.total) * 100;
                                    // Place upload progress bar visibility code here
                                    $('.progress-bar').css('width', percentComplete+'%');
                                    $('.progress-bar').attr('aria-valuenow', percentComplete);
                                }
                            }, false);
                            return xhr;
                        },
                        success:function(response)
                        {
                            $('.progress-bar').css('width', '0%');
                            $('.progress-bar').attr('aria-valuenow', '0');
                            $('#keyid').val(response.data.id);
                            $('#toast').html(response.message);
                            show_toast();
                            
                        }
                    });
                });
            });

            function showInPopupEvent($is_show)
            {
                if ($is_show == '1') {
                    var myModal = new bootstrap.Modal(document.getElementById('show_in_popup_event'), {
                        keyboard: false,
                        backdrop: 'static'
                    });
                    myModal.show();
                }
                else
                {
                    var myModal = bootstrap.Modal.getInstance(document.getElementById('show_in_popup_event'));
                    myModal.hide();
                    $('.modal-backdrop').hide();
                }
            }

            function show_toast(response)
            {
                var myToast = document.getElementById('toast');//select id of toast
                var bsToast = new bootstrap.Toast(myToast);//inizialize it
                bsToast.show();//show i
            }

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.image-upload-wrap').hide();
                        $('.file-upload-image').attr('src', e.target.result);
                        $('.file-upload-content').show();
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    removeUpload();
                }
            }

            function removeUpload() {
                $('.file-upload-input').replaceWith($('.file-upload-input').clone());
                $('.file-upload-content').hide();
                $('.image-upload-wrap').show();
            }
            $('.image-upload-wrap').bind('dragover', function () {
                $('.image-upload-wrap').addClass('image-dropping');
            });
            $('.image-upload-wrap').bind('dragleave', function () {
                $('.image-upload-wrap').removeClass('image-dropping');
            });

            function matchCustom(params, data) {
                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                    return null;
                }

                // `params.term` should be the term that is used for searching
                // `data.text` is the text that is displayed for the data object
                if (data.text.indexOf(params.term) > -1) {
                    var modifiedData = $.extend({}, data, true);
                    modifiedData.text += ' (matched)';

                    // You can return modified objects from here
                    // This includes matching the `children` how you want in nested data sets
                    return modifiedData;
                }

                // Return `null` if the term should not be displayed
                return null;
            }

        </script>
    </body>
</html>
