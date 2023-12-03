<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpseclib3\Crypt\RSA;

class Password extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'password',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'time' => 'datetime',
    ];


    public function getPasswordAttribute($password)
    {
        //Decrypt clien password.
        $RSA = RSA::loadPrivateKey(env('SECRET'));

        $password = $RSA->decrypt(base64_decode($password));

        return $password;
    }
}
