$(document).ready(function() {
    $('[atribute]').on('click',function(){
        data = {};
        $this = $(this);
        data['value']=$this.attr('state');
        data['atribute']=$this.attr('atribute');
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
})