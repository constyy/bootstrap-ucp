<?php

class Pagination extends Config {







	private static $_perPage = 18;







	public static function limit() {



		if(!isset(self::$_url[2]))



			self::$_url[2] = 1;



		return "LIMIT ".((self::$_url[2] * self::$_perPage) - self::$_perPage).",".self::$_perPage;



	}







	public static function create($rows) {



		if(!isset(self::$_url[2]))



			self::$_url[2] = 1;



		$adjacents = "2";



		$prev = self::$_url[2] - 1;



		$next = self::$_url[2] + 1;



		$lastpage = ceil($rows/self::$_perPage);



		$lpm1 = $lastpage - 1;







		$pagination = "<ul class='pagination justify-content-center'>";



		if($lastpage > 1)



		{



			if($prev != 0)



				$pagination.= "<li class='previous_page btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/1'>« First</a></li>";  



			else 



				$pagination.= "<li class='btn btn-secondary previous_page disabled'><a>« First</a></li>";  



			if ($lastpage < 7 + ($adjacents * 2))



			{   



				for ($counter = 1; $counter <= $lastpage; $counter++)



				{



					if ($counter == self::$_url[2])



						$pagination.= "<li class='active btn btn-secondary'><a href='#'>$counter</a></li>";



					else



						$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a></li>";                   



				}



			}



			elseif($lastpage > 5 + ($adjacents * 2))



			{



				if(self::$_url[2] < 1 + ($adjacents * 2))       



				{



					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)



					{



						if ($counter == self::$_url[2])



							$pagination.= "<li class='active btn btn-secondary'><a href='#'>$counter</a></li>";



						else



							$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a></li>";                   



					}



					$pagination.= "<li class='dots btn btn-secondary'><a href='#'>...</a></li>";



					$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lpm1'>$lpm1</a></li>";



					$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lastpage'>$lastpage</a></li>";       



				}



				elseif($lastpage - ($adjacents * 2) > self::$_url[2] && self::$_url[2] > ($adjacents * 2))



				{



					$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/1'>1</a></li>";



					$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/2'>2</a></li>";



					$pagination.= "<li class='dotsbtn btn-secondary'><a href='#'>...</a></li>";



					for ($counter = self::$_url[2] - $adjacents; $counter <= self::$_url[2] + $adjacents; $counter++)



					{



						if ($counter == self::$_url[2])



							$pagination.= "<li class='activebtn btn-secondary'><a href='#'>$counter</a></li>";



						else



							$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a></li>";                   



					}



					$pagination.= "<li class='dotsbtn btn-secondary'><a href='#'>...</a></li>";



					$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lpm1'>$lpm1</a></li>";



					$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lastpage'>$lastpage</a></li>";      



				}



				else



				{



					$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/1'>1</a></li>";



					$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/2'>2</a></li>";



					$pagination.= "<li class='dotsbtn btn-secondary'><a href='#'>...</a></li>";



					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)



					{



						if ($counter == self::$_url[2])



							$pagination.= "<li class='activebtn btn-secondary'><a href='#'>$counter</a></li>";



						else



							$pagination.= "<li class='btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a></li>";                   



					}



				}



			}



			if($lastpage == (isset(self::$_url[2]) ? self::$_url[2] : 1))



				$pagination.= "<li class='next_page disabled btn btn-secondary'><a>Last »</a></li>";  



			else 



				$pagination.= "<li class='next_page btn btn-secondary'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lastpage'>Last »</a></li>";  



		}



		$pagination .= "</ul>";



		return $pagination;



	}







}