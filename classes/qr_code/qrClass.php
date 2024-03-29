<?php

/**
 * Savaş Can Altun - 30.01.2014
 * QR İmage Creator 
 * @see http://savascanaltun.com
 * @see http://saltun.net
 */


class qRClas {
    
    /**
     * $url = QR URL ( QR  Adresi ) 
     */

    public $url;
    
    /**
     * t = Text ( Yazı ) 
     * s = Size ( Boyut )
     * e = Charset ( Karakter seti )
     */

    function qRCreate($t,$s,$e=NULL) {
        
        if (empty($e)) {
            $e="UTF-8";
        }

        /**
         * Google chart API deprecated QR codes in January 2024.
         * Using the QR Code API.
         * @see https://goqr.me/api/doc/create-qr-code/
         */

        // $this->url="https://chart.googleapis.com/chart?cht=qr&chs=$s&chl=$t&choe=$e&chld=L|4";
        $this->url="http://api.qrserver.com/v1/create-qr-code/?data=$t&size=$s&charset-source=$e";
      
    }

    /**
     * u = Country Code ( Ülke Kodu )
     * a = Area Code ( Alan Kodu ) 
     * t = Phone Number ( Telefon Numarası )
     * s = Size ( boyut ) 
     */
    function telQr($u,$a,$t,$s){

        $this->url="http://api.qrserver.com/v1/create-qr-code/?data=TEL%3A".$u.$a."$t&size=$s";
     
     }

     function smsQr($u,$a,$t,$s,$m){

         $this->url="http://api.qrserver.com/v1/create-qr-code/?data=SMSTO%3A".$u.$a.$t."%3A".$m."&size=$s";
     
     }

}
