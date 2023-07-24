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
class Quote2Controller extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/quote",
     *     summary="Get and save 1 quote from The Simpsons Quote API",
     *     description="Retrieves quote from an external API and saves it to the database.",
     *     tags={"Quotes Model 2"},
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
    public function getAndSave1Quote()
    {

        $retriveCount = 1;
        $response = Http::withOptions(['verify' => false])->get('https://thesimpsonsquoteapi.glitch.me/quotes');
        $quote = [];

        if ($response->ok()) {
            $quotes = $response->json();


            $quote =  Quote::create([
                'quote' => $quotes[0]['quote'],
                'character' => $quotes[0]['character'],
                'image_url' => $quotes[0]['image']
            ]);
        }

        Quote::find($quote->id - 5)->delete();

        return response()->json($quote);
    }


    /**
     * @OA\Get(
     *     path="/api/otherquotes",
     *     summary="Get and save quotes from The Simpsons Quote API",
     *     description="Retrieves quotes from an external API and saves them to the database.",
     *     tags={"Quotes Model 2"},
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
    public function get4Quote()
    {
        $lastFourQuotes = Quote::orderBy('id', 'desc')->take(4)->get();
        return response()->json($lastFourQuotes);
    }
}
