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

    /**
     * @param $key
     * @return mixed
     */
    private function getGoogleUrl($key)
    {
        return Google2FA::getQRCodeGoogleUrl(
            $this->name,
            $this->email,
            $key
        );
    }

    /**
     * @param $key
     * @return mixed
     */
    private function getInlineUrl($key)
    {
        return Google2FA::getQRCodeInline(
            $this->name,
            $this->email,
            $key
        );
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

    public function index()
    {
        $valid = $this->validateInput($key = $this->getSecretKey());

        $googleUrl = $this->getGoogleUrl($key);

        $inlineUrl = $this->getInlineUrl($key);

        return view('welcome')->with(compact('key', 'googleUrl', 'inlineUrl', 'valid'));
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
    private function validateInput($key)
    {
        // Get the code from input
        if (! $code = request()->get('code'))
        {
            return false;
        }

        // Verify the code
        return Google2FA::verifyKey($key, $code);
    }
}
