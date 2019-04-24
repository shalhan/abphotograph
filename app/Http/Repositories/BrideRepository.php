<?php

namespace App\Http\Repositories;

use App\Bride;

class BrideRepository extends Repository
{
    private $bride;

    public function __construct(Bride $bride) {
        $this->bride = $bride;
    }
    
    public function save($data) {
        $this->bride->BRIDE_NAME = $data["BRIDE_NAME"];
        $this->bride->BRIDE_REALNAME = $data["BRIDE_REALNAME"];
        $this->bride->BRIDE_FACEBOOK = isset($data["BRIDE_FACEBOOK"]) ? $data["BRIDE_FACEBOOK"] : "#" ;
        $this->bride->BRIDE_TWITTER = isset($data["BRIDE_TWITTER"]) ? $data["BRIDE_TWITTER"] : "#" ;
        $this->bride->BRIDE_INSTA = isset($data["BRIDE_INSTA"]) ? $data["BRIDE_INSTA"] : "#" ;
        $this->bride->save();
        return $this;
    }

    public function getBride() {
        return $this->bride;
    }
}
