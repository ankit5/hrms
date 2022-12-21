<?php

namespace Drupal\user_module\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\views\Views;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\Core\File\FileSystemInterface;


class ApprovalController extends ControllerBase {

public function send_mail_ap($uid) {

	$uid = \Drupal::currentUser()->id();
 $user = \Drupal\user\Entity\User::load($uid);

// print $user->get('field_reporting_manager_email_id')->value;
// print $user->label();
// exit;

	$view = Views::getView('my_attendance');
	$dt = \Drupal::time()->getCurrentTime();;
//echo 'First day : '. date("Y-m-01", ($dt)).' - Last day : '. date("Y-m-t", ($dt)); 
$first_date =date("m/01/Y", ($dt));
$last_date =date("m/t/Y", ($dt));
$month =date("F", ($dt));
$year =date("Y", ($dt));

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
     $params['from'] = \Drupal::currentUser()->getEmail();
     $to = $user->get('field_reporting_manager_email_id')->value;
     $params['subject'] = 'Approval From Attendance '.$user->label().' '.$month.' '.$year;
     $params['message'] = 'Email with an attachment';
     $params['reply-to'] = $user->get('field_reporting_manager_email_id')->value;
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
      exit();
}

}