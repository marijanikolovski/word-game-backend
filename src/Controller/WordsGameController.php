<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WordsGameController extends AbstractController
{
    public function game(Request $request): JsonResponse
    {
        $dictionary = file('https://raw.githubusercontent.com/dwyl/english-words/master/words_alpha.txt');
        $allWords = array_map('trim', $dictionary);

        $term = $request->query->get('term');
        $word = trim(strtolower($term));

        if (strlen($word) === 0) {
            return new JsonResponse([
                'response' => 'You need to enter a word.'
            ]);
        }

        if (!in_array(($word), $allWords)) {
            return new JsonResponse([
                'response' => 'You got 0 points, the word was not found in the dictionary.'
            ]);
        }

        $points = null;

        $lettersPoints = strlen(count_chars($word, 3));

        $palindromePoints = $this->palindrome($word);

        $almostPalindromePoints = null;
        if (!$palindromePoints) {
            $almostPalindromePoints = $this->almostPalindrome($word);
        }

        $points += $lettersPoints + $palindromePoints + $almostPalindromePoints;


        return new JsonResponse(['points' => $points]);
    }

    private function palindrome($word)
    {
        if ($word === strrev($word)) {
            return 3;
        }

        return null;
    }

    private function almostPalindrome($word)
    {
        for ($i = 0; $i <= strlen($word); $i++) {
            $newWord = substr_replace($word, '', $i, 1);
            if ($newWord === strrev($newWord)) {
                return 2;
            }
        }

        return null;
    }
}
