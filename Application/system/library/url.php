<?php
class Url {
	private $url;
	private $ssl;
	private $rewrite = array();	
	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
	}
		
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}
		
	public function link($route, $args = '', $connection = 'NONSSL') {
		if ($connection ==  'NONSSL') {
			$url = $this->url;
		} else {
			$url = $this->ssl;	
		}
		
		$url .= 'index.php?route=' . $route;
			
		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&')); 
		}
		
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
				
		return $url;
	}

	/*生成后台基本带有参数链接
	*	&filter列表中可以筛选的字段
	*	sort  链接中是否带有排序字段
	*	order  链接中是否带有排序方式
	*	page  链接中是否带有页数
	*/
	public function getParUrl($filter,$sort=true,$order=true,$page=true){
		$url = '';
		$request = new Request();
		foreach($filter as $key =>$value){
			if (isset($request->get[$key])) {
				$url .= "&$key=" . urlencode(html_entity_decode($request->get[$key], ENT_QUOTES, 'UTF-8'));
			}
		}
		if($sort){
			if (isset($request->get['sort'])) {
				$url .= '&sort=' . $request->get['sort'];
			}
		}
		if($order){
			if (isset($request->get['order'])) {
				$url .= '&order=' . $request->get['order'];
			}
		}
		if($page){
			if (isset($request->get['page'])) {
				$url .='&page=' . $request->get['page'];
			}
		}
		return $url ;
	}
}
?>