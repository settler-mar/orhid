(function($)
{
  $.Redactor.prototype.submit = function()
  {
    return {
      init: function ()
      {
        var button = this.button.add('submit', 'Send message');
        this.button.addCallback(button, this.submit.submitForm);
      },
      submitForm: function(buttonName)
      {
        $('#msg_form').submit()
      }
    };
  };
})(jQuery);

(function($)
{
  $.Redactor.prototype.inlinestyle = function()
  {
    return {
      langs: {
        en: {
          "style": "Style"
        }
      },
      init: function()
      {
        var tags = {
          "marked": {
            title: "Marked",
            args: ['mark']
          },
          "code": {
            title: "Code",
            args: ['code']
          },
          "sample": {
            title: "Sample",
            args: ['samp']
          },
          "variable": {
            title: "Variable",
            args: ['var']
          },
          "shortcut": {
            title: "Shortcut",
            args: ['kbd']
          },
          "cite": {
            title: "Cite",
            args: ['cite']
          },
          "sup": {
            title: "Superscript",
            args: ['sup']
          },
          "sub": {
            title: "Subscript",
            args: ['sub']
          }
        };


        var that = this;
        var dropdown = {};

        $.each(tags, function(i, s)
        {
          dropdown[i] = { title: s.title, func: 'inline.format', args: s.args };
        });


        var button = this.button.addAfter('format', 'inline', 'Style');
        button.html('<i class="re-icon re-icon-inline"></i>');
        this.button.addDropdown(button, dropdown);
      }


    };
  };
})(jQuery);

(function($)
{
  $.Redactor.prototype.userimage = function()
  {
    return {
      init: function()
      {
        var button = this.button.add('userimage', 'Insert image');
        button.html('<i class="re-icon re-icon-image"></i>');
        this.button.addCallback(button, this.userimage.show);
      },
      show: function()
      {
        $('.photo_list').addClass('loading');
        $('.photo_list>*').remove();
        $('.file_insert_model_bg .title').hide();
        $('.file_insert_model_bg').show();
        $.post('/fileupload/default/get',function(data){
          $('.photo_list').removeClass('loading');
          if(data.length>0){
            for(i=0;i<data.length;i++){
              add_file_to_list(data[i])
            }
          }
        },'json');
        /*this.modal.addTemplate('userimage', this.userimage.getTemplate());
        this.modal.load('userimage', 'Select image', 600);


        //var button = this.modal.getActionButton();
        //button.on('click', this.userimage.insert);

        this.modal.show();

        $('#mymodal-textarea').focus();*/
      },
      insert: function()
      {
        /*var html = $('#mymodal-textarea').val();

        this.modal.close();

        this.buffer.set(); // for undo action
        this.insert.html(html);*/
      }
    };
  };
})(jQuery);