<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Companies extends Model {
     
    protected $table = 'companies';

    function getCompanyLogo($id_companies,$id_partners){
        if(empty($id_companies))return '/img/revo.png';
        $logo_group=  $this->select('logo_group')->where('id',$id_companies)->first();
        if(!empty($logo_group['logo_group'])){
            if(file_exists('/home/revopay/public_html/public/logos/companies/'.$logo_group['logo_group'])){
                return '/public/logos/companies/'.$logo_group['logo_group'];
            }else{
                $obj_partners= new Partners();
                return $obj_partners->getPartnerLogo($id_partners);
            }
        }
        else {
            $obj_partners= new Partners();
            return $obj_partners->getPartnerLogo($id_partners);
        }
    }

    function getCompanyNameById($id_companies){
        $company_name=$this->select('company_name')->where('id',$id_companies)->first();
        return $company_name['company_name'];
    }

    public function getGroupList($idlevel, $level, $whereCondition = array()){

        $grouplist = array();
        switch (strtoupper($level)){
            case "B":
                $query = DB::table($this->table)
                    ->join('partners', 'partners.id', '=', 'companies.id_partners')
                    ->select('companies.id as id', 'partners.partner_title as partner', 'company_name as group', DB::raw('\'0\' as merchants'), 'compositeID_companies as group_id', 'address', 'city', 'state', 'zip','contact_name as cname', 'contact_email as cemail', 'phone_number as phone', 'companies.last_updated as updte', 'companies.last_updated_by as updte_by', 'companies.status')
                    ->where('companies.status', '=', 1);

                //building search parameter if any only for export
                if(!empty($whereCondition)){

                    foreach($whereCondition as $key => $value){

                        $valueArray = explode('=', $value);
                        if($valueArray[0] != 'search'){
                            $query->where($valueArray[0], 'like', '%'.$valueArray[1].'%');
                        }


                    }
                }
                $grouplist = $query;
                break;
            case "P":
                $query = DB::table($this->table)
                    ->join('partners', 'partners.id', '=', 'companies.id_partners')
                    ->select('companies.id as id', 'partners.partner_title as partner', 'company_name as group', DB::raw('\'0\' as merchants'), 'compositeID_companies as group_id', 'address', 'city', 'state', 'zip','contact_name as cname', 'contact_email as cemail', 'phone_number as phone', 'companies.last_updated as updte', 'companies.last_updated_by as updte_by', 'companies.status')
                    ->where('companies.status', '=', 1)
                    ->whereIn('id_partners',explode('!',$idlevel));
                //building search parameter if any only for export
                if(!empty($whereCondition)){

                    foreach($whereCondition as $key => $value){

                        $valueArray = explode('=', $value);
                        if($valueArray[0] != 'search'){
                            $query->where($valueArray[0], 'like', '%'.$valueArray[1].'%');
                        }


                    }
                }
                $grouplist = $query;
                break;
            case "G":
                $query = DB::table($this->table)
                    ->select('companies.id as id',  'company_name as group', DB::raw('\'0\' as merchants'), 'compositeID_companies as group_id', 'address', 'city', 'state', 'zip','contact_name as cname', 'contact_email as cemail', 'phone_number as phone', 'companies.last_updated as updte', 'companies.last_updated_by as updte_by', 'companies.status')
                    ->where('companies.status', '=', 1)
                    ->whereIn('companies.id',explode('!',$idlevel));
                //building search parameter if any only for export
                if(!empty($whereCondition)){

                    foreach($whereCondition as $key => $value){

                        $valueArray = explode('=', $value);
                        if($valueArray[0] != 'search'){
                            $query->where($valueArray[0], 'like', '%'.$valueArray[1].'%');
                        }


                    }
                }
                $grouplist = $query;
                break;
        }

        return $grouplist;

    }

    public function getMerchantCountByGroupId($idgroup){

        $merchantCount = DB::table('properties')
            ->select(DB::raw('COUNT(id) as merchantcount'))
            ->where('id_companies', '=', $idgroup)
            ->where('status_clients', '=', '1')
            ->get();
        if(!empty($merchantCount)){
            return $merchantCount[0]['merchantcount'];
        }else{
            return 0;
        }

    }

    public function getUserCountByGroupId($idgroup, $status){

        $userCount = DB::table('web_users')
            ->select('*')
            ->where('web_status', '=', $status)
            ->whereIn('property_id', function($query) use ($idgroup){

                $query->from('properties');
                $query->select(DB::raw('id as property_id'));
                $query->where('id_companies', $idgroup);

            })
            ->get();

        //echo '<pre>';
        //print_r($userCount); die;
        if(!empty($userCount)){
            return count($userCount);
        }else{
            return 0;
        }


    }

    public function getGroupDetail($group_id){

        $query = DB::table($this->table)
            ->select('companies.id as id', 'company_name as group', 'compositeID_companies as group_id', 'address', 'city', 'state', 'zip', 'contact_name as cname', 'contact_email as cemail', 'phone_number as phone')
            ->where('companies.id', '=', $group_id);

        $groupdetail = $query->get();
        return $groupdetail;

    }

    public function updateGroupDetail($groupdetails = array()){

        DB::table($this->table)
            ->where('id', $groupdetails['id'])
            ->update(['company_name' => $groupdetails['company_name'], 'compositeID_companies' => $groupdetails['group_id'], 'address' => $groupdetails['address'], 'city' => $groupdetails['city'], 'state' => $groupdetails['state'], 'zip' => $groupdetails['zip'], 'contact_name' => $groupdetails['contact_name'], 'contact_email' => $groupdetails['contact_email'], 'phone_number' => $groupdetails['phone_number']]);

        return true;
    }
}
