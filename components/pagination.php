<?php
namespace app\components;

use Yii;
use yii\base\Widget;

class pagination extends Widget
{
  public $this_page;
  public $max_page;
  public $url_param;

  public function run()
  {

    $page = $this->this_page;
    $max_page = $this->max_page;

    if ($max_page < 2) return;

    $out = '<ul class="pagination">';
    if ($page == 1) {
      $out .= "<li class='prev disabled'><span>«</span></li>";
    } else {
      $url=$this->makeUrl($page-1);
      $out .= "<li class='prev'><a href='".$url."'>«</a></li>";
    }

    for ($i = 1; $i <= $max_page; $i++) {
      if ($i == $page) {
        $out .= '<li class="active"><span>' . $i . '</span></li>';
      } else {
        $url=$this->makeUrl($i);
        $out .= '<li><a href="' . $url . '">' . $i . '</a></li>';
      }
    }

    if ($page == $max_page) {
      $out .= "<li class='next disabled'><span>»</span></li>";
    } else {
      $url=$this->makeUrl($page+1);
      $out .= "<li class='next'><a href='" . ($url) . "'>»</a></li>";
    }
    return $out;
  }

  function makeUrl($page){
    $url=Yii::$app->request->getPathInfo();
    $url_param = $this->url_param;

    if($page!=1){
      $url_param['page']=$page;
    }

    if(count($url_param)==0)return $url;
    return $url.'?'.http_build_query($url_param);
  }
}

/*
<ul class="pagination"><li class="prev disabled"><span>«</span></li>
<li class="active"><a href="/user/admin/index?page=1&amp;per-page=100" data-page="0">1</a></li>
<li><a href="/user/admin/index?page=2&amp;per-page=100" data-page="1">2</a></li>
<li><a href="/user/admin/index?page=3&amp;per-page=100" data-page="2">3</a></li>
<li><a href="/user/admin/index?page=4&amp;per-page=100" data-page="3">4</a></li>
<li class="next"><a href="/user/admin/index?page=2&amp;per-page=100" data-page="1">»</a></li></ul>
 */