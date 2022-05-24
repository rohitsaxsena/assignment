<?php

namespace Drupal\assignment;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class CurrentTimeService.
 *
 * Get the current time on the basis of timezone.
 *
 * @package Drupal\assignment\Services
 */
class CurrentTimeService {
  /**
   * The config factory object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a StaticMenuLinkOverrides object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   A configuration factory instance.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentTime() {
    $timezone = $this->configFactory->get('assignment.config_form')->get('timezone');

    $date = new DrupalDateTime();
    $date->setTimezone(new \DateTimeZone($timezone));
    return $date->format('jS M Y - g:i A');
  }

}
