<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <link rel="icon" href="https://hrms.fmc.ltd/upload/favicon.ico" sizes="16x16" type="image/png">
    <title>Accounts</title>

    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{asset('assets_new/font-awesome/4.5.0/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets_new/css/bootstrap.min.css')}}">


    <!-- page specific plugin styles -->

    <!-- text fonts -->
{{--    <link rel="stylesheet" href="{{asset('assets_new/css/fonts.googleapis.com.css')}}"/>--}}
    <link rel="stylesheet" href="{{asset('assets_new/css/jquery.fancybox.css?v=2.1.5')}}" media="screen"/>


    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{{asset('assets_new/css/jquery-ui.custom.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets_new/css/chosen.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets_new/css/bootstrap-datepicker3.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets_new/css/bootstrap-timepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets_new/css/daterangepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets_new/css/bootstrap-datetimepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets_new/css/bootstrap-colorpicker.min.css')}}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css"
          integrity="sha256-Z8TW+REiUm9zSQMGZH4bfZi52VJgMqETCbPFlGRB1P8=" crossorigin="anonymous"/>
    <!-- ace styles -->
    <link rel="stylesheet" href="{{asset('assets_new/css/ace.min.css')}}" class="ace-main-stylesheet"
          id="main-ace-style"/>

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{asset('assets_new/css/ace-part2.min.css')}}" class="ace-main-stylesheet" />
    <![endif]-->
    <link rel="stylesheet" href="{{asset('assets_new/css/ace-skins.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets_new/css/ace-rtl.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets_new/css/style.css')}}"/>

    <link rel="stylesheet" href="{{asset('assets/backend/css/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/select2.min.css')}}">

    @yield('style')

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{asset('assets_new/css/ace-ie.min.css')}}" />
    <![endif]-->

    <!-- inline styles related to this page -->


    <!-- ace settings handler -->
    <script src="{{asset('assets_new/js/ace-extra.min.js')}}"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="https://hrms.fmc.ltd/assets/js/html5shiv.min.js"></script>
    <script src="https://hrms.fmc.ltd/assets/js/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }
    </style>
    <style>
        .note-editable ul {
            list-style: disc inside !important;
        }

        .note-editable ol {
            list-style: decimal inside !important;
        }

        .modal {
            z-index: 99999;
        }

        fieldset.scheduler-border {
            border: 1px solid #0062cc !important;
            padding: 10px !important;
            margin: 10px !important;
            border-radius: 5px;
        }

        legend.scheduler-border {
            font-customer: 1.2em !important;
            /* font-weight: bold !important; */
            text-align: center !important;
            background: #2A3F54;
            width: 50%;
            color: rgb(255, 255, 255);
            border-radius: 5px;
        }

        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width: 1200px;
            }
        }

        .table-striped > tbody > tr:nth-child(odd) > td,
        .table-striped > tbody > tr:nth-child(odd) > th {
            background-color: #bacae3;
        }
        .btn{
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
    </style>
</head>

<body class="skin-2">
@include('backend.partials._navbar')
<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.loadState('main-container')
        } catch (e) {
        }
    </script>

    @include('backend.partials._sidebar')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="page-content">
                @yield("main")
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    <div class="footer">
        <div class="footer-inner">
            <div class="footer-content">
						<span class="bigger-120">

{{--							2018- 2022 &copy; Copyright <span class="blue bolder"> WWW.STITBD.COM</span>--}}
                            Copyright @2022 all reserved by <a href="#" target="_blank">E bizness </a> Developed by <a href="https://stitbd.com/" target="_blank">STITBD</a>
						</span>

                &nbsp; &nbsp;
                <span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#" target="_blank">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
						</span>
            </div>
        </div>
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script  type="text/javascript" src="{{asset('assets_new/js/jquery-2.1.4.min.js')}}"></script>
<!-- <![endif]-->

{{--<script type="text/javascript" src="{{asset('assets/backend/js/jquery.min.js')}}"></script>--}}

<script type="text/javascript" src="{{asset('assets/backend/js/popper.min.js')}}"></script>
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='{{asset('assets_new/js/jquery.mobile.custom.min.js')}}'>" + "<" + "/script>");
</script>
<script src="{{asset('assets_new/js/bootstrap.min.js')}}"></script>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;


    var pusher = new Pusher('6f704846c2fcc906756a', {
        cluster: 'ap2'
    });

    var channel = pusher.subscribe('demo');
    channel.bind('transaction-alert', function(data) {
        notifyMe(JSON.parse(data.hello));
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (!Notification) {
            alert('Desktop notifications not available in your browser. Try Chromium.');
            return;
        }
        if (Notification.permission !== 'granted')
            Notification.requestPermission();
    });

    function notifyMe(data) {
        // console.log(data)
        if (Notification.permission !== 'granted')
            Notification.requestPermission();
        else {
            var notification = new Notification(data.title, {
                icon: 'https://stitbd.com/f_asset/stitbd_logo.png',
                body: data.message,
            });
            notification.onclick = function() {
                window.open(data.url);
            };
        }
    }
</script>


<!-- page specific plugin scripts -->

<!--------------------------------- This script for js data table start ----------------------------->
<script src="{{asset('assets_new/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets_new/js/jquery.dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('assets_new/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets_new/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets_new/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets_new/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets_new/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('assets_new/js/dataTables.select.min.js')}}"></script>

<!--------------------------------- This script for js data table end ----------------------------->
<script src="{{asset('assets_new/js/excanvas.min.js')}}"></script>
<!--------------------------------- This script for js all input tag start ----------------------------->
<script src="{{asset('assets_new/js/jquery-ui.custom.min.js')}}"></script>
<script src="{{asset('assets_new/js/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{asset('assets_new/js/chosen.jquery.min.js')}}"></script>
<script src="{{asset('assets_new/js/spinbox.min.js')}}"></script>
<script src="{{asset('assets_new/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets_new/js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('assets_new/js/moment.min.js')}}"></script>
<script src="{{asset('assets_new/js/daterangepicker.min.js')}}"></script>
<script src="{{asset('assets_new/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('assets_new/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets_new/js/jquery.knob.min.js')}}"></script>
<script src="{{asset('assets_new/js/autosize.min.js')}}"></script>
<script src="{{asset('assets_new/js/jquery.inputlimiter.min.js')}}"></script>
<script src="{{asset('assets_new/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('assets_new/js/bootstrap-tag.min.js')}}"></script>
<!--------------------------------- This script for js all input tag end ----------------------------->

<!-- ace scripts -->
<script src="{{asset('assets_new/js/ace-elements.min.js')}}"></script>
<script src="{{asset('assets_new/js/ace.min.js')}}"></script>
<script type="text/javascript" src="https://hrms.fmc.ltd/assets/js/jquery.fancybox.js?v=2.1.5"></script>

{{--<script type="text/javascript" src="{{asset('assets/backend/js/main.js')}}"></script>--}}

<script src="{{asset('assets/backend/js/toastr.min.js')}}"></script>
<script src="{{asset('assets/backend/js/select2.min.js')}}"></script>

{!! Toastr::message() !!}
@yield('script')
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>

<script>

    (function worker() {
        var url = "{{ route('getDashboardCounter') }}";
        $.ajax({
            url: url,
            success: function (data) {
                console.log(data)
                $('#finalPending').text(data.finalPending);
                $('#totalPendingExpense').text(data.totalPendingExpense);
                $('#totalPendingInternalTransaction').text(data.totalPendingInternalTransaction);
                $('#totalPendingPayment').text(data.totalPendingPayment);
                $('#totalPending').text(data.totalPending);
                $('#totalApproved').text(data.totalApproved);
                $('#totalOrder').text(data.totalOrder);
            },
            complete: function () {
                setTimeout(worker, 5000);
            }
        });
    })();

    (function order_notification() {
        var url = "{{ route('getOrderNotification') }}";
        $.ajax({
            url: url,
            success: function (data) {
                // console.log(data)
                $('#order-notification').html(data);
            },
            complete: function () {
                setTimeout(order_notification, 5000);
            }
        });
    })();
    (function pending_notification() {
        var url = "{{ route('getPendingNotification') }}";
        $.ajax({
            url: url,
            success: function (data) {
                // console.log(data)
                $('#pending-notification').html(data);
            },
            complete: function () {
                setTimeout(pending_notification, 500000000);
            }
        });
    })();
    (function approved_notification() {
        var url = "{{ route('getApprovedNotification') }}";
        $.ajax({
            url: url,
            success: function (data) {
                // console.log(data)
                $('#approved-notification').html(data);
            },
            complete: function () {
                setTimeout(approved_notification, 5000);
            }
        });
    })();

    function numberWithCommas(y) {
        x = (Math.round(y * 100) / 100).toFixed(2);
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>



<!-- inline scripts related to this page -->


<!--------------------------------- This script for chosen and date picher and other tag start ----------------------------->
<script type="text/javascript">

    $(document).ready(function () {
        $(".alart-out").fadeTo(4000, 900).slideUp(900, function () {
            $(".alart-out").slideUp(3000);
        });
    });

    jQuery(function ($) {
        $('#id-disable-check').on('click', function () {
            var inp = $('#form-input-readonly').get(0);
            if (inp.hasAttribute('disabled')) {
                inp.setAttribute('readonly', 'true');
                inp.removeAttribute('disabled');
                inp.value = "This text field is readonly!";
            } else {
                inp.setAttribute('disabled', 'disabled');
                inp.removeAttribute('readonly');
                inp.value = "This text field is disabled!";
            }
        });


        if (!ace.vars['touch']) {
            $('.chosen-select').chosen({allow_single_deselect: true});
            //resize the chosen on window resize

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function () {
                    $('.chosen-select').each(function () {
                        var $this = $(this);
                        $this.next().css({'width': $this.parent().width()});
                    })
                }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function (e, event_name, event_val) {
                if (event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function () {
                    var $this = $(this);
                    $this.next().css({'width': $this.parent().width()});
                })
            });


            $('#chosen-multiple-style .btn').on('click', function (e) {
                var target = $(this).find('input[type=radio]');
                var which = parseInt(target.val());
                if (which == 2) $('#form-field-select-4').addClass('tag-input-style');
                else $('#form-field-select-4').removeClass('tag-input-style');
            });
        }


        $('[data-rel=tooltip]').tooltip({container: 'body'});
        $('[data-rel=popover]').popover({container: 'body'});

        autosize($('textarea[class*=autosize]'));

        $('textarea.limited').inputlimiter({
            remText: '%n character%s remaining...',
            limitText: 'max allowed : %n.'
        });

        $.mask.definitions['~'] = '[+-]';
        $('.input-mask-date').mask('99/99/9999');
        $('.input-mask-phone').mask('(999) 999-9999');
        $('.input-mask-eyescript').mask('~9.99 ~9.99 999');
        $(".input-mask-product").mask("a*-999-a999", {
            placeholder: " ", completed: function () {
                alert("You typed the following: " + this.val());
            }
        });


        $("#input-size-slider").css('width', '200px').slider({
            value: 1,
            range: "min",
            min: 1,
            max: 8,
            step: 1,
            slide: function (event, ui) {
                var sizing = ['', 'input-sm', 'input-lg', 'input-mini', 'input-small', 'input-medium', 'input-large', 'input-xlarge', 'input-xxlarge'];
                var val = parseInt(ui.value);
                $('#form-field-4').attr('class', sizing[val]).attr('placeholder', '.' + sizing[val]);
            }
        });

        $("#input-span-slider").slider({
            value: 1,
            range: "min",
            min: 1,
            max: 12,
            step: 1,
            slide: function (event, ui) {
                var val = parseInt(ui.value);
                $('#form-field-5').attr('class', 'col-xs-' + val).val('.col-xs-' + val);
            }
        });


        //"jQuery UI Slider"
        //range slider tooltip example
        $("#slider-range").css('height', '200px').slider({
            orientation: "vertical",
            range: true,
            min: 0,
            max: 100,
            values: [17, 67],
            slide: function (event, ui) {
                var val = ui.values[$(ui.handle).index() - 1] + "";

                if (!ui.handle.firstChild) {
                    $("<div class='tooltip right in' style='display:none;left:16px;top:-6px;'><div class='tooltip-arrow'></div><div class='tooltip-inner'></div></div>")
                        .prependTo(ui.handle);
                }
                $(ui.handle.firstChild).show().children().eq(1).text(val);
            }
        }).find('span.ui-slider-handle').on('blur', function () {
            $(this.firstChild).hide();
        });


        $("#slider-range-max").slider({
            range: "max",
            min: 1,
            max: 10,
            value: 2
        });

        $("#slider-eq > span").css({width: '90%', 'float': 'left', margin: '15px'}).each(function () {
            // read initial values from markup and remove that
            var value = parseInt($(this).text(), 10);
            $(this).empty().slider({
                value: value,
                range: "min",
                animate: true

            });
        });

        $("#slider-eq > span.ui-slider-purple").slider('disable');//disable third item


        $('#id-input-file-1 , #id-input-file-2').ace_file_input({
            no_file: 'No File ...',
            btn_choose: 'Choose',
            btn_change: 'Change',
            droppable: false,
            onchange: null,
            thumbnail: false //| true | large
            //whitelist:'gif|png|jpg|jpeg'
            //blacklist:'exe|php'
            //onchange:''
            //
        });
        //pre-show a file name, for example a previously selected file
        //$('#id-input-file-1').ace_file_input('show_file_list', ['myfile.txt'])


        $('#id-input-file-3').ace_file_input({
            style: 'well',
            btn_choose: 'Drop files here or click to choose',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small'//large | fit
            //,icon_remove:null//set null, to hide remove/reset button
            /**,before_change:function(files, dropped) {
						//Check an example below
						//or examples/file-upload.html
						return true;
					}*/
            /**,before_remove : function() {
						return true;
					}*/
            ,
            preview_error: function (filename, error_code) {
                //name of the file that failed
                //error_code values
                //1 = 'FILE_LOAD_FAILED',
                //2 = 'IMAGE_LOAD_FAILED',
                //3 = 'THUMBNAIL_FAILED'
                //alert(error_code);
            }

        }).on('change', function () {
            //console.log($(this).data('ace_input_files'));
            //console.log($(this).data('ace_input_method'));
        });


        //$('#id-input-file-3')
        //.ace_file_input('show_file_list', [
        //{type: 'image', name: 'name of image', path: 'http://path/to/image/for/preview'},
        //{type: 'file', name: 'hello.txt'}
        //]);


        //dynamically change allowed formats by changing allowExt && allowMime function
        $('#id-file-format').removeAttr('checked').on('change', function () {
            var whitelist_ext, whitelist_mime;
            var btn_choose
            var no_icon
            if (this.checked) {
                btn_choose = "Drop images here or click to choose";
                no_icon = "ace-icon fa fa-picture-o";

                whitelist_ext = ["jpeg", "jpg", "png", "gif", "bmp"];
                whitelist_mime = ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"];
            } else {
                btn_choose = "Drop files here or click to choose";
                no_icon = "ace-icon fa fa-cloud-upload";

                whitelist_ext = null;//all extensions are acceptable
                whitelist_mime = null;//all mimes are acceptable
            }
            var file_input = $('#id-input-file-3');
            file_input
                .ace_file_input('update_settings',
                    {
                        'btn_choose': btn_choose,
                        'no_icon': no_icon,
                        'allowExt': whitelist_ext,
                        'allowMime': whitelist_mime
                    })
            file_input.ace_file_input('reset_input');

            file_input
                .off('file.error.ace')
                .on('file.error.ace', function (e, info) {
                    //console.log(info.file_count);//number of selected files
                    //console.log(info.invalid_count);//number of invalid files
                    //console.log(info.error_list);//a list of errors in the following format

                    //info.error_count['ext']
                    //info.error_count['mime']
                    //info.error_count['size']

                    //info.error_list['ext']  = [list of file names with invalid extension]
                    //info.error_list['mime'] = [list of file names with invalid mimetype]
                    //info.error_list['size'] = [list of file names with invalid size]


                    /**
                     if( !info.dropped ) {
							//perhapse reset file field if files have been selected, and there are invalid files among them
							//when files are dropped, only valid files will be added to our file array
							e.preventDefault();//it will rest input
						}
                     */


                    //if files have been selected (not dropped), you can choose to reset input
                    //because browser keeps all selected files anyway and this cannot be changed
                    //we can only reset file field to become empty again
                    //on any case you still should check files with your server side script
                    //because any arbitrary file can be uploaded by user and it's not safe to rely on browser-side measures
                });


            /**
             file_input
             .off('file.preview.ace')
             .on('file.preview.ace', function(e, info) {
						console.log(info.file.width);
						console.log(info.file.height);
						e.preventDefault();//to prevent preview
					});
             */

        });

        $('#spinner1').ace_spinner({
            value: 0,
            min: 0,
            max: 200,
            step: 10,
            btn_up_class: 'btn-info',
            btn_down_class: 'btn-info'
        })
            .closest('.ace-spinner')
            .on('changed.fu.spinbox', function () {
                //console.log($('#spinner1').val())
            });
        $('#spinner2').ace_spinner({
            value: 0,
            min: 0,
            max: 10000,
            step: 100,
            touch_spinner: true,
            icon_up: 'ace-icon fa fa-caret-up bigger-110',
            icon_down: 'ace-icon fa fa-caret-down bigger-110'
        });
        $('#spinner3').ace_spinner({
            value: 0,
            min: -100,
            max: 100,
            step: 10,
            on_sides: true,
            icon_up: 'ace-icon fa fa-plus bigger-110',
            icon_down: 'ace-icon fa fa-minus bigger-110',
            btn_up_class: 'btn-success',
            btn_down_class: 'btn-danger'
        });
        $('#spinner4').ace_spinner({
            value: 0,
            min: -100,
            max: 100,
            step: 10,
            on_sides: true,
            icon_up: 'ace-icon fa fa-plus',
            icon_down: 'ace-icon fa fa-minus',
            btn_up_class: 'btn-purple',
            btn_down_class: 'btn-purple'
        });

        //$('#spinner1').ace_spinner('disable').ace_spinner('value', 11);
        //or
        //$('#spinner1').closest('.ace-spinner').spinner('disable').spinner('enable').spinner('value', 11);//disable, enable or change value
        //$('#spinner1').closest('.ace-spinner').spinner('value', 0);//reset to 0


        //datepicker plugin
        //link
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })
            //show datepicker when clicking on the icon
            .next().on(ace.click_event, function () {
            $(this).prev().focus();
        });

        //or change it into a date range picker
        $('.input-daterange').datepicker({autoclose: true});


        //to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
        $('input[name=date-range-picker]').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            }
        })
            .prev().on(ace.click_event, function () {
            $(this).next().focus();
        });


        $('#timepicker1').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false,
            disableFocus: true,
            icons: {
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down'
            }
        }).on('focus', function () {
            $('#timepicker1').timepicker('showWidget');
        }).next().on(ace.click_event, function () {
            $(this).prev().focus();
        });


        if (!ace.vars['old_ie']) $('#date-timepicker1').datetimepicker({
            //format: 'MM/DD/YYYY h:mm:ss A',//use this option to display seconds
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-arrows ',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
            }
        }).next().on(ace.click_event, function () {
            $(this).prev().focus();
        });


        $('#colorpicker1').colorpicker();
        //$('.colorpicker').last().css('z-index', 2000);//if colorpicker is inside a modal, its z-index should be higher than modal'safe

        $('#simple-colorpicker-1').ace_colorpicker();
        //$('#simple-colorpicker-1').ace_colorpicker('pick', 2);//select 2nd color
        //$('#simple-colorpicker-1').ace_colorpicker('pick', '#fbe983');//select #fbe983 color
        //var picker = $('#simple-colorpicker-1').data('ace_colorpicker')
        //picker.pick('red', true);//insert the color if it doesn't exist


        $(".knob").knob();


        var tag_input = $('#form-field-tags');
        try {
            tag_input.tag(
                {
                    placeholder: tag_input.attr('placeholder'),
                    //enable typeahead by specifying the source array
                    source: ace.vars['US_STATES'],//defined in ace.js >> ace.enable_search_ahead
                    /**
                     //or fetch data from database, fetch those that match "query"
                     source: function(query, process) {
						  $.ajax({url: 'remote_source.php?q='+encodeURIComponent(query)})
						  .done(function(result_items){
							process(result_items);
						  });
						}
                     */
                }
            )

            //programmatically add/remove a tag
            var $tag_obj = $('#form-field-tags').data('tag');
            $tag_obj.add('Programmatically Added');

            var index = $tag_obj.inValues('some tag');
            $tag_obj.remove(index);
        } catch (e) {
            //display a textarea for old IE, because it doesn't support this plugin or another one I tried!
            tag_input.after('<textarea id="' + tag_input.attr('id') + '" name="' + tag_input.attr('name') + '" rows="3">' + tag_input.val() + '</textarea>').remove();
            //autosize($('#form-field-tags'));
        }


        /////////
        $('#modal-form input[type=file]').ace_file_input({
            style: 'well',
            btn_choose: 'Drop files here or click to choose',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'large'
        })

        //chosen plugin inside a modal will have a zero width because the select element is originally hidden
        //and its width cannot be determined.
        //so we set the width after modal is show
        $('#modal-form').on('shown.bs.modal', function () {
            if (!ace.vars['touch']) {
                $(this).find('.chosen-container').each(function () {
                    $(this).find('a:first-child').css('width', '210px');
                    $(this).find('.chosen-drop').css('width', '210px');
                    $(this).find('.chosen-search input').css('width', '200px');
                });
            }
        })
        /**
         //or you can activate the chosen plugin after modal is shown
         //this way select element becomes visible with dimensions and chosen works as expected
         $('#modal-form').on('shown', function () {
					$(this).find('.modal-chosen').chosen();
				})
         */


        $(document).one('ajaxloadstart.page', function (e) {
            autosize.destroy('textarea[class*=autosize]')

            $('.limiterBox,.autosizejs').remove();
            $('.daterangepicker.dropdown-menu,.colorpicker.dropdown-menu,.bootstrap-datetimepicker-widget.dropdown-menu').remove();
        });

    });
</script>
<!--------------------------------- This script for chosen and date picher and other tag end ----------------------------->


<!--------------------------------- This script for js data table start ----------------------------->
<script type="text/javascript">
    var myTable;
    jQuery(function ($) {
        //initiate dataTables plugin
        myTable =
            $('#dynamic-table')
                //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                .DataTable({
                    bAutoWidth: false,
                    /* "aoColumns": [
                      { "bSortable": false },
                      null, null,null, null, null, null, null,
                      { "bSortable": false }
                    ], */
                    "aaSorting": [],


                    //"bProcessing": true,
                    //"bServerSide": true,
                    //"sAjaxSource": "http://127.0.0.1/table.php",

                    //,
                    //"sScrollY": "200px",
                    //"bPaginate": false,

                    //"sScrollX": "100%",
                    //"sScrollXInner": "120%",
                    //"bScrollCollapse": true,
                    //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
                    //you may want to wrap the table inside a "div.dataTables_borderWrap" element

                    //"iDisplayLength": 50


                    select: {
                        style: 'multi'
                    }
                });

        var test = $("#dynamic-table thead").children().children();
        var niddle = new Array('Action', 'Update Status');
        var match = new Array();

        for (i = 0; i < test.length; i++) {
            if (!niddle.includes($(test[i]).html())) {
                match.push(i);
            }
        }


        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

        new $.fn.dataTable.Buttons(myTable, {
            buttons: [
                {
                    "extend": "colvis",
                    "text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    columns: ':not(:first):not(:last)'
                },
                {
                    "extend": "copy",
                    "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "csv",
                    "text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        //columns: ':not(:first):not(:last)'
                        //columns: ':not(:last)'
                        columns: match
                    }
                },
                {
                    "extend": "excel",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        //columns: ':not(:first):not(:last)'
                        //columns: ':not(:last)'
                        columns: match
                    }
                },
                {
                    "extend": "pdf",
                    "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "print",
                    "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        //columns: ':not(:first):not(:last)'
                        //columns: ':not(:last)'
                        columns: match
                    },
                    autoPrint: true,
                    //autoPrint: false,
                    message: '<div style="color:red;text-align:center;width:100%;">The Franchise Management Company Ltd</div>'
                }
            ]
        });
        myTable.buttons().container().appendTo($('.tableTools-container'));

        //style the message box
        var defaultCopyAction = myTable.button(1).action();
        myTable.button(1).action(function (e, dt, button, config) {
            defaultCopyAction(e, dt, button, config);
            $('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
        });


        var defaultColvisAction = myTable.button(0).action();
        myTable.button(0).action(function (e, dt, button, config) {

            defaultColvisAction(e, dt, button, config);


            if ($('.dt-button-collection > .dropdown-menu').length == 0) {
                $('.dt-button-collection')
                    .wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
                    .find('a').attr('href', '#').wrap("<li />")
            }
            $('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
        });

        ////

        setTimeout(function () {
            $($('.tableTools-container')).find('a.dt-button').each(function () {
                var div = $(this).find(' > div').first();
                if (div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
                else $(this).tooltip({container: 'body', title: $(this).text()});
            });
        }, 500);


        myTable.on('select', function (e, dt, type, index) {
            if (type === 'row') {
                $(myTable.row(index).node()).find('input:checkbox').prop('checked', true);
            }
        });
        myTable.on('deselect', function (e, dt, type, index) {
            if (type === 'row') {
                $(myTable.row(index).node()).find('input:checkbox').prop('checked', false);
            }
        });


        /////////////////////////////////
        //table checkboxes
        $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

        //select/deselect all rows according to table header checkbox
        $('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function () {
            var th_checked = this.checked;//checkbox inside "TH" table header

            $('#dynamic-table').find('tbody > tr').each(function () {
                var row = this;
                if (th_checked) myTable.row(row).select();
                else myTable.row(row).deselect();
            });
        });

        //select/deselect a row when the checkbox is checked/unchecked
        $('#dynamic-table').on('click', 'td input[type=checkbox]', function () {
            var row = $(this).closest('tr').get(0);
            if (this.checked) myTable.row(row).deselect();
            else myTable.row(row).select();
        });


        $(document).on('click', '#dynamic-table .dropdown-toggle', function (e) {
            e.stopImmediatePropagation();
            e.stopPropagation();
            e.preventDefault();
        });


        //And for the first simple table, which doesn't have TableTools or dataTables
        //select/deselect all rows according to table header checkbox
        var active_class = 'active';
        $('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function () {
            var th_checked = this.checked;//checkbox inside "TH" table header

            $(this).closest('table').find('tbody > tr').each(function () {
                var row = this;
                if (th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
                else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
            });
        });

        //select/deselect a row when the checkbox is checked/unchecked
        $('#simple-table').on('click', 'td input[type=checkbox]', function () {
            var $row = $(this).closest('tr');
            if ($row.is('.detail-row ')) return;
            if (this.checked) $row.addClass(active_class);
            else $row.removeClass(active_class);
        });


        /********************************/
        //add tooltip for small view action buttons in dropdown menu
        $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});

        //tooltip placement on right or left
        function tooltip_placement(context, source) {
            var $source = $(source);
            var $parent = $source.closest('table')
            var off1 = $parent.offset();
            var w1 = $parent.width();

            var off2 = $source.offset();
            //var w2 = $source.width();

            if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
            return 'left';
        }


        /***************/
        $('.show-details-btn').on('click', function (e) {
            e.preventDefault();
            $(this).closest('tr').next().toggleClass('open');
            $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
        });
        /***************/


        /**
         //add horizontal scrollbars to a simple table
         $('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
         {
					horizontal: true,
					styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
					size: 2000,
					mouseWheelLock: true
				  }
         ).css('padding-top', '12px');
         */


    })
</script>

<!--------------------------------- This script for js data table end ----------------------------->


<script type="text/javascript">

    $(document).ready(function () {

        $('.fancybox').fancybox({

            padding: 0,

            openEffect: 'elastic',

            openSpeed: 150,

            closeEffect: 'elastic',

            closeSpeed: 150,

            maxWidth: "100%",

            autoSize: true,

            autoScale: true,

            fitToView: true,

            helpers: {

                title: {

                    type: 'inside'

                },

                overlay: {

                    css: {

                        'background': 'rgba(0,0,2,0.3)'

                    }

                }

            }

        });

        $('.fancyboxview').fancybox({

            padding: 0,

            openEffect: 'elastic',

            openSpeed: 150,


            closeEffect: 'elastic',

            closeSpeed: 150,

            maxWidth: "100%",

            autoSize: true,

            autoScale: true,

            fitToView: true,


            helpers: {

                title: {

                    type: 'inside'

                },

                overlay: {

                    css: {

                        'background': 'rgba(0,0,2,0.3)'

                    }

                }

            }

        });

    });

</script>


</body>

</html>
