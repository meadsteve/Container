<?php
namespace Meadsteve\Container;

/**
 * @package    Depedency Injection Container
 * @author     Steve B <meadsteve@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

/**
 * Basic dependency injection container.
 */
class Container {

	/**
	 * All of the resource loaders registered in the container
	 * @var callable[]
	 */
	protected $arrRegisteredCallbacks = array();

	/**
	 * All the single items registered with the container
	 * @var array
	 */
	protected $arrRegisteredItems = array();

	/**
	 * Controls whether new resources and loaders can be set.
	 * @var bool
	 */
	protected $LockedFromChanges = false;

	/**
	 * Registers a resource with the container. If the resource is callable
	 * then it will be invoked every time with this container as the only argument.
	 * If the callable itself should be returned then set $ForceDisableLoader to true
	 * @param string $Name
	 * @param mixed $Thing
	 * @param bool $ForceDisableLoader Stops a callable $Thing being registered as a loader
	 * @throws \InvalidArgumentException|\RuntimeException
	 */
	public function Register($Name, $Thing, $ForceDisableLoader = false) {

		if ($this->LockedFromChanges) {
			throw new \RuntimeException(
				"Can't register resources when container is locked"
			);
		}

		if (!is_string($Name)) {
			throw new \InvalidArgumentException('Name must be a string');
		}

		$this->UnsetResource($Name);

		if (is_callable($Thing) && !$ForceDisableLoader) {
			$this->RegisterCallback($Name, $Thing);
		}
		else {
			$this->RegisterItem($Name, $Thing);
		}
	}


	/**
	 * Returns the resource in this container for $Name
	 * @param string $Name
	 * @return mixed
	 * @throws \OutOfRangeException if $Name doesn't exist
	 */
	public function GetResource($Name) {
		if ($this->CallbackExists($Name)) {
			$Callback = $this->GetCallback($Name);
			return $Callback($this);
		}
		else if ($this->ItemExists($Name)) {
			return $this->GetItem($Name);
		}

		throw new \OutOfRangeException('Resource not found for ' . $Name);
	}

	/**
	 * Returns the actual instance of the callback used to load the resource $Name
	 * @param string $Name
	 * @throws \OutOfRangeException if the specified name doesn't exist
	 * @return callable
	 */
	public function &GetResourceLoader($Name) {
		if ($this->CallbackExists($Name)) {
			return $this->GetCallback($Name);
		}
		else {
			throw new \OutOfRangeException($Name . "doesn't exist as a loader");
		}
	}

	/**
	 * Locks the container so that new resources can't be registered.
	 * @param bool $Lock
	 */
	public function Lock($Lock = true) {
		$this->LockedFromChanges = ($Lock !== false);
	}

	/**
	 * Loads an item from the container
	 * @param $Name
	 * @throws \OutOfRangeException if the specified name doesn't exist
	 * @return mixed
	 */
	public function __get($Name) {
		return $this->GetResource($Name);
	}

	/**
	 * Registers an item with the container.
	 * @param $Name
	 * @param $Value
	 */
	public function __set($Name, $Value) {
		$this->Register($Name, $Value);
	}

	/**
	 * @param $Name
	 */
	protected function UnsetResource($Name) {
		if ($this->CallbackExists($Name)) {
			$this->UnsetCallback($Name);
		}
		else if ($this->ItemExists($Name)) {
			$this->UnsetItem($Name);
		}
	}

	/**
	 * @param string $Name
	 * @param callable $Callback
	 */
	protected function RegisterCallback($Name, $Callback) {
		$this->arrRegisteredCallbacks[$Name] = $Callback;
	}

	/**
	 * @param string $Name
	 * @param mixed $Item
	 */
	protected function RegisterItem($Name, $Item) {
		$this->arrRegisteredItems[$Name] = $Item;
	}


	/**
	 * @param string $Name
	 * @return bool
	 */
	protected function CallbackExists($Name) {
		return array_key_exists($Name, $this->arrRegisteredCallbacks);
	}

	/**
	 * @param string $Name
	 * @return callable
	 */
	protected function GetCallback($Name) {
		return $this->arrRegisteredCallbacks[$Name];
	}

	/**
	 * @param string $Name
	 */
	protected function UnsetCallback($Name) {
		unset($this->arrRegisteredCallbacks[$Name]);
	}

	/**
	 * @param string $Name
	 * @return bool
	 */
	protected function ItemExists($Name) {
		return array_key_exists($Name, $this->arrRegisteredItems);
	}

	/**
	 * @param string $Name
	 * @return mixed
	 */
	protected function GetItem($Name) {
		return $this->arrRegisteredItems[$Name];
	}

	/**
	 * @param string $Name
	 */
	protected function UnsetItem($Name) {
		unset($this->arrRegisteredItems[$Name]);
	}
}
