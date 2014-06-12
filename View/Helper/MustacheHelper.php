<?php
app::uses('AppHelper', 'View/Helper');
app::uses('HtmlHelper', 'View/Helper');

/**
 * Class MustacheHelper
 *
 * @author Jelmer Dröge <jelmer@avolans.nl>
 */
class MustacheHelper extends AppHelper {

/**
 * @var Mustache_Engine
 */
	public $Engine;

/**
 * @var array
 */
	private $__templateCache = array(
		'name' => false,
		'template' => null
	);

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		if (class_exists('Mustache_Autoloader', false) === false) {
			App::import('Vendor', 'Mustache_Autoloader', array('file' => 'mustache' . DS . 'mustache' . DS . 'src' . DS . 'Mustache' . DS . 'Autoloader.php'));
			Mustache_Autoloader::register();
			$this->Engine = new Mustache_Engine();
		}
	}

/**
 * @param       $name
 * @param array $context
 *
 * @return string
 */
	public function renderElement($name, $context = array()) {
		return $this->Engine->render($this->getTemplate($name), $context);
	}

/**
 * @param $name
 *
 * @return mixed
 */
	public function getTemplate($name) {
		if ($this->__templateCache['name'] == $name) {
			return $this->__templateCache['template'];
		}
		$this->__templateCache = array(
			'name' => $name,
			'template' => file_get_contents(APP . 'View' . DS . 'Elements' . DS . 'mustache' . DS . $name . '.mustache')
		);

		return $this->__templateCache['template'];
	}

/**
 * Add a template to the view so that we can use it dynamically and share PHP templates in our JavaScript
 *
 * @param $name
 *
 * @return string
 */
	public function templateToView($name) {
		return sprintf('<script type="text/mustache" class="mustache-template" data-name="%s">%s</script>', str_replace('/', '-', $name), $this->getTemplate($name));
	}
}
