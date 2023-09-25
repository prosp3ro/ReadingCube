<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Exceptions\DatabaseQueryException;
use Src\Models\DB;
use Src\View;
use Throwable;

class IndexController
{
    private View $view;
    private DB $db;

    public function __construct(View $view, DB $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            try {
                $sql = "SELECT * FROM books";
                $statement = $this->db->prepare($sql);
                $statement->execute();
                $results = $statement->fetchAll();

                return $this->view->render("index", [
                    "books" => $results
                ]);
            } catch (Throwable $exception) {
                throw new DatabaseQueryException($exception->getMessage(), 0, $exception);
            }
        }

        header("Location: /login");
        exit();
    }

    public function captcha()
    {
        header('Content-Type: image/jpeg');

        $tla = glob("captcha_bcg/{*.jpg,*.jpeg}", GLOB_BRACE);
        $czcionki = glob("captcha_fonts/*.ttf");

        $znaki = 'ABCDEFGHIJKLMNPQRSTUWXYZ123456789';

        $obrazek_tla = $tla[array_rand($tla)];
        $liczba_znakow = rand(4, 6);

        $cap = imagecreatefromjpeg($obrazek_tla);

        $kolor = imagecolorallocate($cap, 250, 250, 250);
        $linie = imagecolorallocate($cap, 205, 205, 205);

        for ($x = 1; $x <= 50; $x++)        // powtarzamy 50 razy - rysujemy 50 linii
            imageline(                        // funkcja rysująca linię
                $cap,                            // uchwyt obrazka
                0,                               // współrzędna X początku linii
                rand(-100, imagesy($cap) + 100),    // współrzędna Y początku linii
                imagesx($cap),                   // współrzędna X końca linii
                rand(-100, imagesy($cap) + 100),    // współrzędna Y końca linii
                $linie                           // kolor linii
            );

        for ($x = 1; $x <= $liczba_znakow; $x++) {
            $czcionka = $czcionki[array_rand($czcionki)];
            $znak     = $znaki[rand(0, strlen($znaki) - 1)];

            $odleglosc_miedzy_znakami = (int) (round(imagesx($cap) / $liczba_znakow + 1) - 10) * ($x - 1) + 20;

            imagettftext(                      // funkcja pisząca tekst
                $cap,                             // uchwyt obrazka
                rand(20, 30),                     // rozmiar czcionki
                rand(-15, 15),                    // naczylenie znaku
                $odleglosc_miedzy_znakami,        // odległość między znakami
                rand(40, 60),                     // położenie względem górnej krawędzi obrazka
                $kolor,
                $czcionka,
                $znak
            );
        }

        imagejpeg($cap);
    }

    public function showAboutUsPage()
    {
        return $this->view->render("about-us", [
            "header" => "About Us | " . APP_NAME,
        ]);
    }

    public function showContactPage()
    {
        return $this->view->render("contact", [
            "header" => "Contact | " . APP_NAME,
        ]);
    }

    public function showFAQPage()
    {
        return $this->view->render("faq", [
            "header" => "FAQ | " . APP_NAME,
        ]);
    }
}
