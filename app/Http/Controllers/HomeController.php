<?php
namespace App\Http\Controllers;

use App\Email;
use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;


class HomeController extends Controller
{
    public function upload()
    {
//        $sendMail = $this->mail();
        $tmp_name = $_FILES['file']['tmp_name'];
        $result = $this->readCSV($tmp_name, ['delimiter' => ',']);

        $data = [];
        $count = 0;
        for($i = 0; $i < count($result); $i++) {
            if ($i == 0) {
                $data[] = $result[$i];
                continue;
            }
            if (!isset($result[$i][0]) || !isset($result[$i][3]) || trim($result[$i][0]) == '' || trim($result[$i][3]) == '' || !filter_var(trim($result[$i][3]), FILTER_VALIDATE_EMAIL)) { continue; };
//            $data['name'] = trim($result[$i][0]);
//            $data['email'] = trim($result[$i][3]);
//            $this->mail($data);
            $data[] = $result[$i];
            $count++;
        }
        echo json_encode(['success' => 1, 'data' => $data, 'total' => $count]);
    }

    public function sendmail() {
        $dataString = $_POST['data'];
        $data = json_decode($dataString);
        $count = 0;
        for ($i = 0; $i < count($data); $i++) {
            if ($i == 0 || !isset($data[$i][0]) || !isset($data[$i][3]) || trim($data[$i][0]) == '' || trim($data[$i][3]) == '' || !filter_var(trim($data[$i][3]), FILTER_VALIDATE_EMAIL)) { continue; };
            $res['name'] = trim($data[$i][0]);
            $res['email'] = trim($data[$i][3]);
            $phone = isset($data[$i][2]) ? trim($data[$i][2]) : '';
            $location = isset($data[$i][1]) ? trim($data[$i][1]) : '';
            $socialMedia = isset($data[$i][4]) ? trim($data[$i][4]) : '';
            $this->mail($res);
            $email = Email::where('email', $res['email'])->first();
            if (!$email) {
                $email = new Email();
                $email->name = $res['name'];
                $email->email = $res['email'];
                $email->phone = $phone;
                $email->location = $location;
                $email->social_media = $socialMedia;
                $email->sent_count = 1;
                $email->is_replied = 0;
            } else {
                $email->name = $res['name'];
            }
            $email->save();
            $count++;
        }
        echo json_encode(['success' => 1, 'total' => $count]);
    }
    public function readCSV($csvFile, $array)
    {
        $line_of_text = [];
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
        }
        fclose($file_handle);
        return $line_of_text;
    }

    public function mail($data)
    {
        Mail::to($data['email'])->send(new SendMailable($data));
        return true;
    }

    public function subscribe()
    {
        Log::info($_POST);
        $data = file_get_contents("php://input");
        $events = json_decode($data, true);
        Log::info($events);
        die;
        $from = isset($_POST['from']) ? $_POST['from'] : 'Default Name';
        $data['name'] = $from;
        $data['email'] = 'zinkyfmt@gmail.com';
//        $this->mail($data);
        echo json_encode(['success' => 1]);
    }
}

