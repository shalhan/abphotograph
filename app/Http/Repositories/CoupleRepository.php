<?php

namespace App\Http\Repositories;

use App\Couple;
use Auth;
use Config;

class CoupleRepository extends Repository
{
    private $couple;
    private $DEFAULT_SUBFOLDER = 'diary';
    private $DEFAULT_SUBFOLDER2 = 'wedding=';

    public function __construct(Couple $couple) {
        $this->couple = $couple;
    }
    
    /**
     * @param Array $data
     */
    public function save($data) {
        $this->couple->MSGROOM_GUID = $data["MSGROOM_GUID"];
        $this->couple->MSBRIDE_GUID = $data["MSBRIDE_GUID"];
        $this->couple->PACKAGE_ID = 1;
        $this->couple->EXPIRED_DATE = date("Y-m-d",strtotime($data["EXPIRED_DATE"]));
        $this->couple->STATUS = Auth::user()->GUID;
        $this->couple->LOVE_STORY = "";
        $this->couple->MSVENDOR_GUID = 2; //temporary
        $this->couple->PREWEDPHOTO_AMOUNT = $data["PREWEDPHOTO_AMOUNT"];
        $this->couple->VIEW_AMOUNT = 0;
        $this->couple->CREATED_DATE = date("Y-m-d");
        $this->couple->SUBFOLDER = $this->DEFAULT_SUBFOLDER;
        $this->couple->SUBFOLDER2 = $this->DEFAULT_SUBFOLDER2 . $data["SUBFOLDER2"];
        $this->couple->MSTEMPLATE_GUID = $data["MSTEMPLATE_GUID"];
        $this->couple->save();
    }
    
    /**
     * @param Array $data
     */
    public function getAll($data) {
        $take = Config::get('pagination.couples');
        $skip = ( $data['page'] - 1 ) * $take;

        return $this->couple
                    ->select('GUID','MSGROOM_GUID', 'MSBRIDE_GUID', 'PACKAGE_ID', 'EXPIRED_DATE', 'STATUS', 'LOVE_STORY', 'MSVENDOR_GUID', 'PREWEDPHOTO_AMOUNT', 'VIEW_AMOUNT', 'CREATED_DATE', 'SUBFOLDER', 'SUBFOLDER2', 'MSTEMPLATE_GUID')
                    ->where('MSVENDOR_GUID', Auth::user()->GUID)
                    ->with(['bride', 'groom', 'template'])
                    ->skip($skip)
                    ->take($take)
                    ->get();
    }
    
    /**
     * @param Int $id
     */
    public function getById($id) {
        return $this->couple
                    ->select('GUID','MSGROOM_GUID', 'MSBRIDE_GUID', 'PACKAGE_ID', 'EXPIRED_DATE', 'STATUS', 'LOVE_STORY', 'MSVENDOR_GUID', 'PREWEDPHOTO_AMOUNT', 'VIEW_AMOUNT', 'CREATED_DATE', 'SUBFOLDER', 'SUBFOLDER2', 'MSTEMPLATE_GUID')
                    ->where('MSVENDOR_GUID', Auth::user()->GUID)
                    ->with(['bride', 'groom', 'template'])
                    ->find($id);
    }



    /*)*
     * @return Couple
     */
    public function getCouple() {
        return $this->couple;
    }
}
