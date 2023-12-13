<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    protected Validator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new Validator();
    }

    /**
    * @test 
    */
    public function it_validates_username()
    {
        // it doesnt pass if username contains other characters than letters and numbers and is at least 5 characters long
        // $username = "";

        // it doesnt pass if username is less than 5 characters long
        // $username = "test";

        // it doesnt pass if username is longer than 20 characters long

        // it doesnt pass if username is empty

        // it doesnt pass if username contains only letters

        // it doesnt pass if username contains only numbers

        // it doesnt pass if username is already taken

        // it passes if username contains letters and numbers, is between 5 and 20 characters and is unique
    }

    /**
    * @test 
    */
    public function it_validates_email()
    {
        // it doesnt pass if email is empty

        // it doesnt pass if email has invalid format

        // it doesnt pass if email is already taken

        // it passes if email has valid format and is unique
    }

    /**
    * @test 
    */
    public function it_validates_password()
    {
        // it throws exception if there is no password_confirmation key in arguments

        // it doesnt pass if password and password_confirmation are not the same

        // it doesnt pass if password contains other characters than letters, numbers or these characters: `~!@#$%^&*()-_=+[{]};:'",<.>/?\|

        // it doesnt pass if password is less than 8 characters long

        // it doesnt pass if password is longer than 100 characters

        // it doesnt pass if password doesnt contain at least 1 uppercase letter

        // it doesnt pass if password doesnt contain at least 1 lowercase letter

        // it doesnt pass if password doesnt contain at least 1 number

        // it passes if password is between 8 and 100 characters long, contains 1 uppercase letter, 1 lowercase letter, 1 number and each of extra characters
    }

    /**
    * @test 
    */
    public function it_returns_json_response_based_on_email_availability()
    {
        // it returns json object

        // it returns json error message if email has invalid format

        // it returns json false message if email is already in use

        // ...
    }
}
