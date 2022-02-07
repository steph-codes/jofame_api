<?php

namespace App\Http\Controllers;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\SettingsController;
use Illuminate\Http\Request;
use Odoo;
use Ripoo\OdooClient;
// use OdooApi;
//use Illuminate\Http\Client\Request;



class VisitorController extends Controller
{
    
    public function __construct()
    {
        //DB::connection instance
        //
        // $host = '46.101.23.167';
        // $db = 'royalpalm';
        // $user = 'root';
        // $password = 'Amore123_';

        // $client = new OdooClient($host, $db, $user, $password);
        // $client->version();
    }

    // public function showAllArticles(){
    //     // $host = '46.101.23.167';
    //     // $db = 'royalpalm';
    //     // $user = 'root';
    //     // $password = 'Amore123_';

    //     // $client = new OdooClient($host, $db, $user, $password);
    //     // var_dump(["client"=>$client->version()]);
    //     //$client->version();



    //     //dd("test");
    //     // $odoo = new Obuchmann\LaravelOdooApi\Odoo();
    //     // $client = OdooApi::getClient();
    //     $odoo = new \Obuchmann\LaravelOdooApi\Odoo;
    //     $this->odoo = $odoo
    //         ->username('support@jofameintegrated.com')
    //         ->password('Edeke123_')
    //         ->database('royalpalm')
    //         ->host('https://ebsuiness.jofameintegrated.com')
    //         ->connect();
    //     var_dump($odoo->version()); exit;
    //     $version = $odoo->connect();

    //     return response(["response"=>$version]);
    //     // //return response()->json(Article::all());


    // }

    // public function showArticle($id){
    //     //dd($id);
    //     //return response()->json(Article::find($id));
    // }

    // public function createVisitor(Request $request)
    // {
    //     //validate
    //     $this->validate($request, [
    //         'name'=> 'required',
    //         'email'=> 'required',
    //         'Phone'=>'required',
    //         'address'=>'required'
    //     ]);

    //     $data = file_get_contents("php://input");
    //     $vars = (array) json_decode($data);
    //     $new_phone = substr($vars['phone'], -10);
    //     $phone = '0'.$new_phone;
    //     //$create = new Create();
    //     //return $create->register_visitor($vars['name'],$vars['email'],$phone, $vars['address'], $vars['tag']);

    //     //dd($request); //from inputs
    //     //insert record
    //     $article = Article::create($request->all());
    //     return response()->json($article, 201);

    // }

    // public function oneVisitor($id, Request $request)
    // {
    //     $new_phone = substr(input('phone'), -10);
    //     $phone = '0'.$new_phone;
    //     $create = new CreateController();
    //     return $create->get_visitor_id($phone);

    //     // $visitor = Article::findOrFail($id);
    //     // return response()->json($visitor, 200);
    // }

    // public function visitorRecord($id, Request $request)
    // {
    //     $new_phone = substr(input('phone'), -10);
    //     $phone = '0'.$new_phone;
    //     $create = new Create();
    //     return $create->get_visitor_record($phone);
    //     // $visitor = Article::findorFail($id);
    //     // return response()->json($visitor, 200);
    // }

    // public function updateVisitor($id, Request $request)
    // {
    //     //dd($request);
    //     $data = file_get_contents("php://input");
    //     $vars = (array) json_decode($data);
    //     $new_phone = substr(input('phone'), -10);
    //     $phone = '0'.$new_phone;
    //     $create = new Create();
    //     return $create->get_visitor_record($phone);
    //     $create = new Create();
    //     return $create->register_visitor($vars['name'],$vars['email'],$phone, $vars['address'], $vars['tag']);

    //     //dd($request); //from inputs
    //     //insert record
    //     // $visitor = Article::findorFail($id);
    //     // $visitor->update($request->all());
    //     // return response()->json($visitor, 200);

    // }

    // public function createVisit()
    // {
    //     $data = file_get_contents("php://input");
    //     $vars = (array) json_decode($data);
    //     $new_phone = substr($vars['phone'], -10);
    //     $phone = '0'.$new_phone;
    //     $create = new Create();
    //     return $create->create_visit($vars['phone'],$vars['time'],$vars['user_email'],$vars['reason']);
    // }
    // public function login(Request $request)
    // {
    //     $this->validate($request, [
    //         'email'=> 'required',
    //         'password'=>'required'
    //     ]);

    //     $data = file_get_contents("php://input");
    //     $vars = (array) json_decode($data);
    //     $create = new Create();
    //     return $create->login($vars['email'],$vars['password']);
    // }

    public function getUser(Request $request){
        $email = $request->only('email');
        $create = new CreateController();
        return $create->get_partner_json($email);
    }

    // public function userVisit(){
    //     $email = input('email');
    //     $create = new Create();
    //     return $create->get_user_visits($email);
    // }

    // public function userPendingVisitCount(){
    //     $email = input('email');
    //     $create = new Create();
    //     return $create->get_pending_visit_count($email);
    // }

    // public function getAccountInfo(){
    //     $email = input('email');
    //     $create = new Create();
    //     return $create->get_account_info($email);
    // }

    // public function cancelVisit(){
    //     $email = input('email');
    //     $create = new Create();
    //     return $create->cancel_visit($email);
    // }

    // public function resetPassword(){
    //     $email = input('email');
    //     $create = new Create();
    //     return $create->reset_password($email);
    // }

    // public function changeEmail(){
    //     $email = input('email');
    //     $new_email = input('new_email');
    //     $create = new Create();
    //     return $create->change_email($email,$new_email);
    // }

    // public function changePhone(){
    //     $phone = input('phone');
    //     $email = input('email');
    //     $create = new Create();
    //     return $create->change_email($email,$phone);
    // }

    // public function createComplaint(){
    //     $id = input('email');
    //     $issue = input('issue');
    //     $desc = input('desc');
    //     $create = new Create();
    //     return $create->create_complaint($id,$issue,$desc);
    // }

    // public function getComplaint(){
    //     $id = input('email');
    //     $create = new Create();
    //     return $create->get_user_complaint($id);;
    // }

    // public function Announcements(){
    //     //$id = input('email');
    //     $create = new Create();
    //     return $create->get_announcements();
    // }







    // public function delete($id)
    // {
    //     //dd($id);
    //     Article::findOrFail($id)->delete;
    //     return response('Deleted Succesfully', 200);
    // }
}
