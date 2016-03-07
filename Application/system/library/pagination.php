<?php
class Pagination {
	public $total = 0;
	public $page = 1;
	public $limit = 20;
	public $num_links = 10;
	public $url = '';
	public $text = 'Showing {start} to {end} of {total} ({pages} Pages)';
	public $text_first = '|&lt;';
	public $text_last = '&gt;|';
	public $text_next = '&gt;';
	public $text_prev = '&lt;';
	public $style_links = 'links';
	public $style_results = 'results';

	public function render() {
		$total = $this->total;
		
		if ($this->page < 1) {
			$page = 1;
		} else {
			$page = $this->page;
		}
		
		if (!(int)$this->limit) {
			$limit = 10;
		} else {
			$limit = $this->limit;
		}
		
		$num_links = $this->num_links;
		$num_pages = ceil($total / $limit);
		
		$output = '';
		
		if ($page > 1) {
            if(strpos($this->url,'{page}.html')!==false){
                $output .= ' <a href="' . str_replace('/{page}.html', '.html', $this->url) . '">'. $this->text_first . '</a> ';
            }else{
                $output .= ' <a href="' . str_replace('{page}', 1, $this->url) . '">' . $this->text_first . '</a> ';
            }
            if(strpos($this->url,'{page}.html')!==false && $page - 1 == 1 ){
                $output .= '<a href="' . str_replace('/{page}.html', '.html', $this->url) . '">' . $this->text_prev . '</a> ';
            }else{
                $output .= '<a href="' . str_replace('{page}', $page - 1, $this->url) . '">' . $this->text_prev . '</a> ';
            }
			
		}

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);
			
				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}
						
				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}

			if ($start > 1) {
				$output .= ' .... ';
			}

			for ($i = $start; $i <= $end; $i++) {
				if ($page == $i) {
					$output .= " <a class='active'>". $i . "</a> ";
				} else {
                     if(strpos($this->url,'{page}.html')!==false && $i == 1){
              
                        $output .= ' <a href="' . str_replace('/{page}.html', '.html', $this->url) . '">'. $i . '</a> ';
                    }else{
                       $output .= ' <a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a> ';
                    }
					
				}	
			}
            
							
			if ($end < $num_pages) {
				$output .= ' .... ';
			}
		}
		
		if ($page < $num_pages) {
			$output .= ' <a href="' . str_replace('{page}', $page + 1, $this->url) . '">' . $this->text_next . '</a> <a href="' . str_replace('{page}', $num_pages, $this->url) . '">' . $this->text_last . '</a> ';
			
		}
		$output .="<span class='ml_10 font13 bold'>$page/$num_pages</span> <span class='ml_10 font13 bold'>total $total</span>";
		
		
		$find = array(
			'{start}',
			'{end}',
			'{total}',
			'{pages}'
		);
		
		$replace = array(
			($total) ? (($page - 1) * $limit) + 1 : 0,
			((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit),
			$total, 
			$num_pages
		);
		//return ($output ? '<div class="' . $this->style_links . '">' . $output . '</div>' : '') . '<div class="' . $this->style_results . '">' . str_replace($find, $replace, $this->text) . '</div>';
		if($num_pages>1){
			if($output){
				$output = '<section class="pro_sort propage">' . $output . '</section>';
			}
		}else{
			$output = '';
		}
		//return ($output ?  $output: '');
		return $output;
	}
}
?>