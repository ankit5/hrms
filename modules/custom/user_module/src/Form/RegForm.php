<?php

namespace Drupal\user_module\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Class RegForm.
 *
 * @package Drupal\user_module\Form
 */
class RegForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'reg_form';
  }


  public function getTitle($date) {

    
    return 'Regularize For (' . $date . ')';
  }



  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$date = NULL) {
   $uid = \Drupal::currentUser()->id(); 
    $database = Database::getConnection();

    //$check_date = date('Y-m-d', time())." 00:00:00";
     $query = $database->query("SELECT
       e.name name,
    CAST(punch_time AS DATE) punchDate,
    TIME(min(punch_time)) InTime,
    if(max(punch_time)=min(punch_time),null,TIME(max(punch_time))) OutTime
FROM users_field_data e
JOIN punch_time p ON e.uid = p.user_id
where DATE(punch_time) = '".$date."' and ( user_id = '".$uid."') 
GROUP BY punchDate, e.uid, name
");  
    $result = $query->fetchAll();
//     print "<pre>";
// print_r($result);
// print "</pre>";

$OutTime = isset($result[0]->OutTime)?$result[0]->OutTime:'';
$InTime = isset($result[0]->InTime)?$result[0]->InTime:'';
$punchDate= isset($result[0]->punchDate)?$result[0]->punchDate:'';
$current_time = \Drupal::time()->getCurrentTime();


$date_current = date('Y-m-d', $current_time); 



      $query->allowRowCount = TRUE;
      if(!$query->rowCount() || empty($OutTime)){
//print "yes";
//exit;
      }

    $form['date'] = array(
    '#type' => 'hidden',
    '#value' => $date, 
);
     
    $form['in_time'] = [
  '#type' => 'datetime',
  '#title' => $this->t('In Time'),
  '#size' => 20,
  '#date_date_element' => 'none', // hide date element
  '#date_time_element' => 'time', // you can use text element here as well
  '#date_time_format' => 'H:i',

  '#default_value' => new DrupalDateTime(''.$punchDate.' '.$InTime.'', 'Asia/Kolkata'),
 ];

$form['in_time']['#disabled'] = TRUE;
  $form['out_time'] = [
  '#type' => 'datetime',
  '#title' => $this->t('Out Time'),
  '#size' => 20,
  '#date_date_element' => 'none', // hide date element
  '#date_time_element' => 'time', // you can use text element here as well
  '#date_time_format' => 'H:i',
  '#default_value' => new DrupalDateTime(''.$punchDate.' '.$OutTime.'', 'Asia/Kolkata'),
 ];

  $form['reason'] = [
  '#type' => 'textarea',
  '#title' => $this->t('Reason'),
  '#cols' => 70,
     '#rows' => 3,
 ];


    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Regularize',
      '#attributes' => array('class' => array('button button--action button--primary')),
    ];

    if(!empty($OutTime) || empty($InTime) || $punchDate == $date_current){
 $form['out_time']['#disabled'] = TRUE;
 $form['submit']['#disabled'] = TRUE;
 
}
$status_out = '';
if(!empty($OutTime)){
  $status_out = '<h3>Status Pending</h3>';
}

$form['#prefix'] = '<div id="set_search_results_wrapper"></div><div class="regularize-form" style="color: orange;">'.$status_out;
$form['#suffix'] = '</div>';

    return $form;
  }

  public function ajaxSubmit(array &$form, FormStateInterface $form_state) {
  // Return the search results element of the form.
  return $form;
}

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
 $uid = \Drupal::currentUser()->id(); 
    $database = Database::getConnection();

$date=$form_state->getValue('date');
$out_time=$form_state->getValue('out_time')->format("H:i:s");

     $query = $database->query("SELECT
       e.name name,
    CAST(punch_time AS DATE) punchDate,
    TIME(min(punch_time)) InTime,
    if(max(punch_time)=min(punch_time),null,TIME(max(punch_time))) OutTime
FROM users_field_data e
JOIN punch_time p ON e.uid = p.user_id
where DATE(punch_time) = '".$date."' and ( user_id = '".$uid."') 
GROUP BY punchDate, e.uid, name
");  
    $result = $query->fetchAll();

$OutTime = isset($result[0]->OutTime)?$result[0]->OutTime:'';
$InTime = isset($result[0]->InTime)?$result[0]->InTime:'';
$punchDate= isset($result[0]->punchDate)?$result[0]->punchDate:'';
$current_time = \Drupal::time()->getCurrentTime();


$date_current = date('Y-m-d', $current_time); 



      //$query->allowRowCount = TRUE;
     if($InTime >= $out_time){

$form_state->setErrorByName('out_time', $this->t('Please Select valid Out Time'));


      }else {
        //print "yes";
      }
     // exit;

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field = $form_state->getValues();
    $date=$form_state->getValue('date');
    $out_time=$form_state->getValue('out_time')->format("H:i:s");
    $out_time = $date." ".$out_time;
    // print $out_time;
    // exit;
    $uid = \Drupal::currentUser()->id(); 
    
     $url = Url::fromUserInput('/my/attendance', [], ['absolute' => 'true']);

      $database = Database::getConnection();
     
     $punch_add = $database->insert('punch_time')
        ->fields([
            'user_id' => \Drupal::currentUser()->id(),
            'punch_time' => $out_time,
            'punch_status' => 0,
            'reason' => $form_state->getValue('reason'),
          ])
        ->execute();
      
        \Drupal::messenger()->addStatus('Regularize Succesfully');
      //drupal_set_message("succesfully updated");
      $response = new RedirectResponse($url->toString());
      $response->send();
    
    
  }

}
