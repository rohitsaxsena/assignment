<?php

namespace Drupal\assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\assignment\CurrentTimeService;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;

/**
 * Provides a 'Current Time' block.
 *
 * @Block(
 *  id = "assignment",
 *  admin_label = @Translation("Current Time"),
 * )
 */
class CurrentTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The current time service.
   *
   * @var \Drupal\assignment\CurrentTimeService
   */
  protected $currentTime;

  /**
   * The config factory object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The page cache disabling policy.
   *
   * @var \Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $pageCacheKillSwitch;

  /**
   * Class constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\assignment\CurrentTimeService $currentTime
   *   The current time service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   A configuration factory instance.
   * @param \Drupal\Core\PageCache\ResponsePolicy\KillSwitch $pageCacheKillSwitch
   *   The kill switch.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentTimeService $currentTime, ConfigFactoryInterface $config_factory, KillSwitch $pageCacheKillSwitch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentTime = $currentTime;
    $this->configFactory = $config_factory;
    $this->pageCacheKillSwitch = $pageCacheKillSwitch;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('assignment.currenty_time_service'),
      $container->get('config.factory'),
      $container->get('page_cache_kill_switch')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data['country'] = $this->configFactory->get('assignment.config_form')->get('country');
    $data['city'] = $this->configFactory->get('assignment.config_form')->get('city');
    $data['currentTime'] = $this->currentTime->getCurrentTime();

    $this->pageCacheKillSwitch->trigger();

    $build = [
      '#theme' => 'current_time',
      '#data' => $data,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
