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

map = [0,0,0,0,0,0,0,0];
function show_msg(text,type){
    flag = 0;
    for (i=0;i<8;i++){      // searching free space
        if (flag==0) {  // still don't find
            if (map[i] == 0) {
                flag = 1;
                indexAttr = i;
                map[i] = 1;
            }  // find
        }
    }
    $('body').append('<div class="notification'+type+' animated bounceInLeft" indexAttr="'+indexAttr+'"  style="top:'+indexAttr*100+'px"><p>'+text+'</p><a href="">Close</a></div>>');
    $('.notification'+type).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
        function(){
            $(this).removeClass('animated bounceInLeft');
            $(this).addClass('animated bounceOut');
            $(this).css('-animation-delay', '5s');
            $(this).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',function(){
                $(this).removeClass('animated bounceOut');
                map[$(this).attr('indexAttr')] = 0;
                $(this).remove();
            })
    });
}
show_msg("Mes1",1);

function onlineTrace() {
    $.get('/online');
    setTimeout(onlineTrace, 60000)
}