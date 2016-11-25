;(function($){
  var form;
  var file_api = ( window.File && window.FileReader && window.FileList && window.Blob ) ? true : false;
  var defaults={
    error_class:'error',
    error_message_param:'error_text',
    ajax_class:'ajax_form',
    url:location.href,
    method:'post',
    dataType:'json',
    err_message:{
      login:'Заполните поле логин',
      password:'Заполните поле пароль',
      name:'Введите свое имя',
      mail:'Введите свой email',
      comment:'Введите ваше сообщение',
      data:'Введите дату',
      text:'Введите текст',
      skill:'Введите технологии',
      image:'Выберите фаил',
      link:'Введите ссылку',
      fail_ajax:'Ошибка отправки формы'
    }
  };
  function ajax_form(element,options){
    this.form=$(element);
    this.config=$.extend({},defaults,options);
    form=this;
    this.init();
  }
  ajax_form.prototype.ajax_done=function(data) {
    if(data.href) {
      setTimeout('location.hash="";location.pathname="'+data.href+'"',2000);
    }
    if(data.error){
      form.result_message(data.error,'error_windows')
    }else if(data.message){
      form.result_message(data.message);
      form.form[0].reset()
    }
    form.form.removeClass('disabled');
    form.form.find('[type=submit]').prop('disabled',false);
  };
  ajax_form.prototype.ajax_fail=function(data) {
    form.result_message(form.config.err_message.fail_ajax,'error_windows');
    form.form.removeClass('disabled');
    form.form.find('[type=submit]').prop('disabled',false);
  };
  ajax_form.prototype.result_message=function(text,className) {
    var message_windows=$('<div/>',{
      class:'message_windows '+className
    });
    var close_btn=$('<input/>',{
      value:'Закрыть',
      type:'button',
      class:'message_windows__button'
    }).on('click',function(){
      $(this).parent().parent().remove()
    });
    var content=$('<div/>',{
      class:'message_windows__content',
      text:text
    });
    content.append(close_btn);
    message_windows.append(content);
    $('body').append(message_windows);
    message_windows.addClass('open');
    setTimeout('$(".message_windows .message_windows__button").click()',3000)
  };
    ajax_form.prototype.error_display=function(element,state,func){
    if(state){
      element.parent().removeClass(form.config.error_class);
    }else{
      element.parent().addClass(form.config.error_class);
      msg=(form.config.err_message[element.attr('name')]?form.config.err_message[element.attr('name')]:'Заполните поле')
      element.parent().attr(msg);
      element
        .off("input")
        .off("paste")
        .on('input',  function() {
          func(this)
        })
        .on('paste',  function() {
          func(this)
        });
    }
  }
  ajax_form.prototype.check_num=function(input) {
      e=input
      if(get_ctrl_ev(e) || (e.which>48 && e.which<57)|| (e.which>95 && e.which<106)) return true
      var value = String.fromCharCode(input.keyCode)
      var rep = /^[0-9]+$/i;
      return rep.test(value);
    }
  ajax_form.prototype.test_mail_valid=function(element){
    reg = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    mail=$(element).val()
    if(reg.test(mail)){
      form.error_display(element,false,form.test_mail_valid)
      return false;
    }else {
      form.error_display(element,true,form.test_mail_valid)
      return true;
    };
  }
  ajax_form.prototype.vlidate=function(element){
    if(form.form.hasClass('disabled'))return;
    element=$(element);
    if(element.val().length<3){
      form.error_display(element,false,form.vlidate)
      return false;
    }else {
      form.error_display(element,true)
      return true;
    };
  };

  ajax_form.prototype.init=function(){
    var elements=this.form.find('[required]');
    $.each(elements,function(){
      $(this)
        .attr('required',false)
        .addClass('required');
    });
    this.form.find('[type=file]').on('change',function(){
      var file_name;
      var input=this;
      var $this=$(input);
      if( file_api && input.files[0] ){
        file_name = input.files[0].name;
        var file = input.files[0];
        var baze_img=$('#'+$this.attr('img_to'))
        if(baze_img){
          var reader = new FileReader();
          var img = document.createElement("img");
          img.file = file;
          $('#'+$this.attr('img_to'))
            .css('background','none')
            .text('')
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
      if(!file_name.length){
        $this.parent().find('span.image_name').text($this.attr('default_text'))
      }else{
        $this.parent().find('span.image_name').text(file_name)
      }
    }).each(function(){
      $this=$(this);
      $this.attr('default_text',$this.parent().find('span.image_name').text())
    });
    this.form
      .on('submit',function(e){
        var is_validate=true;

        $this=$(this);
        elements=$this.find('.required');
        $.each(elements,function(){
          var $this=$(this);
          is_validate=form.vlidate($this) && is_validate;
        });

        if(!is_validate){
        	e.preventDefault();
        	return false;
        }
        if(form.form.hasClass(form.config.ajax_class)){
          e.preventDefault();
          form.form.addClass('disabled');
          form.form.find('[type=submit]').prop('disabled',true);
          var ajax_parametr={
            url: $this.attr('action')||form.config.url,
            method:$this.attr('method')||form.config.method,
            dataType:$this.attr('dataType')||form.config.dataType
          };
          if($this.find('[type=file]').length>0){
            ajax_parametr['data']=new FormData($this[0]);
            ajax_parametr['processData']=false;
            ajax_parametr['contentType']=false;
          }else{
            ajax_parametr['data']=$this.serializeObject();
            if($this.attr('json')){
              ajax_parametr['data']=JSON.stringify(ajax_parametr['data']);
              ajax_parametr['beforeSend']= function (xhr) {
                xhr.setRequestHeader('content-type', 'application/json');
              }
          }
          }
          $.ajax(ajax_parametr)
            .done(form.ajax_done)
            .fail(form.ajax_fail);
        }else{
        	return true;
        }
      })
      .on('reset',function(e){
        $this=$(this);
        $this.find('.required')
          .off( "input")
          .off( "paste")
          .parent().removeClass(form.config.error_class)
      })
  };
  $.fn.ajax_form=function(options){
    $(this).each(function(){
      new ajax_form(this,options);
    });
    return this;
  }
})(jQuery);

$.fn.serializeObject = function()
{
  var o = {};
  var a = this.serializeArray();
  $.each(a, function() {
    if (o[this.name]) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
};

$('form').ajax_form();