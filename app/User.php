<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use \SoapClient;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'username', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    //protected $table = 'students_request';
   // protected $fillable = ['username', 'password'];
    //protected $hidden = ['password', 'remember_token'];

    private $soapURL;
    private $soapClient;
    private $user;
    private $objUserDetails;
    private $userDetails;
    private $auth;
    private $isLogin;
    private $username;


    public function __construct($auth = null) {
        if($auth) {
            $this->soapURL = "https://passport.psu.ac.th/authentication/authentication.asmx?wsdl";
            $this->soapClient = new SoapClient($this->soapURL);
        }
    }

    public function Authenticate($username, $password) {
        $params = array(
            'username' => $username,
            'password' => $password
        );
        
        $user = $this->soapClient->Authenticate($params);

        if ($user->AuthenticateResult)
        {
            $this->username = $username;
            $this->objUserDetails = $this->soapClient->GetUserDetails($params);
            $this->userDetails = $this->objUserDetails->GetUserDetailsResult->string;
            $this->isLogin = true;

            return true;
        }

        $this->isLogin = false;
        return false;
    }

    //return firstname
    public function getFirstname()
    {
        if ($this->isLogin)
            return $this->userDetails[1];
        return null;
    }

    //return lastname
    public function getLastname()
    {
        if ($this->isLogin)
            return $this->userDetails[2];
        return null;
    }
    
    //return title name
    public function getTitle()
    {
        if ($this->isLogin)
            return $this->userDetails[12];
        return null;
    }

    public function getId() {
        return $this->userDetails[0];
    }

    public function getEmail() {
        return $this->userDetails[13];
    }

    public function getPid() {
        return $this->userDetails[5];
    }
}
