<?php
/**
 * Created by PhpStorm.
 * User: albertborsos
 * Date: 15. 01. 15.
 * Time: 13:45
 */

namespace albertborsos\yii2lib\helpers;


use Yii;

class Seo {

	const TYPE_CANONICAL = 'canonical';
	const TYPE_KEYWORDS = 'keywords';
	const TYPE_DESCRIPTION = 'description';
	const TYPE_TITLE = 'title';
	const TYPE_ROBOTS = 'robots';

	const INDEX = 'INDEX';
	const NOINDEX = 'NOINDEX';

	public static function registerTag($type, $content){
		$view = Yii::$app->getView();
		switch($type){
			case self::TYPE_TITLE:
				$appName = Yii::$app->name;
				if(strpos($content, $appName)){
					$view->title = $content;
				}else{
					$view->title = $content.' | '.$appName;
				}
				break;
			case self::TYPE_CANONICAL:
				$url = is_array($content) ? Yii::$app->getUrlManager()->createAbsoluteUrl($content) : $content;
				$view->registerLinkTag([
					'rel' => $type,
					'href' => $url,
				],$type);
				break;
			case self::TYPE_KEYWORDS:
			case self::TYPE_DESCRIPTION:
			case self::TYPE_ROBOTS:
				$view->registerMetaTag([
					'name' =>$type,
					'content' => $content,
				], $type);
				break;
		}
	}

	public static function noIndex(){
		$view = Yii::$app->getView();

		self::removeItemFromMetaTags(Seo::TYPE_ROBOTS);

		$view->registerMetaTag([
			'name' =>Seo::TYPE_ROBOTS,
			'content' => Seo::NOINDEX,
		]);
	}

	public static function removeItemFromMetaTags($needle){
		$metaTags = Yii::$app->getView()->metaTags;
		if(!is_null($metaTags)){
			$results = array_filter($metaTags, function ($item) use ($needle) {
				if (stripos($item, $needle) !== false) {
					return true;
				}
				return false;
			});

			foreach($results as $key => $value){
				if(isset($metaTags[$key])){
					unset(Yii::$app->getView()->metaTags[$key]);
				}
			}
		}

	}
}