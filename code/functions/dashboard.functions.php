<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: dashboard.functions.php
*/

	function polLangText($id)
	{
		global $pol;
		$result = $pol['db']->query("SELECT * FROM languages WHERE id = '$id' LIMIT 1;");
		if($result->rowCount() == 1)
		{
			$lg = $result->fetchObject();
			return '<span class="flag"><img src="library/flags/'.$lg->flag.'.png" /> <em> '.$lg->name.'</em></span>';;
		}
		else
			return false;
	}

	function pShowCourseListItem($id){
		global $pol;
		$course = pSiRo('courses', $id);
		return ' <div class="course">
			<a class="button throw floatright" style="margin-top: 5px" href="#""><i class="fa fa-leaf"></i> Throw</a> <!--VERVANG!-->
			<span class="avatar"><img src="library/avatars/courses/'.$course->avatar.'" /></span>
			<span class="title">'.$course->name.'</span><span class="usertext"> by <a href="#">'.pUsNa($course->user_id).'</a></span><br />
			'.polLangText($course->language_id).'<br />
			<div style="margin-left: 60px;margin-top: 10px;width: 92%;" class="bar"><span class="progress" style="width: 10%;"></span></div>
  		</div>';
	}


	function pArFavouriteCourses()
	{
		return false;
	}

 ?>