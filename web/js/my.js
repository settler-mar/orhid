var file_api = ( window.File && window.FileReader && window.FileList && window.Blob ) ? true : false;

$(document).ready(function() {
// checkbox when create tarificatorTable row

$('.open_menu').click(function(){
    if ($('#left_open').is(":hidden")) {
					$('#left_open').show().css({"left": "-250px"}).animate({"left": "0px"}, 300 );
					$("body").addClass("body_in");
					$("#main").animate({"left": "250px", "opacity": "0.5"}, 300 );


			 } else {
				$('#left_open').animate({"left": "-250px"}, 300 )
                $("#main").animate({"left": "0px", "opacity": "1"}, 300 );
				 setTimeout(function () {
				$('#left_open').hide();
				$("body").removeClass("body_in");
				}, 300);
}
	 return false;
});


$('.us_on').click(function(){
	$('.user_chat').animate({"left": "-670px"}, 400 );
	$('.chat_block').css({"right": "-670px", "position": "absolute"}).show().animate({"right": "0px"}, 300 );
	setTimeout(function () {
				$('.chat_block').css({"position": "relative"});
				}, 250);

	setTimeout(function () {
				$('.user_chat').hide();
				}, 500);

	 return false;
});

    var chosen_option={disable_search_threshold: 15}
     $("select.icon-select").chosenImage(chosen_option);
    $("select").not('.incon-select').chosen(chosen_option);
    $('[name*=country]').on('change',function(event) {
        $('[name*=city]').html('').trigger("chosen:updated");
        $.post('/city/get/'+this.value,function(data){
            city_list=$('[name*=city]');
            city_obj=[];
            for(i=0;i<data.length;i++){
                var opt=$('<option/>',{
                    value:data[i]['id'],
                    text:data[i]['city']+'('+data[i]['state']+')',
                });
                city_obj.push(opt)
            }
            city_list.append(city_obj);
            city_list.trigger("chosen:updated");
        },'json')
    });
    $('body').on('click','[longdesc]',function(){
    	enlarge(this);
    })

    $('.clear_photo').on('click',function(){
      $this=$(this).parent().parent();
      $this.find('img').parent().html('')
      $this.find('input').val('')
    })


    var slider = $('#headerimgs img');
    if (slider.length>0) {
        slide_pic=[];
        for(i=0;slider.length>i;i++){
            img=$(slider[i]).attr('src');
            slide=$('<div/>',{
                'class':'slide'+(i==0?' active':''),
                'style':'background-image: url('+img+');'
            });
            slide_pic.push(slide)
        }
        slider.remove();
        $('#headerimgs').append(slide_pic);
        setInterval(function() {
            sl=$('#headerimgs .active')
                .removeClass('active');
            index=sl.index();
            index++;
            slides=$('#headerimgs .slide');
            if(index>=slides.length){
                index=0
            };
            slides.eq(index).addClass('active')
        },5000)
    }


    file_img=$('[type=file]+div img');
    for(i=0;i<file_img.length;i++){
        $(file_img[i]).wrap($('<a/>', {
            'href': $(file_img[i]).attr('src'),
            'class':'fancy'
        }));
    }
    $('a.photo_people,a.fancy,.one_img a').jqPhotoSwipe({
      galleryOpen: function (gallery) {
        //with `gallery` object you can access all methods and properties described here http://photoswipe.com/documentation/api.html
        //console.log(gallery);
        //console.log(gallery.currItem);
        //console.log(gallery.getCurrentIndex());
        //gallery.zoomTo(1, {x:gallery.viewportSize.x/2,y:gallery.viewportSize.y/2}, 500);
        //gallery.toggleDesktopZoom();
      }
    });

    $('.girl_id_chose').on('change',function(){
        a=$('.chouse_girl_form a.send_gift');
        v=parseInt(this.value);
        if(!v)v=0;
        href=a.attr('base_href')+v+a.attr('dop_href');
        a.attr('href',href)
    })

    $('#nav-toggle').on('click',function(){
        $body=$('body')
        if($body.hasClass('menu_open')){
            $body.removeClass('menu_open')
        }else{
            $body.addClass('menu_open')
        }
    })
});

function init_file_prev(obj){
    el=obj.parent();
    obj.on('change',function(){
        var file_name;
        var input=this;
        var $this=$(input);
        var baze_img=$this.parent().find('.crop-image-upload-container');
        baze_img.find('img').remove();
        if( file_api && input.files[0] ){
            baze_img.find('.clear_photo').show();
            file_name = input.files[0].name;
            var file = input.files[0];
            if(baze_img){
                var reader = new FileReader();
                var img = document.createElement("img");
                img.file = file;
                baze_img
                    .css('background','none')
                    .append(img)
                reader.onload = (function(aImg) {
                    return function(e) {
                        aImg.src = e.target.result;
                        aImg.longdesc= e.target.result
                    };
                })(img);
                reader.readAsDataURL(file);
            }
        }else{
            file_name = $this.val();
        }
        /*if(!file_name.length){
            $this.parent().find('span.image_name').text($this.attr('default_text'))
        }else{
            $this.parent().find('span.image_name').text(file_name)
        }*/
    });
    var el=obj.parent();
    el.find('.clear_photo').click(function(){
        $el=$(this).parent().parent();
        $el.find('img').remove();
        $el.find('input').val('');
        $el.find('.help-block').html('');
        $(this).hide();
    });
}

function show_msg(text,type,title){
    popup.open({message:text,type:type,title:title})
}


var popup = (function() {
    var conteiner;
    var mouseOver = 0;
    var timerClearAll = null;
    var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
    var time = 3000;

    var _setUpListeners = function() {
        $('body').on('click', '.notification_close', _closePopup);
        $('body').on('mouseenter', '.notification_container', _onEnter);
        $('body').on('mouseleave', '.notification_container', _onLeave);
    };

    var _onEnter = function(event) {
        if(event)event.preventDefault();
        if (timerClearAll!=null) {
            clearTimeout(timerClearAll);
            timerClearAll = null;
        }
        conteiner.find('.notification_item').each(function(i){
            var option=$(this).data('option');
            if(option.timer) {
                clearTimeout(option.timer);
            }
        });
        mouseOver = 1;
    };

    var _onLeave = function() {
        conteiner.find('.notification_item').each(function(i){
            $this=$(this);
            var option=$this.data('option');
            if(option.time>0) {
                option.timer = setTimeout(_closePopup.bind(option.close), option.time - 1500 + 100 * i);
                $this.data('option',option)
            }
        });
        mouseOver = 0;
    };

    var _closePopup = function(event) {
        if(event)event.preventDefault();

        var $this = $(this).parent();
        $this.on(animationEnd, function() {
            $(this).remove();
        });
        $this.addClass('notification_hide')
    };

    var open = function(data) {
        var option = {time : (data.time||data.time===0)?data.time:time};
        if (!conteiner) {
            conteiner = $('<ul/>', {
                'class': 'notification_container'
            });

            $('body').append(conteiner);
            _setUpListeners();
        }

        var li = $('<li/>', {
            class: 'notification_item'
        });

        if (data.type){
            li.addClass('notification_item-' + data.type);
        }

        var close=$('<span/>',{
            class:'notification_close'
        });
        option.close=close;
        li.append(close);

        if(data.title && data.title.length>0) {
            var title = $('<p/>', {
                class: "notification_title"
            });
            title.html(data.title);
            li.append(title);
        }

        var content = $('<div/>',{
            class:"notification_content"
        });
        content.html(data.message);

        li.append(content);

        conteiner.append(li);

        if(option.time>0){
            option.timer=setTimeout(_closePopup.bind(close), option.time);
        }
        li.data('option',option)
    };

    return {
        open: open
    };
}());

function onlineTrace() {
    $.get('/online',function(data){
        if(data['chat']){
            $('.chat_cnt').attr('count',data['chat']);
        }else{
            $('.chat_cnt').removeAttr('count');
        }

        if(data['mails']){
            $('.mail_cnt').attr('count',data['mails']);
        }else{
            $('.mail_cnt').removeAttr('count');
        }
    },'json');
    setTimeout(onlineTrace, 60000)
}

function parse_input_json(data){
    if(data['status']!=0){
        show_msg(data['msg'],'err');
        return;
    }
    show_msg(data['msg'],'info');
    if(data['href']=='#'){
        location.reload()
    }
    if(data['href'].length>1){
        location.href=data['href'];
    }
}

var userChat = (function() {

    var my_id=false;
    var user=false;
    var old_tot_fav=false;
    var max_text=255;

    function _timer() {
        this.interval = setTimeout(this.update, 1000);
    }

    function _update() {
        post={
            id:this.my_id,
            user:this.user,
            last_msg:this.last_msg
        };
        $.post('/chat/get',post,this.parce,'json')
            .fail(this.timer)
    }

    function _parce(data) {
        this.last_msg=data.time;
        render = false;
        t_user=[];
        tot_fav=0;

        if(data.favorites.indexOf(this.user+'')<0) {
            $('.add_fav').show();
            $('.remove_fav').hide();
        }else{
            $('.add_fav').hide();
            $('.remove_fav').show();
        }

        if(data.users){
            user_out='';
            msg_cnt=[];
            tot_new=0;
            if($('.user_all .loading').length>0) {
                render = true;
            }else{
                els=$('.user_all>*');
                for(var i=0;i<els.length;i++){
                    t_user.push($(els[i]).attr('user'));
                    msg_cnt.push(els.eq(i).find('b'));
                }
                if(els.length!=data.users.length)render = true
            }
            data.users.sort(chat_user_sort);
            for(var i=0;i<data.users.length;i++){
                user=data.users[i];
                if(!render){
                    if(user.id!=t_user[i])render = true;
                    if(user.in_new && msg_cnt[i].hasClass('new_mes'))render = true;
                    if(!user.in_new && msg_cnt[i].text()!=user.out)render = true;
                }
                if(user.in_new)tot_new+=user.in_new;
                user['this_user']=(user.id==this.user);

                user.fav=(data.favorites.indexOf(user.id+'')>=0);
                if(user.fav)tot_fav++;
                user_out+=templates.chat_user(user)
            }
            if(old_tot_fav!=tot_fav)render=true;
            old_tot_fav=tot_fav;
            if(render){
                $('.user_all').html(user_out);
                $('.count_user').text('('+data.users.length+')');
                $('.count_user_fav').text('('+tot_fav+')');
            }
            if(tot_new){
                $('.chat_cnt ').attr('count',tot_new);
            }else {
                $('.chat_cnt ').removeAttr('count');
            }
        }
        if(data.chat) {
            $('.mess_block .loading').remove();
            var chat_out='';
            for(i=0;data.chat.length>i;i++){
                chat_out+=templates.chat_message(data.chat[i])
            }
            if(data.chat.length>0){
                mes_b=$('.mess_block');
                scroll=mes_b.scrollTop(); //текущее состояние прокрутки
                h=mes_b.height(); //высота контейнерв
                msg_h=0; //высота содержимого
                children=mes_b.children();
                for(i=0;i<children.lenght;i++){
                    msg_h+=children.eq(i).outerHeight()
                }
                //придумать прокрутку вниз если
                $('.mess_block').append(chat_out).scrollTop(9999);
            }
        }
        this.timer()
    }

    function _send(){
        post={};
        if(!this.calck_char())return false;
        $('.send_chat .emojionearea-editor img').removeAttr('alt');
        post["message"]=$('.send_chat .emojionearea-editor').html();
        if(post["message"].length<1) return;
        post["to"]=this.user;
        $('.send_chat .emojionearea-editor').html('');
        this.calck_char();
        $.post('/chat/send',post,this.send_msg_ansv,'json')
    }

    function _send_msg_ansv(data){
        //console.log(data);
    }

    function _add_fav(){
        set_fav(this.user,1)
    }
    function _remove_fav(){
        set_fav(this.user,0)
    }

    function _calck_char(){
        el=$('.emojionearea-editor');
        l=el.text().length;
        ost=max_text-l;
        el=el.parent();
        if(l>10) {
            if (ost < 0) {
                el.addClass('err');
                el.removeAttr('counter')
                return false;
            } else {
                el.removeClass('err');
                el.attr('counter', l + ' / ' + max_text)
            }
        }else{
            el.removeAttr('counter')
            el.removeClass('err');
        }
        return true;
    }

    function init(data){
        this.my_id=data.my_id;
        this.user=data.user;
        this.last_msg=0;
        this.update=_update.bind(this);
        this.parce=_parce.bind(this);
        this.timer=_timer.bind(this);
        this.send=_send.bind(this);
        this.add_fav=_add_fav.bind(this);
        this.remove_fav=_remove_fav.bind(this);
        this.send_msg_ansv=_send_msg_ansv.bind(this);
        this.calck_char=_calck_char.bind(this);
        $('.send_chat [type=submit]').on('click',this.send);
        $('.add_fav').on('click',this.add_fav).hide();
        $('.remove_fav').on('click',this.remove_fav).hide();

        $('.user_chat .user_add>span').on('click',function(){
            if($(this).find('.count_user_fav').length>0){
                $('.user_chat').addClass('show_fav')
            }else{
                $('.user_chat').removeClass('show_fav')
            }
        });
        this.update()

        $('body').on('keyup','.emojionearea-editor',this.calck_char)
    }

    return {
        init: init
    };
})();

function chat_user_sort(a, b) {
    a_time=a.in_time>a.out_time?a.in_time:a.out_time;
    b_time=b.in_time>b.out_time?b.in_time:b.out_time;
    if((!!a.in_new)!=(!!b.in_new))
        return !a.in_new;
    else
        return a_time<b_time
}

function set_fav(user,s){
    post={user:user,status:s};
    $.post('/user/fav',post,function(data){
        if(data.message){
            popup.open(data)
        }else{
            popup.open({message:"Error to edit favorite.",type:'err',title:"ERROR"})
        }
    },'json')
}

/*LEGEND*/



$('.legend_block').click(function(){
    if ($('.modal').is(":hidden")) {
					$('.modal').show();
					$("body").addClass("lock");	

			 } else {
				
				$('.modal').hide;
	            $("body").removeClass("lock");	
				
}
	 return false;
});



$('.leg_close').click(function(){
	$('.modal').hide();
	$("body").removeClass("lock");	
    return false;
});