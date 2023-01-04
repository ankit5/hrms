<?php

namespace Drupal\user_module\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\views\Views;

use Drupal\Core\Url;
use Drupal\Core\File\FileSystemInterface;

/**
 * Class ApprovalForm.
 *
 * @package Drupal\user_module\Form
 */
class ApprovalForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'approval_form';
  }



  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
   
    $conn = Database::getConnection();
   
     $form['#prefix'] = '<div><b>Do you want to send this attendance timesheet to the manager for approval? </b></div><p></p>';
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Send',
      //'#id' => 'custom-form-submit-after-check',
      '#attributes' => array('class' => array('button button--action button--primary')),

    ];
   // $form['#attached']['library'][] = 'user_module/confirmation-js';
    $form['#cache'] = ['max-age' => 0];
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

   $uid = \Drupal::currentUser()->id();
 $user = \Drupal\user\Entity\User::load($uid);

// print $user->get('field_reporting_manager_email_id')->value;
// print $user->label();
// exit;

  $view = Views::getView('my_attendance');
  $dt = \Drupal::time()->getCurrentTime();
//echo 'First day : '. date("Y-m-01", ($dt)).' - Last day : '. date("Y-m-t", ($dt)); 
   $field_punch_date_value = \Drupal::request()->query->get('field_punch_date_value');
   $field_punch_date_value_1 = \Drupal::request()->query->get('field_punch_date_value_1');
$first_date =($field_punch_date_value)?$field_punch_date_value:date("m/01/Y", ($dt));
$last_date =($field_punch_date_value_1)?$field_punch_date_value_1:date("m/t/Y", ($dt));
$month =date("F", strtotime($first_date));

$month_int =date("m", strtotime($first_date));
$year =date("Y", ($dt));

$view->setDisplay('data_export_1');

  $view->setExposedInput(['field_punch_date_value' => $first_date]);
  $view->setExposedInput(['field_punch_date_value_1' => $last_date]);
$views_preview = $view->preview('data_export_1');

$display = \Drupal::service('renderer')->renderRoot($views_preview);
/*print $display;
exit();*/

$directory = 'public://reports';
/** @var FileSystemInterface $file_system */
$file_system = \Drupal::service('file_system');
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);

$file_name = 'Attendance-'.$user->label().'-'.$month.'-'.$year.'.xlsx';

  $file = \Drupal::service('file.repository')->writeData($display, 'public://reports/'.$file_name, FileSystemInterface::EXISTS_REPLACE);
        if (!$file) {
          // Failed to create the file, abort the batch.
          unset($context['sandbox']);
          $context['success'] = FALSE;
          throw new StorageException('Could not create a temporary file.');
        }

        $file->setTemporary();
        $file->save();
       
        //$file_path = file_url_transform_relative(file_create_url($file->getFileUri()));
          /* print $file_path;
 exit();
*/

$mailManager = \Drupal::service('plugin.manager.mail');
     $module = 'user_module';
     $key = 'approval_mail';
     //$params['from'] = \Drupal::currentUser()->getEmail();
     $to = $user->get('field_reporting_manager_email_id')->value;
     $params['subject'] = 'Please approve the timesheet of '.$user->label().' | '.$month.' '.$year;
     $params['message'] = '<p>Dear '.$user->get('field_reporting_manager')->value.'</p>

<p>Please approve the attached timesheet of '.$user->label().' for the month of ..... '.$year.'/'.$month_int.' </p>

<p>Thanks in advance. We really appreciate your kind support!!</p>

<p>Thanks & Regards </p>
<p>Wildnet Technologies Pvt. Ltd.</p>';
     $params['reply-to'] = \Drupal::config('system.site')->get('mail');
     $params['cc'] = \Drupal::currentUser()->getEmail();
     //Attaching a file to the email
      $attachment = array(

        'filepath' => $file->getFileUri(),

        'filename' => $file_name,

        'filemime' => 'application/xlsx'

    );
     $params['attachments'][] = $attachment;
     $langcode = \Drupal::currentUser()->getPreferredLangcode();
     $send = true;
     $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
     \Drupal::messenger()->addStatus('Approval Send Succesfully');
       $url = Url::fromUserInput('/my/all-attendance', [], ['absolute' => 'true']);
      $response = new RedirectResponse($url->toString());
      $response->send();
      //exit();
    
    
  }

}
