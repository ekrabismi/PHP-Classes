<?php

class Project{
	
	public  $total_data;
	public  $info;
	public  $low_limit;
	public  $high_limit;
	public  $type;
	public  $order;	
	public  $scarch_str;
	public  $scarch_type;
		
	function __construct()
	{
		$this->total_data = 0;
	}
	
	function getTotal($status)
	{
		include("includes/config.php");
		$sql = "select id from projectlist where status='$status'";
	    $result = mysql_query($sql,$link) or die(mysql_error());
		$this->total_data = mysql_num_rows($result);
	}
	
	function getInfo($status)
	{
		include("includes/config.php");
		
		$sql = "select * from projectlist where status='$status' order by $this->type $this->order, id desc limit $this->low_limit, $this->high_limit" ;
	
		$result = mysql_query($sql,$link);
		$i=0;
		while($pdata = mysql_fetch_array($result))
        {
			$this->info[$i]['id'] = $pdata['id'];
			$this->info[$i]['site'] = $pdata['site'];
			$this->info[$i]['client'] = $pdata['client'];
			$this->info[$i]['user'] = $pdata['user'];
			$this->info[$i]['project_type'] = $pdata['project_type'];
			$i++;
		}
	}
	
	
	function getExpInfo($project_type)
	{
		include("includes/config.php");
		
		$sql = "select * from projectlist where project_type='$project_type' order by id desc" ;
	
		$result = mysql_query($sql,$link);
		$this->total_data = mysql_num_rows($result);
		$i=0;
		while($pdata = mysql_fetch_array($result))
        {
			$this->info[$i]['id'] = $pdata['id'];
			$this->info[$i]['site'] = $pdata['site'];
			$i++;
		}
	}
	
	function getSiteInfo()
	{
		include("includes/config.php");
		
		$sql = "select * from projectlist order by project_type";
		$result = mysql_query($sql,$link);
		$this->total_data = mysql_num_rows($result);
		$i=0;
		while($pdata = mysql_fetch_array($result))
        {
			$this->info[$i]['project_type'] = $pdata['project_type'];
			$this->info[$i]['site'] = $pdata['site'];
			$i++;
		}
	}
	
	function getSearchTotal($scr_type,$scr_str)
	{
		include("includes/config.php");
		$sql = "select id from projectlist WHERE $scr_type LIKE '%$scr_str%'";
	    $result = mysql_query($sql,$link) or die(mysql_error());
		$this->total_data = mysql_num_rows($result);
	}
	
	function getSearchInfo()
	{
		include("includes/config.php");
		
		$sql = "select * from projectlist where $this->scarch_type LIKE '%$this->scarch_str%' order by $this->type $this->order, id desc limit $this->low_limit, $this->high_limit " ;
	
		$result = mysql_query($sql,$link);
		$i=0;
		while($pdata = mysql_fetch_array($result))
        {
			$this->info[$i]['id'] = $pdata['id'];
			$this->info[$i]['site'] = $pdata['site'];
			$this->info[$i]['client'] = $pdata['client'];
			$this->info[$i]['user'] = $pdata['user'];
			$this->info[$i]['project_type'] = $pdata['project_type'];
			$i++;
		}
	}
	
	function Delete($id)
	{
		include("includes/config.php");
		$sql = "delete from projectlist where id = $id";
	    $result = mysql_query($sql,$link) or die(mysql_error());
		echo "Delete entry to database successful.";
	}
	
	function td($td_name)
	{
		$url = get_request_uri();
		if(isset($_REQUEST['type']))
		 {
			 $url = "?". get_str($url,"?","&type");
		 }
		 
		echo "<a href='$url&type=$td_name&order=asc'>&nu;</a><b>" . ucfirst($td_name) . "</b><a href='$url&type=$td_name&order=desc'>^</a>";
	}
	
	function page()
	{
		  include("includes/config.php");
		  echo '<div>';
		  $this->type = empty($_REQUEST['type'])? 'id': $_REQUEST['type'];
		  $this->order = empty($_REQUEST['order'])? 'desc' : $_REQUEST['order'];
		  $page = $_REQUEST['page'];
		  $this->scarch_str = empty($_REQUEST['s'])? '' : $_REQUEST['s'];
	      $this->scarch_type = empty($_REQUEST['s_t'])? '' : $_REQUEST['s_t'];
		  
		  echo make_pages($this->total_data,$project_page_limit,"page=$page&type=$this->type&order=$this->order&s=$this->scarch_str&s_t=$this->scarch_type");
		  
		 if(isset($_GET["l"]))
			{  
			 $this->low_limit = $_GET["l"];
			 $this->high_limit = $_GET["h"];
			}
			
			else{
				$this->low_limit=0;
				$this->high_limit=$project_page_limit;
			}
		  echo '</div>';
	}
}
?>