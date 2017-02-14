$(document).ready(function() {
    $('.dropdown-menu[atribute] li').on('click',function(){
        data = {};
        $this = $(this);
        data['value']=$this.attr('state');
        data['atribute']=$this.parent().attr('atribute');
        els=$('.table [name="selection[]"]:checked');
        if(els.length==0){
            show_msg('You should mark users.','err');
            return false;
        }
        data['keys']=[]
        for(i=0;i<els.length;i++){
            data['keys'].push(els[i].value)
        }
        url = location.pathname.replace('index','');
        url += '/ajax_multi_set';
        url = url.replace('//','/');

        $.post(url,data,function(response) {
            parse_input_json(response)
        },'json');
    })

    $(".field-tarificatortable-credits").hide();
    $("input[name='hide_the_tarifTable']" ).on( "click", analiz_tarif_type);

    function analiz_tarif_type(){
        if($("input[name='hide_the_tarifTable']").attr("checked") == 'checked') {
            $("#w1" ).show(500);
            $(".field-tarificatortable-credits").hide();
            $("input[name='hide_the_tarifTable']").attr('checked', false);
        }
        else{
            $("#w1").hide();
            $(".field-tarificatortable-credits").show(500);
            $("input[name='hide_the_tarifTable']").attr('checked', true);
        }
    }
})