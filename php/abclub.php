<?php //namespace App\Http\ABClub

class Variation {
  public $name;
  public $optimizelyId;
  public $probability;

  function __construct($options) {
    $this->name = $options['name'];
    $this->optimizelyId = array_key_exists('optimizely_id', $options) ? $options['optimizely_id'] : null;
    $this->probability = array_key_exists('probability', $options) ? $options['probability'] : null;
  }
}

class Experiment {
  public $name;
  public $optimizelyId;
  public $variations;
  public $variant;
  public $storage;

  function __construct($options) {
    $this->name = $options['name'];
    $this->optimizelyId = array_key_exists('optimizely_id', $options) ? $options['optimizely_id'] : null;

    $this->variations = array();
    foreach ($options['variations'] as $variationName => $variationOptions) {
      $variationOptions['name'] = $variationName;
      $this->variations[$variationName] = new Variation($variationOptions);
      $this->storage= new CookieStorage();
    }
  }

  public function test() {
    $this->variant = $this->variant ? $this->variant : $this->storage->get($this->name);
    if (!$this->variant) {
      $this->variant = $this->randomVariant();
      $this->storage->set($this->name, $this->variant);
    }
    return $this->variant;
  }
  
  public function getBucket() {
    $this->variant = $this->variant ? $this->variant : $this->storage->get($this->name);
    return $this->variant;
  }
  
  public function getOptimizelyExperimentId() {
    return $this->optimizelyId;
  }
  
  public function getOptimizelyVariationId() {
    $variant = $this->getBucket();
    return $variant && $variant->optimizelyId ? $variant->optimizelyId : null;
  }

  public function shouldTrackWithOptimizely() {
    return $this->getOptimizelyExperimentId() && $this->getOptimizelyVariationId();
  }
  
  public function randomVariant() {
    $unspecifiedVariations = array();
    $randomNumber = (float) rand() / (float) getrandmax();
    $currentSum = 0;
    
    foreach ($this->variations as $variationName => $variation) {
      if ($variation->probability != null) {
        $currentSum += $variation->probability;
        if ($currentSum > $randomNumber) {
          return $variation;
        }
      } else {
        array_push($unspecifiedVariations, $variation);
      }
    }
    
    return $unspecifiedVariations[array_rand($unspecifiedVariations)];
  }
}

abstract class Storage {
  abstract public function get($name);
  abstract public function set($name, $value);
}

/**
 * By default, cookies are used as the storage mechanism for experiment/variation pairs.
 * Cookies can be used on any type of a/b test where there is a visitor, regardless of whether
 * they are signed in or not.
 *
 * If you have an a/b test that is exclusively for signed in users and you can persist the
 * variation assignments to a database, you should use UserStorage instead.
 */
class CookieStorage extends Storage {
  public function get($name) {
    // TODO for your application
  }

  public function set($name, $value) {
    // TODO for your application
  }
}

/**
 * You may also want a class UserStorage for a/b tests that happen exclusively on signed in users.
 * Just store the variation name in the database associated with that user.
 *
 * This way, even if the user clears their cookies or visits from different browsers/tablets/etc, you
 * can still know which variation they're in.
 *
 * It's also useful for a/b tests that run when the user isn't present, such as a/b testing emails
 * sent asynchronously via cron jobs.
 */
class UserStorage extends Storage {
  public function get($name) {
    // TODO for your application
  }

  public function set($name, $value) {
    // TODO for your application
  }
}

class ABClub {
  private static $experiments;

  public static function init() {
    ABClub::$experiments = ABClub::$experiments || array();
  }
  
  public static function get($name) {
    $experiment = ABClub::$experiments[$name];
    if (!$experiment) {
      throw new Exception('ABClub cannot find experiment named \'' . $name .
        '\' in registered experiments list.');
    }
    return $experiment;
  }

  public static function register($options) {
    $experimentName = $options['name'];
    $existingExperiment = ABClub::$experiments[$experimentName];
    if ($existingExperiment) {
      throw new Exception('ABClub cannot register experiment named \'' . $experimentName .
        '\' because it was already registered.');
    }
    ABClub::$experiments[$experimentName] = new Experiment($options);
  }

  public static function test($name) {
    $experiment = ABClub::get($name);
    return $experiment->test();
  }
  
  public static function activeExperiments() {
    $actives = array();
    foreach ($this->experiments as $experimentName => $experiment) {
      $bucket = $experiment->getBucket();
      if ($bucket) {
        array_push($actives, array($experimentName => $experiment]);
      }
    }
    return $actives;
  }
}

ABClub::init();

ABClub::register(array(
  'name' => "button",
  "variations" => array(
    "blue" => array(
      "probability" => 1.0 / 12.0
    ),
    "green" => array(
      "probability" => 1.0 / 6.0
    ),
    "red" => array(
      // If a probability is unspecified, it'll be whatever is leftover, equally shared with
      // all other probability unspecified variants.
    ),
    "yellow" => array(
    )
  )
));

$randomPlacements = array();
foreach (range(0, 100000) as $number) {
  $name = ABClub::get("button")->randomVariant()->name;
  $randomPlacements[$name] = array_key_exists($name, $randomPlacements) ? $randomPlacements[$name] : 0;
  $randomPlacements[$name]++;
}

var_dump($randomPlacements);
