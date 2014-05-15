<?php
////////////////////////////////////////////////
// (c) Copyright by Simon Kaspar              //
// Datei: kdf2funktion.php                    //
// All rights reserved by Simon Kaspar        //
////////////////////////////////////////////////

      function pbkdf2( $p, $s, $c = 5000, $kl = 32, $a = 'sha256' ) {
          $hl = strlen(hash($a, null, true)); # Hash length
          $kb = ceil($kl / $hl);              # Key blocks to compute
          $dk = '';                           # Derived key
          # Create key
          for ( $block = 1; $block <= $kb; $block ++ ) {
              # Initial hash for this block
              $ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);
              # Perform block iterations
              for ( $i = 1; $i < $c; $i ++ )
                  # XOR each iterate
                  $ib ^= ($b = hash_hmac($a, $b, $p, true));
              $dk .= $ib; # Append iterated block
          }

          # Return derived key of correct lengt
          $passwd = substr($dk, 0, $kl);
	  return base64_encode($passwd);
      }
?>
