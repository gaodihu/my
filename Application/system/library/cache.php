<?php

/**
 * OpenCart Ukrainian Community
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License, Version 3
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/copyleft/gpl.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email

 *
 * @category   OpenCart
 * @package    OCU Memcached
 * @copyright  Copyright (c) 2011 created by UncleAndy, maintained by Eugene Lifescale for OpenCart Ukrainian Community (http://opencart-ukraine.tumblr.com)
 * @license    http://www.gnu.org/copyleft/gpl.html     GNU General Public License, Version 3
 */


final class Cache {

    private $expire;
    private $memcache;
    private $ismemcache = false;
    private $store_id ;
    private $lang_id ;
    public function __construct($exp = 3600,$store_id,$language_id) {
        $this->expire = $exp;
        $this->store_id = $store_id;
        $this->lang_id = $language_id;
        if (CACHE_DRIVER == 'memcached' && class_exists('Memcache'))
        {
            $mc = new Memcache;
        if ($mc->pconnect(MEMCACHE_HOSTNAME, MEMCACHE_PORT))
        {
            $this->memcache = $mc;
            $this->ismemcache = true;
        };
        };

        $files = glob(DIR_CACHE . 'cache.*');

        if ($files) {
            foreach ($files as $file) {
                $time = substr(strrchr($file, '.'), 1);

                  if ($time < time()) {
                    if (file_exists($file)) {
                        @unlink($file);
                    }
                  }
            }
        }
      }

    public function get($key) {
         $key = $key . $this->lang_id.$this->store_id;
        if ((CACHE_DRIVER == 'memcached') && class_exists('Memcache') && $this->ismemcache)
        {
           
            return($this->memcache->get(MEMCACHE_NAMESPACE . $key, 0));
        }
        else
        {
        $files = glob(DIR_CACHE . 'cache.' . $key . '.*');

        if ($files) {
            foreach ($files as $file) {
                  $cache = '';

                $handle = fopen($file, 'r');

                if ($handle) {
                    $cache = fread($handle, filesize($file));

                    fclose($handle);
                }

                  return unserialize($cache);
                }
        }
        }
      }

      public function set($key, $value) {
         $key = $key . $this->lang_id.$this->store_id;
        if ((CACHE_DRIVER == 'memcached') &&  class_exists('Memcache') &&  $this->ismemcache)
        {
            
        $this->memcache->set(MEMCACHE_NAMESPACE . $key, $value, MEMCACHE_COMPRESSED, $this->expire);
        }
        else
        {

                $this->delete($key);

            $file = DIR_CACHE . 'cache.' . $key . '.' . (time() + $this->expire);

            $handle = fopen($file, 'w');

                fwrite($handle, serialize($value));

                fclose($handle);
            };
      }

      public function delete($key) {
        if ((CACHE_DRIVER == 'memcached') && class_exists('Memcache') && $this->ismemcache)
        {
        $this->memcache->delete(MEMCACHE_NAMESPACE . $key, 0);
        }
        else
        {
        $files = glob(DIR_CACHE . 'cache.' . $key . '.*');

        if ($files) {
            foreach ($files as $file) {
                  if (file_exists($file)) {
                    @unlink($file);
                    clearstatcache();
                }
            }
        }
        }
      }
}
