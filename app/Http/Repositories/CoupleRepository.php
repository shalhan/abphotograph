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
        $this->couple->STATUS = 1;
        $this->couple->LOVE_STORY = "";
        $this->couple->MSVENDOR_GUID = Auth::user()->GUID;
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
    public function edit($data) {
        $couple = $this->couple->find($data["GUID"]);
        $couple->MSGROOM_GUID = $data["MSGROOM_GUID"];
        $couple->MSBRIDE_GUID = $data["MSBRIDE_GUID"];
        $couple->PACKAGE_ID = 1;
        $couple->EXPIRED_DATE = date("Y-m-d",strtotime($data["EXPIRED_DATE"]));
        $couple->STATUS = 1;
        $couple->LOVE_STORY = "";
        $couple->MSVENDOR_GUID = Auth::user()->GUID; //temporary
        $couple->PREWEDPHOTO_AMOUNT = $data["PREWEDPHOTO_AMOUNT"];
        $couple->VIEW_AMOUNT = 0;
        $couple->CREATED_DATE = date("Y-m-d");
        $couple->SUBFOLDER = $this->DEFAULT_SUBFOLDER;
        $couple->SUBFOLDER2 = $this->DEFAULT_SUBFOLDER2 . $data["SUBFOLDER2"];
        $couple->MSTEMPLATE_GUID = $data["MSTEMPLATE_GUID"];
        $couple->update();
        $this->couple = $couple;
    }
    
    /**
     * @param Array $data
     * @return Array Couple
     */
    public function getAll($data) {
        $take = Config::get('pagination.couples');
        $skip = ( $data['page'] - 1 ) * $take;

        $couples = $this->couple
                    ->select('GUID','MSGROOM_GUID', 'MSBRIDE_GUID', 'PACKAGE_ID', 'EXPIRED_DATE', 'STATUS', 'LOVE_STORY', 'MSVENDOR_GUID', 'PREWEDPHOTO_AMOUNT', 'VIEW_AMOUNT', 'CREATED_DATE', 'SUBFOLDER', 'SUBFOLDER2', 'MSTEMPLATE_GUID', 'COUPLE_COVER_1', 'COUPLE_COVER_2', 'COUPLE_COVER_3')
                    ->where('MSVENDOR_GUID', Auth::user()->GUID)
                    ->with(['bride', 'groom', 'template'])
                    ->skip($skip)
                    ->take($take)
                    ->orderBy('GUID', 'desc')
                    ->get();

        return $this->getResponse($couples, $data);
    }
    
    /**
     * @param Int $id
     * @return Object Couple
     */
    public function getById($id) {
        return $this->couple
                    ->select('GUID','MSGROOM_GUID', 'MSBRIDE_GUID', 'PACKAGE_ID', 'EXPIRED_DATE', 'STATUS', 'LOVE_STORY', 'MSVENDOR_GUID', 'PREWEDPHOTO_AMOUNT', 'VIEW_AMOUNT', 'CREATED_DATE', 'SUBFOLDER', 'SUBFOLDER2', 'MSTEMPLATE_GUID', 'COUPLE_COVER_1', 'COUPLE_COVER_2', 'COUPLE_COVER_3')
                    ->where('MSVENDOR_GUID', Auth::user()->GUID)
                    ->with(['bride', 'groom', 'template', 'galleries'])
                    ->find($id);
    }

    /**
     * @param Int $id => couple id
     */

    public function dropById($id) {
        $this->couple
            ->where("MSVENDOR_GUID", Auth::user()->GUID)
            ->find($id)
            ->delete();
    }

    /*)*
     * @return Couple
     */
    public function getCouple() {
        return $this->couple;
    }
}
