<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

define('STATUT_PRINT', 0);     // Largeur max de l'image en pixels
define('STATUT_PENDING', 1);
define('STATUT_APPROVE', 2);     // Largeur max de l'image en pixels
define('STATUT_REFUSED', 3);
define('STATUT_ABSENT', 4);
define('STATUT_PAID', 5);
define('STATUT_DO', 6);

define('STATUT_ENABLE', 7);
define('STATUT_DISABLE', 8);

define('STATUT_FAILED', 12);

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function str_random($length)
    {
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }

    static function delais($date_create)
    {
        $firstDate  = new \DateTime(date('Y-m-d'));
        $secondDate = new \DateTime($date_create);
        $result = $firstDate->diff($secondDate);

        return $result->d;
    }

    static function age($date_create)
    {
        $firstDate  = new \DateTime(date('Y-m-d'));
        $secondDate = new \DateTime($date_create);
        $result = $firstDate->diff($secondDate);

        return $result->y;
    }

    static function his_orabank($number_card)
    {

        if (strlen($number_card) == 16 || strlen($number_card) == 19) {    // 16 caractères
            $valeur = substr($number_card, 0, 4);
            if ($valeur == "4510") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    static function formatPhone($num)
    {
        if (preg_match("#^0[6-7][0-7]([-. ]?[0-9]{2}){3}$#", $num)) {
            $meta_carac = array("-", ".", " ");
            $num = str_replace($meta_carac, "", $num);
            return $num;
        }

        return false;
    }

    static function format_card($number_card)
    {

        $m1 = "";
        if (strlen($number_card) == 16) {    // 16 caractères
            // Séparation de tous les caractères
            $c = array();
            for ($i = 0; $i < 16; $i++) {
                if (is_numeric(substr($number_card, $i, 1))) {    // Uniquement des chiffres
                    $c[$i] = substr($number_card, $i, 1);
                } else {
                    return false;
                }
            }
            // Contrôle
            for ($i = 0; $i < 16; $i++) {

                $m1 .= $c[$i];
                if ($i == 3) $m1 .= " ";
                if ($i == 7) $m1 .= " ";
                if ($i == 11) $m1 .= " ";
            }
            return $m1;
        } elseif (strlen($number_card) == 19) {
            // Séparation de tous les caractères
            $c = str_replace(" ", "", $number_card);
            for ($i = 0; $i < 16; $i++) {
                if (!is_numeric(substr($c, $i, 1))) {    // Uniquement des chiffres
                    return false;
                }
            }
            // Contrôle
            return $number_card;
        } else {
            return false;
        }
    }



    static function status($status)
    {
        switch ($status) {
            case STATUT_PRINT:
                $message['type'] = "primary";
                $message['message'] = "Imprimé";
                return $message;
                break;
            case STATUT_PENDING:
                $message['type'] = "warning";
                $message['message'] = "En cours de traitement";
                return $message;
                break;
            case STATUT_APPROVE:
                $message['type'] = "success";
                $message['message'] = "Approuvée";
                return $message;
                break;
            case STATUT_REFUSED:
                $message['type'] = "danger";
                $message['message'] = "Refusée";
                return $message;
            case STATUT_PAID:
                $message['type'] = "success";
                $message['message'] = "Payée";
                return $message;
                break;
            case STATUT_DO:
                $message['type'] = "success";
                $message['message'] = "Traitée";
                return $message;
                break;
            case STATUT_ENABLE:
                $message['type'] = "success";
                $message['message'] = "Actif";
                return $message;
                break;
            case STATUT_DISABLE:
                $message['type'] = "danger";
                $message['message'] = "Désactivé";
                return $message;
                break;
            case STATUT_FAILED:
                $message['type'] = "danger";
                $message['message'] = "Échouée";
                return $message;
                break;
        }
    }

    static function request_status()
    {
        print '<option value="' . STATUT_PRINT . '">Imprimé</option>';
        print '<option value="' . STATUT_PENDING . '">En cours de traitement</option>';
        print '<option value="' . STATUT_APPROVE . '">Appouvé</option>';
        print '<option value="' . STATUT_REFUSED . '">Refusé</option>';
    }

    static function card_status()
    {
        print '<option value="' . STATUT_ENABLE . '">Actif</option>';
        print '<option value="' . STATUT_DISABLE . '">Inactif</option>';
    }

    static function refill_status()
    {
        print '<option value="' . STATUT_PRINT . '">Imprimé</option>';
        print '<option value="' . STATUT_PENDING . '">En cours de traitement</option>';
        print '<option value="' . STATUT_DO . '">Traité</option>';
    }

    static function he_can($controller, $action)
    {
        $user = Auth::user();
        $rolepermissions = DB::table('security_role_permission')
            ->join('security_permissions', 'security_permissions.id', '=', 'security_role_permission.security_permission_id')
            ->select('security_role_permission.*', 'security_permissions.*')
            ->where('security_role_permission.security_role_id', $user->security_role_id)
            ->get();

        foreach ($rolepermissions as $permission) {

            if ($permission->name == $controller) {

                switch ($action) {
                    case 'look':
                        if ($permission->look != "on") {

                            return redirect('logout')->with('error', "Vous n'avez pas le droit de faire cette action.");
                        }
                        break;
                    case 'creat':
                        if ($permission->creat != "on") {

                            return redirect('logout')->with('error', "Vous n'avez pas le droit de faire cette action.");
                        }
                        break;
                    case 'updat':
                        if ($permission->updat != "on") {

                            return redirect('logout')->with('error', "Vous n'avez pas le droit de faire cette action.");
                        }
                        break;
                    case 'del':
                        if ($permission->del != "on") {

                            return redirect('logout')->with('error', "Vous n'avez pas le droit de faire cette action.");
                        }
                        break;
                }
            }
        }
    }
}
