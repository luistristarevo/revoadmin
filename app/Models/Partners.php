<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Partners extends Model {
    
    protected $table = 'partners';

    function getPartnerLogo($id_partners){
        if(empty($id_partners))return '/img/revo.png';
        $logo=  $this->select('logo')->where('id',$id_partners)->first();
        if(empty($logo))return '/img/revo.png';
        if(empty($logo['logo']))return '/img/revo.png';
        if(file_exists('/home/revopay/public_html/public/logos/partners/'.$logo['logo'])){
            return '/public/logos/partners/'.$logo['logo'];
        }else{
             return '/img/revo.png';
        }
    }

    public function getPartnerList($idlevel, $level, $whereCondition = array()){

        $partnerlist = array();

        switch (strtoupper($level)){
            case "B":
                $query = DB::table($this->table)
                    ->select('partners.id', 'partner_name as name', 'partner_title as title', 'partner_composite_id as partner_id', 'partners.logo', 'partners.status', 'partners.layout_id as layout', 'partners.last_updated as update_date', 'partners.last_updated_by as update_by');

                if(!empty($whereCondition)){
                    foreach($whereCondition as $key => $value){
                        $valueArray = explode('=', $value);
                        if($valueArray[0] != 'search'){
                            $query->where($valueArray[0], 'like', '%'.$valueArray[1].'%');
                        }
                    }

                }
                $query->groupBy('partners.id');
                $partnerlist = $query;
                break;

            case "P":
                $query = DB::table($this->table)
                    ->select('partners.id', 'partner_name as name', 'partner_title as title', 'partner_composite_id as partner_id', 'partners.logo', 'partners.status', 'partners.layout_id as layout', 'partners.last_updated as update_date', 'partners.last_updated_by as update_by')
                    ->whereIn('partners.id',explode('!',$idlevel));

                if(!empty($whereCondition)){
                    foreach($whereCondition as $key => $value){
                        $valueArray = explode('=', $value);
                        if($valueArray[0] != 'search'){
                            $query->where($valueArray[0], 'like', '%'.$valueArray[1].'%');
                        }
                    }

                }
                $query->groupBy('partners.id');
                $partnerlist = $query;
                break;
        }
        return $partnerlist;

    }

    public function getGroupCount($id_partner){

        $groups = DB::table('companies')
            ->select(DB::raw('COUNT(id) as groups'))
            ->where('status', 1)
            ->where('id_partners', $id_partner)
            ->get();
        if(!empty($groups)){
            return $groups[0]['groups'];
        }
        return 0;
    }

    public function getMerchantCount($id_partner){

        $merchants = DB::table('properties')
            ->select(DB::raw('COUNT(id) as merchants'))
            ->where('status_clients', 1)
            ->where('id_partners', $id_partner)
            ->get();

        if(!empty($merchants)){
            return $merchants[0]['merchants'];
        }
        return 0;

    }

    public function getPartnerDetail( $partner_id){

        $query = DB::table($this->table)
            ->select('partners.id', 'partner_name as name', 'partner_title as title', 'partner_composite_id as partner_id', 'partners.logo', 'partners.status', 'partners.layout_id as layout', 'partners.last_updated as update_date', 'partners.last_updated_by as update_by')
            ->where('partners.id', '=', $partner_id);
        $partnerdetail = $query->get();
        return $partnerdetail;

    }

    public function updatePartnerDetail($partnerInfo, $filename){

        $user_name = $this->getUserNameById($partnerInfo['user_id']);
        $updatedby = $user_name[0]['first_name'].' '.$user_name[0]['last_name'];
        if($filename != ''){
            DB::table($this->table)
                ->where('id', $partnerInfo['id'])
                ->update(['status' => $partnerInfo['status'], 'partner_title' => $partnerInfo['partner_title'], 'partner_composite_id' => $partnerInfo['partner_id'], 'layout_id' => $partnerInfo['layout'], 'last_updated_by' => $updatedby, 'logo' => $filename]);
        }else{

            DB::table($this->table)
                ->where('id', $partnerInfo['id'])
                ->update(['status' => $partnerInfo['status'], 'partner_title' => $partnerInfo['partner_title'], 'partner_composite_id' => $partnerInfo['partner_id'], 'layout_id' => $partnerInfo['layout'], 'last_updated_by' => $updatedby]);

        }
        return true;

    }

    public function getUserNameById($user_id){

        $user_detail = DB::table('users')
            ->select('id', 'first_name', 'last_name')
            ->where('id', '=', $user_id)
            ->get();

        return $user_detail;


    }

    public function getAllPartners(){
        $result=DB::table($this->table)->get();
        return $result;
    }

    function get1PartnerInfo($id,$key){
        $result=$this->where('id','=',$id)->select($key)->first();
        return $result[$key];
    }
}
