function fileuploaddone(e, data){
  $('.progress_bar').hide();
  $('.fileinput-button').show();
  $('.progress_bar span').width("0");
  console.log(1111111111111+data);
  img=add_file_to_list(data.result.files[0].url);
  img.click()
  popup.open({message:"Picture was successfully uploaded.",type:'info',title:"File upload done"})
}
function fileuploadfail(e, data){
  $('.progress_bar').hide();
  $('.fileinput-button').show();
  popup.open({message:"Try again later or another image.",type:'err',title:"File upload error"})
}
function fileuploadprogress(e, data){
  p=100*data.loaded/data.total;
  $('.progress_bar span').width(p+"%");
}
function fileuploadstart(e, data){
  $('.progress_bar').show();
  $('.progress_bar span').width("0");
  $('.fileinput-button').hide();
}

$('.file_insert_model_bg .close').on('click',function(){
  $('.file_insert_model_bg').hide();
  $('.file_insert_model_bg .select').removeClass('select');
});
$('.file_insert_model_bg').hide();

function add_file_to_list(file){
  $('.file_insert_model_bg .title').show();
  img=$("<img/>",{
    src:file
  });
  div=$("<div/>");
  div.append(img);

  $('.photo_list').append(div);
  return div;
}
$('.photo_list').on('click','div',function(){
  $('.file_insert_model_bg .select').removeClass('select');
  $(this).addClass('select');
});

$('.insert_photo').on('click',function(){
  img=$('.file_insert_model_bg .select img');
  if(img.length>0){
    src=img[0].src;
    jQuery('#message').redactor('insert.html', "<img src='"+src+"' class='user_img'/>");
  }
  $('.file_insert_model_bg .select').removeClass('select');
  $('.file_insert_model_bg').hide();
});

$('.in_mes_top').on('click',function(){
  $this=$(this).parent()
  if($this.hasClass('close_message')){
    $this.removeClass('close_message')
  }else{
    $this.addClass('close_message')
  }
});