<?php

use Mail;
use Illuminate\Console\Command;

class Test extends Command {

    protected $name = 'test';

    protected $description = 'This is a test.';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {

        Mail::send('admin.email', [], function($message) {
            $message->from('library.coe.psu@gmail.com', 'PSU PHUKET CoE Library System');
            $message->to('ntossapo@gmail.com', 'Tossapon Nuanchuay')->subject('Test');
        });

        $this->info('Test has fired.');
    }
}