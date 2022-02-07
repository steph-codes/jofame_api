<?php
namespace App\Http\Controllers;
use App\Http\Controllers\OdooController;
use Ripoo\OdooClient;
use Carbon\Carbon;
use Exception;


class CreateController extends Controller
{

        public $client;
        public $settings;


        public function __construct() {

                $this->client = new OdooClient("http://46.101.23.167:10069", "jofame", "support@jofameintegrated.com", "Edeke123_");
        }

        public function register_visitor($name,$email,$phone,$address){

            $phone_number = "0" . substr($phone, -10);
            $is_visitor = $this->get_visitor_record($phone_number);
            if(isset($is_visitor['id'])){
                return $is_visitor['id'];
            }

            $visitor_id = uniqid("visitor_");
            $data = [
              'name' => $name,
              'email' => $email,
              'street' => $address,
              'phone' => $phone,
              'id_proof_no' => $visitor_id
            ];
            $id = $this->client->create('fo.visitor', $data);
            return $id;

        }

        public function get_visitor_record($phone){

            $criteria = [
              ['phone', '=', $phone],
            ];
            $offset = 0;
            $limit = 1;
            $fields = ['id','name', 'email', 'street','id_proof_no','phone'];
            $visitor = $this->client->search_read('fo.visitor', $criteria, $fields, 1);

            if($visitor == []){

                return json_encode([
                   "status" => 1,
                   "message" => "No record with that number"
                ]);
            }
            return $visitor[0];
        }

        public function get_visitor_id($phone){

            $criteria = [
              ['phone', '=', $phone],
            ];
            $offset = 0;
            $limit = 1;
            $id = $this->client->search('fo.visitor', $criteria, $offset, $limit);


            if($id == []){

                return json_encode([
                   "status" => 1,
                   "message" => "No record with that number"
                ]);
            }
            return $id[0];
        }


        public function create_visit($phone,$time,$user_email,$reason){

            $phone_number = "0" . substr($phone, -10);
            $entry = Carbon::parse($time)->format('Y/m/d h:i:s');
            //$entry = Carbon::parse($time)->format('m/d/Y h:m');
            $visitor_id = $this->get_visitor_id($phone_number);
            $visitor_record = $this->get_visitor_record($phone_number);
            $partner = $this->get_partner($user_email);
            $partner_id = $partner->id;
            $visitor_email = $visitor_record['email'];
            $visitor_phone = $visitor_record['phone'];
            $visitor_id_number = $visitor_record['id_proof_no'];
            if($visitor_phone == null){
                return json_encode([
                   "status" => 1,
                   "message" => "Visitor Is invalid"
                ]);
            }
            $data = [
              'visitor' => $visitor_id,
              'email' => $visitor_email,
              'phone' => $phone_number,
              'check_in_date' => $entry,
              'visiting_person' => $partner_id,
              'visit_description' => $reason,
              //'resaon' => "1",
              'purpose' => "1"
            ];

            $id = $this->client->create('fo.visit', $data);
            return $id;

        }

        public function create_complaint($email,$issue,$description){

            $partner =  $this->get_partner($email);
            $partner_id = $partner->id;

            if($partner_id == null){

                return json_encode([
                   "status" => 1,
                   "message" => "User Is invalid"
                ]);

            }

            $data = [
              'name' => $issue,
              'email_from' => $email,
              'partner_id' => $partner_id,
              'description' => $description,
            ];

            $id = $this->client->create('project.issue', $data);

            if($id == null){

                return json_encode([
                   "status" => 2,
                   "message" => "Unable to create complaint"
                ]);

            }

            return json_encode([
                   "status" => 0,
                   "message" => "Complaint created"
            ]);

        }

        public function get_user_complaint($email){

            $partner =  $this->get_partner($email);
            $partner_id = $partner->id;

            $criteria = [
              ['partner_id', '=', $partner_id],
            ];
            $offset = 0;
            $limit = 100;
            $fields = [];
            $id = $this->client->search('project.issue', $criteria, $offset, $limit);
            //var_dump($id);
            $complaints = $this->client->read('project.issue', $id, $fields);

            if($complaints == []){

                return json_encode([
                   "status" => 1,
                   "message" => "No user with such id"
                ]);
            }
            return json_encode($complaints);
        }

        public function get_partner($email){

            $criteria = [
                ['email', '=', $email],
                ['is_property', '=', true]
            ];
            $offset = 0;
            $limit = 1;
            try {
                $customer_ids = $this->client->search('res.partner', $criteria, $offset, $limit);
                $fields = [];
                $customers = $this->client->read('res.partner', $customer_ids, $fields);
                if (isset($customers[0])){
                    $customer = (object) $customers[0];
                    return $customer;
                }else{
                    return NULL;
                }

            } catch (Exception $e) {

            }

        }

        public function get_partner_phone($phone){

            $criteria = [
                ['phone', '=', $phone],
                ['is_property', '=', true]
            ];
            $offset = 0;
            $limit = 1;
            try {
                $customer_ids = $this->client->search('res.partner', $criteria, $offset, $limit);
                $fields = [];
                $customers = $this->client->read('res.partner', $customer_ids, $fields);
                if (isset($customers[0])){
                    $customer = (object) $customers[0];
                    return $customer;
                }else{
                    return NULL;
                }

            } catch (Exception $e) {

            }

        }


        public function get_user_visits($email){

            $partner =  $this->get_partner($email);
            $partner_id = $partner->id;

            $criteria = [
                ['visiting_person', '=', $partner_id],
            ];
            $offset = 0;
            $limit = 10;
            try {
                $customer_ids = $this->client->search('fo.visit', $criteria, $offset, $limit);
                $fields = [];
                $customers = $this->client->read('fo.visit', $customer_ids, $fields);
                if ($customers){
                    return json_encode($customers);
                }else{
                    return json_encode([
                        "status"=> 1,
                        "message"=> "No existing visit"

                    ]);
                }

            } catch (Exception $e) {

            }

        }


        public function get_pending_visit_count($email){

            $partner =  $this->get_partner($email);
            $partner_id = $partner->id;

            $criteria = [
                ['visiting_person', '=', $partner_id],
                ['state', '=', 'draft'],
            ];
            $offset = 0;
            $limit = 100;
            try {
                $customer_ids = $this->client->search('fo.visit', $criteria, $offset, $limit);
                $fields = [];
                $customers = $this->client->read('fo.visit', $customer_ids, $fields);
                if ($customers){
                    return count($customers);//json_encode($customers);
                }else{
                   return 0;
                }

            } catch (Exception $e) {

            }

        }


        public function get_partner_json($email){

            $criteria = [
                ['email', '=', $email]
            ];
            $offset = 0;
            $limit = 1;
            try {
                $customer_ids = $this->client->search('res.partner', $criteria, $offset, $limit);
                var_dump($customer_ids);
                die();
                $fields = [];
                $customers = $this->client->read('res.partner', $customer_ids, $fields);
                if (isset($customers[0])){
                    $customer = (object) $customers[0];
                    var_dump($customer);
                    die();
                    return json_encode($customer);
                }else{
                    return json_encode([
                        "status"=> 1,
                        "message"=> "User does not exist"

                    ]);
                }

            } catch (Exception $e) {

            }

        }


        public function book($name,$email,$phone,$address,$time,$user_email,$reason){
            $phone_number = "0" . substr($phone, -10);
            $visitor = $this->register_visitor($name,$email,$phone_number,$address);
            if(is_int($visitor)){

                $visit = $this->create_visit($phone_number,$time,$user_email,$reason);

                if(is_int($visit)){
                    $visitor_record = $this->get_visitor_record($phone_number);
                    $visitor_email = $visitor_record['email'];
                    $visitor_id_number = $visitor_record['id_proof_no'];
                    $visitor_name = $visitor_record['name'];
                    $visitor_address = $visitor_record['street'];
                    return json_encode([
                       "status" => 0,
                       "message" => "visitor created",
                       "visitor_name" => $visitor_name,
                       "visitor_email" => $visitor_email,
                       "visitor_phone" => $phone_number,
                       "visitor_id" => $visitor_id_number,
                       "visit_time" => $time,
                       "visit_address" => $address
                       ]);
                }else{
                    return json_encode([
                       "status" => 1,
                       "message" => "Visit could not be created"
                    ]);
                }
            }else{
                return json_encode([
                       "status" => 1,
                       "message" => "Visitor could not be created"
                    ]);
            }
        }

        public function login($email,$password){

            $hash = hash('SHA512', $password);
            $email_trim = trim($email);
            $phone_trim = "0" . substr(trim($email), -10);
            try {
                $partner = $this->get_partner($email_trim);
                $partner_phone = $this->get_partner_phone($phone_trim);
                //print_r($partner);
                if($partner == NULL){
                    if($partner_phone){
                         //->portal_password == $hash
                        $display_name = $partner_phone->display_name;
                        $name = $partner_phone->name;
                        $email = $partner_phone->email;
                        $street = $partner_phone->street;
                        $house = $partner_phone->contact_address;

                        return json_encode([
                               "status" => 0,
                               "name" => $name,
                               "display_name" => $display_name,
                               "email" => $email,
                               "street" => $street,
                               "house" => $house,
                               "message" => "Login Successful"
                            ]);
                    }
                    return json_encode([
                       "status" => 1,
                       "message" => "Invalid User"
                    ]);
                }
                //return json_encode([
                       //$partner
                    //]);
                if($partner) {
                    //->portal_password == $hash
                    $display_name = $partner->display_name;
                    $name = $partner->name;
                    $email = $partner->email;
                    $street = $partner->street;
                    $house = $partner->contact_address;

                return json_encode([
                       "status" => 0,
                       "name" => $name,
                       "display_name" => $display_name,
                       "email" => $email,
                       "street" => $street,
                       "house" => $house,
                       "message" => "Login Successful"
                    ]);
              }
                else {
                  return json_encode([
                      "status" => 1,
                      "message" => "Invalid Password"
                   ]);
                }
            }catch(Exception $e){

                return json_encode([
                   "status" => 1,
                   "message" => "Invalid Username"
                    ]);
            }

        }

        public function get_account_info($email){

            $odoo = new OdooController();

            try{

                $partner = $this->get_partner($email);
                # get product_category
                $category = $odoo->get_product_category($this->settings->product_category);
                $products = $odoo->get_products_in_category($category);
                # odoo details
                $credit = number_format($partner->credit,2);
                $house = $partner->name;
                $invoiced = number_format($partner->total_invoiced,2);
                $phone = isset($partner->mobile) ? $partner->mobile : $partner->phone;
                $lines = $odoo->get_account_move_lines($partner);
                $lines_arr = json_decode($lines,"true")[$partner->id];
                $payments = $lines_arr;
                $products_all = $products;

                return json_encode([
                    'status' => 0,
                    'credit' => $credit,
                    'house' => $house,
                    'invoiced' => $invoiced,
                    'phone' => $phone,
                    'payments' => [
                        $payments
                    ],
                    'products' => [
                        $products_all
                    ]

                ]);
            }catch(Exception $e){

                return json_encode([
                   "status" => 1,
                   "message" => "Something went wrong"
                    ]);
            }

        }


        public function cancel_visit($phone_num){

            $phone = "0" . substr($phone_num, -10);

            $ids = $this->client->search('fo.visit', [['phone', '=', $phone]], 0, 1);
            try{

                $this->client->write('fo.visit', $ids, ['state' => 'cancel']);
                return json_encode([
                    'status' => 0,
                    'state' => 'cancelled',
                ]);

            }
            catch(Exception $e){
                return json_encode([

                    'status' => 1,
                    'state' => 'Draft',
                ]);

            }
        }


        public function reset_password($email){

            try {
                $partner = $this->get_partner($email);
                $pwd = $this->rand_string(8);
                $hash = hash('SHA512',$pwd);

                $this->client->write('res.partner', [$partner->id], ['portal_password' => $hash ]);
                $vars = ['name' => $partner->name,'username'=> $email, 'pwd' => $pwd];
                // Mail::send("password-reset", $vars, function($message) use ($vars) {
                //     $message->to($vars['username'],$vars['name']);
                //     $message->subject('MAGPORA Password Reset');
                //     });

                return json_encode([
                    'status' => 0,
                    'message' => 'Password Reset Successful',
                ]);

            }
            catch(Exception $e){
                
                return json_encode([
                    'status' => 1,
                    'state' => 'Password Reset Failed',
                ]);
            }

        }


        public function change_email($email, $new_email){
            try {
                $partner = $this->get_partner($email);
                $this->client->write('res.partner', [$partner->id], ['email' => $new_email ]);

                return json_encode([
                    'status' => 0,
                    'message' => 'Email Change Successful',
                    'username' => $new_email,
                    'name' => $partner->name
                ]);

            }

            catch(Exception $e){
               
                return json_encode([
                    'status' => 1,
                    'state' => 'Email Change Failed',
                ]);
            }

        }

        public function change_phone($email, $phone_num){
            $phone = "0" . substr($phone_num, -10);
            try {
                $partner = $this->get_partner($email);
                $this->client->write('res.partner', [$partner->id], ['phone' => $phone]);

                return json_encode([
                    'status' => 0,
                    'message' => 'Phone number Change Successful',
                    'phone' => $phone,
                    'name' => $partner->name
                ]);

            }

            catch(Exception $e){
                
                return json_encode([
                    'status' => 1,
                    'state' => 'Phone number Change Failed',
                ]);
            }

        }


        function rand_string( $length ) {

            $chars = '_abc_defghi$$_j_klmn_opqrs_tuvw_$xyzABCD_EFGH$IJKLMNOPQRS$TUVWXYZ0123$567$89';
            return substr(str_shuffle($chars),0,$length);

        }


        public function confirm_msisdn($phone){

            $criteria = [
              ['phone', '=', $phone],
            ];
            $offset = 0;
            $limit = 1;
            $fields = ['id','name', 'email', 'phone'];
            $visitor = $this->client->search_read('res.partner', $criteria, $fields, 1);

            if($visitor == []){

                return json_encode([
                   "status" => 1,
                   "message" => "No record with that number"
                ]);
            }
            return $visitor[0];
        }

        public function get_announcements(){

            $criteria = [
              ['state', '=', "done"],
            ];
            $offset = 0;
            $limit = 10;
            $fields = ["__last_update","announcement","name","id","announcement_reason"];
            $announcements = $this->client->search_read('hr.announcement', $criteria, $fields);

            if(empty($announcements)){

                return json_encode([
                   "status" => 1,
                   "message" => "No announcements"
                ]);
            }
            return json_encode($announcements);
        }

}
