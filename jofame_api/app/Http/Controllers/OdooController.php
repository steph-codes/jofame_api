<?php
namespace App\Http\Controllers;
use Ripoo\OdooClient;
use App\Http\Controllers\SettingsController;
use Log;


class OdooController extends Controller
{
    //class Odoo {

        public $client;

        public function __construct() {
            //$settings = SettingsController::instance();
            $settings = new SettingsController();
            $host = $settings->host;
            $db = $settings->db;
            $login = $settings->login;
            $password = $settings->password;
            try {
                $this->client = new OdooClient($host, $db, $login, $password);
            } catch (Exception $e) {
                Log::info($e->getMessage());
            }

            //$host = '46.101.23.167';
            // $db = 'royalpalm';
            // $user = 'root';
            // $password = 'Amore123_';

            // $client = new OdooClient($host, $db, $user, $password);
            // var_dump(["client"=>$client->version()]);
            //$client->version();
        }

        public function get_account_move_lines($partner) {
            try {
                return $this->client->model_execute_kw(
                                "res.partner", "get_account_statement_move_lines", [$partner->id]);
            } catch (Exception $e) {

            }
        }

        public function get_product_category($name) {
            $criteria = [['name', '=', $name]];
            $fields = [];
            $limit = 1;
            try {
                $categories = $this->client->search_read(
                        'product.category', $criteria, $fields, $limit);
                $category = (object) $categories[0];
                return $category;
            } catch (Exception $e) {

            }
        }

        public function get_products_in_category($category, $limit = 50) {
            $criteria = [
                ['categ_id', '=', $category->id],
            ];
            $fields = [];
            $offset = 0;
            #$limit = 20;
            try {
                $products = $this->client->search_read(
                        'product.template', $criteria, $fields, $limit);
                return $products;
            } catch (Exception $e) {

            }
        }

        public function get_partner($email) {
            $criteria = [
                ['email', '=', $email],
                ['is_property', '=', true]
            ];
            $offset = 0;
            $limit = 1;
            try {
                $customer_ids = $this->client->search('res.partner', $criteria, $offset, $limit);
                #$fields = ['name', 'email', 'credit'];
                $fields = [];
                $customers = $this->client->read('res.partner', $customer_ids, $fields);
                $customer = (object) $customers[0];
                return $customer;
            } catch (Exception $e) {

            }
        }
        public function rand_string($length) {

            $chars = '_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012356789';
            $escape = '_$';
            return substr(str_shuffle($chars . $escape), 0, $length);
        }

    //}

}
