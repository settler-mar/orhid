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
    $('[name=country]').on('change',function(event) {
        $('[name=city]').html('').trigger("chosen:updated");
        $.post('/lib/geo_ip/example.php?city',{country:this.value},function(data){
            city_list=$('[name=city]')
            for(i=0;i<data.length;i++){
                var opt=$('<option/>',{
                    value:data[i]['id'],
                    text:data[i]['name_en'],
                })
                city_list.append(opt)
            }
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

    $(document).on('click.bs.modal.data-api', '[data-toggle="modal"]', function (e) {
        var $this   = $(this)
        var href    = $this.attr('href')
        var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) // strip for ie7
        var option  = $target.data('bs.modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

        if ($this.is('a')) e.preventDefault()

        $target.one('show.bs.modal', function (showEvent) {
            if (showEvent.isDefaultPrevented()) return // only register focus restorer if modal will actually get shown
            $target.one('hidden.bs.modal', function () {
                $this.is(':visible') && $this.trigger('focus')
            })
        });
    })
});