<?php

function number_to_words_money($number) {
  $f = new \NumberFormatter("fr", \NumberFormatter::SPELLOUT);

  $parts = explode('.', number_format($number, 2, '.', ''));

  $dirhams = (int)$parts[0];
  $centimes = (int)$parts[1];

  $words = ucfirst($f->format($dirhams)) . ' dirham' . ($dirhams > 1 ? 's' : '');

  if ($centimes > 0) {
    $words .= ' et ' . $f->format($centimes) . ' centime' . ($centimes > 1 ? 's' : '');
  }

  return $words;
}
