<?php

namespace App\Http\Controllers;

use Storage;
use Google2FA;

class Home extends Controller
{
    private $fileName = 'google2fasecret.key';

    private $name = 'PragmaRX';

    private $email = 'google2fa@pragmarx.com';

    private $secretKey;

    public function check2fa()
    {
        $isValid = $this->validateInput();

        // Render index and show the result
        return $this->index($isValid);
    }

    private function getSecretKey()
    {
        if (! $key = $this->getStoredKey())
        {
            $key = Google2FA::generateSecretKey();

            $this->storeKey($key);
        }

        return $key;
    }

    /**
     * @return mixed
     */
    private function getStoredKey()
    {
        // No need to read it from disk it again if we already have it
        if ($this->secretKey)
        {
            return $this->secretKey;
        }

        if (! Storage::exists($this->fileName))
        {
            return null;
        }

        return Storage::get($this->fileName);
    }

    public function index($valid = false)
    {
        $key = $this->getSecretKey();

        $url = Google2FA::getQRCodeGoogleUrl(
            $this->name,
            $this->email,
            $key
        );

        return view('welcome')->with(compact('key', 'url', 'valid'));
    }

    /**
     * @param $key
     */
    private function storeKey($key)
    {
        Storage::put($this->fileName, $key);
    }

    /**
     * @return mixed
     */
    private function validateInput()
    {
        // Get the code from input
        $code = request()->get('code');

        // Get our secret key
        $key = $this->getSecretKey();

        // Verify the code
        $isValid = Google2FA::verifyKey($key, $code);

        return $isValid;
    }
}
