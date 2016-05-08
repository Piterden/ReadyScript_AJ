<?php
namespace Imldelivery\Model\Behavior;

class User extends \RS\Behavior\BehaviorAbstract {

	public function getFirstAddress() {
		$user_id = $this->owner->offsetGet('id');

		$q = new \RS\Orm\Request();
		$first_address = $q->select()
            ->from(new \Shop\Model\Orm\Address, 'A')
            ->where(array(
	            'A.user_id' => $user_id,
	            'A.deleted' => 0
	        ))
	        ->limit(1)
            ->objects();

        if (count($first_address) > 0) {
        	return $first_address[0];
        }

        return false;
	}

}
