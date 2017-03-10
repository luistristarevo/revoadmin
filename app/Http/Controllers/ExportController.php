<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ExportController extends Controller
{
    public function export($query_base64, $type){
        //security
        $objAdminAuth = new AuthAdminController();
        $objAdminAuth->checkAuthPermissions(Session::get('user_logged')['id']);

        $sql = base64_decode($query_base64);
  
        $records = DB::select(DB::raw($sql));

        switch($type){
            case 'topstransactions':
                $this->exportTops($records,'transactions');
                break;
            case 'topsdeposits':
                $this->exportTops($records,'deposits');
                break;
            case 'lockboxtransactions':
                $this->exportTops($records,'transactions',true);
                break;
            case 'lockboxdeposits':
                $this->exportTops($records,'deposits',true);
                break;
            case 'caliber':
                $this->exportCaliber($records);
                break;
            case 'csv':
                $this->exportCsv($records);
                break;
            case 'jenark':
                $this->exportJenark($records);
                break;
            case 'promo':
                $this->exportPromo($records);
                break;
        }


    }

    public function exportCsv($records){
        $string_to_save='';
        $extension = 'csv';
        foreach($records as $record){
            $it = 0;
            $count = count($record);
            foreach($record as $key ){
                $string_to_save.= $key;
                if($it < $count-1){
                    $string_to_save.=',';
                }
                $it++;
            }
            $string_to_save .= chr(13).chr(10);

        }
        $this->outputDownloadFile($string_to_save,'Csv',$extension);
    }

    public function exportPromo($records){
        $string_to_save='';
        $extension = 'txt';
        foreach ($records as $record) {
            $string_to_save.= $record['promo_name'].','.$record['promo_link'].chr(13).chr(10);

            $string_to_save.= 'applied to partners:'.chr(13).chr(10);
            $applied_partners = DB::table('promo_applied')
                ->join('partners','promo_applied.id_partners','=','partners.id')
                ->where('id_promo','=',$record['id_p'])
                ->where('id_partners','>',0)->get();
            foreach ($applied_partners as $p){
                $string_to_save.= $p['partner_title'].chr(13).chr(10);
            }

            $string_to_save.= 'applied to groups:'.chr(13).chr(10);
            $applied_groups = DB::table('promo_applied')
                ->join('companies','promo_applied.id_companies','=','companies.id')
                ->where('id_promo','=',$record['id_p'])
                ->where('promo_applied.id_companies','>',0)->get();
            foreach ($applied_groups as $g){
                $string_to_save.= $g['company_name'].chr(13).chr(10);
            }

            $string_to_save.= 'applied to merchants:'.chr(13).chr(10);
            $applied_merchants = DB::table('promo_applied')
                ->join('properties','promo_applied.id_property','=','properties.id')
                ->where('id_promo','=',$record['id_p'])
                ->where('promo_applied.id_property','>',0)->get();
            foreach ($applied_merchants as $g){
                $string_to_save.= $g['name_clients'].chr(13).chr(10);
            }

            $string_to_save.="----------".chr(13).chr(10);
        }

        $this->outputDownloadFile($string_to_save,'Promo',$extension);


    }

    public function exportTops($records,$type,$regular=null){
        $mid = 0;
        $string_to_save='';
        switch ($type){
            case 'deposits':
                $obj_property=new \App\Models\Properties();
                foreach ($records as $line) {
                    switch ($mid) {
                        case 1:
                            $cmid = $obj_property->get1PropertyInfo($line['id_property'], 'lockbox_id');
                            break;
                        case 2:
                            $cmid = $obj_property->get1PropertyInfo($line['id_property'], 'misc_field');
                            break;
                        case 3:
                            $cmid = $obj_property->get1PropertyInfo($line['id_property'], 'bank_id');
                            break;
                        case 0:
                        default:
                            $cmid = $line['property_id'];
                            break;
                    }
                    $string_to_save .= sprintf("%-13.13s", $cmid);
                    if ($regular) {
                        $string_to_save .= sprintf("%'013.13s", $line['customer_id']);
                    } else {
                        $string_to_save .= sprintf("%'014.14s", $line['customer_id']);
                    }
                    $string_to_save .= date('ymd', strtotime($line['date']));
                    $string_to_save .= str_pad(substr($line['trans_id'], -10), 10, '0', STR_PAD_LEFT);
                    //change float to string without dot (123.45 -> 12345)
                    $string_to_save .= sprintf("%'08.8s", number_format($line['credit'], 2, '', ''));
                    $string_to_save .= "\x0D\x0A";
                }
                break;
            case 'transactions':
                foreach($records as $line){
                    switch($mid){
                        case 1:
                            $cmid=$line['lockbox_id'];
                            break;
                        case 2:
                            $cmid=$line['misc_field'];
                            break;
                        case 3:
                            $cmid=$line['bank_id'];
                            break;
                        case 0:
                        default:
                            $cmid=$line['compositeID_clients'];
                            break;
                    }
                    $string_to_save .= sprintf("%-13.13s",$cmid);
                    if($regular){
                        $string_to_save .= sprintf("%'013.13s", $line['account_number']);
                    }
                    else {
                        $string_to_save .= sprintf("%'014.14s", $line['account_number']);
                    }
                    $string_to_save .= date('ymd', strtotime($line['trans_last_post_date']));
                    $string_to_save .= str_pad(substr($line['trans_id'],-10),10,'0',STR_PAD_LEFT);
                    //change float to string without dot (123.45 -> 12345)
                    $string_to_save .= sprintf("%'08.8s", number_format($line['trans_net_amount'], 2, '', ''));
                    $string_to_save .= "\x0D\x0A";

                }
                break;
        }

        $extension = "txt";
        $this->outputDownloadFile($string_to_save,'Export',$extension);
    }

    public function exportCaliber($records){
        $mid = 0;
        $amount=0;
        $count=0;
        $string_to_save='';
        foreach($records as $line){
            $string_to_save .= $line['trans_id'].','.$line['account_number'].',';
            switch($mid){
                case 1:
                    $cmid=$line['lockbox_id'];
                    break;
                case 2:
                    $cmid=$line['misc_field'];
                    break;
                case 3:
                    $cmid=$line['bank_id'];
                    break;
                case 0:
                default:
                    $cmid=$line['compositeID_clients'];
                    break;
            }
            $count++;
            $amount+=$line['trans_net_amount'];
            $string_to_save .= date('Ymd', strtotime($line['trans_last_post_date'])).',';
            $string_to_save .= number_format($line['trans_net_amount'], 2,'.','').','.$line['trans_card_type'].',';
            $string_to_save .= $cmid;
            $string_to_save .= "\x0D\x0A";
        }
        $extension = "txt";
        $this->outputDownloadFile($string_to_save,'Caliber',$extension);
    }

    public function exportJenark($records){
        $mid =0;
        $string_to_save='';
        $totalamount=0;
        foreach($records as $line){
            switch($mid){
                case 1:
                    $cmid=$line['lockbox_id'];
                    break;
                case 2:
                    $cmid=$line['misc_field'];
                    break;
                case 3:
                    $cmid=$line['bank_id'];
                    break;
                case 0:
                default:
                    $cmid=$line['compositeID_clients'];
                    break;
            }
            $totalamount+=$line['trans_net_amount'];
            $account_number= substr(str_pad($line['account_number'],8,0,STR_PAD_LEFT),-8);
            $aux=date_create($line['trans_last_post_date']);
            $date=date_format($aux,'mdY');
            $amount=sprintf("%'08.8s", number_format($line['trans_net_amount'], 2, '', ''));
            $trans_id=substr(str_pad($line['trans_id'],8,0,STR_PAD_LEFT),-8);
            $string_to_save.=$account_number." ".$date." ".$amount." ".$trans_id;
            $string_to_save .= "\x0D\x0A";
        }
        $totalamount=str_pad(str_replace(".","",$totalamount),8,0,STR_PAD_LEFT);
        $trans_total=str_pad(count($records),8,0,STR_PAD_LEFT);
        $textheader="Header ".$date." ".$totalamount." ".$trans_total."\r\n";
        $string_to_save=$textheader.$string_to_save;
        $extension = "txt";
        $this->outputDownloadFile($string_to_save,'Jenark',$extension);
    }

    public function outputDownloadFile($data,$name, $extension){
        header('Content-Type: application/octet-stream; charset=utf-8');
        header('Content-Disposition: attachment; filename='.$name.'.'.$extension);
        header('Content-Length: '. strlen($data) );
        echo $data;
        exit;
    }
}
