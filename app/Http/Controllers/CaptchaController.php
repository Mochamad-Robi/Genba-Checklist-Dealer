<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function generate()
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captcha    = substr(str_shuffle($characters), 0, 6);

        session(['captcha' => $captcha]);
        session()->save();

        $width  = 220;
        $height = 70;
        $image  = imagecreatetruecolor($width, $height);

        // Background putih
        $bg = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, $width, $height, $bg);

        // Noise lines
        for ($i = 0; $i < 4; $i++) {
            $lc = imagecolorallocate($image, rand(200, 220), rand(200, 220), rand(200, 220));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lc);
        }

        // Noise dots
        for ($i = 0; $i < 50; $i++) {
            $dc = imagecolorallocate($image, rand(210, 240), rand(210, 240), rand(210, 240));
            imagesetpixel($image, rand(0, $width), rand(0, $height), $dc);
        }

        // Path font TTF — taruh file .ttf di public/fonts/captcha.ttf
        $fontPath = public_path('fonts/captcha.ttf');

        $textColors = [
            [180, 10,  30],
            [20,  80,  160],
            [30,  120, 50],
            [100, 20,  140],
            [20,  20,  20],
        ];

        $fontSize = 30; // besar & jelas
        $x        = 12;

        foreach (str_split($captcha) as $char) {
            [$r, $g, $b] = $textColors[array_rand($textColors)];
            $color = imagecolorallocate($image, $r, $g, $b);
            $angle = rand(-12, 12); // sedikit miring biar ada efek captcha
            $y     = rand(45, 55);  // posisi vertikal

            imagettftext($image, $fontSize, $angle, $x, $y, $color, $fontPath, $char);
            $x += 34;
        }

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return response($imageData, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function validateCaptcha(Request $request)
    {
        $inputCaptcha   = strtoupper(trim($request->input('captcha', '')));
        $sessionCaptcha = session('captcha');

        if (!$sessionCaptcha || $inputCaptcha !== $sessionCaptcha) {
            session()->forget('captcha');
            return response()->json([
                'success' => false,
                'message' => 'CAPTCHA salah, silakan coba lagi.',
            ], 422);
        }

        session()->forget('captcha');
        return response()->json([
            'success' => true,
            'message' => 'CAPTCHA valid.',
        ]);
    }
}