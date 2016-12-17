var file_api = ( window.File && window.FileReader && window.FileList && window.Blob ) ? true : false;

$(document).ready(function() {

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
    $('a.photo_people,a.fancy,.one_img a').fancybox();
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

    var _setUpListeners = function() {
        $('body').on('click', '.notification_close', _closePopup);
        $('body').on('mouseenter', '.notification_container', _timerstop);
        $('body').on('mouseleave', '.notification_container', _timerstart);

        //$('body').on('click', '.popup', _stopPropogation);
    };

    var _timerstop = function(event) {
        //event.preventDefault();
        if (timerClearAll!=null) {
            clearTimeout(timerClearAll);
            timerClearAll = null;
        }
        conteiner.find('.notification_item').each(function(i){
            clearTimeout($(this).attr('data-timer'));
        });
        mouseOver = 1;
        //document.getElementsByClassName.clearTimeout(($(this).data('timer')));
        //$('.notification_item').clearTimeout(($(this).data('timer')));
    }

    var _timerstart = function(event) {
        //event.preventDefault();
        var liForRemove;
        var idInterval;
        $('.notification_item').addClass('forHide');
        timerClearAll = setTimeout(function(){
            idInterval = setInterval(function() {
                liForRemove = $('.forHide').first();
                if (liForRemove.length != 0) {
                    liForRemove.removeClass('forHide');
                    liForRemove.addClass('notification_hide').on(animationEnd, function () {
                        $(this).remove();
                    });
                }
                else{
                    clearTimeout(idInterval);
                }
               },100)
          },3000);
        mouseOver = 0;
    }

    var _closePopup = function(event) {
        //event.preventDefault();
        var $this = $(this).parent();
        $this.on(animationEnd, function() {
            $(this).remove();
        });
        $this.addClass('notification_hide')
    };

    var open = function(data) {
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

        li.append('<span class="notification_close"></span>');
        li.on(animationEnd, function() {
            if ( mouseOver == 0) {
                li.attr('data-timer', timeout = setTimeout(function () {
                    li.addClass('notification_hide');
                    li.on(animationEnd, function () {
                       $(this).remove();
                    });
                }, 5000));
            }
        });
        var img = $('<img />',
            { class: 'notification_img',
                src: 'img/notification_'+data.type+'.png',
            })
            .appendTo(li);

        content = $('<p/>',{
            class:"notification_title"
        });
        content.html(data.title);
        li.append(content);

        content = $('<div/>',{
            class:"notification_content"
        });
        content.html(data.message);

        li.append(content);



        /*li.data( 'timeout', timeout );*/

        conteiner.append(li)
    };

    return {
        open: open
    };
}());
indexmes=0;
setInterval(function(){
    var  i = Math.floor(1+Math.random()*(4));
    var type;
    switch (i){
        case 1: {type = 'err'; break;    }
        case 2: {type = 'info'; break;    }
        case 3: {type = 'success'; break;    }
        case 4: {type = 'alert'; break;    }
}
    show_msg('text vdfvgd gertdgedr gerger'+indexmes,type,'Title');indexmes++;
},2000);


function onlineTrace() {
    $.get('/online');
    setTimeout(onlineTrace, 60000)
}
