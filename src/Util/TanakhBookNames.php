<?php

namespace Moehrenzahn\ScriptureKit\Util;


use RuntimeException;

class TanakhBookNames
{
    const BOOK_NAMES = [
        1 => "Bereshit",
        2 => "Shemot",
        3 => "Vayikra",
        4 => "Bemidbar",
        5 => "Devarim",
        6 => "Yĕhôshúa",
        7 => "Shophtim",
        8 => "Rūth",
        9 => "1 Shmû’ēl",
        10 => "2 Shmû’ēl",
        11 => "M'lakhim I",
        12 => "M'lakhim II",
        13 => "Divrei ha-Yamim I",
        14 => "Divrei ha-Yamim II",
        15 => "‘Ezrā",
        16 => "‘Ezrā (Nehemiah)",
        17 => "Estēr",
        18 => "Iyyôbh",
        19 => "Tehillim",
        20 => "Mishlei",
        21 => "Qōheleth",
        22 => "Shīr Hashīrīm",
        23 => "Yĕsha‘ăyāhû",
        24 => "Yirmyāhû",
        25 => "Eikhah",
        26 => "Yĕkhezqiēl",
        27 => "Dānî'ēl",
        28 => "Hôshēa",
        29 => "Yô’ēl",
        30 => "‘Āmôs",
        31 => "Ōvadhyāh",
        32 => "Yônāh",
        33 => "Mîkhāh",
        34 => "Nakḥûm",
        35 => "Khăvhakûk",
        36 => "Tsĕphanyāh",
        37 => "Khaggai",
        38 => "Zkharyāh",
        39 => "Mal’ākhî",
    ];

    /**
     * @param int $number
     * @return string
     */
    public static function getBookName(int $number): string
    {
        if (isset(self::BOOK_NAMES[$number])) {
            return self::BOOK_NAMES[$number];
        }
        throw new RuntimeException(sprintf('Book name for number %s could not be resolved.', $number));
    }
}
