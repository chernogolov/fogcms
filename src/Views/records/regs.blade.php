@push('scripts')
        <script src="{{asset('/vendor/fogcms/js/vendor/jstree/jstree.js')}}"></script>
        <script src="{{asset('/vendor/fogcms/js/vendor/summernote/summernote-bs4.min.js')}}"></script>
@endpush
@push('styles')
        <link href="{{ asset('/vendor/fogcms/js/vendor/summernote/summernote-bs4.css') }}" rel="stylesheet">
@endpush
<div class="row">
    <div id="jstree"></div>
    <br>
    <br>
</div>
<script type="text/javascript">
    $(function()
    {
        $.jstree.defaults.core.worker = false;
        $('#jstree').jstree(
            {
                'core' :
                {
                    'data' :
                    [
                        @foreach($nodes as $n)
                            @if(isset($nodes->keyBy('id')[$n->parent_id])) @php $parent = $n->parent_id; @endphp @else @php $parent = '#'; @endphp @endif
                            {
                                "id" : "{{$n->id}}",
                                "parent" : "{{$parent}}",
                                "text" : "{{str_limit($n->name, 20)}}",
                                @if(!isset($n->access))
                                    "state" : {"disabled" : true},
                                @endif
                                "url" : "{{ route('reg_records', ['id' => $n->id]) }}",
                                "href" : "{{ route('reg_records', ['id' => $n->id]) }}",
                                "a_attr" : {"title" : "{{$n->name}} ID {{$n->id}}"} },

                        @endforeach

                        @if (Gate::allows('view-admin'))
                            {
                                "id" : "cart",
                                "parent" : "#",
                                "text" : "{{__('Trash')}}",
                                "url" : "/panel/trash",
                                "icon" : "mdi mdi-trash-can"
                            }
                        @endif
                    ]
                },
                "plugins" : [ "themes", "html_data", "state"],
                "state" : { "key" : "regs" }
            }
        ).on("ready.jstree", function(e,data){
                @if(isset($node->id))
                    $("#jstree").jstree("deselect_all");
                    $("#jstree").jstree("select_node", {{intval($node->id)}});
                @endif

                var url = '/panel/1';
                my_selected = $("#jstree").jstree("get_selected", null, true);
                if(my_selected!=undefined && parseInt(my_selected)>0)
                    url = '/panel/'+my_selected;


                destination = 'records';
                if($('#'+destination).html().length==0)
                {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {},
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            $('#'+destination).html(data);
                        },
                        beforeSend: function() {
                            $('#'+destination+' #head-image').html('<img src="/vendor/fogcms/img/load_min.gif" style="height: 22px;">&nbsp;&nbsp;Загрузка...');
                        },
                        error: function (msg) {
                            $('#'+destination).html(msg);
                        }
                    });
                }
        }).on("activate_node.jstree", function(e,data){
                    destination = 'records';
                    $.ajax({
                        url: data.node.original.url,
                        type: "POST",
                        data: {},
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            $('#'+destination+' #head-image').html('<img src="/vendor/fogcms/img/load_min.gif" style="height: 22px;">&nbsp;&nbsp;Загрузка...');
                        },
                        success: function (data) {
                            $('#'+destination).html(data);
                        },
                        error: function (msg) {
                            $('#'+destination).html(msg);
                        }
                    });
        });
    });
</script>

