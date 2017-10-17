<?php

namespace Drupal\detect_mobile\CacheContext;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\detect_mobile\Detect\MobileDetect;

/**
* Class MobileCacheDetect.
*/
class MobileCacheDetect implements CacheContextInterface {

  /**
   * The Mobile_Detect object.
   *
   * @var \Mobile_Detect
   */
  protected $mobileDetectInstance;

  /**
   * Constructs a new MobileCacheDetect object.
   */
  public function __construct(MobileDetect $mobileInstance) {
    $this->mobileDetectInstance = $mobileInstance;
  }

  /**
  * {@inheritdoc}
  */
  public static function getLabel() {
    return t('Check user agent is mobile or desktop');
  }

  /**
  * {@inheritdoc}
  */
  public function getContext() {
    $context = $this->mobileDetectInstance->isMobile()? 'Mobile':'Desktop';
    return $context;
  }

  /**
  * {@inheritdoc}
  */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }

}
