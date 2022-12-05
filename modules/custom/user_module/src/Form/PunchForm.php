<?php

namespace Drupal\user_module\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

/**
 * Class PunchForm.
 *
 * @package Drupal\user_module\Form
 */
class PunchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'punch_form';
  }



  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
   
    $conn = Database::getConnection();
    if (isset($num)) {
      $query = $conn->select('user_logins', 'm')
        ->condition('id', $num)
        ->fields('m');
      $record = $query->execute()->fetchAssoc();
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Mark Attendance',
      '#attributes' => array('class' => array('button button--action button--primary')),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field = $form_state->getValues();
    $uid = \Drupal::currentUser()->id(); 
    
     $url = Url::fromUserInput('/my/attendance', [], ['absolute' => 'true']);

      $database = Database::getConnection();
      $check_date = date('Y-m-d', time())." 00:00:00";
      $select = $database->query("SELECT * from punch_time where punch_time = '".$check_date."' and user_id='".$uid."' ");
      $select->allowRowCount = TRUE;
      $current_time = \Drupal::time()->getCurrentTime();
      $date_current = date('Y-m-d', $current_time);
       
      if($select->rowCount()>0){
       $punch_updated = $database->update('punch_time')
  ->fields([
    'punch_time' => date('Y-m-d\TH:i:s', $current_time),
  ])
  ->condition('punch_time', $check_date, '=')
  ->condition('user_id', $uid, '=')
  ->execute();
      }else{
     $punch_add = $database->insert('punch_time')
        ->fields([
            'user_id' => \Drupal::currentUser()->id(),
            'punch_time' => date('Y-m-d\TH:i:s', $current_time),
          ])
        ->execute();
      }
        \Drupal::messenger()->addStatus('Punch Succesfully');
      //drupal_set_message("succesfully updated");
      $response = new RedirectResponse($url->toString());
      $response->send();
    
    
  }

}
