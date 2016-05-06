<?php
namespace Imldelivery\Model\Behavior;

class Product extends \RS\Behavior\BehaviorAbstract {

	function getSibling($direction, $back_url) {

		$product_id = $this->owner->id;
		$reverse_arr = array(
			'prev' => 'next',
			'next' => 'prev'
		);

		$app = \RS\Application\Application::getInstance();
		$breadcrumbs = $app->breadcrumbs->getBreadCrumbs();
		$last_link = end($breadcrumbs);

		$q = new \RS\Orm\Request();
        $alias = array_filter(explode('/', $back_url));

		$dir = $q->select()
            ->from(new \Catalog\Model\Orm\Dir, 'D')
            ->where(array(
	            'D.is_spec_dir' => 'N',
	            'D.public' => 1,
	            'D.alias' => end($alias)
	        ))
	        ->select('D.is_spec_dir', 'D.public', 'D.alias', 'D.id')
            ->objects();

        $dir_id = $dir[0]->id;

        if (count($dir) > 0) {

	        $q = new \RS\Orm\Request();
	        $ids = $q->select()
	            ->from(new \Catalog\Model\Orm\Xdir, 'X')
	            ->where(array(
		            'X.dir_id' => $dir_id
		        ))
		        ->select('X.product_id', 'X.dir_id')
	            ->objects();

	        $ids_array = array();
	        foreach ($ids as $product) {
	        	$ids_array[] = $product->product_id;
	        }

			$q = new \RS\Orm\Request();
	        $products = $q->select()
	            ->from(new \Catalog\Model\Orm\Product, 'P')
	            ->where(array(
	            	'P.public' => 1
	            ))
	            ->whereIn('P.id', $ids_array)
		        // ->select('P.id', 'P.dateof')
		        ->orderby('P.title DESC')
	            ->objects();

	        foreach ($products as $key => $product) {
	        	if ($product->id == $product_id) {
	        		$current = $key;
	        	}
	        }

        	switch ($direction) {
        		case 'prev':
        			$sibling = $products[$current - 1];
        			break;

				case 'next':
        			$sibling = $products[$current + 1];
        			break;

        		default:
        			return false;
        			break;
        	}

        	if ($sibling === NULL) return false;
        	//$prod_obj = new \Catalog\Model\Orm\Product($sibling);

        	// echo '<pre>';
        	// echo print_r($sibling->getUrl());
        	// echo '</pre>';
	        // return var_dump($current);
	        return $sibling->getUrl();
        }

	}

}
