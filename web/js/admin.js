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
        $.post("/user/admin/ajax_multi_set",data,function(response) {
            parse_input_json(response)
        },'json');
    })

})