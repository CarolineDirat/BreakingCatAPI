<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CardService implements CardServiceInterface
{       
   /**
    * create
    *
    * @param  string $imageContent
    * @param  array<string,string> $quote ["quote" : string, "author" : string]
    * @return string
    */
   public function createContent(string $imageContent, array $quote): string
   {
      $card = $this->createResource($imageContent, $quote);

      ob_start();
      \imagejpeg($card);
      $content = ob_get_clean();
      \imagedestroy($card);  

      return $content;
   }
   
   /**
    * cardCreate
    *
    * @param  string                $imageContent
    * @param  array<string, string> $quote
    *
    * @return resource|false
    */
   private function createResource(string $imageContent, array $quote)
   {
      $image = \imagecreatefromstring($imageContent);

      $heigh = \imagesy($image);
      $width = \imagesx($image);

      $text = $quote['quote'];

      $charsArray = \str_split($text, 1);
      $charsTotalNb = \count($charsArray);
      $charsNbPerLine = (int) floor(($width - 50) / 10);
      $lineNb = (int) ceil($charsTotalNb / $charsNbPerLine);

      $text = \wordwrap($text, $charsNbPerLine, "\n", \false);
      $quoteHeigh = 60 + $lineNb * 25;
      
      $card = \imagecreatetruecolor($width, $heigh + $quoteHeigh);
      
      $black = \imagecolorallocate($card, 0, 0, 0);
      $white = \imagecolorallocate($card, 255, 255, 255);
      
      \imagefill($card, 0, 0, $black);
      \imagecopymerge($card, $image, 0, 0, 0, 0, $width, $heigh, 100);
      \imagedestroy($image);
      
      $font = __DIR__ . '/../../public/fonts/Averia_Serif_Libre.ttf';
      
      \imagettftext($card, 15, 0, 25, $heigh + 30, $white, $font, $text);
      
      $author = $quote['author'];
      if (empty($author)) {
        $author = 'Anonymous';
      }
      
      \imagettftext(
         $card,
         12,
         0,
         (int) $width/2,
         $heigh + 40 + $lineNb * 25,
         $white,
         $font,
         '-' . $author . '-'
         );

      for ($i = 0 ; $i < 7 ; $i++ ) { 
         \imageline($card, 0 + $i, 0 + $i, 0 + $i , \imagesy($card), $black);
         \imageline($card, 0, 0 + $i, \imagesx($card) , 0 + $i, $black);
         \imageline($card, \imagesx($card) - $i, 0, \imagesx($card) - $i, \imagesy($card), $black);
      }

      if (empty(\get_resource_type($card))) {
         throw new Exception("Error Processing Request in CardService::createCard()");
      }

      return $card;
   }
}
  