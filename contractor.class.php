<?php

class Contractor{
	
	public  $total_data;
	public  $info;
	public  $low_limit;
	public  $high_limit;
	public  $type;
	public  $order;
		
	function __construct()
	{
		include("includes/config.php");
		$sql = "select id from contractor_list";
	    $result = mysql_query($sql,$link) or die(mysql_error());
		$this->total_data = mysql_num_rows($result);
	}

	
	function getInfo()
	{
		include("includes/config.php");
		
		$sql = "select * from contractor_list order by $this->type $this->order, odesk_hour desc limit $this->low_limit, $this->high_limit" ;
   
	
		$result = mysql_query($sql,$link);
		$i=0;
		while($pdata = mysql_fetch_array($result))
        {
			$this->info[$i]['id'] = $pdata['id'];
			$this->info[$i]['odesk_link'] = $pdata['odesk_link'];
			$this->info[$i]['email'] = $pdata['email'];
			$this->info[$i]['name'] = $pdata['name'];
			$this->info[$i]['odesk_hour'] = $pdata['odesk_hour'];
			$this->info[$i]['odesk_rating'] = $pdata['odesk_rating'];
			$this->info[$i]['odesk_dollar'] = $pdata['odesk_dollar'];
			
			$i++;
		}
	}
	
	function getCurrentTotal()
	{
		include("includes/config.php");
		$sql = "select distinct(user) from projectlist where status='new'  or status='pause'";
	    $result = mysql_query($sql,$link) or die(mysql_error());
		$this->total_data = mysql_num_rows($result);
	}
	
	function getCurrentInfo()
	{
		include("includes/config.php");
		
		if(isset($_GET["l"]))
			{  
			 $this->low_limit = $_GET["l"];
			 $this->high_limit = $_GET["h"];
			}
			
			else{
				$this->low_limit=0;
				$this->high_limit=$contractor_page_limit;
			}
		
		$sql = "select distinct(p.user), c.id, c.odesk_link, c.name, c.odesk_hour, c.odesk_rating from projectlist p inner join contractor_list c on c.name = p.user where  p.status='new'   or p.status='pause' order by c.odesk_rating desc, c.odesk_hour desc limit $this->low_limit, $this->high_limit";
   
	
		$result = mysql_query($sql,$link);
		$i=0;
		while($pdata = mysql_fetch_array($result))
        {
			$this->info[$i]['id'] = $pdata['id'];
			$this->info[$i]['odesk_link'] = $pdata['odesk_link'];
			$this->info[$i]['name'] = $pdata['name'];
			$this->info[$i]['user'] = $pdata['user'];
			$this->info[$i]['odesk_hour'] = $pdata['odesk_hour'];
			$this->info[$i]['odesk_rating'] = $pdata['odesk_rating'];
			$i++;
		}
	}
	
	function getQuickInfo()
	{
		include("includes/config.php");
		
		$sql = "select distinct(user) from projectlist where status='new' or status='pause'";	
		$result = mysql_query($sql,$link);
		$i=0;
		while($pdata = mysql_fetch_array($result))
        {
			$this->info[$i]['user'] = $pdata['user'];
			$i++;
		}
	}
	
	function td($td_name)
	{
		$url = get_request_uri();
		if(isset($_REQUEST['type']))
		 {
			 $url = "?". get_str($url,"?","&type");
		 }
		 
		echo "<div class='tddiv1'>
		 <a href='$url&type=$td_name&order=asc'>&nu;</a><b>" . ucfirst($td_name) . "</b><a href='$url&type=$td_name&order=desc'>^</a>
		</div>";
	}
	
	function page()
	{
		  include("includes/config.php");
		  echo '<div>';
		  $this->type = empty($_REQUEST['type'])? 'odesk_rating': $_REQUEST['type'];
		  $this->order = empty($_REQUEST['order'])? 'desc' : $_REQUEST['order'];
		  $page = $_REQUEST['page'];
		  
		  echo make_pages($this->total_data,$contractor_page_limit,"page=$page&type=$this->type&order=$this->order");
		  
		 if(isset($_GET["l"]))
			{  
			 $this->low_limit = $_GET["l"];
			 $this->high_limit = $_GET["h"];
			}
			
			else{
				$this->low_limit=0;
				$this->high_limit=$contractor_page_limit;
			}
		  echo '</div>';
	}
	
}
?>