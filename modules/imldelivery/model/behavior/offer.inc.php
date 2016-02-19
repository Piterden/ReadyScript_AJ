<?php
namespace Imldelivery\Model\Behavior;

class Offer extends \RS\Behavior\BehaviorAbstract
{
    function getMainImage($width, $height, $img_type = 'xy', $absolute = false)
    {
        $offer = $this->owner; //Расширяемый объект.
        $main_image = new \Photo\Model\Orm\Image($offer->getMainPhotoId());
        $image_url = $main_image->getUrl($width, $height, $img_type, $absolute);

        return $image_url;
    }

}
