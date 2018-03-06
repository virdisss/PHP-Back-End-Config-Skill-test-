<?php

namespace Coalition;

class ConfigRepository implements \ArrayAccess
{
    /**
     * ConfigRepository Constructor
     */
    private $configArray = array();

    public function __construct($arr = null)
    {
       if ($arr != null) {

           foreach ($arr as $key => $value) {
               $this->configArray[$key] = $value;
           }
       }
    }

    /**
     * Determine whether the config array contains the given key
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->configArray[$key]);
    }

    /**
     * Set a value on the config array
     *
     * @param string $key
     * @param mixed  $value
     * @return \Coalition\ConfigRepository
     */
    public function set($key, $value)
    {
        if (is_null($key)) {
            $this->configArray[] = $value;
        } else {
            $this->configArray[$key] = $value;
        }
        return $this;
    }

    /**
     * Get an item from the config array
     *
     * If the key does not exist the default
     * value should be returned
     *
     * @param string     $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            $default = $this->configArray[$key];
        }
        return $default;
    }

    /**
     * Remove an item from the config array
     *
     * @param string $key
     * @return \Coalition\ConfigRepository
     */
    public function remove($key)
    {
        unset($this->configArray[$key]);
        return $this;
    }

    /**
     * Load config items from a file or an array of files
     *
     * The file name should be the config key and the value
     * should be the return value from the file
     * 
     * @param array|string The full path to the files $files
     * @return void
     */
    public function load($files)
    {
        //If it is an array we fetch each element from the array and add the value to the config array
        if (is_array($files)) {

            foreach ($files as $path) {
                $this->fillConfigArray($path);
            }

        } else {
            $this->fillConfigArray($files);
        }
    }

    private function fillConfigArray($path) {
        $key = basename($path, ".php");
        $value = include $path;
        $this->set($key, $value);
    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
        return $this->remove($offset);
    }
}