@extends('layouts.master')
@section('page_title', __('General'))

@push('css')
    <link href="/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />

    <link href="/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Emoji Picker -->
    <link href="/plugins/emoji-picker/css/emoji.css" rel="stylesheet" type="text/css">
    <!-- Datatable -->
    <link href="/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <section class="content-header">
        <h1>
            {{__('Schedule Posts')}}
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>{{__('Set up Auto Post Schedule!')}}</h4>
            <p>{{__('This feature allows you to set auto post to all your social acounts!')}}</p>
        </div>
        <form role="form" method="post" id="post_queque_form">
            <div class="row">
                <div class="col-md-8">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="box box-success">
                        <div class="box-header">
                            <div class="box-title">
                                <span>{{__('New Post Schedule!')}}</span>
                            </div>
                        </div>
                        <div class="box-body form">
                            {{csrf_field()}}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('Message')}}</label>
                                            <p class="lead emoji-picker-container">
                                                <textarea class="form-control  textarea-control" rows="10" name="message" data-emojiable="true">{{old('message')}}</textarea>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box box-info">
                                            <div class="box-header">
                                                <div class="box-title">
                                                    <i class="fa fa-picture-o"></i>{{__('Attach Image')}}
                                                </div>
                                            </div>
                                            <div class="box-body">
                                                <div class="dup row">
                                                    <div class="col-md-12">
                                                        <span class="help-block"> <b>{{__('Requirement!')}}</b> {{__('Width and Height should be between 350px and 1000px, Aspect Ratio: 1/1. You may crop and resize the image on the file manager, after upload')}} </span>
                                                        <div class="dup-group row">
                                                            @if(collect(old('image_path'))->count())
                                                                @php
                                                                    $i = 0;
                                                                @endphp
                                                                @foreach(collect(old('image_path')) as $image_path)
                                                                    <div id="lfm-image{{($i == 0)? '' : '-'.$i}}" class="fileinput dup-item col-md-4 lfm-container" style="margin-bottom: 5px;">
                                                                        <img class="thumbnail holder" data-trigger="fileinput" style="width: 200px; height: 150px;"/>
                                                                        <input class="form-control lfm-input" type="hidden" name="image_path[]" value="{{$image_path}}" readonly>
                                                                        <div>
                                                                            <button data-input="lfm-input" data-preview="holder" class="btn default lfm-image-btn" type="button">
                                                                                {{__('Choose')}}
                                                                            </button>
                                                                            <a href="javascript:;" class="btn btn-danger dup-delete">
                                                                                {{__('Delete')}}
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    @php
                                                                        $i++;
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div id="lfm-image" class="fileinput dup-item col-md-4 lfm-container" style="margin-bottom: 5px;">
                                                                    <img class="thumbnail holder" data-trigger="fileinput" style="width: 200px; height: 150px;"/>
                                                                    <input class="form-control lfm-input" type="hidden" value="" name="image_path[]" readonly>
                                                                    <div>
                                                                        <button data-input="lfm-input" data-preview="holder" class="btn default lfm-image-btn" type="button">
                                                                            {{__('Choose')}}
                                                                        </button>
                                                                        <a href="javascript:;" class="btn btn-danger dup-delete">
                                                                            {{__('Delete')}}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <hr/>
                                                        <div class="">
                                                            <a href="javascript:;"  class="btn btn-success dup-add">
                                                                <i class="fa fa-plus"></i> {{__('Add')}}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box box-info">
                                            <div class="box-header">
                                                <div class="box-title">
                                                    <i class="fa fa-video-camera"></i>{{__('Attach Video')}}
                                                </div>
                                            </div>
                                            <div class="box-body">
                                                <div class="dup row">
                                                    <div class="col-md-12">
                                                        <span class="help-block"> <b>{{__('Note!')}}</b> {{__('Video upload is only available for Facebook for now!')}}  </span>
                                                        <div class="dup-group row">
                                                            @if(collect(old('video_path'))->count())
                                                                @php
                                                                    $i = 0;
                                                                @endphp
                                                                @foreach(collect(old('video_path')) as $video_path)
                                                                    <div id="lfm-video{{($i == 0)? '' : '-'.$i}}" class="fileinput dup-item col-md-4 lfm-container" style="margin-bottom: 5px;">
                                                                        <img class="thumbnail holder" data-trigger="fileinput" style="width: 200px; height: 150px;"/>
                                                                        <input class="form-control lfm-input" type="hidden" name="video_path[]" value="{{$video_path}}" readonly>
                                                                        <div>
                                                                            <button data-input="lfm-input" data-preview="holder" class="btn default lfm-video-btn" type="button">
                                                                                {{__('Choose')}}
                                                                            </button>
                                                                            <a href="javascript:;" class="btn btn-danger dup-delete">
                                                                                {{__('Delete')}}
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    @php
                                                                        $i++;
                                                                    @endphp
                                                                @endforeach
                                                            @else
                                                                <div id="lfm-video" class="fileinput dup-item col-md-4 lfm-container" style="margin-bottom: 5px;">
                                                                    <img class="thumbnail holder" data-trigger="fileinput" style="width: 200px; height: 150px;"/>
                                                                    <input class="form-control lfm-input" value="" type="hidden" name="video_path[]" readonly>
                                                                    <div>
                                                                        <button data-input="lfm-input" data-preview="holder" class="btn default lfm-video-btn" type="button">
                                                                            {{__('Choose')}}
                                                                        </button>
                                                                        <a href="javascript:;" class="btn btn-danger dup-delete">
                                                                            {{__('Delete')}}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <hr/>
                                                        <div class="">
                                                            <a href="javascript:;"  class="btn btn-success dup-add">
                                                                <i class="fa fa-plus"></i> {{__('Add')}}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">{{__('Choose Schedule Date')}}</label>
                                            <div class="col-md-12">
                                                <div class="input-group date form_datetime input-medium bs-datetime" data-date-start-date="+0d">
                                                    <input type="text" size="16" name="schedule_date" value="{{old('schedule_date')}}" class="form-control">
                                                    <span class="input-group-addon">
                                                        <button class="btn default date-set" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <span class="help-block"> {{__('Select date')}} </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions noborder">
                                <button type="reset" class="btn default">{{__('Reset')}}</button>
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary blue">{{__('Add to Queue')}}</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true" aria-expanded="false">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="javascript:;" id="post_now"> {{__('Post Now')}} </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="box box-default ">
                        <div class="box-header">
                            <div class="box-title">
                                <span>{{__('Select Targets')}}</span>
                            </div>
                        </div>
                        <div class="box-body form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="multiple" class="control-label">{{__('Select')}} <b>{{__('Instagram')}}</b> {{__('Accounts')}}</label>
                                            <select id="multiple" name="instagram_ids[]" class="form-control select2-multiple" multiple>
                                                <option></option>
                                                @foreach($ig_accounts as $ig_account)
                                                    <option value="{{$ig_account->id}}" {{(collect(old('instagram_ids'))->contains($ig_account->id)) ? 'selected':''}}>{{$ig_account->profile_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="multiple" class="control-label">{{__('Select')}} <b>{{__('Twitter')}}</b> {{__('Accounts')}}</label>
                                            <select id="multiple" name="twitter_ids[]" class="form-control select2-multiple" multiple>
                                                <option></option>
                                                @foreach($tw_accounts as $tw_account)
                                                    <option value="{{$tw_account->id}}" {{(collect(old('twitter_ids'))->contains($tw_account->id)) ? 'selected':''}}>{{$tw_account->profile_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="multiple" class="control-label">{{__('Select')}} <b>{{__('Facebook')}}</b> {{__('Accounts')}}</label>
                                            <select id="multiple" name="facebook_ids[]" class="form-control select2-multiple" multiple>
                                                <option></option>
                                                @foreach($fb_accounts as $fb_account)
                                                    <option value="{{$fb_account->id}}" {{(collect(old('facebook_ids'))->contains($fb_account->id)) ? 'selected':''}}>{{$fb_account->profile_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="multiple" class="control-label">{{__('Select')}} <b>{{__('Facebook Pages')}}</b></label>
                                            <select id="multiple" name="fb_page_ids[]" class="form-control select2-multiple" multiple>
                                                <option></option>
                                                @foreach($fb_pages as $fb_page)
                                                    <option value="{{$fb_page->id}}" {{(collect(old('fb_page_ids'))->contains($fb_page->id)) ? 'selected':''}}>{{$fb_page->page_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="multiple" class="control-label">{{__('Select')}} <b>{{__('Facebook Groups')}}</b></label>
                                            <select id="multiple" name="fb_group_ids[]" class="form-control select2-multiple" multiple>
                                                <option></option>
                                                @foreach($fb_groups as $fb_group)
                                                    <option value="{{$fb_group->id}}" {{(collect(old('fb_group_ids'))->contains($fb_group->id)) ? 'selected':''}}>{{$fb_group->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="box box-default">
                        <div class="box-header">
                            <div class="box-title">
                                <span>{{__('Instagram Specific')}}</span>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-horizontal form-bordered">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-8">{{__('Post as Single')}}</label>
                                        <div class="col-md-4">
                                            <input type="checkbox" {{(old('message'))? ((old('ig_single') == 'yes')? 'checked': ''): ''}} value="yes" class="make-switch" name="ig_single"  data-size="mini">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-8">{{__('Post as Album')}}</label>
                                        <div class="col-md-4">
                                            <input type="checkbox" {{(old('message'))? ((old('ig_story') == 'yes')? 'checked': ''): 'checked'}} value="yes" class="make-switch" name="ig_story" data-size="mini">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="box box-default">
                        <div class="box-header">
                            <div class="box-title">
                                <span>{{__('Twitter Specific')}}</span>
                            </div>
                        </div>
                        <div class="box-body form">
                            <div class="form-horizontal form-bordered">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-8">{{__('Post as Status')}}</label>
                                        <div class="col-md-4">
                                            <input type="checkbox" {{((old('message')))? ((old('tw_status') == 'yes')? 'checked': ''): 'checked'}} name="tw_status" value="yes" class="make-switch" data-size="mini">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-8">{{__('Post as Media')}}</label>
                                        <div class="col-md-4">
                                            <input type="checkbox" {{((old('message')))? ((old('tw_media') == 'yes')? 'checked': ''): 'checked'}} name="tw_media" value="yes" class="make-switch" data-size="mini">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="box box-default">
                        <div class="box-header">
                            <div class="box-title">
                                <span>{{__('Facebook Specific')}}</span>
                            </div>
                        </div>
                        <div class="box-body form">
                            <div class="form-horizontal form-bordered">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-8">{{__('Post as Status')}}</label>
                                        <div class="col-md-4">
                                            <input type="checkbox" {{((old('message')))? ((old('fb_status') == 'yes')? 'checked': ''): 'checked'}} name="fb_status" value="yes" class="make-switch" data-size="mini">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-8">{{__('Post as Media')}}</label>
                                        <div class="col-md-4">
                                            <input type="checkbox" {{((old('message')))? ((old('fb_media') == 'yes')? 'checked': ''): 'checked'}} name="fb_media" value="yes" class="make-switch" data-size="mini">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-8">{{__('Post as Link')}}</label>
                                        <div class="col-md-4">
                                            <input type="checkbox" {{((old('message')))? ((old('fb_link') == 'yes')? 'checked': ''): ''}} name="fb_link" value="yes" class="make-switch" data-size="mini">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{old('video_title')}}" name="video_title" placeholder="{{__('Video Title')}}" />
                                            <span class="help-block"><b>{{__('Note!')}}</b> {{__('This is required for posting of videos.')}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{old('link_url')}}" name="link_url" placeholder="{{__('Link Url (e.g http://www.google.com)')}}" />
                                            <span class="help-block"><b>{{__('Note!')}}</b> {{__('This is required for posting of links.')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="callout callout-info">
            <h4>{{__('Schedule List')}}</h4>
            @if($alerts = Session::get('alerts'))
                @foreach($alerts as $alert)
                    <div class="alert alert-{{$alert['type']}} alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {{$alert['msg']}}
                    </div>
                @endforeach
            @endif
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="box box-success">
                    <div class="box-header">
                        <div class="box-title">
                            <i class="icon-diamond font-red"></i>
                            <span></span>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-hover table-bordered" id="table_data">
                            <thead>
                            <tr>
                                <th>{{__('Message')}}</th>
                                <th>{{__('Instagram')}}</th>
                                <th>{{__('Facebook')}}</th>
                                <th>{{__('Twitter')}}</th>
                                <th>{{__('Facebook Pages')}}</th>
                                <th>{{__('Facebook Groups')}}</th>
                                <th>{{__('Schedule Time')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script src="/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="/plugins/morris/raphael-min.js" type="text/javascript"></script>

    <script src="/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>

    <!-- Emoji Picker -->
    <script src="/plugins/emoji-picker/js/config.js" type="text/javascript"></script>
    <script src="/plugins/emoji-picker/js/util.js" type="text/javascript"></script>
    <script src="/plugins/emoji-picker/js/jquery.emojiarea.js" type="text/javascript"></script>
    <script src="/plugins/emoji-picker/js/emoji-picker.js" type="text/javascript"></script>

    <!-- Datatables -->
    <script src="/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

    <!-- File Manager -->
    <script src="/vendor/laravel-filemanager/js/lfm.js" type="text/javascript"></script>
    <script>
        var ComponentsDateTimePickers = function() {
            jQuery().datepicker && $(".date-picker").datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: !0
            })
        }
        var ElementDouble = function () {
            function listenDelete() {
                // Listen to new delete...
                $('.dup-delete').on('click', function(e){
                    e.preventDefault();
                    var dupItem = $(this).closest('.dup-item');
                    dupItem.find('img').attr('src', '').trigger('change');
                    dupItem.find(':input').val('').trigger('change');
                    dupItem.fadeOut(1000, function(){
                        // Make sure you don't remove the last item...
                        var dupItems = dupItem.closest('.dup').find('.dup-item');
                        if(dupItems.length > 1){
                            // Delete item
                            dupItem.remove();
                        }
                    });
                })
            }
            var uniqueId = 1;
            return {
                //main function to initiate the module
                init: function () {
                    // Listen to new add...
                    $('.dup-add').on('click', function(e){
                        e.preventDefault();
                        var dupGroup = $(this).closest('.dup').find('.dup-group');
                        // Duplicate Item...
                        var newItem = dupGroup.find('.dup-item').first().clone();
                        // Perform cleaning on newItem...
                        newItem.find('img').attr('src', '').trigger('change');
                        newItem.find(':input').val('').trigger('change');
                        // Insert a uniqueId..
                        newItem.attr('id', newItem.attr('id') + '-' + uniqueId);
                        // Append..
                        newItem.appendTo(dupGroup).fadeIn(1000);

                        listenDelete();
                        // Initialize Filemanager...
                        $('.lfm-image-btn').filemanager('image');
                        $('.lfm-video-btn').filemanager('file');
                        uniqueId++
                    });

                    listenDelete();
                    // Initialize Filemanager...
                    $('.lfm-image-btn').filemanager('image');
                    $('.lfm-video-btn').filemanager('file');
                }
            };
        }();
        var initTable = function () {
            var table = $('#table_data');

            var oTable = table.DataTable({

                processing: true,
                serverSide: true,
                "ajax": {
                    "async": true,
                    "type": "POST",
                    "url": '{{route('post-queue-data')}}',
                    "data": {}
                },
                columns: [
                    {data: 'msg_body'},
                    {data: 'instagram_ids'},
                    {data: 'facebook_ids'},
                    {data: 'twitter_ids'},
                    {data: 'fb_page_ids'},
                    {data: 'fb_group_ids'},
                    {data: 'schedule_time'},
                    {data: 'status', orderable: false},
                    {data: 'action', orderable: false},
                ],

                "language": {
                    "aria": {
                        "sortAscending": ": {{__('activate to sort column ascending')}}",
                        "sortDescending": ": {{__('activate to sort column descending')}}"
                    },
                    "emptyTable": "{{__('No data available in table')}}",
                    "info": "{{__('Showing').' _START_ '.__('to').' _END_ '.__('of').' _TOTAL_ '.__('entries')}}",
                    "infoEmpty": "{{__('No entries found')}}",
                    "infoFiltered": "{{'(filtered1 '.__('from').' _MAX_ '.__('total entries').')'}}",
                    "lengthMenu": "{{'_MENU_ '.__('entries')}}",
                    "search": "{{__('Search:')}}",
                    "zeroRecords": "{{__('No matching records found')}}"
                },

                // setup responsive extension: http://datatables.net/extensions/responsive/
                responsive: true,

                //"ordering": false, disable column ordering
                //"paging": false, disable pagination

                "order": [
                    [0, 'asc']
                ],

                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "pageLength": 20,

                //"dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            });
        }

        jQuery(document).ready(function() {
            ComponentsDateTimePickers();

            // Initialize Multiple selector...
            $.fn.select2.defaults.set("theme", "bootstrap");
            var s = "{{__('Select Account')}}";
            $(".select2, .select2-multiple").select2({
                placeholder: s,
                width: null
            })

            // Initialize Table...
            initTable();

            // Initialize DateTime Picker...
            $(".form_datetime").datetimepicker({
                autoclose: true,
                isRTL: App.isRTL(),
                format: "yyyy-mm-dd hh:ii:ss",
                fontAwesome: true,
                pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
            });

            //Initialize Emoji...
            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: '/plugins/emoji-picker/img/',
                popupButtonClasses: 'fa fa-smile-o'
            });
            window.emojiPicker.discover();

            // Set post_now btn listener
            $('#post_now').on('click', function(e){
                $('<input />').attr('type', 'hidden')
                    .attr('name', "post_now")
                    .attr('value', "1")
                    .appendTo('#post_queque_form');
                $('#post_queque_form').submit();
            })

            // Initialize Element Double...
            ElementDouble.init();

            // Initialize Thumbnail of selected Image and Video...
            $("input[name~='image_path[]']").each(function(){
                // Check if it is empty...
                if($(this).val().length > 3){
                    $HOLDER = $(this).closest('.dup-item').find('.holder');
                    url = $(location).attr('protocol') + '//' + $(location).attr('host') + $(this).val();

                    $HOLDER.attr('src', url).trigger('change');
                }
            });

            $("input[name~='video_path[]']").each(function() {
                // Check if it is empty...
                if ($(this).val().length > 3) {
                    $HOLDER = $(this).closest('.dup-item').find('.holder');
                    var filename = $(this).val().substring($(this).val().lastIndexOf('/')+1);
                    filename  = (filename.length > 5)? filename.substring(0, 3) + '...+' + filename.split('.').pop() : filename;
                    url = 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=' + filename.toUpperCase();

                    $HOLDER.attr('src', url).trigger('change');
                }
            });
        });
    </script>
@endpush