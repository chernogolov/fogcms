$(function()
{
    $( '.fields-selector>li>a' ).on("click", function()
    {
        $('#btn_'+$(this).data('attr')).text($(this).text());
        $('#sf_'+$(this).data('attr')).attr('data-field', $(this).data('field')).data('field', $(this).data('field'));
    });

    $('body').on('click',function(){
        $('.searchData').html('');
    });

    $( document )
        .on("click", '.check_all', function()
        {
            if (!$(".check_all").is(":checked"))
                $(".for_check").prop("checked","");
            else
                $(".for_check").prop("checked","checked");
        })
        .on("click", '.editable', function()
        {
            if ($(".editable").is(":checked"))
                $(".form-editable").prop("disabled","");
            else
                $(".form-editable").prop("disabled","disabled");
        })
        .on("click", '.set-access', function(e)
        {
            id = $(this).data('id');
            if ($(this).is(":checked"))
            {
                $("#view_"+id).prop("checked","checked");
                $("#edit_"+id).prop("checked","checked");
                $("#delete_"+id).prop("checked","checked");
                $("#send"+id).prop("checked","checked");
                $("#h_view_"+id).val("1");
                $("#h_edit_"+id).val("1");
                $("#h_delete_"+id).val("1");
                $("#h_send_"+id).val("1");
            }
            else
            {
                $("#view_"+id).prop("checked","");
                $("#edit_"+id).prop("checked","");
                $("#delete_"+id).prop("checked","");
                $("#send"+id).prop("checked","");
                $("#h_view_"+id).val("0");
                $("#h_edit_"+id).val("0");
                $("#h_delete_"+id).val("0");
                $("#h_send_"+id).val("0");
            }
        })
        .on("input keyup", '.search-link', function()
        {
            var text = $(this).val();
            var id = $(this).data('id');
            if($(this).data('limit') != undefined)
                var limit = $(this).data('limit');
            else
                var limit = 20;
            var destination = 'list-'+id;
            var sel = $('#'+destination).data('vls') + '';
            var selarr = sel.split(',');
            delay(function(){

                if(text.length > 1)
                {
                    $.ajax({
                        url: "/search/"+id,
                        type: "POST",
                        data: {text:text,limit:limit},
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            $('#'+destination).html('<option disabled selected>&nbsp;&nbsp;Загрузка...</option>');
                        },
                        success: function (data) {
                            $('#'+destination).html(data);
                            if(typeof selarr == 'array' || typeof selarr == 'object')
                            {
                                $.each(selarr, function(index, value){
                                    $("#"+destination+" option[value='"+value+"']").attr("selected", "selected");
                                });
                            }
                            var filter = {[$('#similar-'+id).data('attr')] : { 0 :[$('#similar-'+id).data('attr'), '=', $("#list-" + id + " option:selected").val()]}};
                    $('#similar-'+id).removeClass('disabled').data("filter", filter);
                    $('#myModal' + id + ' span').html($("#list-" + id + " option:selected").text());
                },
                error: function (msg) {
                    $('#'+destination).html(msg);
                }
            });
        }
}, 600 );
})
//        .on("input", '.search-link', function()
//        {
//            console.log('asd');
//        })
.on('click', '.dl', function(e){
    $(this).each(function(){
        alert('sadasd');
    })
    var id = $(this).data('id');
    var rid = $(this).data('rid');
    $('#val-'+id).val(rid);
})
    .on('click', '.set_record', function(e){
        var id = $(this).data('id');
        var rvalue = $(this).data('value');
        var attr = $(this).data('attr');
        $('#id_'+attr).val(id);
        $('#sf_'+attr).val(rvalue);
        $('.searchData').html('');
    })
    //формы с классом submit
    .on("click", '.submit', function(e){
        e.preventDefault();
        var formId = $(this).attr('form');
        var destination = $(this).data('destination');
        var btn = $(this).data('btn');

        if($('body').hasClass('modal-open'))
        {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        }

        var url = $('#'+formId).prop('action');

        var formData = new FormData($('#'+formId)[0]);
        formData.append($(this).prop('name'), $(this).val());

        for(var pair of formData.entries())
        if(formData.get(pair[0]).size!= undefined && formData.get(pair[0]).size == 0)
            formData.delete(pair[0]);

        $.ajax({
            url: url,
            cache:false,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            type: "POST",
            beforeSend: function() {
                $('#'+destination+' #head-image').html('<img src="/vendor/fogcms/img/load_min.gif" style="height: 22px;">&nbsp;&nbsp;Загрузка...');
                $('#'+btn+'-btn').attr('disabled', 'disabled');
            },
            success: function (data) {
                $('#'+destination).html(data);
                $('#'+btn+'-btn').removeAttr('disabled')
            },
            error: function (data) {
                var errors = $.parseJSON(data.responseText);
                $.each(errors.errors, function (key, value) {
                    var id = key.replace(/\./gi, '_');
                    $('#'+id).html(value).removeClass('hidden');
                });
                $('#'+btn+'-btn').removeAttr('disabled')
            }
        });

    })
    //любая ссылка с классом ajax
    .on("click", '.ajax', function(e){
        e.preventDefault();
        var destination = 'records';
        var url = $(this).prop('href');
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {},
            type: "POST",
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

    })
    //пагинация
    .on("click", '.pagination .page-item .page-link', function(e){
        e.preventDefault();
        var destination = 'records';
        var url = $(this).prop('href');
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {},
            type: "POST",
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
    }).on("click", '.change_status', function(e) {
        e.preventDefault();
        var url = $(this).prop('href');
        var id = $(this).data("id");
        $.ajax({
            url: '/panel/status/' + id,
            data: {sid: $(this).data("sid")},
            type: "GET",
            beforeSend: function () {
                $('#status_' + id).html('<img src="/vendor/fogcms/img/load_min.gif">');
            },
            success: function (data) {
                $('#status_' + id).html(data['status'])
                $('#item_' + id).prop('class', data['class']);
            },
            error: function (msg) {
                $('#records').html(msg);
            }
        });
    }).on("blur", '.change_rating', function(e){
        var rid = $(this).data('id');
        var rvalue = $(this).val();
        if(rvalue == undefined)
            rvalue = 0;
        $.ajax({
            url: '/panel/rate/' + rid,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: {rating: rvalue},
            beforeSend: function () {
            },
            success: function (data) {
                $('#rating-'+rid).css('border','green solid 1px');
            },
            error: function (msg) {
                $('#records').html(msg);
            }
        });
    })
        .on("keydown", '.change_rating', function(e){
            if(e.keyCode === 13) {
                e.preventDefault();
                var rid = $(this).data('id');
                var rvalue = $(this).val();
                if(rvalue == undefined)
                    rvalue = 0;
                $.ajax({
                    url: '/panel/rate/' + rid,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    data: {rating: rvalue},
                    beforeSend: function () {
                    },
                    success: function (data) {
                        $('#rating-'+rid).css('border','green solid 1px');
                    },
                    error: function (msg) {
                        $('#records').html(msg);
                    }
                });
            }
        }).on("click", '.on_off', function(e){
            e.preventDefault();
            var url = $(this).prop('href');
            var id = $(this).data("id");
            var sid = $(this).data("sid");
            $.ajax({
                url: '/panel/onoff/' + id + '/' + sid,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: {},
                beforeSend: function () {
                    $('#onoff_' + id).html('<img src="/img/load_min.gif">');
                },
                success: function (data) {
                    $('#onoff_' + id).html(data['status']);
                    $('#'+id).data("sid", data['change']);
                },
                error: function (msg) {
                    $('#records').html(msg);
                }
            });
        }).on("click", '#export', function(e){
            e.preventDefault();
            var formId = $(this).attr('form');
            var formData = new FormData($('#editForm')[0]);
            var url = $('#'+formId).prop('action');
            formData.append('export', '1');
            $.ajax({
                url: url,
                cache:false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                type: "POST",
                beforeSend: function() {
                    $('#export').html('<img src="/vendor/fogcms/img/load_min.gif">').attr('disabled',true);
                },
                success: function (data) {
                    console.log(data);
                    window.open('/'+data, '_blank');
                    window.close();
                    $('#export').html('<span class="glyphicon glyphicon-export"></span>&nbsp;Экспорт').removeAttr('disabled');
                },
                error: function (data) {
                    console.log(data);
                }
            });
    });

$( document ).on("click", '#create_tmp_user', function()
{
    if (!$(this).is(":checked"))
    {
        $('.user-form').each(function(){
            $(this).prop('disabled', true);
            $(this).val($(this).data('value'));
        });
    }
    else
    {
        $('.user-form').each(function(){
            $(this).prop('disabled', false);
            $(this).val('');
        });
    }

});

$('.checkbox').on('change', function(){
    if(this.checked == 0)
    {
        $('#h_'+this.id).val(0);
        $('#'+this.id).val(0);
    }
    else
    {
        $('#h_'+this.id).val(1);
        $('#'+this.id).val(1);
    }
});

$('.checkbox2').on('change', function(){
    this.value = this.checked ? 1 : 0;
}).change();

$('.close').on('click', function(){
    $('#file_' + $(this).prop('id')).remove();
});
});

function objDump(object) {
    var out = "";
    if(object && typeof(object) == "object"){
        for (var i in object) {
            out += i + ": " + object[i] + "\n";
        }
    } else {
        out = object;
    }
    alert(out);
}

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();