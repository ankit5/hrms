<?php

namespace Drupal\automail\Plugin\QueueWorker;

/**
 * @file
 * Contains \Drupal\automail\Plugin\QueueWorker\AutoMailSendQueuedMail.
 */

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\user\Entity\User;

/**
 * Send emails for the users.
 *
 * @QueueWorker(
 *   id = "email_queue",
 *   title = @Translation("Automail Send Queued Email"),
 *   cron = {"time" = 600}
 * )
 */
class AutoMailSendQueuedMail extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $mailManager = \Drupal::service('plugin.manager.mail');
   $params['title'] = $data['subject'];
   $params['message'] = $data['message'];
  /*   $token = \Drupal::token();
    $params['message'] = $token->replace($data['message']);
    $params['title'] = $token->replace($data['subject']);*/
    $user_role = $data['user_role'];
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = TRUE;
    $ids = [];
    $users = [];
    $query = \Drupal::entityQuery('user')->condition('status', 1);
    if (!empty($user_role)) {
      $query->condition('roles', $user_role, 'IN');
      $ids = $query->execute();
    }
    else {
      $ids = $query->execute();
    }
    $users = User::loadMultiple($ids);
    
    foreach ($users as $user) {
$params['account'] = $user;
      $result = $mailManager->mail('automail', 'send_mailto_user', $user->getEmail(), $langcode, $params, $send);
    }
    if ($result['result'] != TRUE) {
      $message = t('There was a problem sending your email notification');
      \Drupal::messenger()->addError($message);
      \Drupal::logger('Automail-log')->error($message);
      return;
    }
  }

}
