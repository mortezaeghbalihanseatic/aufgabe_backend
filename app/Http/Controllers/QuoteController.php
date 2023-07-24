<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * @group Quotes
 *
 * APIs for managing quotes.
 */
class QuoteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/quotes",
     *     summary="Get and save quotes from The Simpsons Quote API",
     *     description="Retrieves quotes from an external API and saves them to the database.",
     *     tags={"Quotes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Quote")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function getAndSaveQuotes()
    {

        $count = Quote::count();

        $retriveCount = max(5 - $count, 1);
        $response = Http::withOptions(['verify' => false])->get('https://thesimpsonsquoteapi.glitch.me/quotes?count=' . $retriveCount);

        if ($response->ok()) {
            $quotes = $response->json();

            foreach ($quotes as $quoteData) {
                Quote::create([
                    'quote' => $quoteData['quote'],
                    'character' => $quoteData['character'],
                    'image_url' => $quoteData['image']
                ]);
            }
        }

        $lastFiveQuotes = Quote::orderBy('id', 'desc')->take(5)->get();

        $arraylist = array_map(function ($q) {
            return $q['id'];
        }, $lastFiveQuotes->toArray());

        $othersPreviousQQuotes = Quote::all()->whereNotIn('id', $arraylist);
        foreach ($othersPreviousQQuotes as $model) {
            $model->delete();
        }

        return response()->json($lastFiveQuotes);
    }
}
