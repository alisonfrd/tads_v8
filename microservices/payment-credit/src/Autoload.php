<?php

class Autoload
{
    static public function loadSource($className)
    {
      $pathToFile = ROOT_PATH . '/src/' . $className . '.php';

      if (file_exists($pathToFile))
        require $pathToFile;
    }
}

spl_autoload_register('Autoload::loadSource');
