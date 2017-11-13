<?php

	class Config {

		public static function get($path = null){
			$config = false; 
			if($path) {
				$config = $GLOBALS['config'];
				$path = explode('/', $path);

				foreach($path as $bit){
					if(isset($config[$bit])){
						$config = $config[$bit];
					} else {
						$config = false;
					}
				}
			}
			return $config;
			
		}

		public static function getScreen(){
			return self::get('screenName');
		}

		

	}





?>