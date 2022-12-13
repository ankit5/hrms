<?php

namespace Drupal\automail\Form;

/**
 * @file
 * Contains Drupal\automail\Form\AutoMailSettingForm.
 */

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AutoMailSettingForm controller.
 */
class AutoMailSettingForm extends ConfigFormBase {

  protected $nodes;

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
    return 'automail_setting_form';
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityManagerInterface $nodes, ConfigFactoryInterface $config_factory) {
    $this->nodes = $nodes;
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $nodeTypes = [];
    $options = [];
    $nodeTypes = $this->nodes->getStorage('node_type')->loadMultiple();
    $config = $this->configFactory->get('automail.settings');
    $form['nodetype_fieldset'] = [
      '#type' => 'details',
      '#title' => $this->t('Node Types'),
      '#open'  => TRUE,
    ];
    foreach ($nodeTypes as $key => $value) {
      $options[$key] = $value->label();
    }
    $form['nodetype_fieldset']['bundle_list'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Please choose content type for email.'),
      '#options' => $options,
      '#required' => TRUE,
      '#default_value' => empty($config->get('bundle_list')) ? [] : $config->get('bundle_list'),
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
      '#token_types' => ['node'],
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
      '#token_types' => ['node'],
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
      ->set('bundle_list', array_keys(array_filter($form_state->getValue('bundle_list'))))
      ->set('automail_subject', $form_state->getValue('email_subject'))
      ->set('automail_body', $form_state->getValue('email_body')['value'])
      ->save();
    drupal_flush_all_caches();
  }

}
