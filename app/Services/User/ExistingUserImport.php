<?php

namespace App\Services\User;

//use App\TempTable;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Log;
//use Webpatser\Uuid\Uuid;

class ExistingUserImport
{
    protected $users = [];
    protected $valid = true;
    protected $errorRows = [];
    protected $rows = [];
    public $errorRowId;
    public $validRowId;

    public function checkImportData($rows, $header)
    {
        $emails = [];
        foreach ($rows as $key => $row) {
            if (count($header) != count($row)) {
                // var_dump(count($header), count($row));
                continue;
            }
            // var_dump(count($header), count($row));
                $row = array_combine($header, $row);
                // $this->rows[] = $row;

                // check for correct email
                if (!$this->checkValidEmail($row['email'])) {
                    $row['message'] = 'Invalid email';
                    $this->errorRows[$key] = $row;
                    $this->valid = false;
                } else {
                    $emails[] = $row['email'];
                }           
        }

        $exist = $this->checkUserExist($emails);
        if (count($exist) > 0) {
            $this->valid = false;
            $this->addUserExistErrorMessage($exist, $header, $rows);
        }

        return $this->valid;
    }

    public function getErrorRows()
    {
        ksort($this->errorRows);
        return $this->errorRows;
    }

    // public function getErrorRowId()
    // {
    //     ksort($this->errorRows);
    //     $row = TempTable::create([
    //         'uuid' => Uuid::generate(),
    //         'user_id' => Auth::user()->id,
    //         'data' => serialize($this->errorRows),
    //         'created_at' => Carbon::now(),
    //         'updated_at' => Carbon::now(),
    //     ]);
    //     $this->errorRowId = $row->uuid->string;
    //     return $row->uuid;
    // }

    private function checkValidEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //"Invalid email format";
            return false;
        }
        return true;
    }

    private function checkUserExist($emails)
    {
        return User::whereIn('email', $emails)->get()->pluck('email')->toArray();
    }

    private function addUserExistErrorMessage($exist, $header, $rows)
    {
        foreach ($rows as $key => $row) {
            if (count($header) != count($row)) {
                // var_dump(count($header), count($row));
                continue;
                }
            $row = array_combine($header, $row);
            if (in_array($row['email'], $exist)) {
                $row['message'] = 'Email exists.';
                $this->errorRows[$key] = $row;
            }
        }
        return $rows;
    }

    public function createUsers($header, $rows)
    {
        try {
            DB::connection()->disableQueryLog();
            DB::beginTransaction();
            // $pwd = bcrypt('Welcome2u');
            foreach ($rows as $row) {
            if (count($header) != count($row)) {
                // var_dump(count($header), count($row));
                // var_dump($header);
                continue;
                }
                $row = array_combine($header, $row);
                // var_dump($header);
                // var_dump($row['indexno_old']);
                // var_dump($row['indexno']);
                // var_dump(isset($row['new']));
                // dd();
                User::create([
                    // 'indexno_new' => $row['indexno_new'],
                    'indexno' => $row['indexno'],
                    'password' => bcrypt($row['password']),
                    // 'indexno' => $row['indexno'],
                    'indexno_old' => $row['indexno_old'],
                    // 'name' => $row['name'],
                    'nameFirst' => $row['nameFirst'],
                    'nameLast' => strtoupper($row['nameLast']),
                    'name' => $row['nameFirst'].' '.strtoupper($row['nameLast']),
                    'email' => $row['email'],
                    'must_change_password' => 1,
                    // 'password' => bcrypt(uniqid()),
                    'approved_account' => 1,
                ]);
            }
            DB::commit();
        } 
        catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
            Log::info($exception->getMessage());
        }
    }

    // public function getValidRowId()
    // {
    //     $errorRows = TempTable::where('uuid', $this->errorRowId)->first();
    //     $errorRows = unserialize($errorRows->data);
    //     $validUsers = [];
    //     $emails = array_column($errorRows, 'email');
    //     foreach ($this->rows as $row) {
    //         if (!in_array($row['email'], $emails)) {
    //             $validUsers[] = $row;
    //         }
    //     }
    //     $row = TempTable::create([
    //         'uuid' => Uuid::generate(),
    //         'user_id' => Auth::user()->id,
    //         'data' => serialize($validUsers),
    //         'created_at' => Carbon::now(),
    //         'updated_at' => Carbon::now(),
    //     ]);
    //     return $row->uuid;
    // }
}