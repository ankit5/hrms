<?php

namespace Drupal\automail\Form;

/**
 * @file
 * Contains Drupal\automail\Form\UserMailSettingForm.
 */

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\Role;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * UserMailSettingForm controller.
 */
class UserMailSettingForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['automail.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_setting_form';
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $roles = [];
    $options = [];
    $config = $this->configFactory->get('automail.settings');
    $form['user_fieldset'] = [
      '#type' => 'details',
      '#title' => $this->t('User Roles'),
      '#open'  => TRUE,
    ];
    $roles = Role::loadMultiple();
    foreach ($roles as $role => $roleObj) {
      if ($role != 'anonymous' && $role != 'authenticated') {
        $options[$role] = $roleObj->get('label');
      }
    }
    $form['user_fieldset']['user_roles_list'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Please choose user role for email.'),
      '#options' => $options,
      '#default_value' => empty($config->get('user_roles_list')) ? [] : $config->get('user_roles_list'),
      '#description' => $this->t("Select roles that can be assigned to receive a email."),
    ];
     $form['email_fieldset'] = [
      '#type' => 'details',
      '#title' => $this->t('Email subject and body'),
      '#open'  => TRUE,
    ];
    $form['email_fieldset']['email_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#required' => TRUE,
      '#default_value' => empty($config->get('automail_subject')) ? '' : $config->get('automail_subject'),
      '#description' => $this->t('Enter email subject.'),
    ];
    $form['email_fieldset']['tokens'] = [
      '#theme' => 'token_tree_link',
      '#token_types' => ['user'],
      '#global_types' => FALSE,
      '#click_insert' => TRUE,
    ];
    $form['email_fieldset']['email_body'] = [
      '#type' => 'text_format',
      '#required' => TRUE,
      '#title' => $this->t('Body'),
      '#default_value' => empty($config->get('automail_body')) ? '' : $config->get('automail_body'),
      '#description' => $this->t('Enter email body.'),
    ];
     $form['email_fieldset']['body_tokens'] = [
      '#theme' => 'token_tree_link',
      '#token_types' => ['user'],
      '#global_types' => FALSE,
      '#click_insert' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('automail.settings')
      ->set('user_roles_list', array_keys(array_filter($form_state->getValue('user_roles_list'))))
      ->set('automail_subject', $form_state->getValue('email_subject'))
      ->set('automail_body', $form_state->getValue('email_body')['value'])
      ->save();
    drupal_flush_all_caches();
  }

}
