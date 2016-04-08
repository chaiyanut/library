<?php
namespace App\Console\Commands;

use Mail;
use Illuminate\Console\Command;
use DB;

class Test extends Command {

    protected $name = 'test';

    protected $description = 'This is a test.';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $result = DB::select('SELECT users.* FROM users, borrows where date(date_add(borrows.created_at, interval 4 day)) = date(now()) and borrows.user_id = users.id;')->get();
        foreach ($result as $user) {

            Mail::send('admin.email', ['user'=>$user], function ($message) use ($user) {
                $message->from('library.coe.psu@gmail.com', "library");
                $message->to($user->email, $user->name)->subject('Test');
            });
        }

        $this->info('Test has fired.');
    }
}
