<?php

namespace Drupal\user_module\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\views\Views;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;


class AttendanceController extends ControllerBase {

public function get_attendance() {
    
    $uid = \Drupal::currentUser()->id();   
  $database = \Drupal::database();

$where =" WHERE  ( punch_status ='1' ) ";  
$where.=" and  ( e.uid = '".$uid."' ) "; 

if( !empty($_REQUEST['search']['value']) ) { 
	$where.=" and  ( name LIKE '".$_REQUEST['search']['value']."%' ) ";    
	
}
if( !empty($_REQUEST['enddate']) and !empty($_REQUEST['startdate']) ) { 
$where.=" and (date(punch_time) between '".$_REQUEST['startdate']."' and '".$_REQUEST['enddate']."' )";
	}
elseif( !empty($_REQUEST['startdate']) ) { 
$where.=" and (punch_time LIKE '".$_REQUEST['startdate']."%' )";
	}
$columns = array( 
	0 =>'punchDate',
	1 =>'punchDate',
);
$order ='';
if( !empty($columns[$_REQUEST['order'][0]['column']]) ) {
$order =" ORDER BY ". $columns[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir']." LIMIT ".$_REQUEST['start']." ,".$_REQUEST['length']."   ";
}


    $query = $database->query("SELECT
    	 e.name name,
    CAST(punch_time AS DATE) punchDate,
    TIME(min(punch_time)) InTime,
    if(max(punch_time)=min(punch_time),null,TIME(max(punch_time))) OutTime
FROM users_field_data e
JOIN punch_time p ON e.uid = p.user_id
".$where."
GROUP BY punchDate, e.uid, name
".$order."
");  
    $result = $query->fetchAll();
     foreach ($result as $key => $value) {
        
        $result[$key]->name = $value->name;

    }

// print_r($result);
// exit;
$query->allowRowCount = TRUE;
$count = $query->rowCount();
if(isset($_REQUEST['draw'])){

}
$json_data = array( 
 "draw"            => intval( $_REQUEST['draw'] ),  
 "recordsTotal"    => intval($count),  
 "recordsFiltered" => intval($count),
 "data"            => $result   // total data array
 );

//echo json_encode($json_data);	

 return new JsonResponse($json_data);
 
}

public function admin_attendance() {
  //   \Drupal::keyValueExpirable('tempstore.shared.views')
  // ->delete('attendance');
       
  $database = \Drupal::database();

$where =" WHERE  ( punch_status ='1' ) ";  

if( !empty($_REQUEST['search']['value']) ) { 
	$where.=" and  ( name LIKE '".$_REQUEST['search']['value']."%' ) ";    
	
}
if( !empty($_REQUEST['enddate']) and !empty($_REQUEST['startdate']) ) { 
$where.=" and (date(punch_time) between '".$_REQUEST['startdate']."' and '".$_REQUEST['enddate']."' )";
	}
elseif( !empty($_REQUEST['startdate']) ) { 
$where.=" and (punch_time LIKE '".$_REQUEST['startdate']."%' )";
	}
$columns = array( 
	0 =>'punchDate',
	1 =>'punchDate',
    2 =>'punchDate',
);

$order ='';
if( !empty($columns[$_REQUEST['order'][0]['column']]) ) {
$order =" ORDER BY ". $columns[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir']." LIMIT ".$_REQUEST['start']." ,".$_REQUEST['length']."   ";
}


    $query = $database->query("SELECT
    	 e.name name, ep.field_employee_id_value empid, 
    CAST(punch_time AS DATE) punchDate,
    if(max(punch_time)=min(punch_time),null,TIME(max(punch_time))) OutTime,
    TIME(min(punch_time)) InTime
FROM users_field_data e
JOIN punch_time p ON e.uid = p.user_id
INNER JOIN user__field_employee_id ep ON e.uid = ep.entity_id
".$where."
GROUP BY punchDate, e.uid, name, empid
".$order."
");  
    $result = $query->fetchAll();
    $current_time = \Drupal::time()->getCurrentTime();
      $date_current = date('Y-m-d', $current_time);
      $status='';
    foreach ($result as $key => $value) {
        if($value->punchDate==$date_current){
        $status = '<p class="w">Wait</p>';
        }elseif ($value->OutTime==null) {
        $status = '<p class="a">Absent</p>';
       }else{
        $status = '<p class="p">Present</p>';
       }
        $result[$key]->name = $value->name;
        $result[$key]->status = $status;

    }
/*print "<pre>";
print_r($result);
print "</pre>";
exit;*/
$query->allowRowCount = TRUE;
$count = $query->rowCount();
if(isset($_REQUEST['draw'])){

}


$json_data = array( 
 "draw"            => intval( $_REQUEST['draw'] ),  
 "recordsTotal"    => intval($count),  
 "recordsFiltered" => intval($count),
 "options" => $options,
 "data"            => $result   // total data array
 );

//echo json_encode($json_data);	

 return new JsonResponse($json_data);
 
}

public function admin_list_attendance(){


	 return [
        '#theme' => 'admin_list_attendance',
        '#attached' => [
          'library' => [
            'user_module/drupal_js',
            'user_module/user_module_css',
          ],
        ],
      ];
}



public function my_attendance(){


	 return [
        '#theme' => 'my_attendance',
        '#attached' => [
          'library' => [
            'user_module/drupal_js',
            'user_module/user_module_css',
          ],
        ],
      ];
}

public function admin_reg_accept($pid){
 $database = \Drupal::database();
$uid = \Drupal::currentUser()->id(); 
    
     $url = Url::fromUserInput('/admin/regularization-requests', [], ['absolute' => 'true']);

     $punch_updated = $database->update('punch_time')
  ->fields([
    'punch_status' => 1,
  ])
  ->condition('id', $pid, '=')
  ->execute();
  \Drupal::messenger()->addStatus('Accecp Succesfully');
      
      $response = new RedirectResponse($url->toString());
      $response->send();
      exit();
}

}