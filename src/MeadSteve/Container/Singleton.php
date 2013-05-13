<?php
namespace Meadsteve\Container;

/**
 * @package    Depedency Injection Container
 * @author     Steve B <meadsteve@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

/**
 * The singleton class is a small companion class for the MeadSteve\Container
 * Dependency injection container. It allows a resource to be wrapped up
 * as a singleton so that any construction logic it may have is only run a single
 * time.
 */
class Singleton {

	/**
	 * The function responsible for creating the resource instance for this singleton.
	 * @var callable
	 */
	protected $Callback;

	/**
	 * The specific instance represented by the singleton once the loader has been
	 * called.
	 * @var mixed
	 */
	protected $LoadedInstance;

	/**
	 * @param callable $WrappedCallback Function that creates the singleton resource.
	 */
	public function __construct($WrappedCallback) {
		$this->Callback = $WrappedCallback;
		$this->LoadedInstance = null;
	}

	/**
	 * Returns the single instance of the resource represented by this singleton.
	 * @param Container $Cont
	 * @return mixed
	 */
	public function GetInstance(Container $Cont = null) {
		if ($this->LoadedInstance === null) {
			$Callback = $this->Callback;
			$this->LoadedInstance = $Callback($Cont);
		}
		return $this->LoadedInstance;
	}

	/**
	 * Forces the singleton instance to be reloaded next time it's requested.
	 */
	public function ForceReload() {
		$this->LoadedInstance = null;
	}

	/**
	 * When called as a function the singleton returns the instance it represents.
	 * @param mixed $Arg
	 * @return mixed
	 */
	public function __invoke($Arg = null) {
		return $this->GetInstance($Arg);
	}
}
