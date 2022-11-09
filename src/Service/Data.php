<?php

namespace App\Service;


class Data
{
    public function getData(): array
    {
        return [
            "brand" => ["Apple", "Samsung", "Asus", "Xiaomi", "Huawei", "Oppo", "Vivo", "Motorola", "Lenovo", "LG", "Nokia"],
            "operatingSystem" => ["Android", "IOS", "Tizen", "Linux"],
            "color" => ["Black", "Red", "Blue", "Yellow", "Grey", "Green"],
            "bluetooth" => ["true", "false"],
            "network" => ["5G", "4G", "3G"],
            "usb" => ["USB-A", "USB-B", "USB-C", "Micro-USB", "Mini-USB"],
            "screenSize" => ["7.60", "7.30", "7", "6.90", "6.80", "6.70", "6.62", "6.50", "5.71", "5.20"],
            "screenResolution" => ["828 X 1792", "1125 X 2436", "1242 X 2688", "1080 X 1920", "640 X 1136"],
            "internalMemory" => ["64Go", "128Go", "256Go"],
            "ramMemory" => ["4Go", "8Go", "16Go", "32Go"]
        ];
    }
}
